<?php
       global $phpbb_root_path;
        if (file_exists("./phpBB3/")) {
                $phpBBpath="./phpBB3/";
        } else {
                if (file_exists("../phpBB3")) {
                        $phpBBpath="../phpBB3";
                }
        }



        define('IN_PHPBB', true);
//$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';

        $phpbb_root_path = $phpBBpath;
        echo $php_root_path;

        $phpEx = substr(strrchr(__FILE__, '.'), 1);

require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);



        class phpBBlink {
              var $connection;

              var $user_id="";
              var $user_name="";
              var $user_regdate="";
              var $user_password="";
              var $user_email="";
              var $user_icq=" ";
              var $user_website=" ";
              var $user_occ=" ";
              var $user_from=" ";
              var $user_interests=" ";
              var $user_sig=" ";
              var $user_sig_bbcode_uid=" ";
              var $user_avatar=" ";
              var $user_avatar_type="0";
              var $user_viewemail="0";
              var $user_aim=" ";
              var $user_yim=" ";
              var $user_msnm=" ";
              var $user_attachsig="";
              var $user_allowsmile="1";
              var $user_allowhtml="0";
              var $user_allowbbcode="0";
              var $user_allow_viewonline="1";
              var $user_notify="0";
              var $user_notify_pm="1";
              var $user_popup_pm="1";
              var $user_timezone="0";
              var $user_dateformat="D M d, Y g:i a";
              var $user_lang="english";
              var $user_style="1";
              var $user_level="0";
              var $user_allow_pm="1";
              var $user_active="1";
              var $user_actkey=" ";
              var $user_session=" s";
              var $user_permission=" ";

              function updatepassword() {
                  $this->user_password=md5($this->user_password);
                  $query=sprintf("update ".USERS_TABLE." set user_password = '%s' where user_id='%s'",$this->user_password,$this->user_id);
                  $updpassword = mysql_query($query, $this->connection) or die(mysql_error() );

              }

              function deleteuser() {
                  $query=sprintf("delete from ".USERS_TABLE." where username='%s'",$this->user_name);
                  $updpassword = mysql_query($query, $this->connection) or die(mysql_error() );
              }

              function userexists($username) {
                $query = sprintf("select * from ".USERS_TABLE." where username='%s'",$username);
                $bbq = mysql_query($query,$this->connection) or die (mysql_error() );
                if (mysql_num_rows($bbq)>1) {
                  $row = mysql_fetch_assoc($bbq);
                  return $row['user_id'];
                } else {
                  return -1;
                }
              }

              function adduser() {

                  $this->user_regdate = time();
                  $this->user_avatar_type = "0";
                  $this->user_notify="1";
                  $this->user_password=md5($this->user_password);

                  $query_getuser = sprintf(" select * from ".USERS_TABLE." where user_id=%s ",$this->user_id);
                  $phpBBgetuser = mysql_query($query_getuser, $this->connection) or die(mysql_error() );

                  if (mysql_num_rows($phpBBgetuser)<1) {

                      $user_row['username']=$this->user_name;
                      $user_row['group_id']= 2;
                      $user_row['user_email']= $this->user_email;
                      $user_row['user_type']= 0;
                      $user_row['user_password']= $this->user_password;

                      user_add($user_row, false );

                  }

              }

              function logout($session_id,$user_id) {
                    session_end($session_id,$user_id);
              }


              function createsession() {
                        global $user;
                        global $auth;
                        global $db;
                        global $user_ip;
                        global $phpBBpath;
                        global $connection;
                        global $SID, $_SID, $db, $config, $cache, $phpbb_root_path, $phpEx;


// Start session management
                        $user->session_begin();
                        $auth->acl($user->data);

                        return $user->session_id;


              }

              function login($redirect) {
                        global $user;
                        global $auth;
                        global $db;
                        global $user_ip;
                        global $phpBBpath;
                        global $phpbb_root_path;
                        global $connection;
                        global $SID, $_SID, $db, $config, $cache, $phpbb_root_path, $phpEx;

                        $rurl="";

                        $method = basename(trim($config['auth_method']));
                        if (file_exists($phpbb_root_path . 'includes/auth/auth_' . $method . '.' . $phpEx)) {
                                include_once($phpbb_root_path . 'includes/auth/auth_' . $method . '.' . $phpEx);
                        } else {
                         echo trim($phpbb_root_path . 'includes/auth/auth_' . $method . '.' . $phpEx);
                         exit;
                        }
                        $loginattempt = login_db( $this->user_name, $this->user_password );

//                        $auth->login($this->user_name, $this->user_password,  false, 1,0) ;

                        if ( $logintattempt['status']=3 ) {
                                $userid=$loginattempt['user_row']['user_id'];
                                $user->session_begin();

                // Give us some basic information


                                $user->data['user_id']=$loginattempt['user_row']['user_id'];
                                $auth->acl($user->data);
                                $user->setup('ucp');

                 $sql = sprintf("UPDATE ".SESSIONS_TABLE." set session_user_id=%s WHERE session_id='%s'",$userid,$user->session_id );

                 $userupd = mysql_query($sql, $connection) or die(mysql_error() );

                                return append_sid("");


                      }

                return "";


              }

        }


?>
