<?php

class Messu extends Tikilib {
  var $db;
  
  function Messu($db) 
  {
    
    if(!$db) {
      die("Invalid db object passed to UsersLib constructor");
    }
    $this->db = $db;
  }
    
  function user_exists($user)
  {
    global $userlib;
    return $userlib->user_exists($user);
  }
  
  function post_message($user,$from,$to,$cc,$subject,$body,$priority)
  {
    global $smarty,$userlib;
    
    $from = addslashes($from);
    $to = addslashes($to);
    $cc = addslashes($cc);
    $subject = addslashes($subject);
    $body = strip_tags(addslashes($body));
    // Prevent duplicates
    $hash = md5($subject.$body);
    if($this->getOne("select count(*) from messu_messages where user='$user' and user_from='$from' and hash='$hash'")) {
      return false;
    }
    
    $now = date('U');
    
    $query = "insert into messu_messages(user,user_from,user_to,user_cc,subject,body,date,isRead,isReplied,isFlagged,priority,hash)
              values('$user','$from','$to','$cc','$subject','$body',$now,'n','n','n',$priority,'$hash')";
    $this->query($query);
    
    // Now check if the user should be notified by email
    if($this->get_user_preference($user,'minPrio',6)<=$priority) {
      $smarty->assign('mail_site',$_SERVER["SERVER_NAME"]);
      $smarty->assign('mail_date',date("U"));
      $smarty->assign('mail_user',$user);
      $smarty->assign('mail_from',$from);
      $smarty->assign('mail_subject',$subject);
      $smarty->assign('mail_body',$body);
      $mail_data = $smarty->fetch('mail/messu_message_notification.tpl');
      $email = $userlib->get_user_email($user);
      if($email) {
        print("sale mail a $email");
        @mail($email, 'New message arrived from '.$_SERVER["SERVER_NAME"], $mail_data);
      }
    }
    
    return true;          
  }

  function validate_user($user,$pass)
  {
    global $userlib;
    $cant = $userlib->validate_user($user,$pass,'','');
    return $cant;
  }
  
  function user_unread_messages($user)
  {
    $cant = $this->getOne("select count(*) from messu_messages where user='$user' and isRead='n'");
    return $cant;
  }
  
  function list_user_messages($user,$offset,$maxRecords,$sort_mode,$find,$flag='',$flagval='',$prio='')
  {
    if($prio) {
     $prio = " and priority=$prio ";
    }
    if($flag) {
      // Process the flags
      $flag = " and $flag='$flagval' ";
    } 
    $sort_mode = str_replace("_desc"," desc",$sort_mode);
    $sort_mode = str_replace("_asc"," asc",$sort_mode);
    if($find) {
      $mid=" and (subject like '%".$find."%' or body like '%".$find."%')".$flag.$prio;  
    } else {
      $mid="".$flag.$prio; 
    }
    $query = "select * from messu_messages where user='$user' $mid order by $sort_mode,msgId desc limit $offset,$maxRecords";
    $query_cant = "select count(*) from messu_messages where user='$user' $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["len"]=strlen($res["body"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function flag_message($user, $msgId, $flag, $val)
  {
    $query = "update messu_messages set $flag='$val' where user='$user' and msgId=$msgId";
    $this->query($query);
  }
  
  function delete_message($user, $msgId)
  {
    $query = "delete from messu_messages where user='$user' and msgId=$msgId";
    $this->query($query);
  }
  
  function get_next_message($user,$msgId, $sort_mode, $find, $flag, $flagval,$prio)
  {
    if($prio) {
     $prio = " and priority=$prio ";
    }
    if($flag) {
      // Process the flags
      $flag = " and $flag='$flagval' ";
    } 
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (subject like '%".$find."%' or body like '%".$find."%')".$flag.$prio;  
    } else {
      $mid="".$flag.$prio; 
    } 
    $query = "select min(msgId) from messu_messages where user='$user' and msgId>$msgId $mid order by $sort_mode,msgId desc limit 0,1";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    if(!$res) return false;
    return $res['min(msgId)'];
  }
  
  function get_prev_message($user,$msgId, $sort_mode, $find, $flag, $flagval,$prio)
  {
    if($prio) {
     $prio = " and priority=$prio ";
    }
    if($flag) {
      // Process the flags
      $flag = " and $flag='$flagval' ";
    } 
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (subject like '%".$find."%' or body like '%".$find."%')".$flag.$prio;  
    } else {
      $mid="".$flag.$prio; 
    } 
    $query = "select max(msgId) from messu_messages where user='$user' and msgId<$msgId $mid order by $sort_mode,msgId desc limit 0,1";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    if(!$res) return false;
    return $res['max(msgId)'];    
  }
  
  function get_message($user,$msgId)
  {
    $query = "select * from messu_messages where user='$user' and msgId='$msgId'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }
 
}

$messulib = new Messu($dbTiki);

?>