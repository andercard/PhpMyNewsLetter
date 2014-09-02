<?PHP

include("include/config.php");
include("include/variables.php");
include("include/db/db_" . $db_type . ".inc.php");
include("include/interface.php");
include("include/lib/pmnl.lib.php");

$conf = new pmnl_configuration();
$conf->configurationAssocier(
	$hostname,
	$login,
	$pass,
	$database,
	$table_global_config
);

include("include/lang/" . $conf->language . ".php");

// Initialisation des variables
$pmnl_id = false;
$msg_id = false;

// Lecture des variables
if (isset($_POST['list_id'])) {
	$pmnl_id = (int) $_POST['list_id'];
} else {
	if (isset($_GET['list_id'])) {
		$pmnl_id = (int) $_GET['list_id'];
	}
}

if (isset($_POST['msg_id'])) {
	$msg_id = (int) $_POST['msg_id'];
} else {
	if (isset($_GET['msg_id'])) {
		$msg_id = (int) $_GET['msg_id'];
	}
}

$list = pmnl_newsletter_lister(
	$conf->db_host,
	$conf->db_login,
	$conf->db_pass,
	$conf->db_name,
	$conf->table_listsconfig
);

html_header(translate("ARCHIVE_TITLE"));

echo "<div class='content'>";

if ($list) {
	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("ARCHIVE_TITLE") . "</div>";
	echo "<div class='subcontent' align='center'>";
	
	echo "<form action='archives.php' method='post' name='archive_form' class='form-light'>";
	echo "<span>" . translate("ARCHIVE_CHOOSE") . " : </span>";
	echo "<select name='list_id'>";
	for ($i = 0; $i < sizeof($list); $i++) {
		echo "<option value='" . $list[$i]['list_id'] . "' ";
		if ($list[$i]['list_id'] == $pmnl_id) {
			echo "selected='selected' ";
		}
		echo ">" . $list[$i]['newsletter_name'] . "</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='OK' />";
	echo "</form>";

	if ($pmnl_id) {

		$newsletter = new pmnl_newsletter();
		$newsletter->configurationAssocier(
			$conf->db_host,
			$conf->db_login,
			$conf->db_pass,
			$conf->db_name,
			$pmnl_id,
			$conf->table_listsconfig
		);
		
		$newsletter_messages = $newsletter->getArchivesSelectList($conf->table_archives, $msg_id);
		if ($newsletter_messages) {
			echo "<form action='archives.php' method='post' name='archive_form2' class='form-light'>";
			echo $newsletter_messages;
			echo "<br />";
			echo "<br />";
			echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
			echo "<input type='submit' value='" . translate("ARCHIVE_DISPLAY") . "' />";
			echo "</form>";
		} else {
			echo "<div align='center' class='error'>" . translate("ARCHIVE_NOT_FOUND") . "</div>";
		}
		
		echo "</div>";
		echo "</div>";

		if ($msg_id) {
			$newsletter_archive = $newsletter->getArchiveMsg($conf->table_archives, $msg_id);
			if ($newsletter_archive) {
				echo $newsletter_archive;
			}
		}
	} else {
		echo "</div>";
		echo "</div>";
	}
}

echo "</div>";

html_footer();

?>