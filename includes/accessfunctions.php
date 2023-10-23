<?php
        global $session_contact;
        global $session_contactdetail;
        global $connection;
        global $action;
        global $templatf;
        global $sid;
        global $database_connection;
        global $shopid;
        global $encrypted;
        global $modx;

             
 // FUNCTION TO SIGN IN
function signin($username,$password) {
                    global $session_contact;
                    global $loginfailed;
                    global $defaulttemplate;
                    global $templatefolder;
                    global $header;
                    global $message;
                    global $phpBB;
                    global $wiki;
                    global $sid;
                    global $session;



                    if (MODXLINK) {
                        global $modx;
                    }

                    $ucount=$session_contact->getcontact($username);
                    $validuser=true;
                    $v=true;

                    if ($ucount>0) {
                          if (trim($session_contact->password)!=trim($password)) {
                            $validuser=false;
                          }
                    } else {
                       $validuser=false;
                    }

                    if (!$validuser) {
                        $loginfailed="<div class='failed'>Login failed</div>";
                        $session_contact->userid="";
                        $session_contact->password="";
                    } else {

                        if ( ($session_contact->status=='pending') && CHECKSTATUS) {
                            $defaulttemplate="templates/pending.html";
                            $header = $registrationheader;
                            $message= "Sorry your application to join is still pending";
                        }

                        if (($session_contact->status=='approved') || ($session_contact->status=='active') || (!CHECKSTATUS) ) {



                            if (PHPBBLINK) {
                                $phpBB->user_name = $username;
                                $phpBB->user_password = $password;
                                $phpsid=$phpBB->login('');
                                setcookie("phpsession",$phpsid);
                            }

                           if (WIKILINK) {
                                    $wiki->loginuser( $username,$password );
                           }

                           if (MODXLINK) {
                                    $_SESSION['webShortname']=$username;
                                    $_SESSION['webFullname']=$session_contact->firstname." ".$contact->lastname;
                                    $_SESSION['webEmail']=$session_contact->email;
                                    $_SESSION['webValidated']=1;
                                    $_SESSION['webInternalKey']=$internalKey;
                                    $_SESSION['webValid']=base64_encode($password);
                                    $_SESSION['webUser']=base64_encode($username);
                                    $_SESSION['webFailedlogins']=0;
                                    $_SESSION['webLastlogin']=$lastlogin;
                                    $_SESSION['webnrlogins']=$nrlogins;
                                    $_SESSION['webUserGroupNames'] = ''; // reset user group names
                           }



                           $t=DEFAULT_TEMPLATEbase;
                           if (file_exists($templatefolder.'/content_'.$session_contact->type.'.html')) {
                             $t=$templatefolder.'/content_'.$session_contact->type.'.html';
                           }

                           $session->userid=$session_contact->userid;
                           $session->initsession();
                           $session->timein=date('d/m/Y h:i:s');
                           $session->timeupd=date('d/m/Y h:i:s');
                           $session->active='1';
                           $session->pagename="index.html";
                           $session->deletesessionbyuserid();
                           $session->insertsession();



                           setcookie("csession",$session->session_id);

                           if ($sid>'') {
                                  $loc="content.php".$sid."&template=".$t;
                           } else {
                                  $loc="content.php?template=".$t;
                           }

                           if (isset($_REQUEST['url'])) {
                              if (strlen($_REQUEST['url'])>0 ) {
                                $loc=$_REQUEST['url'];
                              }
                           }

                          header("location: ".$loc );

                        }

                    }

} // end sign in function
// END SIGN IN FUNCTION

function signout() {
 global $session;

 if (isset($_COOKIE['csession'])) {
                $session->sessionid = $_COOKIE['csession'];
                $session->getsessionbysessionid();
                $session->active=false;
                $session->updatesession();
 }  else {
                $session->active=false;
 }

 setcookie('csession','');
 $loc=SIGNOUT_PAGE;
 header("location: ".$loc );

}

function maildetails() {
        global $defaulttemplate;
        global $session_contact;
        global $ucount;
        global $message;
        global $action;
        global $sitename;
        global $siteadminemail;
        global $templatefolder;


          $defaulttemplate=TEMPLATE_FOLDER."index.html";
            $contact=new contact;
            $contact->connection=$connection;

          if (isset($_POST['email'])) {
             $contact->email = $_POST['email'];
             $ucount=$contact->getcontactbyemail($contact->email);
          }

          if ($ucount==1) {
                $message="Hello ".trim($contact->title)." ".trim($contact->lastname)."\n";
                $message=$message." Your login details are as follows: \n";
                $message=$message."user name ".trim($contact->username)."\n";
                $message=$message."password ".trim($contact->password)."\n";
//                        echo $message;exit;

                 mail( $contact->email, $sitename." Details Request", $message ,"from: $siteadminemail " );
                 $message="";
          } // send only if 1 email found
          else {
                $message = "User not found - please retry";
                $action="forgot";
          }
}

