<?php


class contactdetail {
        var $connection="";
        var $contactid="";
        var $title="";
        var $shortdescription="";
        var $facebookid="";
        var $paypalemail="";
        var $description="";
        var $newsletter="";
        var $newsletteremail="";
        var $hyperlink="";
        var $datejoined="";
        var $active="";
        var $shop="";
        var $security="";
        var $securitynumber="";
        var $referralsource="";
        var $contactdate="";
        var $media= array();
        var $detail= array();


        function createcontactdetailtable($connection) {

                $query="drop table if exists ".TBL_PREFIX."contactdetail; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."contactdetail`  (".
                "  `contactid` int(11) default '0',".
                "  `title` varchar(160) default '',".
                "  `shortdescription` varchar(255) default '',".
                "  `facebookid` varchar(20) default '', ".
                "  `paypalemail` varchar(40) default '', ".
                "  `description` text ,".
                "  `newsletter` varchar(1) ,".
                "  `newsletteremail` varchar(30) ,".
                "  `hyperlink` varchar(10) , ".
                "  `datejoined` datetime default '0000-00-00 00:00:00',".
                "  `active` varchar(5) , ".
                "  `referralsource` varchar(160) default '',".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`contactid`)".
                ") ";

                   

                $userrec = dosql($query);


                $query="drop table if exists ".TBL_PREFIX."contactmedia; ";
                $userrec = dosql($query);

                $userrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."contactmedia` (".
                                " `contactid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                " `mediaf` varchar(130),".
                                " `medianame` varchar(130),".
                                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                                "  `wholastmod` varchar(20) default '',".
                                "PRIMARY KEY ( id)".
                                ") ";

                $userrec = dosql($query);

        }



        function contactdetail() {
                 $this->type="member";
                 $this->status="pending";
                 $this->crypt=new crypt();
                 unset ($this->media);
        }



        function getcontactdetailbyid($id) {
                global $encrypted;
                global $connection;

        $dater= new dater;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contactdetail  WHERE ".TBL_PREFIX."contactdetail.contactid='%s'",$id);

            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                if ($encrypted==false) {

                    $this->contactid=$row_user['contactid'];
                    $this->title=$row_user['title'];
                    $this->shortdescription=$row_user['shortdescription'];
                    $this->facebookid = $row_user['facebookid'];
                    $this->paypalemail = $row_user['paypalemail'];
                    $this->description=$row_user['description'];
                    $this->referralsource=$row_user['referralsource'];
                    $this->newsletter=$row_user['newsletter'];
                    $this->newsletteremail=$row_user['newsletteremail'];
                    $this->hyperlink=$row_user['hyperlink'];
                    $this->datejoined=$dater->sqltophpdate($row_user['datejoined']);
                    $this->active=$row_user['active'];

                } else {

                    $this->contactid=$row_user['contactid'];
                    $this->title=trim($this->crypt->decrypt(strip2($row_user['title'])));
                    $this->shortdescription=trim($this->crypt->decrypt(strip2($row_user['shortdescription'])));
                    $this->facebookid = trim($this->crypt->decrypt(strip2($row_user['facebookid'])));
                    $this->paypalemail = trim($this->crypt->decrypt(strip2($row_user['paypalemail'])));
                    $this->description=trim($this->crypt->decrypt(strip2($row_user['description'])));
                    $this->referralsource=trim($this->crypt->decrypt(strip2($row_user['referralsource'])));
                    $this->newsletter=$row_user['newsletter'];
                    $this->newsletteremail=$row_user['newsletteremail'];
                    $this->hyperlink=$row_user['hyperlink'];
                    $this->datejoined=$dater->sqltophpdate($row_user['datejoined']);
                    $this->active=$row_user['active'];

                }
                $this->getmedia('');
            }

            return mysql_num_rows($userrec);
        }



