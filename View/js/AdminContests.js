function AddContest(response){ //TODO: Non va bene prendere i dati dal form, che intanto potrebbe essere cambiato
	if (response.type=='good') {
		var tbodyEl=document.getElementsByClassName('InformationTableTbody')[0];
		var name=document.getElementById('NameInput').value;
		var date=document.getElementById('date_DateInput').value;
		
		AddRow( document.getElementById('AdminContestsTable'),{
			redirect:{'ContestId':response.ContestId},
			values:{'name':name, 'date':GetItalianDate(date), 'RawDate':date}
		}, 'RawDate');
	}
}

function AddContestRequest() {
	var name=document.getElementById('NameInput').value;
	var date=document.getElementById('date_DateInput').value;
	MakeAjaxRequest('../Modify/ManageContest.php', {name:name, date:date, type:'add'}, AddContest);
}
