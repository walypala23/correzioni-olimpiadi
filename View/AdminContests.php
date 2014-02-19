<?php
global $v_contests;
?>

<h2 class='pageTitle'>
	Gare
</h2>

<h3 class='pageSubtitle'>
	Lista delle gare
</h3>

<?php
if (empty($v_contests)) {
	?>
	<div class='emptyTable'> Ancora nessuna gara inserita. </div>
	<table class='InformationTable hiddenEmptyTable'>
	<?php
}
else {
?>
	<table class='InformationTable'>
	<?php
}?>
	<thead><tr>
		<th class='contestColumn'>Gara</th>
		<th class='dateColumn'>Data</th>
	</tr></thead>
	
	<tbody>
	<?php
		foreach($v_contests as $con) {
			?>
			<tr class='trlink'
			<?php if (!is_null($con['date']) ) {
				?>
				value='<?=$con['date']?>'
				<?php
			}
			else {
				?>
				value='0000-00-00'
				<?php
			}?>
			onclick=Redirect('AdminContestInformation',{contestId:<?=$con['id']?>})>
			<td class='contestColumn'><?=$con['name']?>
			<span class='blocked correctionsCompleted' >
			<?php
			if ($con['blocked']==1) {
				?>
				Correzioni terminate
				<?php
			}?>
			</span>
			</td>			
			<?php if (!is_null($con['date'])) { 
				?> 
				<td class='dateColumn'><?=getItalianDate($con['date'])?></td> 
				<?php 
			}
			else { 
				?>
				<td class='dateColumn'>-</td> 
				<?php 
			} ?>
			</tr>
			<?php
		}
	?>
	</tbody>
	
</table>

<h3 class="pageSubtitle">
	Aggiungi una gara
</h3>

<div class='createContainer'>
	<table>
	<tr>
		<th> Nome </th>
		<th> Data </th>
		<th> </th>
	</tr>
	<tr>
		<td> <input type="text" name="name" id="name_input"> </td>
		<td> 
		<input type="text" name="date" id="date_input" value="aaaa-mm-gg"> </td>
		<td> <input type="button" value="Aggiungi" onclick=AddContestRequest()> </td>
	</tr>
	</table>
</div>