<?php
global $v_admin, $v_corrections, $v_contestant, $v_contest, $v_MailSent, $v_SolutionsBoolean;
?>

<h2 class='PageTitle'>
	<span>
	<?=$v_contest['name']?>
	</span>
	<span>
	<?php 
	if (!is_null($v_contest['date'])) {?>
		- <?=GetItalianDate($v_contest['date'])?>
		<?php
	} ?>
	</span>
</h2>
<h3 class='PageSubtitle'>
	<span>
		<?=$v_contestant['surname']?> <?=$v_contestant['name']?> 
	</span>
	
	<span>
<?php
	if ($v_SolutionsBoolean) { ?>
		<a href='../Modify/DownloadFiles.php?type=ParticipationPdf&ContestId=<?=$v_contest['id']?>&ContestantId=<?=$v_contestant['id']?>' download class='DownloadIconSubtitle'><img src='../View/Images/DownloadPdf.png' alt='Scarica elaborato' title='Scarica elaborato'></a>
<?php } ?>
	</span>
	
	<span>
<?php	if ($v_admin == 1) {?>
			(<a id='CorrectionRecord' href="ViewParticipationTxt.php?ContestId=<?=$v_contest['id']?>&ContestantId=<?=$v_contestant['id']?>">verbale di correzione</a>
			<?php 
				if ($v_MailSent == 0) {
					InsertDom('buttons', ['buttons'=>[
						'GoodMail'=>['onclick'=>'SendGoodMail('.$v_contest['id'].', '.$v_contestant['id'].')'],
						'BadMail'=>['onclick'=>'SendBadMail('.$v_contest['id'].', '.$v_contestant['id'].')']
					]]); 
				}
				else {
					InsertDom('buttons', ['buttons'=>[
						'GoodRemail'=>['onclick'=>'SendGoodMail('.$v_contest['id'].', '.$v_contestant['id'].')'],
						'BadRemail'=>['onclick'=>'SendBadMail('.$v_contest['id'].', '.$v_contestant['id'].')']
					]]);
				}
			?>
			)
<?php  } ?>
	</span>
</h3>

<?php
$columns = [];
$columns[] = ['id'=>'problem', 'name'=>'Problema', 'class'=>['ProblemColumn'], 'order'=>1];
$columns[] = ['id'=>'mark', 'name'=>'Voto', 'class'=>['MarkColumn'], 'order'=>1, 'type'=>'number'];
$columns[] = ['id'=>'comment', 'name'=>'Commento', 'class'=>['CommentColumn']];
$columns[] = ['id'=>'user', 'name'=>'Correttore', 'class'=>['UsernameColumn']];

$rows = [];
foreach ($v_corrections as $correction) {
	$ProblemLink = '<a data-sort_name=\'' . $correction['problem']['name'] . '\' 
					class=\'ProblemLink\'
					href=\'ViewProblem.php?ContestId='.$v_contest['id'] . 
					'&ProblemId='.$correction['problem']['id'].'\'>';
	$row = ['values'=>[
		'problem'=>$ProblemLink . $correction['problem']['name'] . '</a>',
		'mark'=>($correction['mark']=='-1')?'∅':$correction['mark'],
		'comment'=>$correction['comment'],
		'user'=>$correction['username']
		], 'data'=>['problem_id'=>$correction['problem']['id'] ] ];
	$rows[]=$row;
}

$table = ['columns'=>$columns, 'rows'=>$rows, 'InitialOrder'=>['ColumnId'=>'problem'] ];
if ($v_contest['blocked'] == 0) {
	$buttons = [];
	$buttons['modify']=['onclick'=>'OnModification'];
	$buttons['confirm']=['onclick'=>'Confirm', 'hidden'=>true];
	$buttons['cancel']=['onclick'=>'Clear', 'hidden'=>true];
	$table['buttons']=$buttons;
}

InsertDom('table', $table);
?>


<script>
	var ContestantId = <?=$v_contestant['id']?>;
	
	function GetContestantId(row) {
		return ContestantId;
	}
	
	function GetProblemId(row) {
		return GetDataAttribute(row, 'problem_id');
	}
</script>
