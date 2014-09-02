<?PHP

include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");

function export_subscribers($hostname, $login ,$pass, $database, $table_email, $pmnl_id) {
	$db = new Db();
	$db->DbConnect($hostname, $login, $pass, $database);
	$db->DbQuery("SELECT email FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "'");
	echo $db->DbError();

	header("Content-disposition: filename=listing.txt");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");

	$client = getenv("HTTP_USER_AGENT");
	if (eregi("Win", $client)) {
		$crlf = "\r\n";
	} else {
		$crlf = "\n";
	}

	for ($i = 0; $i < $db->DbNumRows(); $i++) {
		$line = $db->DbNextRow();
		print $line[0]; 
		if ($i < ($db->DbNumRows()-1)) {
			print $crlf;
		}
	}
	
	exit();
}

$db_type = $_POST['db_type'];
$db_host = $_POST['db_host'];
$db_name = $_POST['db_name'];
$db_login = $_POST['db_login'];
$db_pass = $_POST['db_pass'];
$table_email = $_POST['table_email'];
$pmnl_id = $_POST['list_id'];

export_subscribers($db_host, $db_login, $db_pass, $db_name, $table_email, $pmnl_id);

?>