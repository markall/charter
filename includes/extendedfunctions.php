<?php
function showprofile() {

   global $templatef;
   global $s;
   global $session_contact;
   global $session_contactdetail;

   $crypt = new crypt();

                  if (isset($_REQUEST['pid'])) {

                         $pid = $crypt->decrypt(rawurldecode($_REQUEST['pid']));
                         $templatef = TEMPLATE_FOLDER."profile.html";
                         if (isset($_REQUEST['template'])) {
                            $templatef = $_REQUEST['template'];
                         }
                         if (file_exists( $templatef ) ) {
                           $s=getfile($templatef);  // the template
                          }

                          $contactp = new contact();
                          $contactp->getcontactbyid( $pid );
                          $contactdetailp = new contactdetail();
                          $contactdetailp->getcontactdetailbyid($pid );

                          $s = generatepage($s,$contactp,$contactdetailp,'p_');
                          $s = generatepage($s,$session_contact,$session_contactdetail,'');

                          header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
                          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

                          echo tidytags($s);
                          exit;
                     }
}

function contacttojson() {

   global $s;
   global $session_contact;
   global $session_contactdetail;

   $crypt = new crypt();

                  if (isset($_REQUEST['pid'])) {

                         $pid = $crypt->decrypt(rawurldecode($_REQUEST['pid']));

                          $contactp = new contact();
                          $contactp->getcontactbyid( $pid );
                          $contactdetailp = new contactdetail();
                          $contactdetailp->getcontactdetailbyid($pid );




                           $json .= objecttojson($json,$contactp );
                           $json .= objecttojson($json,$contactdetailp );

                           $json = substr_replace($json, '', -1); // to get rid of extra comma


                           echo "{".$json."}";

                          exit;
                     }

}

function adminedit() {
  global $shopid;
  global $session_contact;
  global $session_contactdetail;

  $crypt = new crypt();

  if (isset($_REQUEST['pid'])) {

    if ($_REQUEST['pid']!=-1) {
       $pid = $crypt->decrypt(rawurldecode($_REQUEST['pid']));
    } else {
       $pid=-1;
    }


  ;
    $prefix= $_REQUEST['prefix'];
    $template = $_REQUEST['template'];
    $s=file_get_contents($template);


    $contactp = new contact();
    $contactp->getcontactbyid( $pid );

//    var_dump($_REQUEST);exit;
    $contactdetailp = new contactdetail();
    $contactdetailp->getcontactdetailbyid($pid );

    $s = generatepage($s,$contactp,$contactdetailp,$prefix);

    $s = generatepage($s,$session_contact,$session_contactdetail,'');

                          header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
                          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

                          echo tidytags($s);
                          exit;
  }

}


function sendmessageto() {
  global $defaulttemplate;


                 $mcontact = new contact();
                 $mcontact->connection=$connection;

                 $mcontact->getcontactbyid($_REQUEST['mcontactid']);
                 $fromname=$_REQUEST['fromname'];
                 $fromemail=$_REQUEST['fromemail'];
                 $fromtelephone=$_REQUEST['fromtelephone'];
                 $message=$_REQUEST['message'];
                 $subject=$_REQUEST['subject'];

                 $message=$message."\n".$fromname."\n".$fromtelephone;

//                 mail($mcontact->email,$subject,$message, "from: $fromemail"  );

                 $defaulttemplate=TEMPLATE_FOLDER.'/messagethankyou.html';

}

function addfeedback() {
  global $shopid;

  include_once INCLUDES_FOLDER."db_feedback.php";

                 $mcontact = new contact();
                 $mcontact->connection=$connection;

                 $mcontact->getcontactbyid($_REQUEST['mcontactid']);
                 $fromname=$_REQUEST['fromname'];
                 $fromemail=$_REQUEST['fromemail'];
                 $fromtelephone=$_REQUEST['fromtelephone'];
                 $message=$_REQUEST['message'];
                 $subject=$_REQUEST['subject'];

                 $feedback = new feedback();

                 $feedback->shopid = $mcontact->shopid;
                 $feedback->contactid = $_REQUEST['mcontactid'];
                 $feedback->reviewerid=0;
                 $feedback->productid=0;
                 $feedback->orderid=0;
                 $feedback->rating=$_REQUEST['rating'];
                 $feedback->title=$_REQUEST['title'];
                 $feedback->comment=$message;
                 $feedback->name=$fromname;
                 $feedback->publish=0;
                 $feedback->ip= $_SERVER['SERVER_ADDR' ];

                 $feedback->insertfeedback();


}

