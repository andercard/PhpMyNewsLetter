<?PHP

if ($pmnl_partie == 'modele') {

	switch ($pmnl_service) {
		case 'accueil':
			include('configuration.accueil.php');
		break;
		
		case 'enregistrer' :
			include('configuration.enregistrer.php');
		break;
		
		default :
			include('configuration.accueil.php');
		break;
	}

}

if ($pmnl_partie == 'vue') {

	switch ($pmnl_service) {
		case 'accueil':
			include('configuration.accueil.html.php');
		break;
		
		case 'enregistrer' :
			include('configuration.accueil.html.php');
		break;
		
		default :
			include('configuration.accueil.html.php');
		break;
	}

}

?>