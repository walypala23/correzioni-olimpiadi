<?php
require_once '../Utilities.php';
SuperRequire_once('General','SessionManager.php');
SuperRequire_once('General','sqlUtilities.php');
SuperRequire_once('General','PermissionManager.php');
SuperRequire_once('Modify','ObjectSender.php');
	
function AddContest($db, $name, $date) {
	if( !is_string( $name ) or strlen( $name )<=ContestName_MINLength or strlen( $name )>ContestName_MAXLength ) {
		return ['type'=>'bad', 'text'=>'Il nome della gara deve essere una stringa con un numero di caratteri compreso tra '.ContestName_MINLength.' e '.ContestName_MAXLength];
	}
	
	Query($db, QueryInsert('Contests',['name'=>$name, 'date'=>$date, 'blocked'=>0]));
	return ['type'=>'good', 'text'=>'La gara è stata creata con successo', 'data'=>[
	'ContestId'=>$db->insert_id, 'name'=>$name, 'date'=>$date] ] ;
}

function RemoveContest($db, $ContestId) {
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	
	Query($db, QueryDelete('Contests', ['id'=>$ContestId]));
	return ['type'=>'good', 'text'=>'La gara è stata eliminata con successo', 'ContestId'=>$ContestId];
}

function BlockContest($db, $ContestId) {
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	if( $Exist1['blocked']==1 ) {
		return ['type'=>'bad', 'text'=>'La gara scelta è già bloccata'];
	}
	
	Query($db, QueryUpdate('Contests', ['id'=>$ContestId], ['blocked'=>1]));
	return ['type'=>'good', 'text'=>'La gara è stata bloccata con successo'];
}

function UnblockContest($db, $ContestId) {
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	if( $Exist1['blocked']==0 ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non è bloccata'];
	}
	
	Query($db, QueryUpdate('Contests', ['id'=>$ContestId], ['blocked'=>0]));
	return ['type'=>'good', 'text'=>'La gara è stata sbloccata con successo'];
}

function ChangeName($db, $ContestId, $name) {
	if( !is_string( $name ) or strlen( $name )<=ContestName_MINLength or strlen( $name )>ContestName_MAXLength ) {
		return ['type'=>'bad', 'text'=>'Il nome della gara deve essere una stringa con un numero di caratteri compreso tra '.ContestName_MINLength.' e '.ContestName_MAXLength];
	}
	
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	
	Query( $db, QueryUpdate('Contests', ['id'=>$ContestId], ['name'=>$name]));
	return ['type'=>'good', 'text'=>'Il nome è stato cambiato con successo'];
}

function ChangeDate($db, $ContestId, $date) {
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	
	Query( $db, QueryUpdate('Contests', ['id'=>$ContestId], ['date'=>$date]));
	return ['type'=>'good', 'text'=>'La data è stata cambiata con successo'];
}

function ChangeNameAndDate($db, $ContestId, $name, $date) {
	if( !is_string( $name ) or strlen( $name )<=ContestName_MINLength or strlen( $name )>ContestName_MAXLength ) {
		return ['type'=>'bad', 'text'=>'Il nome della gara deve essere una stringa con un numero di caratteri compreso tra '.ContestName_MINLength.' e '.ContestName_MAXLength];
	}
	
	$Exist1=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	if( is_null($Exist1) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta non esiste'];
	}
	
	Query( $db, QueryUpdate('Contests', ['id'=>$ContestId], ['name'=>$name, 'date'=>$date]));
	return ['type'=>'good', 'text'=>'Il nome e la data sono stati cambiati con successo'];
}

