<?PHP

// vérifie la validité de la newsletter
$list_name = false;
if ($pmnl_id) {
	$list_name = pmnl_newsletter_nom(
		$conf->db_host,
		$conf->db_login,
		$conf->db_pass,
		$conf->db_name,
		$conf->table_listsconfig,
		$pmnl_id
	);
	if (!$list_name) {
		$pmnl_id = false;
	}
}

if ($pmnl_id === false) {
	$pmnl_id = pmnl_newsletter_premiere(
		$conf->db_host,
		$conf->db_login,
		$conf->db_pass,
		$conf->db_name,
		$conf->table_listsconfig
	);
}

$newsletter = new pmnl_newsletter();
$newsletter->configurationAssocier(
		$conf->db_host,
		$conf->db_login,
		$conf->db_pass,
		$conf->db_name,
		$pmnl_id,
		$conf->table_listsconfig
);

$list_total_subscribers = $newsletter->abonnesCompter($conf->table_email);


$list = pmnl_newsletter_lister(
		$conf->db_host,
		$conf->db_login,
		$conf->db_pass,
		$conf->db_name,
		$conf->table_listsconfig
);

//no newsletter available, so let's configure the first one !
if (sizeof($list) == 0) {
	$pmnl_module = "newsletterconf";
	$pmnl_service = "create";
}

?>