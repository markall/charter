<?php

class offer {

        var $id="";
        var $shopid="";
        var $contactid=0;
        var $start="";
        var $expires="";
        var $description="";
        var $vouchercode="";
        var $title="";
        var $status="";
        var $productid="";
        var $expired="0";
        var $impressions="";
        var $target="";
        var $media=array();
        var $categories=array();


        function createofferstable($connection) {

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."offers`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) default '0' NOT NULL,".
                "  `contactid` int(11) NOT NULL ,".
                "  `start` datetime default '0000-00-00 00:00:00',".
                "  `expires` datetime default '0000-00-00 00:00:00',".
                "  `description` text default '',".
                "  `vouchercode` varchar(50) default '', ".
                "  `title` varchar(100) default '', ".
                "  `status` varchar(10) default '', ".
                "  `target` varchar(10) default '', ".
                "  `productid` int(11) default 0 ,".
                "  `impressions` int(11) default 0 ,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $userrec = dosql($query);


        }





        function getofferbyid($id) {
                global $connection;

                $dater=new dater();

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."offers  WHERE id='%s'",$id);

            $rec = dosql($query);
            if (mysql_num_rows($rec)>0) {

                 $row = mysql_fetch_assoc($rec);

                 $this->id = $row['id'];

                 $this->shopid = $row['shopid'];
                 $this->contactid = $row['contactid'];
                 $this->start= $dater->sqltophpdate($row['start']);
                 $this->expires=$dater->sqltophpdate($row['expires']);
                 $this->description=$row['description'];
                 $this->vouchercode=$row['vouchercode'];
                 $this->title=$row['title'];
                 $this->status=$row['status'];
                 $this->target=$row['target'];
                 $this->productitem=$row['productid'];
                 $this->impressions=$row['impressions'];

                 $d=substr($dater->sqltophpdate($row['expires']),0,2);
                 $m=substr($dater->sqltophpdate($row['expires']),3,2);
                 $y=substr($dater->sqltophpdate($row['expires']),7,4);

                 $r= date("d-m-Y", mktime(0, 0, 0, $m, $d, $y+1));
                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                 $dc2= date("Ymd");    // today

                 $this->expired=0;

                 if (round($dc1,0)<round($dc2,0)) {
                       $this->expired='1';
                 }



            }

            return mysql_num_rows($rec);
        }

        function getoffers($contactid,$productid,$datefrom,$status, $signedin, $balance ) {
                global $encrypted;
                global $connection;
                global $dater;

                $dater = new dater();

            $where="";
            if ($contactid>0) {
                    $where="WHERE contactid='".$contactid."' ";
                    if ($productid>0) {
                      $where = $where." and productid='".$productid."' ";
                    }
            } else {
                    if ($productid>0) {
                      $where = $where." where productid='".$productid."' ";
                    }
            }

            if ($datefrom>'') {
                if ($where<' ') {
                    $where = ' where ';
                } else {
                    $where = $where.' and ';
                }
                $where = $where.' '.TBL_PREFIX."offers.expires>='".$dater->phptosqldate($datefrom)."'";
            }

            if ($status>'') {
                if ($where<' ') {
                    $where = ' where ';
                } else {
                    $where = $where.' and ';
                }
                $where = $where.' '.TBL_PREFIX."offers.status>='".$status."'";
            }

                if ($balance) {
                    $query = "select min(impressions) as impressions from ".TBL_PREFIX."offers ".$where." order by expires desc";
                    $rec = dosql($query);

                    if (mysql_num_rows($rec)>0) {
                         $row = mysql_fetch_assoc($rec);
                         $i = $row['impressions'];
                         if ($i>0) {
                                 if ($where<' ') {
                                   $where = ' where ';
                                 } else {
                                   $where = $where.' and ';
                                 }
                                 $i=$i+1;
                                 $where = $where.TBL_PREFIX."offers.impressions<=".$i;
                         }
                    }
                }

                if (!$signedin) {
                        if ($where<' ') {
                            $where = ' where ';
                        } else {
                            $where = $where.' and ';
                        }
                        $where = $where.TBL_PREFIX."offers.target<>'members' ";

                }


            $query = sprintf("SELECT * FROM ".TBL_PREFIX."offers  ".$where." order by expires desc",$contactid,$productid);
             
            $rec = dosql($query);

            if (mysql_num_rows($rec)>0) {
                 $row = mysql_fetch_assoc($rec);
                 $this->id = $row['id'];
                 $this->shopid = $row['shopid'];
                 $this->contactid = $row['contactid'];
                 $this->start= $dater->sqltophpdate($row['start']);
                 $this->expires=$dater->sqltophpdate($row['expires']);
                 $this->description=$row['description'];
                 $this->vouchercode=$row['vouchercode'];
                 $this->title=$row['title'];
                 $this->status=$row['status'];
                 $this->target=$row['target'];
                 $this->productitem=$row['productid'];
                 $this->impressions=$row['impressions'];

                 if ($row['expires']>' ') {
                         $d=substr($dater->sqltophpdate($row['expires']),0,2);
                         $m=substr($dater->sqltophpdate($row['expires']),3,2);
                         $y=substr($dater->sqltophpdate($row['expires']),7,4);

                         $r= date("d-m-Y", mktime(0, 0, 0, $m, $d, $y+1));

                         $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                         $dc2= date("Ymd");    // today

                         $this->expired=0;

                         if (round($dc1,0)<round($dc2,0)) {
                               $this->expired='1';
                         }
                 }

                 mysql_data_seek ( $rec, 0 );
             }


            return $rec;

        }

        function deleteofferbycontactid($contactid) {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."offers  WHERE contactid='%s'",$contactid);
               $user = dosql($query);
               return $rec;
        }

        function deleteofferbyid($id) {
               global $connection;
               $query = sprintf("DELETE FROM ".TBL_PREFIX."offers  WHERE id='%s'",$id);

               $user = dosql($query);
               return $rec;
        }

        function insertoffer() {
               global $connection;
               $dater= new dater;

               $query ="SELECT MIN(impressions) as minimp from ".TBL_PREFIX."offers";
               $res = dosql($query);
               if ($row=mysql_fetch_assoc($res)) {
                 $impressions = $row['minimp'];
               } else {
                 $impression=1;
               }

               $query = sprintf("INSERT INTO ".TBL_PREFIX."offers ( shopid, contactid, start, expires , description , vouchercode,
                                                                         title ,status, target, productid  ,impressions, datelastmod,datecreated )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->start),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->expires),'date','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->vouchercode,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($this->target,'text','',''),
                     GetSQLValueString($this->productid,'text','',''),
                     GetSQLValueString($impressions ,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function updateofferbyid($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("UPDATE ".TBL_PREFIX."offers set start=%s,expires=%s,description=%s,vouchercode=%s,title=%s,
                                                            status=%s,target=%s,productid=%s,impressions=%s,datelastmod=%s
                                        where id=%s",
                          GetSQLValueString($dater->phptosqldate($this->start),'date','',''),
                          GetSQLValueString($dater->phptosqldate($this->expires),'date','',''),
                          GetSQLValueString($this->description,'text','',''),
                          GetSQLValueString($this->vouchercode,'text','',''),
                          GetSQLValueString($this->title,'text','',''),
                          GetSQLValueString($this->status,'text','',''),
                          GetSQLValueString($this->target,'text','',''),
                          GetSQLValueString($this->productid,'text','',''),
                          GetSQLValueString($this->impressions,'text','',''),
                          GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''), $id );
                          $shopupd = dosql($query);
        }

 }
?>
