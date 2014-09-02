<?PHP

$entrees_config = array(
'newsletter_name' => array(
	'form_name' => 'newsletter_name',
	'type' => 'string',
	'defaut' => false,
),
'from_name' => array(
	'form_name' => 'from_name',
	'type' => 'string',
	'defaut' => false,
),
'from' => array(
		'form_name' => 'from',
		'type' => 'string',
		'defaut' => false,
),
'subject' => array(
	'form_name' => 'subject',
	'type' => 'string',
	'defaut' => false,
),
'header' => array(
	'form_name' => 'header',
	'type' => 'string',
	'defaut' => false,
),
'footer' => array(
	'form_name' => 'footer',
	'type' => 'string',
	'defaut' => false,
),
'subscription_subject' => array(
	'form_name' => 'subscription_subject',
	'type' => 'string',
	'defaut' => false,
),
'subscription_body' => array(
	'form_name' => 'subscription_body',
	'type' => 'string',
	'defaut' => false,
),
'welcome_subject' => array(
	'form_name' => 'welcome_subject',
	'type' => 'string',
	'defaut' => false,
),
'welcome_body' => array(
	'form_name' => 'welcome_body',
	'type' => 'string',
	'defaut' => false,
),
'quit_subject' => array(
	'form_name' => 'quit_subject',
	'type' => 'string',
	'defaut' => false,
),
'quit_body' => array(
	'form_name' => 'quit_body',
	'type' => 'string',
	'defaut' => false,
),
);

$entrees = pmnl_entrees_filtrer($entrees_config);
unset($entrees_config);

/* saving the configuration */
$save = $newsletter->configurationEnregistrer(
	$_POST['list_id'],
	$entrees['newsletter_name'], 
	$entrees['from'],
	$entrees['from_name'], 
	$entrees['subject'],
	$entrees['header'],
	$entrees['footer'], 
	$entrees['subscription_subject'],
	$entrees['subscription_body'],
	$entrees['welcome_subject'],
	$entrees['welcome_body'], 
	$entrees['quit_subject'],
	$entrees['quit_body']
);

if ($save) {
	$newsletter->rafraichir();
	$pmnl_controleur->messageAjouter('succes', translate("NEWSLETTER_SETTINGS_SAVED"));
	$pmnl_controleur->pileAjouter('modele', 'newsletterconf');
	$pmnl_service = 'accueil';
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_SAVING_SETTINGS", " : <br />" . DbError()));
	$pmnl_service = 'accueil';
}

?>