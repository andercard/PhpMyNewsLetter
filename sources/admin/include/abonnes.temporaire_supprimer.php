<?PHP

// Abonnés

$del_tmpaddr = (empty($_POST['del_tmpaddr']) ? "" : $_POST['del_tmpaddr']);
$deleted_temp = delete_subscriber(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_temp,
	$pmnl_id,
	$del_tmpaddr
);

if ($deleted_temp) {
	$pmnl_controleur->messageAjouter('succes', translate("SUBSCRIBER_TEMP_DELETED"));
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_DELETING_TEMP", "<i>" . $del_tmpaddr . "</i>"));
}

?>