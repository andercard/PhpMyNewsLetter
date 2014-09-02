<?PHP

if ($pmnl_partie == 'modele') {

	$newsletter = new pmnl_newsletter();

	if ($pmnl_id) {
		$newsletter->configurationAssocier(
			$conf->db_host,
			$conf->db_login,
			$conf->db_pass, 
			$conf->db_name,
			$pmnl_id,
			$conf->table_listsconfig
		);
	}

	/* deleting a newsletter*/
	if ($pmnl_service == "delete") {
		$deleted = pmnl_newsletter_supprimer(
			$conf->db_host,
			$conf->db_login,
			$conf->db_pass,
			$conf->db_name,
			$conf->table_listsconfig,
			$conf->table_archives,
			$conf->table_email,
			$conf->table_temp,
			$pmnl_id
		);
	}
	
	if ($pmnl_service == "delete") {
		if ($deleted) {
			$pmnl_controleur->messageAjouter('succes', translate("NEWSLETTER_DELETED"));
		} else {
			$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_DELETING_NEWSLETTER"));
		}
	}


	if ($pmnl_service == "create") {
	
		$entrees_config = array(
			'name' => array(
				'form_name' => 'name',
				'type' => 'string',
				'defaut' => false,
			),
			'from_name' => array(
				'form_name' => 'from_name',
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

		//
		$entrees = pmnl_entrees_filtrer($entrees_config);
		unset($entrees_config);
		
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
		
		// sélection des valeurs par défaut lors de la création d'une nouvelle Newsletter 
		if ($pmnl_etape == "1") {
			$PMNL_Form['name']['valeur'] = "";
			$PMNL_Form['from_name']['valeur'] = htmlspecialchars($conf->admin_name, ENT_QUOTES);
			$PMNL_Form['from_addr']['valeur'] = $conf->admin_email;
			$PMNL_Form['subject']['valeur'] = "";
			$PMNL_Form['header']['valeur'] = translate("NEWSLETTER_DEFAULT_HEADER");
			$PMNL_Form['footer']['valeur'] = translate("NEWSLETTER_DEFAULT_FOOTER");
			$PMNL_Form['subscription_subject']['valeur'] = htmlspecialchars(translate("NEWSLETTER_SUB_DEFAULT_SUBJECT"), ENT_QUOTES);
			$PMNL_Form['subscription_body']['valeur'] = translate("NEWSLETTER_SUB_DEFAULT_BODY");
			$PMNL_Form['welcome_subject']['valeur'] = htmlspecialchars(translate("NEWSLETTER_WELCOME_DEFAULT_SUBJECT"), ENT_QUOTES);
			$PMNL_Form['welcome_body']['valeur'] = translate("NEWSLETTER_WELCOME_DEFAULT_BODY");
			$PMNL_Form['quit_subject']['valeur'] = htmlspecialchars(translate("NEWSLETTER_UNSUB_DEFAULT_SUBJECT"), ENT_QUOTES);
			$PMNL_Form['quit_body']['valeur'] = translate("NEWSLETTER_UNSUB_DEFAULT_BODY");
			$PMNL_Form['submit']['valeur'] = htmlspecialchars(translate("NEWSLETTER_SAVE_NEW"), ENT_QUOTES);
		}
		
		/* adding a new newsletter */
		if ($pmnl_etape == "2") {
			$new_id = pmnl_newsletter_creer(
				$conf->db_host,
				$conf->db_login,
				$conf->db_pass, 
				$conf->db_name,
				$conf->table_listsconfig,
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
			if ($new_id > 0) {
				$pmnl_id = $new_id;
			}
		}
	}
}


if ($pmnl_partie == 'vue') {

	if ($pmnl_service == 'create') {

		if ($pmnl_etape == "1") {
		
			echo "<div class='subsection'>";
			echo "<div class='subtitle'>" . translate("NEWSLETTER_CREATE") . "</div>";
			echo "<div class='subcontent'>";
			
			echo "<form action='' method='post' class='form-light'>";
			echo "<input type='hidden' name='page' value='lettres' />";
			echo "<input type='hidden' name='action' value='create' />";
			echo "<input type='hidden' name='etape' value='2' />";
			
			require "include/newsletters_config.php";
			
			echo "</form>";
			
			echo "</div>";
			echo "</div>";
			
			echo "<br />";
			echo "<br />";
			
		}
		
		if ($pmnl_etape == "2") {
			if ($new_id) {
				echo "<div align='center 'class='success'>" . translate("NEWSLETTER_SETTINGS_CREATED")." . </div>";
			} else {
				echo "<div align='center' class='error'>" . translate("ERROR_SAVING_SETTINGS", " : <br />" . DbError()) . "</div>";
			}
		}
		
	} else {
	
		echo "<div class='subsection'>";
		echo "<div class='subtitle'>" . translate("NEWSLETTER_ACTION") . "</div>";
		echo "<div class='subcontent'>";
		
		echo "<form action='index.php' method='post' name='newsletter_list' class='form-light'>";
		echo "<input type='hidden' name='page' value='lettres' />";
		echo "<input type='hidden' name='action' value='create' />";
		echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
		echo "<input type='submit' value='" . htmlspecialchars(translate("NEWSLETTER_NEW"), ENT_QUOTES) . "' />";
		echo "</form>";
		
		echo "</div>";
		echo "</div>";
		
		echo "<br />";
		echo "<br />";
		
	}
}

?>