function showcategory() {
    global $templatef;
    global $shopid;
    global $category;
    include_once  INCLUDES_FOLDER."db_category.php";

    $category=new category();
    $category->shopid=$shopid;
    $category->connection = $connection;

    if (isset($_REQUEST['categoryid'])) {
        $categoryid = $_REQUEST['categoryid'];
        $category->getcategorybyid($shopid,$categoryid);
    }

    if (isset($_REQUEST['categorycode'])) {
        $categorycode = $_REQUEST['categorycode'];
        $category->getcategory($shopid,$categorycode);
    }

    if ($category->id>0) {
        $template="<%category_code%>";
        $templatef = TEMPLATE_FOLDER."/shop/categorypage.html";

        if (file_exists($category->listtemplate)) {
              $templatef= $category->listtemplate;
        }

    }


}

function showproduct() {
    global $templatef;
    global $shopid;

    include_once  INCLUDES_FOLDER."db_category.php";
    include_once  INCLUDES_FOLDER."db_product.php";

    $category=new category();
    $category->shopid=$shopid;

    $product=new product();
    $product->shopid=$shopid;

    $template="<%category_code%>";
    $templatef = TEMPLATE_FOLDER."shop/productpage.html";

    if (isset($_REQUEST['categoryid'])) {
        $categoryid = $_REQUEST['categoryid'];
        if ($category->getcategorybyid($shopid,$categoryid)>0) {
          if (file_exists($category->prodpagetemplate)) {
              $templatef= $category->prodpagetemplate;
          }
        }
    }

    if (isset($_REQUEST['productid']) ) {
        $productid = $_REQUEST['productid'];
        if ($product->getproductbyid($shopid,$productid)>0) {
          $template="<%product_code%>";
          if (file_exists($product->template)) {
              $templatef= $product->template;
          }
        }
    }


}

