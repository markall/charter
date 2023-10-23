<?php

class rate {
        var $shopid=0;
        var $id=0;
        var $title = "";
        var $baseprice=0;
        var $priceperunit=0;
        var $baseunit=0;
        var $ratetype="";
        var $vatcode = 0;
        var $includesvat=0;


        var $wholastmod="";


        function createratetable($connection) {
                $query="drop table if exists ".TBL_PREFIX."rates; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."rates`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `title` varchar(40) default '',".
                "  `baseprice` decimal(10,2) default 0,".
                "  `baseunit` int(11) default 0,".
                "  `priceperunit` decimal(10,2) default 0,".
                "  `vatcode` int(11) default 0,".
                "  `includesvat` tinyint default -1,".
                "  `ratetype` varchar(10) ,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $raterec = dosql($query);

                $query="drop table if exists ".TBL_PREFIX."raterentalobjlink; ";
                $userrec = dosql($query);
                "CREATE TABLE `".TBL_PREFIX."rentalobjratelink`  (".
                "  `rentalobjid` int(11) NOT NULL ,".
                "  `rateid` int(11) NOT NULL ,".
                               "  PRIMARY KEY  (`rentalobjid`,`rateid` )".
                ") ";
                $raterec = dosql($query);

				$query = "".
				"CREATE TABLE `".TBL_PREFIX."contactrentals` (".
				"  `shopid` int(11) NOT NULL, ".
				"  `contactid` int(11) NOT NULL, ".
				"  `rentalid` int(11) NOT NULL, ".
				" PRIMARY KEY ( `shopid`, `contactid`, `rentalid` )".
				" ) ";

				$categoryrec = dosql($query);

        }
         function getratebyid($id) {

                global $connection;
                $dater= new dater;

            $query = sprintf("SELECT ".TBL_PREFIX."rates.*".
                                            " FROM ".TBL_PREFIX."rates  ".
                                            "WHERE ".TBL_PREFIX."rates.id='%s'  " ,$id);

            $rec = dosql($query);

            $row = mysql_fetch_assoc($rec);

            if (mysql_num_rows($rec)>0) {
                 $this->shopid = $row['shopid'];
                 $this->id = $row['id'];
                 $this->title=$row['ratetitle'];
                 $this->baseprice=$row['baseprice'];
                 $this->baseunit=$row['baseunit'];
                 $this->priceperunit=$row['priceperunit'];
                 $this->vatcode=$row['vatcode'];
                 $this->includesvat=$row['includesvat'];
                 $this->ratetype=$row['ratetype'];
            }

            return mysql_num_rows($rec);
        }

        function getallrates() {

                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT ".TBL_PREFIX."rates.*".
                                            " FROM ".TBL_PREFIX."rates  order by ratetype, ratetitle"
                                           );

            $locrecs = dosql($query);


            return $locrecs;
        }

        function insertrate() {
               global $connection;
               $dater= new dater;

               $query = sprintf("INSERT INTO ".TBL_PREFIX."rates ( shopid , ratetitle ,
               baseprice,baseunit,priceperunit,vatcode,includesvat,ratetype,datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->baseprice,'text','',''),
                     GetSQLValueString($this->baseunit,'text','',''),
                     GetSQLValueString($this->priceperunit,'text','',''),
                     GetSQLValueString($this->vatcode,'text','',''),
                     GetSQLValueString($this->includesvat,'text','',''),
                     GetSQLValueString($this->ratetype,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );

           $userins = dosql($query);

          $rateid=mysql_insert_id( $connection );


          return $rateid;
        }

        function updateratebyid($id) {

                global $connection;

                    $dater= new dater;

                    $query = sprintf("UPDATE ".TBL_PREFIX."rates set ratetitle=%s,
                                                                        baseprice=%s,
                                                                        baseunit=%s,
                                                                        priceperunit=%s,
                                                                        vatcode=%s,
                                                                        includesvat=%s,
                                                                        ratetype=%s,
                                                                        datelastmod=%s where id=%s",
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->baseprice,'text','',''),
                     GetSQLValueString($this->baseunit,'text','',''),
                     GetSQLValueString($this->priceperunit,'text','',''),
                     GetSQLValueString($this->vatcode,'text','',''),
                     GetSQLValueString($this->includesvat,'text','',''),
                     GetSQLValueString($this->ratetype,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $id );

                    $userupd = dosql($query);

        }



        function deleteratebyid($id) {

               $query = sprintf("DELETE FROM ".TBL_PREFIX."rates where
                        shopid=%s and id=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($id,'text','','')
                        );

                $locationdel = dosql($query);

                return $locationdel;
        }

}




class location {

        var $shopid=0;
        var $id=-1;
        var $location="";
        var $pickup=true;
        var $dropoff=true;
        var $status="active";
        function createlocationtable($connection) {

                $query="drop table if exists ".TBL_PREFIX."location; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."location`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `location` varchar(40) default '',".
                "  `pickup` boolean default false,".
                "  `dropoff` boolean default false,".
                "  `status` boolean default false,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $raterec = dosql($query);

        }


