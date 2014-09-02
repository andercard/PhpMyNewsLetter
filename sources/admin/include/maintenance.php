<?PHP

$table_temp_vider = flushTempTable(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_temp,
	$conf->validation_period
);

if ($table_temp_vider) {
	$pmnl_controleur->messageAjouter('informations', 'Table temporaire vidée');
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_FLUSHING_TEMP_TABLE", $conf->table_temp));
}

?>