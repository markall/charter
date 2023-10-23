<?php

class bookingcontactlink  {

        var $id="";
        var $bookingid="";
        var $contactid="";
        var $categoryid="";
        var $status="";

        function bookingcontactlink() {
                $id="";
                $bookingid="";
                $contactid="";
                $categoryid="0";
                $status="active";
        }

        function getbookingcontactlink() {
                 $query = sprintf("SELECT ".TBL_PREFIX."bookingcontactlink.*".
                                  " FROM ".TBL_PREFIX."bookingcontactlink  ".
                                  "WHERE ".TBL_PREFIX."bookingcontactlink.bookingid='%s' AND".
                                  TBL_PREFIX."bookingcontactlink.contactid='%s' "
                                  ,$this->bookingid,$this->contactid );

                                $rec2 = dosql($query);
                                return $rec2;
        }
}

class booking {
        var $id=0;
        var $shopid=0;
        var $connection="";
        var $rentalobjid=0;
        var $rateid=0;
        var $customerid=0;
        var $startdatetime="";
        var $enddatetime="";
        var $pickuplocationid=0;
        var $dropofflocationid=0;
                var $pickuplocationtext = "";
                var $dropofflocationtext = "";
        var $numberofpeople=0;
        var $status="";
        var $rentaltype="";
        var $transport="";
        var $menuid=0;
        var $catering=false;
        var $cateringnotes="";
        var $drinknotes="";
        var $othernotes="";
        var $adminnotes="";		
        var $takenbyid=0;
        var $extras = array();
        var $basecost=0;
        var $menucost=0;
        var $additionalcost=0;
        var $deposit=0;
        var $depositdate="";
		var $depositpaid = "";
		var $balancepaid = "";
		var $invoicenumber = "";
        var $paid=0;
        var $paiddate="";
        var $netcost=0;
        var $vatrate=0;

        var $emailskipper=0;
        var $emailmate=0;
        var $emailcaterer=0;
        var $emailclient=0;
        var $emailowner=0;

        var $contacts=array();



        function createbookingtable($connection) {

        /*
        ALTER TABLE `mm_bookings` ADD `emailskipper` BOOL NOT NULL DEFAULT '0' AFTER `vatrate` ,
ADD `emailmate` BOOL NOT NULL DEFAULT '0' AFTER `emailskipper` ,
ADD `emailcaterer` BOOL NOT NULL DEFAULT '0' AFTER `emailmate` ,
ADD `emailclient` BOOL NOT NULL DEFAULT '0' AFTER `emailcaterer` ,
ADD `emailowner` BOOL NOT NULL DEFAULT '0' AFTER `emailclient`
        */
                $query="drop table if exists ".TBL_PREFIX."bookings; ";
                $userrec = dosql($query);

                $query="drop table if exists ".TBL_PREFIX."bookingextras; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."bookings`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `rentalobjid` int(11),".
                "  `rateid` int(11),".
                "  `pickuplocationid` int(11),".
                "  `dropofflocationid` int(11),".
                "  `pickuplocationtext` varchar(250) default '' ,".
                "  `dropofflocationtext` varchar(250) default '' ,".
                "  `customerid` int(11),".
                "  `startdatetime` datetime default '0000-00-00 00:00:00',".
                "  `enddatetime` datetime default '0000-00-00 00:00:00',".
                "  `status` varchar(20) default '',".
                "  `rentaltype` varchar(20) default '',".
                "  `numberofpeople` int(11),".
                "  `transport` boolean,".
                "  `menuid` int(11), ".
                "  `catering` boolean,".
                "  `cateringnotes` text,".
                "  `drinknotes` text,".
                "  `othernotes` text,".
                "  `adminnotes` text,".				
                "  `takenbyid` int(11), ".
                "  `basecost` decimal(10,2) ,".
                "  `menucost` decimal(10,2) ,".
                "  `additionalcost` decimal(10,2) ,".
                "  `deposit` decimal(10,2) ,".
                "  `depositdate`  datetime default '0000-00-00 00:00:00',".
                "  `paid` decimal(10,2) ,".
                "  `paiddate`  datetime default '0000-00-00 00:00:00',".
                "  `netcost` decimal(10,2) ,".
                "  `vatrate` decimal(10,2) ,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";
                   
                $bookingrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."bookingextras`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `bookingid` int(11) NOT NULL ,".
                "  `description` varchar(40) ,".
                "  `extracost` decimal(10,2) ,".
                "  PRIMARY KEY  (`id`)".
                ") ";
                $bookingrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."bookingcontactlink`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `bookingid` int(11) NOT NULL default 0,".
                "  `contactid` int(11) NOT NULL default 0,".
                "  `categoryid` int(11) NOT NULL default 0 , ".
                "  PRIMARY KEY  (`bookingid`,`contactid` )".
                ") ";
                $contactrec = dosql($query);

        }



