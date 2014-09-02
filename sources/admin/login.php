<?PHP

$pmnl_chemin = '../';
$pmnl_url = '../';

include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/variables.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");
include($pmnl_chemin . "include/interface.php");
include($pmnl_chemin . "include/lib/pmnl.lib.php");


$conf = new pmnl_configuration();
$conf->configurationAssocier(
	$hostname,
	$login,
	$pass,
	$database,
	$table_global_config
);

include($pmnl_chemin . "include/lang/" . $conf->language . ".php");

$error = (isset($_GET['error']) ? $_GET['error'] : 0);

html_header(translate("LOGIN_TITLE") , "../phpmynewsletter.css");

echo "<div class='subsection2'>";
echo "<div class='subtitle'>" . translate("LOGIN_TITLE") . "</div>";
echo "<div class='subcontent' align='center'>";

echo "<form action='index.php' method='post' class='form-light' name='loginform'>";
echo "<div class='info'>" . translate("LOGIN_PLEASE_ENTER_PASSWORD") . "</div>";
echo "<span class='field'>" . translate("LOGIN_PASSWORD") . ": </span>";
echo "<input type='password' name='form_pass' />";
echo "<br />";

if ($error == 1) {
	echo "<div align='center' class='error'>" . translate("LOGIN_BAD_PASSWORD") . " ! </div>";
} else {
	echo "<br />";
}

echo "<input type='submit' value='" . translate("LOGIN") . "' />";
echo "<input type='hidden' name='form' value='1' />";

echo "</form>";
echo "</div>";


echo "<script type='text/javascript'><!--
document.loginform.form_pass.focus();
//--></script>";

include("include/pagefooter.inc.php");

?>