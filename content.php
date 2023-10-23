<?php
	
 error_reporting(0);
global $action;

    session_start();

    require_once('Connections/connection.php');
    include_once "config/errorhandling.php";

    include "config/sitedetails.php";


    include INCLUDES_FOLDER."standardfunctions.php";
    include INCLUDES_FOLDER."db_contact.php";
    include INCLUDES_FOLDER."db_session.php";

        //Includes
    if (SUBSCRIPTIONS==1) {
        include_once INCLUDES_FOLDER."db_subscription.php";
    }
    include_once INCLUDES_FOLDER."extendedfunctions.php";
    include_once INCLUDES_FOLDER."userfunctions.php";
    include_once INCLUDES_FOLDER."db_contactdetail.php";
    if (PRODUCTS==1) {
            include_once INCLUDES_FOLDER."db_product.php";
    }
    include_once INCLUDES_FOLDER."generatepage.php";

    if (MODXLINK==1) {
        include_once INCLUDES_FOLDER."modx_i.php";
        global $modx;
     }




    include_once INCLUDES_FOLDER."accessfunctions.php";

    $prefix="";

    if (FACEBOOK==1) {
        include_once INCLUDES_FOLDER."db_facebook.php";
        include_once INCLUDES_FOLDER."facebookfunctions.php";
    }


