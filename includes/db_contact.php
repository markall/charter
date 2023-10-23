<?php



class contact {
        var $shopid=0;
        var $connection="";
        var $userid="";
        var $username="";
        var $password="";
        var $title="";
        var $firstname="";
        var $initials="";
        var $lastname="";
        var $company="";
        var $telephone1="";
        var $telephone2="";
        var $mobile="";
        var $primaryaddressid="";
        var $house="";
        var $address1="";
        var $address2="";
        var $address3="";
        var $town="";
        var $county="";
        var $country="";
        var $postcode="";
        var $email="";
        var $status="";
        var $type="";
        var $dob="";
        var $sex="";
        var $selected="";
        var $parentcontactid=-1;
        var $webaddress="";
        var $latitude="";
        var $longtitude="";
        var $wholastmod="";
        var $description="";
        var $categories=array();
        var $addresses=array();
		var $rentals = array();




        var $detail= array();


        function createcontacttable($connection) {

                $query="drop table if exists ".TBL_PREFIX."contacts; ";
                $userrec = dosql($query);
                        
                $query = "".
                "CREATE TABLE `".TBL_PREFIX."contacts`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `username` varchar(40) default '',".
                "  `password` varchar(140) default '',".
                "  `title` varchar(140) default '',".
                "  `firstname` varchar(140) default '',".
                "  `initials` varchar(140) default '',".
                "  `lastname` varchar(160) default '',".
                "  `company` varchar(160) default '',".
                "  `email` varchar(160) default '',".
                "  `webaddress` varchar(160) default '',".
                "  `telephone1` varchar(140) default '',".
                "  `telephone2` varchar(140) default '',".
                "  `mobile` varchar(140) default '',".
                "  `dob` datetime default '0000-00-00 00:00:00' ,".
                "  `sex` varchar(6) default 'male' ,".
                "  `type` varchar(20) default '',".
                "  `status` varchar(20) default '',".
                "  `description` text,".
                "  `image` blob ,".
                "  `parentcontactid` int(11),".
                "  `sortorder` varchar(20) default '',".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  (`id`),".
                "  KEY `user` (`username`)".
                ") ";

                $userrec = dosql($query);

                $query = ""."CREATE INDEX ".TBL_PREFIX."IDX_username ON ".TBL_PREFIX."contacts (username); ";
                $userrec = dosql($query);

                $query = ""."CREATE INDEX ".TBL_PREFIX."IDX_email ON ".TBL_PREFIX."contacts (email); ";
                $userrec = dosql($query);

                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."contactcategories` (".
                                "   `shopid` int(11) default '0', ".
                                "   `contactid` int(11)  default '0', ".
                                "   `categoryid` int(11)  default '0', ".
                                "   PRIMARY KEY (`shopid`,`categoryid`,`contactid` ) ".
                                ") ";

                $productrec = dosql($query);


                $query = "".
                "CREATE TABLE `".TBL_PREFIX."address`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `contactid` int(11) ,".
                "  `house` varchar(140) default '',".
                "  `address1` varchar(150) default '',".
                "  `address2` varchar(150) default '',".
                "  `address3` varchar(250) default '',".
                "  `town` varchar(160) default '',".
                "  `county` varchar(140) default '',".
                "  `country` varchar(140) default '',".
                "  `postcode` varchar(130) default '',".
                "  `latitude` varchar(30) default '',".
                "  `longtitude` varchar(30) default '',".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `datelastmod` datetime default '0000-00-00 00:00:00',".
                "  `wholastmod` varchar(20) default '',".
                "  PRIMARY KEY  ( `id`)".
                ") ";


                $addressrec = dosql($query);

                $query = "".
                "CREATE TABLE `".TBL_PREFIX."contactaddresslink`  (".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `shopid` int(11) ,".
                "  `contactid` int(11) ,".
                "  `addressid` int(11) ,".
                "  `addresstype` char(1) ,".
                "  PRIMARY KEY  (`id`),".
                "  KEY `contactid` (`contactid`) ,".
                "  KEY `addressid` (`addressid`) ".
                ") ";

                $addressrec = dosql($query);

        }



        function contact() {
                 $this->type="user";
                 $this->status="pending";
                 $this->crypt=new crypt();

        }

