<?php
global $v_corrections, $v_contestant, $v_contest;
?>

<!--
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
	<span class='contestant_title'>
		<?=$v_contestant['surname']?> <?=$v_contestant['name']?>
	</span>
</h3>
-->

Caro/a <?=$v_contestant['name']?>, <br>
questo è il verbale di correzione dei tuoi esercizi: <br><br>

<?php
foreach ($v_corrections as $correction) {
	?>
	<u><?=$correction['problem']['name']?></u> 
	<?php
	if ($correction['done']) {?>
		<strong><?=($correction['mark'] == '-1')?'∅':$correction['mark']?></strong> [<kbd><?=$correction['username']?></kbd>] <?=$correction['comment']?>
	<?php }
	else {?>
		<strong>#</strong>
	<?php } ?>
	<br>
	<?php
}
?>
