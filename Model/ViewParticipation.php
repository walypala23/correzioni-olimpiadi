<?php
	
	require_once "../Utilities.php";
	SuperRequire_once("General","sqlUtilities.php");
	SuperRequire_once("General", "TemplateCreation.php");
	SuperRequire_once("General", "PermissionManager.php");
	
	$db=OpenDbConnection();
	
	$ContestId=$_GET["ContestId"];
	$ContestantId=$_GET["ContestantId"];
	
	CheckPagePermission($db,$ContestId);
	
	//PermissionChecked
	
	$v_contest=OneResultQuery($db, QuerySelect('Contests', ['id'=>$ContestId]));
	$v_contestant=OneResultQuery($db, QuerySelect('Contestants', ['id'=>$ContestantId]));
	
	$problems=ManyResultQuery($db, QuerySelect('Problems', ['ContestId'=>$ContestId], null, 'name'));
	
	$v_corrections=[];
	
	foreach($problems as $pro){
		
		$nn=OneResultQuery($db, QuerySelect('Corrections', 
		['ProblemId'=>$pro['id'], 'ContestantId'=>$ContestantId], 
		['mark','comment','UserId']
		));
		
		if (is_null($nn)) $nn['done']=false;
		else $nn['done']=true;
		
		$nn['problem']=OneResultQuery($db, QuerySelect('Problems', ['id'=>$pro['id']]));
		$nn['username']=OneResultQuery($db, QuerySelect('Users', ['id'=>$nn['UserId']], ['username']))['username'];
		
		$v_corrections[]= $nn;
	}
	
	
	
	$db->close();
	
	TemplatePage("ViewParticipation",[	'Index'=>'index.php',
										'Gare'=>'ViewContests.php',
										$v_contest['name']=>'ViewContestInformation.php?ContestId='.$ContestId,
										'Partecipanti'=>'ViewContestantsOfAContest.php?ContestId='.$ContestId,
										$v_contestant['name']." ".$v_contestant['surname']=>'ViewParticipation.php?ContestId='.$ContestId.'&ContestantId='.$ContestantId]);
?>
