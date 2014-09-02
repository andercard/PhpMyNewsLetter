<?PHP

include($pmnl_chemin . "include/config.php");
include($pmnl_chemin . "include/variables.php");
include($pmnl_chemin . "include/db/db_" . $db_type . ".inc.php");
include($pmnl_chemin . "include/lib/class.phpmailer.php");
include($pmnl_chemin . "include/lib/pmnl.lib.php");

$conf = new pmnl_configuration();
$conf->configurationAssocier(
	$hostname,
	$login,
	$pass,
	$database,
	$table_global_config
);

if (empty($conf->language)) {
	$conf->language = "english";
}

include($pmnl_chemin . "include/lang/" . $conf->language . ".php");

//check authentification
$form_pass = (empty($_POST['form_pass']) ? "" : $_POST['form_pass']);

if (!checkAdminAccess($conf->admin_pass, $form_pass)) {
	header("Location:index.php");
	exit();
}

$step = (empty($_GET['step']) ? "" : $_GET['step']);

$subject = (!empty($_POST['subject'])) ? $_POST['subject'] : '';
$message = (!empty($_POST['message'])) ? $_POST['message'] : '';
$format = (!empty($_POST['format'])) ? $_POST['format'] : '';

$pmnl_id = (!empty($_POST['list_id'])) ? $_POST['list_id'] : '';
$pmnl_id = (!empty($_GET['list_id']) && empty($pmnl_id)) ? $_GET['list_id'] : $pmnl_id;

$begin = (!empty($_POST['begin'])) ? $_POST['begin'] : '';
$begin = (!empty($_GET['begin']) && empty($begin)) ? $_GET['begin'] : 0;

$msg_id = (!empty($_GET['msg_id'])) ? $_GET['msg_id'] : '';
$sn = (!empty($_GET['sn'])) ? $_GET['sn'] : '';
$error = (!empty($_GET['error'])) ? $_GET['error'] : '';


