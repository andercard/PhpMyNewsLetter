<?php

// you can include this form in any page of
// your website as follow:
//
// in this file, provide correct values for:
// $path_to_pmnl
// $pmnl_id newsletter id
// then in your page (need to be a php page) 
// add this line :
// include("path/to/this/page/form.php");
// NOTA: your page need to use UTF8 encoding
// put 
// <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
// in your html header




//path to phpMyNewsletter base directory
$path_to_pmnl = "./";

// newsletter id, leave this empty if you want to list available
// newsletter.

//$pmnl_id = 1;
$pmnl_id = "";

//display archive link (true or false) ?
$display_archive = true;







////////////////////// DO NOT MODIFY /////////////////////

if(file_exists("./".$path_to_pmnl."include/config.php")){
  include("./".$path_to_pmnl."include/config.php");
  include("./".$path_to_pmnl."include/variables.php");
  include("./".$path_to_pmnl."include/db/db_".$db_type.".inc.php");
}
include("./".$path_to_pmnl."include/interface.php");
include("./".$path_to_pmnl."include/lib/pmnl.lib.php");


if(file_exists("./".$path_to_pmnl."include/config.php")){
   print newsletter_list($pmnl_id, true, $display_archive);
  } else {
  include "./".$path_to_pmnl."include/lang/english.php";
  echo pmnl_msg_error(translate("NEWSLETTER_NOT_YET"));
}

?>