function updatecategory() {
 global $shopid;
 global $connection;

    include_once  INCLUDES_FOLDER."db_category.php";

    $category=new category();

    $category->shopid=$shopid;
    $category->connection = $connection;

    if (isset($_REQUEST['categoryid'])) {
        $categoryid = $_REQUEST['categoryid'];

        if ($categoryid>0) {
           if ($category->getcategorybyid($shopid,$categoryid)>0) {
               $category->code = $_REQUEST['categorycode'];
               $category->title = $_REQUEST['categorytitle'];
               $category->header = $_REQUEST['categoryheader'];
               $category->footer = $_REQUEST['categoryfooter'];
               $category->orderstr = $_REQUEST['categoryorderstr'];


             if (isset($_REQUEST['categoryparentidlist'])) {
               if (count($_REQUEST['categoryparentidlist'])>0) {
                   foreach ($_REQUEST['categoryparentidlist'] as $key=>$value) {
                      if (!$category->isincategory($value)) {
                           $category->addparent($value);
                      }
                   }
                 }
             }


              if (isset( $_REQUEST['parentdeleteflag'] )) {

                  foreach ($_REQUEST['parentdeleteflag'] as $key=>$value) {
                        if ($value==1) {
                           $category->deleteparent($shopid,$category->id,$key);
                        }
                   }

              }

              if (isset( $_REQUEST['mediadeleteflag'] )) {
                  foreach ($_REQUEST['mediadeleteflag'] as $key=>$value) {
                        if ($value==1) {
                           $media = $category->getcategorymediabyid( $shopid, $key );
                           if ($media->id>0) {
                                   if (file_exists(IMAGE_DIR.'categories/'.$category->id.'/'.$media->mediaf ) ){
                                          unlink( IMAGE_DIR.'categories/'.$category->id.'/'.$media->mediaf );
                                   }
                           }
                           $category->deletemedia($shopid, $key , 0 ) ;
                        }
                   }
              }

               if (isset( $_REQUEST['categoryparentid'] )) {
                       $category->parents[] = $_REQUEST['categoryparentid'];
               }

               $category->description = $_REQUEST['categorydescription'];
               $category->ctype = $_REQUEST['categorytype'];
               $category->status = $_REQUEST['categorystatus'];

               if (strtolower($category->status)=='d') {
                       // delete images
                       if (isset($category->media)) {
                               foreach ($category->media as $key=>$value) {
                                   if (file_exists( IMAGE_DIR.'categories/'.$category->id.'/'.$value )) {
                                           unlink( IMAGE_DIR.'categories/'.$category->id.'/'.$value );
                                   }
                               }
                       }

                       $category->deletemedia($shopid, 0 ,$category->id ) ;
                       $category->deleteparent($shopid, $category->id , 0 );
                       $category->deletecontactlink($shopid,$category->id,0);
                       $category->deleteproductlink($shopid,$category->id,0);
                       $category->deleteattributes($shopid,$category->id);
                       $category->delete($shopid,$category->id);

                       // delete contact categories
                       // delete product categories
                       // delete category attributes

               } else {
                       $category->updatecategorybyid($categoryid);
               }


           }
        }  else {
               unset($category->parents);

               $category->code = $_REQUEST['categorycode'];
               $category->title = $_REQUEST['categorytitle'];
               if ($_REQUEST['categoryorderstr']<' ') {
                   $category->orderstr = $_REQUEST['categorytitle'];
               } else {
                   $category->orderstr = $_REQUEST['categoryorderstr'];
               }
               $category->header = $_REQUEST['categoryheader'];
               $category->footer = $_REQUEST['categoryfooter'];
               $category->description = $_REQUEST['categorydescription'];
               $category->ctype = $_REQUEST['categorytype'];
               $category->status = $_REQUEST['categorystatus'];

               if (isset($_REQUEST['categoryparentidlist'])) {
                 if (count($_REQUEST['categoryparentidlist'])>0) {
                   foreach ($_REQUEST['categoryparentidlist'] as $key=>$value) {
                           $category->parents[]=$value;
                   }
                 }
               }

               $category->insertcategory();


        }


        if ( isset($_FILES['upload']['name']) ) {
                           if ( $_FILES['upload']['name'][0]>'' ) {;
                                   $dir[]=IMAGE_DIR;
                                   $dir[]="categories";
                                   $dir[]=$category->id;
                                   $addedmedia = uploadfiles(  $dir , false );

                                   $media = array();
                                   foreach ($addedmedia as $key=>$value) {

                                      $media['mediaid'] = 0;
                                      $media['mediaf'] = $value;
                                      $category->media[] = $media;

                                      $category->addmedia( $value );
                                   }


                          }
         }

    }


}