        function booking() {
                $this->status="pending";
                $this->id=0;
                $this->shopid=0;
                $this->customerid=0;
                $this->rentalobjid=0;
                $this->startdatetime="";
                $this->enddatetime="";
                $this->pickuplocationid=0;
                $this->dropofflocationid=0;
                $this->pickuplocationtext="";
                $this->dropofflocationtext="";
                $this->status="active";
                $this->rentaltype="";
                $this->numberofpeople=0;
                $this->transport=false;
                $this->catering=false;
                $this->menuid=0;
                $this->cateringnotes="";
                $this->drinknotes="";
                $this->othernotes="";
                $this->adminnotes="";				
                $this->takenbyid="";
                $this->extras = array();
                $this->deposit=0;
                $this->depositdate="";
				$this->depositpaid=false;
				$this->balancepaid=false;
				$this->invoicenumber='';
                $this->paid=0;
                $this->paiddate="";
                $this->basecost=0;
                $this->menucost=0;
                $this->additionalcost=0;
                $this->netcost=0;
                $this->vatrate=0;

                $this->emailskipper = 0;
                $this->emailmate=0;
                $this->emailcaterer=0;
                $this->emailclient=0;
                $this->emailowner=0;

                $this->contacts = array();
                $this->wholastmod="";

        }

        function getbooking($id) {


            $dater= new dater();

            $query = sprintf("SELECT ".TBL_PREFIX."bookings.*".
                                            " FROM ".TBL_PREFIX."bookings  ".
                                            "WHERE ".TBL_PREFIX."bookings.id='%s'  " ,$id);


            $rec = dosql($query);

            $row = mysql_fetch_assoc($rec);

            if (mysql_num_rows($rec)>0) {

                $this->id=$row['id'];
                $this->shopid=$row['shopid'];
                $this->customerid=$row['customerid'];
                $this->rentalobjid=$row['rentalobjid'];
                $this->rateid=$row['rateid'];
                $this->startdatetime=$dater->sqltophpdatetime($row['startdatetime'],2);
                $this->enddatetime=$dater->sqltophpdatetime($row['enddatetime'],2);
                $this->pickuplocationid=$row['pickuplocationid'];
                $this->dropofflocationid=$row['dropofflocationid'];
                                $this->pickuplocationtext=$row['pickuplocationtext'];
                                $this->dropofflocationtext=$row['dropofflocationtext'];
                $this->status=$row["status"];
                $this->rentaltype=$row["rentaltype"];
                $this->numberofpeople=$row['numberofpeople'];
                $this->transport=$row["transport"];
                $this->catering=$row['catering'];
                $this->menuid=$row['menuid'];
                $this->cateringnotes=$row['cateringnotes'];
                $this->drinknotes=$row['drinknotes'];
                $this->othernotes=$row['othernotes'];
                $this->adminnotes=$row['adminnotes'];				
                $this->basecost=$row['basecost'];
                $this->menucost=$row['menucost'];
                $this->additionalcost=$row['additionalcost'];
                $this->deposit=$row['deposit'];
                $this->depositpaid=$row['depositpaid'];
                $this->balancepaid=$row['balancepaid'];
                $this->invoicenumber=$row['invoicenumber'];	
                $this->takenbyid=$row['takenbyid'];
                $this->depositdate=$dater->sqltophpdate($row['depositdate']);
                $this->paid=$row['paid'];
                $this->paiddate=$dater->sqltophpdate($row['paiddate']);
                $this->netcost=$row['netcost'];
                $this->vatrate=$row['vatrate'];

                $this->emailskipper = $row['emailskipper'];
                $this->emailmate= $row['emailmate'];
                $this->emailcaterer= $row['emailcaterer'];
                $this->emailclient=$row['emailclient'];
                $this->emailowner=$row['emailowner'];


            }

            unset ($this->contacts);
            $query = sprintf("SELECT ".TBL_PREFIX."bookingcontactlink.*".
                                            " FROM ".TBL_PREFIX."bookingcontactlink  ".
                                            " JOIN ".TBL_PREFIX."categories on ".TBL_PREFIX."bookingcontactlink.categoryid=".TBL_PREFIX."categories.id ".
                                            "WHERE ".TBL_PREFIX."bookingcontactlink.bookingid='%s' order by title " ,$id);
            $rec2 = dosql($query);

            while ($row = mysql_fetch_assoc($rec2) ) {
               $contactlink=new bookingcontactlink();
               $contactlink->id=$row['id'];
               $contactlink->bookingid=$row['bookingid'];
               $contactlink->contactid=$row['contactid'];
               $contactlink->categoryid=$row['categoryid'];
               $contactlink->status='active';
               $this->contacts[]=$contactlink;
            }
              
            return mysql_num_rows($rec);
        }