        function location() {
                 $this->shopid="0";
                 $this->id="-1";
                 $this->location="";
                 $this->pickup=true;
                 $this->dropoff=true;
                 $this->status=true;

        }

        function getlocationbyid($id) {

                global $connection;

                $dater= new dater;


            $query = sprintf("SELECT ".TBL_PREFIX."location.*".
                                            " FROM ".TBL_PREFIX."location  ".
                                            "WHERE ".TBL_PREFIX."location.id='%s'  " ,$id);

            $rec = dosql($query);

            $row = mysql_fetch_assoc($rec);

            if (mysql_num_rows($rec)>0) {
                 $this->shopid = $row['shopid'];
                 $this->id = $row['id'];
                 $this->location=$row['location'];
                 $this->pickup=$row['pickup'];
                 $this->dropoff=$row['dropoff'];
                 $this->status=$row['status'];

            }

            return mysql_num_rows($rec);
        }

        function getalllocations() {

                global $connection;

                $dater= new dater;


            $query = sprintf("SELECT ".TBL_PREFIX."location.*".
                                            " FROM ".TBL_PREFIX."location  "
                                           );

            $locrecs = dosql($query);


            return $locrecs;
        }

        function insertlocation() {
               global $connection;
               $dater= new dater;

               $query = sprintf("INSERT INTO ".TBL_PREFIX."location ( shopid , location ,
               pickup,dropoff,status,datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->location,'text','',''),
                     GetSQLValueString($this->pickup,'text','',''),
                     GetSQLValueString($this->dropoff,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );



           $userins = dosql($query);

          $locationid=mysql_insert_id( $connection );


          return $locationid;

        }

        function updatelocationbyid($id) {

                global $connection;

                    $dater= new dater;

                    $query = sprintf("UPDATE ".TBL_PREFIX."location set location=%s,
                                                                        pickup=%s,
                                                                        dropoff=%s,
                                                                        status=%s,
                                                                        datelastmod=%s where id=%s",
                     GetSQLValueString($this->location,'text','',''),
                     GetSQLValueString($this->pickup,'text','',''),
                     GetSQLValueString($this->dropoff,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $id );


                    $userupd = dosql($query);




        }




        function deletelocationbyid() {

               $query = sprintf("DELETE FROM ".TBL_PREFIX."location where
                        shopid=%s and id=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->id,'text','','')
                        );

                $locationdel = dosql($query);

                return $locationdel;
        }


}

class rentalobj {
        var $id=0;
        var $shopid=0;
        var $connection="";
        var $title="";
        var $shortdesc="";
        var $maxpersons="0";
        var $type="";
        var $description="";
        var $status="";
        var $licensed=false;
        var $owner=0;
        var $skipper=0;
        var $crew=0;
        var $caterer=0;
        var $cleaner=0;
        var $color='';
        var $drinks=0;
        var $wholastmod="";

        var $locations=array();
        var $detail= array();


