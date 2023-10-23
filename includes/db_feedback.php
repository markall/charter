<?php

class feedback {

        var $id="";
        var $shopid="";
        var $contactid=0;
        var $reviewerid=0;
        var $productid=0;
        var $orderid=0;
        var $rating=0;
        var $title="";
        var $comment="";
        var $name="";
        var $publish="";
        var $ip="";
        var $datemodified="";
        var $datecreatd="";



        function createfeedbacktable($connection) {

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."feedback`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) default '0' NOT NULL,".
                "  `contactid` int(11) NOT NULL ,".
                "  `reviewerid` int(11) NOT NULL ,".
                "  `productid` int(11) NOT NULL ,".
                "  `orderid` int(11) NOT NULL ,".
                "  `rating` int(11) default 0, ".
                "  `title` varchar(50) default '', ".
                "  `comment` text , ".
                "  `name` varchar(90) default '', ".
                "  `publish` boolean default 0, ".
                "  `ip` varchar(50) default '', ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $userrec = dosql($query);


        }





        function getfeedbackbyid($id) {
                global $encrypted;
                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."feedback  WHERE id='%s'",$id);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->id = $row_user['id'];
                 $this->shopid = $row_user['shopid'];
                 $this->contactid = $row_user['contactid'];
                 $this->reviewerid = $row_user['reviewerid'];
                 $this->productid = $row_user['productid'];
                 $this->orderid = $row_user['orderid'];
                 $this->rating = $row_user['rating'];
                 $this->title = $row_user['title'];
                 $this->comment = $row_user['comment'];
                 $this->name=$row_user['name'];
                 $this->publish=$row_user['publish'];
                 $this->ip = $row_user['ip'];
                 $this->datecreated = $dater->sqltophpdatetime( $row_user['datecreated'] );
                 $this->datemodified = $dater->sqltophpdatetime( $row_user['datemodified'] );

            }

            return mysql_num_rows($userrec);
        }


        function getfeedbackbychoice($contactid,$productid, $publish ) {
                global $encrypted;
                global $connection;

            $query = "SELECT * FROM ".TBL_PREFIX."feedback ";
            $whereset=0;
            if ($contactid>0) {
              $query = $query.sprintf("where contactid=%s",$contactid);
              $whereset=1;
            }
            if ($productid>1) {
               if (!$whereset) {
                 $query = $query." where ";
                 $whereset=1;
               } else {
                 $query=$query." and ";
               }
              $query = $query.sprintf("productid=%s",$contactid);
            }

            if ($publish) {
               if (!$whereset) {
                 $query = $query." where ";
               } else {
                 $query=$query." and ";
               }
              $query = $query.sprintf("publish=%s",$publish);
            }
                
            $result = dosql($query);


            return $result;
        }



        function insertfeedback() {
               global $connection;
               $dater= new dater;
               $query = sprintf("INSERT INTO ".TBL_PREFIX."feedback ( shopid, contactid, reviewerid, productid, orderid, rating , title ,
                                           comment, name, publish , ip, datelastmod,datecreated )
               VALUES ( %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->reviewerid,'text','',''),
                     GetSQLValueString($this->productid,'text','',''),
                     GetSQLValueString($this->orderid,'text','',''),
                     GetSQLValueString($this->rating,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->comment,'text','',''),
                     GetSQLValueString($this->name,'text','',''),
                     GetSQLValueString($this->publish,'text','',''),
                     GetSQLValueString($this->ip,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );

               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function updatefeedbackbyid($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("UPDATE ".TBL_PREFIX."feedback set contactid=%s,reviewerid=%s,productid=%s,orderid=%s,rating=%s,title=%s,comment=%s,name=%s,publish=%s,ip=%s, datelastmod=%s
                                        where id=%s",
                                             GetSQLValueString($this->contactid,'text','',''),
                                             GetSQLValueString($this->reviewerid,'text','',''),
                                             GetSQLValueString($this->productid,'text','',''),
                                             GetSQLValueString($this->orderid,'text','',''),
                                             GetSQLValueString($this->rating,'text','',''),
                                             GetSQLValueString($this->title,'text','',''),
                                             GetSQLValueString($this->comment,'text','',''),
                                             GetSQLValueString($this->name,'text','',''),
                                             GetSQLValueString($this->publish,'text','',''),
                                             GetSQLValueString($this->ip,'text','',''),
                                             GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                          $id );
                          $shopupd = dosql($query);
        }

        function deletefeedbackbyid($id,$whichid) {
                global $connection;
                $query = sprintf("DELETE FROM ".TBL_PREFIX."feedback where $whichid=%s",
                          $id );
                          $shopupd = dosql($query);
        }


 }
?>
