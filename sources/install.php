<?PHP

// if pmnl is already installed, redirect to admin login 
if (file_exists('include/config.php')) {
	header('Location:./admin/index.php');
	exit;
}

include('./include/variables.php');
include('./include/interface.php');
include('./include/lib/pmnl.lib.php');


$pmnl_etapes = array(
	'langue' => 'langue',
	'base' => 'base',
	'general' => 'general',
	'enregistrement' => 'enregistrement'
);

$step = current($pmnl_etapes);
if (isset($_REQUEST['step'])) {
	if (array_key_exists($_REQUEST['step'], $pmnl_etapes)) {
		$step = $pmnl_etapes[$_REQUEST['step']];
	}
};

$langfile = current($pmnl_langues);
if (isset($_REQUEST['langfile'])) {
	if (array_key_exists($_REQUEST['langfile'], $pmnl_langues)) {
		$langfile = $_REQUEST['langfile'];
	}
};

$db_type = current($pmnl_sgbd);
if (isset($_REQUEST['db_type'])) {
	if (array_key_exists($_REQUEST['db_type'], $pmnl_sgbd)) {
		$db_type = $_REQUEST['db_type'];
	}
}

$op = (isset($_REQUEST['op']) ? $_REQUEST['op'] : false);


include_once('./include/db/db_' . $db_type . '.inc.php');
include_once('./include/lang/' . $langfile . '.php');

html_header(translate('PHPMYNEWSLETTER_TITLE'));

echo '<div align="center">';
echo '<img src="img/logo_phpmynewsletter.gif" alt="logo" border="0" />';
echo '<h3>' . translate('INSTALL_TITLE') . ' ' . $step . '/4</h3>';
echo '</div>';


if ($step == "langue") {

	echo "<div class='subsection2'>";
	echo "<div class='subtitle'>" . translate("INSTALL_LANGUAGE") . "</div>";
	echo "<div class='subcontent' align='center'>";
	echo "<form action='install.php' method='post' class='form-light'>";	
	echo "<span>" . translate("INSTALL_LANGUAGE_LABEL") . " : </span>";
	echo "<select name='langfile'>";
	foreach($pmnl_langues as $langue) {
		echo "<option value='" . $langue . "'>" . $langue . "</OPTION>";
	}
	echo "</select>";
	echo "<br />";
	echo "<br />";
	echo "<input type='hidden' name='step' value='base' />";
	echo "<input type='submit' value='" . translate("OK_BTN") . "' />";
	echo "</form>";
	echo "</div>";
	echo "</div>";
	
}


if ($step == "base") {

	echo "<div class='subsection2'>";
	echo "<div class='subtitle'>" . translate("INSTALL_DB_TYPE") . "</div>";
	echo "<div class='subcontent' align='center'>";
	echo "<form action='install.php' method='post' class='form-light'>";
	echo "<span>" . translate("INSTALL_DB_TYPE") . " : </span>";
	echo "<select name='db_type'>";
	foreach($pmnl_sgbd as $sgbd) {
		echo "<option value='" . $sbgd . "'>" . $sgbd . "</OPTION>";
	}
	echo "</select>";
	echo "<input type='hidden' name='langfile' value='" . $langfile . "' />";
	echo "<input type='hidden' name='step' value='general' />";
	echo "<input type='submit' value='OK ' />";
	echo "</form>";
	echo "</div>";
	echo "</div>";
	
}


