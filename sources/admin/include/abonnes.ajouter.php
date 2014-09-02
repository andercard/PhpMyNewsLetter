<?PHP

// Abonnés

$add_addr = (empty($_POST['add_addr']) ? "" : $_POST['add_addr']);
if (!empty($add_addr)) {
	$add_r = add_subscriber(
		$conf->db_host,
		$conf->db_login,
		$conf->db_pass,
		$conf->db_name,
		$conf->table_email,
		$pmnl_id,
		$add_addr
	);
	if ($add_r == 0) {
		$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_ADDING_SUBSCRIBER", " <b>" . $add_addr . "</b>"));
	} else if($add_r == -1) {
		$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_ALREADY_SUBSCRIBER", "<b>" . $add_addr . "</b>"));
	} else {
		$pmnl_controleur->messageAjouter('succes', translate("SUBSCRIBER_ADDED", "<b>" . $add_addr . "</b>"));
	}
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_SUPPLY_VALID_EMAIL"));
}

?>