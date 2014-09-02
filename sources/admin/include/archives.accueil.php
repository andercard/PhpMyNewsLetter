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

$newsletter_archive = $newsletter->getArchiveMsg($conf->table_archives, $msg_id);

$newsletter_archives_liste = $newsletter->getArchivesSelectList($conf->table_archives, $msg_id);

$newsletter_archives = $newsletter->archivesListe($conf->table_archives);

?>