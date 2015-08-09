<h2 class='PageTitle'>
	Configurazioni del sito
</h2>
<h3 class='PageSubtitle'>
	Sola lettura
</h3>

<?php
$columns = [];
$columns[] = ['id'=>'name', 'name'=>'Impostazione', 'order'=>1];
$columns[] = ['id'=>'value', 'name'=>'Valore'];

$ConfigurationsNames = [
	'EmailSMTP',
	'EmailHost',
	// 'EmailPort', 
	'EmailUsername',
	// 'EmailPassword',
	'EmailAddress',
	// 'PHPMailerPath',
	 
	'SessionDuration',
	'ContestName_MAXLength',
	'ContestName_MINLength',
	'username_MAXLength',
	'username_MINLength',
	'password_MAXLength',
	'password_MINLength',
	'ContestantName_MAXLength',
	'ContestantSurname_MAXLength',
	'ContestantSchool_MAXLength',
	'ContestantEmail_MAXLength',
	'ProblemName_MAXLength',
	'comment_MAXLength'
];

$rows = [];
foreach ($ConfigurationsNames as $name) {
	$row = ['values'=>['name'=>$name, 'value'=>constant($name)]];
	$rows []= $row;
}

$table = ['columns'=>$columns, 'rows'=>$rows, 'id'=>'ConfigurationsTable'];

InsertDom('table',  $table);