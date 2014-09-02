<?PHP

// Archives

// Initialisation des variables
$msg_id = false;

// Lecture des variables
if (isset($_POST['msg_id'])) {
	$msg_id = (int) $_POST['msg_id'];
} else {
	if (isset($_GET['msg_id'])) {
		$msg_id = (int) $_GET['msg_id'];
	}
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

$newsletter_delete = $newsletter->deleteArchive($conf->table_archives, $msg_id);
if ($newsletter_delete) {
	$pmnl_controleur->messageAjouter('succes', translate("ARCHIVE_DELETED"));
} else {
	$pmnl_controleur->messageAjouter('erreurs', translate("ERROR_DELETING_ARCHIVE"));
}

$newsletter_archives_liste = $newsletter->getArchivesselectList($conf->table_archives, $msg_id);

?>