        function createrentalobjtable($connection) {

                $query="drop table if exists ".TBL_PREFIX."rentalobj; ";
                $userrec = dosql($query);

                $query="drop table if exists ".TBL_PREFIX."rentalobjlocationlink; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."rentalobj`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `title` varchar(255) default '',".
                "  `shortdesc` varchar(255) default '',".
                "  `rentaltype` varchar(1) default '',".
                "  `maxpersons` varchar(140) default '',".
                "  `type` varchar(20) default '',".
                "  `status` varchar(20) default '',".
                "  `description` text,".
                "  `licensed` boolean,".
                "  `owner` int(11),".
                "  `skipper` int(11),".
                "  `crew` int(11),".
                "  `caterer` int(11),".
                "  `drinks` int(11),".
                "  `cleaner` int(11), ".
                "  `color` varchar(7),".
                "  `image` blob ,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";
                   
                $boatrec = dosql($query);

                                $query = "".
                "CREATE TABLE `".TBL_PREFIX."rentalobjlocationlink`  (".
                "  `rentalobjid` int(11) NOT NULL ,".
                "  `locationid` int(11) NOT NULL ,".
                               "  PRIMARY KEY  (`rentalobjid`,`locationid`)".
                ") ";
                  $boatrec = dosql($query);
        }



        function rentalobj() {
                 $this->type="boat";
                 $this->status="pending";
                 $this->crypt=new crypt();

        }

        function getrentalobj($id) {

                global $connection;

                $dater= new dater;


            $query = sprintf("SELECT ".TBL_PREFIX."rentalobj.*".
                                            " FROM ".TBL_PREFIX."rentalobj  ".
                                            "WHERE ".TBL_PREFIX."rentalobj.id='%s'  " ,$id);

            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->shopid = $row_user['shopid'];
                 $this->id = $row_user['id'];
                 $this->title=$row_user['title'];
                 $this->shortdesc=$row_user['shortdesc'];
                 $this->type=$row_user['type'];
                 $this->maxpersons=$row_user['maxpersons'];
                 $this->type=$row_user['type'];
                 $this->status=$row_user['status'];
                 $this->description=$row_user['description'];
                 $this->licensed=$row_user['licensed'];
                 $this->owner=$row_user['owner'];
                 $this->skipper=$row_user['skipper'];
                 $this->crew=$row_user['crew'];
                 $this->caterer=$row_user['caterer'];
                 $this->drinks=$row_user['drinks'];
                 $this->color=$row_user['color'];

                $this->getlocationbyrentalobj();

                $this->getratebyrentalobj();

            }

            return mysql_num_rows($userrec);
        }





        function getallrentalobjs($status,$type,$offset,$count,$order ) {

                global $connection;

            $query = "SELECT ".TBL_PREFIX."rentalobj.* "  .
                                            " FROM ".TBL_PREFIX."rentalobj  ";

//                $query = "SELECT * FROM ".TBL_PREFIX."contacts";
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
                    
            $rentalobjs = dosql($query);
            
            return $rentalobjs;
        }



        function addtolocation($locationid) {
              $query = sprintf("INSERT INTO ".TBL_PREFIX."rentalobjlocationlink ( rentalobjid, locationid ) VALUES ( %s, %s ) ",
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($locationid,'text','','')

                );

                $contactcatins = dosql($query);
                return ;

        }

        function addtorate($rateid) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."rentalobjratelink ( rentalobjid, rateid ) VALUES ( %s, %s ) ",
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($rateid,'text','','')
                );

                $contactcatins = dosql($query);

                return ;
        }

        function checkexists($rateid) {
                $r=false;
               $query = sprintf("SELECT * FROM ".TBL_PREFIX."rentalobjratelink WHERE rentalobjid=%s AND rateid=%s ",
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($rateid,'text','','')
                );

                $rec = dosql($query);
                if (mysql_num_rows($rec)>0) {
                  $r=true;
                }

                return $r;
        }

        function deletefromlocation($locationid) {
              $query = sprintf("DELETE FROM  ".TBL_PREFIX."rentalobjlocationlink
                WHERE rentalobjid=%s AND locationid = %s  ",
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($locationid,'text','','')

                );

                $contactcatins = dosql($query);
                return ;

        }

        function deletefromrate($rateid) {
              $q="";
              if ($rateid>-1) {
                $q= " AND rateid = %s  ";
              }
              $query = sprintf("DELETE FROM  ".TBL_PREFIX."rentalobjratelink
                WHERE rentalobjid=%s".$q,
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($rateid,'text','','')

                );

                $delrecs = dosql($query);
                return ;

        }

        function getlocationbyrentalobj() {

                $query=sprintf("SELECT * from ".TBL_PREFIX."rentalobjlocationlink left join ".TBL_PREFIX."location on "
                    .TBL_PREFIX."location.id=".TBL_PREFIX."rentalobjlocationlink.locationid ".
                    " where rentalobjid=%s ",
                   GetSQLValueString($this->id,'text','','')
                );

                $rentallocations =  dosql($query);

                unset ($this->locations);

                while ($row=mysql_fetch_assoc($rentallocations)) {
                  $this->locations[$row['location']]=$row['locationid'];
                }


                return $rentallocations;
        }

        function getratebyrentalobj() {

                $query=sprintf("SELECT * from ".TBL_PREFIX."rentalobjratelink left join ".TBL_PREFIX."rates on "
                    .TBL_PREFIX."rates.id=".TBL_PREFIX."rentalobjratelink.rateid ".
                    " where rentalobjid=%s ",
                   GetSQLValueString($this->id,'text','','')
                );

               $rentalrates =  dosql($query);

                unset ($this->rates);

                while ($row=mysql_fetch_assoc($rentalrates)) {
                  if (strlen($row['ratetitle'])>0) {
                      $this->rates[$row['rateid']]=$row['ratetitle'];

                  }
                }


                return $rentalrates;
        }


        function addtocategory($categoryid) {
           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."rentalcategories (
                shopid, contactid, categoryid )
                VALUES ( %s, %s, %s ) ",
                 GetSQLValueString($this->shopid ,'text','',''),
                 GetSQLValueString($this->userid,'text','',''),
                 GetSQLValueString($categoryid,'text','','')
                );


                $contactcatins = dosql($query);

                return mysql_insert_id();

        }

        function deletefromcategory($categoryid) {
           $dater= new dater;

           if ($categoryid>0) {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."rentalcategories where
                        shopid=%s and contactid=%s and categoryid=%s ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','',''),
                         GetSQLValueString($categoryid,'text','','')
                        );
           } else {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."rentalcategories where
                        shopid=%s and contactid=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','','')
                        );

           }


                $rentalcatdel = dosql($query);

                return $rentalcatdel;

        }

        function getcategoriesbyrental() {
                global $connection;

                $query=sprintf("SELECT * from ".TBL_PREFIX."rentaltcategories where
                   shopid=%s and contactid=%s ",
                   GetSQLValueString($this->shopid ,'text','',''),
                   GetSQLValueString($this->userid,'text','','')
                );



                $rentalcategories =  dosql($query);

                unset ($this->categories);

                while ($row=mysql_fetch_assoc($rentalcategories)) {
                  $this->categories[]=$row['categoryid'];
                }

                return $rentalcategories;


        }



        function insertrentalobj() {
                global $encrypted;
                global $connection;

                $dater= new dater;

               $query = sprintf("INSERT INTO ".TBL_PREFIX."rentalobj ( shopid ,title, shortdesc,
                maxpersons, type, status ,description, licensed, owner, skipper, crew, caterer, drinks, color,
                datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s, %s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->shortdesc,'text','',''),
                     GetSQLValueString($this->maxpersons,'text','',''),
                     GetSQLValueString($this->type,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->licensed,'text','',''),
                     GetSQLValueString($this->owner,'text','',''),
                     GetSQLValueString($this->skipper,'text','',''),
                     GetSQLValueString($this->crew,'text','',''),
                     GetSQLValueString($this->caterer,'text','',''),
                     GetSQLValueString($this->drinks,'text','',''),
                     GetSQLValueString($this->color,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );



           $userins = dosql($query);

          $rentalobjid=mysql_insert_id( $connection );


          return $rentalobjid;
        }

        function updaterentalobjbyid($id) {
                global $encrypted;
                global $connection;

                    $dater= new dater;

                    $query = sprintf("UPDATE ".TBL_PREFIX."rentalobj set title=%s, shortdesc=%s,
                                         maxpersons=%s,type=%s,status=%s,description=%s,
                                         licensed=%s, owner=%s, skipper=%s, crew=%s, caterer=%s, drinks=%s,color=%s,
                                          datelastmod=%s where id=%s",
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->shortdesc,'text','',''),
                     GetSQLValueString($this->maxpersons,'text','',''),
                     GetSQLValueString($this->type,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->licensed,'text','',''),
                     GetSQLValueString($this->owner,'text','',''),
                     GetSQLValueString($this->skipper,'text','',''),
                     GetSQLValueString($this->crew,'text','',''),
                     GetSQLValueString($this->caterer,'text','',''),
                     GetSQLValueString($this->drinks,'text','',''),
                     GetSQLValueString($this->color,'text','',''),    
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $id );


                    $userupd = dosql($query);




        }

        function deleterentalobj() {

               $query = sprintf("DELETE FROM ".TBL_PREFIX."rentalobjlocationlink where
                        rentalobjid=%s  ",
                         GetSQLValueString($this->id,'text','','')
                        );
                $r = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."rentalobjratelink where
                        rentalobjid=%s  ",
                         GetSQLValueString($this->id,'text','','')
                        );

               $r = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."rentalobj where
                         id=%s  ",
                         GetSQLValueString($this->id,'text','','')
                        );

                $rentalobjtdel = dosql($query);

                return;
        }



 }


?>
