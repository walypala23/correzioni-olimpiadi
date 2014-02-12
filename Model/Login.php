<?php
require_once "../Utilities.php";

SuperRequire_once("General", "SessionManager.php");
SuperRequire_once("General", "AskInformation.php");
SuperRequire_once("General", "sqlUtilities.php");
SuperRequire_once("General", "TemplateCreation.php");


$SessionStatus=CheckSession();

if( CheckSession() == -1 ) {
	EndSession();
	$v_ErrorMessage="Your session is expired, login again.";
	TemplatePage("Login","Error",0);
	die();
}
else if( CheckSession() == 1 ) {
	SuperRedirect("Model","index.php");
	die();
}
else if ( CheckSession()==0 and !is_null($_POST["username"]) ) {
	$db=OpenDbConnection();
	$UserId=OneResultQuery($db, QuerySelect('Users',
	['username'=>$_POST['username'],'passHash'=>passwordHash( $_POST['password'] )],
	['id']
	) ) ['id'];
	
	$db->close();
	
	if( is_null($UserId) ) {
		$v_ErrorMessage="Incorrect username or password";
		TemplatePage("Login","Error",0);
	}
	else {
		StartSession($UserId,$_POST['username']);
		SuperRedirect("Model","index.php");
		die();
	}
}
else if( CheckSession()==0 ) TemplatePage("Login","",0);
?>