function updateproduct() {
 global $shopid;
 global $connection;

    include_once  INCLUDES_FOLDER."db_product.php";

    $product=new product();

    $product->shopid=$shopid;
    $product->connection = $connection;

    if (isset($_REQUEST['productid'])) {
        $productid = $_REQUEST['productid'];


        if ($productid>0) {
           if ($_REQUEST['productstatus']=='delete') {
             // delete everything
             
           } else {
               if ($product->getproductbyid($shopid,$productid)>0) {


                   $product->code = $_REQUEST['productcode'];
                   $product->title = $_REQUEST['producttitle'];
                   $product->header = $_REQUEST['productheader'];
                   $product->footer = $_REQUEST['productfooter'];
                   $product->position = $_REQUEST['productposition'];
                   $product->price = $_REQUEST['productprice'];
                   $product->vatcode = $_REQUEST['productvatcode'];
                   $product->vatexempt = $_REQUEST['productvatexempt'];  

                   if (count($_REQUEST['productcategoryid'])>0) {
                     foreach ($_REQUEST['productcategoryid'] as $key=>$value) {
                        if (!$product->isincategory($value)) {
                             $product->addtocategory($value);
                        }
                     }
                   }


                  if (isset( $_REQUEST['categorydeleteflag'] )) {
                      foreach ($_REQUEST['categorydeleteflag'] as $key=>$value) {
                            if ($value==1) {
                               $product->deletecategory($key);
                            }
                       }

                  }

                  if (isset( $_REQUEST['mediadeleteflag'] )) {

                      foreach ($_REQUEST['mediadeleteflag'] as $key=>$value) {
                            if ($value==1) {
                               $media = $product->getmediabyid( $shopid, $key );
                               if ($media->id>0) {
                                       if (file_exists(IMAGE_DIR.'products/'.$product->id.'/'.$media->mediaf ) ){
                                              unlink( IMAGE_DIR.'products/'.$product->id.'/'.$media->mediaf );
                                       }
                                       $product->deletemedia($shopid, 0 , $key );
                               }

                            }
                       }
                  }

                   if (isset( $_REQUEST['productcategoryidid'] )) {
                           $product->parents[] = $_REQUEST['productcategoryid'];
                   }

                   $product->shortdescription = $_REQUEST['productshortdescription'];
                   $product->description = $_REQUEST['productdescription'];
                   $product->ptype = $_REQUEST['producttype'];
                   $product->status = $_REQUEST['productstatus'];

                   if (strtolower($product->status)=='d') {

                    if (isset($product->media)) {
                       foreach ($product->media as $key=>$value) {
                               if (file_exists(IMAGE_DIR.'products/'.$product->id.'/'.$value->mediaf ) ){
                                   unlink( IMAGE_DIR.'products/'.$product->id.'/'.$value->mediaf );
                               }
                       }
                    }
                    $product->deleteattributes($shopid,$product->id);
                    $product->deletefromcategories($shopid,$product->id);
                    $product->deletemedia($shopid,$product->id,0);
                    $product->deleterelations($shopid,$product->id);
                    $product->delete($shopid,$product->id);
                    $productid=-1;
                   } else {
                           $product->updateproduct($productid);
                   }

               }
           } // else if not delete
        }  else {
               unset($product->categories);

               $product->code = $_REQUEST['productcode'];
               $product->title = $_REQUEST['producttitle'];
               if ($_REQUEST['productorderstr']<' ') {
                   $product->orderstr = $_REQUEST['producttitle'];
               } else {
                   $product->orderstr = $_REQUEST['productorderstr'];
               }
               $product->header = $_REQUEST['productheader'];
               $product->footer = $_REQUEST['productfooter'];
               $product->description = $_REQUEST['productdescription'];
               $product->ptype = $_REQUEST['producttype'];
               $product->status = $_REQUEST['productstatus'];
               $product->price = $_REQUEST['productprice'];

               if (isset($_REQUEST['productcategoryid'])) {
                 if (count($_REQUEST['productcategoryid'])>0) {
                   foreach ($_REQUEST['productcategoryid'] as $key=>$value) {
                           $product->categories[]=$value;
                   }
                 }
               }

               $product->insertproduct();


        }


        if ( isset($_FILES['upload']['name']) ) {
                           if ( $_FILES['upload']['name'][0]>'' ) {;
                                   $dir[]=IMAGE_DIR;
                                   $dir[]="products";
                                   $dir[]=$product->id;
                                   $addedmedia = uploadfiles(  $dir , false );

                                   $media = array();
                                   foreach ($addedmedia as $key=>$value) {

                                      $media['mediaid'] = 0;
                                      $media['mediaf'] = $value;
                                      $product->media[] = $media;

                                      $product->addmedia($shopid,$product->id,$value );
                                   }


                          }
         }

    }


}