        function getallbookings($status,$type,$offset,$count,$order,$contactid, $startdatetime, $enddatetime, $rentalobjid ,$bookingid ) {

                global $connection;
                $dater=new dater();

                $query = "SELECT ".TBL_PREFIX."bookings.id as bookingid, ".TBL_PREFIX."bookings.status as bookingstatus, ".TBL_PREFIX."bookings.*, "  .TBL_PREFIX."rentalobj.*".
                                            " FROM ".TBL_PREFIX."bookings  ";

                if ($contactid>0) {
                   $query = $query. " LEFT JOIN ".TBL_PREFIX."bookingcontactlink on ".TBL_PREFIX."bookings.id=".TBL_PREFIX."bookingcontactlink.bookingid";
                }

                $query = $query. " LEFT JOIN ".TBL_PREFIX."rentalobj on ".TBL_PREFIX."bookings.rentalobjid=".TBL_PREFIX."rentalobj.id ";


                $and=false;

                if ($status>" ") {
                   $query=$query.' where status="'.$status.'"';
                   $and=true;
                }

                if ($type>" ") {
                   if ($and) {
                           $query=$query.' and (type REGEXP \''.$type.'\'>0)';
                   } else {
                           $query=$query.' where (type REGEXP \''.$type.'\'>0)';
                           $and=true;
                   }
                }

                if ($contactid>0) {
                   if ($and) {
                           $query=$query." and (".TBL_PREFIX."bookings.customerid=".trim($contactid)." or ".TBL_PREFIX."bookingcontactlink.contactid=".trim($contactid)." )";
                   } else {
                           $query=$query." where (".TBL_PREFIX."bookings.customerid=".trim($contactid)." or ".TBL_PREFIX."bookingcontactlink.contactid=".trim($contactid)." )";
                           $and=true;
                   }
                }

                if ($rentalobjid>0) {
                   if ($and) {
                           $query=$query." and ".TBL_PREFIX."bookings.rentalobjid=".$rentalobjid;
                   } else {
                           $query=$query." where ".TBL_PREFIX."bookings.rentalobjid=".$rentalobjid;
                           $and=true;
                   }
                }

                if ($bookingid>0) {
                   if ($and) {
                           $query=$query." and ".TBL_PREFIX."bookings.id=".$bookingid;
                   } else {
                           $query=$query." where ".TBL_PREFIX."bookings.id=".$bookingid;
                           $and=true;
                   }
                }

                if ($startdatetime!="" and $enddatetime!="") {
                   if ($and) {
                           $query=$query." and (".
                                TBL_PREFIX."bookings.startdatetime>='".$dater->phptosqldate($startdatetime)."' and ".
                                TBL_PREFIX."bookings.enddatetime<='".$dater->phptosqldate($enddatetime)."' )\n";
                   } else {
                           $query=$query." where (".
                                TBL_PREFIX."bookings.startdatetime>='".$dater->phptosqldate($startdatetime)."' and ".
                                TBL_PREFIX."bookings.enddatetime<='".$dater->phptosqldate($enddatetime)."' )\n";
                   }

                }

                $query = $query.$order;

                if ($offset>-1) {
                   $query = $query.' LIMIT '.$offset.','.$count;
                }

            $bookings = dosql($query);

            return $bookings;
        }





