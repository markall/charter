<?php


class categories {
        var $connection="";
        var $contactid="";
        var $employmentstatus="";
        var $emailcontactafter="";
        var $emailfromwebsite="";
        var $occupation="";
        var $occdescription="";         
        var $qualifications="";
        var $referralsource="";
        var $datejoinedcsb="";
        var $datelastemployed="";
        var $msleoj="";
        var $dateemployed="";
        var $jobfounddetail="";
        var $dateunsubscribed="";
        var $datelapsed="";
        var $mcdateofjoining="";
        var $mcdateofretiring="";
        var $mccurrentrole="";
        var $vintroducer="";
        var $vcvreviwerid="";
        var $vnotes="";
        var $media= array();


        var $detail= array();


        function createtraderstable($connection) {

                $query="drop table if exists ".TBL_PREFIX."categories; ";
                $userrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."categories`  (".
                "  `id` int(11) default '0',".
                "  `categorytitle` varchar(100) default '',".
                "  `categoryparentid` int(11) default '0' ,".
                                "  `categoryheader` varchar(100) default '',".
                                "  `categoryfooter` varchar(100) default '',".
                                "  `categoryimage` varchar(100) default '',",
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`categorytitle`)".
                ") ";

                $userrec = dosql($query);


                $query="drop table if exists ".TBL_PREFIX."contactcategories; ";
                $userrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."contactcategorieslink` (".
                                " `contactid` int(11) NOT NULL,".
                                " `categoryid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                "PRIMARY KEY ( id)".
                                ") ";

