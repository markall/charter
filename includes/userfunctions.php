<?php
// Copyright The Timeless Dimension Company 2009
// Modification subject to licence agreement

function setup() {
            if (isset($_GET['table'])) {
                  switch ($_GET['table'])   {
                   case "contactdetail":
                          contactdetail::createcontactdetailtable($connection);
                          break;
                  case "contact":
                        contact::createcontacttable($connection);
                        break;
                  }
            }
}

function updateuser($contact,$contactdetail) {

  global $defaulttemplate;
  global $phpBB;
  global $message;
  global $contactsession;
  global $session_contactdetail;
  global $session_contact;
  global $action;

                   $defaulttemplate=TEMPLATE_FOLDER."members-update.html";
                   $prefix='';
                   $crypt = new crypt();

                   $checkcontact = new contact();


                   if (isset($_REQUEST['prefix'])) {
                       $prefix = $_REQUEST['prefix'];
                   }

                   if (isset($_REQUEST['template'])) {
                        $redirect="content.php?template=".$_REQUEST['template'];
                   }


             //      $contactdetail->getcontactdetailbyid($contact->userid);  

                   if ( ($contactsession->ctype='A') && ($prefix>'') ) {
                       if (isset( $_REQUEST[$prefix.'contactusername'] ) && isset($_REQUEST[$prefix.'contactpassword']) ) {

                         if ($contact->username!=$_REQUEST[$prefix.'contactusername'])  {
                             if (isset($_REQUEST[$prefix.'contactusername'])) {

                                     if ($checkcontact->getcontact( $_REQUEST[$prefix.'contactusername'] )<1) {
                                             $contact->username = $_REQUEST[$prefix.'contactusername'];
                                     }
                             }
                         }
                         if ($contact->password!=$_REQUEST[$prefix.'contactpassword']) {
                                             $contact->password = $_REQUEST[$prefix.'contactpassword'];
                         }
                       }
                   }


                   if (isset($_REQUEST[$prefix.'title'])) {
                           $contact->title = $_REQUEST[$prefix.'title'];
                   }

                   if (isset($_REQUEST[$prefix.'initials'])) {
                           $contact->initials = $_REQUEST[$prefix.'initials'];
                   }

                   if (isset($_REQUEST[$prefix.'firstname'])) {
                           $contact->firstname = $_REQUEST[$prefix.'firstname'];
                   }

                   if (isset($_REQUEST[$prefix.'lastname'])) {
                            $contact->lastname = $_REQUEST[$prefix.'lastname'];
                   }

                   if (isset($_REQUEST[$prefix.'email'])) {
                             $contact->email = $_REQUEST[$prefix.'email'];
                   }


                   if (isset($_REQUEST[$prefix.'dob'])) {
                             $contact->dob = $_REQUEST[$prefix.'dob'];
                   }

                   if (isset($_REQUEST[$prefix.'sex'])) {
                             $contact->sex = $_REQUEST[$prefix.'sex'];
                   }

                   if (isset($_REQUEST[$prefix.'company'])) {
                             $contact->company = $_REQUEST[$prefix.'company'];
                   }

                   if (isset($_REQUEST[$prefix.'address1'])) {
                             $contact->address1 = $_REQUEST[$prefix.'address1'];
                   }

                   if (isset($_REQUEST[$prefix.'address2'])) {
                             $contact->address2 = $_REQUEST[$prefix.'address2'];
                   }

                   if (isset($_REQUEST[$prefix.'town'])) {
                             $contact->town = $_REQUEST[$prefix.'town'];
                   }

                   if (isset($_REQUEST[$prefix.'county'])) {
                             $contact->county = $_REQUEST[$prefix.'county'];
                   }

                   if (isset($_REQUEST[$prefix.'country'])) {
                             $contact->country = $_REQUEST[$prefix.'country'];
                   }

                   if (isset($_REQUEST[$prefix.'postcode'])) {
                             $contact->postcode = $_REQUEST[$prefix.'postcode'];
                   }

                   if (isset($_REQUEST[$prefix.'telephone1'])) {
                             $contact->telephone1 = $_REQUEST[$prefix.'telephone1'];
                   }

                   if (isset($_REQUEST[$prefix.'telephone2'])) {
                             $contact->telephone2 = $_REQUEST[$prefix.'telephone2'];
                   }
                   if (isset($_REQUEST[$prefix.'mobile'])) {
                             $contact->mobile = $_REQUEST[$prefix.'mobile'];
                   }

                   if (isset($_REQUEST[$prefix.'email'])) {
                             $contact->email = $_REQUEST[$prefix.'email'];
                   }

                   if (isset($_REQUEST[$prefix.'webaddress'])) {
                             $contact->webaddress = $_REQUEST[$prefix.'webaddress'];
                   }

                   if (isset($_REQUEST[$prefix.'description'])) {
                             $contact->description = $_REQUEST[$prefix.'description'];
                   }

                   if (isset($_REQUEST[$prefix.'mtype'])) {
                           $contact->type = $_REQUEST[$prefix.'mtype'];
                   }

                   if (isset($_REQUEST[$prefix.'parentcontactid'])) {
                           $contact->parentcontactid = $_REQUEST[$prefix.'parentcontactid'];
                   }

                   if (isset($_REQUEST[$prefix.'parentcontactidencrypted' ])) {
                           $contact->parentcontactid = $crypt->decrypt(rawurldecode($_REQUEST[$prefix.'parentcontactidencrypted']));
                   }

                   if (isset($_REQUEST[$prefix.'status'])) {
                             $contact->status = $_REQUEST[$prefix.'status'];
                   }


                   if (isset($_REQUEST['oldp'])) {
                      if ($_REQUEST['oldp']==$contact->password) {
                       if ($_REQUEST['newp']>' ') {
                           $contact->password=$_REQUEST['newp'];
                           if (PHPBBLINK) {
                                $phpBB->user_id = $phpBB->userexists($contact->username);
                                $phpBB->user_password = $contact->password;
                                        if ($phpBB->user_id>0) {
                                           $phpBB->updatepassword();
                                        }
                                   }
                            }
                        } else {
                          if ($_REQUEST['oldp']>' ') {
                              $message=$message." The old password was incorrect so the new password has not been updated";
                          }
                        }
                   }

					if (isset($_REQUEST[$prefix.'rental'])) {							
                           if (isset($contact->rentals)) {
                                   foreach ($contact->rentals as $key=>$value) {
                                      $contact->deletefromrental($value);
                                   }
                           }
						   
                           // the add the new rental
                            unset($contact->rental);						   
                           if (isset($_REQUEST[$prefix.'rentalidlist'])) {
                               if (count($_REQUEST[$prefix.'rentalidlist'])>0) {
                                  foreach ($_REQUEST[$prefix.'rentalidlist'] as $key=>$value) {								
                                                  $contact->addtorental($value);
                                                  $contact->rentals[]=$value;
                                           }

                                        }

                              }

                              if (isset( $_REQUEST[$prefix.'rentaldeleteflag'] )) {
                                  foreach ($_REQUEST[$prefix.'rentaldeleteflag'] as $key=>$value) {
                                        if ($value==1) {
                                           $contact->deletefromrental($key);
                                        }
                                   }
                              }

					}
					
                       if (isset($_REQUEST[$prefix.'category'])) {
                           //category
                           // first get the old category and delete it

                           if (isset($contact->categories)) {
                                   foreach ($contact->categories as $key=>$value) {
                                      $contact->deletefromcategory($value);
                                   }
                           }

                           // the add the new category
                            unset($contact->categories);

                            if (isset($_REQUEST[$prefix.'categoryparentidlist'])) {
                               if (count($_REQUEST[$prefix.'categoryparentidlist'])>0) {
                                  foreach ($_REQUEST[$prefix.'categoryparentidlist'] as $key=>$value) {
                                                  $contact->addtocategory($value);
                                                  $contact->categories[]=$value;
                                           }

                                        }

                              }

                              if (isset( $_REQUEST[$prefix.'categorydeleteflag'] )) {

                                  foreach ($_REQUEST[$prefix.'categorydeleteflag'] as $key=>$value) {
                                        if ($value==1) {
                                           $contact->deletefromcategory($key);
                                        }
                                   }

                              }

                       }


                   $contact->updatecontactbyid($contact->userid);

                       if (isset($_REQUEST[$prefix.'mclass'])) {
                              //1 , 21  or 51
                              // Relates to the amount of Subs they pay
                              // A = up to 20 employees  - £40
                              // B = up to 50 employees - £70
                              // C = over 50 employees - £95
                             $contactdetail->mclass = $_REQUEST[$prefix.'mclass'];
                       }

                       if (isset($_REQUEST[$prefix.'newsletter'])) {
                                 $contactdetail->newsletter = $_REQUEST[$prefix.'newsletter'];
                       }

                       if (isset($_REQUEST['newsletteremail'])) {
                                 $contactdetail->newsletteremail = $_REQUEST[$prefix.'newsletteremail'];
                       }

                   $contactdetail->updatecontactdetailbyid($contact->userid);

                   if ( isset($_FILES['upload']['name']) ) {
                           if ( $_FILES['upload']['name'][0]>'' ) {;
                                   $dir[]="profiles";
                                   $dir[]=$contact->userid;
                                   $contactdetail->deletemedia( $contact->userid,'profile','' );
                                   $addedmedia = uploadfiles(  $dir , false );
                                   $media = array();
                                   foreach ($addedmedia as $key=>$value) {

                                      $media['mediaid'] = 0;
                                      $media['mediaf'] = $value;
                                      $media['medianame'] = 'profile';
                                      $contactdetail->media[] = $media;

                                      $contactdetail->insertmedia( $contact->userid, $media );
                                   }

                                   $contactdetail->media=array_merge($addedmedia,$contactdetail->media);
                          }

                   }
                 return $contact;

}