function updateoffer() {

 global $shopid;
 global $session_contact;

    include_once  INCLUDES_FOLDER."db_offers.php";


    $offer = new offer();
    $offer->shopid=$shopid;
    $offer->contactid=$session_contact->userid;
    $action='insert';

    if (isset($_REQUEST['doid'])) {
            $crypt=new crypt();

            $offer->id=$crypt->decrypt(rawurldecode($_REQUEST['doid']));

            $action='delete';

    }

    if (isset($_REQUEST['offerid'])) {
      $offer->id=$_REQUEST['offerid'];
      if ($offer->id>0) {
        $action='update';
      }
    }

    if (isset($_REQUEST['offertitle'])) {
      $offer->title=$_REQUEST['offertitle'];
    }
    if (isset($_REQUEST['offerdescription'])) {
      $offer->description=$_REQUEST['offerdescription'];
    }
    if (isset($_REQUEST['vouchercode'])) {
      $offer->vouchercode=$_REQUEST['vouchercode'];
    }
    if (isset($_REQUEST['offerstart'])) {
      $offer->start=$_REQUEST['offerstart'];
    }
    if (isset($_REQUEST['offerexpires'])) {
      $offer->expires=$_REQUEST['offerexpires'];
    }
    if (isset($_REQUEST['offertarget'])) {
      $offer->target=$_REQUEST['offertarget'];
    }
    if (isset($_REQUEST['offerstatus'])) {
      $offer->status=$_REQUEST['offerstatus'];
    }


    switch ($action) {
    case 'insert':
            $offer->insertoffer();
            break;
    case 'update':
            $offer->updateofferbyid($offer->id);
            break;
    case 'delete':
            $offer->deleteofferbyid($offer->id);
            break;
    }


}

function updateevent() {

 global $shopid;
 global $session_contact;
 global $session;

    include_once  INCLUDES_FOLDER."db_events.php";

    $event = new event();
    $event->shopid=$shopid;
    $event->contactid=$session_contact->userid;
    $action='insert';

    if (isset($_REQUEST['deleid'])) {
            $crypt=new crypt();

            $event->id=$crypt->decrypt(rawurldecode($_REQUEST['deleid']));
            $action='delete';
               
    }
    if (isset($_REQUEST['eid'])) {
            $crypt=new crypt();

            $event->id= $crypt->decrypt(rawurldecode($_REQUEST['eid']));
    }

    if (isset($_REQUEST['eventid'])) {
      $event->id=$_REQUEST['eventid'];
      if ($event->id>0) {
        $action='update';
      }
    }

    if (isset($_REQUEST['eventtitle'])) {
      $event->title=$_REQUEST['eventtitle'];
    }

    if (isset($_REQUEST['eventdescription'])) {
      $event->description=$_REQUEST['eventdescription'];
    }

    if (isset($_REQUEST['eventlocation'])) {
      $event->location=$_REQUEST['eventlocation'];
    }


    if (isset($_REQUEST['eventpostcode'])) {
      $event->postcode=$_REQUEST['eventpostcode'];
    }

    if (isset($_REQUEST['eventstatus'])) {
      $event->status=$_REQUEST['eventstatus'];
    }

    if (isset($_REQUEST['eventtarget'])) {
      $event->target=$_REQUEST['eventtarget'];
    }

    if (isset($_REQUEST['eventacceptpayment'])) {
      $event->acceptpayment=$_REQUEST['eventacceptpayment'];
    }

    if (isset($_REQUEST['eventstart'])) {

        foreach ($_REQUEST['eventstart'] as $key=>$value) {

                $eventdate=new eventdate();
                $eventdate->shopid=$event->shopid;
                $eventdate->contactid=$session_contact->userid;
                $eventdate->start=$value;
                $eventdate->end=$_REQUEST['eventend'][$key];

                $eventdate->status=$_REQUEST['eventdatestatus'][$key];

                $eventdate->displaytext=$_REQUEST['eventdisplaytext'][$key];
                $eventdate->cost=$_REQUEST['eventcost'][$key];
                $eventdate->availability=$_REQUEST['eventavailability'][$key];

                if (isset($_REQUEST['eventdatelocation'][$key])) {
                   $eventdate->location=$_REQUEST['eventdatelocation'][$key];
                }
                if (isset($_REQUEST['eventdatepostcode'][$key])) {
                   $eventdate->location=$_REQUEST['eventdatepostcode'][$key];
                }
                $eventdate->id=$_REQUEST['eventdateid'][$key];

                if ($session->active) {
                    $eventdate->orderstr=$session_contact->type;
                }

                $event->eventdates[]=$eventdate;
        }

    }



    switch ($action) {
    case 'insert':
            $event->insertevent();
            break;
    case 'update':
            $event->updateevent($event->id);
            break;
    case 'delete':
            $event->deleteeventbyid($event->id);
            break;
    }


}

