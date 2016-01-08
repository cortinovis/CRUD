function cambia_stato(quale)
{
	document.getElementById('stato').value=quale;
	document.getElementById('forma').submit();
}

function cambia_stato2(quale)
{
	document.getElementById('stato').value=quale;
}

function gestadm(utente){
	document.getElementById('campoutente').value=utente;
	document.getElementById('stato').value=14;
	document.getElementById('forma').submit();
}
