<?PHP

if ($pmnl_partie == 'modele') {
	
	switch ($pmnl_service) {
	
		case 'preview' :
			
			$subject = (!empty($_POST['subject'])) ? $_POST['subject'] : '';
			$message = (!empty($_POST['message'])) ? $_POST['message'] : '';
			$format = (!empty($_POST['format'])) ? $_POST['format'] : '';
			
			// Préparation des variables
			$html_subject = htmlspecialchars($subject);
			if ($format == "html") {
				$html_message = $message;
			} else {
				$html_message = nl2br(htmlspecialchars($message));
			}
			
		break;
		
		case 'done' :
			$error = (empty($_GET['error']) ? "0" : $_GET['error']);
			$errorlog = (empty($_GET['errorlog']) ? "0" : $_GET['errorlog']);
		break;
		
		default :
			$newsletter = new pmnl_newsletter();
			$newsletter->configurationAssocier(
				$conf->db_host,
				$conf->db_login,
				$conf->db_pass,
				$conf->db_name,
				$pmnl_id,
				$conf->table_listsconfig
			);
			$newsletter_abonnes = $newsletter->abonnesCompter($conf->table_email);
		break;
	}
	
}


if ($pmnl_partie == 'vue') {

	switch ($pmnl_service) {
	
		case 'preview':
		
			//
			echo "<div class='subsection'>";
			echo "<div class='subtitle'>" . translate("COMPOSE_PREVIEW_TITLE") . "</div>";
			echo "<div class='subcontent'>";
			
			echo "<form method='post' action='send.php' class='form-light'>";
			echo "<table border='0' cellpadding='5' align='center'>";
			
			echo "<tr>";
			echo "<td width='100%'>";
			echo "<u>" . translate("COMPOSE_SUBJECT") . "</u> : ";
			echo "<span>" . $html_subject . "</span>";
			echo "</td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td>";
			echo $html_message;
			echo "</td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td>";
			echo "<div align='center'>";
			echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
			echo "<input type='hidden' name='format' value='" . $format . "' />";
			echo "<input type='hidden' name='subject' value='" . urlencode($subject) . "' />";
			echo "<input type='hidden' name='message' value='" . urlencode($message) . "' />";
			echo "<input type='button' value='" . "&lt;&lt; " . htmlspecialchars(translate("COMPOSE_BACK"), ENT_QUOTES) . "' onclick='javascript:history.go(-1)' />";
			echo "<input type='submit' value='" . htmlspecialchars(translate("COMPOSE_SEND"), ENT_QUOTES) . " &gt;&gt;' />";
			echo "</div>";
			echo "</td>";
			echo "</tr>";
			
			echo "</table>";
			echo "</form>";
			
			echo "</div>";
			echo "</div>";
			
			echo "<br />";
			echo "<br />";
		break;
		
		
		case "done" :
		
			echo "<div class='title'>" . translate("COMPOSE_SENDING") . "</div>";
			
			if ($error != 0) {
				echo "<div align='center' class='error'>" . translate("ERROR_SENDING") . "</div>";
			} else {
				echo "<div align='center' class='success'>" . translate("COMPOSE_SENT") . ".</div>";
			}

			if ($errorlog) {
				echo "<div align='center' class='error'>" . translate("ERROR_LOG_CREATE") . "</div>";
			}
			
			echo "<br />";
			echo "<br />";
		break;


		default:

			if ($newsletter_abonnes) {
				echo "<div class='subsection'>";
				echo "<div class='subtitle'>" . translate("COMPOSE_NEW") . "</div>";
				echo "<div class='subcontent'>";
				
				echo "<script language='javascript' type='text/javascript'>";
				echo "function Soumettre(){";
				echo "message = '" . addslashes(translate("ERROR_ALL_FIELDS_REQUIRED")) . "';";
				echo "if ((document.mailform.subject.value == '') || (document.mailform.message.value == '')) {";
				echo "alert(message);"; 
				echo "} else {";
				echo "document.mailform.submit();";
				echo "}";
				echo "}";
				echo "</script>";

				echo "<form name='mailform' method='post' action='' class='form-light'>";
				echo "<table border='0' cellpadding='5'>";
				
				echo "<tr>";
				echo "<td>";
				echo "<span>" . translate("COMPOSE_FORMAT") . " : </span>";
				echo "<input type='radio' class='radio' name='format' value='text' checked='checked' />";
				echo "<span>" . translate("COMPOSE_FORMAT_TEXT") . "</span>";
				echo "<input type='radio' class='radio' name='format' value='html' />";
				echo "<span>" . translate("COMPOSE_FORMAT_HTML") . "</span>";
				echo "<span>" . translate("COMPOSE_FORMAT_HTML_NOTICE") . "</span>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td width='868' height='25' align='left' valign='top'>";
				echo "<span>" . translate("COMPOSE_SUBJECT") . " : </span>";
				echo "<input type='text' name='subject' value='" . htmlspecialchars($newsletter->subject, ENT_QUOTES) . "' size='50' maxlength='255' />";
				echo "</td>";
				echo "</tr>";
			
				
				echo "<tr>";
				echo "<td>";
				echo "<textarea name='message' rows='20' cols='70'>";
				echo $newsletter->header;
				echo "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
				echo $newsletter->footer;
				echo "</textarea>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td>";
				echo "<center>";
				echo "<input type='hidden' name='page' value='compose' />";
				echo "<input type='hidden' name='action' value='preview' />";
				echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
				echo "<input type='reset' value='" . htmlspecialchars(translate("COMPOSE_RESET"), ENT_QUOTES) . "' />";
				echo "<input type='button' value='" . htmlspecialchars(translate("COMPOSE_PREVIEW"), ENT_QUOTES) . " &gt;&gt;' onclick='Soumettre()' />";
				echo "</center>";
				echo "</td>";
				echo "</tr>";
				
				echo "</table>";
				echo "</form>";
				
				echo "</div>";
				echo "</div>";
				
				echo "<br />";
				echo "<br />";

			} else {
				echo pmnl_msg_error(translate("ERROR_UNABLE_TO_SEND"));
				
				echo "<br />";
				echo "<br />";
			}
		break;
	}
}

?>