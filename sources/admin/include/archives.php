<?PHP

if ($pmnl_partie == 'modele') {

	switch ($pmnl_service) {
		case 'accueil':
			include('archives.accueil.php');
		break;
		
		case 'supprimer' :
			include('archives.supprimer.php');
		break;
		
		default :
			include('archives.accueil.php');
		break;
	}

}

if ($pmnl_partie == 'vue') {

	switch ($pmnl_service) {
		case 'accueil':
			include('archives.accueil.html.php');
		break;
		
		case 'supprimer' :
			include('archives.supprimer.html.php');
		break;
		
		default :
			include('archives.accueil.html.php');
		break;
	}

}

?>