        function insertcontactdetail() {
                global $encrypted;
                global $connection;

                   $dater= new dater;
             
           if ($encrypted==false) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."contactdetail (
        contactid, title, shortdescription, facebookid, paypalemail,description ,
               referralsource, newsletter,
               newsletteremail,hyperlink, datejoined, active,
               datelastmod,datecreated )
               VALUES ( %s,  %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->shortdescription,'text','',''),
                     GetSQLValueString($this->facebookid,'text','',''),
                     GetSQLValueString($this->paypalemail,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->referralsource,'text','',''),
                     GetSQLValueString($this->newsletter,'text','',''),
                     GetSQLValueString($this->newsletteremail,'text','',''),
                     GetSQLValueString($this->hyperlink,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->datejoined),'date','',''),
                     GetSQLValueString($this->active,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
           } else {
                     $query = sprintf("INSERT INTO ".TBL_PREFIX."contactdetail (
        contactid, title, shortdescription, facebookid, paypalemail, description ,
        referralsource,newsletter,
               newsletteremail,hyperlink, datejoined, active,
               datelastmod,datecreated  )
               VALUES (%s,  %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->title),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->shortdescription),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->facebookid),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->paypalemail),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->description),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->referralsource),'text','',''),
                     GetSQLValueString($this->newsletter,'text','',''),
                     GetSQLValueString($this->newsletteremail,'text','',''),
                     GetSQLValueString($this->hyperlink,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->datejoined),'date','',''),
                     GetSQLValueString($this->active,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
           }


           $userins = dosql($query);

          $this->id=mysql_insert_id();

          return $this->id;
        }

        function updatecontactdetailbyid($id) {
                global $encrypted;
                global $connection;

                                   $dater= new dater;

                if ($encrypted==false) {
                    $query = sprintf("UPDATE ".TBL_PREFIX."contactdetail set
                                        title=%s, shortdescription=%s, description=%s,
                                        facebookid=%s, paypalemail=%s,
                                        referralsource=%s, newsletter=%s,
                                        newsletteremail=%s, hyperlink=%s,
                                        datejoined=%s,active=%s,
                                        datelastmod=%s
                                        where contactid=%s",
                                   GetSQLValueString($this->title,'text','',''),
                                   GetSQLValueString($this->shortdescription,'text','',''),
                                   GetSQLValueString($this->description,'text','',''),
                                   GetSQLValueString($this->facebookid,'text','',''),
                                   GetSQLValueString($this->paypalemail,'text','',''),
                                   GetSQLValueString($this->referralsource,'text','',''),
                                   GetSQLValueString($this->newsletter,'text','',''),
                                   GetSQLValueString($this->newsletteremail,'text','',''),
                                   GetSQLValueString($this->hyperlink,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datejoined),'date','',''),
                                   GetSQLValueString($this->active,'text','',''),
                                   GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','') ,
                           $id );
                    } else {
                    $query = sprintf("UPDATE ".TBL_PREFIX."contactdetail set
                                        title=%s, shortdescription=%s, description=%s,
                                        facebookid=%s, paypalemail=%s,
                                        referralsource=%s, newsletter=%s,
                                        newsletteremail=%s, hyperlink=%s,
                                        datejoined=%s,active=%s,
                                        datelastmod=%s
                                        where contactid=%s",
                                   GetSQLValueString($this->crypt->encrypt($this->title),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->shortdescription),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->description),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->facebookid),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->paypalemail),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->referralsource),'text','',''),
                                   GetSQLValueString($this->newsletter,'text','',''),
                                   GetSQLValueString($this->newsletteremail,'text','',''),
                                   GetSQLValueString($this->hyperlink,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datejoined),'date','',''),
                                   GetSQLValueString($this->active,'text','',''),                                   
                                   GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                                 $id );
                    }

                    $userupd = dosql($query);
        }

        function deletebycontactdetailid($id) {
                    global $connection;
                    $query = sprintf("DELETE from ".TBL_PREFIX."contactdetail where contactid=%s",$id);
                    $userupd = dosql($query);

                    $query = sprintf("DELETE FROM ".TBL_PREFIX."contactmedia WHERE contactid=%s  ", $id );
                    $userupd = dosql($query);

        }

        function insertmedia($id,$media) {
            global $connection;
            $dater= new dater;
            $sdate = $dater->phptosqldate(date("d/m/Y H:i"));



            $query = sprintf("INSERT INTO ".TBL_PREFIX."contactmedia ( contactid, mediaf, medianame, datelastmod,datecreated )
               VALUES ( %s, %s, %s, %s, %s )",
                     GetSQLValueString($id,'text','',''),
                     GetSQLValueString($media['mediaf'],'text','',''),
                     GetSQLValueString($media['medianame'],'text','','') ,
                     GetSQLValueString($sdate,'date','',''),
                     GetSQLValueString($sdate,'date','','')
                     );

                 $userins = dosql($query);

        }

        function getmedia($mname) {
            global $connection;
            $media = array();

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contactmedia WHERE contactid=%s", $this->contactid );

            if ($mname>' ') {
               $query = $query_contactmedia.sprintf(" AND medianame = '%s' ",$mname );
            }


            $contactmediarec = dosql($query);
            unset ($this->media);
            if (mysql_num_rows($contactmediarec)>0) {
              while  ($row_contactmedia = mysql_fetch_assoc($contactmediarec)) {
                      $media['mediaid'] = $row_contactmedia['id'];
                      $media['mediaf'] = $row_contactmedia['mediaf'];
                      $media['medianame'] = $row_contactmedia['medianame'];
                      $this->media[] = $media;
              }
            }
        }

        function deletemedia($id,$iname,$fname ) {
            global $connection;
//                        $this->getmedia($id,'');
//                        foreach ($this->media as $key=>$value) {
//                                unlink( "/profiles/".$id."/".$value['mediaf'] );
//
//                        }

            if ($fname>'') {
                    $query = sprintf("DELETE FROM ".TBL_PREFIX."contactmedia WHERE contactid=%s and mediaf='%s' ", $id,$fname );
            } else {
              $query = sprintf("DELETE FROM ".TBL_PREFIX."contactmedia WHERE contactid=%s and medianame='%s' ", $id,$iname );
            }

            $contactmediarec = dosql($query);

            unset ($this->media);
            $this->media= array();
        }


}

?>
