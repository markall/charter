<?php

require_once '../facebook-platform/php/facebook.php';

global $defaulttemplate;
global $action;


$appapikey = '4a012cf5241438fdb1ef99b2cfb9dc87';
$appsecret = 'd800cd988fecdb0bb658da0287485f9d';
$facebook = new Facebook($appapikey, $appsecret);

$is_tab = isset($_POST['fb_sig_in_profile_tab']);

$mm_facebook = new mm_facebook();

if (isset($facebook) ) {

        if( !$is_tab ) {
                $user = $facebook->require_login();
        } else {
                  $defaulttemplate=TEMPLATE_FOLDER."facebooktab.html";

        }

        $fb_session_key = md5($facebook->api_client->session_key);
        session_id($fb_session_key);
        session_start();

              // ACTION AUTHORISE FACEBOOK
                      if ($action=="facebook_authorise") {
                           updatefacebookrecord();
                      }

      // ACTION REMOVE FACEBOOK
                      if ($action=="facebook_remove") {
                           deletefacebookrecord();
                      }

                     updatefacebookrecord();
                     $defaulttemplate=TEMPLATE_FOLDER."facebooktab.html";  
}

function updatefacebookrecord() {
$appapikey = '4a012cf5241438fdb1ef99b2cfb9dc87';
$appsecret = 'd800cd988fecdb0bb658da0287485f9d';
$facebook = new Facebook($appapikey, $appsecret);

$is_tab = isset($_POST['fb_sig_in_profile_tab']);

$mm_facebook = new mm_facebook();


  $uid = $_POST["fb_sig_user"];
  if (isset($uid)) {
          $mm_facebook->viewerid = $facebook->user;
          $mm_facebook->profileid = $facebook->canvas_user;
          $mm_facebook->$_SERVER['SERVER_ADDR'];
          $mm_facebook->insertfacebook();
  }


}

function deletefacebookrecord() {
     // if ( ($user_id!=NULL) && ($facebook->fb_params['uninstall']==1) ) {
     //            mm_facebook->deletefacebookbyid($user_id);
                  //The user has removed your app
    //  }
}



?>
