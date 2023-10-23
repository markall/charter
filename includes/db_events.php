<?php


class eventdate {

      var $id;
      var $shopid;
      var $contactid;
      var $eventid;
      var $start;
      var $end;
      var $displaytext;
      var $location;
      var $postcode;
      var $cost;
      var $orderstr;
      var $availability;
      var $booked;
      var $status;
      var $options = array();
      var $datecreated;
      var $datelastmod;
      var $wholastmod;

      function geteventdatebyid($id) {
        global $connection;
        $query = "SELECT * FROM ".TBL_PREFIX."eventdates where id=".$id;
        $rec = dosql($query);
        if (mysql_num_rows($rec)>0) {
          $row=mysql_fetch_assoc($rec);
          $this->id=$id;
          $this->shopid=$row['shopid'];
          $this->contactid=$row['contactid'];
          $this->eventid=$row['eventid'];
          $this->start=$row['start'];
          $this->end=$row['end'];
          $this->displaytext=$row['displaytext'];
          $this->location=$row['location'];
          $this->postcode=$row['postcode'];
          $this->cost=$row['cost'];
          $this->availability=$row['availability'];
          $this->booked=$row['booked'];
          $this->status=$row['status'];
          $this->orderstr=$row['orderstr'];
        }

        return mysql_num_rows($rec);
      }

      function getalleventdates( $eventid, $datefrom ) {

        $dater=new dater();

        $where = "";
        global $connection;

        $where = $where." where eventid='".trim($eventid)."'";

        if ($datefrom>'') {
           $where = $where." and ".TBL_PREFIX."eventdates.start>='".$dater->phptosqldate($datefrom)."'";
        }

        $query = "SELECT * FROM ".TBL_PREFIX."eventdates ".$where." order by orderstr,start, contactid, eventid  ";

        $recs = dosql($query);

        return $recs;

      }

        function inserteventdate() {
               global $connection;
               $dater= new dater;

               $query = sprintf("INSERT INTO ".TBL_PREFIX."eventdates ( shopid, contactid, eventid, start, end,
                                                          displaytext,location,postcode,cost,availability,booked,status,orderstr,datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->eventid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->start),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->end),'date','',''),
                     GetSQLValueString($this->displaytext,'text','',''),
                     GetSQLValueString($this->location,'text','',''),
                     GetSQLValueString($this->postcode,'text','',''),
                     GetSQLValueString($this->cost,'text','',''),
                     GetSQLValueString($this->availability,'text','',''),
                     GetSQLValueString($this->booked,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->orderstr,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );

               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function updateeventdate($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("UPDATE ".TBL_PREFIX."eventdates set start=%s, end=%s, displaytext=%s, location=%s,postcode=%s,cost=%s, availability=%s ,
                                        booked=%s,status=%s, orderstr=%s,datelastmod=%s, wholastmod=%s  where id=%s",
                          GetSQLValueString($dater->phptosqldate($this->start),'date','',''),
                          GetSQLValueString($dater->phptosqldate($this->end),'date','',''),
                          GetSQLValueString($this->displaytext,'text','',''),
                          GetSQLValueString($this->location,'text','',''),
                          GetSQLValueString($this->postcode,'text','',''),
                          GetSQLValueString($this->cost,'text','',''),
                          GetSQLValueString($this->availability,'text','',''),
                          GetSQLValueString($this->booked,'text','',''),
                          GetSQLValueString($this->status,'text','',''),
                          GetSQLValueString($this->orderstr,'text','',''),
                          GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                          GetSQLValueString($this->wholastmod,'text','',''),
                           $id );


                          $shopupd = dosql($query);
        }

        function deleteeventdate($id) {

                global $connection;
                $query = sprintf("DELETE FROM ".TBL_PREFIX."eventdates where id=%s", $id );
                $shopupd = dosql($query);

        }

        function deleteeventdatebyeventid($id) {
                global $connection;
                $query = sprintf("DELETE FROM ".TBL_PREFIX."eventdates where eventid=%s", $id );
                $shopupd = dosql($query);
        }

}




class event {

        var $id="";
        var $shopid="";
        var $contactid="";
        var $title="";
        var $description="";
        var $status="";
        var $target="";
        var $acceptpayment="";
        var $categories=array();
        var $eventdates=array();


        function createeventstable($connection) {


                $query = "".
                "CREATE TABLE `".TBL_PREFIX."events`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) default '0' NOT NULL,".
                "  `contactid` int(11) NOT NULL ,".
                "  `title` varchar(100) default '', ".
                "  `location` varchar(100) default '', ".
                "  `postcode` varchar(10) default '', ".
                "  `description` text default '',".
                "  `target` text default '',".
                "  `status` varchar(10) default '', ".
                "  `acceptpayment` tinyint, ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."eventdates`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) default '0' NOT NULL,".
                "  `contactid` int(11) NOT NULL ,".
                "  `eventid` int(11) NOT NULL ,".
                "  `start` datetime default '0000-00-00 00:00:00',".
                "  `end` datetime default '0000-00-00 00:00:00',".
                "  `displaytext` varchar(100) ,".
                "  `location` varchar(100) default '', ".
                "  `postcode` varchar(10) default '', ".
                "  `cost` float(11,2),".
                "  `availability` int(11),".
                "  `booked` int(11),".
                "  `status` varchar(10) default '', ".
                "  `orderstr` varchar(10) default '', ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id` )".
                ") ";

