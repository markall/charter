<?php
    include_once INCLUDES_FOLDER."db_rentalobjs.php";
    include_once INCLUDES_FOLDER."db_bookings.php";
    include_once INCLUDES_FOLDER."db_category.php";

    function docharter($action,$s) {
              global $bookingid;

             // ACTION  Rental Objs
              if ($action=="updaterentalobjs") {
                   updaterentalobjs();
              }

             // Action location
                if ($action=="updatelocation") {
                   updatelocation();
                }

             // Action rate
                if ($action=="updaterate") {
                   updaterate();
                }

                if ($action=="updateratetable") {
                   updateratetable();
                }

             // Action booking
                if ($action=="updatebooking") {
                   $bookingid=updatebooking();
                }
             // Action delete booking
                if ($action=="deletebooking") {
                   $bookingid=deletebooking();
                }

             // Action booking
                if ($action=="updatefunc") {
                   $funcid=updatefunc();
                }


    //**********************************************
    // AJAX SCRIPT RETURNS
    //**********************************************
                if ($action=="fetchrentalrate") {
                   if (isset($_REQUEST['id'])) {
                        $id = $_REQUEST['id'];

                        $ratetype='menu';
                        if ($_REQUEST['ratetype']) {
                           $ratetype=$_REQUEST['ratetype'];
                        }
                        $s=fetchrentalrates($id,$ratetype);
                        echo $s;exit;
                   }

                }

                if ($action=="fetchrentalmaxpersons") {
                   if (isset($_REQUEST['id'])) {
                        $id = $_REQUEST['id'];
                        $s=fetchrental($id,'maxpersons');
                        echo $s;exit;
                   }

                }

                if ($action=="fetchrentallocation") {
                   if (isset($_REQUEST['id'])) {
                        $id = $_REQUEST['id'];

                        $locationtype='pickup';
                        if ($_REQUEST['locationtype']) {
                           $locationtype=$_REQUEST['locationtype'];
                        }
                        $s=fetchlocations($id,$locationtype);
                        echo $s;exit;
                   }

                }

                if ($action=="checkavailability") {
                        $s= checkavailability();
                        echo $s;    exit;
                }


    }


    function generatecharter($s) {

        $rentalobjid=-1;
        $locationid=-1;
        $rateid=-1;
        global $bookingid;

        if (isset($_REQUEST['bookingid']) ) {
                $bookingid=$_REQUEST['bookingid'];

        }
                $booking = new booking();

                if ($bookingid>-1) {
                       $booking->getbooking($bookingid);
                }

                $rentalobjid= $booking->rentalobjid;


        if (strpos($s,"<%booking")>0) {
            $s=showbooking($s,$bookingid);
        }

        if (strpos($s,"<%func")>0) {
                     $s=showfunc($s);
        }





    //****
    // LOCATIONS

             if (isset($_REQUEST['locationid']) ) {
                    $locationid=$_REQUEST['locationid'];
             }

             $s=showlocations($s,$locationid);

    //***
    // RATES AS TABLE
    //
                if (strpos($s,"<%ratesastable")>0) {
                        $t= rentalratesastable();
                        $s=  str_replace("<%ratesastable%>", $t ,$s);

                }

    //****
    // RATE

             if (isset($_REQUEST['rateid']) ) {
                  if ( $_REQUEST['rateid']>-1) {
                    $rateid=$_REQUEST['rateid'];
                  }
             }


             $s=showrate($s,$rateid);



    //****
    // RENTAL OBJ

               if (isset($_REQUEST['rentalobjid']) ) {
                    $rentalobjid=$_REQUEST['rentalobjid'];
               }

             $s=showrentalobj( $s,$rentalobjid );

    //****
    // CALENDAR


             $s=showcalendar($s);

             if (isset($_REQUEST['datefrom'])) {
                $s=str_replace("<%datefrom%>",$_REQUEST["datefrom"],$s);
             }

             if (isset($_REQUEST['dateto'])) {
                $s=str_replace("<%dateto%>",$_REQUEST["dateto"],$s);
             }


             if (isset($_REQUEST['timerentfrom'])) {

                $s=str_replace("<%timerentfrom%>",$_REQUEST["timerentfrom"],$s);
             }

             if (isset($_REQUEST['timerentto'])) {
                $s=str_replace("<%timerentto%>",$_REQUEST["timerentto"],$s);
             }

             if (isset($_REQUEST['timeduration'])) {
                $dater = new dater();
                $timerentfrom = $_REQUEST["timerentfrom"];
                $timerentto =  $_REQUEST["timerentto"];
                $dur= ($dater->mkdate($timerentto)-$dater->mkdate($timerentfrom))/60/60;
//                $s=str_replace("<%timeduration%>",$_REQUEST["timeduration"],$s);
                $s=str_replace("<%timeduration%>",$dur,$s);
             }



             if (isset($_REQUEST['rateid'])) {
                $s=str_replace("<%rateid%>",$_REQUEST["rateid"],$s);
             }
             if (isset($_REQUEST['maincost'])) {
                $s=str_replace("<%maincost%>",$_REQUEST["maincost"],$s);
             }



             if (strpos($s,"<%rentalobjasoptionsall")>0) {
                 $sdx=strpos($s,"<%rentalobjasoptionsall|id=");
                 $rentalobjid=0;
                 $et="%>";
                 if ($sdx>0) {
                        $t=substr($s ,$sdx+strlen('<%rentalobjasoptionsall|id='),strlen($s) );
                        $edx=strpos($t,'%>');
                        if ($edx>0) {
                           $rentalobjid=substr($t,0,$edx);
                           $et="|id=".substr($t,0,$edx+2);
                        }
                 }
                 $s = str_replace("<%rentalobjasoptionsall".$et, rentalobjlist('',$rentalobjid,false ) , $s);       //all rentals

             }
			 
             if (strpos($s,"<%rentalobjasoptionsbyuser")>0) {
                 $sdx=strpos($s,"<%rentalobjasoptionsbyuser|id=");
                 $rentalobjid=0;
                 $et="%>";
                 if ($sdx>0) {
                        $t=substr($s ,$sdx+strlen('<%rentalobjasoptionsbyuser|id='),strlen($s) );
                        $edx=strpos($t,'%>');
                        if ($edx>0) {
                           $rentalobjid=substr($t,0,$edx);
                           $et="|id=".substr($t,0,$edx+2);
                        }
                 }
                 $s = str_replace("<%rentalobjasoptionsbyuser".$et, rentalobjlist('',$rentalobjid , true ) , $s);       //allowed rentals

             }			 

             if (strpos($s,'<%rentalobjasoptionsactive')>0) {
                 $sdx=strpos($s,'<%rentalobjasoptionsactive|id=');

                 $rentalobjid=0;
                 $et="%>";
                 if ($sdx>0) {
                        $t=substr($s ,$sdx+strlen('<%rentalobjasoptionsactive|id='),strlen($s) );
                        $edx=strpos($t,'%>');
                        if ($edx>0) {
                           $rentalobjid=substr($t,0,$edx);
                           $et="|id=".substr($t,0,$edx+2);
                        }
                 }

                 $s = str_replace("<%rentalobjasoptionsactive".$et, rentalobjlist('active',$rentalobjid, false ) , $s);       //all rentals
             }


             return $s;
    }

    function showrentalobj($s,$id) {


               $rentalobj = new rentalobj();

                if ($id>-1) {
                    $rentalobj->getrentalobj($id);
                }

               $s=str_replace(  "<%rentalobjshopid%>",$rentalobj->shopid,$s);
               $s=str_replace(  "<%rentalobjid%>",$rentalobj->id,$s);
               $s=str_replace(  "<%rentalobjtitle%>",$rentalobj->title,$s);
               $s=str_replace(  "<%rentalobjshortdesc %>",$rentalobj->shortdesc,$s);
               $s=str_replace(  "<%rentalobjmaxpersons%>",$rentalobj->maxpersons,$s);
               $s=str_replace(  "<%rentalobjtype%>",$rentalobj->type,$s);
               $t="";
               $t=$t."<option value='boat'";
               if ($rentalobj->type=="boat") {
                 $t=$t." selected ";
               }
               $t=$t.">Boat</option>";
               $t=$t."<!--option value='room'";
               if ($rentalobj->type=="room") {
                 $t=$t." selected ";
               }

               $t=$t.">Room</option -->";

               $owner = new contact();
               $ownerdetail = new contactdetail();
               $owner->getcontactbyid($rentalobj->owner);
               $ownerdetail->getcontactdetailbyid($rentalobj->owner);
               $s=setcontact($s,$owner,$ownerdetail,"Owner_" );

               $s=str_replace(  "<%rentalobjtypes%>",$t,$s);
               $s=str_replace(  "<%rentalobjstatus%>",$rentalobj->status,$s);
               $s=str_replace(  "<%rentalobjdescription%>",$rentalobj->description,$s);
               $s=str_replace(  "<%rentalobjshortdesc%>",$rentalobj->shortdesc,$s);
               $s=str_replace(  "<%rentalobjlicensed%>",$rentalobj->licensed,$s);
               $s=str_replace(  "<%rentalobjownerid%>",$rentalobj->owner,$s);
               $s=str_replace(  "<%rentalobjskipperid%>",$rentalobj->skipper,$s);
               $s=str_replace(  "<%rentalobjcrewid%>",$rentalobj->crew,$s);
               $s=str_replace(  "<%rentalobjcatererid%>",$rentalobj->caterer,$s);
               $s=str_replace(  "<%rentalobjdrinks%>",$rentalobj->drinks,$s);
               $s=str_replace(  "<%rentalobjcolor%>",$rentalobj->color,$s);

               $t="";
               $t=$t."<option value='active'";
               if ($rentalobj->type=="active") {
                 $t=$t." selected ";
               }
               $t=$t.">Active</option>";
               $t=$t."<option value='hold'";
               if ($rentalobj->type=="hold") {
                 $t=$t." selected ";
               }
               $t=$t.">Hold</option>";

               $t=$t."<option value='delete'";
               if ($rentalobj->type=="delete") {
                 $t=$t." selected ";
               }

               $t=$t.">Delete</option>";
               $s=str_replace(  "<%rentalobjstatusasoptions%>",$t,$s);

               if (strpos($s,"<%rentalobjs_locationsasoptions")>0) {
                       $locationlstasoption="";
                       $locationid=-1;
                       $sdx=strpos($s,'<%rentalobjs_locationsasoptions|id=');
                       $et="%>";
                       if ($sdx>0) {
                              $t=substr($s ,$sdx+strlen('<%rentalobjs_locationsasoptions|id='),strlen($s) );
                              $edx=strpos($t,'%>');

                              if ($edx>0) {
                                 $locationid=substr($t,0,$edx);
                                 $et="|id=".substr($t,0,$edx+2);
                              }
                       }

                       if (isset($rentalobj->locations)) {
                         foreach ($rentalobj->locations as $key=>$value) {
                            $locationlstasoption=$locationlstasoption."<option value='$value' ";
                       
                            if ($value==$locationid) {
                                  $locationlstasoption=$locationlstasoption." selected ";
                            }
                            $locationlstasoption=$locationlstasoption.">$key</option>";
                         }


                           $s = str_replace("<%rentalobjs_locationsasoptions".$et,$locationlstasoption,$s);
                      }
             //          $s=str_replace("<%rentalobjs_locationsasoptions%>",$locationlstasoption,$s);
               }

               $locationlstadmin="";
               if (strpos($s,"<%rentalobjs_locationsadmin%>")>0)  {
               if (isset($rentalobj->locations)) {
                   foreach ($rentalobj->locations as $key=>$value) {
                     $locationlstadmin = "<tr id=\"loc_$value\"><td>$key</td><td><a href=\"javascript:removerow('tbllocations','loc_".
                                          $value."');\">remove</a>".
                                          "<input type=\"hidden\" value=\"$value\" id=\"iloc_$value\" name=\"locationidlist[]\" />".
                                          "<input type=\"hidden\" value=\"0\" id=\"dloc_$value\" name=\"locationdeleteflag[$value]\" />".
                                          "</td></tr>".$locationlstadmin;

                   }
               }
               }
               $s = str_replace("<%rentalobjs_locationsadmin%>",$locationlstadmin,$s);
/*                  c2.innerHTML= "<a href=\"javascript:removerow('"+tableid+"','"+newrow.id+"');\">remove</a>"+
//              "<input type=\"hidden\" value=\""+parentobj.options[pdx].value+"\" id=\"nli"+lidx+"\" name=\"locationidlist[]\" />";

                 $locationlstadmin = "<tr id=\"loc_$value->id\"><td>$value->locationid</td><td><a href=\"javascript:removerow('tblmedia','loc_".
                                      $value->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$value->locationid\" id=\"iloc_$value->locationid\" name=\"locationidlist[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dloc_$value->id\" name=\"locationdeleteflag[$value->id]\" />".
                                      "</td></tr>".$locationlstadmin;
                     */

               $ratelstadmin="";
               if (strpos($s,"<%rentalobjs_ratesadmin%>")>0)  {
                 if (isset($rentalobj->rates)) {
                     foreach ($rentalobj->rates as $key=>$value) {
                      if (strlen($value)>0) {
                       $ratelstadmin = "<tr id=\"rate_$key\"><td>$value</td><td><a href=\"javascript:removerow('tblrates','rate_".
                                            $key."');\">remove</a>".
                                            "<input type=\"hidden\" value=\"$key\" id=\"irate_$key\" name=\"rateidlist[]\" />".
                                            "<input type=\"hidden\" value=\"0\" id=\"drate_$key\" name=\"ratedeleteflag[$key]\" />".
                                            "</td></tr>".$ratelstadmin;
                      }
                     }
                 }
                       $s = str_replace("<%rentalobjs_ratesadmin%>",$ratelstadmin,$s);
               }


               if (strpos($s,"<%rentalobjs_ratesasoptions%>")>0) {
                       $ratelstasoption="";

                       $rateid=-1;
                       $sdx=strpos($s,'<%rentalobjs_ratesasoptions|id=');
                       $et="%>";
                       if ($sdx>0) {
                              $t=substr($s ,$sdx+strlen('<%rentalobjs_ratesasoptions|id='),strlen($s) );
                              $edx=strpos($t,'%>');

                              if ($edx>0) {
                                 $rateid=substr($t,0,$edx);
                                 $et="|id=".substr($t,0,$edx+2);
                              }
                       }

                       foreach ($rentalobj->rates as $key=>$value) {
                          $ratelstasoption=$ratelstasoption."<option value='".$key."'";
                          if ($key==$rateid) {
                                $ratelstasoption=$ratelstasoption." selected ";
                          }
                          $ratelstasoption=$ratelstasoption.">$value</option>";
                       }
                       $s=str_replace("<%rentalobjs_ratelstasoption".$et,$ratelstasoption,$s);

               }

               if (strpos($s,'<%rentalobjs_ratebytype=')>0) {
                 $i = strpos($s,'<%rentalobjs_ratebytype=');
                 $f=$s;
                 $t="";
                 while ($i>0) {
                         $e=substr($s, $i+strlen('<%rentalobjs_ratebytype='), strlen($s) );

                         $c = substr($e,0,strpos($e,'%>'));
                         $sel='';
                         $ii=strpos($c,'|');
                         if ($ii>-1) {
                            $sel = substr($c,$ii+1,strlen($c)-2);
                            $c=substr($c,0,$ii );
                            $r = "<%rentalobjs_ratebytype=".$c."|".$sel."%>";
                         } else {
                            $r = "<%rentalobjs_ratebytype=".$c."%>";
                         }

                         $rate= new rate();
                         if (isset($rentalobj->rates)) {
                           foreach ($rentalobj->rates as $key=>$value) {
                              $rate->getratebyid($key);
                              if ($rate->ratetype==$c) {
  //                            "15|20.00|100|8 Canapes"
                                      $t=$t."<option value='".$key."|".$rate->baseprice."|".$rate->baseunit."|".$rate->priceperunit."|".$value."'>$value</option>";
                              }
                           }
                         }
                         $s=str_replace($r,$t,$s);

                         $i = strpos($s,'<%rentalobjs_ratebytype=');
                 }
               }

         return $s;

    }
    function rentalobjlist( $status,$id,$byuser ) {
           global $session_contact;

		   $rentalrecs = rentalobj::getallrentalobjs($status,'boat','-1','',' order by title' );
		   
           $t="";
           while ($row=mysql_fetch_assoc( $rentalrecs )) {
		   
				$found = true;
					if (!empty($session_contact->rentals)) {
						if ($byuser) {
							$found = false;

							foreach ($session_contact->rentals as $rentalid) {
			
							if ($row['id']==$rentalid) {
									$found = true;
								}
							}
						}
						if (count($session_contact->rentals)<1) {
							$found = true;
						} else {
							if ($session_contact->rentals[0]<1) {
								$found = true;
							}
						}
					}
				
				if ($found) {
					$t=$t."<option value='".$row['id']."' ";
					if ( trim($row['id'])==trim($id)) {
                        $t=$t." selected ";
					}
					$t=$t.">".$row['title']."</option>";
				}
           }

        return $t;

    }


    function updaterentalobjs() {
        global $shopid;
        $rentalobj= new rentalobj();
        $rentalobjid=-1;
        $prefix='';

        $crypt = new crypt();

        if (isset($_REQUEST['prefix'])) {
             $prefix = $_REQUEST['prefix'];
        }


        if (isset($_REQUEST['rentalobjid'])) {
                 $rentalobjid=$_REQUEST['rentalobjid'];
                 if ($rentalobjid>-1) {
                    $rentalobj->getrentalobj($rentalobjid);

                    $rentalobj->shopid = $shopid;
                    $rentalobj->title  =  $_REQUEST['rentalobjtitle'];
                    $rentalobj->type =  $_REQUEST['rentalobjtype'];
                    $rentalobj->shortdesc =  $_REQUEST['rentalobjshortdesc'];
                    $rentalobj->maxpersons =  $_REQUEST['rentalobjmaxpersons'];
                    $rentalobj->status = $_REQUEST['rentalobjstatus'];
                    $rentalobj->color = $_REQUEST['rentalobjcolor'];

                    $rentalobj->description = $_REQUEST['rentalobjdescription'];
                    $rentalobj->owner = trim($crypt->decrypt(strip2(rawurldecode($_REQUEST['rentalobjownerid']))));
                    $rentalobj->skipper = trim($crypt->decrypt(strip2(rawurldecode($_REQUEST['rentalobjskipperid']))));
                    $rentalobj->crew = trim($crypt->decrypt(strip2(rawurldecode($_REQUEST['rentalobjcrewid']))));
                    $rentalobj->caterer = trim($crypt->decrypt(strip2(rawurldecode($_REQUEST['rentalobjcatererid']))));

                    if ($rentalobjid>0) {
                         $rentalobj->updaterentalobjbyid($rentalobjid);
                    } else {
                         $rentalobj->id=$rentalobj->insertrentalobj();
                    }

                    if (isset($_REQUEST['locationidlist'])) {
                        // location
                        // first get the old location and delete it

                        if (isset($rentalobj->locations)) {
                                  foreach ($rentalobj->locations as $key=>$value) {
                                    $rentalobj->deletefromlocation($value);
                                  }
                        }

                        // the add the new location
                        unset($rentalobj->locations);

                        if (isset($_REQUEST['locationidlist'])) {
                            if (count($_REQUEST['locationidlist'])>0) {
                                      foreach ($_REQUEST['locationidlist'] as $key=>$value) {
                                               $rentalobj->addtolocation($value);
                                               $rentalobj->locations[]=$value;
                                      }
                             }
                        }

                        if (isset( $_REQUEST['locationdeleteflag'] )) {
                            foreach ($_REQUEST['locationdeleteflag'] as $key=>$value) {
                                     if ($value==1) {
                                         $rentalobj->deletefromlocation($key);
                                     }
                            }
                        }
                    }


                    if (isset($_REQUEST['rateidlist'])) {
                        // location
                        // first get the old rate and delete it


                        if (isset($rentalobj->rates)) {
                                  $rentalobj->deletefromrate(-1);
//                                  foreach ($rentalobj->rates as $key=>$value) {
//                                  }
                        }

                        // the add the new rates
                        unset($rentalobj->rates);

                        if (isset($_REQUEST['rateidlist'])) {
                            if (count($_REQUEST['rateidlist'])>0) {
                                      foreach ($_REQUEST['rateidlist'] as $key=>$value) {
                                         if (!$rentalobj->checkexists($value) ) {
                                            $rentalobj->addtorate($value);
                                            $rentalobj->rates[$key]=$value;
                                         }
                                      }
                             }
                        }

                        if (isset( $_REQUEST['ratedeleteflag'] )) {
                            foreach ($_REQUEST['ratedeleteflag'] as $key=>$value) {
                                     if ($value==1) {
                                         $rentalobj->deletefromrate($key);
                                     }
                            }
                        }
                    }
                    if ($rentalobj->status=='delete') {

                        $rentalobj->deleterentalobj();
                    }
                 }
        }

    }

    function setlocations($id) {
        $locrecs = location::getalllocations();
        $t="";
        while ($row=mysql_fetch_assoc( $locrecs )) {
          $t=$t."<option value='".$row['id']."'";
          if ($row['id']==$id) {
                $t=$t." selected ";
          }
          $t=$t.">".$row['location']."</option>";
        }
        return $t;
    }

    function showlocations($s,$id) {
               $location = new location();

               if ($id>-1) {
                   $location->getlocationbyid($id);
               }
               if (strpos($s,"<%locationid%>")>0) {
                  $s = str_replace("<%locationid%>",$location->id,$s);
               }
               if (strpos($s,"<%locationpickup%>")>0) {
                  if ($location->pickup) {
                      $s = str_replace("<%locationpickup%>","checked",$s);
                  } else {
                     $s = str_replace("<%locationpickup%>"," ",$s);
                  }
               }

               if (strpos($s,"<%locationdropoff%>")>0) {
                  if ($location->dropoff) {
                          $s = str_replace("<%locationdropoff%>","checked",$s);
                  } else {
                          $s = str_replace("<%locationdropoff%>"," ",$s);
                  }
               }

               if (strpos($s,"<%locationstatusaoptions%>")>0 ) {
                   $options = array();
                   $options["active"]="Active";
                   $options["hold"]="Hold";
                   $options["delete"]="Delete";
                   $o="";
                   foreach ($options as $k=>$v) {
                           $o=$o."<option value='".$k."' ";
                           if ($location->status==$k) {
                             $o=$o." selected ";
                           }
                           $o=$o.">".$v."</option>";
                   }
                   $s=str_replace("<%locationstatusaoptions%>",$o,$s);

               }

               if (strpos($s,"<%location%>")>0) {
                  $s = str_replace("<%location%>",$location->location,$s);
               }

               $locationid=-1;
               $options = tagoptions('<%locationsasoptions',$s);
               $optionsa = explode('&',$options);
               foreach ($optionsa as $key=>$value) {
                        $oa = explode("=",$value);
                        if ($oa[0]=='id') {
                          $locationid=$oa[1];
                        }
                }
                if ($options>"") {
                    $options="|".$options;
                }

                $s=str_replace("<%locationsasoptions".$options."%>",setlocations($locationid),$s);


        return $s;
    }



    function updatelocation() {
        global $shopid;

            $id= $_REQUEST['locationid'];

            $location = new location();

            $location->location = $_REQUEST['location'];
            $location->pickup = false;
            if ($_REQUEST['pickup']) { $location->pickup=true; }
//            $location->pickup = $_REQUEST['pickup'];
//            $location->dropoff = $_REQUEST['dropoff'];
            $location->dropoff = false;
            if ($_REQUEST['dropoff']) { $location->dropoff=true; }

            $location->status = $_REQUEST['status'];
               
            if ($id<0) {
                $location->shopid = $shopid;
                $location->insertlocation();
            } else {
                $location->updatelocationbyid($id);
            }

    }

    function updatebooking() {
        global $shopid;

        $crypt=new crypt();

        $booking = new booking();

        $booking->shopid=$shopid;
        if (isset($_REQUEST['bookingid'])) {
                $booking->id=  $_REQUEST['bookingid'];
        } else {
                $booking->id = -1;
        }

        if (isset($_REQUEST['rentalobjid']) ) {
                $booking->rentalobjid= $_REQUEST['rentalobjid'];
               
        }

        if (isset($_REQUEST['rateid']) ) {
                $booking->rateid= $_REQUEST['rateid'];
        }

        if (isset($_REQUEST['customerid'])) {
                $booking->customerid=$_REQUEST['customerid'];
        }


        if (isset($_REQUEST['bookingrentfrom']) ) {
          $booking->startdatetime=$_REQUEST['bookingrentfrom'];
        }

        if (isset($_REQUEST['bookingrentto']) ) {
           $booking->enddatetime=$_REQUEST['bookingrentto'];
        }



        if (isset($_REQUEST['pickuplocation']) ) {
          $booking->pickuplocationid=$_REQUEST['pickuplocation'];
        }
                if (isset($_REQUEST['pickuplocationtext']) ) {
                        $booking->pickuplocationtext=$_REQUEST['pickuplocationtext'];
                }
                
                if (isset($_REQUEST['dropofflocation']) ) {
          $booking->dropofflocationid=$_REQUEST['dropofflocation'];
        }
                if (isset($_REQUEST['dropofflocationtext']) ) {
                        $booking->dropofflocationtext=$_REQUEST['dropofflocationtext'];
                }

        if (isset($_REQUEST['noofpeople']) ) {
         $booking->numberofpeople=$_REQUEST['noofpeople'];
        }
        if (isset($_REQUEST['status']) ) {
          $booking->status=$_REQUEST['status'];
        }
        if (isset($_REQUEST['rentaltype']) ) {
          $booking->rentaltype=$_REQUEST['rentaltype'];
        }
        if (isset($_REQUEST['transport']) ) {
          $booking->transport=false;
          if  ($_REQUEST['transport']=="on") {
               $booking->transport=true;
          }
        }
        if (isset($_REQUEST['menuid']) ) {
           $booking->menuid=$_REQUEST['menuid'];
        }
        if (isset($_REQUEST['cateringnotes']) ) {
           $booking->cateringnotes=$_REQUEST['cateringnotes'];
        }
        if (isset($_REQUEST['drinknotes']) ) {
          $booking->drinknotes=$_REQUEST['drinknotes'];
        }
        if (isset($_REQUEST['othernotes']) ) {
          $booking->othernotes=$_REQUEST['othernotes'];
        }
		
        if (isset($_REQUEST['adminnotes']) ) {
          $booking->adminnotes=$_REQUEST['adminnotes'];
        }
        
		if (isset($_REQUEST['takenbyid']) ) {
          $booking->takenbyid = trim($crypt->decrypt(rawurldecode( $_REQUEST['takenbyid'] )));
        }

        if (isset($_REQUEST['baseamt']) ) {
          $booking->basecost=$_REQUEST['baseamt'];
        }
        if (isset($_REQUEST['menuamt']) ) {
          $booking->menucost=$_REQUEST['menuamt'];
        }
        if (isset($_REQUEST['additionalamt']) ) {
          $booking->additionalcost=$_REQUEST['additionalamt'];
        }
		
        if (isset($_REQUEST['deposit'])) {
                 $booking->deposit = $_REQUEST['deposit'];
        }		
        if (isset($_REQUEST['depositdate'])) {
                 $booking->depositdate = $_REQUEST['depositdate'];
        }
        if (isset($_REQUEST['depositpaid'])) {
                 $booking->depositpaid = $_REQUEST['depositpaid'];
        }
        if (isset($_REQUEST['balancepaid'])) {
                 $booking->balancepaid = $_REQUEST['balancepaid'];
        }

        if (isset($_REQUEST['invoicenumber'])) {
                 $booking->invoicenumber = $_REQUEST['invoicenumber'];
        }
		
        if (isset($_REQUEST['paid'])) {
                 $booking->paid = $_REQUEST[paid];
        }
        if (isset($_REQUEST['paiddate'])) {
                 $booking->paiddate = $_REQUEST['paiddate'];
        }

        if (isset($_REQUEST['emailskipper'])) {
                 $booking->emailskipper = $_REQUEST['emailskipper'];
        }

        if (isset($_REQUEST['emailmate'])) {
                 $booking->emailmate = $_REQUEST['emailmate'];
        }

        if (isset($_REQUEST['emailcaterer'])) {
                 $booking->emailcaterer = $_REQUEST['emailcaterer'];
        }
        if (isset($_REQUEST['emailclient'])) {
                 $booking->emailclient = $_REQUEST['emailclient'];
        }
        if (isset($_REQUEST['emailowner'])) {
                 $booking->emailowner = $_REQUEST['emailowner'];
        }

        if (isset($_REQUEST['skipperid'])) {
            $skipperobj = new bookingcontactlink();
            $skipperobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['skipperid'])));
            $category = new category();
            $category->getcategory($shopid,'Skipper');
            $skipperobj->categoryid = $category->id;


            $booking->contacts[]= $skipperobj;

        }

        if (isset($_REQUEST['mateid'])) {
            $crewobj = new bookingcontactlink();
            $crewobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['mateid'])));
            $category = new category();
            $category->getcategory($shopid,'Mate');
            $crewobj->categoryid = $category->id;

            $booking->contacts[]= $crewobj;
        }

        if (isset($_REQUEST['catererid'])) {
            $catererobj = new bookingcontactlink();
            $catererobj->contactid =  trim($crypt->decrypt(rawurldecode($_REQUEST['catererid'])));
            $category = new category();
            $category->getcategory($shopid,'Caterer');
            $catererobj->categoryid = $category->id;

            $booking->contacts[]= $catererobj;
        }



        if (!empty($_REQUEST['booking_categoryidlist'])) {
	
                foreach ($_REQUEST['booking_categoryidlist'] as $k=>$v) {
                //        echo "k=".$k." v=".$v." c=".trim($crypt->decrypt(rawurldecode($_REQUEST['booking_contactidlist'][$k])))."<br/>";
                   $obj = new bookingcontactlink();
                   $obj->bookingid=$booking->id;


                   if (!empty($_REQUEST['booking_contactlinkid'][$k])) {
                           $obj->id=$_REQUEST['booking_contactlinkid'][$k];
                   } else {
                           $obj->id=-1;
                   }

                  $str_contactid = $_REQUEST['booking_contactidlist'][$k];

                  if (ENCRYPT) {
                    $str_contactid = $_REQUEST['booking_contactidlist'][$k];
                  }

                  // Hack because I have mixed encrypted user ids and non encrypted

                  if (strlen($str_contactid)>10) {
                          $str_contactid =  $crypt->decrypt(rawurldecode($_REQUEST['booking_contactidlist'][$k]));
                  }


                   $obj->contactid =  $str_contactid;
                   $obj->categoryid = $v;
                   $obj->status="active";
                   if (isset($_REQUEST['contactlinkdeleteflag'])) {
                      if ($_REQUEST['contactlinkdeleteflag'][$k]==1) {
                         $obj->status='deleted';
                      }
                   }
				  
                   $booking->contacts[]= $obj;
                }


        }

                   $foundskipper =0;
                   $foundmate = 0;
                   $foundcaterer = 0;

        foreach ($booking->contacts as $key=>$obj) {


               $cat = new category();
               $cat->getcategorybyid($shopid,$obj->categoryid);

               if ($cat->code=='Skipper') {

                   if ($foundskipper) {
                        $obj->status='deleted';
                   }
                   $foundskipper = 1;  // only one skipper allowed
               }
			  
               if ($cat->code=='Mate') {
                   if ($foundmate) {
                        $obj->status='deleted';
                   }

                   $foundmate =1;
               }
               if ($cat->code=='Caterer') {
                   if ($foundcode) {
                        $obj->status='deleted';
                   }

                   $foundcaterer =1;
               }


        }




        if ($booking->id<0) {
          $booking->id=$booking->insertbooking();
          $message ="Hi <br/><br/>\n\nThis is an automated email to let you know that a charter booking has been placed   \n\n<br/><br/>";

        } else {
          $booking->updatebooking();
         $message ="Hi <br/><br/>\n\nThis is an automated email to let you know that a charter booking has been updated   <br/><br/>\n\n";

        }

        // Final emails
        $rentalobj = new rentalobj();
        $rentalobj->getrentalobj($booking->rentalobjid);


        $subject="From Henley Sales And Charter Booking Confirmation id ".$booking->id;

       global $siteadminemail;
       global $company;

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
$headers .= "From: ".$company."<".$siteadminemail.">" . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";


