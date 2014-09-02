<?PHP




function saveREFConfigFile($db_host, $db_login, $db_pass, $db_name, $db_config_table, $db_type='mysql')
{
  $configfile ="<?\nif (!defined( \"_CONFIG\" ))\n\t{\n\n\t\tdefine(\"_NEWSLETTER_CLASS\", 1);";
  $configfile.="\n\n\n\t\t$"."db_type = \"$db_type\";";
  $configfile.="\n\t\t$"."hostname = \"$db_host\";";
  $configfile.="\n\t\t$"."login = \"$db_login\";";
  $configfile.="\n\t\t$"."pass = \"$db_pass\";";
  $configfile.="\n\t\t$"."database = \"$db_name\";";
  $configfile.="\n\t\t$"."table_global_config=\"$db_config_table\";";
  $configfile.="\n\t\t$"."pmnl_version =\"0.7\";\n\n\t}\n\n?>";
  
  if(is_writable("../include/"))
    {
      $fc = fopen("../include/config.php", "w");
      $w = fwrite ($fc, $configfile ); 
      return 1;
    }

  else {
    return -1;
  }
}





function acceptModMsg($hostname,$login ,$pass,$database, $table_mod, $msg_id, $table_archive)
{
  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);
  
  $msg= getWaitingMsg($hostname,$login ,$pass,$database, $table_mod,  $msg_id);

  
  $sql="SELECT id FROM $table_archive ORDER BY id DESC";
  $db->DbQuery($sql);    
  $id = $db->DbNextRow();
  $newid = $id[0]+1;
  
  $sql="INSERT INTO $table_archive (`id`,`date`,`type`,`subject`,`message`,`list_id`) VALUES('$newid','$msg[0]','$msg[1]','$msg[3]','$msg[4]','$msg[5]')";
  $db->DbQuery($sql);    
  if($db->DbError())
    {
      echo $db->DbError();
      return -1;
    }
  
  else  
    return deleteModMsg($hostname,$login ,$pass,$database, $table_mod, $msg_id);
}




function deleteModMsg($hostname,$login ,$pass,$database, $table_mod, $msg_id)
{
  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);
  
  $sql="DELETE FROM $table_mod WHERE id='$msg_id'";
  $db->DbQuery($sql);    
  if($db->DbError())
    {
      echo $db->DbError();
      return -1;
    }
  else  return 1;
}


function getWaitingMsg($hostname,$login ,$pass,$database, $table_mod,  $msg_id)
{
  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);
  
  $sql="SELECT date, type, email_from, subject,  message, list_id FROM $table_mod WHERE id='$msg_id'";
  $db->DbQuery($sql);    
  if($db->DbNumRows())
    return  $db->DbNextRow();
  else 
    return 0;
}


function getWaitingMsgList($hostname,$login ,$pass,$database, $table_mod,  $pmnl_id, $msg_id='')
{
  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);
  
  $sql="SELECT id, date, email_from, subject FROM $table_mod WHERE list_id='$pmnl_id'";
  $db->DbQuery($sql);    
  if($db->DbNumRows())
    {
      while($r = $db->DbNextRow())
	{
	  $form.="<OPTION VALUE=\"".$r[0]."\"";
	  if($msg_id==$r[0]) $form.= " SELECTED ";
	  $form.=">$r[1] | $r[2] | $r[3] </option> ";
	 
	}
      return $form;
	
    }
  else return 0;
  
  
  

}





function save_mod_message($hostname,$login ,$pass,$database, $table_mod,$subject,$format, $body, $date,$pmnl_id, $from)
{
  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);

  $sql="SELECT id FROM $table_mod ORDER BY id DESC";
  $db->DbQuery($sql);    
  $id = $db->DbNextRow();
  
  $newid = $id[0]+1;
  $sql="INSERT into $table_mod (`id`, `date`,`type`, `subject` , `message`, `list_id` , `email_from`) VALUES ('$newid', '$date','$format','$subject','$body', '$pmnl_id', '$from')";
  $db->DbQuery($sql);
  if($db->DbError()) 
    {
      echo $db->DbError();
      return -1;
    }
  return $newid;
}





/** **/
function getSubscribersEmail($db_host, $db_login, $db_pass, $db_name, $table_config ,$email , $from, $from_name,$pmnl_id='')
{

  $conf = new pmnl_configuration();
  $conf->configurationAssocier($db_host, $db_login, $db_pass, $db_name, $table_config);  
  
  $db= new Db();
  $db->DbConnect($db_host, $db_login, $db_pass, $db_name);
  $sql = "SELECT DISTINCT email  FROM $conf->table_email ";
  if(!empty($pmnl_id))
    $sql.="WHERE list_id='$pmnl_id'";
  
  $db->DbQuery($sql);
  if($db->DbError()){
    echo $db->DbError();
    return -1;
  }
  
  $body ="adresse email des abonnés:\n";
  $body.="-------------------------\n";

  while($a=$db->DbNextRow())
    {
      $body.=$a[0]."\n";
    }

  return sendEmail($conf->sending_method,$email, $from, $from_name, "Liste des adresses", $body, $conf->smtp_auth, $conf->smtp_host='', $conf->smtp_login='', $conf->smtp_pass )  ;
    }


function addSubscriberMod($host, $login, $pass, $database, $table_email, $ref_sub_table , $pmnl_id, $addr)
{


  $addr=strtolower($addr);

  $db= new Db();
  $db->DbConnect($host, $login, $pass, $database);
  $sql = "SELECT email FROM $table_email WHERE list_id='$pmnl_id' AND email='$addr'";
  $db->DbQuery($sql);
  if($db->DbError()){
    echo $db->DbError();
    return -1;
  }
  $mail = $db->DbNumRows();

  $sql = "SELECT email FROM $ref_sub_table WHERE list_id='$pmnl_id' AND email='$addr'";
  $db->DbQuery($sql);
  if($db->DbError()){
    echo $db->DbError();
    return -1;
  }
  $mail+= $db->DbNumRows();
  
  if($mail) return 0;
  
  $sql = "INSERT INTO $ref_sub_table (`email`, `list_id`) VALUES ('$addr', '$pmnl_id')"; 
  $db->DbQuery($sql);
  if($db->DbError()){
    echo $db->DbError();
    return -1;
  }

  return 1;  
  //  return $hash;

}


?>