        function getcontact($username) {
                global $encrypted;
                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT ".TBL_PREFIX."contacts.*,".
                                            TBL_PREFIX."address.id as addressid,".
                                            TBL_PREFIX."address.house,".
                                            TBL_PREFIX."address.address1,".
                                            TBL_PREFIX."address.address2,".
                                            TBL_PREFIX."address.address3,".
                                            TBL_PREFIX."address.town,".
                                            TBL_PREFIX."address.county,".
                                            TBL_PREFIX."address.country,".
                                            TBL_PREFIX."address.postcode,".
                                            TBL_PREFIX."address.latitude,".
                                            TBL_PREFIX."address.longtitude".
                                            " FROM ".TBL_PREFIX."contacts  ".
                                  "LEFT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".TBL_PREFIX."contactaddresslink.contactid ".
                                  "LEFT JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid ".
                                  "WHERE ".TBL_PREFIX."contacts.username='%s' and ".TBL_PREFIX."contactaddresslink.addresstype='P' ",$username);

            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                if ($encrypted==false) {
                 $this->shopid = $row_user['shopid'];
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=stripslashes($row_user['lastname']);
                 $this->company=$row_user['company'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->telephone2=$row_user['telephone2'];
                 $this->mobile=$row_user['mobile'];
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=$row_user['house'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->country=$row_user['country'];
                 $this->postcode=$row_user['postcode'];
                 $this->email=$row_user['email'];
                 $this->webaddress=$row_user['webaddress'];
                 $this->description=$row_user['description'];
                 if ($row_user['dob']>' ') {
                         $this->dob=substr($dater->sqltophpdatetime( $row_user['dob']),0,10);
                 } else {

                 }
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                } else {
                 $this->shopid = $row_user['shopid'];
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $this->crypt->decrypt($row_user['password']);
                 $this->title=$this->crypt->decrypt($row_user['title']);
                 $this->firstname=$this->crypt->decrypt($row_user['firstname']);
                 $this->initials=$this->crypt->decrypt($row_user['initials']);
                 $this->lastname=stripslashes($this->crypt->decrypt($row_user['lastname']));
                 $this->company=$this->crypt->decrypt($row_user['company']);
                 $this->telephone1=$this->crypt->decrypt($row_user['telephone1']);
                 $this->telephone2=$this->crypt->decrypt($row_user['telephone2']);
                 $this->mobile=$this->crypt->decrypt($row_user['mobile']);
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=$this->crypt->decrypt($row_user['house']);
                 $this->address1=$this->crypt->decrypt($row_user['address1']);
                 $this->address2=$this->crypt->decrypt($row_user['address2']);
                 $this->address3=$this->crypt->decrypt($row_user['address3']);
                 $this->town=$this->crypt->decrypt($row_user['town']);
                 $this->county=$this->crypt->decrypt($row_user['county']);
                 $this->country=$this->crypt->decrypt($row_user['country']);
                 $this->postcode=$this->crypt->decrypt($row_user['postcode']);
                 $this->email=$row_user['email'];
                 $this->webaddress=$row_user['webaddress'];
                 $this->description=trim($this->crypt->decrypt(strip2($row_user['description'])));
                 if ($row_user['dob']>' ') {
                         $this->dob=substr($dater->sqltophpdatetime( trim($this->crypt->decrypt($row_user['dob']))),0,10);
                 } else {

                 }


                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];

                 $this->getcategoriesbycontact();
				 
				 if (CHARTER) {
					$this->getrentalsbycontact();
				 }

                }


            }