function forgotpasswords() {
  global $defaulttemplate;
  global $templatefolder;
  global $s;
  global $forgot;

       $defaulttemplate=TEMPLATE_FOLDER."forgot.html";
       $s="";
       $s=$s."<form method='post' action='?action=mailpassword' >";
       $s=$s."<label>Please enter your email address</label>";
       $s=$s."<input type='text' name='email' id='email' size='40' />";
       $s=$s."<input type='submit' value='email me my password please' /";
       $s=$s."</form>";
       $forgot = $s;
                         
}

// *****************************************************************************
//
//     START UP SCRIPT
//
// *****************************************************************************
         mysql_select_db($database_connection, $connection);

global $action;

if (WIKILINK) {
        require_once("wikilink.php");
        global $wiki;
        $wiki = new wikilink;
}

if (PHPBBLINK) {
        require_once("phpBBlink3.php");
        global $php;
        $phpBB = new phpBBlink;
        $phpBB->connection=$connection;
//        $phpsid=$phpBB->createsession();
}



// *****************************************************************************
//  used for registration only
         if (isset($_GET['checkuser'])) {
                $contact = new contact;
                $contact->connection = $connection;
//                $contact->initcontact();
                if ($contact->getcontact($_GET["checkuser"])<1) {
                // no user found set session inactive
                        echo "false";
                } else {
                        echo "true";
                }
                exit;
         }
// *****************************************************************************

         if (isset($_REQUEST['action'])){
                $action=$_REQUEST['action'];
         }
         
         if (isset($_REQUEST['template'])) {
                $templatef=$_REQUEST['template'];
         }

         $sid="";
         if (isset($_REQUEST['sid'])) {
                $sid=$_REQUEST['sid'];
         }

// ****************************************************
// INITIALISE THE VARIABLES
// ****************************************************


// when multiple items per page
         $offset=0;
         $perpage=10;
         $searchstr="";
         if (isset($_REQUEST['offset'])) {
                $offset=$_REQUEST['offset'];
         }
         if (isset($_REQUEST['iperpage'])) {
                $perpage=$_REQUEST['iperpage'];
         }
         if (isset($_REQUEST['searchstr'])) {
                $searchstr=$_REQUEST['searchstr'];
         }

// set default string variables

         $defaulttemplate="templates/index.html";
         $login="";
         $registertext="";
         $register="";
         $forgottext="";
         $contactlist="";
         $loginfailed="";
         $register="";
         $regfailed="";
         $forgot="";
         $contactlist="";
         $header="";
         $message="";
         $sid="";
         $login="<form method='post'>".
                "<input type='hidden' name='action' value='signin' />".
                "<div class='lwidth'><label>user name</label></div><input type='text' size='10' name='username' /><br/>".
                "<div class='lwidth'><label>password</label></div><input type='password' name='password' size='10' />".
                "<input type='submit' value='Sign In' /> ".
                "</form>";


         $registertext="<a href='?action=register'>Register</a>";
         $forgottext="<a href='?action=forgot'>Forgotten Password</a>";

// setup common objects
         $session_contact = new contact;
         $session_contact->connection = $connection;
//         $contact->initcontact();
         $session_contact->shopid=$shopid;

         $session= new mmsession;
         $session->connection =$connection;
         $session->active=0;
     //    $session->initsession;

// ******************************************************************
// IF IN SESSION COOKIE
// ******************************************************************

                if (USESHOPID) {
                   $session_contact->getcontactbyid($shopid);
                   $session_contactdetail = new contactdetail();

                   $session_contactdetail->shopid=$shopid;
                   $session_contactdetail->connection = $connection;
                   $session_contactdetail->getcontactdetailbyid($session_contact->userid);

                }
                
         if (isset($_COOKIE['csession'])) {

          $session->session_id = $_COOKIE['csession'];

          $session->getsessionbysessionid();

          if ($session_contact->getcontactbyid($session->userid)<1) {
                // no user found set session inactive
                $session->timeupd=date('d/m/Y h:i:s');
                $session->active=0;
                $session->updatesession();
          } else {
                // user found we are logged in
                $defaulttemplate=DEFAULT_TEMPLATEbase;

                if (file_exists($templatefolder.'/content_'.$session_contact->type.'.html')) {
                         $defaulttemplate=$templatefolder.'/content_'.$session_contact->type.'.html';
                }

                $session->timeupd=date('d/m/Y h:i:s');
                $session->updatesession();

                 $session_contactdetail = new contactdetail();

                 $session_contactdetail->shopid=$shopid;
                 $session_contactdetail->connection = $connection;
                 $session_contactdetail->getcontactdetailbyid($session_contact->userid);

          }

         }





               if (isset($_REQUEST['redirect']) ) {
                          header("location: ".$_REQUEST['redirect'] );
                }



// **********************************************************************
// check php session phpbb
// **********************************************************************
         if (isset($_COOKIE['phpsession'])) {
                $phpsid = $_COOKIE['phpsession'];
         }
?>