function AddProblem($db, $ContestId, $name) {
	if( !is_string($name) or strlen( $name )>ProblemName_MAXLength or strlen( $name )==0) {
		return ['type'=>'bad', 'text'=>'Il nome del problema deve essere una stringa non vuota di al più '.ProblemName_MAXLength.' caratteri'];
	}
	
	$Exist1=OneResultQuery($db, QuerySelect('Problems', ['ContestId'=>$ContestId, 'name'=>$name] ));
	if( !is_null( $Exist1 ) ){
		return ['type'=>'bad', 'text'=>'La gara scelta ha già un problema con lo stesso nome'];
	}
	
	Query( $db, QueryInsert( 'Problems', ['ContestId'=>$ContestId, 'name'=>$name] ) );
	return ['type'=>'good', 'text'=>'Il problema è stato aggiunto con successo', 'ProblemId'=>$db->insert_id];
}

function RemoveProblem($db, $ProblemId) {
	$Exist1=OneResultQuery($db, QuerySelect('Problems', ['id'=>$ProblemId]));
	if( is_null( $Exist1 ) ) {
		return ['type'=>'bad', 'text'=>'Il problema non esiste'];
	}
	
	Query( $db, QueryDelete( 'Problems', ['id'=>$ProblemId]) );
	return ['type'=>'good', 'text'=>'Il problema è stato eliminato con successo', 'ProblemId'=>$ProblemId];
}

function ChangeProblemName( $db, $ProblemId, $name ){
	if( !is_string($name) or strlen( $name )>ProblemName_MAXLength or strlen( $name )==0) {
		return ['type'=>'bad', 'text'=>'Il nome del problema deve essere una stringa non vuota di al più '.ProblemName_MAXLength.' caratteri'];
	}
	
	$Problem=OneResultQuery($db, QuerySelect('Problems', ['id'=>$ProblemId]));
	if( is_null( $Problem ) ) {
		return ['type'=>'bad', 'text'=>'Il problema non esiste'];
	}
	
	$ContestId=$Problem['ContestId'];
	
	$Exist1=OneResultQuery($db, QuerySelect('Problems', ['ContestId'=>$ContestId, 'name'=>$name]));
	if( !is_null( $Exist1 ) ) {
		return ['type'=>'bad', 'text'=>'La gara scelta ha già un problema con questo nome'];
	}
	
	Query( $db, QueryUpdate('Problems', ['id'=>$ProblemId], ['name'=>$name]));
	return ['type'=>'good', 'text'=>'Il nome del problema è stato cambiato con successo'];
}

	
	
$db= OpenDbConnection();
if( IsAdmin( $db, GetUserIdBySession() ) == 0 ) {
	$db -> close();
	SendObject( ['type'=>'bad', 'text'=>'Non hai i permessi per gestire le gare o i problemi'] );
	die();
}

$data=json_decode( $_POST['data'] , 1);
if( $data['type'] == 'add' ) SendObject( AddContest( $db, $data['name'], $data['date'] ) );
else if( $data['type'] == 'remove' ) SendObject( RemoveContest( $db, $data['ContestId'] ) );
else if( $data['type'] == 'block' ) SendObject( BlockContest( $db, $data['ContestId'] ) );
else if( $data['type'] == 'unblock' ) SendObject( UnblockContest( $db, $data['ContestId'] ) );
else if( $data['type'] == 'ChangeName' ) SendObject( ChangeName( $db, $data['ContestId'] , $data['name']) );
else if( $data['type'] == 'ChangeDate' ) SendObject( ChangeDate( $db, $data['ContestId'] , $data['date']) );
else if( $data['type'] == 'ChangeNameAndDate' ) SendObject( ChangeNameAndDate( $db, $data['ContestId'] , $data['name'], $data['date']) );
else if( $data['type'] == 'AddProblem' ) SendObject( AddProblem( $db, $data['ContestId'] , $data['name']) );
else if( $data['type'] == 'RemoveProblem' ) SendObject( RemoveProblem( $db, $data['ProblemId'] ) );
else if( $data['type'] == 'ChangeProblemName' ) SendObject( ChangeProblemName( $db, $data['ProblemId'] , $data['name']) );
else SendObject( ['type'=>'bad', 'text'=>'L\'azione scelta non esiste'] );

$db -> close();
?>
