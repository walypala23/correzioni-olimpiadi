<?php
global $v_contestants;
?>

<h2 class='PageTitle'>
	Partecipanti
</h2>

<h3 class='PageSubtitle'>
	Lista dei partecipanti
</h3>

<?php
if (empty($v_contestants)) {
	?>
	<div class='EmptyTable'> Ancora nessun partecipante inserito. </div>
	<table class='InformationTable hidden'>
	<?php
}
else {
?>
	<div class='EmptyTable hidden'> Ancora nessun partecipante inserito. </div>
	<table class='InformationTable'>
	<?php
}?>
	<thead><tr>
		<th class='SurnameColumn'>Cognome</th>
		<th class='NameColumn'>Nome</th>
	</tr></thead>
	
	<tbody>
	<?php
		foreach($v_contestants as $con) {
			?>
			<tr class='trlink' data-orderby='<?=$con['surname']?>' onclick=Redirect('AdminContestantInformation',{ContestantId:<?=$con['id']?>})>
			<td class='SurnameColumn'><?=$con['surname']?></td>
			<td class='NameColumn'><?=$con['name']?></td>
			</tr>
			<?php
		}
	?>
	</tbody>
	
</table>

<h3 class="PageSubtitle">
	Aggiungi un partecipante
</h3>

<div class='FormContainer'>
	<table>
	<tr>
		<th> Cognome </th>
		<th> Nome </th>
		<th> Scuola </th>
		<th> </th>
	</tr>
	<tr>
		<td> <input type="text" name="surname" id="surname_input"> </td>
		<td> <input type="text" name="name" id="name_input"> </td>
		<td> <input type="text" name="school" id="school_input"> </td>
		<td> <input type="button" value="Aggiungi" onclick=AddContestantRequest()> </td>
	</tr>
	</table>
</div>
