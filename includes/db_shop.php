<?php

class shop {

        var $id="";
        var $code="";
        var $name="";
        var $title="";
        var $contactid=0;


        function createshoptable($connection) {

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."shops`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `code` varchar(40) default '',".
                "  `name` varchar(140) default '',".
                "  `title` varchar(140) default '',".
                "  `contactid` integer default -1,".
                "  `type` varchar(20) default '',".
                "  `status` varchar(20) default '',".
                "  `description` text,".
                "  `image` blob NOT NULL,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`),".
                "  KEY `shopcode` (`code`)".
                ") ";

                $userrec = dosql($query);


        }





        function getshopbyid($id) {
                global $encrypted;
                global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."shops  WHERE ".TBL_PREFIX."shops.id='%s'",$id);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                 $this->id = $row_user['id'];
                 $this->code = $row_user['code'];
                 $this->name = $row_user['name'];
                 $this->title=$row_user['title'];
                 $this->contactid=$row_user['contactid'];

            }

            return mysql_num_rows($userrec);
        }


        function getallshops($status,$type) {
                global $connection;
                $query = "SELECT * FROM ".TBL_PREFIX."shops";
                if ($status>" ") {
                   $query=$query.' where status="$status"';
                }
                if ($type>" ") {
                   $query=$query.' where type="$type"';
                }
            $shops = dosql($query);

            return $shops;

        }


        function insertshop() {
               global $connection;
               $dater= new dater;
               $query = sprintf("INSERT INTO ".TBL_PREFIX."shops ( code, name, title, datelastmod,datecreated )
               VALUES ( %s, %s, %s, %s, %s )",
                     GetSQLValueString($this->code,'text','',''),
                     GetSQLValueString($this->name,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
               $shopins = dosql($query);
               $this->id=mysql_insert_id();

               return $this->id;
        }

        function updateshopbyid($id) {

                global $connection;

                $dater= new dater;

                $query = sprintf("UPDATE ".TBL_PREFIX."shops set code=%s,name=%s,title=%s,datelastmod=%s
                                        where id=%s",
                          GetSQLValueString($this->code,'text','',''),
                          GetSQLValueString($this->name,'text','',''),
                          GetSQLValueString($this->title,'text','',''),
                          GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''), $id );

                         $shopupd = dosql($query);
        }

 }
?>