function approvepayment($contact) {
     global $shopid;
     global $content;
     global $connection;

     include_once INCLUDES_FOLDER."sagepay_includes.php";
     include_once INCLUDES_FOLDER."db_subscription.php";
     include_once INCLUDES_FOLDER."db_product.php";
     include_once INCLUDES_FOLDER."db_events.php";

     $sagepay = new sagepay();
     $sagepay->decrypt($_REQUEST['crypt']);

   '     echo $sagepay->decode_Status;
        echo "<br/>";
        echo $sagepay->decode_StatusDetail;
        echo "<br/>";
        echo $sagepay->decode_VendorTxCode;
        echo "<br/>";
        echo $sagepay->decode_VPSTxId;
        echo "<br/>";
        echo $sagepay->decode_TxAuthNo;
        echo "<br/>";
        echo $sagepay->decode_Amount;
        echo "<br/>";
        echo $sagepay->decode_AVSCV2;
        echo "<br/>";
        echo $sagepay->decode_AddressResult;
        echo "<br/>";
        echo $sagepay->decode_PostCodeResult;
        echo "<br/>";
        echo $sagepay->decode_CV2Result;
        echo "<br/>";
        echo $sagepay->decode_GiftAid;
        echo "<br/>";
        echo $sagepay->decode_3DSecureStatus;
        echo "<br/>";
        echo $sagepay->decode_CAVV;
        echo "<br/>";
        echo $sagepay->decode_CardType;
        echo "<br/>";
        echo $sagepay->decode_Last4Digits;
        echo "<br/>";
        echo $sagepay->decode_AddressStatus; // PayPal transactions only
        echo "<br/>";
        echo $sagepay->decode_PayerStatus;     // PayPal transactions only ' ;

        if ($sagepay->decode_Status=='OK') {
//              1-S-1-20101130-782586420
                $code = explode("-", $sagepay->decode_VendorTxCode);

                $contactid=$code[0];

                $paymenttype=$code[1];

                switch ($paymenttype) {
                case 'S':
                        // update subscription
                        $subscribeto=$code[3];
                        $subscribefrom=$code[4];

                        $product=new product();
                        $product->getproductbyid($shopid,$code[2]);


                        $subscription = new subscription();
                        $subscription->contactid = $contactid;
                        $subscription->start = substr($subscribefrom,6,2).'-'.substr($subscribefrom,4,2).'-'.substr($subscribefrom,0,4);
                        $subscription->expires = substr($subscribeto,6,2).'-'.substr($subscribeto,4,2).'-'.substr($subscribeto,0,4);
                        $subscription->paid = $sagepay->decode_Amount;
                        $subscription->amount = $sagepay->decode_Amount;
                        $subscription->shopid=$shopid;
                        $subscription->productid=$code[2];
                        $subscription->insertsubscription();
                        $message = $contact->title.' '.$contact->firstname.' '.$contact->lastname.' from '.$contact->company;
                        $message = $message.' has renewed his subscription for the period '.$subscription->start.' to '.$subscription->expires;

                        $content = "
        Ref: $subscription->id
                        <br/><br/>

        For $product->title :          $subscription->start to $subscription->expires     <br/><br>

        Please note your authorisation number: $sagepay->decode_TxAuthNo<br/><br/>


        Amount &pound;  $sagepay->decode_Amount<br/><br/>

        </p>";
                        $tcontact= new contact();
                        $tcontact->connection = $connection;
                        $tcontact->getcontactbyid($contactid);
                        $tcontact->status='active';

                        $tcontact->updatecontactbyid($contactid);
                      
                        break; // is a subscription payment
                case 'E':
                        $eventdate=new eventdate();
                        $eventdate->geteventdatebyid($code[2]);

                        $event= new event();
                        $event->geteventbyid($eventdate->eventid);


                        $message = $contact->title.' '.$contact->firstname.' '.$contact->lastname.' from '.$contact->company;
                        $message = $message.' has paid for an place at '.$event->title.' on '.$eventdate->start;

                        $content = "
        Ref: $eventdate->id / $code[0]
                        <br/><br/>

        For 1 place at $event->title on $eventdate->start              <br/><br>

        Please note your authorisation number: $sagepay->decode_TxAuthNo<br/><br/>


        Amount &pound;  $sagepay->decode_Amount<br/><br/>

        </p>";

                        break;  // is an event payment
                }


                     //SITE_ADMINEMAIL.
       //         mail('mark@merchantmakers.co.uk' ,'Maidenhead CofC Subscription renewal',$message,"from: ".SITE_ADMINEMAIL );

        }   //status ok


}