        function insertbooking() {

                $dater= new dater;
                global $connection;

               $query = sprintf("INSERT INTO ".TBL_PREFIX."bookings ( shopid,
                customerid,rentalobjid,rateid,startdatetime,enddatetime,pickuplocationid,dropofflocationid,pickuplocationtext,dropofflocationtext,status,rentaltype,numberofpeople,
                transport,menuid,catering,cateringnotes,drinknotes,othernotes,adminnotes,basecost,menucost,additionalcost,deposit,depositpaid,balancepaid,invoicenumber,depositdate,paid,paiddate,
                takenbyid,netcost,vatrate,emailskipper,emailmate,emailcaterer,emailclient, emailowner, datelastmod,datecreated,wholastmod )
               VALUES ( %s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->customerid,'text','',''),
                     GetSQLValueString($this->rentalobjid,'text','',''),
                     GetSQLValueString($this->rateid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->startdatetime),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->enddatetime),'date','',''),
                     GetSQLValueString($this->pickuplocationid,'text','',''),
                     GetSQLValueString($this->dropofflocationid,'text','',''),
                                        GetSQLValueString($this->pickuplocationtext,'text','',''),
                                        GetSQLValueString($this->dropofflocationtext,'text','',''),
                                        GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->rentaltype,'text','',''),
                     GetSQLValueString($this->numberofpeople,'text','',''),
                     GetSQLValueString($this->transport,'text','',''),
                     GetSQLValueString($this->menuid,'text','',''),
                     GetSQLValueString($this->catering,'text','',''),
                     GetSQLValueString($this->cateringnotes,'text','',''),
                     GetSQLValueString($this->drinknotes,'text','',''),
                     GetSQLValueString($this->othernotes,'text','',''),
                     GetSQLValueString($this->adminnotes,'text','',''),					 
                     GetSQLValueString($this->basecost,'text','',''),
                     GetSQLValueString($this->menucost,'text','',''),
                     GetSQLValueString($this->additionalcost,'text','',''),
                     GetSQLValueString($this->deposit,'text','',''),
                     GetSQLValueString($this->depositpaid,'text','',''),					 
                     GetSQLValueString($this->balancepaid,'text','',''),					 
                     GetSQLValueString($this->invoicenumber,'text','',''),					 
                     GetSQLValueString($dater->phptosqldate($this->depositdate),'date','',''),
                     GetSQLValueString($this->paid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->paiddate),'date','',''),
                     GetSQLValueString($this->takenbyid,'text','',''),
                     GetSQLValueString($this->netcost,'text','',''),
                     GetSQLValueString($this->vatrate,'text','',''),
                     GetSQLValueString($this->emailskipper,'text','',''),
                     GetSQLValueString($this->emailmate,'text','',''),
                     GetSQLValueString($this->emailcaterer,'text','',''),
                     GetSQLValueString($this->emailclient,'text','',''),
                     GetSQLValueString($this->emailowner,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );

      
           $userins = dosql($query);

          $bookingid=mysql_insert_id( $connection );


          foreach ($this->contacts as $key=>$value) {

                 $query = sprintf("INSERT INTO ".TBL_PREFIX."bookingcontactlink ( bookingid,contactid,categoryid)  VALUES(%s,%s,%s) ",
                         GetSQLValueString($bookingid,'text','',''),
                         GetSQLValueString($value->contactid,'text','',''),
                         GetSQLValueString($value->categoryid,'text','','')
                        );
                        $insrec = dosql($query);

          }

          return $bookingid;

        }

        function updatebooking() {
                global $encrypted;
                global $connection;

                    $dater= new dater;


                    $query = sprintf("UPDATE ".TBL_PREFIX."bookings set customerid=%s, rentalobjid=%s,  rateid=%s,
                                         startdatetime=%s,enddatetime=%s,pickuplocationid=%s,dropofflocationid=%s,pickuplocationtext=%s,dropofflocationtext=%s,rentaltype=%s,status=%s,numberofpeople=%s,transport=%s,menuid=%s,
                                         catering=%s,cateringnotes=%s,drinknotes=%s,othernotes=%s,adminnotes=%s,basecost=%s,menucost=%s,additionalcost=%s,
                                         deposit=%s,depositpaid=%s,balancepaid=%s,invoicenumber=%s,depositdate=%s,paid=%s,paiddate=%s,
                                         takenbyid=%s, netcost=%s, vatrate=%s,
                                         emailskipper = %s, emailmate=%s, emailcaterer = %s, emailclient = %s, emailowner = %s,
                                          datelastmod=%s where id=%s",
                     GetSQLValueString($this->customerid,'text','',''),
                     GetSQLValueString($this->rentalobjid,'text','',''),
                     GetSQLValueString($this->rateid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->startdatetime),'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->enddatetime),'text','',''),
                     GetSQLValueString($this->pickuplocationid,'text','',''),
                     GetSQLValueString($this->dropofflocationid,'text','',''),
                                        GetSQLValueString($this->pickuplocationtext,'text','',''),
                                        GetSQLValueString($this->dropofflocationtext,'text','',''),
                     GetSQLValueString($this->rentaltype,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->numberofpeople,'text','',''),
                     GetSQLValueString($this->transport,'text','',''),
                     GetSQLValueString($this->menuid,'text','',''),
                     GetSQLValueString($this->catering,'text','',''),
                     GetSQLValueString($this->cateringnotes,'text','',''),
                     GetSQLValueString($this->drinknotes,'text','',''),
                     GetSQLValueString($this->othernotes,'text','',''),
                     GetSQLValueString($this->adminnotes,'text','',''),					 
                     GetSQLValueString($this->basecost,'text','',''),
                     GetSQLValueString($this->menucost,'text','',''),
                     GetSQLValueString($this->additionalcost,'text','',''),
                     GetSQLValueString($this->deposit,'text','',''),
                     GetSQLValueString($this->depositpaid,'text','',''),
                     GetSQLValueString($this->balancepaid,'text','',''),					 
                     GetSQLValueString($this->invoicenumber,'text','',''),					 
                     GetSQLValueString($dater->phptosqldate($this->depositdate),'date','',''),
                     GetSQLValueString($this->paid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->paiddate),'date','',''),
                     GetSQLValueString($this->takenbyid,'text','',''),
                     GetSQLValueString($this->netcost,'text','',''),
                     GetSQLValueString($this->vatrate,'text','',''),
                     GetSQLValueString($this->emailskipper,'text','',''),
                     GetSQLValueString($this->emailmate,'text','',''),
                     GetSQLValueString($this->emailcaterer,'text','',''),
                     GetSQLValueString($this->emailclient,'text','',''),
                     GetSQLValueString($this->emailowner,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $this->id );


                    $userupd = dosql($query);


                  foreach ($this->contacts as $key=>$value) {

                       if ($value->status=='active' ) {

							// Check to see if the record exists
								$query = sprintf("SELECT ".TBL_PREFIX."bookingcontactlink.*".
                                            " FROM ".TBL_PREFIX."bookingcontactlink  ".
                                            "WHERE ".TBL_PREFIX."bookingcontactlink.categoryid='%s' and ".
													 TBL_PREFIX."bookingcontactlink.bookingid='%s' and ".
											         TBL_PREFIX."bookingcontactlink.contactid='%s'"  ,$value->categoryid, $this->id, $value->contactid );
                                $rec2 = dosql($query);
								
                                if  (mysql_num_rows($rec2)>0) {
									$row2= mysql_fetch_assoc($rec2);
									$value->id = $row2['id'];
								}

                                if ($value->id>-1) {
                                 //update
                                  $query = sprintf("UPDATE ".TBL_PREFIX."bookingcontactlink set categoryid=%s,contactid=%s where id=%s   ",
                                         GetSQLValueString($value->categoryid,'text','',''),
                                         GetSQLValueString($value->contactid,'text','',''),
                                         GetSQLValueString($value->id,'text','','')
                                          );

                                } else {
                                 //insert
									if (!empty($value->categoryid)) {
										$query = sprintf("INSERT INTO ".TBL_PREFIX."bookingcontactlink ( bookingid,categoryid,contactid   )
                                                 VALUES(%s,%s,%s)",
                                                 GetSQLValueString($value->bookingid,'text','',''),
                                                 GetSQLValueString($value->categoryid,'text','',''),
                                                 GetSQLValueString($value->contactid,'text','','')
                                           );
									}
                                }
                       }


                       if ($value->status=='deleted' ) {
                          $query = sprintf("DELETE FROM ".TBL_PREFIX."bookingcontactlink where id=%s",
                         $value->id
                         );    

                       }

                       $contactlinkrec = dosql($query);

          }


        }

        function deletebookingbyid() {

               $query = sprintf("DELETE FROM ".TBL_PREFIX."bookings where
                        shopid=%s and id=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->id,'text','','')
                        );

                $bookingdel = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."bookingcontactlink where
                         id=%s  ",
                         GetSQLValueString($this->id,'text','','')
                        );

                $bookingdel = dosql($query);

                return $bookingdel;
        }



 }


