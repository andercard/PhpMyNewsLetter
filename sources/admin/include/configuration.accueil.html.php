<?PHP

echo "<div class='subsection'>";
echo "<div class='subtitle'>" . translate("NEWSLETTER_SETTINGS") . "</div>";
echo "<div class='subcontent'>";

echo "<form action='' method='post' class='form-light'>";

echo "<div align='center'>";

echo "<table>";
echo "<tbody>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['name']['champ'] . ": </span></td>";
echo "<td><input type='text' name='newsletter_name' value='" . $PMNL_Form['name']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['from_name']['champ'] . ": </span></td>";
echo "<td><input type='text' name='from_name' value='" . $PMNL_Form['from_name']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['from_addr']['champ'] . ": </span></td>";
echo "<td><input type='text' name='from' value='" . $PMNL_Form['from_addr']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['subject']['champ'] . ": </span></td>";
echo "<td><input type='text' name='subject' value='" . $PMNL_Form['subject']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td valign='top'><span class='field'>" . $PMNL_Form['header']['champ'] . ": </span></td>";
echo "<td><textarea name='header' cols='50' rows='10'>" . $PMNL_Form['header']['valeur'] . "</textarea></td>";
echo "</tr>";

echo "<tr>";
echo "<td valign='top'><span class='field'>" . $PMNL_Form['footer']['champ'] . ": </span></td>";
echo "<td><textarea name='footer' cols='50' rows='10'>" . $PMNL_Form['footer']['valeur'] . "</textarea></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['subscription_subject']['champ'] . ": </span></td>";
echo "<td><input type='text' name=' subscription_subject' value='" . $PMNL_Form['subscription_subject']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td valign='top'><span class='field'>" . $PMNL_Form['subscription_body']['champ'] . ": </span></td>";
echo "<td><textarea cols='50' rows='10' name='subscription_body'>" . $PMNL_Form['subscription_body']['valeur'] . "</textarea></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['welcome_subject']['champ'] . ": </span></td>";
echo "<td><input type='text' name=' welcome_subject' value='" . $PMNL_Form['welcome_subject']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td valign='top'><span class='field'>" . $PMNL_Form['welcome_body']['champ'] . ": </span></td>";
echo "<td><textarea cols='50' rows='10' name='welcome_body'>" . $PMNL_Form['welcome_body']['valeur'] .  "</textarea></td>";
echo "</tr>";

echo "<tr>";
echo "<td><span class='field'>" . $PMNL_Form['quit_subject']['champ'] . ": </span></td>";
echo "<td><input type='text' name=' quit_subject' value='" . $PMNL_Form['quit_subject']['valeur'] . "' size='50' /></td>";
echo "</tr>";

echo "<tr>";
echo "<td valign='top'><span class='field'>" . $PMNL_Form['quit_body']['champ'] . ": </span></td>";
echo "<td><textarea cols='50' rows='10' name='quit_body'>" . $PMNL_Form['quit_body']['valeur'] . "</textarea></td>";
echo "</tr>";

echo "</tbody>";
echo "</table>";

echo "<input type='hidden' name='page' value='newsletterconf' />";
echo "<input type='hidden' name='action' value='enregistrer' />";
echo "<input type='hidden' name='list_id' value='" . $pmnl_id . "' />";
echo "<input type='submit' value='" . $PMNL_Form['submit']['valeur'] . "' />";

echo "</div>";

echo "</form>";

echo "</div>";
echo "</div>";

echo "<br />";
echo "<br />";

?>