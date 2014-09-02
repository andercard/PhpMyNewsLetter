<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="generator" content="Notepad Plus Plus" />
	<meta name="robots" content="noindex,nofollow" />
	<title>phpMyNewsletter v<?php echo PMNL_VERSION. " > ".$pmnl_module; ?></title>
	<link rel="stylesheet" href="../phpmynewsletter.css" type="text/css" />
	<link rel="stylesheet" href="admin.css" type="text/css" />
</head>
<body>

	<div id='pmnl_version'>
		<span>phpMyNewsletter v<?php echo PMNL_VERSION; ?></span>
	</div>

	<div id="header">
		<ul id="menu">
			
			<!--
			Partie gauche
			-->
			<li <?php if($pmnl_module=="subscribers" || empty($pmnl_module)){echo "class='actif' ";} ?>id="menuSubscriber">
				<img src="img/tango/16x16/system-users.png" alt="" />
				<a href="?page=subscribers&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_SUBSCRIBERS"); ?></a>
			</li>
			
			<li <?php if($pmnl_module=="compose"){echo "class='actif' ";} ?>id="menuCompose">
				<img src="img/tango/16x16/mail-message-new.png" alt="" />
				<a href="?page=compose&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_COMPOSE"); ?></a>
			</li>
						
			<li <?php if($pmnl_module=="archives"){echo "class='actif' ";} ?>  id="menuArchive">
				<img src="img/tango/16x16/package-x-generic.png" alt="" />
				<a href="?page=archives&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_ARCHIVES"); ?></a>
			</li>
			
			<li <?php if($pmnl_module=="newsletterconf"){echo "class='actif' ";} ?>  id="menuNewsletter">
				<img src="img/tango/16x16/document-properties.png" alt="" />
				<a href="?page=newsletterconf&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_NEWSLETTER"); ?></a>
			</li>
			
			
			<!--
			Partie droite
			-->
			<li id="menuLogout" style="float:right;">
				<img src="img/tango/16x16/system-log-out.png" alt="" />
				<a href="logout.php" ><?php echo translate("MENU_LOGOUT"); ?></a>
			</li>
			
			<li <?php if($pmnl_module=="config"){echo "class='actif' ";} ?>  id="menuConfig" style="float:right;">
				<img src="img/tango/16x16/preferences-system.png" alt="" />
				<a href="?page=config&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_CONFIG"); ?></a>
			</li>
			
			<!--
			<li <?php if($pmnl_module=="envois"){echo "class='actif' ";} ?>id="menuEnvois" style="float:right;">
				<img src="img/tango/16x16/system-users.png" alt="" />
				<a href="?page=envois&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_ENVOIS"); ?></a>
			</li>
			-->
			
			<li <?php if($pmnl_module=="lettres"){echo "class='actif' ";} ?>id="menuLettres" style="float:right;">
				<img src="img/tango/16x16/folder-open.png" alt="" />
				<a href="?page=lettres&amp;list_id=<?php echo $pmnl_id;?>"><?php echo translate("MENU_LETTRES"); ?></a>
			</li>
			
		</ul>
	</div>
	
	<br style="clear:both" />
	
	<div id="main">
<?php

if ($pmnl_id) {
	
	// Préparation des variables
	$html_select_options = "";
	$html_newsletter_name = "ERREUR";
	$html_newsletter_autres = "";
	if (sizeof($list) > 1) {
		for ($i = 0; $i < sizeof($list); $i++) {
			if ($pmnl_id == $list[$i][0]) {
				$html_newsletter_name = $list[$i][1];
			} else {
				$html_select_options .= "<option value='".$list[$i][0]."'>";
				$html_select_options .= $list[$i][1];
				$html_select_options .= "</option>";
			}
		}
		$html_newsletter_autres .= "<span>Autres newsletter disponibles : </span>";
		$html_newsletter_autres .= "<select name='list_id'>";
		$html_newsletter_autres .= $html_select_options;
		$html_newsletter_autres .= "</select>";
		$html_newsletter_autres .= "<input type='submit' value='OK' />";
		$html_newsletter_autres .= "<input type='hidden' name='page' value='" . $pmnl_module . "' />";
	}
	if ($list_total_subscribers > 1) {
		$html_inscrits = translate("NEWSLETTER_TOTAL_SUBSCRIBERS");
	} else {
		$html_inscrits = translate("NEWSLETTER_TOTAL_SUBSCRIBER");
	}
	
	echo "<form class='newsletter-list' action='index.php' method='post' name='selected_newsletter'>";
	echo "<ul id='submenu'>";
	echo "<li style='float:left;'>";
	echo "<span>" . translate("SELECTED_NEWSLETTER") . " : </span>";
	echo "<strong>" . $html_newsletter_name . " (". $list_total_subscribers . " " . $html_inscrits . ")</strong>";
	echo "</li>";
	echo "<li style='float:right;'>";
	echo $html_newsletter_autres;
	echo "</li>";
	echo "</ul>";
	echo "<br style='clear:both;' />";
	echo "</form>";
} elseif ($list_name == -1) {
	echo "<ul id='submenu'>";
	echo "<li>";
	echo "</li>";
	echo "</ul>";
	$error_list = true;
} elseif (empty($list) && $pmnl_module != "newsletterconf" && $pmnl_module != "config") {
	echo "<ul id='submenu'>";
	echo "<li>";
	echo "</li>";
	echo "</ul>";
	echo  pmnl_msg_error(translate("ERROR_NO_NEWSLETTER_CREATE_ONE").".");
	$error_list = true;
	include("include/pagefooter.inc.php");
	exit();
} else {
	echo "<ul id='submenu'>";
	echo "<li>";
	echo "</li>";
	echo "</ul>";
}

?>