// ***********************************************************************
// MAIN CONTROL FLOW DEPENDS ON ACTION
// ***********************************************************************

        if (isset($action) ) {

// ACTION IS SETUP TABLES
           if ($action=="setup") {
                setup();
           }

// *****************************************************************************
// USER ACCESS FUNCTIONS
// *****************************************************************************

// ACTION TO SIGN OUT
           if ($action=="signout") {
                signout();
           }

// ACTION TO SIGN IN
               if ($action=="signin") {
                    $defaulttemplate=TEMPLATE_FOLDER."index.html";
                    $username =$_POST['username'];
                    $password =$_POST['password'];
                    signin($username,$password);
               }

// ACTION TO REGISTER
               if ($action=="register") {
                        register();
               }

// ACTION MAIL PASSWORD
               if ($action=="mailpassword") {
                        maildetails();
               }   // if mailpassword

// ACTION FORGOT PASSWORD
               if ($action=="forgot") {
                  forgotpasswords();
               }

//******************************************************************************
// USER FUNCTIONS
//******************************************************************************
         if ($session->active) {
// ACTION EDIT USER
               if ($action=="edit") {
                   $defaulttemplate=TEMPLATE_FOLDER."admin/editcontact.html";
                   $session_contactdetail->getcontactdetailbyid($session_contact->userid);
               }

// ACTION UPDATE USER
               if ( $action=="updateuser" ) {
                   if (isset($_POST['pid'])) {
                      $crypt= new crypt();
                      $pid = $crypt->decrypt( rawurldecode($_POST['pid']) );
                      $contact = new contact();
                      $contact->connection=$connection;
                      $contactdetail = new contactdetail();
                      $contactdetail->connection=$connection;
                      $contact->getcontactbyid( $pid );
                      $contactdetail->getcontactdetailbyid($pid) ;
                      updateuser($contact,$contactdetail);
                      $action="adminedit";
                      if (isset($_REQUEST['pid']) ) {
                        $prefix = $_REQUEST['pid'];
                      } else {
                        $prefix="p_";
                      }
                   } else {
                       updateuser($session_contact,$session_contactdetail);
                   }
               }

// ACTION CREATE NEW USER
               if ( ($action=="createuser")  || ($action=="insertuser") ){
                   $defaulttemplate=TEMPLATE_FOLDER."admin/registration.html";
                      
                   $contact = createuser( $action );

                   $contactdetail = new contactdetail();
                   $contactdetail->getcontactdetailbyid($contact->userid);

               }

// *****************************************************************************
// EXTENDED MODULES
// *****************************************************************************


// ACTION DO BACKUP/RESTORE
                          if ($action=="backup") {
                    include_once INCLUDES_FOLDER."db_backup.php";
                                        dobackup();

                          }
// ACTION INSERT INVOICE
              if ($action=="insertinvoice") {
                    include_once INCLUDES_FOLDER."db_invoice.php";
                    include_once INCLUDES_FOLDER."invoicefunctions.php";

                    $invoiceid= insertinvoice();

              }

// ACTION Export INVOICE
              if ($action=="exportinvoices") {
                    include_once INCLUDES_FOLDER."db_invoice.php";
                    include_once INCLUDES_FOLDER."invoicefunctions.php";
                    $s=exportinvoices("");
                    header('Content-type: text/plain');
                    header('Content-Disposition: attachment; filename="invoices.csv"');
                    echo $s;
                    exit;

              }

// ACTION PAYMENTS
               if ($action=="payments") {
                  $defaulttemplate=TEMPLATE_FOLDER."admin/payments.html";
                  $session_contactdetail->getcontactdetailbyid($contact->userid);
               }

// ACTION PROFILE
               if ($action=="profile") {
                  showprofile();
               }


               if ($action=="json_contact") {
                  contacttojson();   exit;
               }

// ACTION TO SEND A MESSAGE TO A CONTACT
           if ($action=="sendmessage") {
                sendmessageto();
           }

// ACTION TO ADD A TESTIMONIAL/FEEDBACK
           if ($action=="addfeedback") {
                addfeedback();
           }


// ACTION EDIT FROM ADMIN
               if ($action=="adminedit") {
                     adminedit();
               }
               
if ( ($session->active) && ($session_contact->type=="A")) {
// ACTION ADD/UPDATE A CATEGORY
           if ($action=="updatecategories") {
                updatecategory();
           }
// ACTION MANAGE A CATEGORY
           if ($action=="managecategories") {
                $defaulttemplate=TEMPLATE_FOLDER.'/admin/managecategories.html';
           }

// ACTION MANAGE A PRODUCT
           if ($action=="manageproducts") {
                $defaulttemplate=TEMPLATE_FOLDER.'/admin/manageproducts.html';
           }

// ACTION ADD/UPDATE A PRODUCT
           if ($action=="updateproducts") {
                updateproduct();
           }

// ACTION TO ADD AN OFFER
           if ($action=="updateoffers") {
                updateoffer();
           }
// ACTION EVENTS
               if ($action=="updateevents") {
                   updateevent();
               }


// ACTION DELETE CONTACT
               if ($action=="deletecontact") {
                    $templatef=$_REQUEST['template'];
                    deletecontact();
                    adminedit();
               }
// ACTION DELETE CONTACT MEDIA
               if ($action=="deletecontactmedia") {
                  deletecontactmedia();
               }

}



// ACTION SUCCESFULL PAYMENT
               if ($action=="paysuccess") {
                   $templatef=TEMPLATE_FOLDER."sageform-success.html";
                   global $content;
                   approvepayment($contact);

               }
// ACTION FAILED PAYMENT
               if ($action=="sagepay") {
                   $session_contact->getcontactbyid($_REQUEST['contactid']);
               }

// ACTION FAILED PAYMENT
               if ($action=="payfailed") {
                   $templatef=$templatefolder."sageform-failed.html";
                   failpayment($session_contact);
               }

// ACTION DELETE SUBSCRIPTION
               if ($action=="deletesubscription") {
                    $templatef=$_REQUEST['template'];
                    deletesubscription();
                    adminedit();
               }
// ACTION INSERT SUBSCRIPTION
               if ($action=='insertsubscription') {
                   insertsubscription();
                   adminedit();

               }

// ACTION Show Category
               if ($action=="showcategory") {
                  showcategory();
               }

// ACTION Show Category
               if ($action=="showproduct") {
                  showproduct();
               }

// ACTION IS Category Parent
               if ($action=="isparent") {
                 if (isset($_REQUEST['categoryid'])) {
                   $categoryid=$_REQUEST['categoryid'];
                   if (isset($_REQUEST['parentid'])) {
                     include_once "db_category.php";
                     $parentid=$_REQUEST['parentid'];
                         $category= new category();
                         $category->shopid=$shopid;
                        // echo $category->issibling($parentid,$categoryid,0);
                         exit;
                   }
                 }
               }

               if ($action=="getcontactsbycategory") {
                     ajax_getcontactsbycategory( );

               }


               $s='';
//******************************************************************************
//  CHARTER
//******************************************************************************

              if (CHARTER==1) {

                        include_once( INCLUDES_FOLDER."charterfunctions.php" );

                        $s=docharter($action,$s);
               }

               } //if session active
             }     // if action
//******************************************************************************
// PREPARE OUTPUT
//******************************************************************************

                if (!file_exists($templatef) || !$session->active ) {
                    $templatef = $defaulttemplate;
                }

              $s=file_get_contents($templatef);
              if (!isset($contact) ) {
                $contact = new contact();
                $contactdetail= new contactdetail();
              }
              if (isset($_REQUEST['prefix']) ) {
                    $prefix = $_REQUEST['prefix'];
                   } else {
                    $prefix="p_";
              }

              $s = generatepage($s,$session_contact,$session_contactdetail,'');

              $s = generatepage($s,$contact,$contactdetail,$prefix);






 header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        echo tidytags($s);

?>
