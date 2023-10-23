<?php
class mmsession {
        var $connection;
        var $userid;
        var $ip;
        var $timein;
        var $timeupd;
        var $pagename;
        var $session_id;
        var $active;

        function createsessiontable($connection) {
          $query = "".
          "CREATE TABLE `".TBL_PREFIX."contactsession` (".
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
//                $this->userid=0;
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
                        WHERE ".TBL_PREFIX."contactsession.session_id='%s' AND ".TBL_PREFIX."contactsession.session_active=1",$this->session_id);

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
                $query = sprintf("DELETE FROM ".TBL_PREFIX."contactsession where ".TBL_PREFIX."contactsession.session_userid= %s", $this->userid);
                $session_del = dosql($query);

        }
                
 }
?>
