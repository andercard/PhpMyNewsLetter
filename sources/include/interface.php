<?PHP

//
function pmnl_msg($msg, $classe = false) {
	$sortie = false;
	
	$sortie = "";
	if ($classe) {
		$sortie .=  "<div class='" . $classe . "'>";
	} else {
		$sortie .=  "<div>";
	}
	$sortie .= $msg;
	$sortie .= "</div>";
	
	return $sortie;
}

//
function pmnl_msg_success($msg) {
	$sortie = false;
	
	$sortie = pmnl_msg($msg, "success");
	
	return $sortie;
}

//
function pmnl_msg_info($msg) {
	$sortie = false;
	
	$sortie = pmnl_msg($msg, "info");
	
	return $sortie;
}

//
function pmnl_msg_error($msg) {
	$sortie = false;
	
	$sortie = pmnl_msg($msg, "error");
	
	return $sortie;
}


//
function html_langues_options($pmnl_langues, $actif) {
	$sortie = false;

	foreach ($pmnl_langues as $value) {
		$sortie .= "<option value='" . $value . "'";
		if ($actif == $value) {
			$sortie .= " selected='selected'";
		}
		$sortie .= ">" . ucfirst($value) . "</option>";
	}

	return $sortie;
}

//
function html_header($title = '', $css = 'phpmynewsletter.css') {
header("Content-type: text/html; charset=utf-8");
echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="generator" content="Notepad" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" href="$css" type="text/css" />
	<title>$title</title>
</head>
<body>

EOT;

}

//
function html_footer() {
	echo "<br />";
	echo "<div align='center'>";
	echo "<a href='" . PMNL_HOMEPAGE . "' target='_blank'>";
	echo "<img src='img/button_pmnl.png' alt='logo pmnl' title='powered by phpMyNewsletter' border='0' />";
	echo "</a>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}

//
function page_header() {
	echo "<table border='0' cellpadding='0' cellspacing='0' align='center' width='90%'>";
	echo "<tbody>";
	echo "<tr>";
	echo "<td rowspan='3' align='center' valign='top' width='20'>";
	echo "<br />";
	echo "</tr>";
	echo "<tr>";
	echo "<td>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>";
	echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
	echo "<tbody>";
	echo "<tr>";
	echo "<td bgcolor='black' width='1'>";
	echo "<img src='img/clear.gif' width='1' height='1' alt='' />";
	echo "</td>";
	echo "<td bgcolor='white'>";
	echo "<br />";
}

//
function page_footer() {
	echo "<br />";
	echo "</td>";
	echo "<td bgcolor='black' width='1'>";
	echo "<img src='img/clear.gif' width='1' height='1' alt='' />";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan='3' bgcolor='black'>";
	echo "<img src='img/clear.gif' width='1' height='1' alt='' />";
	echo "</td>";
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
	echo "<br />";
}

//
function table_header() {
	echo "<table width='70%' cellspacing='0' border='0' cellpadding='0' align='center'>";
}

//
function table_title($title) {
	echo "<tr>";
	echo "<td width='100%' class='titreSection'>" . $title . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width='100%'>";
	echo "<img src='img/line.gif' width='100%' height='2' alt='--' />";
	echo "<br />";
	echo "</td>";
	echo "</tr>";
}

//
function table_footer() {
	echo "</table>";
}

?>