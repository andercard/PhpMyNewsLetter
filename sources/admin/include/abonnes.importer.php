<?PHP

// Abonnés

$import_file = (!empty($_FILES['import_file']) ? $_FILES['import_file'] : "");
if (!empty($import_file) && $import_file != "none" && $import_file['size'] > 0 && is_uploaded_file($import_file['tmp_name'])) {
	$tmp_subdir_writable = 1;
	$open_basedir = @ini_get('open_basedir');
	if (!empty($open_basedir)) {
		$tmp_subdir = (DIRECTORY_SEPARATOR == "/" ? "./import/" : ".\\import\\");
		if (!is_writable($tmp_subdir)) {
			$tmp_subdir_writable = 0;
		} else {
			$local_filename = $tmp_subdir . basename($import_file['tmp_name']);
			move_uploaded_file($import_file['tmp_name'], $local_filename);
			$liste = fread(fopen($local_filename, "r"), filesize($local_filename));
			unlink($local_filename);
		}
	} else {
		$liste = fread(fopen($import_file['tmp_name'], "r"), filesize($import_file['tmp_name']));
	} 
	if ($tmp_subdir_writable) {
		$liste = ereg_replace("\n|\r|\n\r", "\n", $liste);
		$liste = explode("\n", $liste);

		for ($i = 0; $i < sizeof($liste); $i++) {
			/* Ajouter un nouvel enregistrement dans la table */
			$liste[$i] = trim($liste[$i]);
			if (!empty($liste[$i])) {
				$added = add_subscriber(
					$conf->db_host,
					$conf->db_login,
					$conf->db_pass,
					$conf->db_name,
					$conf->table_email,
					$pmnl_id,
					$liste[$i]
				);
				if ($added == -1) {
					$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_ALREADY_SUBSCRIBER", "<b>" . $liste[$i] . "</b>"));
				} elseif ($added == 1) {
					$pmnl_controleur->messageAjouter('succes', translate("SUBSCRIBER_ADDED", "<b>" . $liste[$i] . "</b>"));
				} elseif ($added == -2) {
					$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_SQL", DbError()));
				}
			} elseif ($i == 0) {
				//protection against trailing empty line
				$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_NO_EMAIL_IN_FILE"));
			}
		}
	} else {
		$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_IMPORT_TMPDIR_NOT_WRITABLE"));
	}
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_IMPORT_FILE_MISSING"));
}

?>