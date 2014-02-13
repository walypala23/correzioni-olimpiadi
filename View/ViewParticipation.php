<?php
global $v_corrections, $v_contestant, $v_contest;
?>


<h2 class="pageTitle">
<?php
	echo $v_contest['name'];
?>
</h2>

<h3 class="pageSubtitle">
<?php
	echo $v_contestant['name']." ".$v_contestant['surname'];
?>
</h3>

<?php
if (empty($v_corrections)) {
	echo "<div class='emptyTable'> Ancora nessuna correzione. </div>";
}
else {
?>

<table class="InformationTable" id="participationTable">
	<thead><tr>
		<th class='problemColumn'>Problema</th>
		<th class='markColumn'>Voto</th>
		<th class='commentColumn'>Commento</th>
		<th class='userColumn'>Correttore</th>
		<th class='modifyColumn'></th>
	</tr></thead>
	
	<tbody>
	<?php
		foreach($v_corrections as $cor) {
			echo "<tr><td class='problemColumn'>".$cor['problem']."</td>";
			if ($cor["done"]) {
				echo "<td class='markColumn'>".$cor['mark']."</td>";
				echo "<td class='commentColumn'>".$cor['comment']."</td>";
				echo "<td class='userColumn'>".$cor['username']."</td>";
				echo "<td class='modifyColumn'> <a target='_blank' class='modifyButton' href=''>Modify</a> </td>";
			}
			else {
				echo "<td class='markColumn'>-</td><td class='commentColumn'>-</td><td class='userColumn'>-</td>";
				echo "<td class='modifyColumn'> <a target='_blank' class='modifyButton' href=''>Modify</a> </td>";
			}
			echo "</tr>";
		}
	?>
	</tbody>
	
</table>

<?php
}
?>
