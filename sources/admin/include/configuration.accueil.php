<?PHP

//Préparation des variables à afficher
$PMNL_Form = array();
$PMNL_Form['name'] = array(
	'champ' => translate("NEWSLETTER_NAME"),
	'valeur' => htmlspecialchars($newsletter->name, ENT_QUOTES)
);
$PMNL_Form['from_name'] = array(
	'champ' => translate("NEWSLETTER_FROM_NAME"),
	'valeur' => htmlspecialchars($newsletter->from_name, ENT_QUOTES)
);
$PMNL_Form['from_addr'] = array(
	'champ' => translate("NEWSLETTER_FROM_ADDR"),
	'valeur' => $newsletter->from
);
$PMNL_Form['subject'] = array(
	'champ' => translate("NEWSLETTER_SUBJECT"),
	'valeur' => htmlspecialchars($newsletter->subject, ENT_QUOTES)
);
$PMNL_Form['header'] = array(
	'champ' => translate("NEWSLETTER_HEADER"),
	'valeur' => $newsletter->header
);
$PMNL_Form['footer'] = array(
	'champ' => translate("NEWSLETTER_FOOTER"),
	'valeur' => $newsletter->footer
);
$PMNL_Form['subscription_subject'] = array(
	'champ' => translate("NEWSLETTER_SUB_MSG_SUBJECT"),
	'valeur' => htmlspecialchars($newsletter->subscription_subject, ENT_QUOTES)
);
$PMNL_Form['subscription_body'] = array(
	'champ' => translate("NEWSLETTER_SUB_MSG_BODY"),
	'valeur' => $newsletter->subscription_body
);
$PMNL_Form['welcome_subject'] = array(
	'champ' => translate("NEWSLETTER_WELCOME_MSG_SUBJECT"),
	'valeur' => htmlspecialchars($newsletter->welcome_subject, ENT_QUOTES)
);
$PMNL_Form['welcome_body'] = array(
	'champ' => translate("NEWSLETTER_WELCOME_MSG_BODY"),
	'valeur' => $newsletter->welcome_body
);
$PMNL_Form['quit_subject'] = array(
	'champ' => translate("NEWSLETTER_UNSUB_MSG_SUBJECT"),
	'valeur' => htmlspecialchars($newsletter->quit_subject, ENT_QUOTES)
);
$PMNL_Form['quit_body'] = array(
	'champ' => translate("NEWSLETTER_UNSUB_MSG_BODY"),
	'valeur' => $newsletter->quit_body
);
$PMNL_Form['submit'] = array(
	'champ' => "",
	'valeur' => htmlspecialchars(translate("NEWSLETTER_SAVE_SETTINGS"), ENT_QUOTES)
);

?>