switch ($step) {

	case "send":
	
		// open log file
		$dontlog = 1;
		$handler = @fopen('./logs/' . date("Ymd") . '-list' . $pmnl_id . '-msg' . $msg_id . '.log', 'a+');
		if ($handler) {
			$dontlog = 0;
		}

		$limit = $conf->sending_limit;
		
		$mail = new PHPMailer();
		$mail->CharSet = $conf->charset;
		$mail->PluginDir = "../include/lib/";
		$mail->LE = "\r\n";
		//$mail->SMTPDebug = true;
		
		// Choix de la méthode d'envoi
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
				$mail->IsMail();
			break;
		}
 
		// Lecture du message
		$msg = get_message(
			$hostname,
			$login,
			$pass,
			$database,
			$conf->table_archives,
			$msg_id
		);
		
		// Traitement du message
		$format = $msg[0];
		$subject = stripslashes($msg[1]);
		$message = stripslashes($msg[2]);
		unset($msg);
		
		// Détermination du format du mail
		if ($format == "html") {
			$mail->IsHTML(true);
		}
		
		// Récupération des informations concernant la newsletter
		$newsletter = new pmnl_newsletter();
		$newsletter->configurationAssocier(
			$hostname,
			$login,
			$pass,
			$database,
			$pmnl_id,
			$conf->table_listsconfig
		);
		
		// Préparation des variables FROM, FROM_NAME, SENDER et SUBJECT
		if (strtoupper($conf->charset) == "UTF-8") {
			$email_from = iconv("UTF-8", $conf->charset , $newsletter->from);
			$email_from_name = iconv("UTF-8", $conf->charset , $newsletter->from_name);
			$email_sender = $email_from;
			$email_subject = iconv("UTF-8", $conf->charset , $subject);
		} else {
			$email_from = $newsletter->from;
			$email_from_name = $newsletter->from_name;
			$email_sender = $email_from;
			$email_subject = $subject;
		}
		
		// Configuration du mail
		$mail->From = $email_from;
		$mail->FromName = $email_from_name;
		$mail->Sender = $email_sender;
		$mail->Subject = $email_subject;
		
		// Récupération des adresses e-mail
		$addr = $newsletter->getAddress(
			$conf->table_email,
			$begin,
			$limit
		);
		
		$unsub_message = translate("SEND_UNSUBSCRIPTION_LINK");
 
		for ($i = 0; $i < count($addr); $i++) {
		
			$mail->AddAddress($addr[$i]);
			
			$body = "";
			$unsub_link = "";
			
			$unsub_url = $conf->base_url . $conf->path . "subscription.php?list_id=" . $pmnl_id . "&op=leave&email_addr=" . $addr[$i];
			
			if ($format == "html") {
				
				$unsub_link .= "<a target='_blank' href='" . $unsub_url . "'>";
				$unsub_link .= $unsub_url;
				$unsub_link .= "</a>";
				
				$body .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
				$body .= "<html>";
				$body .= "<head>";
				$body .= "<title>" . $email_subject . "</title>";
				$body .= "</head>";
				$body .= "<body>";
				$body .= $message;
				$body .= "<br /";
				$body .= "<div>";
				$body .= $unsub_message;
				$body .= $unsub_link;
				$body .= "</div>";
				$body .= "</body>";
				$body .= "</html>";
				
			} else {
			
				//$unsub_link .= $conf->base_url . $conf->path . "subscription.php?list_id=" . $pmnl_id . "&op=leave&email_addr=" . urlencode($addr[$i]);
				
				$body .= $message;
				$body .= $unsub_message;
				$body .= $unsub_url;
				
			}

			if (strtoupper($conf->charset) == "UTF-8") {
				$email_body = $body;
			} else {
				$email_boy = iconv("UTF-8", $conf->charset , $body);
			}

			$mail->Body = $email_body;

			@set_time_limit(150); 
			
			if (!$mail->Send()) {
				$errstr = ($begin + $i + 1) . "\t" . date("H:i:s") . "\t" . $addr[$i] . "\t" . $mail->ErrorInfo . "\r\n";	
			} else {
				$errstr = ($begin + $i + 1) . "\t" . date("H:i:s") . "\t" . $addr[$i] . "\t" . "OK" . "\r\n";	
			}
			
			if (!$dontlog) {
				fwrite($handler, $errstr, strlen($errstr));
			}
			
			$mail->ClearAddresses();
		} 


		$begin += $limit;
		
		if ($begin < $sn) {
			header("location:send.php?step=send&error=" . $error . "&begin=" . $begin . "&list_id=" . $pmnl_id . "&msg_id=" . $msg_id . "&sn=" . $sn);
		} else {
			$errstr = "------------------------------------------------------------\r\n";
			$errstr .= "Finished at " . date("H:i:s") . "\r\n";
			$errstr .= "============================================================\r\n";
			
			if (!$dontlog) {
				fwrite($handler, $errstr, strlen($errstr));
			}
			
			if (!$dontlog) {
				fclose($handler);
			}

			header("location:index.php?page=compose&op=done&error=" . $error . "&list_id=" . $pmnl_id . "&errorlog=" . $dontlog);
		}

	break;
 
	default:
		$conf = new pmnl_configuration();
		$conf->configurationAssocier(
			$hostname,
			$login,
			$pass,
			$database,
			$table_global_config
		);

		$message = urldecode($message);
		
		// save the message in the database
		$date = date("Y-m-d H:i:s");
		$msg_id = save_message(
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

		// open log file
		$dontlog = 1;
		$handler = @fopen('./logs/' . date("Ymd") . '-list' . $pmnl_id . '-msg' . $msg_id . '.log', 'a+');
		if ($handler) {
			$dontlog = 0;
		}

		$newsletter = new pmnl_newsletter();
		$newsletter->configurationAssocier(
			$hostname,
			$login,
			$pass,
			$database,
			$pmnl_id,
			$conf->table_listsconfig
		);

		$num = $newsletter->abonnesCompter($conf->table_email);
		$errstr = "============================================================\r\n";
		$errstr .= date("d M Y") . "\r\n";
		$errstr .= "Started at " . date("H:i:s") . "\r\n";
		$errstr .= "N° \t Date \t\t Recipient \t\t Status \r\n";	
		$errstr .= "------------------------------------------------------------\r\n";
		
		if (!$dontlog) {
			fwrite($handler, $errstr, strlen($errstr));
		}
		
		if (!$dontlog) {
			fclose($handler);
		}

		header("location:send.php?step=send&begin=0&list_id=" . $pmnl_id . "&msg_id=" . $msg_id . "&sn=" . $num . "&error=0");
		
	break;

}

?>
