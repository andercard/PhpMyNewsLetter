<?PHP

$pmnl_chemin = '../';
$pmnl_url = '../';

if (!file_exists($pmnl_chemin . "include/config.php")) {
	header("Location:" . $pmnl_url . "install.php");
	exit;
}

include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/variables.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");
include($pmnl_chemin . "include/interface.php");
include($pmnl_chemin . "include/lib/qor.lib.php");
include($pmnl_chemin . "include/lib/pmnl.lib.php");


$pmnl_controleur = new qor_controleur();
$pmnl_controleur->actionsDefinir($pmnl_modules);
$pmnl_controleur->cheminDefinir(realpath(null) . '/include/');


$entrees_config = array(
	'page' => array(
		'form_name' => 'page',
		'type' => 'string',
		'defaut' => false,
	),
	'action' => array(
		'form_name' => 'action',
		'type' => 'string',
		'defaut' => false,
	),
	'etape' => array(
		'form_name' => 'etape',
		'type' => 'string',
		'defaut' => '1',
	),
	'list_id' => array(
		'form_name' => 'list_id',
		'type' => 'integer',
		'defaut' => false,
	),
	'form_pass' => array(
		'form_name' => 'form_pass',
		'type' => 'string',
		'defaut' => false,
	),
	'op' => array(
		'form_name' => 'op',
		'type' => 'string',
		'defaut' => false,
	),
	'error_list' => array(
		'form_name' => 'error_list',
		'type' => 'string',
		'defaut' => false,
	),
);


// lecture des variables
$entrees = pmnl_entrees_filtrer($entrees_config);
unset($entrees_config);


// Initialisation des variables
$pmnl_module = $entrees['page'];
$pmnl_service = $entrees['action'];
$pmnl_etape = $entrees['etape'];
$pmnl_id = $entrees['list_id'];


// Anciennes variables
$form_pass = $entrees['form_pass'];
$error_list = $entrees['error_list'];


// vérification des variables
if (!isset($pmnl_modules[$pmnl_module])) {
	$pmnl_module = current($pmnl_modules);
}


// chargement de la configuration
$conf = new pmnl_configuration();
$r = $conf->configurationAssocier(
	$hostname,
	$login,
	$pass,
	$database,
	$table_global_config
);
if ($r != 'SUCCESS') {
	include($pmnl_chemin . "include/lang/english.php");
	echo "<div class='error'>" . translate($r) . "</div>";
	exit;
}	


//
if (empty($conf->language)) {
	$conf->language = "english";
}

include($pmnl_chemin . "include/lang/" . $conf->language . ".php");

/***  LOGIN CHECK ***/
if (!checkAdminAccess($conf->admin_pass, $form_pass)) {
	if (!empty($_POST['form']) && $_POST['form']) {
		header("Location:login.php?error=1");
	} else {
		header("Location:login.php");
	}
	exit;
}

$pmnl_controleur->pileAjouter('modele', 'commun');
$pmnl_controleur->pileAjouter('modele', 'maintenance');

switch ($pmnl_module) {
	case "archives" :
		$pmnl_controleur->pileAjouter('modele', 'archives');
		$pmnl_controleur->pileAjouter('vue', 'archives');
	break;

	case "config" :
		$pmnl_controleur->pileAjouter('modele', 'globalconf');
		$pmnl_controleur->pileAjouter('vue', 'globalconf');
	break;

	case "compose" :
		$pmnl_controleur->pileAjouter('modele', 'compose');
		$pmnl_controleur->pileAjouter('vue', 'compose');
	break;
	
	case "newsletterconf" :
		$pmnl_controleur->pileAjouter('modele', 'newsletterconf');
		$pmnl_controleur->pileAjouter('vue', 'newsletterconf');
	break;
	
	case "subscribers":
		$pmnl_controleur->pileAjouter('modele', 'subscribers');
		$pmnl_controleur->pileAjouter('vue', 'subscribers');
	break;
	
	case "lettres":
		$pmnl_controleur->pileAjouter('modele', 'lettres');
		$pmnl_controleur->pileAjouter('vue', 'lettres');
	break;
	
	default:
		$pmnl_module = "subscribers";
		$pmnl_controleur->pileAjouter('modele', 'subscribers');
		$pmnl_controleur->pileAjouter('vue', 'subscribers');
	break;
}

// MODELE
$pmnl_partie = 'modele';

while ($mvc_action = $pmnl_controleur->pileSuivant('modele')) {
	$mvc_document = $mvc_action['script'];
	if (file_exists($pmnl_controleur->chemin . $mvc_document)) {
		include($pmnl_controleur->chemin . $mvc_document);
	}
}

// VUE
$pmnl_partie = 'vue';

include("include/pageheader.inc.php");
include("include/page_infos.php");

while ($mvc_action = $pmnl_controleur->pileSuivant('vue')) {
	$mvc_document = $mvc_action['html'];
	if (file_exists($pmnl_controleur->chemin . $mvc_document)) {
		include($pmnl_controleur->chemin . $mvc_document);
	}
}

include("include/pagefooter.inc.php");

?>