<?php

 class session {
        var $connection;
        var $userid;
        var $ip;
        var $timein;
        var $timeupd;
        var $pagename;
        var $session_id;
        var $active;

        function createsessiontable() {
          $query = "".
          "CREATE TABLE ".TBL_PREFIX."`contactsession` (".
          "  `id` int(11) NOT NULL auto_increment, ".
          "  `session_userid` int(11) NOT NULL default 0, ".
          "  `session_timein` datetime default '0000-00-00 00:00:00' ,".
          "  `session_timeupd` datetime default '0000-00-00 00:00:00' ,".
          "  `session_ip` VARCHAR(100) ,".
          "  `session_pagename` VARCHAR (100), ".
          "  `session_id` VARCHAR(100) default '', ".
          "  `session_active` BOOLEAN, ".
          "  PRIMARY KEY  (`id`), ".
          "  KEY `session_ksessionid` (`session_id`),".
          "  KEY `session_kuserid` (`session_userid`)".
          ")";

          $userrec = dosql($query);


        }


        function initsession() {
                $this->userid=0;
                $this->ip= $_SERVER['REMOTE_ADDR'];
                $this->timein=date('d/m/Y h:i:s');
                $this->timeupd=date('d/m/Y h:i:s');
                $this->session_id=md5($this->userid.$this->ip);
                $this->active="1";
                $this->pagename="";
        }


        function insertsession() {

            $dater=new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."contactsession ( session_userid, session_timein, session_timeupd, session_ip,
                                  session_pagename, session_id, session_active )
           VALUES ( %s, %s, %s, %s, %s, %s, %s )",
                 GetSQLValueString($this->userid,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->timein),'date','',''),
                 GetSQLValueString($dater->phptosqldate($this->timeupd),'date','',''),
                 GetSQLValueString($this->ip,'text','',''),
                 GetSQLValueString($this->pagename,'text','',''),
                 GetSQLValueString($this->session_id,'text','',''),
                 GetSQLValueString($this->active,'text','','')
                 );


           $sessionins = dosql($query);

           return mysql_insert_id();

        }

        function updatesession() {
                $dater=new dater;
                $query = sprintf("UPDATE  ".TBL_PREFIX."contactsession set session_timeupd = %s,
                                          session_pagename = %s, session_active = %s
                                          where session_id = %s ",
                                         GetSQLValueString($dater->phptosqldate($this->timeupd),'date','',''),
                                         GetSQLValueString($this->pagename,'text','',''),
                                         GetSQLValueString($this->active,'text','',''),
                                         GetSQLValueString($this->session_id,'text','','') );
                $sessionupd = dosql($query);
        }

        function getsessionbysessionid() {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contactsession
                        WHERE contactsession.session_id='%s' AND contactsession.session_active=1",$this->session_id);

            $session_rec = dosql($query);
            $row_session = mysql_fetch_assoc($session_rec);

            if (mysql_num_rows($session_rec)>0) {
                 $this->userid = $row_session['session_userid'];
                 $this->ip = $row_session['session_ip'];
                 $this->timein=$row_session['session_timein'];
                 $this->timeupd=$row_session['session_timeupd'];
                 $this->active=$row_session['session_active'];
                 $this->session_id=$row_session['session_id'];
            }
            return mysql_num_rows($session_rec);
        }

        function deletesessionbyuserid() {
                $query = sprintf("DELETE FROM ".TBL_PREFIX."contactsession where contactsession.session_userid= %s", $this->userid);
                $session_del = dosql($query);

        }



 }
 class contact {
        var $connection;
        var $userid;
        var $username;
        var $password;
        var $title;
        var $firstname;
        var $initials;
        var $lastname;
        var $company;
        var $telephone1;
        var $mobile;
        var $address1;
        var $address2;
        var $address3;
        var $town;
        var $county;
        var $postcode;
        var $email;
        var $status;
        var $house;
        var $pin;

        function createcontacttable() {
                $query = "".
                "CREATE TABLE `".TBL_PREFIX."contacts`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `username` varchar(20) default '',".
                "  `password` varchar(20) default '',".
                "  `title` varchar(20) default '',".
                "  `firstname` varchar(20) default '',".
                "  `initials` varchar(20) default '',".
                "  `lastname` varchar(30) default '',".
                "  `company` varchar(30) default '',".
                "  `email` varchar(80) default '',".
                "  `house` varchar(20) default '',".
                "  `address1` varchar(25) default '',".
                "  `address2` varchar(25) default '',".
                "  `address3` varchar(100) default '',".
                "  `town` varchar(30) default '',".
                "  `county` varchar(20) default '',".
                "  `postcode` varchar(15) default '',".
                "  `telephone1` varchar(20) default '',".
                "  `telephone2` varchar(20) default '',".
                "  `mobile` varchar(20) default '',".
                "  `dob` datetime default '0000-00-00 00:00:00' ,".
                "  `type` varchar(10) default '',".
                "  `status` varchar(10) default '',".
                "  `description` text,".
                "  `image` blob NOT NULL,".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`),".
                "  KEY `user` (`username`)".
                ") ";

                $userrec = dosql($query);

        }



        function initcontact() {
                 $this->username="";
                 $this->password="";
                 $this->title="";
                 $this->firstname="";
                 $this->initials="";
                 $this->lastname="";
                 $this->company="";
                 $this->telephone1="";
                 $this->mobile="";
                 $this->address1="";
                 $this->address2="";
                 $this->address3="";
                 $this->town="";
                 $this->county="";
                 $this->postcode="";
                 $this->email="";
                 $this->dob="";
                 $this->type="user";
                 $this->status="pending";
        }

        function getcontact($username) {                   
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contacts  WHERE contacts.username='%s'",$username);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=$row_user['lastname'];
                 $this->company=$row_user['company'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->mobile=$row_user['mobile'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->postcode=$row_user['postcode'];
                 $this->email=$row_user['email'];
                 $this->dob=$row_user['dob'];
                 $this->status=$row_user['status'];

            }
            return mysql_num_rows($userrec);
        }

        function getcontactbyemail($email) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contacts  WHERE contacts.email='%s'",$email);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=$row_user['lastname'];
                 $this->company=$row_user['company'];
                 $this->email=$row_user['email'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->mobile=$row_user['mobile'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->postcode=$row_user['postcode'];
                 $this->dob=$row_user['dob'];
                 $this->status=$row_user['status'];
            }
            return mysql_num_rows($userrec);
        }

        function getcontactbyid($id) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."contacts  WHERE contacts.id='%s'",$id);
            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=$row_user['lastname'];
                 $this->company=$row_user['company'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->mobile=$row_user['mobile'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->postcode=$row_user['postcode'];
                 $this->email=$row_user['email'];
                 $this->dob=$row_user['dob'];
                 $this->status=$row_user['status'];

            }
            return mysql_num_rows($userrec);
        }

        function getallcontacts($status,$type) {
                $query = "SELECT * FROM ".TBL_PREFIX."contacts";
                if ($status>" ") {
                   $query=$query.' where status="$status"';
                }
                if ($type>" ") {
                   $query=$query.' where type="$type"';
                }
            $contacts =dosql($query);
            return $contacts;
        }

        function insertcontact() {
                   $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."contacts ( username, password, title, firstname, initials, lastname,company,
                                    telephone1,mobile,address1,address2,address3,town,county,postcode,
                                    email, dob, type,status )
           VALUES ( %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                 GetSQLValueString($this->username,'text','',''),
                 GetSQLValueString($this->password,'text','',''),
                 GetSQLValueString($this->title,'text','',''),
                 GetSQLValueString($this->firstname,'text','',''),
                 GetSQLValueString($this->initials,'text','',''),
                 GetSQLValueString($this->lastname,'text','',''),
                 GetSQLValueString($this->company,'text','',''),
                 GetSQLValueString($this->telephone1,'text','',''),
                 GetSQLValueString($this->mobile,'text','',''),
                 GetSQLValueString($this->address1,'text','',''),
                 GetSQLValueString($this->address2,'text','',''),
                 GetSQLValueString($this->address3,'text','',''),
                 GetSQLValueString($this->town,'text','',''),
                 GetSQLValueString($this->county,'text','',''),
                 GetSQLValueString($this->postcode,'text','',''),
                 GetSQLValueString($this->email,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                 GetSQLValueString($this->type,'text','',''),
                 GetSQLValueString($this->status,'text','','')
                 );

           $userins = dosql($query);

           return mysql_insert_id();
        }

        function updatecontactbyid($id) {
                                   $dater= new dater;
          $query = sprintf("UPDATE ".TBL_PREFIX."contacts set username=%s, password=%s,title=%s,firstname=%s,initials=%s,
                                lastname=%s, company=%s,
                                telephone1=%s,mobile=%s,address1=%s,address2=%s,address3=%s,town=%s,county=%s,postcode=%s,
                                email=%s,dob=%s,type=%s,status=%s where id=%s",
                 GetSQLValueString($this->username,'text','',''),
                 GetSQLValueString($this->password,'text','',''),
                 GetSQLValueString($this->title,'text','',''),
                 GetSQLValueString($this->firstname,'text','',''),
                 GetSQLValueString($this->initials,'text','',''),
                 GetSQLValueString($this->lastname,'text','',''),
                 GetSQLValueString($this->company,'text','',''),
                 GetSQLValueString($this->telephone1,'text','',''),
                 GetSQLValueString($this->mobile,'text','',''),
                 GetSQLValueString($this->address1,'text','',''),
                 GetSQLValueString($this->address2,'text','',''),
                 GetSQLValueString($this->address3,'text','',''),
                 GetSQLValueString($this->town,'text','',''),
                 GetSQLValueString($this->county,'text','',''),
                 GetSQLValueString($this->postcode,'text','',''),
                 GetSQLValueString($this->email,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                 GetSQLValueString($this->type,'text','',''),
                 GetSQLValueString($this->status,'text','',''),
                 $id );
                 $userupd =dosql($query);
        }

 }

?>