                $userrec = dosql($query);

        }



        function initcontactdetail() {
                 $this->type="member";
                 $this->status="pending";
                 $this->crypt=new crypt();
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
                    $this->employmentstatus=$row_user['employmentstatus'];
                    $this->emailcontactafter=$row_user['emailcontactafter'];
                    $this->emailfromwebsite=$row_user['emailfromwebsite'];
                    $this->occupation=$row_user['occupation'];
                    $this->occdescription=$row_user['occdescription'];                                  
                    $this->qualifications=$row_user['qualifications'];
                    $this->referralsource=$row_user['referralsource'];
                    $this->datejoinedcsb=$dater->sqltophpdate( $row_user['datejoinedcsb'] );
                    $this->datelastemployed=$dater->sqltophpdate( $row_user['datelastemployed'] );
                    $this->msleoj=$row_user['msleoj'];
                    $this->dateemployed=$dater->sqltophpdate( $row_user['dateemployed'] );
                    $this->jobfounddetail=$row_user['jobfounddetail'];
                    $this->dateunsubscribed=$row_user['dateunsubscribed'];
                    $this->datelapsed=$dater->sqltophpdate( $row_user['datelapsed'] );
                    $this->mcdateofjoining=$dater->sqltophpdate( $row_user['mcdateofjoining'] );
                    $this->mcdateofretiring=$dater->sqltophpdate( $row_user['mcdateofretiring'] );
                    $this->mccurrentrole=$row_user['mccurrentrole'];
                    $this->vintroducer=$row_user['vintroducer'];
                    $this->vcvreviwerid=$row_user['vcvreviwerid'];
                    $this->vnotes=$row_user['vnotes'];
                } else {

                    $this->employmentstatus=$row_user['employmentstatus'];
                    $this->emailcontactafter=$row_user['emailcontactafter'];
                    $this->emailfromwebsite=$row_user['emailfromwebsite'];
                    $this->occupation=trim($this->crypt->decrypt(strip2($row_user['occupation'])));
                    $this->occdescription=trim($this->crypt->decrypt(strip2($row_user['occdescription'])));
                    $this->qualifications=trim($this->crypt->decrypt(strip2($row_user['qualifications'])));
                    $this->referralsource=trim($this->crypt->decrypt(strip2($row_user['referralsource'])));
                    $this->datejoinedcsb=$dater->sqltophpdate( $row_user['datejoinedcsb'] );
                    $this->datelastemployed=$dater->sqltophpdate( $row_user['datelastemployed'] );
                    $this->msleoj=$row_user['msleoj'];
                    $this->dateemployed=$dater->sqltophpdate( $row_user['dateemployed'] );
                    $this->jobfounddetail=$row_user['jobfounddetail'];
                    $this->dateunsubscribed=$dater->sqltophpdate( $row_user['dateunsubscribed'] );
                    $this->datelapsed=$dater->sqltophpdate( $row_user['datelapsed'] );
                    $this->mcdateofjoining=$dater->sqltophpdate( $row_user['mcdateofjoining'] );
                    $this->mcdateofretiring=$dater->sqltophpdate( $row_user['mcdateofretiring'] );
                    $this->mccurrentrole=$row_user['mccurrentrole'];
                    $this->vintroducer=$row_user['vintroducer'];
                    $this->vcvreviwerid=$row_user['vcvreviwerid'];
                    $this->vnotes=trim($this->crypt->decrypt(strip2($row_user['vnotes'])));
                }
                $this->getmedia($id,'');
            }

            return mysql_num_rows($userrec);
        }



        function insertcontactdetail() {
                global $encrypted;
                global $connection;

                   $dater= new dater;

           if ($encrypted==false) {
               $query_user = sprintf("INSERT INTO ".TBL_PREFIX."contactdetail (
        contactid, employmentstatus, emailcontactafter, emailfromwebsite,  occupation, occdescription ,
        qualifications,  referralsource, datejoinedcsb,  datelastemployed,  msleoj,
        dateemployed, jobfounddetail, dateunsubscribed,  datelapsed,  mcdateofjoining,
        mcdateofretiring,  mccurrentrole,  vintroducer, vcvreviwerid, vnotes, datelastmod,datecreated )
               VALUES ( %s,  %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->employmentstatus,'text','',''),
                     GetSQLValueString($this->emailcontactafter,'text','',''),
                     GetSQLValueString($this->emailfromwebsite,'text','',''),
                     GetSQLValueString($this->occupation,'text','',''),
                     GetSQLValueString($this->occdescription,'text','',''),
                     GetSQLValueString($this->qualifications,'text','',''),
                     GetSQLValueString($this->referralsource,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->datejoinedcsb),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->datelastemployed),'date','',''),
                     GetSQLValueString($this->msleoj,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dateemployed),'date','',''),
                     GetSQLValueString($this->jobfounddetail,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dateunsubscribed),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->datelapsed),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->mcdateofjoining),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->mcdateofretiring),'date','',''),
                     GetSQLValueString($this->mccurrentrole,'text','',''),
                     GetSQLValueString($this->vintroducer,'text','',''),
                     GetSQLValueString($this->vcvreviwerid,'text','',''),
                     GetSQLValueString($this->vnotes,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','')
                     );
           } else {
                     $query= sprintf("INSERT INTO ".TBL_PREFIX."contactdetail (
        contactid, employmentstatus, emailcontactafter, emailfromwebsite,  occupation, occdescription ,
        qualifications,  referralsource, datejoinedcsb,  datelastemployed,  msleoj,
        dateemployed, jobfounddetail, dateunsubscribed,  datelapsed,  mcdateofjoining,
        mcdateofretiring,  mccurrentrole,  vintroducer, vcvreviwerid, vnotes, datelastmod,datecreated )
               VALUES ( %s,  %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->contactid,'text','',''),
                     GetSQLValueString($this->employmentstatus,'text','',''),
                     GetSQLValueString($this->emailcontactafter,'text','',''),
                     GetSQLValueString($this->emailfromwebsite,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->occupation),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->occdescription),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->qualifications),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->referralsource),'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->datejoinedcsb),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->datelastemployed),'date','',''),
                     GetSQLValueString($this->msleoj,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dateemployed),'date','',''),
                     GetSQLValueString($this->jobfounddetail,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dateunsubscribed),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->datelapsed),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->mcdateofjoining),'date','',''),
                     GetSQLValueString($dater->phptosqldate($this->mcdateofretiring),'date','',''),
                     GetSQLValueString($this->mccurrentrole,'text','',''),
                     GetSQLValueString($this->vintroducer,'text','',''),
                     GetSQLValueString($this->vcvreviwerid,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->vnotes),'text','',''),
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
                    $query_user = sprintf("UPDATE ".TBL_PREFIX."contactdetail set
                                        employmentstatus=%s,
                                        emailcontactafter=%s, emailfromwebsite=%S,  occupation=%s, occdescription=%s,
                                        qualifications=%s,  referralsource=%s, datejoinedcsb=%s,  datelastemployed=%s,  msleoj=%s,
                                        dateemployed=%s, jobfounddetail=%s, dateunsubscribed=%s,  datelapsed=%s,  mcdateofjoining=%s,
                                        mcdateofretiring=%s,  mccurrentrole=%s,  vintroducer=%s, vcvreviwerid=%s, vnotes=%s, datelastmod=%s
                                        where contactid=%s",
                                   GetSQLValueString($this->employmentstatus,'text','',''),
                                   GetSQLValueString($this->emailcontactafter,'text','',''),
                                   GetSQLValueString($this->emailfromwebsite,'text','',''),
                                   GetSQLValueString($this->occupation,'text','',''),
                                   GetSQLValueString($this->occdescription,'text','',''),                                                                  
                                   GetSQLValueString($this->qualifications,'text','',''),
                                   GetSQLValueString($this->referralsource,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datejoinedcsb),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datelastemployed),'date','',''),
                                   GetSQLValueString($this->msleoj,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->dateemployed),'date','',''),
                                   GetSQLValueString($this->jobfounddetail,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->dateunsubscribed),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datelapsed),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->mcdateofjoining),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->mcdateofretiring),'date','',''),
                                   GetSQLValueString($this->mccurrentrole,'text','',''),
                                   GetSQLValueString($this->vintroducer,'text','',''),
                                   GetSQLValueString($this->vcvreviwerid,'text','',''),
                                   GetSQLValueString($this->vnotes,'text','',''),
                                   GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','','') ,
                           $id );
                    } else {
                    $query = sprintf("UPDATE ".TBL_PREFIX."contactdetail set
                                        employmentstatus=%s,
                                        emailcontactafter=%s, emailfromwebsite=%s,  occupation=%s, occdescription=%s,
                                        qualifications=%s,  referralsource=%s, datejoinedcsb=%s,  datelastemployed=%s,  msleoj=%s,
                                        dateemployed=%s, jobfounddetail=%s, dateunsubscribed=%s,  datelapsed=%s,  mcdateofjoining=%s,
                                        mcdateofretiring=%s,  mccurrentrole=%s,  vintroducer=%s, vcvreviwerid=%s, vnotes=%s, datelastmod=%s
                                        where contactid=%s",
                                   GetSQLValueString($this->employmentstatus,'text','',''),
                                   GetSQLValueString($this->emailcontactafter,'text','',''),
                                   GetSQLValueString($this->emailfromwebsite,'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->occupation),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->occdescription),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->qualifications),'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->referralsource),'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datejoinedcsb),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datelastemployed),'date','',''),
                                   GetSQLValueString($this->msleoj,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->dateemployed),'date','',''),
                                   GetSQLValueString($this->jobfounddetail,'text','',''),
                                   GetSQLValueString($dater->phptosqldate($this->dateunsubscribed),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->datelapsed),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->mcdateofjoining),'date','',''),
                                   GetSQLValueString($dater->phptosqldate($this->mcdateofretiring),'date','',''),
                                   GetSQLValueString($this->mccurrentrole,'text','',''),
                                   GetSQLValueString($this->vintroducer,'text','',''),
                                   GetSQLValueString($this->vcvreviwerid,'text','',''),
                                   GetSQLValueString($this->crypt->encrypt($this->vnotes),'text','',''),
                                   GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                                 $id );
                    }

                    $userupd = dosql($query);
        }

        function deletebycontactdetailid($id) {
                    $query = sprintf("DELETE from ".TBL_PREFIX."contactdetail where contactid=%s",$id);
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

        function getmedia($id,$mname) {
            global $connection;
            $media = array();

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contactmedia WHERE contactid=%s", $id );

            if ($mname!='') {
               $query_contactmedia = $query_contactmedia.sprintf(" AND medianame = '%s' ",$mname );
            }

            $contactmediarec = dosql($query);
            unset ($this->media);
            if (mysql_num_rows($contactmediarec)>0) {
              while  ($row_contactmedia = mysql_fetch_assoc($contactmediarec)) {
                      $media['mediaid'] = $row_contactmedia['id'];
                      $media['mediaf'] = $row_contactmedia['mediaf'];
                      $media['medianame'] = $row_contactmedia['mediaanme'];
                      $this->media[] = $media;
              }
            }
        }

        function deletemedia($id) {
            global $connection;
                        $this->getmedia($id,'');
                        foreach ($this->media as $key=>$value) {
                                unlink( "/profiles/".$id."/".$value['mediaf'] );
                        }
            $query = sprintf("DELETE ROM ".TBL_PREFIX."contactmedia WHERE contactid=%s", $id );
            $contactmediarec = dosql($query);
            unset ($this->media);
        }


}

?>
