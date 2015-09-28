function AddContest(response) {//TODO: Non va bene prendere i dati dal form, che intanto potrebbe essere cambiato
	if (response.type == 'good') {
		var name = response.data['name'];
		var date = response.data['date'];
		var ContestId = response.data['ContestId'];
		
		AddRow(document.getElementById('AdminContestsTable'), {
			redirect: {'ContestId': ContestId},
			values: {'name': name, 'blocked': '', 'date': date}
		}, 'date');
	}
}

function AddContestRequest(inputs) {
	var name = inputs.namedItem('name').value;
	var date = inputs.namedItem('date').value;
	MakeAjaxRequest('../Modify/ManageContest.php', {name: name, date: date, type: 'add'}, AddContest);
}
