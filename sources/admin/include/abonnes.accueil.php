<?PHP

// Abonnés

// Liste des inscrits
$subscribers = get_subscribers(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_email,
	$pmnl_id
);


// Liste des inscrits n'ayant pas validé leur compte
$tmp_subscribers = get_subscribers(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_temp,
	$pmnl_id
);


?>