class func {
        var $id=0;
        var $shopid=0;
        var $bookingid="";
        var $crewboardingtime="";
        var $crewboardinglocationid="";
        var $crewboardinglocationtext="";
        var $crewsailtime="";
        var $crewremarks="";

        var $cateringdeliverytime="";
        var $cateringdeliverylocationid="";
        var $cateringdeliverylocationtext="";

        var $guestprinciple = "";
        var $guestevent = "";
        var $guestboardingtime = "";
        var $guestboardinglocationid = "";
        var $guestboardinglocationtext = "";
        var $guestboardingsailtime = "";
        var $guestboardingremarks="";

        var $gueststopover1time = "";
        var $gueststopover1locationid = "";
        var $gueststopover1locationtext = "";
        var $gueststopover1sailtime = "";
        var $gueststopover1remarks="";

        var $gueststopover2time = "";
        var $gueststopover2locationid = "";
        var $gueststopover2locationtext = "";
        var $gueststopover2sailtime = "";
        var $gueststopover2remarks="";

        var $guestdisembarktime = "";
        var $guestdisembarklocationid = "";
        var $guestdisembarklocationtext = "";
        var $guestdisembarksailtime = "";
        var $guestdisembarkremarks="";

        var $crewdisembarktime="";
        var $crewdisembarklocationid ="";
        var $crewdisembarklocationtext ="";
        var $crewdisembarkremarks="";

        var $welcomedrink = "";
        var $drinkremarks = "";
        var $meal1remarks="";
        var $meal2remarks="";

        var $notes="";

        var $contacts = array();

        var $wholastmode="";
        var $datelastmod="";
        var $datecerated="";





