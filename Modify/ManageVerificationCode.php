<?php
	require_once '../Utilities.php';
	SuperRequire_once('General', 'sqlUtilities.php');
	SuperRequire_once('Modify', 'ObjectSender.php');

	function SendCode($db, $ContestantEmail, $OldUser) {
		if (!filter_var($ContestantEmail, FILTER_VALIDATE_EMAIL)) {
			return ['type'=>'bad', 'text'=>'L\'email inserita non è valida'];
		}
		
		$row = OneResultQuery($db, QuerySelect('Contestants', ['email'=>$ContestantEmail]));
		
		if (is_null($row) and $OldUser) {
			return ['type'=>'bad', 'text'=>'L\'email inserita non è registrata su questo sito'];
		}
		
		if (!is_null($row) and !$OldUser) {
			return ['type'=>'bad', 'text'=>'L\'email è già usata da un partecipante'];
		}
		
		// Checking whether is passed enough time from last email sent with a code
		$row = OneResultQuery($db, QuerySelect('VerificationCodes', ['email'=>$ContestantEmail]));
		if (!is_null($row)) {
			$timestamp = new Datetime($row['timestamp']);
			// Sums 10 minutes to the timestamp
			$timestamp->add(new DateInterval('PT10M'));
			if (new Datetime('now') < $timestamp) {
				return ['type'=>'bad', 'text'=>'Prima di poter inviare un altro codice devono passare 10 minuti'];
			}
		}
		
		$code = GenerateRandomString();		
		
		
		// Send mail		
		$MailBody = 'Il codice di verifica che devi inserire sul sito <strong>Correzioni Olimpiadi</strong> è:<br>';
		$MailBody .= '<big>'.$code.'</big><br><br>';
		$MailBody .= 'p.s. Se non hai richiesto l\'invio del codice di verifica, ignora questa email.';
		
		$SendingSuccess = SendMail($ContestantEmail, 'Codice di verifica - Correzioni Olimpiadi', $MailBody);
		
		if (!$SendingSuccess) {
			return ['type'=>'bad', 'text'=>'Il codice non è stato inviato a causa di un errore nell\'invio della mail'];
		}
		
		Query($db, QueryDelete('VerificationCodes', ['email'=>$ContestantEmail]));
		
		$TimestampSql = (new Datetime('now'))->format('Y-m-d H:i:s');
		Query($db, QueryInsert('VerificationCodes', ['email'=>$ContestantEmail, 'code'=>$code, 'timestamp'=>$TimestampSql]));
		
		return ['type'=>'good', 'text'=>'Codice inviato con successo', 'data'=>['email'=>$ContestantEmail]];
	}

	function CheckCode($db, $ContestantEmail, $code, $OldUser) {
		$row = OneResultQuery($db, QuerySelect('VerificationCodes', ['email'=>$ContestantEmail, 'code'=>$code]));
		if (is_null($row)) {
			return ['type'=>'bad', 'text'=>'Il codice di verifica non è corretto'];
		}
		
		$timestamp = new Datetime($row['timestamp']);
		$timestamp->add(new DateInterval('PT6H')); // sums 6 hours to the timestamp
		if (new Datetime('now') > $timestamp) {
			return ['type'=>'bad', 'text'=>'Il codice di verifica è scaduto'];
		}
		$data = ['email'=>$ContestantEmail, 'code'=>$code, 'OldUser'=>false];
		if ($OldUser) {
			$row = OneResultQuery($db, QuerySelect('Contestants', ['email'=>$ContestantEmail]));
			if (is_null($row)) {
				return ['type'=>'bad', 'text'=>'Non ci sono i dati relativi a tale email'];
			}
			
			// Calculation of SchoolYear
			$LastOlympicYear = $row['LastOlympicYear'];
			$NowTime = new Datetime('now');
			$CurrentYear = intval($NowTime->format('Y'));
			$CurrentMonth = intval($NowTime->format('m'));
			$SchoolYear = $CurrentYear + (5 - $LastOlympicYear) + (($CurrentMonth >= 6)?1:0);
			
			$data = [
				'name'=>$row['name'],
				'surname'=>$row['surname'],
				'school'=>$row['school'],
				'SchoolCity'=>$row['SchoolCity'],
				'email'=>$row['email'],
				'SchoolYear'=>$SchoolYear,
				'code'=>$code,
				'OldUser'=>true
			];
		}
			
		return ['type'=>'good', 'text'=>'Il codice di verifica è corretto', 'data'=>$data];
	}
	
	$db= OpenDbConnection();
	$data = json_decode($_POST['data'], 1);
	
	if ($data['type'] == 'send') SendObject(SendCode($db, $data['email'], $data['OldUser']));
	else if ($data['type'] == 'check') SendObject(CheckCode($db, $data['email'], $data['code'], $data['OldUser']));
	else SendObject(['type'=>'bad', 'text'=>'L\'azione scelta non esiste']);
	
	$db->close();
