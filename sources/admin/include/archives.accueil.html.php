<?PHP

// Archives

echo "<script language='javascript' type='text/javascript'>";
echo "function deleteArchive() {";
echo "document.archive_form.elements['action'].value = 'delete';";
echo "document.archive_form.submit();";
echo "}";
echo "</script>";
	
echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("ARCHIVE_TITLE") . "</div>";
echo "<div class='subcontent'>";

/*
if ($newsletter_archives_liste) {
	echo "<form action='index.php' method='post' name='archive_form' class='form-light'>";
	echo $newsletter_archives_liste;
	echo "<input type='hidden' name='page' value='archives' />";
	echo "<input type='hidden' name='action' value='accueil' />";
	echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
	echo "<input type='submit' value='" . htmlspecialchars(translate("ARCHIVE_DISPLAY"), ENT_QUOTES) . "' />";
	echo "<input type='button' value='" . htmlspecialchars(translate("ARCHIVE_DELETE"), ENT_QUOTES) . "' onclick='deleteArchive();' />";
	echo "</form>";
} else {
	echo pmnl_msg_info(translate("NO_ARCHIVE"));
}

echo "<br />";
*/

if ($newsletter_archives) {
	echo "<table>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>Date</th>";
	echo "<th>Format</th>";
	echo "<th>Sujet</th>";
	echo "<th>Action</th>";
	echo "</tr>";
	foreach ($newsletter_archives as $archive) {
		echo "<tr>";
		echo "<td>" . $archive['id'] . "</td>";
		echo "<td>" . $archive['date'] . "</td>";
		echo "<td>" . $archive['type'] . "</td>";
		echo "<td>" . $archive['subject'] . "</td>";
		echo "<td>";
		echo "<a href='index.php?list_id=" . $pmnl_id . "&page=archives&action=afficher&msg_id=" . $archive['id'] . "'>Afficher</a>";
		echo "&nbsp;";
		echo "<a href='index.php?list_id=" . $pmnl_id . "&page=archives&action=supprimer&msg_id=" . $archive['id'] . "'>Supprimer</a>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br />";
} else {
	echo pmnl_msg_info(translate("NO_ARCHIVE"));
}

echo "</div>";
echo "</div>";

echo "<br />";
echo "<br />";

if ($newsletter_archive) {
	echo $newsletter_archive;
}

?>