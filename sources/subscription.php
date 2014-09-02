<?PHP

include("include/config.php");
include("include/variables.php");
include("include/db/db_" . $db_type . ".inc.php");
include("include/interface.php");
include("include/lib/pmnl.lib.php");
include("include/lib/class.phpmailer.php");

//dirty hack
if (!function_exists('iconv') && function_exists('libiconv')) {
	function iconv($input_encoding, $output_encoding, $string) {
		return libiconv($input_encoding, $output_encoding, $string);
	}
}

if (!function_exists('iconv') && !function_exists('libiconv')) {
	include_once("./include/lib/ConvertCharset.class.php");

	function iconv($input_encoding, $output_encoding, $string) {
		$converter = new ConvertCharset();
		return $converter->Convert($string, $input_encoding, $output_encoding);
	}
}

$conf = new pmnl_configuration();
$conf->configurationAssocier($hostname, $login, $pass, $database, $table_global_config);
include("include/lang/" . $conf->language.".php");


$pmnl_id = (!empty($_POST['list_id']) ? $_POST['list_id'] : "");
$pmnl_id = (empty($pmnl_id) && !empty($_GET['list_id']) ? $_GET['list_id'] : $pmnl_id);

$email_addr = (!empty($_POST['email_addr']) ? $_POST['email_addr'] : "");
$email_addr = (empty($email_addr) && !empty( $_GET['email_addr']) ? $_GET['email_addr'] : $email_addr);

$op = (!empty($_POST['op']) ? $_POST['op'] : "");
$op = (empty($op) && !empty( $_GET['op']) ? $_GET['op'] : $op);

$hash = (!empty($_POST['hash']) ? $_POST['hash'] : "");
$hash = (empty($hash) && !empty( $_GET['hash']) ? $_GET['hash'] : $hash);


if ($op == "leave" && !$conf->unsub_validation) {
	$op = "leave_direct";
} else if ($op == "leave_direct" && $conf->unsub_validation) {
	$op = "leave";
} else if ($op == "join" && !$conf->sub_validation) {
	$op = "join_direct";
} else if ($op == "join_direct" && $conf->sub_validation) {
	$op = "join";
}

$popup = (!empty($_POST['popup']) ? $_POST['popup'] : "" );