//        $message = $message."Booking id: ".$booking->id." <a href='".SITE_URI."/content.php?template=templates/bookingedit.html&bookingid=".$bookingid."' >click here to view </a> \n\n";

        $message = $message."Booking id: ".$booking->id." <a href=\"".SITE_URL."/content.php?template=templates/booking4.html&bookingid=".$booking->id."\" >click here to view </a> <br/><br/>\n\n";

        $message = $message."For ".$rentalobj->title." Charter Starts: ".$booking->startdatetime." Finishes: ".$booking->enddatetime."\n<br/><br/>\n";
        $message = $message." For any queries please contact us on 01491 578870 \n<br/><br/>\n"  ;
        $message = $message." Kind regards\n\n Gillian \n<br/><br/>\n"  ;

		
		if (file_exists("templates/booking-email.html") )
		{
			$st = file_get_contents("templates/booking-email.html");
		
			             
              if (!isset($contact) ) {
                $icontact = new contact();
                $icontactdetail= new contactdetail();
              }
              if (isset($_REQUEST['prefix']) ) {
                    $prefix = $_REQUEST['prefix'];
                   } else {
                    $prefix="p_";
              }
           

              $st = generatepage($st,$icontact,$icontactdetail,$prefix);
			  
			  $message =  tidytags($st);

			
		}	
		


           $client = new contact();
           $client->getcontactbyid($booking->customerid);
           if (isset($_REQUEST["email_Client"]) ) {
               if ($_REQUEST["email_Client"]=="yes" ) {
//                      mail($client->email,$subject,$message,$headers);
                  //    echo "emailing to ".$client->email;
               }
           }



        $owner = new contact();
        $owner->getcontactbyid($rentalobj->ownerid);
        if (isset($_REQUEST["email_Owner"]) ) {
            if ($_REQUEST["email_Owner"]=="yes" ) {
                  mail($owner->email,$subject,$message,$headers);
//                echo "emailing to ".$owner->email;
            }
         }

        // Now the rest

        foreach ($booking->contacts as $key=>$obj) {
             if ($obj->status=="active") {
					
                   $contact = new contact();
                   $contact->getcontactbyid($obj->contactid);
				   
                   $category = new category();
                   $categorycode =  strtolower($category->getcategorycodebyid($shopid,$obj->categoryid));
				  
				 
                   if (!empty($_REQUEST["email".$categorycode]) ) {
				   
                        if ($_REQUEST["email".$categorycode]==1 ) {
                               
							   mail($contact->email,$subject,$message,$headers);
							   
                        }
                   }
             }
        }

         mail( "mark@merchantmakers.co.uk", $subject, $message ,$headers);





        return $booking->id;
    }

    function deletebooking() {
        global $shopid;

        $crypt=new crypt();

        $booking = new booking();
        $func = new func();

        $booking->shopid=$shopid;

        if (isset($_REQUEST['bookingid'])) {

                $booking->id=  $_REQUEST['bookingid'];
                $func->bookingid = $_REQUEST['bookingid'];
                $func->getfunc($booking->id);

                $booking->deletebookingbyid();
                $func->deletefuncbyid();

        }

        return $booking->id;


    }

    function showbooking($s,$id) {
             global $rentalobjid;
             global $shopid;


             $crypt=new crypt();

               $booking = new booking();

               if ($id>-1) {
                   $booking->getbooking($id);
               }
               $rentalobjid= $booking->rentalobjid;


               $s=str_replace("<%bookingid%>",$booking->id,$s);
               $s=str_replace("<%bookingrentalobjid%>",$booking->rentalobjid,$s);

               $s=str_replace("<%bookingrateid%>",$booking->rateid,$s);
               $s=str_replace("<%bookingcustomerid%>",$booking->customerid,$s);

               if (isset($_REQUEST['timerentfrom'])) {
                        $bookingrentfrom = $_REQUEST['timerentfrom'];
                        $bookingrentto = $_REQUEST['timerentto'];
                     //   echo $bookingrentfrom;
               } else {
                        $bookingrentfrom = $booking->startdatetime;
                        $bookingrentto = $booking->enddatetime;
               }

               $s=str_replace("<%bookingstartdatetime%>",$bookingrentfrom,$s);
               $s=str_replace("<%bookingenddatetime%>",$bookingrentto,$s);

               $s=str_replace("<%bookingstartdate%>",substr($bookingrentfrom,0,10),$s);
               $s=str_replace("<%bookingenddate%>",substr($bookingrentto,0,10),$s);
			   
               $s=str_replace("<%bookingstarttime%>",substr($bookingrentfrom,11,5),$s);
               $s=str_replace("<%bookingendtime%>",substr($bookingrentto,11,5),$s);
			   
               $dater= new dater();

               $dur= ($dater->mkdate($booking->enddatetime)-$dater->mkdate($booking->startdatetime))/60/60;


         //      echo $booking->enddatetime." = ".$booking->startdatetime." = ".$dur;

               $s=str_replace("<%bookingduration%>",$dur,$s);

               $s=str_replace("<%bookingpickuplocationid%>",$booking->pickuplocationid,$s);
               $s=str_replace("<%bookingdropofflocationid%>",$booking->dropofflocationid,$s);
                        
               $location= new location();
               $location->getlocationbyid($booking->pickuplocationid);
                                if ( ($location->location>' ') && (trim($booking->pickuplocationtext)=="") ) {
                                        $booking->pickuplocationtext = $location->location;
                           }
                        
                                $s=str_replace("<%bookingpickuplocationtext%>",$booking->pickuplocationtext,$s);

               $location->getlocationbyid($booking->dropofflocationid);
                                if ( ($location->location>' ') && (trim($booking->dropofflocationtext)=="") ) {
                                        $booking->dropofflocationtext = $location->location;
                                }
                                        
                                $s=str_replace("<%bookingdropofflocationtext%>",$booking->dropofflocationtext,$s);

               $s=str_replace("<%bookingstatus%>",$booking->status,$s);
               $s=str_replace("<%bookingrentaltype%>",$booking->rentaltype,$s);
               $s=str_replace("<%bookingnoofpeople%>",$booking->numberofpeople,$s);
               $s=str_replace("<%bookingtransport%>",$booking->transport,$s);
               $flag="";
               if ($booking->transport) {
                $flag=" checked ";
               }
			   $basecost = trim($booking->basecost);
			   if (empty($basecost)) {
					$booking->basecost=0;
			   }
               $s=str_replace("<%bookingtransportflag%>",$flag,$s);
               $s=str_replace("<%bookingmenuid%>",$booking->menuid,$s);
               $s=str_replace("<%bookingcateringnotes%>",$booking->cateringnotes,$s);
               $s=str_replace("<%bookingdrinknotes%>",$booking->drinknotes,$s);
               $s=str_replace("<%bookingothernotes%>",$booking->othernotes,$s);
               $s=str_replace("<%bookingadminnotes%>",$booking->adminnotes,$s);			   
               $s=str_replace("<%bookingtakenby%>",$booking->takenbyid,$s);
               $s=str_replace("<%bookingbasecost%>",$booking->basecost,$s);
               $s=str_replace("<%bookingmenucost%>",$booking->menucost,$s);
               $s=str_replace("<%bookingadditionalcost%>",$booking->additionalcost,$s);
			   
               if ( $booking->depositpaid ) {
                       $s=str_replace("<%bookingdepositpaid%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingdepositpaid%>","",$s);
               }
			   
               if ( $booking->balancepaid ) {
                       $s=str_replace("<%bookingbalancepaid%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingbalancepaid%>","",$s);
               }

               $s=str_replace("<%invoicenumber%>",$booking->invoicenumber,$s);
			   
               $totalcost = ($booking->basecost + $booking->menucost + $booking->additionalcost) ;

               $s=str_replace("<%bookingtotalcost%>", sprintf("%01.2f", $totalcost),$s);


               $s=str_replace("<%bookingdeposit%>",$booking->deposit,$s);
               $s=str_replace("<%bookingdepositdate%>",$booking->depositdate,$s);
               $s=str_replace("<%bookingpaid%>",$booking->paid,$s);
               $s=str_replace("<%bookingpaiddate%>",$booking->paiddate,$s);

               if ( $booking->emailskipper ) {
                       $s=str_replace("<%bookingemailskipper%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingemailskipper%>","",$s);
               }
               if ( $booking->emailmate    ) {
                       $s=str_replace("<%bookingemailmate%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingemailmate%>","",$s);
               }
               if ( $booking->emailcaterer   ) {
                       $s=str_replace("<%bookingemailcaterer%>","checked=checked" ,$s);
               } else {
                       $s=str_replace("<%bookingemailcaterer%>","" ,$s) ;
               }
               if ( $booking->emailclient ) {
                       $s=str_replace("<%bookingemailclient%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingemailclient%>","",$s);
               }

               if ( $booking->emailowner ) {
                       $s=str_replace("<%bookingemailowner%>","checked=checked",$s);
               } else {
                       $s=str_replace("<%bookingemailowner%>","",$s) ;
               }


               $client = new contact();
               $clientdetail = new contactdetail();
               $client->getcontactbyid($booking->customerid);
               $clientdetail->getcontactdetailbyid($booking->customerid);
               $s=setcontact($s,$client,$clientdetail,"client_" );


               $client->getcontactbyid($booking->takenbyid);
               $clientdetail->getcontactdetailbyid($booking->takenbyid);
               $s=setcontact($s,$client,$clientdetail,"Admin_" );

               $category=new category();
               $bookingcontact= new contact();
               $bookingcontactdetail = new contactdetail();

                if (isset($booking->contacts) ) {
                  $a="";
                  $tbl_t=""; ob_start();

                  $cdx=0;
                  foreach ($booking->contacts as $key=>$value) {
                        $cdx++;
                        $t="<%category_title%>: <%contactcompany%> &middot; <%contactfirstname%> <%contactlastname%> &middot;<%contacttelephone1%> <br/><br/> ";
                        $category->getcategorybyid($shopid,$value->categoryid)  ;
                        $bookingcontact->getcontactbyid($value->contactid);
                        $bookingcontactdetail->getcontactdetailbyid($value->contactid);

                        $s=setcontact($s,$bookingcontact,$bookingcontactdetail,$category->code."_" ); //individual contacts by category

                        $t=setcategory($t,$value->categoryid);
                        $t= setcontact($t,$bookingcontact,$bookingcontactdetail,"" );

                        $a=$a.$t; //all contacts by category

//                        $str_contactid = $value->contactid;
//                        if (ENCRYPT) {
                           $str_contactid=  trim(rawurlencode( $crypt->encrypt($value->contactid )));
//                        }
                        $tbl_t="<tr id=\"bc_$value->id\">\n".
                                "<td>$bookingcontact->firstname $bookingcontact->lastname  </td>\n".
                                "<td>$category->title</td>\n".
                                "<td><a href=\"javascript:removerow('additionalcontacts','bc_".$value->id."');\">remove</a>\n".
                                "<input type=\"hidden\" value=\"".$value->categoryid."\" id=\"icon".$value->categoryid."\" name=\"booking_categoryidlist[]\" />\n".
                                "<input type=\"hidden\" value=\"". $str_contactid."\" id=\"icat".$str_contactid."\" name=\"booking_contactidlist[]\" />\n".
                                "<input type=\"hidden\" value=\"0\" id=\"dbc_$value->id\" name=\""."contactlinkdeleteflag[]\" />\n".
                                "<input type=\"hidden\" value=\"$value->id\" id=\"dbid_$value->id\" name=\""."booking_contactlinkid[]\" />\n".
                                "</td></tr>\n".$tbl_t;
                        if ($cdx>20) {break; }

                  }
                  $s=str_replace("<%booking_contacts%>",$a,$s);
                  $s=str_replace("<%booking_contacts_tbl%>",$tbl_t,$s);
               }

                    include_once INCLUDES_FOLDER."db_invoice.php";
                    include_once INCLUDES_FOLDER."invoicefunctions.php";
                      $s = str_replace("<%bookinginvoices%>" , showbookinginvoices($booking->id ),$s );

               return $s;

    }

    function updaterate() {

          global $shopid;

            $id= $_REQUEST['rateid'];

            $rate = new rate();

            $rate->title =  $_REQUEST['ratetitle'];
            $rate->baseprice=  $_REQUEST['ratebaseprice'];
            $rate->baseunit=  $_REQUEST['ratebaseunit'];
            $rate->priceperunit=  $_REQUEST['ratepriceperunit'];
            $rate->vatcode = $_REQUEST['ratevatcode'];
            $rate->includesvat=0;

            if ($_REQUEST['rateincludesvat']) {
                $rate->includesvat=-1;
            }

            $rate->ratetype=$_REQUEST['ratetype'];


            if ($id<0) {
                $rate->shopid = $shopid;
                $rate->insertrate();
            } else {
                $rate->updateratebyid($id);
            }
    }

    function updateratetable() {

          global $shopid;


            foreach ($_REQUEST['ratetitle'] as $key=>$value) {

            $rate = new rate();
            $rate->getratebyid($key);
            $change=0;

            if ($rate->title!=$_REQUEST['ratetitle']) {
                    $rate->title =  $_REQUEST['ratetitle'][$key];
                    $change=-1;
            }

            if ($rate->baseprice!=$_REQUEST['ratebaseprice']) {
                    $rate->baseprice =  $_REQUEST['ratebaseprice'][$key];
                    $change=-1;
            }
            if ($rate->baseunit!=$_REQUEST['ratebaseunit'][$key]) {
                    $rate->baseunit=  $_REQUEST['ratebaseunit'][$key];
                    $change=-1;
            }
            if ($rate->priceperunit!=$_REQUEST['ratepriceperunit'][$key]) {
                    $rate->priceperunit=  $_REQUEST['ratepriceperunit'][$key];
                    $change=-1;
            }

            if ($rate->vatcode!=$_REQUEST['ratevatcode'][$key]) {
                    $rate->vatcode = $_REQUEST['ratevatcode'][$key];
                    $change=-1;
            }


            if ($rate->includesvat!=$_REQUEST['rateincludesvat'][$key]) {
                $rate->includesvat=$_REQUEST['rateincludesvat'][$key];
                $change=-1;
            }

            if ($rate->ratetype!=$_REQUEST['ratetype'][$key]) {
                $rate->ratetype=$_REQUEST['ratetype'][$key];
                $change=-1;
            }


            if ($change) {
                $rate->updateratebyid($key);
            }

            if ($_REQUEST['delete'][$key]=="delete") {
                $rate->deleteratebyid($key);
//                echo "DElete ".$key." <br/>";
            }


            }



    }

    function showrate($s,$id) {
               global $vatrates;

               $rate = new rate();



               if ($id>-1) {
                   $rate->getratebyid($id);
               }

               if (strpos($s,"<%rateid%>")>0) {
                  $s = str_replace("<%rateid%>",$id,$s);
               }

               $s = str_replace("<%ratetitle%>",$rate->title,$s);
               $s = str_replace("<%ratebaseprice%>",$rate->baseprice,$s);
               $s = str_replace("<%ratebaseunit%>",$rate->baseunit,$s);
               $s = str_replace("<%ratepriceperunit%>",$rate->priceperunit,$s);
               $s = str_replace("<%ratevatcode%>",$rate->vatcode,$s);

               $t="";
               foreach ($vatrates as $key=>$value) {
                 $t=$t."<option value='".$key."' ";
                 if ($key==$rate->vatcode) {
                        $t=$t." selected ";
                 }

                 $t=$t.">"." ".$value."%</option>";
               }
               $s=str_replace("<%vatcodesasoptions%>",$t,$s);

               $s = str_replace("<%rateincludesvat%>",$rate->includesvat,$s);

               $t="";
               if ($rate->includesvat) {
                 $t="checked";
               } else {
                  $t="";
               }

               $s = str_replace("<%rateincludesvatascheck%>",$t,$s);

               $s = str_replace("<%ratetype%>",$rate->ratetype,$s);

               $ratetypeasoptions = "";
               $ratetypearr = array ( "boat","menu" );
               foreach ($ratetypearr as $key=>$value) {
                 $ratetypeasoptions =$ratetypeasoptions."<option value='$value'";
                 if ($rate->ratetype==$value) {
                  $ratetypeasoptions=$ratetypeasoptions." selected ";
                 }
                 $ratetypeasoptions=$ratetypeasoptions.">".$value."</option>";
               }

               $s = str_replace("<%ratetypeasoptions%>",$ratetypeasoptions,$s);

               $s = str_replace("<%ratessasoptions%>",setrates(),$s);

               return $s ;

    }

      function setrates() {
        $raterecs = rate::getallrates();
        $t="";
        while ($row=mysql_fetch_assoc( $raterecs )) {
          $t=$t."<option value='".$row['id']."'>".$row['ratetitle']."</option>";
        }
        return $t;
    }

    function fetchrentalrates($id,$ratetype) {
        $s="";

        $rentalobj = new rentalobj();
        $rate= new rate();

        $rentalobj->getrentalobj($id);
        foreach ($rentalobj->rates as $key=>$value) {
               $rate->getratebyid($key);
               if ($rate->ratetype==$ratetype) {
                   $s=$s."<option value='".$key."|".$rate->baseprice."|".$rate->baseunit."|".$rate->priceperunit."'>$value</option>";
               }
        }

        return $s;
    }

    function rentalratesastable() {
        $s="";
         global $vatrates;
        $raterecs= rate::getallrates();

        while ($row = mysql_fetch_assoc($raterecs) ) {

                   $s=$s."<tr><td>".$row['id']."</td>";
                   $s=$s."<td><input type='text' class='ltext' name='ratetitle[".$row['id']."]' value='".$row['ratetitle']."' /></td>";
                   $s=$s."<td><input type='text' class='ntext' name='ratebaseprice[".$row['id']."]' value='".$row['baseprice']."' /></td>";
                   $s=$s."<td><input type='text' class='ntext' name='ratebaseunit[".$row['id']."]' value='".$row['baseunit']."' /></td>";
                   $s=$s."<td><input type='text' class='ntext' name='ratepriceperunit[".$row['id']."]' value='".$row['priceperunit']."' /></td>";

                   $omenu="<select name='ratetype[".$row['id']."]' >/n";
                   $omenu=$omenu."<option value='boat' ";
                   if ($row['ratetype']=="boat") {
                        $omenu=$omenu." selected ";
                   }

                   $omenu=$omenu.">boat</option>/n ";

                   $omenu=$omenu."<option value='menu' ";
                   if ($row['ratetype']=="menu") {
                        $omenu=$omenu." selected ";
                   }

                   $omenu=$omenu.">menu</option>/n ";



                   $omenu=$omenu."</select>";
                   $s=$s."<td>".$omenu."</td>";


                   $s=$s."<td><input type='checkbox' name='includesvat[".$row['id']."]' ";

                   if ($row['includesvat']) {
                        $s=$s." checked ";
                   }
                   $s=$s."  /></td>";

                   $s=$s."<td><select name='vatcode[".$row['id']."]' >/n";

                   foreach ($vatrates as  $key=>$value) {
                        $s=$s."<option value='".$key."' ";
                        if ($row['vatcode']==$key) {
                         $s = $s. " selected ";
                        }
                        $s= $s.">".$value." %</option>/n ";
                   }
                   $s=$s."</select></td>";

//                   $s=$s."<td><input type='text' class='ntext' name='vatcode['".$key."] value='".$row['vatcode']."' /></td>";

                   $s=$s."<td><input type='checkbox' name='delete[".$row['id']."]' value='delete' /></td></tr>\n\n";
        }

        return $s;
    }

    function fetchrental($id,$field) {
        $s="";

        $rentalobj = new rentalobj();


        $rentalobj->getrentalobj($id);
        foreach ($rentalobj as $key=>$value) {
         if ($key==$field) {
                $s=$value;
         }
        }



        return $s;
    }

    function fetchlocations($id,$locationtype) {
        $s="";

        $rentalobj = new rentalobj();
        $rate= new rate();

        $rentalobj->getrentalobj($id);
        foreach ($rentalobj->locations as $key=>$value) {
            $s=$s."<option value='$value' ";
        //    if ($value==$locationid) {
        //        $locationlstasoption=$locationlstasoption." selected ";
        //    }
            $s=$s.">$key</option>";
        }

        return $s;

    }

    function updatefunc() {
        $funcobj = new func();
        $bookingobj = new booking();
        $exists=false;
        global $shopid;

        $crypt=new crypt();


        if (isset($_REQUEST['bookingid'])) {
                $funcobj->bookingid = $_REQUEST['bookingid'];
                $bookingobj->getbooking( $_REQUEST['bookingid'] );
                $defdate = substr($bookingobj->startdatetime,0,10);

                if ($funcobj->getfunc($funcobj->bookingid)>0) {
                  $exists=true;

                }
        }


        if (isset($_REQUEST['funcobjcrewboardingtime'])) {
                $funcobj->crewboardingtime =  $defdate." ".$_REQUEST['funcobjcrewboardingtime'];
        }

        if (isset($_REQUEST['funcobjcrewboardinglocationid'])) {
                $funcobj->crewboardinglocationid =   $_REQUEST['funcobjcrewboardinglocationid'];
        }


        if (isset($_REQUEST['funcobjcrewboardinglocationtext'])) {
                $funcobj->crewboardinglocationtext =   $_REQUEST['funcobjcrewboardinglocationtext'];
        }

        if (isset($_REQUEST['funcobjcrewsailtime'])) {
                $funcobj->crewsailtime =   $defdate." ".$_REQUEST['funcobjcrewsailtime'];
        }
        if (isset($_REQUEST['funcobjcrewremarks'])) {
                $funcobj->crewremarks =  $_REQUEST['funcobjcrewremarks'] ;
        }

        if (isset($_REQUEST['funcobjcateringdeliverytime'])) {
                $funcobj->cateringdeliverytime = $defdate." ".$_REQUEST['funcobjcateringdeliverytime'];
        }
        if (isset($_REQUEST['funcobjcateringdeliverylocationid'])) {
                $funcobj->cateringdeliverylocationid = $_REQUEST['funcobjcateringdeliverylocationid'];
        }
        if (isset($_REQUEST['funcobjcateringdeliverylocationtext'])) {
                $funcobj->cateringdeliverylocationtext = $_REQUEST['funcobjcateringdeliverylocationtext'];
        }


        if (isset($_REQUEST['funcobjguestevent'])) {
                $funcobj->guestevent =  $_REQUEST['funcobjguestevent'];
        }

        if (isset($_REQUEST['funcobjguestboardingtime'])) {
                $funcobj->guestboardingtime =  $defdate." ".$_REQUEST['funcobjguestboardingtime'];
        }
        if (isset($_REQUEST['funcobjguestboardinglocationid'])) {
                $funcobj->guestboardinglocationid = $_REQUEST['funcobjguestboardinglocationid'];
        }

        if (isset($_REQUEST['funcobjguestboardinglocationtext'])) {
                $funcobj->guestboardinglocationtext = $_REQUEST['funcobjguestboardinglocationtext'];
        }

        if (isset($_REQUEST['funcobjguestboardingsailtime'])) {
                $funcobj->guestboardingsailtime = $defdate." ".$_REQUEST['funcobjguestboardingsailtime'];
        }
        if (isset($_REQUEST['funcobjguestboardingremarks'])) {
                $funcobj->guestboardingremarks = $_REQUEST['funcobjguestboardingremarks'];
        }

        if (isset($_REQUEST['funcobjgueststopover1time'])) {
                $funcobj->gueststopover1time =  $defdate." ".$_REQUEST['funcobjgueststopover1time'];
        }

        if (isset($_REQUEST['funcobjstopover1locationid'])) {
                $funcobj->gueststopover1locationid = $_REQUEST['funcobjstopover1locationid'];
        }

        if (isset($_REQUEST['funcobjgueststopover1locationtext'])) {
                $funcobj->gueststopover1locationtext = $_REQUEST['funcobjgueststopover1locationtext'];
        }

        if (isset($_REQUEST['funcobjgueststopover1sail'])) {
                $funcobj->gueststopover1sailtime =  $defdate." ".$_REQUEST['funcobjgueststopover1sail'];
        }
        if (isset($_REQUEST['funcobjgueststopover1remarks'])) {
                $funcobj->gueststopover1remarks =  $_REQUEST['funcobjgueststopover1remarks'];
        }
        if (isset($_REQUEST['funcobjgueststopover2time'])) {
                $funcobj->gueststopover2time = $defdate." ".$_REQUEST['funcobjgueststopover2time'];
        }
        if (isset($_REQUEST['funcobjgueststopover2locationid'])) {
                $funcobj->gueststopover2locationid = $_REQUEST['funcobjgueststopover2locationid'];
        }
        if (isset($_REQUEST['funcobjgueststopover2locationtext'])) {
                $funcobj->gueststopover2locationtext = $_REQUEST['funcobjgueststopover2locationtext'];
        }

        if (isset($_REQUEST['funcobjgueststopover2sailtime'])) {
                $funcobj->gueststopover2sailtime = $defdate." ".$_REQUEST['funcobjgueststopover2sailtime'];
        }
        if (isset($_REQUEST['funcobjgueststopover2remarks'])) {
                $funcobj->gueststopover2remarks =  $_REQUEST['funcobjgueststopover2remarks'];
        }

        if (isset($_REQUEST['funcobjguestdisembarktime'])) {
                $funcobj->guestdisembarktime =  $defdate." ".$_REQUEST['funcobjguestdisembarktime'];
        }
        if (isset($_REQUEST['funcobjguestdisembarklocationid'])) {
                $funcobj->guestdisembarklocationid =$_REQUEST['funcobjguestdisembarklocationid'];
        }
        if (isset($_REQUEST['funcobjguestdisembarklocationtext'])) {
                $funcobj->guestdisembarklocationtext =$_REQUEST['funcobjguestdisembarklocationtext'];
        }
        if (isset($_REQUEST['funcobjguestdisembarksailtime'])) {
                $funcobj->guestdisembarksailtime =  $defdate." ".$_REQUEST['funcobjguestdisembarksailtime'];
        }
        if (isset($_REQUEST['funcobjguestdisembarkremarks'])) {
                $funcobj->guestdisembarkremarks = $_REQUEST['funcobjguestdisembarkremarks'];
        }
        if (isset($_REQUEST['funcobjcrewdisembarktime'])) {
                $funcobj->crewdisembarktime = $defdate." ".$_REQUEST['funcobjcrewdisembarktime'];
        }
        if (isset($_REQUEST['funcobjcrewdisembarklocationid'])) {
                $funcobj->crewdisembarklocationid = $_REQUEST['funcobjcrewdisembarklocationid'];
        }
        if (isset($_REQUEST['funcobjcrewdisembarklocationtext'])) {
                $funcobj->crewdisembarklocationtext = $_REQUEST['funcobjcrewdisembarklocationtext'];
        }
        if (isset($_REQUEST['funcobjcrewdisembarkremarks'])) {
                $funcobj->crewdisembarkremarks =  $_REQUEST['funcobjcrewdisembarkremarks'];
        }
        if (isset($_REQUEST['funcobjwelcomedrink'])) {
                $funcobj->welcomedrink =  $_REQUEST['funcobjwelcomedrink'];
        }
        if (isset($_REQUEST['funcobjdrinkremarks'])) {
                $funcobj->drinkremarks =   $_REQUEST['funcobjdrinkremarks'];
        }
        if (isset($_REQUEST['funcobjmeal1remarks'])) {
                $funcobj->meal1remarks = $_REQUEST['funcobjmeal1remarks'];
        }
        if (isset($_REQUEST['funcobjmeal2remarks'])) {
                $funcobj->meal2remarks =  $_REQUEST['funcobjmeal2remarks'];
        }

        if (isset($_REQUEST['funcobjnotes'])) {
                $funcobj->notes =  $_REQUEST['funcobjnotes'];
        }

//   Handle Contacts

        if (isset($_REQUEST['funcobjskipperid'])) {

            $clobj = new bookingcontactlink();
            $clobj->bookingid = $funcobj->bookingid;
            $clobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['funcobjskipperid'])));
            $clobj->status='active';
            $clobj->id=-1;

            $category = new category();
            $category->getcategory($shopid,'Skipper');
            $clobj->categoryid = $category->id;

            $catfound=0;
            foreach ($bookingobj->contacts as $k=>$contactlinkobj ) {
                if ($contactlinkobj->categoryid==$category->id) {
                     if ($contactlinkobj->contactid!=$clobj->contactid) {
                             $contactlinkobj->contactid=$clobj->contactid;
                             $catfound=1;
                     }
                }
             }
             if ($catfound==0) {
                    $bookingobj->contacts[]=$clobj;
             }

        }

        if (isset($_REQUEST['funcobjmateid'])) {

            $clobj = new bookingcontactlink();
            $clobj->bookingid = $funcobj->bookingid;
            $clobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['funcobjmateid'])));
            $clobj->status='active';
            $clobj->id=-1;

            $category = new category();
            $category->getcategory($shopid,'Mate');
            $clobj->categoryid = $category->id;
            $catfound=0;
            foreach ($bookingobj->contacts as $k=>$contactlinkobj ) {
                if ($contactlinkobj->categoryid==$category->id) {
                     if ($contactlinkobj->contactid!=$clobj->contactid) {
                             $contactlinkobj->contactid=$clobj->contactid;
                             $catfound=1;
                     }
                }
             }
             if ($catfound==0) {
                    $bookingobj->contacts[]=$clobj;
             }

        }


        if (isset($_REQUEST['funcobjstewardid'])) {

            $clobj = new bookingcontactlink();
            $clobj->bookingid = $funcobj->bookingid;
            $clobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['funcobjstewardid'])));
            $clobj->status='active';
            $clobj->id=-1;

            $category = new category();
            $category->getcategory($shopid,'Steward');
            $clobj->categoryid = $category->id;
            $catfound=0;
            foreach ($bookingobj->contacts as $k=>$contactlinkobj ) {
                if ($contactlinkobj->categoryid==$category->id) {
                     if ($contactlinkobj->contactid!=$clobj->contactid) {
                             $contactlinkobj->contactid=$clobj->contactid;
                             $catfound=1;
                     }
                }
             }
             if ($catfound==0) {
                    $bookingobj->contacts[]=$clobj;
             }

        }

        if (isset($_REQUEST['funcobjguestid'])) {

            $clobj = new bookingcontactlink();
            $clobj->bookingid = $funcobj->bookingid;
            $clobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['funcobjguestid'])));
            $clobj->status='active';
            $clobj->id=-1;

            $category = new category();
            $category->getcategory($shopid,'Principle');
            $clobj->categoryid = $category->id;
            $catfound=0;
            foreach ($bookingobj->contacts as $k=>$contactlinkobj ) {
                if ($contactlinkobj->categoryid==$category->id) {
                     if ($contactlinkobj->contactid!=$clobj->contactid) {
                             $contactlinkobj->contactid=$clobj->contactid;
                             $catfound=1;
                     }
                }
             }
             if ($catfound==0) {
                    $bookingobj->contacts[]=$clobj;
             }

        }

        if (isset($_REQUEST['funcobjcatererid'])) {

            $clobj = new bookingcontactlink();
            $clobj->bookingid = $funcobj->bookingid;
            $clobj->contactid = trim($crypt->decrypt(rawurldecode($_REQUEST['funcobjcatererid'])));
            $clobj->status='active';
            $clobj->id=-1;

            $category = new category();
            $category->getcategory($shopid,'Caterer');
            $clobj->categoryid = $category->id;
            $catfound=0;
            foreach ($bookingobj->contacts as $k=>$contactlinkobj ) {
                if ($contactlinkobj->categoryid==$category->id) {
                     if ($contactlinkobj->contactid!=$clobj->contactid) {
                             $contactlinkobj->contactid=$clobj->contactid;
                             $catfound=1;
                     }
                }
             }

             if ($catfound==0) {
                    $bookingobj->contacts[]=$clobj;
             }

        }


        if (isset($funcobj->bookingid)) {
            if ($exists) {
                $funcobj->updatefuncbybookingid($funcobj->bookingid);
            } else {
                $funcobj->insertfunc() ;
            }
        }

        $bookingobj->updatebooking();


        // send the emails

        if (isset($_REQUEST['funcobjsendnotification']) ) {
                if ($_REQUEST['funcobjsendnotification']==1) {
						
                        sendemails($funcobj->bookingid);
                } // send
        } // if exists

        return $funcobj->id;

    }

    function showfunc($s) {
            $funcobj = new func() ;
            if (isset($_REQUEST['bookingid'])) {
                $bookingid = $_REQUEST['bookingid'];
                $funcobj->getfunc($bookingid);
            }



            $location= new location();


            $s= str_replace('<%funcobjskipperid%>', $funcobj->skipperid, $s);
            $s= str_replace('<%funcobjmateid%>', $funcobj->mateid, $s);


            $s= str_replace('<%funcobjcrewboardingtime%>', $funcobj->crewboardingtime, $s);
            $s= str_replace('<%funcobjcrewboardinglocationid%>', $funcobj->crewboardinglocationid, $s);

           
            if (strlen($funcobj->crewboardinglocationtext)>0) {
                    $s= str_replace('<%funcobjcrewboardinglocationtext%>', $funcobj->crewboardinglocationtext , $s);
            } else {
                    $location->getlocationbyid($funcobj->crewboardinglocationid);
                    $s= str_replace('<%funcobjcrewboardinglocationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjcrewsailtime%>', $funcobj->crewsailtime, $s);
            $s= str_replace('<%funcobjcrewremarks%>', $funcobj->crewremarks, $s);

            $s= str_replace('<%funcobjcateringdeliverytime%>', $funcobj->cateringdeliverytime, $s);
            $s= str_replace('<%funcobjcateringdeliverylocationid%>', $funcobj->cateringdeliverylocationid, $s);

            if (strlen($funcobj->cateringdeliverylocationtext)>0) {
                    $s= str_replace('<%funcobjcateringdeliverylocationtext%>', $funcobj->cateringdeliverylocationtext , $s);
            } else {
                   $location->getlocationbyid($funcobj->cateringdeliverylocationid);
                   $s= str_replace('<%funcobjcateringdeliverylocationtext%>', $location->location, $s);
            }





            $s= str_replace('<%funcobjguestevent%>', $funcobj->guestevent, $s) ;

            $s= str_replace('<%funcobjguestboardingtime%>', $funcobj->guestboardingtime, $s);
            $s= str_replace('<%funcobjguestboardinglocationid%>', $funcobj->guestboardinglocationid, $s);

            if (strlen($funcobj->guestboardinglocationtext)>0) {
                    $s= str_replace('<%funcobjguestboardinglocationtext%>', $funcobj->guestboardinglocationtext , $s);
            } else {
                   $location->getlocationbyid($funcobj->guestboardinglocationid);
                   $s= str_replace('<%funcobjguestboardinglocationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjguestboardingsailtime%>', $funcobj->guestboardingsailtime, $s);
            $s= str_replace('<%funcobjguestboardingremarks%>', $funcobj->guestboardingremarks, $s);

            $s= str_replace('<%funcobjgueststopover1time%>', $funcobj->gueststopover1time, $s);
            $s= str_replace('<%funcobjgueststopover1locationid%>', $funcobj->gueststopover1locationid, $s);

            if (strlen($funcobj->gueststopover1locationtext)>0) {
                    $s= str_replace('<%funcobjgueststopover1locationtext%>', $funcobj->gueststopover1locationtext , $s);
            } else {
                    $location->getlocationbyid($funcobj->gueststopover1locationid);
                    $s= str_replace('<%funcobjgueststopover1locationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjgueststopover1sailtime%>', $funcobj->gueststopover1sailtime, $s);
            $s= str_replace('<%funcobjgueststopover1remarks%>', $funcobj->gueststopover1remarks, $s);

            $s= str_replace('<%funcobjgueststopover2time%>', $funcobj->gueststopover2time, $s);
            $s= str_replace('<%funcobjgueststopover2locationid%>', $funcobj->gueststopover2locationid, $s);

            if (strlen($funcobj->gueststopover2locationtext)>0) {
                    $s= str_replace('<%funcobjgueststopover2locationtext%>', $funcobj->gueststopover2locationtext , $s);
            } else {
                    $location->getlocationbyid($funcobj->gueststopover2locationid);
                    $s= str_replace('<%funcobjgueststopover2locationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjgueststopover2sailtime%>', $funcobj->gueststopover2sailtime, $s);
            $s= str_replace('<%funcobjgueststopover2remarks%>', $funcobj->gueststopover2remarks, $s);

            $s= str_replace('<%funcobjguestdisembarktime%>', $funcobj->guestdisembarktime, $s);
            $s= str_replace('<%funcobjguestdisembarklocationid%>', $funcobj->guestdisembarklocationid, $s);

            if (strlen($funcobj->guestdisembarklocationtext)>0) {
                    $s= str_replace('<%funcobjguestdisembarklocationtext%>', $funcobj->guestdisembarklocationtext , $s);
            } else {
                     $location->getlocationbyid($funcobj->guestdisembarklocationid);
                     $s= str_replace('<%funcobjguestdisembarklocationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjguestdisembarksailtime%>', $funcobj->guestdisembarksailtime, $s);
            $s= str_replace('<%funcobjguestdisembarkremarks%>', $funcobj->guestdisembarkremarks, $s);

            $s= str_replace('<%funcobjcrewdisembarktime%>', $funcobj->crewdisembarktime, $s);
            $s= str_replace('<%funcobjcrewdisembarklocationid%>', $funcobj->crewdisembarklocationid, $s);


            if (strlen($funcobj->crewdisembarklocationtext)>0) {
                    $s= str_replace('<%funcobjcrewdisembarklocationtext%>', $funcobj->crewdisembarklocationtext , $s);
            } else {
                $location->getlocationbyid($funcobj->crewdisembarklocationid);
                $s= str_replace('<%funcobjcrewdisembarklocationtext%>', $location->location, $s);
            }

            $s= str_replace('<%funcobjcrewdisembarkremarks%>', $funcobj->crewdisembarkremarks, $s);

            $s= str_replace('<%funcobjwelcomedrink%>', $funcobj->welcomedrink, $s);
            $s= str_replace('<%funcobjdrinkremarks%>', $funcobj->drinkremarks, $s);
            $s= str_replace('<%funcobjmeal1remarks%>', $funcobj->meal1remarks, $s);
            $s= str_replace('<%funcobjmeal2remarks%>', $funcobj->meal2remarks, $s);
            $s= str_replace('<%funcobjnotes%>', $funcobj->notes, $s);




        return $s;

    }

    function sendemails($bookingid) {
           global $shopid;

            $funcobj = new func() ;
            $booking = new booking();

            if (isset($bookingid)) {
                $funcobj->getfunc($bookingid);
                $booking->getbooking($bookingid);
            }



        // Final emails
        $rentalobj = new rentalobj();
        $rentalobj->getrentalobj($booking->rentalobjid);


        $subject="From Henley Sales And Charter Function Sheet ".$booking->id;

               global $siteadminemail;
               global $company;

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $headers .= "From: ".$company."<".$siteadminemail.">" . "\r\n";

                $message = $message.$company." are please to inform you that a booking has been made requiring your attention.  The following link gives details: \n<br/>\n<br/>";

                $message = $message." <a href=\"".SITE_URL."/content.php?template=templates/func_show.html&bookingid=".$booking->id."\" >click here to view the function sheet</a> <br/><br/>\n\n";

                $message = $message."Function id: ".$booking->id."\n<br/><br/>\n";
                $message = $message." Charter Starts: ".$booking->startdatetime." Finishes: ".$booking->enddatetime."\n<br/><br/>\n";
                $message = $message." Boat ".$rentalobj->title."\n<br/><br/>\n";

                $message = $message." For any queries please contact us on 01491 578870 \n<br/><br/>\n"  ;
                $message = $message." Kind regards\n\n  \n<br/><br/>\n"  ;


                $client = new contact();
                $client->getcontactbyid($booking->customerid);

                if ($booking->emailclient ) {
                      mail($client->email,$subject,$message,$headers);
                }

              $owner = new contact();
              $owner->getcontactbyid($rentalobj->ownerid);

              if ($booking->emailowner) {
                  if (strlen($owner->email)>0) {
                      mail($owner->email,$subject,$message,$headers);
                  }
              }

        // Now the rest
           //  echo $message;
        foreach ($booking->contacts as $key=>$obj) {

             if ($obj->status=="active") {

                   $contact = new contact();
                   $contact->getcontactbyid($obj->contactid);
                   $category = new category();
                   $categorycode =  $category->getcategorycodebyid($shopid,$obj->categoryid);


                   if ($categorycode=='Skipper') {
                        if ($booking->emailskipper) {
                               mail($contact->email,$subject,$message,$headers);
                        }
                   }
                   if ($categorycode=='Mate') {
                        if ($booking->emailmate) {
                               mail($contact->email,$subject,$message,$headers);
                        }
                   }
                   if ($categorycode=='Crew') {
                        if ($booking->emailmate) {
                               mail($contact->email,$subject,$message,$headers);
                        }
                   }
                   if ($categorycode=='Caterer') {
                        if ($booking->emailcaterer) {
                               mail($contact->email,$subject,$message,$headers);
                        }
                   }

             }
        }

         mail( "mark@merchantmakers.co.uk", $subject, $message ,$headers);


    }

    function checkavailability() {
            $dater=new dater();

      $s="true";

      if (isset($_REQUEST['fromdate'])) {
        if (isset($_REQUEST['todate'])) {

          $dtefrom =  $dater->phptosqldate($_REQUEST['fromdate']);
          $dteto =  $dater->phptosqldate($_REQUEST['todate']);
          $rentalobjid=$_REQUEST['rentalobjid'];
          // bookingstarttime
          // if  bookingstarttime<thisstarttime and bookingendtime>thisstarttime then a booking clash
          // if  bookingstarttime<thisendtime  and bookingendtime>thisendtime  then a bookingclash
          $query = sprintf("SELECT ".TBL_PREFIX."bookings.* FROM ".TBL_PREFIX."bookings  ".
                  " WHERE ( (
								(( %s>=".TBL_PREFIX."bookings.startdatetime) AND ( %s<=".TBL_PREFIX."bookings.enddatetime) ) OR ".
                            "(( %s>=".TBL_PREFIX."bookings.startdatetime) AND ( %s<=".TBL_PREFIX."bookings.enddatetime) ) OR ".
							"( (%s<=".TBL_PREFIX."bookings.startdatetime) AND ( %s>=".TBL_PREFIX."bookings.enddatetime) ) ".
							" ) AND ".
                            "(".TBL_PREFIX."bookings.rentalobjid=%s )".
                             ")",
                               GetSQLValueString($dtefrom,'text','',''),
                               GetSQLValueString($dtefrom,'text','',''),
                               GetSQLValueString($dteto,'text','',''),
                               GetSQLValueString($dteto,'text','',''),
                               GetSQLValueString($dtefrom ,'text','',''),
							   GetSQLValueString($dteto,'text','',''),
                               GetSQLValueString($rentalobjid,'text','','')
                                ) ;

								
								
          $res = dosql($query);

          if (mysql_num_rows($res) >0) {
                 $s= "false";
          }
        }
      }
      return $s;
    }

    function showcalendar($s) {
                $dater=new dater();



                $datefrom = mktime(0, 0, 0, date("m")-3, date("d"),   date("Y"));
                $datefrom = date('d/m/Y',$datefrom)."<br/>";

//date("d/m/Y");
           //     $datefrom->sub(new DateInterval('P60D'));

                $dateto = "31/12/2099";
                $editoptions= array();
                $rentalobjid=-1;

                if (isset($_SESSION['datefrom'])) {
                        $datefrom = trim($_SESSION['datefrom']);
                }

                if (isset($_SESSION['dateto'])) {
                        $dateto = trim($_SESSION['dateto']);
                }

                if (isset($_SESSION['bookingid'])) {
                        $bookingid = trim($_SESSION['bookingid']);
                }

                if (isset($_REQUEST['datefrom'])) {
                        $datefrom = trim($_REQUEST['datefrom']);
                        $_SESSION['datefrom']=$datefrom;
                }

                if (isset($_REQUEST['dateto'])) {
                        $dateto = trim($_REQUEST['dateto']);
                        $_SESSION['dateto']=$dateto;
                }

                if (isset($_REQUEST['bookingid'])) {
                        $bookingid = trim($_REQUEST['bookingid']);
                        $_SESSION['bookingid']=$bookingid;
                }

                if (isset($_REQUEST['searchstr'])) {
                        $searchstr = trim($_REQUEST['searchstr']);
                }

                if (isset($_REQUEST['editoptions']) || isset($_SESSION['editoptions']) ) {
                   if (isset($_REQUEST['editoptions'])) {
                       $_SESSION['editoptions']=trim($_REQUEST['editoptions']);
                   }

                   $editoptions =explode( "|", $_SESSION['editoptions'] );
                   $s = str_replace ( "<%calendareditoptions%>",$_SESSION['editoptions'],$s);
                }


                if (isset($_REQUEST['rentalobjid'])) {
                   $rentalobjid = $_REQUEST['rentalobjid'];
                }

                $t="";
                $fullcalendar="";

                $notfirst=0;

                $sdx=strpos($s,'<%bookingcalendar');

        while ($sdx>0) {

               $options = tagoptions('<%bookingcalendar',$s);
               unset($optionsa);
               $optionsa = explode('&',$options);
             //  $order =  " order by ".TBL_PREFIX."rentalobj.title"; startdatetime
               $order =  " order by ".TBL_PREFIX."bookings.startdatetime";
               $contactid = 0;
          //     $rentalobjid = -1;

                foreach ($optionsa as $key=>$value) {
                        $oa = explode("=",$value);
                        if ($oa[0]=='orderby') {
                          $order=' order by '.$oa[1];
                        }
                        if ($oa[0]=='contactid') {
                          $contactid=$oa[1];
                        }
                        if ($oa[0]=='rentalobjid') {
                          $rentalobjid=$oa[1];
                        }

                        if ($oa[0]=='fullcalendarevents') {
                          $fullcalendarevents=$oa[1];
                        }
                }

                $calres=booking::getallbookings("","",0,9999,$order ,$contactid, $datefrom, $dateto, $rentalobjid,$bookingid );

                           $cid='even';
                           $id=0;
                           $t="";
						   $te = "";						   
                           $fullcalendar="";

                           while ($row = mysql_fetch_assoc($calres) ) {

                             $client = new contact;
                             $client->getcontactbyid($row['customerid']);

                             $match = true;
                             if (!empty($searchstr) && (strlen($searchstr)>0)) {
                               $match=false;

                               if (strpos(strtolower(" ".$client->lastname),strtolower($searchstr) )>0) {
                                 $match=true;
                               }
                             }

                             if ($match===true) {
                               if ($id==0) {
                                       $cid='even';
                                       $id=1;
                                    }  else {
                                       $cid='odd';
                                       $id=0;
                                    }

                                    $dur= ($dater->mkdate($dater->sqltophpdatetime($row['enddatetime'],2))-$dater->mkdate($dater->sqltophpdatetime($row['startdatetime'],2)))/60/60;

                                    $t=$t."<tr>"; // class='$cid' 


                                    $t=$t."<td>";  // class='css_bookingid' 

                                //    if ($row['bookingstatus']!="invoiced") {
                                            if (in_array("booking",$editoptions,true)) {
                                                    $t=$t."<a href='content.php?template=templates/bookingedit.html&bookingid=".$row['bookingid']."'>B</a> ";
                                            }
                                            if (in_array("function",$editoptions,true)) {
                                                    $t=$t."<a href='content.php?template=templates/func_edit.html&bookingid=".$row['bookingid']."'>F</a> ";
                                            }

                                            if (in_array("delete",$editoptions,true)) {
                                               // if (round($row['deposit'])==0) {
                                                        $t=$t."<a href='content.php?template=templates/bookingdelete.html&bookingid=".$row['bookingid']."'>D</a> ";
                                              //  }
                                            }


                                  //  } else {
                                  //          if (in_array("invoiced",$editoptions,true)) {
                                  //                  $t=$t."<a href='content.php?template=templates/invoice_print.html&bookingid=".$row['bookingid']."'>I</a> ";
                                  //          }
                                  //  }


                                    $d_s = $dater->sqltophpdatetime($row['startdatetime'],2);
                                    $d_e = $dater->sqltophpdatetime($row['enddatetime'],2);
                                    $t=$t."</td><td>".$row['bookingid']."</td>";  //class='css_bookingid'
                                    $t=$t."<td>".$row['title']."</td>"; // class='css_rentaltitle' 
                                    $t=$t."<td id='starttime'>".$d_s."</td>"; // class='css_starttime' 
                                    $t=$t."<td id='endtime' >".$d_e."</td>"; //class='css_endtime' 
                                    $t=$t."<td id='duration' >".$dur."</td>"; //class='css_duration' 
                                    $t=$t."<td id='status' >".$row['bookingstatus']."</td>"; //class='css_status' 
                                    $t=$t."<td id='client' >".$client->lastname." ".$client->firstname."</td>"; //class='css_client' 
                                    $t=$t."</tr>";
									
									$te = $te."".$row['bookingid'].",".$row['title'].",".$d_s.",".$d_s.",".$d_e.",".$dur.",".$row['bookingstatus'].",".$client->lastname." ".$client->firstname."\n";

                                    $d_s_y =  substr($d_s,6,4);
                                    $d_s_m = substr($d_s,3,2);
                                    $d_s_d = substr($d_s,0,2) ;

									$remove[] = "'";
									$remove[] = '"';
									$remove[] = "-"; // just as another example

									$client->lastname = str_replace( $remove, "", $client->lastname );
                                    if ($notfirst==1) {
                                      $fullcalendar = $fullcalendar.",";
                                     
                                    } else {
                                      $notfirst=1;
                                    }
                                    $fullcalendar=$fullcalendar."{  id: ".$row['bookingid'].",\n".
                                                                "title: \"".$row['title']." (".$client->lastname.")\",\n".
                                                                "start: new Date(".$d_s_y.",".($d_s_m-1).",".$d_s_d.",".substr($d_s,11,2).",".substr($d_s,14,2)."),\n".
                                                                "end: new Date(".substr($d_e,6,4).",".(substr($d_e,3,2)-1).",".substr($d_e,0,2).",".substr($d_e,11,2).",".substr($d_e,14,2)." ), \n".
                                                                "allDay:false,"."\n".
																"lastname:'".$client->lastname."',"."\n".
																"firstname:'".$client->firstname."',"."\n".
																"rental:'".$row['title']."',"."\n".
																"telephone1:'".$client->telephone1."',"."\n".
																"mobile:'".$client->mobile."',"."\n".
																"backgroundColor:'".$row['color']."',"."\n".
																"eventColor:'".$row['color']."',"."\n".
                                                                "url: 'content.php?template=templates/bookingedit.html&bookingid=".$row['bookingid']."'\n".
                                                                "}"."\n";
                                    /*
                                                                    {
                                        title: 'Lunch',
                                        start: new Date(y, m, d, 12, 0),
                                        end: new Date(y, m, d, 14, 0),
                                        allDay: false
                                },
                                    */
                                 }     // if match
                               } // while

                               if ($options>"") {
                                  $options="|".$options;
                               }


                           if ($fullcalendarevents=="yes") {
                                $s=str_replace("<%bookingcalendar".$options."%>",$fullcalendar,$s);
                           } else {
                                $s=str_replace("<%bookingcalendar".$options."%>",$t,$s);
                           }

                           $sdx=strpos($s,'<%bookingcalendar');


        }
                   //  echo "abcd";exit;
                 if ($_REQUEST['action']=='printcalendar' ) {
						header('Content-Type: text/csv; charset=utf-8');
						header('Content-Disposition: attachment; filename=bookings.csv');
						echo $te;
						exit;
				 }
				 
                return $s;
    }

?>
