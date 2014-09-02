<?PHP

if ($pmnl_partie == 'modele') {

	//
	if ($pmnl_service == "save") {

		$smtp_host = (isset($_POST['smtp_host']) ? $_POST['smtp_host'] : '');
		$smtp_port = (isset($_POST['smtp_port']) ? $_POST['smtp_port'] : '');
		$smtp_auth = (isset($_POST['smtp_auth']) ? $_POST['smtp_auth'] : 0);
		$smtp_login = (isset($_POST['smtp_login']) ? $_POST['smtp_login'] : '');
		$smtp_pass = (isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '');
		$mod_sub = (isset($_POST['mod_sub']) ? $_POST['mod_sub'] : 0);
		
		if (is_numeric($smtp_port)) {
			$smtp_chaine = $smtp_host . ':' . $smtp_port;
		} else {
			$smtp_chaine = $smtp_host . ':25';
		}

		if ($conf->configuration_enregister(
			$_POST['db_host'],
			$_POST['db_login'],
			$_POST['db_pass'],
			$_POST['db_name'],
			$_POST['table_config'],
			$_POST['admin_pass'],
			50,
			$_POST['base_url'], 
			$_POST['path'],
			$_POST['language'],
			$_POST['table_email'], 
			$_POST['table_temp'],
			$_POST['table_listsconfig'], 
			$_POST['table_archives'],
			$_POST['sending_method'], 
			$smtp_chaine,
			$smtp_auth,
			$smtp_login, 
			$smtp_pass,
			$_POST['sending_limit'],
			$_POST['validation_period'], 
			$_POST['sub_validation'],
			$_POST['unsub_validation'], 
			$_POST['admin_email'],
			$_POST['admin_name'],
			$_POST['mod_sub'], 
			$_POST['table_sub'],
			$_POST['charset']
		)) {
			$configSaved = true;
		} else {
			$configSaved = false;
		}
		
		if ($_POST['file'] == 1) {
			$configFile = saveConfigFile(
				$_POST['db_host'],
				$_POST['db_login'],
				$_POST['db_pass'],
				$_POST['db_name'],
				$_POST['table_config']
			);
			$forceUpdate = 1;
			include($pmnl_chemin . "include/config.php");
			unset($forceUpdate);
		}
		
		if ($configSaved) {
			$pmnl_controleur->messageAjouter('succes', translate("GCONFIG_SUCCESSFULLY_SAVED"));
			if ($_POST['file'] == 1 && !$configFile) {
				$pmnl_controleur->messageAjouter('erreurs', translate("Error while writing config.php in include/ directory (check permissions please)"));
			}
		} else {
			if ($configFile == -1) {
				$pmnl_controleur->messageAjouter('erreurs', translate("Unable to write config.php in include/ directory (check permissions please)"));;
			} else if ($file == 1) {
				$pmnl_controleur->messageAjouter('erreurs', translate("Error while saving configuration"));
			}
		}
	}

	//Préparation des variables
	$PMNL_Form = array();
	/*
	$PMNL_Form[''] = array(
		'champ' => '',
		'valeur' => ''
	);
	*/

	// SGBD
	$PMNL_Form['sgbd_host'] = array(
		'champ' => translate("GCONFIG_DB_HOST"),
		'valeur' => htmlspecialchars($conf->db_host, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_name'] = array(
		'champ' => translate("GCONFIG_DB_DBNAME"),
		'valeur' => htmlspecialchars($conf->db_name, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_login'] = array(
		'champ' => translate("GCONFIG_DB_LOGIN"),
		'valeur' => htmlspecialchars($conf->db_login, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_pass'] = array(
		'champ' => translate("GCONFIG_DB_PASSWD"),
		'valeur' => htmlspecialchars($conf->db_pass, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_tables_config'] = array(
		'champ' => translate("GCONFIG_DB_CONFIG_TABLE"),
		'valeur' => htmlspecialchars($conf->db_config_table, ENT_QUOTES)
	);
	/*
	$PMNL_Form['sgbd_tables_utilisateurs'] = array(
		'champ' => translate("GCONFIG_SGBD_UTILISATEURS"),
		'valeur' => htmlspecialchars($conf->sgbd_utilisateurs, ENT_QUOTES)
	);
	*/
	$PMNL_Form['sgbd_tables_newsletters_email'] = array(
		'champ' => translate("GCONFIG_DB_TABLE_MAIL"),
		'valeur' => htmlspecialchars($conf->table_email, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_tables_newsletters_temp'] = array(
		'champ' => translate("GCONFIG_DB_TABLE_TEMPORARY"),
		'valeur' => htmlspecialchars($conf->table_temp, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_tables_newsletters_config'] = array(
		'champ' => translate("GCONFIG_DB_TABLE_NEWSCONFIG"),
		'valeur' => htmlspecialchars($conf->table_listsconfig, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_tables_newsletters_archives'] = array(
		'champ' => translate("GCONFIG_DB_TABLE_ARCHIVES"),
		'valeur' => htmlspecialchars($conf->table_archives, ENT_QUOTES)
	);
	$PMNL_Form['sgbd_tables_newsletters_inscriptions'] = array(
		'champ' => translate("GCONFIG_DB_TABLE_SUBMOD"),
		'valeur' => htmlspecialchars($conf->table_sub, ENT_QUOTES)
	);

	// APP
	/*
	$PMNL_Form['app_admin_email'] = array(
		'champ' => translate("GCONFIG_APP_ADMIN_EMAIL"),
		'valeur' => htmlspecialchars($conf->app_admin_email, ENT_QUOTES)
	);
	*/
	$PMNL_Form['app_admin_mdp'] = array(
		'champ' => translate("GCONFIG_MISC_ADMIN_PASSW"),
		'champ2' => translate("GCONFIG_MISC_ADMIN_PASSW2")
	);
	$PMNL_Form['app_expediteur_nom'] = array(
		'champ' => translate("GCONFIG_MESSAGE_ADMIN_NAME"),
		'valeur' => htmlspecialchars($conf->admin_name, ENT_QUOTES)
	);
	$PMNL_Form['app_expediteur_email'] = array(
		'champ' => translate("GCONFIG_MESSAGE_ADMIN_MAIL"),
		'valeur' => htmlspecialchars($conf->admin_email, ENT_QUOTES)
	);
	$PMNL_Form['app_encodage'] = array(
		'champ' => translate("GCONFIG_MESSAGE_CHARSET"),
		'valeur' => $conf->charset
	);
	$PMNL_Form['app_url'] = array(
		'champ' => translate("GCONFIG_MISC_BASE_URL"),
		'valeur' => $conf->base_url
	);
	$PMNL_Form['app_chemin'] = array(
		'champ' => translate("GCONFIG_MISC_BASE_PATH"),
		'valeur' => $conf->path
	);
	$PMNL_Form['app_langue'] = array(
		'champ' => translate("GCONFIG_MISC_LANGUAGE"),
		'valeur' => html_langues_options($pmnl_langues, $conf->language)
	);

	// ENVOIS
	$PMNL_Form['sending_limit'] = array(
		'champ' => translate("GCONFIG_MESSAGE_NUM_LOOP"),
		'valeur' => $conf->sending_limit
	);
	$PMNL_Form['quotas_heure'] = array(
		'champ' => 'Nombre de messages expédiés par heure',
		'valeur' => '50'
	);
	$PMNL_Form['quotas_jour'] = array(
		'champ' => 'Nombre de messages expédiés par jour',
		'valeur' => '100'
	);

	// SMTP
	$PMNL_Form['smtp_host'] = array(
		'champ' => translate("GCONFIG_MESSAGE_SMTP_HOST"),
		'valeur' => $conf->smtp_host
	);
	$PMNL_Form['smtp_port'] = array(
		'champ' => translate("GCONFIG_MESSAGE_SMTP_PORT"),
		'valeur' => $conf->smtp_port
	);
	$PMNL_Form['smtp_auth'] = array(
		'champ' => translate("GCONFIG_MESSAGE_SMTP_AUTH"),
		'valeur' => $conf->smtp_auth
	);
	$PMNL_Form['smtp_login'] = array(
		'champ' => translate("GCONFIG_MESSAGE_SMTP_LOGIN"),
		'valeur' => $conf->smtp_login
	);
	$PMNL_Form['smtp_password'] = array(
		'champ' => translate("GCONFIG_MESSAGE_SMTP_PASSWORD"),
		'valeur' => $conf->smtp_pass
	);

	// ABONNEMENTS
	$PMNL_Form['abonnements_inscription_confirmation'] = array(
		'champ' => translate("GCONFIG_SUBSCRIPTION_CONFIRM_SUB"),
		'valeur' => $conf->sub_validation
	);
	$PMNL_Form['abonnements_inscription_confirmation_delai'] = array(
		'champ' => translate(
			"GCONFIF_SUBSCRIPTION_VALIDATION_TIMEOUT",
			"<input type='text' name='validation_period' size='3' value='" . $conf->validation_period . "' />"
		),
	);
	$PMNL_Form['abonnements_desinscription_confirmation'] = array(
		'champ' => translate("GCONFIG_SUBSCRIPTION_CONFIRM_UNSUB"),
		'valeur' => $conf->unsub_validation
	);
}


if ($pmnl_partie == 'vue') {

	echo "<div class='title-simple'>" . translate("GCONFIG_TITLE") . "</div>";


	echo "<form method='post' name='global_config' class='form-simple'>";


	// Section CONFIGURATION SGBD
	$config_writable = is_writable("../include/config.php");

	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("GCONFIG_DB_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	if (!$config_writable) {
		echo "<div class='info'>" . translate("GCONFIG_DB_CONFIG_UNWRITABLE", $conf->path . "include/config.php") . "</div>";
		echo "<br />";
		
		echo "<input type='hidden' name='file' value='0' />";
		echo "<input type='hidden' name='db_host' value='" . $PMNL_Form['sgbd_host']['valeur'] . "' />";
		echo "<input type='hidden' name='db_name' value='" . $PMNL_Form['sgbd_name']['valeur'] . "' />";
		echo "<input type='hidden' name='db_login' value='" . $PMNL_Form['sgbd_login']['valeur'] . "' />";
		echo "<input type='hidden' name='db_pass' value='" . $PMNL_Form['sgbd_pass']['valeur'] . "' />";
		echo "<input type='hidden' name='table_config' value='" . $PMNL_Form['sgbd_tables_config']['valeur'] . "' />";

		echo "<table cellspacing='5'>";
		
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_host']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_host']['valeur'] . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_name']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_name']['valeur'] . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_login']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_login']['valeur'] . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_pass']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_pass']['valeur'] . "</td>";
		echo "</tr>";

		/*
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_tables_utilisateurs']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_tables_utilisateurs']['valeur'] . "</td>";
		echo "</tr>";
		*/
		
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_tables_config']['champ'] . "</td>";
		echo "<td>" . $PMNL_Form['sgbd_tables_config']['valeur'] . "</td>";
		echo "</tr>";
		
	} else {
		
		echo "<input type='hidden' name='file' value='1' />";
		
		echo "<table cellspacing='5'>";
		
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_host']['champ'] . "</td>";
		echo "<td><input type='text' name='db_host' value='" . $PMNL_Form['sgbd_host']['valeur'] . "' /></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_name']['champ'] . "</td>";
		echo "<td><input type='text' name='db_name' value='" . $PMNL_Form['sgbd_name']['valeur'] . "' /></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_login']['champ'] . "</td>";
		echo "<td><input type='text' name='db_login' value='" . $PMNL_Form['sgbd_login']['valeur'] . "' /></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_pass']['champ'] . "</td>";
		echo "<td><input type='password' name='db_pass' value='" . $PMNL_Form['sgbd_pass']['valeur'] . "' /></td>";
		echo "</tr>";

		/*
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_tables_utilisateurs']['champ'] . "</td>";
		echo "<td><input type='text' name='table_utilisateurs' value='" . $PMNL_Form['sgbd_tables_utilisateurs']['valeur'] . "' /></td>";
		echo "</tr>";
		*/
		
		echo "<tr>";
		echo "<td>" . $PMNL_Form['sgbd_tables_config']['champ'] . "</td>";
		echo "<td><input type='text' name='table_config' value='" . $PMNL_Form['sgbd_tables_config']['valeur'] . "' /></td>";
		echo "</tr>";
	}

	echo "<tr>";
	echo "<td colspan='2'>";
	echo "<br />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sgbd_tables_newsletters_email']['champ'] . "</td>";
	echo "<td><input type='text' name='table_email' value='" . $PMNL_Form['sgbd_tables_newsletters_email']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sgbd_tables_newsletters_temp']['champ'] . "</td>";
	echo "<td><input type='text' name='table_temp' value='" . $PMNL_Form['sgbd_tables_newsletters_temp']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sgbd_tables_newsletters_config']['champ'] . "</td>";
	echo "<td><input type='text' name='table_listsconfig' value='" . $PMNL_Form['sgbd_tables_newsletters_config']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sgbd_tables_newsletters_archives']['champ'] . "</td>";
	echo "<td><input type='text' name='table_archives' value='" . $PMNL_Form['sgbd_tables_newsletters_archives']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sgbd_tables_newsletters_inscriptions']['champ'] . "</td>";
	echo "<td><input type='text' name='table_sub' value='" . $PMNL_Form['sgbd_tables_newsletters_inscriptions']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "</table>";

	echo "</div>"; 
	echo "</div>";


	// Section CONFIGURATION ADMIN 
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("GCONFIG_MISC_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	/*
	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_admin_email']['champ'] . "</td>";
	echo "<td><input type='text' name='app_admin_email' value='" . $PMNL_Form['app_admin_email']['valeur'] . "' /></td>";
	echo "</td>";
	echo "</tr>";
	*/

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_admin_mdp']['champ'] . "</td>";
	echo "<td>";
	echo "<input type='password' name='admin_pass' value='' autocomplete='off' />";
	echo "<font style='font-size:x-small;'>" . $PMNL_Form['app_admin_mdp']['champ2'] . "</font>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_url']['champ'] . "</td>";
	echo "<td><input type='text' name='base_url' size='30' value='" . $PMNL_Form['app_url']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_chemin']['champ'] . "</td>";
	echo "<td><input type='text' name='path' size='30' value='" . $PMNL_Form['app_chemin']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_langue']['champ'] . "</td>";
	echo "<td>";
	echo "<select name='language'>";
	echo $PMNL_Form['app_langue']['valeur'];
	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "</table>";

	echo "</div>"; 
	echo "</div>";


	// Section 3
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>Configuration générale</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_expediteur_nom']['champ'] . "</td>";
	echo "<td><input type='text' name='admin_name' size='30' value='" . $PMNL_Form['app_expediteur_nom']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_expediteur_email']['champ'] . "</td>";
	echo "<td><input type='text' name='admin_email' size='30' value='" . $PMNL_Form['app_expediteur_email']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['app_encodage']['champ'] . "</td>";
	echo "<td>";
	echo "<select name='charset'>";
	foreach ($pmnl_locales as $local) {
		if ($PMNL_Form['app_encodage']['valeur'] == $local) {
			echo "<option value='" . $local . "' selected='selected'>" . $local . "</option>";
		} else {
			echo "<option value='" . $local . "'>" . $local . "</option>";
		}
	}
	echo "</tr>";

	echo "</table>";

	echo "</div>"; 
	echo "</div>";


	// Section GESTION DES ENVOIS
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("GCONFIG_MESSAGE_HANDLING_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td>" . translate("GCONFIG_MESSAGE_SEND_METHOD") . "</td>";
	echo "<td>";
	echo "<select name='sending_method'>";

	echo "<option value='smtp'";
	if ($conf->sending_method == "smtp") {
		echo " selected='selected' ";
	}
	echo ">";
	echo "STMP";
	echo "</option>";

	echo "<option value='php_mail'";
	if ($conf->sending_method == "php_mail") {
		echo " selected='selected'";
	}
	echo ">";
	echo translate("GCONFIG_MESSAGE_SEND_METHOD_FUNCTION");
	echo "</option>";

	/*
	echo "<option value='online_mail' ";
	if ($conf->sending_method=="online_mail") {
		echo "selected='selected'";
	}
	echo ">" . translate("INSTALL_PHP_MAIL_FONCTION_ONLINE") . "</option>";
	*/

	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['sending_limit']['champ'] . "</td>";
	echo "<td><input type='text' name='sending_limit' size='3' value='" . $PMNL_Form['sending_limit']['valeur'] . "' /></td>";
	echo "</tr>";

	/*
	echo "<tr>";
	echo "<td>" . $PMNL_Form['quotas_heure']['champ'] . "</td>";
	echo "<td><input type='text' name='quotas_heure' value='" . $PMNL_Form['quotas_heure']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['quotas_jour']['champ'] . "</td>";
	echo "<td><input type='text' name='quotas_jour' value='" . $PMNL_Form['quotas_jour']['valeur'] . "' /></td>";
	echo "</tr>";
	*/

	echo "</table>";

	echo "</div>"; 
	echo "</div>";


	// Section SMTP
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>SMTP</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['smtp_host']['champ'] . "</td>";
	echo "<td><input type='text' name='smtp_host' value='" . $PMNL_Form['smtp_host']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['smtp_port']['champ'] . "</td>";
	echo "<td><input type='text' name='smtp_port' value='" . $PMNL_Form['smtp_port']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['smtp_auth']['champ'] . "</td>";
	echo "<td>";

	echo "<input type='radio' class='radio' name='smtp_auth' value='0'";
	if ($PMNL_Form['smtp_auth']['valeur'] == 0) {
		echo " checked='checked'";
	}
	echo " />";

	echo translate("NO");

	echo "<span>&nbsp;</span>";

	echo "<input type='radio' class='radio' name='smtp_auth' value='1'";
	if ($PMNL_Form['smtp_auth']['valeur'] == 1) {
		echo " checked='checked'";
	}
	echo " />";

	echo translate("YES");

	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['smtp_login']['champ'] . "</td>";
	echo "<td><input type='text' name='smtp_login' value='" . $PMNL_Form['smtp_login']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['smtp_password']['champ'] . "</td>";
	echo "<td><input type='text' name='smtp_pass' value='" . $PMNL_Form['smtp_password']['valeur'] . "' /></td>";
	echo "</tr>";

	echo "</table>";

	echo "</div>";
	echo "</div>";


	// Section ABONNEMENTS
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("GCONFIG_SUBSCRIPTION_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td colspan='2'>";
	echo $PMNL_Form['abonnements_inscription_confirmation_delai']['champ'];
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['abonnements_inscription_confirmation']['champ'] . "</td>";
	echo "<td>";

	echo "<input type='radio' class='radio' name='sub_validation'  value='0'";
	if (!$PMNL_Form['abonnements_inscription_confirmation']['valeur']) {
		echo " checked='checked'"; 
	}
	echo " />";

	echo translate("NO");

	echo "<span>&nbsp;</span>";

	echo "<input type='radio' class='radio' name='sub_validation' value='1'";
	if ($PMNL_Form['abonnements_inscription_confirmation']['valeur']) {
		echo " checked='checked'";
	}
	echo " />";

	echo translate("YES");

	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . $PMNL_Form['abonnements_desinscription_confirmation']['champ'] . "</td>";
	echo "<td>";

	echo "<input type='radio' class='radio' name='unsub_validation' value='0'";
	if (!$PMNL_Form['abonnements_desinscription_confirmation']['valeur']) {
		echo " checked='checked'"; 
	}
	echo " />";

	echo translate("NO");

	echo "<span>&nbsp;</span>";

	echo "<input type='radio' class='radio' name='unsub_validation' value='1'";
	if ($PMNL_Form['abonnements_desinscription_confirmation']['valeur']) {
		echo " checked='checked'";
	}
	echo " />";

	echo translate("YES");

	echo "</td>";
	echo "</tr>";

	echo "</table>";

	echo "</div>"; 
	echo "</div>";


	echo "<br />";
	echo "<br />";

	echo "<center>";
	echo "<input type='hidden' name='action' value='save' />";
	echo "<input type='hidden' name='mod_sub' value='0' />";
	echo "<input type='submit' value='" . translate("GCONFIG_SAVE_BTN") . "' />";
	echo "</center>";

	echo "</form>";
}

?>