                $userrec = dosql($query);


        }





        function geteventbyid($id) {
                global $connection;

                $dater=new dater();

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."events  WHERE id='%s'",$id);

            $rec = dosql($query);
            if (mysql_num_rows($rec)>0) {

                 $row = mysql_fetch_assoc($rec);

                 $this->id = $row['id'];
                 $this->shopid = $row['shopid'];
                 $this->contactid = $row['contactid'];
                 $this->title=$row['title'];
                 $this->description=$row['description'];
                 $this->location=$row['location'];
                 $this->postcode=$row['postcode'];
                 $this->target=$row['target'];
                 $this->status=$row['status'];
                 $this->acceptpayment=$row['acceptpayment'];
                 $this->datecreated = $dater->sqltophpdate($row['datecreated']);
                 $this->datelastmod = $dater->sqltophpdate($row['datelastmod']);
                 $this->wholastmod = $dater->sqltophpdate($row['wholastmod']);

                 unset($this->eventdates);

                 $recs=eventdate::getalleventdates( $this->id, '' );

                 while ($row=mysql_fetch_assoc($recs) ) {
                     $eventdate = new eventdate();
                     $eventdate->id=$row['id'];
                     $eventdate->shopid=$row['shopid'];
                     $eventdate->contactid=$row['contactid'];
                     $eventdate->eventid=$row['eventid'];
                     $eventdate->start=$row['start'];
                     $eventdate->end=$row['end'];
                     $eventdate->displaytext=$row['displaytext'];
                     $eventdate->location=$row['location'];
                     $eventdate->postcode=$row['postcode'];
                     $eventdate->cost=$row['cost'];
                     $eventdate->availability=$row['availability'];
                     $eventdate->booked=$row['booked'];
                     $eventdate->status=$row['status'];
                     $eventdate->datecreated=$row['datecreated'];
                     $eventdate->datelastmod=$row['datelastmod'];
                     $eventdate->wholastmod=$row['wholastmod'];

                     $this->eventdates[]=$eventdate;


                 }

            }

            return mysql_num_rows($rec);
        }


        function geteventbydateid($id) {
                global $connection;

                $dater=new dater();

                $where="WHERE ".TBL_PREFIX."eventdates.id='".$id."' ";

            $query = "SELECT ".TBL_PREFIX."events.id as id,".TBL_PREFIX."eventdates.id as eventdateid, title,".TBL_PREFIX."eventdates.location as location,".TBL_PREFIX."eventdates.postcode as postcode,".TBL_PREFIX."eventdates.description,".TBL_PREFIX."events.location as eventlocation,".TBL_PREFIX."events.postcode as eventpostcode,target,start,end,displaytext,cost,availability  FROM ".TBL_PREFIX."events  ";
            $query = $query."RIGHT JOIN ".TBL_PREFIX."eventdates on ".TBL_PREFIX."events.id=".TBL_PREFIX."eventdates.eventid ";
            $query = $query.$where." order by ".TBL_PREFIX."eventdates.start,".TBL_PREFIX."events.title desc";

            $rec = dosql($query);

            return mysql_num_rows($rec);
        }

        function getallevents(  $status,$datefrom , $src ) {

                global $encrypted;
                global $connection;
                global $dater;
                global $session;

                $dater = new dater();

            $where="";

            if ($status>' ') {
                    $where="WHERE ".TBL_PREFIX."events.status='".$status."' ";
            }

            if ($datefrom>'') {
               if ($where>'') {
                  $where=$where." and ".TBL_PREFIX."eventdates.start>='".$dater->phptosqldate($datefrom)."'";
               } else {
                  $where=" where ".TBL_PREFIX."eventdates.start>='".$dater->phptosqldate($datefrom)."'" ;
               }
            }

            if (!$session->active) {
                        if ($where<' ') {
                            $where = ' where ';
                        } else {
                            $where = $where.' and ';
                        }
                        $where = $where.TBL_PREFIX."events.target<>'members' ";
            }

            if ($src>'') {
                 if ($where>'') {
                         $where=$where."and ".TBL_PREFIX."eventdates.orderstr='".$src."' ";
                 } else {
                         $where=$where."WHERE ".TBL_PREFIX."eventdates.orderstr='".$src."' ";
                 }
            }

            $query = "SELECT ".TBL_PREFIX."events.id as id,".
                               TBL_PREFIX."events.contactid as contactid,".
                               TBL_PREFIX."eventdates.id as eventdateid ,title,".
                               TBL_PREFIX."events.location as location,".
                               TBL_PREFIX."eventdates.postcode as postcode,".
                               TBL_PREFIX."events.location as eventlocation,description,target,start,end,displaytext,cost,availability  FROM ".TBL_PREFIX."events  ";
            $query = $query."RIGHT JOIN ".TBL_PREFIX."eventdates on ".TBL_PREFIX."events.id=".TBL_PREFIX."eventdates.eventid ";
            $query = $query.$where." order by ".TBL_PREFIX."eventdates.orderstr,".TBL_PREFIX."eventdates.start,".TBL_PREFIX."events.title desc";

            $rec = dosql($query);


            return $rec;
        }



        function geteventsbycontact( $contactid ) {
                global $encrypted;
                global $connection;
                global $dater;

                $dater = new dater();

            $where="WHERE contactid='".$contactid."' ";

            $query = "SELECT * FROM ".TBL_PREFIX."events  ".$where." order by title ";

            $rec = dosql($query);
            if (mysql_num_rows($rec)>0) {
                 $row = mysql_fetch_assoc($rec);
                 $this->id = $row['id'];

                 $this->shopid = $row['shopid'];
                 $this->contactid = $row['contactid'];
                 $this->title=$row['title'];
                 $this->description=$row['description'];
                 $this->location=$row['location'];
                 $this->postcode=$row['postcode'];
                 $this->target=$row['target'];
                 $this->status=$row['status'];
                 $this->acceptpayment=$row['acceptpayment'];
                 $this->datecreated = $dater->sqltophpdate($row['datecreated']);
                 $this->datelastmod = $dater->sqltophpdate($row['datelastmod']);
                 $this->wholastmod = $dater->sqltophpdate($row['wholastmod']);

                 mysql_data_seek ( $rec, 0 );
             }


            return $rec;

        }

        function deleteeventbycontactid() {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."events  WHERE contactid='%s'",$contactid);
               $user = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."eventdates  WHERE contactid='%s'",$contactid);
               $user = dosql($query);
               return $rec;
        }

        function deleteeventbyid($id) {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."events  WHERE id='%s'",$id);
               $user = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."eventdates  WHERE eventid='%s'",$id);
               $user = dosql($query);
               return $rec;
        }


        function insertevent() {
               global $connection;
               $dater= new dater;

               $this->wholastmod = $_SERVER['REMOTE_ADDR'];


               $query = sprintf("INSERT INTO ".TBL_PREFIX."events ( shopid, contactid, title, location,postcode , description ,target,status,acceptpayment,
                                                                        datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->location,'text','',''),
                     GetSQLValueString($this->postcode,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->target,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->acceptpayment,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );

               $ins = dosql($query);
               $this->id=mysql_insert_id();

                if (isset($this->eventdates)) {
                       foreach ($this->eventdates as $key=>$value) {
                        $value->eventid=$this->id;
                        $value->inserteventdate();
                       }
                }

               return $this->id;
        }

        function updateevent($id) {

                global $connection;

                $dater= new dater;
                $this->wholastmod = $_SERVER['REMOTE_ADDR'];

                $query = sprintf("UPDATE ".TBL_PREFIX."events set title=%s, location=%s, postcode=%s, description=%s, target=%s, status=%s, acceptpayment=%s, datelastmod=%s, wholastmod=%s
                                        where id=%s",
                          GetSQLValueString($this->title,'text','',''),
                          GetSQLValueString($this->location,'text','',''),
                          GetSQLValueString($this->postcode,'text','',''),
                          GetSQLValueString($this->description,'text','',''),
                          GetSQLValueString($this->target,'text','',''),
                          GetSQLValueString($this->status,'text','',''),
                          GetSQLValueString($this->acceptpayment,'text','',''),
                          GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                          GetSQLValueString($this->wholastmod,'text','',''),
                           $id );


                          $upd =dosql($query);

                          if (isset($this->eventdates)) {
                                  $eventdate = new eventdate();

                                  $recs=$eventdate->getalleventdates( $this->id,'' ) ;

                                  foreach ($this->eventdates as $key=>$value) {
                                        if ($value->id>0) {
                                                 $value->updateeventdate($value->id);
                                        } else {
                                                $value->eventid=$this->id;
                                                $value->inserteventdate();
                                         }
                                  }

                                  while ($row=mysql_fetch_assoc($recs)) {
                                        $delete=true;
                                        foreach ($this->eventdates as $key=>$value) {
                                           if ($row['id']==$value->id) {
                                              $delete=false;
                                           }
                                        }

                                        if ($delete) {
                                          $eventdate->deleteeventdate($row['id']);
                                        }
                                  }
                        }
//                               eventdate::deleteeventdatebyeventid($this->id); // delete all as its easier



        }

        function deleteevent($id) {
                global $connection;

                foreach ($this->eventdates as $key=>$value) {
                        $value->deleteeventdate($value->id);
                }

                $query = sprintf("DELETE FROM ".TBL_PREFIX."events where id=%s", $id );
                $upd = dosql($query);

        }

 }
?>
