<?PHP

//include($pmnl_chemin . "include/REF.php");

if($conf->mod_sub)
{
    include($pmnl_chemin . "include/lib/class.phpmailer.php");
    include($pmnl_chemin . "include/REF_function.php");
    
    echo "\t<tr><td width=\"100%\" class=\"titreSection\">".translate("Subscriptions waiting for moderation")."</td></tr>\n";
    echo "\t<tr><td width=\"100%\"><img alt=\"--\" src=\"img/line.gif\" width=\"100%\" height=\"2\"><br>&nbsp;</td></tr>\n";
    echo "\t<tr><td width=\"100%\">\n";
    
    if($op=="mod") {
      if($conf->sub_validation)
	{
	  $moderated=moderate_subscriber($conf->db_host, $conf->db_login, $conf->db_pass, $conf->db_name, $conf->table_temp, $conf->table_sub, $pmnl_id,$mod_addr);
	  $news = new pmnl_newsletter();
	  $news->configurationAssocier($hostname,$login ,$pass,$database, $pmnl_id,$conf->table_listsconfig);
	  
	  $body = $news->subscription_body;
	  $body.= "\n\n".translate("In order to configme your subscription, please click on the following link").":\n";
	  $body.= $conf->base_url.$conf->path."subscription.php?op=confirm_join&email_addr=$mod_addr&hash=$moderated&list_id=$pmnl_id";
	  $mail=sendEmail($conf->sending_method,$mod_addr,$news->from, $news->from_name,$news->subscription_subject,$body, $conf->smtp_auth, $conf->smtp_host, $conf->smtp_login, $conf->smtp_pass);
	  
	}
      else 
	{
	  $moderated=moderate_subscriber($conf->db_host, $conf->db_login, $conf->db_pass, $conf->db_name, $conf->table_email, $conf->table_sub, $pmnl_id,$mod_addr);
	  $news = new pmnl_newsletter();
	  $news->configurationAssocier($hostname,$login ,$pass,$database, $pmnl_id,$conf->table_listsconfig);
	  $body = $news->welcome_body;
	  $body.= "\n\n".translate("Want to unsubscribe ?").":\n";
	  $body.= $conf->base_url.$conf->path."subscription.php?op=confirm_leave&email_addr=$mod_addr&hash=$moderated&list_id=$pmnl_id";
	  $mail=sendEmail($conf->sending_method,$mod_addr,$news->from, $news->from_name,$news->welcome_subject,$body, $conf->smtp_auth, $conf->smtp_host, $conf->smtp_login, $conf->smtp_pass);
	}	
    }
    
    
    
    if($op=="reject")
    {
	$rejected = delete_subscriber($conf->db_host, $conf->db_login, $conf->db_pass, $conf->db_name, $conf->table_sub, $pmnl_id, $mod_addr);
	
    }
    
    
    
    
    $subscribers=get_subscribers($conf->db_host, $conf->db_login, $conf->db_pass, $conf->db_name, $conf->table_sub ,$pmnl_id);
    
    
    if(sizeof($subscribers))
    {
	if($op=="mod"){
	    if($moderated && $mail)
	      echo pmnl_msg_success(translate("Subscription successfully moderated"));
	    else 
	      echo pmnl_msg_error(translate("Error while moderated subscription of")." <b>$mod_addr</b>");
	}
	elseif($op=="reject")
	{
	    if($rejected)
	      
	      echo pmnl_msg_success(translate("Email address successfully deleted"));
	    else
	      echo pmnl_msg_error(translate("Error while deleting")." <b>$mod_addr</b>");
	    
	    
	}
	
	echo translate("Email address waiting for moderation")." :";
	echo "\t<FORM ACTION=\"index.php\" METHOD=\"POST\" NAME=\"mod_form\">\n";
	echo "\t\t<INPUT TYPE=\"hidden\" NAME=\"op\" VALUE=\"mod\">\n";
	echo "\t\t<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\"subscribers\">\n";
	echo "\t\t<INPUT TYPE=\"hidden\" NAME=\"list_id\" VALUE=\"$pmnl_id\">\n";
	echo "\t\t<SELECT NAME=\"mod_addr\">\n";
	
	for($i=0; $i<sizeof($subscribers); $i++){
	    echo "\t\t\t<OPTION VALUE=\"".$subscribers[$i][0]."\" ";
	    echo ">".$subscribers[$i][0]."</OPTION>\n";
	}
	echo "\t\t</SELECT>\n";
	echo "\t\t<INPUT TYPE=\"submit\" VALUE=\"".translate("Accept subscription")."\"><INPUT TYPE=\"button\" NAME=\"reject\" VALUE=\"".translate("Reject subscription")."\" onClick=\"document.mod_form.op.value='reject';document.mod_form.submit()\">\n";
	echo "\t</FORM>\n";
    }
    else 
    {
	if($op=="mod")
	{     
	    if($moderated && $mail)
	      echo pmnl_msg_success(translate("Subscription successfully moderated"));
	    else 
	      echo pmnl_msg_error(translate("Error while moderating")." <b>$mod_addr</b>");
	}
	elseif($op=="reject")
	{
	    if($rejected)
   	      echo pmnl_msg_success(translate("Email address successfully deleted"));
	    else
	      echo pmnl_msg_error(translate("Error while deleting")." <b>$mod_addr</b>");
	}
	
	
	echo pmnl_msg_info(translate("No address to moderate"));
    }
    echo "\t<tr><td>&nbsp;<br></td><td></td></tr>\n";
}

?>