function failpayment($contact) {
                $message = $contact->title.' '.$contact->firstname.' '.$contact->lastname.' from '.$contact->company;
                $message = $message.' has failedto renew their subscription for the period '.$subscription->start.' to '.$subscription->expires;

//SITE_ADMINEMAIL.
                mail('mark@merchantmakers.co.uk' ,'Maidenhead CofC Subscription renewal',$message,"from: ".SITE_ADMINEMAIL );
}

function deletesubscription() {

                    $crypt=new crypt();

                    $sid = $crypt->decrypt(urldecode($_REQUEST['sid']) );
                    $sid=$_REQUEST['sid'];
                    subscription::deletesubscriptionbyid( $sid );
}

function insertsubscription() {
        global $shopid;
        $crypt = new crypt();

                   // update subscription
                   $subscribeto=$_REQUEST['subscribeto'];;
                   $subscribefrom=$_REQUEST['subscribefrom'];
                   $productid=$_REQUEST['productid'];
                   $contactid=$crypt->decrypt(rawurldecode($_REQUEST['pid']));
                   $price = $_REQUEST['price'];

                   $product=new product();
                   $product->getproductbyid($shopid,$productid);

 //                echo $contactid." ".$productid." here";exit;

                   $subscription = new subscription();
                   $subscription->contactid = $contactid;

                   $subscription->start = $subscribefrom;
                   $subscription->expires = $subscribeto;
                   $subscription->paid = $price;
                   $subscription->amount = $price;;
                   $subscription->shopid=$shopid;
                   $subscription->productid=$productid;
                   $subscription->insertsubscription();

}


function deletecontact() {
                    $crypt=new crypt();


                   $pid = trim($crypt->decrypt(rawurldecode($_REQUEST['pid'])));
                   $reccount=0;

                   if (CHARTER) {
                       include_once INCLUDES_FOLDER."db_bookings.php";

//                    $res = $booking::getallbookings($status,$type,$offset,$count,$order,$contactid, $startdatetime, $enddatetime, $rentalobjid )

                      $res = booking::getallbookings('','',-1,99,'',$pid, '', '', -1 ,-1 );
                      $reccount = mysql_num_rows($res);

                    }
                    if ($reccount==0) {
                         contact::deletecontactbyid($pid);
                         contactdetail::deletebycontactdetailid($pid) ;

         
                    }
}

function deletecontactmedia() {
 global $shopid;
 global $session_contact;
 global $session_contactdetail;
 global $session;

        $pid= $session_contact->userid;

        if (isset($_REQUEST['pid']) ) {
           $pid='';
        }

        $filen = $_REQUEST['filen'];
        if (file_exists('profiles/'.$pid.'/'.$filen )) {
         unlink ('profiles/'.$pid.'/'.$filen);
         $session_contactdetail->deletemedia($pid,$iname,$filen);
        }

}

function dobackup () {
        $backup= new backup();
        $backup->backup_tables("*");
}


?>
