<?PHP

$pmnl_chemin = '../';
$pmnl_url = '../';

include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/variables.php");
include($pmnl_chemin . "include/interface.php");
include($pmnl_chemin . "include/lib/pmnl.lib.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");


$leave = leaveAdmin();
$conf = new pmnl_configuration();
$conf->configurationAssocier(
	$hostname,
	$login,
	$pass,
	$database,
	$table_global_config
);


include($pmnl_chemin . "include/lang/" . $conf->language . ".php");

html_header(translate("LOGOUT_TITLE") , "../phpmynewsletter.css");

echo "<div class='subsection2'>";
echo "<div class='subtitle'>" . translate("LOGOUT_TITLE") . "</div>";
echo "<div class='subcontent'>";

if ($leave) {
	echo "<div align='center' class='success'>" . translate("LOGOUT_DONE") . ".</div>";
} else {
	echo "<div align='center' class='error'>" . translate("LOGOUT_DONE") . "</div>";
}

echo "</div>";
echo "</div>";

echo "<br />";
echo "<br />";

echo "<div align='center'>";
echo "<img src='img/puce.gif' border='0' alt='*' />";
echo "<a href='index.php'>" . translate("LOGOUT_BACK") . "</a>";

include("include/pagefooter.inc.php");

?>