if ($step == "general") {

	include_once("include/db/db_" . $db_type . ".inc.php");

	echo "<form method='post' name='global_config' action='install.php' class='form-simple'>";

	echo " <script language='javascript'>
	function checkSMTP() {
		 if(document.global_config.elements['sending_method'].selectedIndex!=0){
		  document.global_config.elements['smtp_host'].disabled = true;
		  document.global_config.elements.smtp_auth[0].disabled = true;
		  document.global_config.elements.smtp_auth[1].disabled = true;
		  document.global_config.elements['smtp_login'].disabled = true;
		  document.global_config.elements['smtp_pass'].disabled = true;

		} else {
		  document.global_config.elements['smtp_host'].disabled = false;
		  document.global_config.elements.smtp_auth[0].disabled = false;
		  document.global_config.elements.smtp_auth[1].disabled = false;
		  document.global_config.elements['smtp_login'].disabled = false;
		  document.global_config.elements['smtp_pass'].disabled = false;
		  }
	  } 
	</script>";


	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("INSTALL_DB_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";
	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_HOSTNAME") . "</td>";
	echo "<td>";
	echo "<input type='hidden' name='file' value='1' />";
	echo "<input type='text' name='db_host' value='' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_NAME") . "</td>";
	echo "<td>";
	echo "<input type='text' name='db_name' value='phpMyNewsletter' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_LOGIN") . "</td>";
	echo "<td>";
	echo "<input type='text' name='db_login' value='' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_PASS") . "</td>";
	echo "<td>";
	echo "<input type='password' name='db_pass' value='' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_TABLE_PREFIX") . "</td>";
	echo "<td>";
	echo "<input type='text' name='table_prefix' value='pmnl_' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_CREATE_DB") . "</td>";
	echo "<td>";
	echo "<input type='checkbox' name='createdb' value='1' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_DB_CREATE_TABLES") . "</td>";
	echo "<td>";
	echo "<input type='checkbox' name='createtables' value='1' checked='checked' />";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</div>";
	echo "</div>";

	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("INSTALL_GENERAL_SETTINGS") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_ADMIN_PASS") . "</td>";
	echo "<td>";
	echo "<input type='password' name='admin_pass' value='' />";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>" . translate("INSTALL_ADMIN_BASEURL") . "</td>";
	echo "<td>";
	echo "<input type='text' name='base_url' size='30' value='http://" . $_SERVER['HTTP_HOST'] . "/' />";
	echo "<br />";
	echo "(" . translate("EXAMPLE") . ": http://www.mydomain.com/";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>" . translate("INSTALL_ADMIN_PATH_TO_PMNL") . "</td>";
	echo "<td>";
	echo "<input type='text' name='path' size='30' value='' />";
	echo "<br />";
	echo "(" . translate("EXAMPLE") . " : tools/newsletter/)";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_LANGUAGE") . "</td>";
	echo "<td>";
	echo "<select name='language'>";
	echo html_langues_options($pmnl_langues);
	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_ADMIN_NAME") . "</td>";
	echo "<td>";
	echo "<input type='text' name='admin_name' size='30' value='Admin' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_ADMIN_EMAIL") . "</td>";
	echo "<td>";
	echo "<input type='text' name='admin_email' size='30' value='admin@mydomain.com' />";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	echo "</div>";

	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("INSTALL_MESSAGE_SENDING_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td colspan='2'>" . translate("INSTALL_MESSAGE_SENDING_LOOP", "<input type='text' name='sending_limit' size='3' value='50' />") . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_SENDING_METHOD") . "</td>";
	echo "<td>";
	echo "<select name='sending_method' onChange='checkSMTP()'>";
	echo "<option value='smtp'>smtp</option>";
	echo "<option value='php_mail' selected='selected'>" . translate("INSTALL_PHP_MAIL_FONCTION") . "</option>";
	echo "<option value='online_mail'>" . translate("INSTALL_PHP_MAIL_FONCTION_ONLINE") . "</option>";
	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_SMTP_HOST") . "</td>";
	echo "<td>";
	echo "<input type='text' name='smtp_host' value='' disabled='disabled' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_SMTP_AUTH_NEEDED") . "</td>";
	echo "<td>";
	echo "<input type='radio' name='smtp_auth' value='0' checked='checked' disabled='disabled' />";
	echo translate("NO") . "&nbsp;";
	echo "<input type='radio' name='smtp_auth' value='1' disabled='disabled' />";
	echo translate("YES");
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_SMTP_USERNAME") . "</td>";
	echo "<td>";
	echo "<input type='text' name='smtp_login' value='' disabled='disabled' />";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>" . translate("INSTALL_SMTP_PASSWORD") . "</td>";
	echo "<td>";
	echo "<input type='text' name='smtp_pass' value='' disabled='disabled' />";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</div>";
	echo "</div>";

	echo "<div class='subsection'>";
	echo "<div class='subtitle'>" . translate("GCONFIG_SUBSCRIPTION_TITLE") . "</div>";
	echo "<div class='subcontent'>";

	echo "<table cellspacing='5'>";

	echo "<tr>";
	echo "<td colspan='2'>" . translate("INSTALL_VALIDATION_PERIOD", "<input type='text' name='validation_period' size='3' value='6' />") . "</td>";

	echo "</tr>";

	echo "<tr>";
	echo "<td colspan='2'>";
	echo translate("INSTALL_SUB_CONFIRM");
	echo "<input type='radio' name='sub_validation'  value='0'>";
	echo translate("NO");
	echo "<input type='radio' name='sub_validation' value='1' checked='checked'>";
	echo translate("YES");
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td colspan='2'>";
	echo translate("INSTALL_UNSUB_CONFIRM");
	echo "<input type='radio' name='unsub_validation' value='0' />";
	echo translate("NO");
	echo "<input type='radio' name='unsub_validation' value='1' checked='checked' />";
	echo translate("YES");
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</div>";
	echo "</div>";

	echo "<br />";
	echo "<br />";
	
	echo "<input type='hidden' name='mod_sub' value='0' />";
	echo "<input type='hidden' name='op' value='saveConfig' />";
	echo "<input type='hidden' name='langfile' value='" . $langfile . "' />";
	echo "<input type='hidden' name='db_type' value='" . $db_type . "' />";
	echo "<input type='hidden' name='step' value='enregistrement' />";

	echo "<center>";
	echo "<input type='submit' value='" . translate("OK_BTN") . "' />";
	echo "</center>";
	echo "</form>";

}

	
if ($step == "enregistrement") {

	include("include/db/db_" . $db_type . ".inc.php");

	$createdb = (isset($_POST['createdb']) ? $_POST['createdb'] : 0);
	$createtables = (isset($_POST['createtables']) ? $_POST['createtables'] : 0);
	$smtp_host = (isset($_POST['smtp_host']) ? $_POST['smtp_host'] : "");
	$smtp_auth = (isset($_POST['smtp_auth']) ? $_POST['smtp_auth'] : 0);
	$smtp_login = (isset($_POST['smtp_login']) ? $_POST['smtp_login'] : "");
	$smtp_pass = (isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : "");
	$mod_sub = (isset($_POST['mod_sub']) ? $_POST['mod_sub'] : 0);

	$db_host = (isset($_POST['db_host']) ? $_POST['db_host'] : "");
	$db_login = (isset($_POST['db_login']) ? $_POST['db_login'] : "");
	$db_pass = (isset($_POST['db_pass']) ? $_POST['db_pass'] : "");
	$db_name = (isset($_POST['db_name']) ? $_POST['db_name'] : "");


	$table_prefix = (isset($_POST['table_prefix']) ? $_POST['table_prefix'] : "");
	$admin_pass = (isset($_POST['admin_pass']) ? $_POST['admin_pass'] : "");
	$base_url = (isset($_POST['base_url']) ? $_POST['base_url'] : "");
	$path = (isset($_POST['path']) ? $_POST['path'] : "");
	$sending_method = (isset($_POST['sending_method']) ? $_POST['sending_method'] : "");
	$language = (isset($_POST['language']) ? $_POST['language'] : "");
	$sending_limit = (isset($_POST['sending_limit']) ? $_POST['sending_limit'] : "");
	$validation_period = (isset($_POST['validation_period']) ? $_POST['validation_period'] : "");
	$sub_validation = (isset($_POST['sub_validation']) ? $_POST['sub_validation'] : "");
	$unsub_validation = (isset($_POST['unsub_validation']) ? $_POST['unsub_validation'] : "");
	$admin_email = (isset($_POST['admin_email']) ? $_POST['admin_email'] : "");
	$admin_name = (isset($_POST['admin_name']) ? $_POST['admin_name'] : "");
	$sub_validation = (isset($_POST['sub_validation']) ? $_POST['sub_validation'] : "");
	
	$sgbd_ok = false;
	
	// Test de connexion SGBD
	$db = new Db();
	$db->DbConnect($db_host, $db_login, $db_pass);
	if ($db->DbError()) {
		echo translate("ERROR_DBCONNECT", $db->DbError());
	} else {
		$sgbd_ok = true;
	}
	
	// Création de la base
	if ($sgbd_ok && ($createdb == 1)) {
		echo "<li>";
		echo translate("INSTALL_SAVE_CREATE_DB", $db_name);
		if (!$db->DbError()) {
			if($db->DbCreate($db_name)) {
				echo "OK";
			} else {
				echo translate("ERROR_SQL", $db->DbError() . ", Query:" . $sql);
				$sgbd_ok = false;
			}
		}
		echo "</li>";
	}
	
	// Test de connexion à la base choisie
	$db = new Db();
	$db->DbConnect($db_host, $db_login, $db_pass, $db_name);
	if ($db->DbError()) {
		echo translate("ERROR_DBCONNECT", $db->DbError());
	} else {
		$sgbd_ok = true;
	}

	// MySQL
	if ($sgbd_ok && ($db_type == "mysql")) {

		if ($createtables == 1) {

			$sql = 'CREATE TABLE IF NOT EXISTS `' . $table_prefix . 'archives` ('
			. ' `id` int(11) NOT NULL AUTO_INCREMENT,'
			. ' `date` datetime NOT NULL default "000-00-00 00:00:00",'
			. ' `type` text NOT NULL,'
			. ' `subject` text NOT NULL,'
			. ' `message` text NOT NULL,'
			. ' `list_id` int(11) NOT NULL,'
			. ' PRIMARY KEY (`id`)'
			. ' ) ENGINE = MyISAM';

			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "archives") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}

			
			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'email ('
				. ' `email` varchar(255) NOT NULL default "",'
				. ' `list_id` int(11) NOT NULL default "0",'
				. ' `hash` varchar(255) NOT NULL default "",'
				. ' KEY `hash` (`hash`)'
				. ' ) ENGINE = MyISAM';
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "email") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}

			
			$sql = ' CREATE TABLE IF NOT EXISTS `' . $table_prefix . 'config` ('
			. ' `admin_pass` varchar(64) NOT NULL default "",'
			. ' `archive_limit` varchar(64) NOT NULL default "",'
			. ' `base_url` varchar(64) NOT NULL default "",'
			. ' `path` varchar(64) NOT NULL default "",'
			. ' `sending_method` enum("smtp","php_mail","online_mail","nexen_mail","sendmail") NOT NULL default "smtp",'
			. ' `language` varchar(64) NOT NULL default "",'
			. ' `table_email` varchar(255) NOT NULL default "",'
			. ' `table_temp` varchar(255) NOT NULL default "",'
			. ' `table_listsconfig` varchar(255) NOT NULL default "",'
			. ' `table_archives` varchar(255) NOT NULL default "",'
			. ' `smtp_host` varchar(255) NOT NULL default "",'
			. ' `smtp_auth` enum("0","1") NOT NULL default "0",'
			. ' `smtp_login` varchar(255) NOT NULL default "",'
			. ' `smtp_pass` varchar(255) NOT NULL default "",'
			. ' `sending_limit` int(4) NOT NULL default "30",'
			. ' `validation_period` tinyint(4) NOT NULL default "0",'
			. ' `sub_validation` enum("0","1") NOT NULL default "1",'
			. ' `unsub_validation` enum("0","1") NOT NULL default "1",'
			. ' `admin_email` varchar(255) NOT NULL default "",'
			. ' `admin_name` varchar(255) NOT NULL default "",'
			. ' `mod_sub` enum("0","1") NOT NULL default "0",'
			. ' `mod_sub_table` varchar(255) NOT NULL default "",'
			. ' `charset` varchar(255) NOT NULL default "utf-8"'
			. ' ) ENGINE = MyISAM';

			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "config") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'listsconfig ('
			. ' `list_id` tinyint(4) NOT NULL auto_increment,'
			. ' `newsletter_name` varchar(255) NOT NULL default "",'
			. ' `from_addr` varchar(255) NOT NULL default "",'
			. ' `from_name` varchar(255) NOT NULL default "",'
			. ' `subject` varchar(255) NOT NULL default "",'
			. ' `header` text NOT NULL,'
			. ' `footer` text NOT NULL,'
			. ' `subscription_subject` varchar(255) NOT NULL default "",'
			. ' `subscription_body` text NOT NULL,'
			. ' `welcome_subject` varchar(255) NOT NULL default "",'
			. ' `welcome_body` text NOT NULL,'
			. ' `quit_subject` varchar(255) NOT NULL default "",'
			. ' `quit_body` text NOT NULL,'
			. ' PRIMARY KEY (`list_id`)'
			. ' ) ENGINE = MyISAM';
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "listconfig") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'sub ('
			. ' `email` varchar(255) NOT NULL default "",'
			. ' `list_id` varchar(64) NOT NULL default "",'
			. ' KEY `list_id` (`list_id`)'
			. ' ) ENGINE = MyISAM';
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "sub") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'temp ('
			. ' `email` varchar(255) NOT NULL default "",'
			. ' `list_id` varchar(64) NOT NULL default "",'
			. ' `hash` varchar(255) NOT NULL default "",'
			. ' `date` date NOT NULL default "0000-00-00"'
			. ' ) ENGINE = MyISAM';
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE", $table_prefix . "temp") . " : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}

		}
		
	}
	
	if ($sgbd_ok && ($db_type == "pgsql")) {

		if ($createtables == 1) {

			$sql = "CREATE TABLE " . $table_prefix . "archives (";
			$sql.= "id serial NOT NULL,";
			$sql.= "date date NOT NULL,";
			$sql.= "type varchar(4) NOT NULL default '',";
			$sql.= "subject text NOT NULL,";
			$sql.= "message text NOT NULL,";
			$sql.= "list_id serial NOT NULL,";
			$sql.= "PRIMARY KEY  (id)";
			$sql.= ")";

			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "archives : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = "CREATE TABLE " . $table_prefix . "email (";
			$sql.= "email varchar(255) NOT NULL default '',";
			$sql.= "list_id varchar(64) NOT NULL default '',";
			$sql.= "hash varchar(255) NOT NULL default ''";
			$sql.= ") ";
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "email : ";
		
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = "CREATE TABLE " . $table_prefix . "config (";
			$sql.= "admin_pass varchar(64) NOT NULL default '',";
			$sql.= "archive_limit varchar(64) NOT NULL default '',";
			$sql.= "base_url varchar(64) NOT NULL default '',";
			$sql.= "path varchar(64) NOT NULL default '',";
			$sql.= "sending_method varchar(64) NOT NULL default 'smtp',";
			$sql.= "language varchar(64) NOT NULL default '',";
			$sql.= "table_email varchar(255) NOT NULL default '',";
			$sql.= "table_temp varchar(255) NOT NULL default '',";
			$sql.= "table_listsconfig varchar(255) NOT NULL default '',";
			$sql.= "table_archives varchar(255) NOT NULL default '',";
			$sql.= "smtp_host varchar(255) NOT NULL default '',";
			$sql.= "smtp_auth varchar(1) NOT NULL default '0',";
			$sql.= "smtp_login varchar(255) NOT NULL default '',";
			$sql.= "smtp_pass varchar(255) NOT NULL default '',";
			$sql.= "sending_limit integer NOT NULL default 30,";
			$sql.= "validation_period integer NOT NULL default 0,";
			$sql.= "sub_validation varchar(1) NOT NULL default '1',";
			$sql.= "unsub_validation varchar(1) NOT NULL default '1',";
			$sql.= "admin_email varchar(255) NOT NULL default '',";
			$sql.= "admin_name varchar(255) NOT NULL default '',";
			$sql.= "mod_sub varchar(1) NOT NULL default '0',";
			$sql.= "mod_sub_table varchar(255) NOT NULL default ''";
			$sql.= "charset varchar(255) NOT NULL default 'utf-8',";
			$sql.= ")";

			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "config : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = "CREATE TABLE " . $table_prefix . "listsconfig (";
			$sql.= "list_id serial NOT NULL ,";
			$sql.= "newsletter_name varchar(255) NOT NULL default '',";
			$sql.= "from_addr varchar(255) NOT NULL default '',";
			$sql.= "from_name varchar(255) NOT NULL default '',";
			$sql.= "subject varchar(255) NOT NULL default '',";
			$sql.= "header text NOT NULL,";
			$sql.= "footer text NOT NULL,";
			$sql.= "subscription_subject varchar(255) NOT NULL default '',";
			$sql.= "subscription_body text NOT NULL,";
			$sql.= "welcome_subject varchar(255) NOT NULL default '',";
			$sql.= "welcome_body text NOT NULL,";
			$sql.= "quit_subject varchar(255) NOT NULL default '',";
			$sql.= "quit_body text NOT NULL,";
			$sql.= "PRIMARY KEY  (list_id)";
			$sql.= ")";
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "listconfig : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}


			$sql = "CREATE TABLE " . $table_prefix . "sub (";
			$sql.= "email varchar(255) NOT NULL default '',";
			$sql.= "list_id varchar(64) NOT NULL default ''";
			$sql.= ")";
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "sub : ";
			
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}

			
			$sql = "CREATE TABLE " . $table_prefix . "temp (";
			$sql.= "email varchar(255) NOT NULL default '',";
			$sql.= "list_id varchar(64) NOT NULL default '',";
			$sql.= "hash varchar(255) NOT NULL default '',";
			$sql.= "date date NOT NULL";
			$sql.= ")";
			
			echo "<li>" . translate("INSTALL_SAVE_CREATE_TABLE") . " " . $table_prefix . "temp : ";
		
			if ($sgbd_ok) {
				$db->DbQuery($sql);
				if ($db->DbError()) {
					echo "</li>";
					echo "<div class='error'>";
					echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
					echo "</div>";
				} else {
					echo translate("DONE");
					echo "</li>";
				}
			}
		}
	}
  
	$db = new Db();
	$db->DbConnect($db_host, $db_login, $db_pass, $db_name);

	if (!get_magic_quotes_gpc()) {
		$table_prefix = escape_string($table_prefix);
		$admin_pass = escape_string($admin_pass);
		$base_url = escape_string($base_url);
		$path = escape_string($path);
		$smtp_host = escape_string($smtp_host);
		$smtp_login = escape_string($smtp_login);
		$smtp_pass = escape_string($smtp_pass);
		$sending_limit = escape_string($sending_limit);
		$validation_period = escape_string($validation_period);
		$sub_validation = escape_string($sub_validation);
		$unsub_validation = escape_string($unsub_validation);
		$admin_email = escape_string($admin_email);
		$admin_name = escape_string($admin_name);
		$mod_sub = escape_string($mod_sub);
	}	

	$admin_pass = md5($admin_pass);

	$sql ="INSERT INTO " . $table_prefix . "config VALUES (";  
	$sql.="'$admin_pass', '50', '$base_url', '$path', ";
	$sql.="'$sending_method', '$language', '" . $table_prefix . "email', '" . $table_prefix . "temp', ";
	$sql.="'" . $table_prefix . "listsconfig', '" . $table_prefix . "archives', '$smtp_host', '$smtp_auth', ";
	$sql.="'$smtp_login', '$smtp_pass', '$sending_limit', '$validation_period', ";
	$sql.="'$sub_validation', '$unsub_validation', '$admin_email', '$admin_name', ";
	$sql.=" '$mod_sub',  '" . $table_prefix . "sub', 'utf-8')";

	echo "<li>" . translate("INSTALL_SAVE_CONFIG") . ": ";
	
	$db->DbQuery($sql);
	if ($db->DbError()) {
		echo "</li>";
		echo "<div class='error'>";
		echo translate("ERROR_SQL", $db->DbError() . "<br / >Query:" . $sql);
		echo "</div>";
	} else {
		echo translate("DONE");
		echo "</li>";
	}

	echo "<li>" . translate("INSTALL_SAVE_CONFIG_FILE") . ":</li> ";

	$configfile = "<?PHP" . "\n";
	$configfile .= "if (!defined( '_CONFIG' )) {" . "\n";
	$configfile .= "\t" . "define('_CONFIG', 1);" . "\n";
	$configfile .= "\t" . '$db_type' . " = '" . $db_type . "';" . "\n";
	$configfile .= "\t" . '$hostname' . " = '" . $db_host . "';" . "\n";
	$configfile .= "\t" . '$login' . " = '" . $db_login . "';" . "\n";
	$configfile .= "\t" . '$pass' . " = '" . $db_pass . "';" . "\n";
	$configfile .= "\t" . '$table_global_config' . " = '" . $table_prefix . "config';" . "\n";
	$configfile .= "\t" . '$database' . " = '" . $db_name . "';" . "\n";
	$configfile .= "\t" . '$pmnl_version' . " = '0.8beta5';" . "\n";
	$configfile .= "}" . "\n";
	$configfile .= "?>"; 

	if (is_writable("include/")) {
		$fc = fopen("include/config.php", "w");
		$w = fwrite ($fc, $configfile ); 
		echo " OK";
	} else  {
		echo "<div class='error'>" . translate("INSTALL_UNABLE_TO_SAVE_CONFIG_FILE") . "</div>";
		echo translate("INSTALL_CONFIG_MANUALLY");
		echo"<TEXTAREA COLS='60' ROWS='18'>" . $configfile . "</TEXTAREA>";
	}

	echo "<br />";
	echo "<div align='center'>";
	echo "<img src='img/puce.png' alt='' />";
	echo "<a href='admin'>" . translate("INSTALL_FINISHED") . "</a>";
	echo "</div>";
}

echo "</td>";
echo "</tr>";

table_footer();
page_footer();
html_footer();

?>