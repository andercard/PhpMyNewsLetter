<?PHP

include("include/variables.php");
include("include/interface.php");
include("include/lib/pmnl.lib.php");

html_header("phpMyNewsletter");

if (file_exists("include/config.php")) {
	include("include/config.php");
	include("include/db/db_" . $db_type . ".inc.php");
	
	$conf = new pmnl_configuration();
	$configuration = $conf->configurationAssocier(
		$hostname,
		$login,
		$pass,
		$database,
		$table_global_config
	);
	
	if ($configuration) {

		include("include/lang/" . $conf->language . ".php");
		
		echo "<div class='content'>";
		
		echo "<div class='subsection'>";
		echo "<div class='subtitle'>" . translate("SUBSCRIPTION_TITLE") . "</div>";
		echo "<div class='subcontent' align='center'>";
		
		$list = pmnl_newsletter_lister(
			$conf->db_host,
			$conf->db_login,
			$conf->db_pass,
			$conf->db_name,
			$conf->table_listsconfig
		);

		if (sizeof($list)) {
			echo "<script type='text/javascript'>";
			echo "function submitform() {";
			echo "message = '" . addslashes(translate("EMAIL_ADDRESS_NOT_VALID")) . "';";
			echo "if (document.sub_form.email_addr.value=='') {";
			echo "alert(message);";
			echo "} else {";
			echo "if (((document.sub_form.email_addr.value.indexOf('@',1))==-1) || (document.sub_form.email_addr.value.indexOf('.',1))==-1 ) {";
			echo "alert(message);";
			echo "} else {";
			echo "document.sub_form.submit();";
			echo "}";
			echo "}";
			echo "}";
			echo "</script>";

			echo "<br />";

			echo "<form action='subscription.php' class='form-light' method='post' name='sub_form' target='' onsubmit='submitform();'>";
			
			echo "<span>" . translate("EMAIL_ADDRESS") . " : </span>";
			echo "<input type='text' size='25' name='email_addr' value='' />";
			echo "<input type='button' name='sub' value='OK' onclick='submitform()' />";
			
			echo "<br />";
			
			if ($conf->sub_validation) {
				$html_join = "join";
			} else {
				$html_join = "join_direct";
			}
			
			echo "<input type='radio' class='radio' name='op' value='" . $html_join . "' checked='checked' />";
			echo translate("NEWSLETTER_SUBSCRIPTION");
			
			if ($conf->unsub_validation) {
				$html_leave = "leave";
			} else {
				$html_leave = "leave_direct";
			}
			
			echo "<input type='radio' class='radio' name='op' value='" . $html_leave . "' />";
			echo translate("NEWSLETTER_UNSUBSCRIPTION");
			
			echo "<br />";

			if (sizeof($list) > 1 && empty($pmnl_id)) {
				echo "<span>" . translate("AVAILABLE_NEWSLETTER") . " : </span>";
				echo "<select name='list_id'>";

				for ($i = 0; $i < sizeof($list); $i++) {
					echo "<option value='" . $list[$i]['list_id'] . "'";
					if (!empty($_GET['list_id'])){
						if ($_GET['list_id'] == $list[$i]['list_id']) {
							echo " selected='selected'";
						}
					} 
					echo ">" . $list[$i]['newsletter_name'] . "</option>";
				}

				echo "</select>";
			} else {
				$tid = (empty($pmnl_id) ? $list[0]['list_id'] : $pmnl_id);
				echo "<input type='hidden' name='list_id' value='" . $tid . "' />";
			}
			
			echo "<br />";

			echo "</form>";
			
			echo "</div>";
			echo "</div>";
			
			echo "<div align='center'><a href='archives.php'>" . translate("ARCHIVE_BROWSE") . "</a></div>";
			
			echo "</div>";

		} else {
			echo pmnl_msg_error(translate("NEWSLETTER_NOT_YET"));
		}
	} else {
		include "include/lang/english.php";
		echo pmnl_msg_error(translate("NEWSLETTER_NOT_YET"));
	}
} else {
	include "include/lang/english.php";
	echo pmnl_msg_error(translate("NEWSLETTER_NOT_YET"));
}

html_footer();

?>