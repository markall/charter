<?php
class contacthistory {
        var $connection="";
        var $id="";
        var $contactid="";
        var $tablename="";
        var $fieldname="";
        var $fieldtype="";
        var $newvalue="";
        var $oldvalue="";
        var $datechanged="";
        var $whochanged="";

        function createcontacthistorytable($connection) {

              //  $query="drop table if exists ".TBL_PREFIX."contacthistory; ";
              //  $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."contacthistory`  (".
                "  `id`  int(11) NOT NULL auto_increment,".
                "  `contactid` int(11) default '0',".
                "  `tablename` varchar(50), ".
                "  `fieldname` varchar(50), ".
                "  `fieldtype` int(11) , ".
                "  `newvalue`  varchar(400), ".
                "  `oldvalue` varchar(400), ".
                "  `effectivefrom` datetime, ".
                "  `datechanged` datetime, ".
                "  `whochanged` varchar(20), ".
                "  PRIMARY KEY  (`id`)   ".
                ") ";
              
                $userrec = dosql($query);

//                $query = ""."CREATE INDEX IDX_historyid ON contacthistory (id); ";
//                $userrec = dosql($query);


        }

        function init() {
                 $this->crypt=new crypt();
        }

        function getcontacthistory($contactid, $tablename, $fieldname, $newvalue ) {
                global $encrypted;
                global $connection;

                $dater = new dater;
                $query = sprintf("SELECT * FROM ".TBL_PREFIX."contacthistory  WHERE ".TBL_PREFIX."contacthistory.contactid='%s' ",$contactid);
                if ($tablename!="" && $fieldname!="") {
                   $query = $query.sprintf(" and tablename='%s' and fieldname='%s'",$tablename,$fieldname );
                }
                if ($newvalue!="" ) {
                   $query = $query.sprintf(" and newvalue='%s'", $newvalue );
                }

                $query=$query." order by effectivefrom desc";
             
                $contacthistory = dosql($query);

                return $contacthistory;
        }

        function insertcontacthistory() {
                global $encrypted;
                global $connection;

                   $dater= new dater;

//           if ($encrypted==false) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."contacthistory ( contactid, tablename, fieldname, newvalue, oldvalue, effectivefrom, datechanged, whochanged )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s )",
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->tablename,'text','',''),
                     GetSQLValueString($this->fieldname,'text','',''),
                     GetSQLValueString($this->newvalue,'text','',''),
                     GetSQLValueString($this->oldvalue,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->effectivefrom),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->whochanged,'text','','')
                     );
 //         } else {
//               $query = sprintf("INSERT INTO contacthistory ( contactid, tablename, fieldname, newvalue, oldvalue, datechanged, whochanged )
//               VALUES ( %s, %s, %s, %s, %s, %s, %s )",
//                     GetSQLValueString($this->contactid,'text','',''),
//                     GetSQLValueString($this->tablename,'text','',''),
//                     GetSQLValueString($this->fieldname,'text','',''),
//                     GetSQLValueString($this->crypt->encrypt($this->newvalue),'text','',''),
//                     GetSQLValueString($this->crypt->encrypt($this->oldvalue),'text','',''),
//                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
//                     GetSQLValueString($this->whochanged,'text','','')
//                     );
//          }

 //         echo $query;

           $userins = dosql($query);

           $userins = dosql("SELECT LAST_INSERT_ID() from ".TBL_PREFIX."contacthistory");

           $row_contact = mysql_fetch_assoc($userins);

           $this->id=$row_contact['LAST_INSERT_ID()'];

          return $this->id;

        }

        function deletecontacthistory($contactid) {
                global $encrypted;
                global $connection;

                $query = sprintf("DELETE FROM ".TBL_PREFIX."contacthistory where ".TBL_PREFIX."contacthistory.contactid=%s ", $contactid );

                $res = dosql($query);

                return $res;
        }

        function getlastfieldbewteendates($contactid,$dtefrom,$dteto, $tablename , $fieldname) {
                global $connection;
                $dater = new dater;
                $status="";

                $query = sprintf("SELECT * FROM ".TBL_PREFIX."contacthistory where effectivefrom>=%s and effectivefrom<=%s and tablename='%s' and fieldname='%s' and contactid=%s order by contactid asc,effectivefrom  desc",
                                GetSQLValueString($dater->phptosqldate($dtefrom),'date','','') ,
                                GetSQLValueString($dater->phptosqldate($dteto),'date','','') ,
                                $tablename,
                                $fieldname,
                                $contactid );


                $res = dosql($query);

                if  (mysql_num_rows($res)>0) {
                     $row = mysql_fetch_assoc($res);
                     $status= $row['newvalue'];
                } else {

                }
                                
                //              if ($contactid==52) { echo $query."<br/>\n"; }
                                
             return $status;
        }

}

?>
