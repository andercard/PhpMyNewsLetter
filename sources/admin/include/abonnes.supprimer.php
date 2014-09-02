<?PHP

// Abonnés

$del_addr = (empty($_POST['del_addr']) ? "" : $_POST['del_addr']);
$deleted = delete_subscriber(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_email,
	$pmnl_id,
	$del_addr
);

if ($deleted) {
	$pmnl_controleur->messageAjouter('succes', translate("SUBSCRIBER_DELETED"));
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_DELETING_SUBSCRIBER", "<i>" . $del_addr . "</i>"));
}

?>