function createuser($action) {


  global $defaulttemplate;
  global $phpBB;
  global $message;
  global $regfailed;
  global $wiki;
  global $registrationemailadminmessage;
  global $session;
  global $shopid;
  global $connection;
  global $session_contactdetail;
  global $session_contact;



                $contact= new contact();
                $contact->shopid=$shopid;
                $contact->connection=$connection;

                $contactdetail= new contactdetail();

                   $redirect="";
                   $regfailed="";
                   $defaulttemplate=TEMPLATE_FOLDER."admin/registration.html";

//                  if ($_REQUEST['password']!=$_REQUEST['validpassword']) {
//                        $regfailed = "Please check your passwords, they do not match";
//                   }


                if (isset($_REQUEST['prefix'])) {
                        $prefix =$_REQUEST['prefix'];

                }

                if (isset($_REQUEST['template'])) {
                        $redirect="content.php?template=".$_REQUEST['template'];
                }


                 if (isset($_REQUEST[$prefix.'username'])) {
                           $contact->username = $_REQUEST[$prefix.'username'];
                   } else {
                           $contact->username = $_REQUEST[$prefix.'email'];
                   }

                   if (isset($_REQUEST[$prefix.'password']) ) {
                           $contact->password = $_REQUEST[$prefix.'password'];
                   } else {
                           // 48 - 0 57 - 9  65 - A 90 -Z    97 - a 122 - z
                           $contact->password = chr(rand(65,90)).chr(rand(65,90)).chr(rand(48,57)).chr(rand(48,57)).chr(rand(97,122)).chr(rand(97,122));
                   }

                   if (isset($_REQUEST[$prefix.'mtype'])) {
                           $contact->type = $_REQUEST[$prefix.'mtype'];
                   }

                   if (isset($_REQUEST[$prefix.'parentcontactid'])) {
                           $contact->parentcontactid = $_REQUEST[$prefix.'parentcontactid'];
                   }

                   if (isset($_REQUEST[$prefix.'title'])) {
                           $contact->title = $_REQUEST[$prefix.'title'];
                   }

                   if (isset($_REQUEST[$prefix.'initials'])) {
                           $contact->initials = $_REQUEST[$prefix.'initials'];
                   }

                   if (isset($_REQUEST[$prefix.'firstname'])) {
                           $contact->firstname = $_REQUEST[$prefix.'firstname'];
                   }

                   if (isset($_REQUEST[$prefix.'lastname'])) {
                            $contact->lastname = $_REQUEST[$prefix.'lastname'];
                   }

                   if (isset($_REQUEST[$prefix.'email'])) {
                             $contact->email = $_REQUEST[$prefix.'email'];
                   }

                   if (isset($_REQUEST[$prefix.'company'])) {
                            $contact->company = $_REQUEST[$prefix.'company'];
                   }

                   if (isset($_REQUEST[$prefix.'webaddress'])) {
                             $contact->webaddress = $_REQUEST[$prefix.'webaddress'];
                   }

                   if (isset($_REQUEST[$prefix.'dob'])) {
                             $contact->dob = $_REQUEST[$prefix.'dob'];
                   }

                   if (isset($_REQUEST[$prefix.'sex'])) {
                             $contact->sex = $_REQUEST[$prefix.'sex'];
                   }

                   if (isset($_REQUEST[$prefix.'address1'])) {
                             $contact->address1 = $_REQUEST[$prefix.'address1'];
                   }

                   if (isset($_REQUEST[$prefix.'address2'])) {
                             $contact->address2 = $_REQUEST[$prefix.'address2'];
                   }

                   if (isset($_REQUEST[$prefix.'town'])) {
                             $contact->town = $_REQUEST[$prefix.'town'];
                   }

                   if (isset($_REQUEST[$prefix.'county'])) {
                             $contact->county = $_REQUEST[$prefix.'county'];
                   }
                   if (isset($_REQUEST[$prefix.'postcode'])) {
                             $contact->postcode = $_REQUEST[$prefix.'postcode'];
                   }

                   if (isset($_REQUEST[$prefix.'telephone1'])) {
                             $contact->telephone1 = $_REQUEST[$prefix.'telephone1'];
                   }
                   if (isset($_REQUEST[$prefix.'telephone2'])) {
                             $contact->telephone2 = $_REQUEST[$prefix.'telephone2'];
                   }
                   if (isset($_REQUEST[$prefix.'mobile'])) {
                             $contact->mobile = $_REQUEST[$prefix.'mobile'];
                   }

                   if (isset($_REQUEST[$prefix.'email'])) {
                             $contact->email = $_REQUEST[$prefix.'email'];
                   }

                   if (isset($_REQUEST[$prefix.'status'])) {
                             $contact->status = $_REQUEST[$prefix.'status'];

                   }

                   if (isset($_REQUEST[$prefix.'description'])) {
                             $contact->description = $_REQUEST[$prefix.'description'];
                   }

                   if (isset($_REQUEST[$prefix.'redirect2'])) {
                       $redirect = $_REQUEST[$prefix.'redirect2'];
                   }




                   if (!USEADMIN) {
                        $contact->status = 'active';
                   }

                   if ($regfailed<" ") {

                        $ucount=$contact->getcontact($contact->username);

                        if (($ucount>0) && ($contact->username>"")) {
                           $regfailed="Sorry The user ".$contact->username." exists";
                           $contact->contact();
                           $contactdetail->contactdetail();
                           if ($redirect>'') {
                                    header("location:".$redirect );
                           }
                        } else {

                          // insert the user "
                          $ucount=$contact->getcontactbyemail($contact->email);

                          if ($ucount>0) {
                              $regfailed="The email ".$contact->email." is already in use, please enter another or request  password details.";
                              if (isset($_REQUEST[$prefix.'failed'])) {
                                       $redirect = $_REQUEST[$prefix.'failed'];
                              }
                          } else {

                                
                                  $userid = $contact->insertcontact();

                                  $contact->userid=$userid;

                                 // insert contact detail

                                   $contactdetail->contactid=$userid;

					if (isset($_REQUEST[$prefix.'rental'])) {							
                           if (isset($contact->rentals)) {
                                   foreach ($contact->rentals as $key=>$value) {
                                      $contact->deletefromrental($value);
                                   }
                           }
						   
                           // the add the new rental
                            unset($contact->rental);						   
                           if (isset($_REQUEST[$prefix.'rentalidlist'])) {
                               if (count($_REQUEST[$prefix.'rentalidlist'])>0) {
                                  foreach ($_REQUEST[$prefix.'rentalidlist'] as $key=>$value) {
                                                  $contact->addtorental($value);
                                                  $contact->rentals[]=$value;
                                           }

                                        }

                              }

                              if (isset( $_REQUEST[$prefix.'rentaldeleteflag'] )) {
                                  foreach ($_REQUEST[$prefix.'rentaldeleteflag'] as $key=>$value) {
                                        if ($value==1) {
                                           $contact->deletefromrental($key);
                                        }
                                   }
                              }

					}

					
                                   if (isset($_REQUEST['category'])) {
                                        $contact->addtocategory($_REQUEST['category']);
                                   }

                                   if (isset($_REQUEST['categorybyname'])) {
                                        include_once INCLUDES_FOLDER."db_category.php";
                                        $category= new category();
                                        $category->getcategory($shopid,$_REQUEST['categorybyname']);
                                        if ($category->id>0) {
                                         $contact->addtocategory($category->id);
                                        }
                                   }

                                  if (isset($_REQUEST[$prefix.'referralsource'])) {
                                        $contactdetail->referralsource=$_REQUEST[$prefix.'referralsource'];
                                   }


                                   if (isset($_REQUEST[$prefix.'newsletter'])) {
                                      $contactdetail->newsletter = $_REQUEST[$prefix.'newsletter'];
                                   }

                                   if (isset($_REQUEST[$prefix.'newsletteremail'])) {
                                      $contactdetail->newsletteremail = $_REQUEST[$prefix.'newsletteremail'];
                                   }




                                   $contactdetail->insertcontactdetail();

                                   $dir[]="profiles";
                                   $dir[]=$userid;

                                   unset ( $contactdetail->media );

                                   $contactdetail->media = uploadfiles(  $dir , false );

                                   foreach ($contactdetail->media as $key=>$value) {
                                        $media['mediaf'] = $value;
                                        $media['medianame'] = 'profile';
                                        $contactdetail->insertmedia( $userid, $media );
                                   }



                           if ($action=="createuser") {

                                  if (!USEADMIN) {
                                      if (WIKILINK) {
                                              $wiki->mName= $contact->username;
                                              $wiki->mPassword  =   $contact->password;
                                              $wiki->mRealname =  $contact->firstname." ".$contact->lastname;
                                              $wiki->mEmail = $contact->email;
                                              $wiki->adduser();
                                      }
                                       if (PHPBBLINK) {
                                          $phpBB->user_id = $phpBB->userexists($contact->username);
                                          if ($phpBB->user_id>-1) {
                                            $phpBB->user_password = $contact->password;
                                            $phpBB->updatepassword();
                                          } else {
                                            $phpBB->user_name = $contact->username;;
                                            $phpBB->user_password = $contact->password;
                                            $phpBB->user_email =  $contact->email;
                                            $phpBB->adduser();
                                          }
                                       }

                                       if (MODXLINK) {
                                           // modx
                                                insertwebuser( $contact->username, $contact->password, $contact->email ,$contact->firstname." ".$contact->lastname,$contact->postcode,$contact->county,$contact->country);
                                       }

                                  }


                                  if (isset($_REQUEST['paynow'])) {
                                    if ($_REQUEST['paynow']==1) {
                                          $price = $_REQUEST['price'];
                                          $productid= $_REQUEST['productid'];
                                          $description= $_REQUEST['description'];
                                          $subscribefrom= $_REQUEST['subscribefrom'];
                                          $subscribeto= $_REQUEST['subscribeto'];
                                          $paytype='S';
                                          $contactid=$contact->userid;
                                          $qty='1';
                                          $tax='0';
                                          $action='sagepay';
                                          $q = "price=$price&productid=$productid&description=$description&subscribefrom=$subscribefrom";
                                          $q = $q."&subscribeto=$subscribeto&paytype=$paytype&contactid=$contactid&qty=$qty&tax=$tax&action=$action";
                                          $q = $q."&template=templates/sageform.html";
                                          header("location: content.php?".$q);
                                          exit;

                                    }
                                  }


                                  if ($redirect>' ') {
                                        header("location:".$redirect );
                                        exit;
                                  }



                                  $message=$registrationemailadminmessage;

                                  if (isset($_POST['source'])) {
                                     $message=$message."source ".$_POST['source']."\n";
                                  }

                                   $message = str_replace("<%contactusername%>", $contact->username,$message);

                                   if (!$_SERVER['SERVER_ADDR']=='127.0.0.1') {
                                           mail( $siteadminemail, $sitename." New Application To Register", $message,"from: $contact->email " );
                                           mail( "mark@merchantmakers.co.uk", $sitename." New Application To Register", $message,"from: $contact->email " );
                                   }

                                   if (CHECKSTATUS) {
                                      $loc=THANKYOU_PAGE;
                                      header("location:".$loc );
                                   } else {
                                          $defaulttemplate=TEMPLATE_FOLDER."registered.html";
                                          signin($contact->username,$contact->password);
                                   }
                                $session_contactdetail=$contactdetail;
                                $session_contact=$contact;
                              }

                          }
                        }


                }
               return $contact;

}
?>