            return mysql_num_rows($userrec);
        }

        function getcontactbyemail($email) {
                global $encrypted;
                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT ".TBL_PREFIX."contacts.*,".
                                            TBL_PREFIX."address.id as addressid,".
                                            TBL_PREFIX."address.house,".
                                            TBL_PREFIX."address.address1,".
                                            TBL_PREFIX."address.address2,".
                                            TBL_PREFIX."address.address3,".
                                            TBL_PREFIX."address.town,".
                                            TBL_PREFIX."address.county,".
                                            TBL_PREFIX."address.country,".
                                            TBL_PREFIX."address.postcode,".
                                            TBL_PREFIX."address.latitude,".
                                            TBL_PREFIX."address.longtitude".
                                            " FROM ".TBL_PREFIX."contacts  ".
                                  "LEFT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".TBL_PREFIX."contactaddresslink.contactid ".
                                  "LEFT JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid ".
                                  "WHERE ".TBL_PREFIX."contacts.email='%s' and ".TBL_PREFIX."contactaddresslink.addresstype='P'",$email);

            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                if ($encrypted==false) {
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=stripslashes($row_user['lastname']);
                 $this->company=$row_user['company'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->telephone1=$row_user['telephone2'];
                 $this->mobile=$row_user['mobile'];
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=$row_user['house'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->country=$row_user['country'];
                 $this->postcode=$row_user['postcode'];
                 $this->email=$row_user['email'];
                 $this->webaddress=$row_user['webaddress'];
                 $this->description=$row_user['description'];
                 $this->dob=substr($dater->sqltophpdatetime( $row_user['dob'],2 ),0,10);
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                } else {
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $this->crypt->decrypt($row_user['password']);
                 $this->title=$this->crypt->decrypt($row_user['title']);
                 $this->firstname=$this->crypt->decrypt($row_user['firstname']);
                 $this->initials=$this->crypt->decrypt($row_user['initials']);
                 $this->lastname=stripslashes($this->crypt->decrypt($row_user['lastname']));
                 $this->company=$this->crypt->decrypt($row_user['company']);
                 $this->telephone1=$this->crypt->decrypt($row_user['telephone1']);
                 $this->telephone2=$this->crypt->decrypt($row_user['telephone2']);
                 $this->mobile=$this->crypt->decrypt($row_user['mobile']);
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=$this->crypt->decrypt($row_user['house']);
                 $this->address1=$this->crypt->decrypt($row_user['address1']);
                 $this->address2=$this->crypt->decrypt($row_user['address2']);
                 $this->address3=$this->crypt->decrypt($row_user['address3']);
                 $this->town=$this->crypt->decrypt($row_user['town']);
                 $this->county=$this->crypt->decrypt($row_user['county']);
                 $this->country=$this->crypt->decrypt($row_user['country']);
                 $this->postcode=$this->crypt->decrypt($row_user['postcode']);
                 $this->email=$row_user['email'];
                 $this->webaddress=trim($this->crypt->decrypt(strip2($row_user['webaddress'] )));
                 $this->description=trim($this->crypt->decrypt(strip2($row_user['description'] )));
                 $this->dob=substr($dater->sqltophpdatetime( trim($this->crypt->decrypt($row_user['dob']) )) ,0,10) ;
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                }


                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                 $this->getcategoriesbycontact();
				 
				 if (CHARTER) {
					$this->getrentalsbycontact();
				 }				 

            }
            return mysql_num_rows($userrec);
        }

        function getcontactbyid($id) {
                global $encrypted;
                global $connection;

                $dater= new dater;

            $query = sprintf("SELECT ".TBL_PREFIX."contacts.* ,".
                                            //TBL_PREFIX."contacts.shopid as shopid,".
                                            //TBL_PREFIX."contacts.id as id,".
                                            //TBL_PREFIX."contacts.username as username,".
                                            //TBL_PREFIX."contacts.password as password,".
                                            //TBL_PREFIX."contacts.title as title,".
                                            //TBL_PREFIX."contacts.firstname as firstname,".
                                            //TBL_PREFIX."contacts.initials as initials,".
                                            //TBL_PREFIX."contacts.lastname as lastname,".
                                            //TBL_PREFIX."contacts.company as company,".
                                            //TBL_PREFIX."contacts.telephone1 as telephone1,".
                                            //TBL_PREFIX."contacts.telephone2 as telephone2,".
                                            //TBL_PREFIX."contacts.mobile as mobile,".
                                            //TBL_PREFIX."contacts.email as email,".
                                            //TBL_PREFIX."contacts.webaddress as webaddress,".
                                            //TBL_PREFIX."contacts.description as description,".
                                            //TBL_PREFIX."contacts.dob as dob,".
                                            //TBL_PREFIX."contacts.sex as sex,".
                                            //TBL_PREFIX."contacts.status as status,".
                                            //TBL_PREFIX."contacts.type as type,".
                                            //TBL_PREFIX."contacts.parentcontactid as parentcontactid,".
                                            TBL_PREFIX."address.id as addressid,".
                                            TBL_PREFIX."address.house,".
                                            TBL_PREFIX."address.address1,".
                                            TBL_PREFIX."address.address2,".
                                            TBL_PREFIX."address.address3,".
                                            TBL_PREFIX."address.town,".
                                            TBL_PREFIX."address.county,".
                                            TBL_PREFIX."address.country,".
                                            TBL_PREFIX."address.postcode,".
                                            TBL_PREFIX."address.latitude,".
                                            TBL_PREFIX."address.longtitude".
                                            " FROM ".TBL_PREFIX."contacts  ".
                                  "LEFT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".TBL_PREFIX."contactaddresslink.contactid ".
                                  "LEFT JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid ".
                                  "WHERE ".TBL_PREFIX."contacts.id='%s' and ".TBL_PREFIX."contactaddresslink.addresstype='P'",$id);

            $userrec = dosql($query);

            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {
                if ($encrypted==false) {
                 $this->shopid = $row_user['shopid'];
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = $row_user['password'];
                 $this->title=$row_user['title'];
                 $this->firstname=$row_user['firstname'];
                 $this->initials=$row_user['initials'];
                 $this->lastname=stripslashes($row_user['lastname']);
                 $this->company=$row_user['company'];
                 $this->telephone1=$row_user['telephone1'];
                 $this->telephone2=$row_user['telephone2'];
                 $this->mobile=$row_user['mobile'];
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=$row_user['house'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->country=$row_user['country'];
                 $this->postcode=$row_user['postcode'];
                 $this->email=$row_user['email'];
                 $this->webaddress=$row_user['webaddress'];
                 $this->description=$row_user['description'];
                 $this->dob=substr($dater->sqltophpdate( $row_user['dob']),0,10);
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                } else {
                 $this->shopid = $row_user['shopid'];
                 $this->userid = $row_user['id'];
                 $this->username = $row_user['username'];
                 $this->password = trim($this->crypt->decrypt(strip2($row_user['password'])));
                 $this->title=trim($this->crypt->decrypt(strip2($row_user['title'])));
                 $this->firstname=trim($this->crypt->decrypt(strip2($row_user['firstname'])));
                 $this->initials=trim($this->crypt->decrypt(strip2($row_user['initials'])));
                 $this->lastname=stripslashes(trim($this->crypt->decrypt( strip2($row_user['lastname']) )));
                 $this->company=trim($this->crypt->decrypt(strip2($row_user['company'])));
                 $this->telephone1=trim($this->crypt->decrypt(strip2($row_user['telephone1'])));
                 $this->telephone2=trim($this->crypt->decrypt(strip2($row_user['telephone2'])));
                 $this->mobile=trim($this->crypt->decrypt(strip2($row_user['mobile'])));
                 $this->primaryaddressid=$row_user['addressid'];
                 $this->house=trim($this->crypt->decrypt(strip2($row_user['house'])));
                 $this->address1=trim($this->crypt->decrypt(strip2($row_user['address1'])));
                 $this->address2=trim($this->crypt->decrypt(strip2($row_user['address2'])));
                 $this->address3=trim($this->crypt->decrypt(strip2($row_user['address3'])));
                 $this->town=trim($this->crypt->decrypt(strip2($row_user['town'])));
                 $this->county=trim($this->crypt->decrypt(strip2($row_user['county'])));
                 $this->country=trim($this->crypt->decrypt(strip2($row_user['country'])));
                 $this->postcode=trim($this->crypt->decrypt(strip2($row_user['postcode'])));
                 $this->email=$row_user['email'];
                 $this->webaddress=trim($this->crypt->decrypt(strip2($row_user['webaddress'])));
                 $this->description=trim($this->crypt->decrypt(strip2($row_user['description'])));
                 $this->dob=substr($dater->sqltophpdatetime( trim($this->crypt->decrypt(strip2($row_user['dob']))),1 ),0,10) ;
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];
                 $this->parentcontactid=$row_user['parentcontactid'];
                }
                          
                 $this->sex=$row_user['sex'];
                 $this->status=$row_user['status'];
                 $this->type=$row_user['type'];

                 $this->parentcontactid=$row_user['parentcontactid'];

                 $this->getcategoriesbycontact();

				 if (CHARTER) {
					$this->getrentalsbycontact();
				 }				 


            }

            return mysql_num_rows($userrec);
        }

        function getallcontacts($status,$type,$offset,$count,$order ) {
            global $connection;

            $query = "SELECT ".TBL_PREFIX."contacts.*,".
                                            TBL_PREFIX."address.id as addressid,".
                                            TBL_PREFIX."address.house,".
                                            TBL_PREFIX."address.address1,".
                                            TBL_PREFIX."address.address2,".
                                            TBL_PREFIX."address.address3,".
                                            TBL_PREFIX."address.town,".
                                            TBL_PREFIX."address.county,".
                                            TBL_PREFIX."address.country,".
                                            TBL_PREFIX."address.postcode,".
                                            TBL_PREFIX."address.latitude,".
                                            TBL_PREFIX."address.longtitude".
                                            " FROM ".TBL_PREFIX."contacts  ".
                                  "LEFT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".
                                  TBL_PREFIX."contactaddresslink.contactid ".
                                  "LEFT JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".
                                  TBL_PREFIX."contactaddresslink.addressid ";

//                $query = "SELECT * FROM ".TBL_PREFIX."contacts";
                if ($status>" ") {
                   $query=$query.' where status="$status"';
                }
                if ($type>" ") {
                   $query=$query.' where (type REGEXP \''.$type.'\'>0)';
                }

                $query = $query.$order;

                if ($offset>-1) {
                   $query = $query.' LIMIT '.$offset.','.$count;
                }
             
            $contacts = dosql($query);
            return $contacts;
        }

        function setsortorder($o) {
                global $connection;
                global $encrypted;

                 $crypt=new crypt();

                $query ="SELECT * FROM ".TBL_PREFIX."contacts";
                $contacts = dosql($query);
                while  ($row = mysql_fetch_assoc($contacts) ) {
                  $query1 = "UPDATE ".TBL_PREFIX."contacts set SORTORDER= ";

                        if ($o==1) {

                          if ($encrypted==true) {
                                  $query1=$query1.
                                          trim($crypt->decrypt(strip2($row['lastname'])) ).
                                          trim($crypt->decrypt(strip2($row['firstname']))).
                                          trim($crypt->decrypt(strip2($row['initials']))).
                                          "' WHERE ID='".$row['id']."'";
                          } else {
                                  $query1=$query1.$row['lastname'].$row['initials'].$row['firstname']."' WHERE ID='".$row['id']."'";
                          }
                        }
                        if ($o==2) {
                          $query1 = $query1.$row['datecreated'];
                          if ($encrypted==true) {
                                  $query1=$query1.
                                          trim($crypt->decrypt(strip2($row['lastname'])) ).
                                          trim($crypt->decrypt(strip2($row['firstname']))).
                                          trim($crypt->decrypt(strip2($row['initials']))).
                                          "' WHERE ID='".$row['id']."'";
                          } else {
                                  $query1=$query1.$row['lastname'].$row['initials'].$row['firstname']."' WHERE ID='".$row['id']."'";
                          }

                        }
                        if ($o==3) {
                          if ($encrypted==true) {
                                  $query1=$query1.
                                          trim($crypt->decrypt(strip2($row['company'])) ).
                                          trim($crypt->decrypt(strip2($row['lastnamename']))).
                                          trim($crypt->decrypt(strip2($row['firstname']))).
                                          "' WHERE ID='".$row['id']."'";
                          } else {
                                  $query1=$query1."'".addslashes($row['company']).addslashes($row['lastname']).addslashes($row['firstname'])."' WHERE ID='".$row['id']."'";
                          }

                        }

                          $contupd = dosql($query);

                }
        }


        function addtocategory($categoryid) {
           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."contactcategories (
                shopid, contactid, categoryid )
                VALUES ( %s, %s, %s ) ",
                 GetSQLValueString($this->shopid ,'text','',''),
                 GetSQLValueString($this->userid,'text','',''),
                 GetSQLValueString($categoryid,'text','','')
                );


                $contactcatins = dosql($query);

                return mysql_insert_id();

        }

        function deletefromcategory($categoryid) {
           $dater= new dater;

           if ($categoryid>0) {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."contactcategories where
                        shopid=%s and contactid=%s and categoryid=%s ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','',''),
                         GetSQLValueString($categoryid,'text','','')
                        );
           } else {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."contactcategories where
                        shopid=%s and contactid=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','','')
                        );

           }


                $contactcatdel = dosql($query);

                return $contactcatdel;

        }

        function getcategoriesbycontact() {
                global $connection;

                $query=sprintf("SELECT * from ".TBL_PREFIX."contactcategories where
                   shopid=%s and contactid=%s ",
                   GetSQLValueString($this->shopid ,'text','',''),
                   GetSQLValueString($this->userid,'text','','')
                );



                $contactcategories =  dosql($query);

                unset ($this->categories);

                while ($row=mysql_fetch_assoc($contactcategories)) {
                  $this->categories[]=$row['categoryid'];
                }

                return $contactcategories;


        }

        function getcontactsbycategory($categoryid,$offset,$perpage,$status,$ctype,$orderby,$searchstr ) {
                global $connection;
                $where = "";

                $order = "group by ".TBL_PREFIX."contacts.id order by ".$orderby;

                if ($orderby=='company') {
                   $order = "group by ".TBL_PREFIX."contacts.id order by ".TBL_PREFIX."contacts.company ";
                }
                if ($orderby=='category') {
                        $order = "group by ".TBL_PREFIX."contacts.id order by ".TBL_PREFIX."categories.orderstr,".TBL_PREFIX."contacts.company ";
                }

                $where = " where ".TBL_PREFIX."contactaddresslink.addresstype='P' ";

                if ($categoryid<1) {

                        if ($status>' ') {
                           $where = $where." and ".TBL_PREFIX."contacts.status='".$status."' ";
                        }

                        if($ctype>' ') {
                               $where = $where." and (".TBL_PREFIX."contacts.type REGEXP '".$ctype."'>0) ";
                        }


                        if ($searchstr>'') {
                           $where = $where." and (locate('".strtoupper($searchstr)."', UCASE(company))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(firstname))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(lastname))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(".TBL_PREFIX."contacts.description))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(address1))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(address2))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(town))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(county))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(telephone1))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(postcode))>0 ) ";
                        }


                        $query = sprintf("SELECT ".TBL_PREFIX."contacts.id as contactid, ".
                                                   TBL_PREFIX."contacts.firstname as firstname, ".
                                                   TBL_PREFIX."contacts.lastname as lastname, ".
                                                   TBL_PREFIX."contacts.description, ".
                                                   TBL_PREFIX."categories.*, ".
                                                    TBL_PREFIX."address.id as addressid,".
                                                    TBL_PREFIX."address.house,".
                                                    TBL_PREFIX."address.address1,".
                                                    TBL_PREFIX."address.address2,".
                                                    TBL_PREFIX."address.address3,".
                                                    TBL_PREFIX."address.town,".
                                                    TBL_PREFIX."address.county,".
                                                    TBL_PREFIX."address.country,".
                                                    TBL_PREFIX."address.postcode,".
                                                    TBL_PREFIX."address.latitude,".
                                                    TBL_PREFIX."address.longtitude".
                                                   " FROM ".TBL_PREFIX."contacts ".
                                         "  left join ".TBL_PREFIX."contactcategories on ".TBL_PREFIX."contacts.id = ".TBL_PREFIX.
                                         "contactcategories.contactid ".
                                         "  left join ".TBL_PREFIX."categories on ".TBL_PREFIX."contactcategories.categoryid = ".TBL_PREFIX."categories.id ".
                                          "  RIGHT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".TBL_PREFIX."contactaddresslink.contactid ".
                                          "  RIGHT JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid %s ".
                                         $order,$where );

                } else {
                        if ($status>' ') {
                           $where = $where." and ".TBL_PREFIX."categories.id='".$categoryid."' "." and ".TBL_PREFIX."contacts.status='".$status."' ";
                        } else {
                           $where = $where." and ".TBL_PREFIX."categories.id='".$categoryid."' ";
                           if($ctype>' ') {
                               $where = $where." and (".TBL_PREFIX."contacts.type REGEXP '".$ctype."'>0) ";
                           }
                        }

                        if ($searchstr>'') {
                           $where = $where." and (locate('".strtoupper($searchstr)."', UCASE(company))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(town))>0 ";
                           $where = $where." or locate('".strtoupper($searchstr)."', UCASE(postcode))>0 )";
                        }

                        $query = sprintf("SELECT ".TBL_PREFIX."contacts.id as contactid, ".
                                         TBL_PREFIX."categories.*, ".
                                                    TBL_PREFIX."address.id as addressid,".
                                                    TBL_PREFIX."address.house,".
                                                    TBL_PREFIX."address.address1,".
                                                    TBL_PREFIX."address.address2,".
                                                    TBL_PREFIX."address.address3,".
                                                    TBL_PREFIX."address.town,".
                                                    TBL_PREFIX."address.county,".
                                                    TBL_PREFIX."address.country,".
                                                    TBL_PREFIX."address.postcode,".
                                                    TBL_PREFIX."address.latitude,".
                                                    TBL_PREFIX."address.longtitude".
                                         " FROM ".TBL_PREFIX."contacts".
                                         " right join ".TBL_PREFIX."contactcategories on ".TBL_PREFIX."contacts.id = ".TBL_PREFIX.
                                         "contactcategories.contactid".
                                         " right join ".TBL_PREFIX."categories on ".TBL_PREFIX."contactcategories.categoryid = ".TBL_PREFIX."categories.id ".
                                          " right JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."contacts.id=".TBL_PREFIX."contactaddresslink.contactid ".
                                          " right JOIN ".TBL_PREFIX."address ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid %s %s",
                                         $where,$order );

                }

                if ($offset>-1) {
                   $query = $query.' LIMIT '.$offset.','.$perpage;
                }

                 
            $contactr = dosql($query);
            return $contactr;


        }

		function getrentalsbycontact() {
                global $connection;

                $query=sprintf("SELECT * from ".TBL_PREFIX."contactrentals where
                   shopid=%s and contactid=%s ",
                   GetSQLValueString($this->shopid ,'text','',''),
                   GetSQLValueString($this->userid,'text','','')
                );


                $contactrentals =  dosql($query);

                unset ($this->rentals);

                while ($row=mysql_fetch_assoc($contactrentals)) {
                  $this->rentals[]=$row['rentalid'];
                }
				
                return $contactrentals;		
		}
		
		function addtorental($rentalid) {

           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."contactrentals (
                shopid, contactid, rentalid )
                VALUES ( %s, %s, %s ) ",
                 GetSQLValueString($this->shopid ,'text','',''),
                 GetSQLValueString($this->userid,'text','',''),
                 GetSQLValueString($rentalid,'text','','')
                );


                $contactrenins = dosql($query);

                return mysql_insert_id();
		}

        function deletefromrental($rentalid) {
           $dater= new dater;

           if ($rentalid>0) {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."contactrentals where
                        shopid=%s and contactid=%s and rentalid=%s ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','',''),
                         GetSQLValueString($rentalid,'text','','')
                        );
           } else {
                   $query = sprintf("DELETE FROM ".TBL_PREFIX."contactrentals where
                        shopid=%s and contactid=%s  ",
                         GetSQLValueString($this->shopid ,'text','',''),
                         GetSQLValueString($this->userid,'text','','')
                        );

           }


                $contactrendel = dosql($query);

                return $contactrendel;

        }
		
        function insertcontact() {
                global $encrypted;
                global $connection;

                   $dater= new dater;

           if ($encrypted==false) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."contacts ( shopid ,username, password, title, firstname, initials,
                                                                lastname,company, telephone1,telephone2,mobile,
                                        email, webaddress, dob,sex, description,type,parentcontactid,status,datelastmod,datecreated,wholastmod )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s, %s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->username,'text','',''),
                     GetSQLValueString($this->password,'text','',''),
                     GetSQLValueString($this->title,'text','',''),
                     GetSQLValueString($this->firstname,'text','',''),
                     GetSQLValueString($this->initials,'text','',''),
                     GetSQLValueString($this->lastname,'text','',''),
                     GetSQLValueString($this->company,'text','',''),
                     GetSQLValueString($this->telephone1,'text','',''),
                     GetSQLValueString($this->telephone2,'text','',''),
                     GetSQLValueString($this->mobile,'text','',''),
                     GetSQLValueString($this->email,'text','',''),
                     GetSQLValueString($this->webaddress,'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                     GetSQLValueString($this->sex,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->type,'text','',''),
                     GetSQLValueString($this->parentcontactid,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );
           } else {

                $query = sprintf(
                                        "INSERT INTO ".TBL_PREFIX."contacts ( shopid ,username, password, title, firstname, initials, lastname,
                                        company, telephone1,telephone2,mobile,
                                        email, webaddress, dob, sex ,description,type,parentcontactid, status,datelastmod,datecreated,wholastmod )
                              VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->username,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->password),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->title),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->firstname),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->initials),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->lastname),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->company),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->telephone1),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->telephone2),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->mobile),'text','',''),
                     GetSQLValueString($this->email,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->webaddress),'text','',''),
                     GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                     GetSQLValueString($this->sex,'text','',''),
                     GetSQLValueString($this->description,'text','',''),
                     GetSQLValueString($this->type,'text','',''),
                     GetSQLValueString($this->parentcontactid,'text','',''),
                     GetSQLValueString($this->status,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                       );

           }
       
            
           $userins = dosql($query);

          $contactid=mysql_insert_id( $connection );


           if ($encrypted==false) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."address ( shopid ,address1,address2,address3,town,county,country,postcode,
                                        latitude,longtitude,datelastmod,datecreated,wholastmod  )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->address1,'text','',''),
                     GetSQLValueString($this->address2,'text','',''),
                     GetSQLValueString($this->address3,'text','',''),
                     GetSQLValueString($this->town,'text','',''),
                     GetSQLValueString($this->county,'text','',''),
                     GetSQLValueString($this->country,'text','',''),
                     GetSQLValueString($this->postcode,'text','',''),
                     GetSQLValueString($this->latitude,'text','',''),
                     GetSQLValueString($this->longtitude,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );
           } else {

               $query = sprintf("INSERT INTO ".TBL_PREFIX."address ( shopid ,address1,address2,address3,town,county,country,postcode,
                                        latitude,longtitude,datelastmod,datecreated,wholastmod  )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address1),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address2),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address3),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->town),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->county),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->country),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->postcode),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->latitude),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->longtitude),'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                       );

           }


           $userins = dosql($query);

           $addressid=mysql_insert_id( $connection );

               $query = sprintf("INSERT INTO ".TBL_PREFIX."contactaddresslink (shopid,contactid,addressid,addresstype)
                                       VALUES ( %s, %s, %s,%s )",
                                     GetSQLValueString($this->shopid,'text','',''),
                                     GetSQLValueString($contactid,'text','',''),
                                     GetSQLValueString($addressid,'text','',''),
                                     GetSQLValueString('P','text','','')
                                       );

           $userins = dosql($query);

           
          return $contactid;
        }

        function updatecontactbyid($id) {
                global $encrypted;
                global $connection;

                $dater= new dater;
            
                if ($encrypted==false) {
                    $query = sprintf("UPDATE ".TBL_PREFIX."contacts set username=%s, password=%s,title=%s,firstname=%s,initials=%s,
                                          lastname=%s, company=%s,
                                          telephone1=%s,telephone2=%s,mobile=%s,
                                          email=%s,webaddress=%s,dob=%s,sex=%s,description=%s,type=%s,parentcontactid=%s,status=%s,datelastmod=%s where id=%s",
                           GetSQLValueString($this->username,'text','',''),
                           GetSQLValueString($this->password,'text','',''),
                           GetSQLValueString($this->title,'text','',''),
                           GetSQLValueString($this->firstname,'text','',''),
                           GetSQLValueString($this->initials,'text','',''),
                           GetSQLValueString($this->lastname,'text','',''),
                           GetSQLValueString($this->company,'text','',''),
                           GetSQLValueString($this->telephone1,'text','',''),
                           GetSQLValueString($this->telephone2,'text','',''),
                           GetSQLValueString($this->mobile,'text','',''),
                           GetSQLValueString($this->email,'text','',''),
                           GetSQLValueString($this->webaddress,'text','',''),
                           GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                           GetSQLValueString($this->sex,'text','',''),
                           GetSQLValueString($this->description,'text','',''),
                           GetSQLValueString($this->type,'text','',''),
                           GetSQLValueString($this->parentcontactid,'text','',''),
                           GetSQLValueString($this->status,'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           $id );
                    } else {
                          $query = sprintf("UPDATE ".TBL_PREFIX."contacts set username=%s, password=%s,title=%s,firstname=%s,initials=%s,
                                                lastname=%s, company=%s,
                                                telephone1=%s,telephone2=%s,mobile=%s,
                                                email=%s,webaddress=%s,dob=%s,sex=%s,description=%s,type=%s,parentcontactid=%s,status=%s,datelastmod=%s where id=%s",
                           GetSQLValueString($this->username,'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->password),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->title),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->firstname),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->initials),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->lastname),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->company),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->telephone1),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->telephone2),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->mobile),'text','',''),
                           GetSQLValueString($this->email,'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->webaddress),'text','',''),
                           GetSQLValueString($dater->phptosqldate($this->dob),'date','',''),
                           GetSQLValueString($this->sex,'text','',''),
                           GetSQLValueString($this->description,'text','',''),
                           GetSQLValueString($this->type,'text','',''),
                           GetSQLValueString($this->parentcontactid,'text','',''),
                           GetSQLValueString($this->status,'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                                 $id );
                    }
                  
                    $userupd = dosql($query);

                if ($encrypted==false) {
                    $query = sprintf("UPDATE ".TBL_PREFIX."address set house=%s, address1=%s,address2=%s,address3=%s,town=%s,county=%s,country=%s,postcode=%s, latitude=%s,longtitude=%s,
                                          datelastmod=%s,wholastmod=%s where id=%s",
                           GetSQLValueString($this->house,'text','',''),
                           GetSQLValueString($this->address1,'text','',''),
                           GetSQLValueString($this->address2,'text','',''),
                           GetSQLValueString($this->address3,'text','',''),
                           GetSQLValueString($this->town,'text','',''),
                           GetSQLValueString($this->county,'text','',''),
                           GetSQLValueString($this->country,'text','',''),
                           GetSQLValueString($this->postcode,'text','',''),
                           GetSQLValueString($this->latitude,'text','',''),
                           GetSQLValueString($this->longtitude,'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           GetSQLValueString($this->wholastmod,'text','',''),
                           $this->primaryaddressid );
                    } else {
                          $query = sprintf("UPDATE ".TBL_PREFIX."address set house=%s, address1=%s,address2=%s,address3=%s,town=%s,county=%s,country=%s,postcode=%s,, latitude=%s,longtitude=%s,
                                          datelastmod=%s,wholastmod=%s where id=%s",
                           GetSQLValueString($this->crypt->encrypt($this->house),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address1),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address2),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address3),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->town),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->county),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->country),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->postcode),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->latitude),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->longtitude),'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           GetSQLValueString($this->crypt->encrypt($this->wholastmod),'text','',''),
                           $this->primaryaddressid );
                    }


                 $userupd = dosql($query);


        }

        function deletecontactbyid($id) {

               global $shopid;

               $query = sprintf("DELETE FROM ".TBL_PREFIX."contacts where
                        shopid=%s and id=%s  ",
                         GetSQLValueString($shopid ,'text','',''),
                         GetSQLValueString($id,'text','','')
                        );

                $contactcatdel = dosql($query);

               $query = sprintf("DELETE FROM ".TBL_PREFIX."contactaddresslink where
                        shopid=%s and contactid=%s  ",
                         GetSQLValueString($shopid ,'text','',''),
                         GetSQLValueString($id,'text','','')
                        );

                $contactcatdel = dosql($query);

                $query = sprintf("DELETE FROM ".TBL_PREFIX."contactcategories where
                        shopid=%s and contactid=%s  ",
                         GetSQLValueString($shopid ,'text','',''),
                         GetSQLValueString($id,'text','','')
                        );


                $contactcatdel = dosql($query);

                return $contactcatdel;
        }

 }

 class address {

        var $shopid=0;
        var $id=0;
        var $house="";
        var $address1="";
        var $address2="";
        var $address3="";
        var $town="";
        var $county="";
        var $country="";
        var $postcode="";
        var $latitude="";
        var $longtitude="";
        var $datecreated="";
        var $datelastmod="";
        var $wholastmod="";
        var $addresstype="";

         function address() {
                 $this->crypt=new crypt();
                 $this->contactid =0;

         }

         function getaddress($addressid ) {
                global $encrypted;
                global $connection;

               $query = sprintf("SELECT ".TBL_PREFIX."*".
                                             " FROM ".TBL_PREFIX."address  where id=%s",$addressid );

            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                if ($encrypted==false) {
                 $this->house=$row_user['house'];
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->country=$row_user['country'];
                 $this->postcode=$row_user['postcode'];
                } else {
                 $this->house=$this->crypt->decrypt($row_user['house']);
                 $this->address1=$this->crypt->decrypt($row_user['address1']);
                 $this->address2=$this->crypt->decrypt($row_user['address2']);
                 $this->address3=$this->crypt->decrypt($row_user['address3']);
                 $this->town=$this->crypt->decrypt($row_user['town']);
                 $this->county=$this->crypt->decrypt($row_user['county']);
                 $this->country=$this->crypt->decrypt($row_user['country']);
                 $this->postcode=$this->crypt->decrypt($row_user['postcode']);
                }


            }

            return mysql_num_rows($userrec);
         }

         function getaddressbycontact($contactid,$atype ) {
                global $encrypted;
                global $connection;

               $query = "SELECT * ".
                                             " FROM ".TBL_PREFIX."address  ".
                                  "LEFT JOIN ".TBL_PREFIX."contactaddresslink ON ".TBL_PREFIX."address.id=".TBL_PREFIX."contactaddresslink.addressid ";

                                  if ($atype>'') {
                                       $query =  $query.sprintf(" and ".TBL_PREFIX."contactaddresslink.addresstype='%s'",$atype);
                                  }

                                  $query=$query.sprintf( " WHERE ".TBL_PREFIX."address.contactid='%s' " , $contactid );



            $userrec = dosql($query);
            $row_user = mysql_fetch_assoc($userrec);

            if (mysql_num_rows($userrec)>0) {

                if ($encrypted==false) {
                 $this->address1=$row_user['address1'];
                 $this->address2=$row_user['address2'];
                 $this->address3=$row_user['address3'];
                 $this->town=$row_user['town'];
                 $this->county=$row_user['county'];
                 $this->country=$row_user['country'];
                 $this->postcode=$row_user['postcode'];
                } else {
                 $this->address1=$this->crypt->decrypt($row_user['address1']);
                 $this->address2=$this->crypt->decrypt($row_user['address2']);
                 $this->address3=$this->crypt->decrypt($row_user['address3']);
                 $this->town=$this->crypt->decrypt($row_user['town']);
                 $this->county=$this->crypt->decrypt($row_user['county']);
                 $this->country=$this->crypt->decrypt($row_user['country']);
                 $this->postcode=$this->crypt->decrypt($row_user['postcode']);
                }


            }

            return mysql_num_rows($userrec);
         }

         function insertaddress($contactid,$atype) {

            global $connection;
            global $encrypted;

            $dater = new dater();

           if ($encrypted==false) {
               $query = sprintf("INSERT INTO ".TBL_PREFIX."address ( shopid ,address1,address2,address3,town,county,country,postcode,
                                        latitude,longtitude,datelastmod,datecreated,wholastmod  )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->address1,'text','',''),
                     GetSQLValueString($this->address2,'text','',''),
                     GetSQLValueString($this->address3,'text','',''),
                     GetSQLValueString($this->town,'text','',''),
                     GetSQLValueString($this->county,'text','',''),
                     GetSQLValueString($this->country,'text','',''),
                     GetSQLValueString($this->postcode,'text','',''),
                     GetSQLValueString($this->latitude,'text','',''),
                     GetSQLValueString($this->longtitude,'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                     );
           } else {

               $query = sprintf("INSERT INTO ".TBL_PREFIX."address ( shopid ,address1,address2,address3,town,county,country,postcode,
                                        latitude,longtitude,datelastmod,datecreated,wholastmod  )
               VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s )",
                     GetSQLValueString($this->shopid,'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address1),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address2),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->address3),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->town),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->county),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->country),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->postcode),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->latitude),'text','',''),
                     GetSQLValueString($this->crypt->encrypt($this->longtitude),'text','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                     GetSQLValueString($this->wholastmod,'text','','')
                       );

           }


           $userins = dosql($query);

           $this->id=mysql_insert_id( $connection );


               $query = sprintf("INSERT INTO ".TBL_PREFIX."contactaddresslink (shopid,contactid,addressid,addresstype)
                                       VALUES ( %s, %s, %s,%s )",
                                     GetSQLValueString($this->shopid,'text','',''),
                                     GetSQLValueString($contactid,'text','',''),
                                     GetSQLValueString($this->id,'text','',''),
                                     GetSQLValueString($atype,'text','','')
                                       );

           $userins = dosql($query);
           return $this->id;
         }

         function updateaddress($contactid,$atype) {
                global $encrypted;
                global $connection;

                $dater= new dater;

                if ($encrypted==false) {
                    $query = sprintf("UPDATE ".TBL_PREFIX."address set house=%s, address1=%s,address2=%s,address3=%s,town=%s,county=%s,country=%s,postcode=%s, latitude=%s,longtitude=%s,
                                          datelastmod=%s,wholastmod=%s where id=%s",
                           GetSQLValueString($this->house,'text','',''),
                           GetSQLValueString($this->address1,'text','',''),
                           GetSQLValueString($this->address2,'text','',''),
                           GetSQLValueString($this->address3,'text','',''),
                           GetSQLValueString($this->town,'text','',''),
                           GetSQLValueString($this->county,'text','',''),
                           GetSQLValueString($this->country,'text','',''),
                           GetSQLValueString($this->postcode,'text','',''),
                           GetSQLValueString($this->latitude,'text','',''),
                           GetSQLValueString($this->longtitude,'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           GetSQLValueString($this->wholastmod,'text','',''),
                           $this->primaryaddressid );
                    } else {
                          $query = sprintf("UPDATE ".TBL_PREFIX."address set house=%s, address1=%s,address2=%s,address3=%s,town=%s,county=%s,country=%s,postcode=%s,, latitude=%s,longtitude=%s,
                                          datelastmod=%s,wholastmod=%s where id=%s",
                           GetSQLValueString($this->crypt->encrypt($this->house),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address1),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address2),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->address3),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->town),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->county),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->country),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->postcode),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->latitude),'text','',''),
                           GetSQLValueString($this->crypt->encrypt($this->longtitude),'text','',''),
                           GetSQLValueString($dater->phptosqldate(date("d/m/Y H:i")),'date','',''),
                           GetSQLValueString($this->crypt->encrypt($this->wholastmod),'text','',''),
                           $this->primaryaddressid );
                    }
                 $userupd = dosql($query);
         }

         function deleteaddress($id) {
               $query = sprintf("DELETE FROM address where id='%s'",$id);
               $userupd = dosql($query);

               $query = sprintf("DELETE FROM contactaddresslink where addressid='%s'",$id);
               $userupd = dosql($query);
         }

 }
?>