if (isset($pmnl_id)
	&& !empty($pmnl_id)
	&& isValidNewsletter($conf->db_host, $conf->db_login, $conf->db_pass, $conf->db_name, $conf->table_listsconfig, $pmnl_id)
	&& isset($email_addr)) {


	if (!email_verifier($email_addr)) {
		html_header(translate("NEWSLETTER_TITLE"));
		echo "<div class='subsection2" . $popup . "'>";
		echo "<div class='subtitle'>" . translate("SUBSCRIPTION_TITLE") . "</div>";
		echo "<div class='subcontent'>";
		echo "<div align='center' class='error" . $popup . "'>" . translate("EMAIL_ADDRESS_NOT_VALID") . "</div>";
		if (empty($popup)) {
			echo "<div align='center'>";
			echo "<img src='img/puce.gif' alt='' />";
			echo "<a href='#' onclick='history.back()'>" . translate("BACK") . "</a>";
			echo "</div>";
		}
		echo "</div>";
		echo "</div>";
		html_footer();
		exit();
	}
  
	switch ($op) {
		case "join":
			html_header(translate("NEWSLETTER_TITLE"));

			echo "<div class='subsection2" . $popup . "'>";
			echo "<div class='subtitle'>" . translate("SUBSCRIPTION_TITLE") . "</div>";
			echo "<div class='subcontent'>";

			if (!$conf->mod_sub) {

				$add = addSubscriberTemp(
					$conf->db_host,
					$conf->db_login,
					$conf->db_pass,
					$conf->db_name,
					$conf->table_email,
					$conf->table_temp,
					$pmnl_id,
					$email_addr
				);
				$news = new pmnl_newsletter();
				$news->configurationAssocier(
					$hostname,
					$login,
					$pass,
					$database,
					$pmnl_id,
					$conf->table_listsconfig
				);

				if (strlen($add) > 3) {

					$body = $news->subscription_body;
					$body .= "\n\n" . translate("SUBSCRIPTION_MAIL_BODY") . ":\n";
					$body .= $conf->base_url . $conf->path . "subscription.php?op=confirm_join&email_addr=" . urlencode($email_addr) . "&hash=" . $add . "&list_id=" . $pmnl_id;

					$subj = (strtoupper($conf->charset) == "UTF-8" ? $news->subscription_subject : iconv( "UTF-8", $conf->charset , $news->subscription_subject));
					$body = (strtoupper($conf->charset) == "UTF-8" ? $body : iconv( "UTF-8", $conf->charset , $body));

					$mail = sendEmail(
						$conf->sending_method,
						$email_addr,
						$news->from,
						$news->from_name,
						$subj,
						$body,
						$conf->smtp_auth,
						$conf->smtp_host,
						$conf->smtp_port,
						$conf->smtp_login,
						$conf->smtp_pass,
						$conf->charset
					);

					echo "<div align='center' class='success" . $popup . "'>" . translate("SUBSCRIPTION_SEND_CONFIRM_MESSAGE") . "</div>";
				} else if ($add == 0) {
					echo "<div align='center' class='error" . $popup . "'>" . translate("SUBSCRIPTION_ALREADY_SUBSCRIBER") . "</div>";
				} else {
					echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_SQL2", DbError()) . "</div>";
				}
				if(empty($popup)){
					echo "<div align='center'>";
					echo "<img src='img/puce.gif' alt=''/>";
					echo "<a href='#' onclick='history.back()'>" . translate("BACK") . "</a>";
					echo "</div>";
				}
			} else {

				$add = addSubscriberMod(
					$conf->db_host,
					$conf->db_login,
					$conf->db_pass,
					$conf->db_name,
					$conf->table_email,
					$conf->table_sub,
					$pmnl_id,
					$email_addr
				);
				
				if ($add) {
					echo "<div align='center' class='success" . $popup . "'>" . translate("Subscription requested recorded, waiting for moderation") . "</div>";
				} else if ($add == 0) {
					echo "<div align='center' class='error" . $popup . "'>" . translate("You are already a subscriber") . "</div>";
				} else {
					echo "<div align='center' class='error" . $popup . "'>" . translate("Error while SQL query") . "</div>";
					echo "<div align='center'>";
					echo "<img src='img/puce.gif' alt='' />";
					echo "<a href='index.php'>" . translate("Back") . "</a>";
					echo "</div>";
				}
			}

			echo "</div>";
			echo "</div>";

			html_footer();
		break;


		case "leave":
			html_header(translate("NEWSLETTER_TITLE"));

			$news = new pmnl_newsletter();
			$news->configurationAssocier(
				$hostname,
				$login,
				$pass,
				$database,
				$pmnl_id,
				$conf->table_listsconfig
			);

			$hash = isValidSubscriber(
				$conf->db_host,
				$conf->db_login,
				$conf->db_pass,
				$conf->db_name,
				$conf->table_email,
				$pmnl_id,
				$email_addr
			);

			echo "<div class='subsection2" . $popup . "'>";
			echo "<div class='subtitle'>" . translate("UNSUBSCRIPTION_TITLE") . "</div>";
			echo "<div class='subcontent'>";

			if ($hash) {
				$body = $news->quit_body;
				$body .= "\n\n" . translate("UNSUBSCRIPTION_MAIL_BODY") . " :\n";
				$body .= $conf->base_url . $conf->path . "subscription.php?op=confirm_leave&email_addr=" . urlencode($email_addr) . "&hash=" . $hash . "&list_id=" . $pmnl_id;

				$subj = (strtoupper($conf->charset) == "UTF-8" ? $news->quit_subject : iconv( "UTF-8", $conf->charset , $news->quit_subject));
				$body = (strtoupper($conf->charset) == "UTF-8" ? $body : iconv( "UTF-8", $conf->charset , $body));

				if (sendEmail($conf->sending_method, $email_addr, $news->from, $news->from_name, $subj, $body
				, $conf->smtp_auth, $conf->smtp_host, $conf->smtp_login, $conf->smtp_pass, $conf->charset)) {
					echo "<div align='center' class='success" . $popup . "'>" . translate("SUBSCRIPTION_SEND_CONFIRM_MESSAGE") . "</div>";
				} else {
					echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_SENDING_CONFIRM_MAIL") . "</div>";
				}
			} else {
				echo "<div align='center' class='error" . $popup . "'>" . translate("You are not a subscriber of this newsletter") . "</div>";
			}

			if (empty($popup)) {
				echo "<div align='center'>";
				echo "<img src='img/puce.gif' alt='' />";
				echo "<a href='index.php'>" . translate("BACK") . "</a>";
				echo "</div>";
			}

			echo "</div>";
			echo "</div>";

			echo "</td>";
			echo "</tr>";
			
			table_footer();
			page_footer();
			html_footer();
		break;



		case "confirm_join":
			html_header(translate("NEWSLETTER_TITLE"));

			$add = addSubscriber(
				$conf->db_host,
				$conf->db_login,
				$conf->db_pass,
				$conf->db_name,
				$conf->table_email,
				$conf->table_temp,
				$pmnl_id,
				$email_addr,
				$hash
			);
			echo "<div class='subsection2" . $popup . "'>";
			echo "<div class='subtitle'>" . translate("SUBSCRIPTION_CONFIRMATION") . "</div>";
			echo "<div class='subcontent'>";
			if ($add == -1) {
				echo "<div align='center' class='error" . $popup . "'>" . translate("SUBSCRIPTION_UNKNOWN_EMAIL_ADDRESS") . "! </div>";
			} elseif ($add) {
				$news = new pmnl_newsletter();
				$news->configurationAssocier(
					$hostname,
					$login,
					$pass,
					$database,
					$pmnl_id,
					$conf->table_listsconfig
				);

				$body = $news->welcome_body;
				$body .= "\n\n" . translate("SUBSCRIPTION_UNSUBSCRIBE_LINK") . ":\n";
				$body .= $conf->base_url . $conf->path . "subscription.php?op=confirm_leave&email_addr=" . urlencode($email_addr) . "&hash=" . $hash . "&list_id=" . $pmnl_id;

				$subj = (strtoupper($conf->charset) == "UTF-8" ? $news->welcome_subject : iconv( "UTF-8", $conf->charset , $news->welcome_subject));
				$body = (strtoupper($conf->charset) == "UTF-8" ? $body : iconv( "UTF-8", $conf->charset , $body));

				$mail = sendEmail(
					$conf->sending_method,
					$email_addr,
					$news->from,
					$news->from_name,
					$subj,
					$body,
					$conf->smtp_auth,
					$conf->smtp_host,
					$conf->smtp_port,
					$conf->smtp_login,
					$conf->smtp_pass,
					$conf->charset
				);

				echo "<div align='center' class='success" . $popup . "'>" . translate("SUBSCRIPTION_FINISHED") . "</div>";
			} else {
				echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_UNKNOWN") . "</div>";
			}

			echo "</div>";
			echo "</div>";
			html_footer();
		break;


		case "confirm_leave":
			html_header(translate("NEWSLETTER_TITLE"));
			$rm = removeSubscriber(
				$conf->db_host,
				$conf->db_login,
				$conf->db_pass,
				$conf->db_name,
				$conf->table_email,
				$pmnl_id,
				$email_addr,
				$hash
			);
			echo "<div class='subsection2" . $popup . "'>";
			echo "<div class='subtitle'>" . translate("UNSUBSCRIPTION_CONFIRMATION") . "</div>";
			echo "<div class='subcontent'>";

			if ($rm == 1) {
				echo "<div align='center' class='success" . $popup . "'>" . translate("UNSUBSCRIPTION_FINISHED") . ".</div>";
			} else if ($rm == -1) {
				echo "<div align='center' class='error" . $popup . "'>" . translate("UNSUBSCRIPTION_UNKNOWN_EMAIL_ADDRESS") . "</div>";
			} else {
				echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_UNKNOWN") . "</div>";
			}

			echo "</div>";
			echo "</div>";
			html_footer();
		break;

		
		case "join_direct":

			if (!$conf->sub_validation) {
				html_header(translate("NEWSLETTER_TITLE"));
				echo "<div class='subsection2" . $popup . "'>";
				echo "<div class='subtitle'>" . translate("SUBSCRIPTION_TITLE") . "</div>";
				echo "<div class='subcontent'>";

				$add = addSubscriberDirect(
					$conf->db_host,
					$conf->db_login,
					$conf->db_pass,
					$conf->db_name,
					$conf->table_email,
					$pmnl_id,
					$email_addr
				);
				
				/* $add not -1 nor -2 
				TODO:need to be rewritten
				*/
				if (strlen($add) > 2) {
					$news = new pmnl_newsletter();
					$news->configurationAssocier(
						$hostname,
						$login,
						$pass,
						$database,
						$pmnl_id,
						$conf->table_listsconfig
					);

					$body = $news->welcome_body;
					$body .= "\n\n" . translate("UNSUBSCRIPTION_MAIL_BODY") . ":\n";
					$body .= $conf->base_url . $conf->path . "subscription.php?op=confirm_leave&email_addr=" . urlencode($email_addr) . "&hash=" . $add . "&list_id=" . $pmnl_id;

					$subj = (strtoupper($conf->charset) == "UTF-8" ? $news->welcome_subject : iconv( "UTF-8", $conf->charset , $news->welcome_subject));
					$body = (strtoupper($conf->charset) == "UTF-8" ? $body : iconv( "UTF-8", $conf->charset , $body));

					$mail = sendEmail(
						$conf->sending_method,
						$email_addr,
						$news->from,
						$news->from_name,
						$subj,
						$body,
						$conf->smtp_auth,
						$conf->smtp_host,
						$conf->smtp_port,
						$conf->smtp_login,
						$conf->smtp_pass,
						$conf->charset
					);

					echo "<div align='center' class='success" . $popup . "'>" . translate("SUBSCRIPTION_FINISHED") . "</div>";
				} else if ($add == -1) {
					echo "<div align='center' class='error" . $popup . "'>" . translate("SUBSCRIPTION_ALREADY_SUBSCRIBER") . "</div>";
				} else {
					echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_UNKNOWN") . "</div>";
				}
				if (empty($popup)) {
					echo "<div align='center'>";
					echo "<img src='img/puce.gif' alt='' />";
					echo "<a href='#' onclick='history.back()'>" . translate("BACK") . "</a>";
					echo "</div>";
				}
				echo "</div>";
				echo "</div>";
				html_footer();

			} else {
				header("Location:index.php");
			}
		break;


		case "leave_direct":
			if (!$conf->unsub_validation) {

				html_header(translate("NEWSLETTER_TITLE"));
				$rm = removeSubscriberDirect(
					$conf->db_host,
					$conf->db_login,
					$conf->db_pass,
					$conf->db_name,
					$conf->table_email,
					$pmnl_id,
					$email_addr
				);

				echo "<div class='subsection2" . $popup . "'>";
				echo "<div class='subtitle'>" . translate("UNSUBSCRIPTION_TITLE") . "</div>";
				echo "<div class='subcontent'>";

				if ($rm == 1) {
					echo "<div align='center' class='success" . $popup . "'>" . translate("UNSUBSCRIPTION_FINISHED") . ".</div>";
				} else if ($rm == -1) {
					echo "<div align='center' class='error" . $popup . "'>" . translate("UNSUBSCRIPTION_UNKNOWN_EMAIL_ADDRESS") . "</div>";
				} else {
					echo "<div align='center' class='error" . $popup . "'>" . translate("ERROR_UNKNOWN") . "</div>";
				}

				if (empty($popup)) {
					echo "<div align='center'>";
					echo "<img src='img/puce.gif' alt='' />";
					echo "<a href='index.php'>" . translate("BACK") . "</a>";
					echo "</div>";
				}
				echo "</div>";
				echo "</div>";
				html_footer();
			} else {
				header("Location:index.php");
			}
		break;


		default:
			header("Location:index.php");
		break;
	}
} else {
	header("Location:index.php");
}

?>