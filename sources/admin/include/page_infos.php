<?PHP

while ($message_erreur = $pmnl_controleur->messageSuivant('erreurs')) {
	echo "<div align='center' class='error'>" . $message_erreur . ".</div>";
}

while ($message_infos = $pmnl_controleur->messageSuivant('informations')) {
	echo "<div align='center' class='infos'>" . $message_infos . ".</div>";
}

while ($message_succes = $pmnl_controleur->messageSuivant('succes')) {
	echo "<div align='center' class='success'>" . $message_succes . ".</div>";
}


?>