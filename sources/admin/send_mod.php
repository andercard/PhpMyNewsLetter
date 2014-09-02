<?PHP

include($pmnl_chemin . "include/lib/class.phpmailer.php");
include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/lib/pmnl.lib.php");
include($pmnl_chemin . "include/grab_globals.inc.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");

$conf = new pmnl_configuration();
$conf->configurationAssocier($hostname, $login, $pass, $database, $table_global_config);

if (!checkAdminAccess($conf->admin_pass, $form_pass)) {
	header("Location:index.php");
}
 
switch ($step) {

	case "send":
		$limit = $conf->sending_limit;
		$mail = new phpmailer();
		$mail->PluginDir = "../include/lib/";

		switch ($conf->sending_method) {
			case "smtp":
				$mail->IsSMTP();
				$mail->Host = $conf->smtp_host;
				$mail->Port = $conf->smtp_port;
				if ($conf->smtp_auth) {
					$mail->SMTPAuth = true;
					$mail->Username = $conf->smtp_login;
					$mail->Password = $conf->smtp_pass;
				}
			break;

			case "php_mail":
				$mail->IsMail();
			break;

			default:
			break;
		}

		$newsletter = new pmnl_newsletter();
		$newsletter->configurationAssocier($hostname, $login, $pass, $database, $pmnl_id, $conf->table_listsconfig);

		$mail->From = $from;
		$mail->FromName = $from;

		//get address
		$addr = $newsletter->getAddress($conf->table_email, $begin, $limit);
		for ($i = 0; $i < sizeof($addr) ; $i++) {
			$mail->AddBCC($addr[$i]);
		}

		$msg = get_message($hostname, $login, $pass, $database, $conf->table_archives, $msg_id);

		$format = $msg[0];
		$subject = stripslashes($msg[1]);
		$message = stripslashes($msg[2]);
		$message .= "\n\nPour vous desinscrire :\n";
		$message .= $conf->base_url . $conf->path;

		if ($format == "html") {
			$mail->IsHTML(true);
		}

		$mail->Subject = $subject;
		$mail->Body =  $message;

		if (!$mail->Send()) {
			$error++;
			echo $mail->ErrorInfo . "<br>";
			echo $begin;
			break;
		}

		$begin += $limit;
		if ($begin < $sn) {
			header("location:send_mod.php?step=send&error=" . $error . "&begin=" . $begin . "&list_id=" . $pmnl_id . "&msg_id=" . $msg_id . "&sn=" . $sn . "&from=" . $from . "&m_id=" . $m_id);
		} else {
			header("location:index.php?page=moderation&op=mod&error=" . $error . "&list_id=" . $pmnl_id . "&m_id=" . $m_id);
		}
	break;

	default:
		$conf = new pmnl_configuration();
		$conf->configurationAssocier($hostname, $login, $pass, $database, $table_global_config);

		$message = urldecode($message);

		$amsg_id = save_message(
			$hostname,
			$login,
			$pass,
			$database,
			$conf->table_archives,
			addslashes($subject),
			$format,
			addslashes($message),
			$date,
			$pmnl_id
		);

		$newsletter = new pmnl_newsletter();
		$newsletter->configurationAssocier($hostname, $login, $pass, $database, $pmnl_id, $conf->table_listsconfig);

		$num = $newsletter->abonnesCompter($conf->table_email);

		header("location:send_mod.php?step=send&begin=0&list_id=" . $pmnl_id . "&msg_id=" . $amsg_id . "&sn=" . $num . "&error=0&from=" . $from_addr . "&m_id=" . $msg_id);
	break;
}

?>