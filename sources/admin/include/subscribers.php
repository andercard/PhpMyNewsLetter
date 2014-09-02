<?PHP

if ($pmnl_partie == 'modele') {

	switch ($pmnl_service) {
		case 'accueil':
			include('abonnes.accueil.php');
		break;
		
		case 'ajouter':
			include('abonnes.ajouter.php');
		break;
		
		case 'supprimer' :
			include('abonnes.supprimer.php');
		break;
		
		case 'importer' :
			include('abonnes.importer.php');
		break;
		
		case 'exporter' :
			include('abonnes.exporter.php');
		break;
		
		case 'temporaire_supprimer' :
			include('abonnes.temporaire_supprimer.php');
		break;
		
		default :
			include('abonnes.accueil.php');
		break;
	}
}

if ($pmnl_partie == 'vue') {

	switch ($pmnl_service) {
		case 'accueil':
			include('abonnes.accueil.html.php');
		break;
	
		case 'ajouter':
			include('abonnes.ajouter.html.php');
		break;
		
		case 'supprimer' :
			include('abonnes.supprimer.html.php');
		break;
		
		case 'importer' :
			include('abonnes.importer.html.php');
		break;
		
		case 'exporter' :
			include('abonnes.exporter.html.php');
		break;
		
		case 'temporaire_supprimer' :
			include('abonnes.temporaire_supprimer.html.php');
		break;
		
		default :
			include('abonnes.accueil.html.php');
		break;
	}

}

?>