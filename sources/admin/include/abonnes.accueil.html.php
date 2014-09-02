<?PHP

echo "<script language='javascript' type='text/javascript'>";
echo "function Submitform() {";
echo "message = '" . addslashes(translate("EMAIL_ADDRESS_NOT_VALID")) . "';";
echo "if (document.sub.add_addr.value == '') {";
echo "alert(message);";
echo "} else {";
echo "if ( ((document.sub.add_addr.value.indexOf('@', 1)) == -1) || (document.sub.add_addr.value.indexOf('.', 1)) == -1 ) {";
echo "alert(message);";
echo "} else {";
echo "document.sub.submit();";
echo "}";
echo "}";
echo "}";
echo "</script>";

// AJOUTER
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("SUBSCRIBER_ADD_TITLE") . "</div>";
echo "<div class='subcontent'>";

echo "<form method='post' name='sub' class='form-light' action=''>";
echo "<input type='hidden' name='page' value='subscribers' />";
echo "<input type='hidden' name='action' value='ajouter' />";
echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
echo "<input type='text' name='add_addr' value='' maxlength='250' size='30' />";
echo "<input type='button'  value='" . translate("SUBSCRIBER_ADD_BTN")."' onclick='Submitform()' />";
echo "</form>";

echo "</div>";
echo "</div>";


// IMPORTER
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("SUBSCRIBER_IMPORT_TITLE") . "</div>";
echo "<div class='subcontent'>";

echo "<form action='' method='post'  enctype='multipart/form-data' name='importform' class='form-light'>";

echo "<script language='javascript' type='text/javascript'>";
echo "function Soumettre() {";
echo "document.importform.import_file.value=document.importform.insert_file.value;";
echo "document.importform.submit();";
echo "}";
echo "</script>";

echo "<input type='file' name='import_file' />";
echo "<input type='hidden' name='page' value='subscribers' />";
echo "<input type='hidden' name='action' value='importer' />";
echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
echo "<input type='submit' value='" . translate("SUBSCRIBER_IMPORT_BTN") . "' />";

echo "<div class='info_left'>" . translate("SUBSCRIBER_IMPORT_HELP") . "</div>";

echo "</form>";

echo "</div>";
echo "</div>";


// INSCRIPTION NON VALIDEES
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("SUBSCRIBER_TEMP_TITLE") . "</div>";
echo "<div class='subcontent'>";

if (sizeof($tmp_subscribers)) {
	echo "<form action='index.php' method='post' class='form-light'>";
	echo "<select name='del_tmpaddr'>";
	for ($i = 0; $i < sizeof($tmp_subscribers); $i++) {
		echo "<option value='" . $tmp_subscribers[$i] . "'>";
		echo $tmp_subscribers[$i];
		echo "</option>";
	}
	echo "</select>";
	echo "<input type='hidden' name='page' value='subscribers' />";
	echo "<input type='hidden' name='action' value='temporaire_supprimer' />";;
	echo "<input type='hidden' name='list_id' value='" . $pmnl_id. "' />";
	echo "<input type='submit' value='" . translate("SUBSCRIBER_TEMP_BTN") . "' />";
	echo "</form>";
} else {
	echo pmnl_msg_info(translate("NO_SUBSCRIBER"));
	echo "<br />";
}

echo "</div>";
echo "</div>";


// SUPPRIMER
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("SUBSCRIBER_DELETE_TITLE") . "</div>";
echo "<div class='subcontent'>";

if (sizeof($subscribers)) {
	echo "<form action='index.php' method='post' class='form-light'>";
	echo "<select name='del_addr'>";
	for ($i = 0; $i < sizeof($subscribers); $i++) {
		echo "<option value='" . $subscribers[$i] . "'>";
		echo $subscribers[$i];
		echo "</option>";
	}
	echo "</select>";
	echo "<input type='hidden' name='page' value='subscribers' />";
	echo "<input type='hidden' name='action' value='supprimer' />";
	echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
	echo "<input type='submit' value='" . translate("SUBSCRIBER_DELETE_BTN") . "' />";
	echo "</form>";
} else {
	echo pmnl_msg_info(translate("NO_SUBSCRIBER"));
	echo "<br />";
}

echo "</div>";
echo "</div>";


// EXPORTER
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("SUBSCRIBER_EXPORT_TITLE") . "</div>";
echo "<div class='subcontent'>";

if (sizeof($subscribers)) {
	echo "<form action='export.php' method='post' class='form-light'>";
	echo "<div align='center'>";
	echo "<input type='hidden' name='page' value='subscribers' />";
	echo "<input type='hidden' name='action' value='exporter' />";
	echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
	echo "<input type='hidden' name='db_pass' value='" . $conf->db_pass . "' />";
	echo "<input type='hidden' name='db_login' value='" . $conf->db_login . "' />";
	echo "<input type='hidden' name='db_host' value='" . $conf->db_host . "' />";
	echo "<input type='hidden' name='db_name' value='" . $conf->db_name . "' />";
	echo "<input type='hidden' name='table_email' value='" . $conf->table_email . "' />";
	echo "<input type='hidden' name='db_type' value='" . $db_type . "' />";
	echo "<input type='submit' name='Submit' value='" . translate("SUBSCRIBER_EXPORT_BTN") . "' />";
	echo "</div>";
	echo "</form>";
} else {
	echo pmnl_msg_info(translate("NO_SUBSCRIBER"));
	echo "<br />";
}

echo "</div>";
echo "</div>";

?>