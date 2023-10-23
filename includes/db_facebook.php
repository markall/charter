<?php

class mm_facebook {

        var $id="";
        var $viewerid="";
        var $profileid="";
        var $ip="";
        var $datecreatd="";



        function createfeedbacktable($connection) {

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."facebook`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `viewerid` int(11) default '0' ,".
                "  `profileid` int(11) default '0' ,".
                "  `ip` varchar(50) default '', ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateupdated` datetime default '0000-00-00 00:00:00',".
                "  PRIMARY KEY  (`id`)".
                ") ";

                $userrec = dosql($query);


        }

        function getfacebookbyid($id) {
                global $encrypted;
                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."facebook  WHERE id='%s'",$id);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->id = $row_user['id'];
                 $this->viewerid = $row_user['shopid'];
                 $this->profileid = $row_user['contactid'];
                 $this->ip = $row_user['ip'];
                 $this->datecreated = $dater->sqltophpdatetime( $row_user['datecreated'] );

            }

            return mysql_num_rows($userrec);
        }

        function getfacebychoice($viewerid,$profileid ) {
                global $encrypted;
                global $connection;

            $query = "SELECT * FROM ".TBL_PREFIX."facebook ";
            $whereset=0;
            if ($viewerid>1) {
              $query = $query.sprintf("where viewerid=%s",$contactid);
              $whereset=1;
            }
            if ($profileid>1) {
               if (!$whereset) {
                 $query = $query." where ";
                 $whereset=1;
               } else {
                 $query=$query." and ";
               }
              $query = $query.sprintf("profileid=%s",$contactid);
            }


            $result = dosql($query);


            return $result;
        }



        function insertfacebook() {
               global $connection;
               $dater= new dater;
               $query = sprintf("INSERT INTO ".TBL_PREFIX."facebook ( viewerid, profileid , ip ,datecreated )
               VALUES ( %s, %s, %s, %s )",
                     GetSQLValueString($this->viewerid,'text','',''),
                     GetSQLValueString($this->profileid,'text','',''),
                     GetSQLValueString($this->ip,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );

               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function deletefacebookbyid($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("DELETE FROM ".TBL_PREFIX."facebook where viewerid=%s",
                                             GetSQLValueString($this->viewerid,'text','','')
                           );
                          $shopdel = dosql($query);
        }



 }
?>
