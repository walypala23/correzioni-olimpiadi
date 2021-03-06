<?php
require_once '../Utilities.php';
SuperRequire_once('General', 'SessionManager.php');
SuperRequire_once('General', 'sqlUtilities.php');
SuperRequire_once('General', 'PermissionManager.php');
SuperRequire_once('Modify', 'ObjectSender.php');

function MakeCorrection($db, $ContestId, $ProblemId, $ContestantId, $mark, $comment) {
	if (is_null($ContestId)) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}

	if ((!is_null($mark)) and (!is_numeric($mark) or floatval($mark)<0.0 or 7.0<floatval($mark)) and ($mark != '-1')) {
		return ['type'=>'bad', 'text'=>'Il voto deve essere un numero compreso tra 0 e 7 o deve essere vuoto'];
	}
	else if (!is_null($mark)) $mark = floatval($mark);
	
	if (!is_string($comment)) {
		return ['type'=>'bad', 'text'=>'Il commento deve essere una stringa'];
	}
	
	$comment = htmlentities($comment, ENT_QUOTES); //Escaping user comment
	if (strlen($comment) > comment_MAXLength) {
		return ['type'=>'bad', 'text'=>'Il commento può avere al più '.comment_MAXLength.' caratteri, il tuo ne ha '.strlen($comment).' (i caratteri speciali valgono più degli altri)'];
	}
	
	$blocked = OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId], ['blocked']))['blocked'];
	if ($blocked == 1) {
		return ['type'=>'bad', 'text'=>'Le correzioni della gara scelta sono terminate'];
	}

	$permission = VerifyPermission($db, GetUserIdBySession(), $ContestId);
	if ($permission == 0) {
		return ['type'=>'bad', 'text'=>'Non hai i permessi per correggere questa gara'];
	}
	
	$ParticipationId = OneResultQuery ($db, QuerySelect('Participations', ['ContestId'=>$ContestId, 'ContestantId'=>$ContestantId]));
	if (is_null ($ParticipationId)) {
		return ['type'=>'bad', 'text'=>'Il partecipante selezionato non ha partecipato alla gara'];
	}

	$correction = OneResultQuery ($db, QuerySelect('Corrections', ['ProblemId'=>$ProblemId, 'ContestantId'=>$ContestantId]));

	if (is_null($correction)) {
		Query($db, QueryInsert('Corrections', 
		['ProblemId'=>$ProblemId, 'ContestantId'=>$ContestantId, 'mark'=>$mark, 'comment'=>$comment, 'UserId'=>GetUserIdBySession() ]));
	}

	else {
		if ($mark === $correction['mark']) Query($db, QueryUpdate('Corrections', ['id'=>$correction['id']], ['comment'=>$comment]));
		else Query($db, QueryUpdate('Corrections', ['id'=>$correction['id']], ['mark'=>$mark, 'comment'=>$comment, 'UserId'=>GetUserIdBySession() ]));
	}
	
	return ['type'=>'good', 'text'=>'Correzione salvata con successo'];
}

$data = json_decode($_POST['data'], 1);
$ContestantId = $data['ContestantId'];
$ProblemId = $data['ProblemId'];
$mark = $data['mark'];
$comment = $data['comment'];

$db = OpenDbConnection();
$ContestId = OneResultQuery($db, QuerySelect('Problems', ['id'=>$ProblemId], ['ContestId']))['ContestId'];

SendObject(MakeCorrection($db, $ContestId, $ProblemId, $ContestantId, $mark, $comment));

$db->close();
?>