        function createfunctable($connection) {

                $query="drop table if exists ".TBL_PREFIX."func; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."func`  (
                  `id` int(11) NOT NULL auto_increment,
                  `shopid` int(11) ,
                  `bookingid` int(11),
                  `crewboardingtime` datetime default '0000-00-00 00:00:00',
                  `crewboardinglocationid` int(11) default '0',
                  `crewboardinglocationtext` varchar(250) default '' ,
                  `crewsailtime` datetime default '0000-00-00 00:00:00',
                  `crewremarks` text ,
                  `cateringdeliverytime` datetime default '0000-00-00 00:00:00',
                  `cateringdeliverylocationid`  int(11) default '0',
                  `cateringdeliverylocationtext` varchar(250) default '' ,
                  `guestprinciple` int(11) default '0' ,
                  `guestevent` varchar(30) default '',
                  `guestboardingtime` datetime default '0000-00-00 00:00:00',
                  `guestboardinglocationid`  int(11) default '0',
                  `guestboardinglocationtext` varchar(250) default '' ,
                  `guestboardingsailtime` datetime default '0000-00-00 00:00:00',
                  `guestboardingremarks` text ,
                  `gueststopover1time` datetime default '0000-00-00 00:00:00',
                  `gueststopover1locationid`  int(11)default 0 ,
                  `gueststopover1locationtext` varchar(250) default '' ,
                  `gueststopover1sailtime` datetime default '0000-00-00 00:00:00',
                  `gueststopover1remarks` text,
                  `gueststopover2time` datetime default '0000-00-00 00:00:00',
                  `gueststopover2locationid`  int(11) default 0 ,
                  `gueststopover2locationtext` varchar(250) default '' ,
                  `gueststopover2sailtime` datetime default '0000-00-00 00:00:00',
                  `gueststopover2remarks` text,
                  `guestdisembarktime` datetime default '0000-00-00 00:00:00',
                  `guestdisembarklocationid`  int(11) default 0 ,
                  `guestdisembarklocationtext` varchar(250) default '' ,
                  `guestdisembarksailtime` datetime default '0000-00-00 00:00:00',
                  `guestdisembarkremarks` text,
                  `crewdisembarktime` datetime default '0000-00-00 00:00:00',
                  `crewdisembarklocationid`  int(11) default 0 ,
                  `crewdisembarklocationtext` varchar(250) default '' ,
                  `crewdisembarkremarks` text,
                  `welcomedrink` boolean ,
                  `drinkremarks` text,
                  `meal1remarks` text,
                  `meal2remarks` text,
                  `notes` text,
                  `datecreated` datetime default '0000-00-00 00:00:00',
                  `datelastmod` datetime default '0000-00-00 00:00:00',
                  `wholastmod` varchar(20) default '',
                  PRIMARY KEY  (`id`)
                ) ";

                $bookingrec = dosql($query);


                $query = "".
                "CREATE TABLE `".TBL_PREFIX."jobcontactlink`  (
                  `id` int(11) NOT NULL auto_increment,
                  `jobid` int(11) NOT NULL default 0,
                  `contactid` int(11) NOT NULL default 0,
                  `categoryid` int(11) NOT NULL default 0 ,
                  `boardingtime` datetime default '0000-00-00 00:00:00',
                  `boardinglocation` int(11) ,
                  `menuoptionid` int(11) ,
                  PRIMARY KEY  (`id` )
                ) ";
                $contactrec = dosql($query);

        }



        function func () {
                $id=0;
                $shopid=0;
                $this->bookingid="";

                $this->skipperid;
                $this->mateid;
                $this->catererid;

                $this->crewboardingtime="";
                $this->crewboardinglocationid=0;
                $this->crewboardinglocationtext="";
                $this->crewsailtime="";
                $this->crewremarks="";

                $this->cateringdeliverytime="";
                $this->cateringdeliverylocationid=0;
                $this->cateringdeliverylocationtext="";

                $this->guestprinciple ="";
                $this->guestevent="";
                $this->guestboardingtime = "";
                $this->guestboardinglocationid = 0;
                $this->guestboardinglocationtext="";
                $this->guestboardingsailtime = "";
                $this->guestboardingremarks="";

                $this->gueststopover1time = "";
                $this->gueststopover1locationid = 0;
                $this->gueststopover1locationtext = "";
                $this->gueststopover1sailtime = "";
                $this->gueststopover1remarks="";

                $this->gueststopover2time = "";
                $this->gueststopover2locationid = 0;
                $this->gueststopover2locationtext = "";
                $this->gueststopover2sailtime = "";
                $this->gueststopover2remarks="";

                $this->guestdisembarktime = "";
                $this->guestdisembarklocationid = 0;
                $this->guestdisembarklocationtext = "";
                $this->guestdisembarksailtime = "";
                $this->guestdisembarkremarks="";

                $this->crewdisembarktime="";
                $this->crewdisembarklocationid=0;
                $this->crewdisembarklocationtext="";

                $this->crewdisembarkremarks="";

                $this->welcomedrink = "";
                $this->drinkremarks = "";
                $this->meal1remarks="";
                $this->meal2remarks="";

                $this->notes="";
                $this->wholastmod="";
                $this->contacts = array();

        }

        function getfunc($id) {
            global $shopid;
            $dater= new dater();

            $query = sprintf("SELECT ".TBL_PREFIX."func.*".
                                            " FROM ".TBL_PREFIX."func  ".
                                            "WHERE ".TBL_PREFIX."func.bookingid='%s'  " ,$id);


            $rec = dosql($query);

            $row = mysql_fetch_assoc($rec);

            if (mysql_num_rows($rec)>0) {

                $this->id=$row['id'];
                $this->shopid=$row['shopid'];
                $this->bookingid=$row['bookingid'];
                $this->crewboardingtime = substr($dater->sqltophpdatetime($row['crewboardingtime'],2),11,5);
//                $this->crewboardingtime = $dater->sqltophpdatetime($row['crewboardingtime'],2);
                $this->crewboardinglocationid=$row['crewboardinglocationid'];
                $this->crewboardinglocationtext=$row['crewboardinglocationtext'];

                $this->crewsailtime=substr($dater->sqltophpdatetime($row['crewsailtime'],2),11,5);
                $this->crewremarks=$row['crewremarks'];

                $this->cateringdeliverytime=substr($dater->sqltophpdatetime($row['cateringdeliverytime'],2),11,5);
                $this->cateringdeliverylocationid=$row['cateringdeliverylocationid'];
                $this->cateringdeliverylocationtext=$row['cateringdeliverylocationtext'];

                $this->guestprinciple = $row['guestprinciple'];
                $this->guestevent = $row['guestevent'];
                $this->guestboardingtime = substr($dater->sqltophpdatetime($row['guestboardingtime'],2),11,5);
                $this->guestboardinglocationid = $row['guestboardinglocationid'];
                $this->guestboardinglocationtext = $row['guestboardinglocationtext'];

                $this->guestboardingsailtime = substr($dater->sqltophpdatetime($row['guestboardingsailtime'],2),11,5);
                $this->guestboardingremarks=$row['guestboardingremarks'];

                $this->gueststopover1time = substr($dater->sqltophpdatetime($row['gueststopover1time'],2),11,5);
                $this->gueststopover1locationid = $row['gueststopover1locationid'];
                $this->gueststopover1locationtext = $row['gueststopover1locationtext'];
                $this->gueststopover1sailtime = substr($dater->sqltophpdatetime($row['gueststopover1sailtime'],2),11,5);
                $this->gueststopover1remarks=$row['gueststopover1remarks'];

                $this->gueststopover2time = substr($dater->sqltophpdatetime($row['gueststopover2time'],2),11,5);
                $this->gueststopover2locationid = $row['gueststopover2locationid'];
                $this->gueststopover2locationtext = $row['gueststopover2locationtext'];

                $this->gueststopover2sailtime = substr($dater->sqltophpdatetime($row['gueststopover2sailtime'],2),11,5);
                $this->gueststopover2remarks=$row['gueststopover2remarks'];

                $this->guestdisembarktime = substr($dater->sqltophpdatetime($row['guestdisembarktime'],2),11,5);
                $this->guestdisembarklocationid = $row['guestdisembarklocationid'];
                $this->guestdisembarklocationtext = $row['guestdisembarklocationtext'];


                $this->guestdisembarksailtime = substr($dater->sqltophpdatetime($row['guestdisembarksailtime'],2),11,5);
                $this->guestdisembarkremarks=$row['guestdisembarkremarks'];

                $this->crewdisembarktime=substr($dater->sqltophpdatetime($row['crewdisembarktime'],2),11,5);
                $this->crewdisembarklocationid=$row['crewdisembarklocationid'];
                $this->crewdisembarklocationtext=$row['crewdisembarklocationtext'];

                $this->crewdisembarkremarks=$row['crewdisembarkremarks'];

                $this->welcomedrink = $row['welcomedrink'];
                $this->drinkremarks = $row['drinkremarks'];
                $this->meal1remarks=$row['meal1remarks'];
                $this->meal2remarks=$row['meal2remarks'];

                $this->notes=$row['notes'];



            }




            return mysql_num_rows($rec);
        }





        function getallfuncs($status,$type,$offset,$count,$order ) {

                global $connection;

                $query = "SELECT ".TBL_PREFIX."func.* "  .
                                            " FROM ".TBL_PREFIX."func  ";

                $and=false;

                if ($status>" ") {
                   $query=$query.' where status="'.$status.'"';
                   $and=true;
                }

                if ($type>" ") {
                   if ($and) {
                           $query=$query.' and (type REGEXP \''.$type.'\'>0)';
                   } else {
                           $query=$query.' where (type REGEXP \''.$type.'\'>0)';
                   }
                }

                $query = $query.$order;

                if ($offset>-1) {
                   $query = $query.' LIMIT '.$offset.','.$count;
                }

            $jobs = dosql($query);

            return $jobs;
        }





        function insertfunc() {

                $dater= new dater;
                global $connection;


               $query = sprintf("INSERT INTO ".TBL_PREFIX."func ( shopid, bookingid,
               crewboardingtime, crewboardinglocationid , crewboardinglocationtext, crewsailtime, crewremarks,
               cateringdeliverytime, cateringdeliverylocationid,cateringdeliverylocationtext,  guestprinciple, guestevent,
               guestboardingtime, guestboardinglocationid, guestboardinglocationtext,guestboardingsailtime,guestboardingremarks,
               gueststopover1time, gueststopover1locationid,gueststopover1locationtext, gueststopover1sailtime, gueststopover1remarks,
               gueststopover2time, gueststopover2locationid,gueststopover2locationtext, gueststopover2sailtime, gueststopover2remarks,
               guestdisembarktime, guestdisembarklocationid,guestdisembarklocationtext, guestdisembarksailtime, guestdisembarkremarks,
               crewdisembarktime,crewdisembarklocationid,crewdisembarklocationtext,crewdisembarkremarks ,
               welcomedrink, drinkremarks, meal1remarks, meal2remarks,
               notes,datecreated, datelastmod, wholastmod  )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->bookingid,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->crewboardingtime),'date','',''),
                     GetSQLValueString($this->crewboardinglocationid,'text','',''),
                     GetSQLValueString($this->crewboardinglocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->crewsailtime),'date','',''),
                     GetSQLValueString($this->crewremarks,'text','',''),

                     GetSQLValueString($this->cateringdeliverytime,'text','',''),
                     GetSQLValueString($this->cateringdeliverylocationid,'text','',''),
                     GetSQLValueString($this->cateringdeliverylocationtext,'text','',''),

                     GetSQLValueString($this->guestprinciple,'text','',''),
                     GetSQLValueString($this->guestevent,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestboardingtime),'date','',''),
                     GetSQLValueString($this->guestboardinglocationid,'text','',''),
                     GetSQLValueString($this->guestboardinglocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestboardingsailtime),'date','',''),
                     GetSQLValueString($this->guestboardingremarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->gueststopover1time),'date','',''),
                     GetSQLValueString($this->gueststopover1locationid,'text','',''),
                     GetSQLValueString($this->gueststopover1locationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->gueststopover1sailtime),'date','',''),
                     GetSQLValueString($this->gueststopover1remarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->gueststopover2time),'date','',''),
                     GetSQLValueString($this->gueststopover2locationid,'text','',''),
                     GetSQLValueString($this->gueststopover2locationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->gueststopover2sailtime),'date','',''),
                     GetSQLValueString($this->gueststopover2remarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->guestdisembarktime),'date','',''),
                     GetSQLValueString($this->guestdisembarklocationid,'text','',''),
                     GetSQLValueString($this->guestdisembarklocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestdisembarksailtime),'date','',''),
                     GetSQLValueString($this->guestdisembarkremarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->crewdisembarktime),'date','',''),
                     GetSQLValueString($this->crewdisembarklocationid,'text','',''),
                     GetSQLValueString($this->crewdisembarklocationtext,'text','',''),
                     GetSQLValueString($this->crewdisembarkremarks,'text','',''),

                     GetSQLValueString($this->welcomedrink,'text','',''),
                     GetSQLValueString($this->drinkremarks,'text','',''),
                     GetSQLValueString($this->meal1remarks,'text','',''),
                     GetSQLValueString($this->meal2remarks,'text','',''),
                     GetSQLValueString($this->notes,'text','',''),

                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );

           $userins = dosql($query);

          $funcid=mysql_insert_id( $connection );



          return $funcid;

        }


        function updatefuncbybookingid($id) {
                global $encrypted;
                global $connection;

                    $dater= new dater;


                    $query = sprintf("UPDATE ".TBL_PREFIX."func set crewboardingtime=%s, crewboardinglocationid=%s ,crewboardinglocationtext=%s , crewsailtime=%s, crewremarks=%s,
               cateringdeliverytime=%s, cateringdeliverylocationid=%s,cateringdeliverylocationtext=%s,  guestprinciple=%s, guestevent=%s,
               guestboardingtime=%s, guestboardinglocationid=%s,guestboardinglocationtext=%s,guestboardingsailtime=%s,guestboardingremarks=%s,
               gueststopover1time=%s, gueststopover1locationid=%s,gueststopover1locationtext=%s, gueststopover1sailtime=%s, gueststopover1remarks=%s,
               gueststopover2time=%s, gueststopover2locationid=%s,gueststopover2locationtext=%s, gueststopover2sailtime=%s, gueststopover2remarks=%s,
               guestdisembarktime=%s, guestdisembarklocationid=%s,guestdisembarklocationtext=%s, guestdisembarksailtime=%s, guestdisembarkremarks=%s,
               crewdisembarktime=%s,crewdisembarklocationid=%s,crewdisembarklocationtext=%s,crewdisembarkremarks=%s ,
               welcomedrink=%s, drinkremarks=%s, meal1remarks=%s, meal2remarks=%s,
               notes=%s, wholastmod=%s , datelastmod=%s
                                          where bookingid=%s",

                     GetSQLValueString($dater->phptosqldate($this->crewboardingtime),'date','',''),
                     GetSQLValueString($this->crewboardinglocationid,'text','',''),
                     GetSQLValueString($this->crewboardinglocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->crewsailtime),'date','',''),
                     GetSQLValueString($this->crewremarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->cateringdeliverytime),'date','',''),
                     GetSQLValueString($this->cateringdeliverylocationid,'text','',''),
                     GetSQLValueString($this->cateringdeliverylocationtext,'text','',''),

                     GetSQLValueString($this->guestprinciple,'text','',''),
                     GetSQLValueString($this->guestevent,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestboardingtime),'date','',''),
                     GetSQLValueString($this->guestboardinglocationid,'text','',''),
                     GetSQLValueString($this->guestboardinglocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestboardingsailtime),'date','',''),
                     GetSQLValueString($this->guestboardingremarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->gueststopover1time),'date','',''),
                     GetSQLValueString($this->gueststopover1locationid,'text','',''),
                     GetSQLValueString($this->gueststopover1locationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->gueststopover1sailtime),'date','',''),
                     GetSQLValueString($this->gueststopover1remarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->gueststopover2time),'date','',''),
                     GetSQLValueString($this->gueststopover2locationid,'text','',''),
                     GetSQLValueString($this->gueststopover2locationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->gueststopover2sailtime),'date','',''),
                     GetSQLValueString($this->gueststopover2remarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->guestdisembarktime),'date','',''),
                     GetSQLValueString($this->guestdisembarklocationid,'text','',''),
                     GetSQLValueString($this->guestdisembarklocationtext,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->guestdisembarksailtime),'date','',''),
                     GetSQLValueString($this->guestdisembarkremarks,'text','',''),

                     GetSQLValueString($dater->phptosqldate($this->crewdisembarktime),'date','',''),
                     GetSQLValueString($this->crewdisembarklocationid,'text','',''),
                     GetSQLValueString($this->crewdisembarklocationtext,'text','',''),
                     GetSQLValueString($this->crewdisembarkremarks,'text','',''),

                     GetSQLValueString($this->welcomedrink,'text','',''),
                     GetSQLValueString($this->drinkremarks,'text','',''),
                     GetSQLValueString($this->meal1remarks,'text','',''),
                     GetSQLValueString($this->meal2remarks,'text','',''),
                     GetSQLValueString($this->notes,'text','',''),
                     GetSQLValueString("who" ,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $id );

                    $userupd = dosql($query);



        }

        function deletefuncbyid() {

               $query = sprintf("DELETE FROM ".TBL_PREFIX."func where
                        shopid=%s and id=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->id,'text','','')
                        );

                $bookingdel = dosql($query);

                $query = sprintf("DELETE FROM ".TBL_PREFIX."jobcontactlink where
                         id=%s  ",
                         GetSQLValueString($this->id,'text','','')
                        );

                $funcdel = dosql($query);

                return $funcdel;
        }





 }


?>
