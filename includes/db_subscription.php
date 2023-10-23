<?php

class subscription {

        var $id="";
        var $shopid="";
        var $contactid=0;
        var $start="";
        var $expires="";
        var $paid="";
        var $amount="";
        var $tax="";
        var $productid="";
        var $expired="0";


        function createsubscriptiontable($connection) {

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."subscriptions`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) default '0' NOT NULL,".
                "  `contactid` int(11) NOT NULL ,".
                "  `start` datetime default '0000-00-00 00:00:00',".
                "  `expires` datetime default '0000-00-00 00:00:00',".
                "  `productid` int(11) default 0 ,".
                "  `paid` float(8,2) default 0 ,".
                "  `amount` float(8,2) default 0 ,".
                "  `tax` float(8,2) default 0, ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $userrec = dosql($query);


        }





        function getsubscriptionbyid($id) {
                global $encrypted;
                global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."subscriptions  WHERE id='%s'",$id);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                 $this->id = $row_user['id'];
                 $this->shopid = $row_user['shopid'];
                 $this->contactid = $row_user['contactid'];
                 $this->start=$row_user['start'];
                 $this->expires=$row_user['expires'];
                 $this->productid=$row_user['productid'];
                 $this->paid=$row_user['paid'];
                 $this->amount=$row_user['amount'];
                 $this->tax=$row_user['tax'];



            }

            return mysql_num_rows($userrec);
        }

        function getsubscriptionsbycontact($contactid,$productid) {
                global $encrypted;
                global $connection;
                global $dater;

                $dater = new dater();

            $where="WHERE contactid='%s' ";
            if ($productid>0) {
              $where = $where." and productid='%s' ";
            }
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."subscriptions  ".$where." order by expires desc",$contactid,$productid);
            $userrec =dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                 $this->id = $row_user['id'];
                 $this->shopid = $row_user['shopid'];
                 $this->contactid = $row_user['contactid'];
                 $this->start= $dater->sqltophpdate($row_user['start']);
                 $this->expires=$dater->sqltophpdate($row_user['expires']);
                 $this->productitem=$row_user['productid'];
                 $this->paid=$row_user['paid'];
                 $this->amount=$row_user['amount'];
                 $this->tax=$row_user['tax'];

                 $d=substr($dater->sqltophpdate($row_user['expires']),0,2);
                 $m=substr($dater->sqltophpdate($row_user['expires']),3,2);
                 $y=substr($dater->sqltophpdate($row_user['expires']),7,4);
                 $r= date("d-m-Y", mktime(0, 0, 0, $m, $d, $y+1));

                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                 $dc2= date("Ymd");    // today

                 $this->expired=0;

                 if (round($dc1,0)<round($dc2,0)) {
                       $this->expired='1';
                 }

                 mysql_data_seek ( $userrec, 0 );
             }


            return $userrec;

        }

        function deletesubscriptionbycontactid( $contactid ) {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."subscriptions  WHERE contactid='%s'",$contactid);
               $user = dosql($query);
               return $rec;

        }

        function deletesubscriptionbyid( $id ) {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."subscriptions  WHERE id='%s'",$id);
               $user = dosql($query);
               return $rec;

        }

        function insertsubscription() {
               global $connection;
               $dater= new dater;
               $query = sprintf("INSERT INTO ".TBL_PREFIX."subscriptions ( shopid, contactid, start, expires , productid , paid, amount, tax , datelastmod,datecreated )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->start),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->expires),'date','',''),
                     GetSQLValueString($this->productid,'text','',''),
                     GetSQLValueString($this->paid,'text','',''),
                     GetSQLValueString($this->amount,'text','',''),
                     GetSQLValueString($this->tax,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function updatesubscriptionbyid($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("UPDATE ".TBL_PREFIX."subscriptions set start=%s,expires=%s,productid=%s,paid=%s,amount=%s,tax=%s,datelastmod=%s
                                        where id=%s",
                          GetSQLValueString($dater->phptosqldate(start),'date','',''),
                          GetSQLValueString($dater->phptosqldate(expires),'date','',''),
                          GetSQLValueString($this->paid,'text','',''),
                          GetSQLValueString($this->amount,'text','',''),
                          GetSQLValueString($this->tax,'text','',''),
                          GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''), $id );
                          $shopupd = dosql($query);
        }

 }
?>
