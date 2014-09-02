<?PHP

// a class for managing Newsletter

if (!defined( 'PMNL_LIB' )) {

	define('PMNL_LIB', 1);
	
	//
	function pmnl_variable_typer($variable, $type = false) {
		$sortie = false;
	
		switch($type) {
			case 'string' :
				$sortie = (string) $variable;
				break;
	
			case 'float' :
				$sortie = (float) $variable;
				break;
	
			case 'integer' :
				$sortie = (integer) $variable;
				break;
	
			default :
				$sortie = (integer) $variable;
				break;
		}
	
		return $sortie;
	}
	
	//
	function pmnl_entree_lire($nom, $defaut = false) {
		$sortie = $defaut;
	
		if (isset($_POST[$nom])) {
			$sortie = $_POST[$nom];
		} else {
			if (isset($_GET[$nom])) {
				$sortie = $_GET[$nom];
			}
		}
	
		return $sortie;
	}
	
	//
	function pmnl_entrees_filtrer($tableau) {
		$sortie = false;
	
		if (is_array($tableau)) {
			$sortie = array();
			foreach($tableau as $cle => $ligne) {
				$valeur_brute = pmnl_entree_lire($ligne['form_name'], $ligne['defaut']);
				$valeur = pmnl_variable_typer($valeur_brute, $ligne['type']);
				$sortie[$cle] = $valeur;
			}
		}
	
		return $sortie;
	}
	
	//
	function escape_string($string, $dbcon = false) {
		if (version_compare(phpversion(), "4.3.0") == -1) {
			return(mysql_escape_string($string));
		} else {
			return(mysql_real_escape_string($string));
		}
	}
	
	//
	function flushTempTable($host,$login, $pass, $database, $temp_table, $limit) {
		$sortie = 0;
	
		$date = date("Y/m/d");
		$elts = explode("/", $date);
		$y = $elts[0];
		$m = $elts[1];
		$d = $elts[2];
	
		$before = mktime(0, 0, 0, $m, $d - $limit, $y);
		$before = date("Ymd", $before);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql= "DELETE FROM " . $temp_table . " where date < '" . $before . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError() . ">" . $sql;
		} else {
			$sortie = 1;
		}
	
		return $sortie;
	}
	
	
	// Check if admin password is correct
	function checkAdminAccess($conf_pass, $admin_pass) {
		$sortie = 0;
	
		if (!empty($_COOKIE['PMNLNG_admin_password']) && ($_COOKIE['PMNLNG_admin_password'] == $conf_pass)) {
			$sortie = 1;
		} else {
			if ($conf_pass == md5($admin_pass)) {
				setcookie("PMNLNG_admin_password", md5($admin_pass));
				$sortie = 1;
			}
		}
	
		return $sortie;
	}
	
	//
	function leaveAdmin() {
		$sortie = 0;
	
		if (setcookie("PMNLNG_admin_password")) {
			$sortie = 1;
		}
	
		return $sortie;
	}
	
	//
	function saveConfigFile($db_host, $db_login, $db_pass, $db_name, $db_config_table, $db_type='mysql') {
		$sortie = -1;
	
		$configfile = "<?PHP\n";
		$configfile .= "if (!defined( \"_CONFIG\" ) || \$forceUpdate == 1 )\n\t{\n\n\t\tif (!defined( \"_CONFIG\" )) define(\"_CONFIG\", 1);";
		$configfile .= "\n\n\n\t\t$" . "db_type = \"" . $db_type . "\";";
		$configfile .= "\n\t\t$" . "hostname = \"" . $db_host . "\";";
		$configfile .= "\n\t\t$" . "login = \"" . $db_login . "\";";
		$configfile .= "\n\t\t$" . "pass = \"" . $db_pass . "\";";
		$configfile .= "\n\t\t$" . "database = \"" . $db_name . "\";";
		$configfile .= "\n\t\t$" . "table_global_config=\"" . $db_config_table . "\";";
		$configfile .= "\n\t\t$" . "pmnl_version =\"" . PMNL_VERSION . "\";\n\n\t}\n\n";
		$configfile .= "?>";
	
		if (is_writable("../include/config.php")) {
			$fc = fopen("../include/config.php", "w");
			$w = fwrite ($fc, $configfile );
			$sortie = 1;
		}
	
		return $sortie;
	}
	
	//
	function sendEmail($send_method, $to, $from, $from_name, $subject, $body, $smtp_auth = 0, $smtp_host = '',$smtp_port = '25', $smtp_login = '',$smtp_pass = '' , $charset = 'UTF-8') {
		$sortie = -2;
	
		$mail = new phpmailer();
		$mail->CharSet = $charset;;
		$mail->PluginDir = "include/lib/";
	
		switch ($send_method) {
			case "smtp":
				$mail->IsSMTP();
				$mail->Host = $smtp_host;
				$mail->Port = $smtp_port;
					
				if ($smtp_auth) {
					$mail->SMTPAuth = true;
					$mail->Username = $smtp_login;
					$mail->Password = $smtp_pass;
				}
				break;
	
			case "php_mail":
				$mail->IsMail();
				break;
	
			default:
				break;
	
		}
	
		$mail->From = $from;
		$mail->FromName = $from_name;
		$mail->Sender = $from;
	
		//get address
		$mail->AddAddress($to);
	
		// $mail->WordWrap = 50;
		$mail->Subject = $subject;
		$mail->Body =  $body;
	
		if(!$mail->Send()) {
			echo $mail->ErrorInfo;
		} else {
			$sortie = 1;
		}
	
		return $sortie;
	}
	
	//
	function isValidSubscriber($host, $login ,$pass, $database, $table_email, $pmnl_id, $email_addr) {
		$sortie = -1;
	
		$email_addr = strtolower($email_addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT hash FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $email_addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
		}
	
		$sub = $db->DbNumRows();
	
		if ($sub == 0) {
			$sortie = 0;
		} else {
			$h = $db->DBNextRow();
			$sortie = $h[0];
		}
	
		return $sortie;
	}
	
	//
	function isValidNewsletter($host, $login ,$pass, $database, $table_list, $pmnl_id) {
		$sortie = 0;
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT list_id FROM " . $table_list . " WHERE list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
		} else {
			$sortie = $db->DbNumRows();
		}
	
		return $sortie;
	}
	
	//
	function addSubscriberTemp($host, $login, $pass, $database, $table_email, $table_temp, $pmnl_id, $addr) {
		$sortie = -1;
	
		$addr = strtolower($addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
		}
	
		$mail = $db->DbNumRows();
	
		$sql = "SELECT email FROM " . $table_temp . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
		}
	
		$mail += $db->DbNumRows();
	
		if ($mail) {
			$sortie = 0;
		} else {
			$hash = unique_id();
			$date = date("Ymd");
			$sql = "INSERT INTO " . $table_temp . " ('email', 'list_id', 'hash', 'date') VALUES ('" . $addr . "', '" . $pmnl_id . "', '" . $hash . "' , '" . $date . "')";
			$db->DbQuery($sql);
			if ($db->DbError()) {
				echo $db->DbError();
			} else {
				$sortie = $hash;
			}
		}
	
		return $sortie;
	}
	
	//
	function addSubscriber($host, $login, $pass, $database, $table_email, $table_temp, $pmnl_id, $addr, $hash) {
		$addr = strtolower($addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email FROM " . $table_temp . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "' AND hash='" . $hash . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -1;
		}
	
		$add = $db->DbNumRows();
		if ($add == 0) {
			return -1;
		}
	
		$sql = "INSERT INTO " . $table_email . " ('email', 'list_id', 'hash') VALUES ('" . $addr . "', '" . $pmnl_id . "','" . $hash . "')";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -2;
		}
	
		$sql = "DELETE FROM " . $table_temp . " WHERE email='" . $addr . "' AND list_id='" . $pmnl_id . "' AND hash='" . $hash . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -3;
		}
	
		return 1;
	}
	
	//
	function addSubscriberDirect($host, $login, $pass, $database, $table_email, $pmnl_id, $addr) {
		$addr = strtolower($addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -1;
		}
	
		$add = $db->DbNumRows();
		if ($add) {
			return -1;
		}
	
		$hash = unique_id();
		$sql = "INSERT INTO " . $table_email . " ('email', 'list_id' , 'hash') VALUES ('" . $addr . "', '" . $pmnl_id . "', '" . $hash . "')";
		$db->DbQuery($sql);
		if($db->DbError()){
			echo $db->DbError();
			return -2;
		}
	
		return $hash;
	}
	
	//
	function removeSubscriber($host, $login, $pass, $database, $table_email, $pmnl_id, $addr, $hash) {
		$addr = strtolower($addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "' AND hash='" . $hash . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -1;
		}
	
		$rm = $db->DbNumRows();
		if ($rm == 0) {
			return -1;
		}
	
		$sql = "DELETE FROM " . $table_email . " WHERE email='" . $addr . "' AND list_id='" . $pmnl_id . "' AND hash='" . $hash . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -2;
		}
	
		return 1;
	}
	
	//
	function removeSubscriberDirect($host, $login, $pass,$database, $table_email, $pmnl_id, $addr) {
		$addr = strtolower($addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -1;
		}
	
		$rm = $db->DbNumRows();
		if ($rm == 0) {
			return -1;
		}
	
		$sql = "DELETE FROM " . $table_email . " WHERE email='" . $addr . "' AND list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -2;
		}
	
		return 1;
	}
	
	//
	function pmnl_newsletter_supprimer($host, $login, $pass, $database, $table_list, $table_archives, $table_email, $table_temp, $pmnl_id) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "DELETE FROM " . $table_list . " WHERE list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		}
	
		$sql = "DELETE FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		}
	
		$sql = "DELETE FROM " . $table_temp . " WHERE list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		}
	
		$sql = "DELETE FROM " . $table_archives . " WHERE list_id='" . $pmnl_id . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		}
	
		return 1;
	}
	
	//
	function pmnl_newsletter_creer($db_host, $db_login, $db_pass, $db_name, $table_listsconfig, $newsletter_name, $from, $from_name, $subject, $header, $footer, $subscription_subject, $subscription_body,$welcome_subject, $welcome_body, $quit_subject, $quit_body) {
		$db = new Db();
		$db->DbConnect($db_host, $db_login, $db_pass, $db_name);
	
		$sql = "SELECT list_id FROM " . $table_listsconfig . " ORDER BY list_id DESC";
		$db->DbQuery($sql);
		$id = $db->DbNextRow();
	
		$newid = $id[0] + 1;
	
		if (!get_magic_quotes_gpc()) {
			$newsletter_name = escape_string($newsletter_name);
			$from = escape_string($from);
			$from_name = escape_string($from_name);
			$subject = escape_string($subject);
			$header = escape_string($header);
			$footer = escape_string($footer);
			$subscription_subject = escape_string($subscription_subject);
			$subscription_body = escape_string($subscription_body);
			$welcome_subject = escape_string($welcome_subject);
			$welcome_body = escape_string($welcome_body);
			$quit_subject = escape_string($quit_subject);
			$quit_body = escape_string($quit_body);
		}
	
		$sql ="INSERT INTO " . $table_listsconfig . " ";
		$sql .= "(`list_id` , `newsletter_name` , `from_addr` , `from_name` , `subject` , `header` , `footer` , `subscription_subject` , `subscription_body`, `welcome_subject` , `welcome_body` , `quit_subject` ,`quit_body`) VALUES ";
		$sql .= "('$newid','$newsletter_name', '$from', '$from_name', '$subject', '$header', '$footer', '$subscription_subject', '$subscription_body', '$welcome_subject','$welcome_body', '$quit_subject', '$quit_body')";
	
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		} else {
			return $newid;
		}
	}
	
	//
	function save_message($host, $login ,$pass, $database, $table_archive, $subject, $format, $body, $date, $pmnl_id) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT id FROM " . $table_archive . " ORDER BY id DESC";
		$db->DbQuery($sql);
		$id = $db->DbNextRow();
	
		$newid = $id[0] + 1;
		$sql = "INSERT into " . $table_archive . " ('id', 'date', 'type', 'subject', 'message', 'list_id') VALUES ('$newid', '$date','$format','$subject','$body', '$pmnl_id')";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -1;
		}
	
		return $newid;
	}
	
	//
	function get_message($host, $login, $pass, $database, $table_archive, $msg_id) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT type, subject, message FROM " . $table_archive . " WHERE id='" . $msg_id . "'";
		$db->DbQuery($sql);
		$message = $db->DbNextRow();
	
		return $message;
	}
	
	//
	function unique_id() {
		mt_srand((double)microtime()*1000000);
		return  md5(mt_rand(0,9999999));
	}
	
	//
	function get_subscribers($host, $login, $pass, $database, $table_email, $pmnl_id) {
		$i = 0;
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT email from ". $table_email . " WHERE list_id = '" . $pmnl_id . "' ORDER BY email";
		$db->DbQuery($sql);
		$subscribers = array();
		$toAdd = $db->DbNextRow();
		while ($toAdd['email']) {
			$subscribers[$i] = $toAdd['email'];
			$toAdd = $db->DbNextRow();
			$i++;
		}
		asort($subscribers);
	
		return $subscribers;
	}
	
	//
	function delete_subscriber($host, $login, $pass, $database, $table_email, $pmnl_id, $del_addr) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "DELETE from " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='". $del_addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			echo $sql;
			return 0;
		} else {
			return 1;
		}
	}
	
	//
	function add_subscriber($host, $login, $pass, $database, $table_email, $pmnl_id, $add_addr) {
		$add_addr = strtolower($add_addr);
	
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "SELECT hash FROM " . $table_email . " WHERE list_id='" . $pmnl_id . "' AND email='" . $add_addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return -2;
		}
	
		$add = $db->DbNumRows();
		if ($add != 0) {
			return -1;
		}
	
		$hash = unique_id();
	
		$sql = "INSERT INTO " . $table_email . " ('email', 'list_id', 'hash') VALUES ('" . $add_addr . "', '" . $pmnl_id . "', '" . $hash . "')";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			return 0;
		} else {
			return 1;
		}
	}
	
	//
	function moderate_subscriber($host, $login, $pass, $database, $table_email, $table_sub, $pmnl_id, $mod_addr) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "DELETE from " . $table_moderation . " WHERE list_id='" . $pmnl_id . "' AND email='" . $mod_addr . "'";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		}
	
		$hash = unique_id();
	
		$sql = "INSERT INTO " . $table_email . " ('email', 'list_id', 'hash') VALUES ('" . $mod_addr . "', '" . $pmnl_id . "', '". $hash . "')";
		$db->DbQuery($sql);
		if ($db->DbError()) {
			echo $db->DbError();
			return 0;
		} else {
			return $hash;
		}
	}
	
	//
	function upgrade($host, $login, $pass, $database, $table_global_config) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$db->DbQuery("SHOW COLUMNS FROM " . $table_global_config);
		$found = 0;
		while ($i = $db->DbNextRow()) {
			if ($i['Field'] == "charset") {
				$found = 1;
				break;
			}
		}
	
		//let's go for upgrade
		if ($found != 1) {
			$sql = "ALTER TABLE " . $table_global_config . " ADD charset varchar(255) NOT NULL default 'utf-8'";
			$db->DbQuery($sql);
		}
	}
	
	
	/* As of 0.8beta5 admin password is stored hashed in the DB, so we
	 need to update it ! */
	function upgrade_password($host, $login, $pass, $database, $table_global_config, $admin_pass) {
		$db = new Db();
		$db->DbConnect($host, $login, $pass, $database);
	
		$sql = "UPDATE " . $table_global_config . " SET admin_pass='" . md5($admin_pass) . "' WHERE admin_pass = '" . $admin_pass . "'";
		$db->DbQuery($sql);
	
		return $db->DbAffectedRows();
	}
	
	//
	function email_verifier($email){
		$sortie = false;
		// regx to test for valid e-mail adres
		$regex = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
		if (preg_match($regex, $email)) {
			$sortie = true;
		}
		return $sortie;
	}
	
	//
	function pmnl_newsletter_lister($host, $login, $pass, $database, $lists_table) {
		$sortie = false;
	
		$db = new Db();
		$connexion = $db->DbConnect($host, $login, $pass, $database);
		if ($connexion) {
			$sql = "SELECT list_id, newsletter_name FROM " . $lists_table . " ORDER BY list_id ASC";
			if ($db->DbQuery($sql)) {
				$nombre = $db->DbNumRows();
				if ($nombre && ($nombre > 0)) {
					$newsletters = array();
					for ($i = 0; $i < $nombre; $i++) {
						$newsletters[$i] = $db->DbNextRow();
					}
					$sortie = $newsletters;
				}
			}
		}
	
		return $sortie;
	}
	
	//
	function pmnl_newsletter_nom($host, $login, $pass, $database, $lists_table, $pmnl_id) {
		$sortie = false;
	
		$db = new Db();
		$connexion = $db->DbConnect($host, $login, $pass, $database);
		if ($connexion) {
			$sql = "SELECT newsletter_name FROM " . $lists_table . " WHERE list_id='" . $pmnl_id . "'";
			if ($db->DbQuery($sql)) {
				$resultat = $db->DbNumRows();
				if ($resultat) {
					$enregistrement =  $db->DbNextRow();
					$sortie = $enregistrement['newsletter_name'];
				}
			}
		}
	
		return $sortie;
	}
	
	//
	function pmnl_newsletter_inscrits($host, $login, $pass, $database, $email_table, $pmnl_id) {
		$sortie = false;
	
		$db = new Db();
		$connexion = $db->DbConnect($host, $login, $pass, $database);
		if ($connexion) {
			$sql = "SELECT COUNT(email) AS nombre FROM " . $email_table . " WHERE list_id='" . $pmnl_id . "'";
			if ($db->DbQuery($sql)) {
				$enregistrement =  $db->DbNextRow();
				$sortie = $enregistrement['nombre'];
			}
		}
	
		return $sortie;
	}
	
	//
	function pmnl_newsletter_premiere($host, $login, $pass, $database, $lists_table) {
		$sortie = false;
	
		$db = new Db();
		$connexion = $db->DbConnect($host, $login, $pass, $database);
		if ($connexion) {
			$sql = "SELECT list_id FROM " . $lists_table . " LIMIT 1";
			if ($db->DbQuery($sql)) {
				$enregistrement = $db->DbNextRow();
				$sortie = $enregistrement['list_id'];
			}
		}
	
		return $sortie;
	}
	
	class pmnl_configuration {
	
		var $sending_method;  // mail() or smtp
	
		/* database info */
		var $db_host;         // database hostname
		var $db_login;        // database login
		var $db_pass;         // database password
		var $db_name;         // database
		var $db_config_table; //
	
		var $table_email;
		var $table_temp;
		var $table_listsconfig;
		var $table_archives;
	
		/* admin info */
		var $admin_pass;
		var $archive_limit; //number of old newsletter / archive page
		var $base_url; // http://www.mydomain.com/myweb/
		var $path; // path/to/phpmynewsletter/
		var $language = "english";
	
		var $smtp_host;
		var $smtp_port;
		var $smtp_auth;
		var $smtp_login;
		var $smtp_pass;
	
		var $sending_limit;
		var $validation_period;
	
		var $sub_validation;
		var $unsub_validation;
	
		var $admin_email;
		var $admin_name;
		var $charset;
		var $mod_post;
		var $mod_sub;
	
		var $table_post;
		var $table_sub;
	
		function sgbd_associer($host, $login, $pass, $database, $config_table) {
			$sortie = false;
				
			$this->db_host = $host;
			$this->db_login = $login;
			$this->db_pass = $pass;
			$this->db_name = $database;
			$this->db_config_table = $config_table;
				
			$sortie = true;
				
			return $sortie;
		}
	
	
		function configurationAssocier($host, $login, $pass, $database, $config_table) {
				
			$sortie = false;
	
			$sgbd_association = $this->sgbd_associer($host, $login, $pass, $database, $config_table);
				
			if ($sgbd_association) {
	
				$db = new Db();
				$connexion = $db->DbConnect($this->db_host, $this->db_login, $this->db_pass, $this->db_name);
	
				if ($connexion) {
	
					$sql = "SELECT * FROM " . $this->db_config_table;
					if ($db->DbQuery($sql)) {
	
						$config_row = $db->DbNextRow();
	
						if ($config_row) {
							$this->admin_pass = $config_row['admin_pass'];
							$this->archive_limit = $config_row['archive_limit'];
							$this->base_url = $config_row['base_url'];
							$this->path = $config_row['path'];
							$this->sending_method = $config_row['sending_method'];
							$this->language = $config_row['language'];
							$this->table_email = $config_row['table_email'];
							$this->table_temp = $config_row['table_temp'];
							$this->table_listsconfig = $config_row['table_listsconfig'];
							$this->table_archives = $config_row['table_archives'];
								
							$chaine = $config_row['smtp_host'];
							$morceaux = explode(":", $chaine, 2);
							if ($morceaux) {
								$this->smtp_host = $morceaux[0];
								$this->smtp_port = $morceaux[1];
							} else {
								$this->smtp_host = $chaine;
								$this->smtp_port ='25';
							}
							unset($chaine);
							unset($morceaux);
								
							$this->smtp_auth = $config_row['smtp_auth'];
							$this->smtp_login = $config_row['smtp_login'];
							$this->smtp_pass = $config_row['smtp_pass'];
							$this->sending_limit = $config_row['sending_limit'];
							$this->validation_period = $config_row['validation_period'];
							$this->sub_validation = $config_row['sub_validation'];
							$this->unsub_validation = $config_row['unsub_validation'];
							$this->admin_email = $config_row['admin_email'];
							$this->admin_name = $config_row['admin_name'];
							$this->mod_sub = $config_row['mod_sub'];
							$this->table_sub = $config_row['mod_sub_table'];
							$this->charset = $config_row['charset'];
	
							$sortie = true;
						}
					}
				}
			}
				
			return $sortie;
		}
	
	
		function configuration_enregister($host, $login, $pass, $database, $config_table, $admin_pass, $archive_limit, $base_url, $path, $language, $table_email, $table_temp, $table_listsconfig, $table_archives, $sending_method, $smtp_host, $smtp_auth, $smtp_login, $smtp_pass, $sending_limit, $validation_period, $sub_validation, $unsub_validation, $admin_email, $admin_name, $mod_sub, $table_sub, $charset) {
			$sortie = false;
	
			$db = new Db();
			$connexion = $db->DbConnect($host, $login, $pass, $database);
				
			if ($connexion) {
	
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
	
				$sql = "UPDATE " . $config_table . " SET ";
				if (!empty($admin_pass)) {
					$sql .= "admin_pass='" . md5($admin_pass) . "', ";
					setcookie("PMNLNG_admin_password" , md5($admin_pass));
				}
				$sql .= "archive_limit='" . $archive_limit . "', ";
				$sql .= "base_url='". $base_url . "', ";
				$sql .= "path='" . $path . "',";
				$sql .= "language='" . $language . "', ";
				$sql .= "table_email='" . $table_email . "', ";
				$sql .= "table_temp='" . $table_temp . "', ";
				$sql .= "table_listsconfig='" . $table_listsconfig . "', ";
				$sql .= "table_archives='" . $table_archives . "', ";
				$sql .= "sending_limit='" . $sending_limit . "' , ";
				$sql .= "sending_method='" . $sending_method . "', ";
				$sql .= "sub_validation='" . $sub_validation . "', ";
				$sql .= "unsub_validation='" . $unsub_validation . "', ";
				$sql .= "admin_email='" . $admin_email . "', ";
				$sql .= "admin_name='" . $admin_name . "' ,";
				$sql .= "mod_sub='" . $mod_sub . "' , ";
				$sql .= "charset='" . $charset . "', ";
				$sql .= "mod_sub_table='" . $table_sub . "', ";
				$sql .= "validation_period='" . $validation_period . "' ";
				if ($sending_method == 'smtp') {
					$sql .= ", smtp_host='" . $smtp_host . "', ";
					$sql .= "smtp_auth='" . $smtp_auth . "' ";
					if ($smtp_auth == 1) {
						$sql .= ", smtp_login='" . $smtp_login . "', ";
						$sql .= "smtp_pass='" . $smtp_pass . "'";
					} else {
						$sql .= ", smtp_login='', ";
						$sql .= "smtp_pass=''";
					}
				} else {
					$sql .= ", smtp_host='', ";
					$sql .= "smtp_auth='0' ";
					$sql .= ", smtp_login='', ";
					$sql .= "smtp_pass=''";
				}
	
				$resultat = $db->DbQuery($sql);
				if ($resultat) {
					$sortie = true;
				}
			}
	
			return $sortie;
		}
	
	}


	class pmnl_newsletter {

		var $pmnl_id;
		var $name; //name of the newsletter

		var $from;
		var $from_name;

		var $subscription_subject;
		var $subscription_body;
		var $welcome_subject;
		var $welcome_body;
		var $quit_subject;
		var $quit_body;

		var $subject;
		var $header;
		var $footer;

		var $db_connexion;
		var $db_host;
		var $db_login;
		var $db_pass;
		var $db_name;
		var $db_table;


		function sgbdAssocier($host, $login, $pass, $database) {
			$sortie = false;
			
			$db = new Db();
			$connexion = $db->DbConnect($host, $login, $pass, $database);
			
			if ($connexion) {
				$this->db_connexion = $db;
				$this->db_host = $host;
				$this->db_login = $login;
				$this->db_pass = $pass;
				$this->db_name = $database;
				
				$sortie = true;
			}
			
			return $sortie;
		}
		
		
		function lire($list_table, $pmnl_id) {
			$sortie = false;
			
			$sql = "SELECT * FROM " . $list_table . " WHERE list_id='" . $pmnl_id . "'";
			$resultat = $this->db_connexion->DbQuery($sql);
			if ($resultat) {
				$this->list_id = $pmnl_id;
				if ($conf = $this->db_connexion->DbNextRow()) {
					$this->name = $conf['newsletter_name'];
			
					$this->from = $conf['from_addr'];
					$this->from_name = $conf['from_name'];
			
					$this->subject = $conf['subject'];
					$this->header = $conf['header'];
					$this->footer = $conf['footer'];
			
					$this->subscription_subject = $conf['subscription_subject'];
					$this->subscription_body = $conf['subscription_body'];
			
					$this->welcome_subject = $conf['welcome_subject'];
					$this->welcome_body = $conf['welcome_body'];
			
					$this->quit_subject = $conf['quit_subject'];
					$this->quit_body = $conf['quit_body'];
			
					$sortie = true;
				}
			}
			
			return $sortie;
		}
		
		function rafraichir() {
			$sortie = false;
			
			$sortie = $this->lire($this->db_table, $this->list_id);
			
			return $sortie;
		}
		
		function configurationAssocier($host, $login, $pass, $database, $pmnl_id , $list_table) {
			$sortie = false;
			
			$connexion = $this->sgbdAssocier($host, $login, $pass, $database);
			if ($connexion) {
				$sql = "SELECT * FROM " . $list_table . " WHERE list_id='" . $pmnl_id . "'";
				$resultat = $this->db_connexion->DbQuery($sql);
				if ($resultat) {
					$this->list_id = $pmnl_id;
					$this->db_table = $list_table;
					if ($conf = $this->db_connexion->DbNextRow()) {
						$this->name = $conf['newsletter_name'];
						
						$this->from = $conf['from_addr'];
						$this->from_name = $conf['from_name'];
						
						$this->subject = $conf['subject'];
						$this->header = $conf['header'];
						$this->footer = $conf['footer'];
						
						$this->subscription_subject = $conf['subscription_subject'];
						$this->subscription_body = $conf['subscription_body'];
						
						$this->welcome_subject = $conf['welcome_subject'];
						$this->welcome_body = $conf['welcome_body'];
						
						$this->quit_subject = $conf['quit_subject'];
						$this->quit_body = $conf['quit_body'];
						
						$sortie = true;
					}
				}
			}
			
			return $sortie;
		}


		function getAddress($mail_table, $begin = '', $limit = ''){
			$sortie = false;
			
			$sql = "SELECT email FROM " . $mail_table  . "WHERE list_id='" . $this->list_id . "'";
			$resultat = $this->db_connexion->DbQuery($sql, $begin, $limit, 1);
			if ($resultat) {
				$nombre = $db->DbNumRows();
				if ($nombre && $nombre > 0) {
					$adresses = array();
					for($i = 0; $i < $db->DbNumRows(); $i++) {
						$enregistrement = $db->DbNextRow();
						$adresses[$i] = $enregistrement['email'];
					}
					$sortie = $adresses;
				}
			}
			
			return $sortie;
		}


		function configurationEnregistrer($pmnl_id, $newsletter_name, $from, $from_name, $subject, $header, $footer, $subscription_subject, $subscription_body, $welcome_subject, $welcome_body, $quit_subject, $quit_body) {
			$sortie = false;
			
			if (!get_magic_quotes_gpc()) {
				$newsletter_name = escape_string($newsletter_name);
				$from = escape_string($from);
				$from_name = escape_string($from_name);
				$subject = escape_string($subject);
				$header = escape_string($header);
				$footer = escape_string($footer);
				$subscription_subject = escape_string($subscription_subject);
				$subscription_body = escape_string($subscription_body);
				$welcome_subject = escape_string($welcome_subject);
				$welcome_body = escape_string($welcome_body);
				$quit_subject = escape_string($quit_subject);
				$quit_body = escape_string($quit_body);
			}

			$sql = "UPDATE " . $this->db_table . " SET ";
			$sql .= "newsletter_name='" . $newsletter_name . "',";
			$sql .= "from_addr='" . $from . "',";
			$sql .= "from_name='" . $from_name . "',";
			$sql .= "subject='" . $subject . "',";
			$sql .= "header='" . $header . "',";
			$sql .= "footer='" . $footer . "',";
			$sql .= "subscription_subject='" . $subscription_subject . "',";
			$sql .= "subscription_body='" . $subscription_body . "',";
			$sql .= "welcome_subject='" . $welcome_subject . "',";
			$sql .= "welcome_body='" . $welcome_body . "',";
			$sql .= "quit_subject='" . $quit_subject . "',";
			$sql .= "quit_body='" . $quit_body . "'";
			$sql .= " WHERE list_id='" . $pmnl_id . "'";

			$resultat = $this->db_connexion->DbQuery($sql);
			
			if ($resultat) {
				$sortie = true;
			}
		
			return $sortie;
		}

		
		function abonnesCompter($table_email) {
			$sortie = false;
			
			$sql = "SELECT COUNT(*) AS nombre FROM " . $table_email . " WHERE list_id='" . $this->list_id . "'";
			$resultat = $this->db_connexion->DbQuery($sql);
			if ($resultat) {
					$num = $this->db_connexion->DbNextRow();
					$sortie = $num['nombre'];
			}
			
			return $sortie;
		}	


		function deleteArchive($table_archives, $msg_id) {
			$sortie = false;
		
			$db = new Db();
			if ($db->DbConnect($this->db_host, $this->db_login, $this->db_pass, $this->db_name)) {
				$sql = "DELETE FROM " . $table_archives . " WHERE id='" . $msg_id . "'";
				$db->DbQuery($sql);
				if ($db->DbError()) {
					$sortie = 0;
				} else {
					$sortie = 1;
				}
			}
			
			return $sortie;
		}

		
		function archivesListe($table_archives) {
			$sortie = false;
			
			$db = new Db();
			$connexion = $db->DbConnect($this->db_host, $this->db_login, $this->db_pass, $this->db_name);
			if ($connexion) {
				$sql = "SELECT id, date, type, subject FROM " . $table_archives . " WHERE list_id='" . $this->list_id . "' ORDER BY date DESC";
				$resultat = $db->DbQuery($sql);
				if ($resultat) {
					$nombre = $db->DbNumRows();
					if ($nombre > 0) {
						$archives = array();
						while ($ligne = $db->DBNextRow()) {
							$archives[] = $ligne;
						}
						$sortie = $archives;
					}
				}
			}
			
			return $sortie;
		}

		function getArchivesSelectList($table_archives, $msg_id = '', $form_name = 'archive_form2') {
			$sortie = false;
			
			$archives = $this->archivesListe($table_archives);
			if ($archives) {
				$html = "";
				$html .= "<select name='msg_id' onchange='document." . $form_name . ".submit()'>";
				foreach ($archives as $archive) {
					$html .= "<option value='" . $archive['id'] . "'";
					if ($msg_id == $archive['id']) {
						$html .= " selected='selected'";
					}
					$html .= ">";
					$html .= stripslashes(htmlspecialchars($archive['subject']));
					$html .= "</option>";
				}
				$html .= "</select>";
				
				$sortie = $html;
			}
			
			return $sortie;
		}


		function getArchiveMsg($table_archives, $msg_id) {
			$sortie = false;
			
			$db = new Db();
			$connexion = $db->DbConnect($this->db_host, $this->db_login, $this->db_pass, $this->db_name);
			if ($connexion) {
				$sql = "SELECT date, type, subject, message FROM " . $table_archives . " WHERE id='" . $msg_id . "'";
				$resultat = $db->DbQuery($sql);
				if ($resultat) {
					$nombre = $db->DbNumRows();
					if ($nombre > 0) {
						$archive = $db->DbNextRow();
						$subject = htmlspecialchars($archive['subject']);
						$subject = stripslashes($subject);
						$date = $archive['date'];
						if($archive['type'] != "html"){ 
							$body = str_replace("<","&lt;", $archive['message']);
							$body = str_replace(">","&gt;", $body);
							$body = nl2br(stripslashes($body));
						} else {
							$body = stripslashes($archive['message']);
						}
						
						$html = "";
						$html .= "<div class='subsection' >";
						$html .= "<div class='subtitle'>" . $subject . " - " . $date . "</div>";
						$html .= "<div class='subcontent'>";
						$html .= $body;
						$html .= "</div>";
						$html .= "</div>";
						$html .= "<br />";
						
						$sortie = $html;
					}
				}
			}
			
			return $sortie;
		}
	}
}

?>