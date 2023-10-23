<?php

class orderitem {
        var $shopid="";
        var $id="";
        var $orderid="";
        var $title="";
        var $description="";
        var $code="";
        var $price="";
        var $quantity="";
        var $taxrate="";
        var $taxamount="";
        var $deliveryamount="";
        var $options=array();


        function getitemsbyorder($shopid,$orderid) {
                global $encrypted;
                global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."orderitems  WHERE ".TBL_PREFIX."orderitems.shopid='%s' and ".TBL_PREFIX."orderitems.id='%s' ",$shopid, $id );
            $itemrec = dosql($query);

            return $itemrec;

        }

        function getorderitem($shopid,$id) {
                global $encrypted;
                global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."orderitems  WHERE ".TBL_PREFIX."orderitems.shopid='%s' and ".TBL_PREFIX."orderitems.id='%s' ",$shopid, $id );
            $itemrec = dosql($query);
            if (mysql_num_rows($itemrec)>0) {
                    $row_user = mysql_fetch_assoc($userrec);
                    $this->shopid=$row_user['shopid'];
                    $this->id = $row_user['id'];
                    $this->orderid = $row_user['orderid'];
                    $this->title=$row_user['title'];
                    $this->description=$row_user['description'];
                    $this->code=$row_user['code'];
                    $this->price=$row_user['price'];
                    $this->quantity=$row_user['quantity'];
                    $this->taxrate=$row_user['taxrate'];
                    $this->taxamount=$row_user['taxamount'];
                    $this->deliveryamount=$row_user['deliveryamount'];

                    unset( $this->options );

                    $optionrec = itemoption::getitemoptions($shopid,$itemid);
                    if (mysql_num_rows($optionrec) >0) {
                        while ($row_option = mysql_fetch_assoc($optionrec) ) {
                           $itemoption =  new itemoption();
                           $itemoption->shopid=$row_option['shopid'];
                           $itemoption->itemid=$row_option['itemid'];
                           $itemoption->optioncode=$row_option['code'];
                           $itemoption->title=$row_option['title'];
                           $itemoption->price=$row_option['price'];
                           $this->options[] = $itemoption ;
                        }
                    }
                }
        }

        function insertorderitem() {
                global $encrypted;
                global $connection;

                $dater= new dater;
                $query = sprintf("insert into ".TBL_PREFIX."orderitems ( shopid, orderid,   title , description, code, price, quantity,
                                                           taxrate, taxamount , deliveryamount, datecreated ,dateamended, whoamended )
                                  values (%s ,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) ",
                                   GetSQLValueString($this->shopid,'text','','') ,
                                   GetSQLValueString($this->orderid,'text','',''),
                                   GetSQLValueString($this->title,'text','',''),
                                   GetSQLValueString($this->description,'text','',''),
                                   GetSQLValueString($this->code,'text','',''),
                                   GetSQLValueString($this->price,'text','',''),
                                   GetSQLValueString($this->quantity,'text','',''),
                                   GetSQLValueString($this->taxrate,'text','',''),
                                   GetSQLValueString($this->taxamount,'text','',''),
                                   GetSQLValueString($this->deliveryamount,'text','',''),
                                   GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                   GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','','')
                                  );

                                  $itemins = dosql($query);
                                  $this->id = mysql_insert_id();


        }

        function updateorderitem() {
                global $encrypted;
                global $connection;

                $dater= new dater;
                $query = sprintf("update ".TBL_PREFIX."orderitems set title=%s , description=%s, code=%s, price=%s,
                                                        quantity=%s, taxrate=%s, taxamount=%s, deliveryamount=%s,
                                                        dateamended=%s , whoamended=%s where id=%s " ,
                                   GetSQLValueString($this->title,'text','',''),
                                   GetSQLValueString($this->description,'text','',''),
                                   GetSQLValueString($this->code,'text','',''),
                                   GetSQLValueString($this->price,'double','',''),
                                   GetSQLValueString($this->quantity,'int','',''),
                                   GetSQLValueString($this->taxrate,'double','',''),
                                   GetSQLValueString($this->taxamount,'double','',''),
                                   GetSQLValueString($this->deliveryamount,'double','',''),
                                   GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','',''),
                                   $this->id ) ;

                                  $itemupd = dosql($query);

        }

}


class itemoption  {
        var $shopid="";
        var $orderid="";
        var $itemid="";
        var $id="";
        var $code="";
        var $title="";
        var $price="";
        var $whoamended="";

        function getitemoptions($shopid,$itemid ) {
                global $encrypted;
                global $connection;

                $query = sprintf("SELECT * FROM ".TBL_PREFIX."orderitemoptions WHERE ".TBL_PREFIX."orderitemoptions.shopid='%s' and ".TBL_PREFIX."orderitemoptions.itemid='%s' ",$shopid, $itemid );
                $optionrec = dosql($query);

                return $optionrec;

        }

        function getitemoption($shopid,$id ) {
                global $encrypted;
                global $connection;

                $query = sprintf("SELECT * FROM ".TBL_PREFIX."orderitemoptions WHERE ".TBL_PREFIX."orderitemoptions.shopid='%s' and ".TBL_PREFIX."orderitemoptions.id='%s' ",$shopid, $id );
                $optionrec = dosql($query);
                $row = mysql_fetch_assoc($optionrec);
                if (mysql_num_rows($optionrec)>0) {
                     $this->shopid=$row['shopid'];
                     $this->orderid=$row['orderid'];
                     $this->itemid=$row['itemid'];
                     $this->id=$row['id'];
                     $this->code=$row['code'];
                     $this->title=$row['title'];
                     $this->price=$row['price'];


                }

                return mysql_num_rows($optionrec);

        }

        function insertitemoption() {
                global $encrypted;
                global $connection;

                $dater= new dater;
                $query = sprintf("insert into ".TBL_PREFIX."orderitemoptions ( shopid, orderid, itemid , code , title , price , datecreated ,dateamended, whoamended )
                                  values (%s ,%s, %s, %s, %s, %s, %s, %s, %s) ",
                                   GetSQLValueString($this->shopid,'text','','') ,
                                   GetSQLValueString($this->orderid,'text','',''),
                                   GetSQLValueString($this->itemid,'text','',''),
                                   GetSQLValueString($this->code,'text','',''),
                                   GetSQLValueString($this->title,'text','',''),
                                   GetSQLValueString($this->price,'text','',''),
                                   GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                   GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','','')
                                  );

                                  $optionins = dosql($query);
                                  $this->id = mysql_insert_id();


        }

        function updateitemoption() {
                global $encrypted;
                global $connection;

                $dater= new dater;
                $query = sprintf("update ".TBL_PREFIX."orderitemoptions set code=%s, title=%s , price=%s, dateamended=%s , whoamended=%s
                                  where id=%s " ,
                                   GetSQLValueString($this->code,'text','',''),
                                   GetSQLValueString($this->title,'text','',''),
                                   GetSQLValueString($this->price,'text','',''),
                                   GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','',''),
                                   $this->id );

                                  $optionins = dosql($query);

        }



}

class payment {
        var $shopid="";
        var $id="";
        var $orderid="";
        var $cardtype="";
        var $cardnumber="";
        var $startdate="";
        var $enddate="";
        var $issuenumber="";
        var $securitynumber="";
        var $ipaddress="";
        var $datecreated="";
        var $dateamended="";
        var $whoamended="";




        function payment() {
            $this->shopid="";
            $this->id="";
            $this->orderid="";
            $this->cardtype="none";
            $this->cardnumber="";
            $this->startdate="";
            $this->enddate="";
            $this->issuenumber="";
            $this->securitynumber="";
            $this->ipaddress="";
            $this->datecreated="";
            $this->dateamended="";
            $this->whoamended="";

            $this->crypt = new crypt();


        }


        function getpayment($shopid,$orderid) {
                global $encrypted;
                global $connection;

                $query = sprintf("select * from ".TBL_PREFIX."payments where shopid=%s and orderid=%s", $shopid,$orderid );

                $paymentrec = dosql($query);
                if (mysql_num_rows($paymentrec)>0) {
                     $row = mysql_fetch_assoc($paymentrec);
                     if ($encrypted==false) {
                         $this->shopid=$row['shopid'];
                         $this->orderid=$row['orderid'];
                         $this->id=$row['id'];
                         $this->cardtype=$row['cardtype'];
                         $this->cardnumber=$row['cardnumber'];
                         $this->startdate=$row['startdate'];
                         $this->enddate= $row['enddate'];
                         $this->issuenumber=$row['issuenumber'];
                         $this->securitynumber= $row['securitynumber'];
                         $this->ipaddress= $row['ipaddress'];
                         $this->browser= $row['browser'];
                         $this->datecreated= $row['datecreated'];
                         $this->dateamended= $row['dateamended'];
                         $this->whoamended=  $row['whoamended'];

                     } else {

                         $this->shopid=$row['shopid'];
                         $this->orderid=$row['orderid'];
                         $this->id=$row['id'];
                         $this->cardtype=$this->crypt->decrypt(strip2($row['cardtype']));
                         $this->cardnumber=$this->crypt->decrypt(strip2($row['cardnumber']));
                         $this->startdate=$this->crypt->decrypt(strip2($row['startdate']));
                         $this->enddate= $this->crypt->decrypt(strip2($row['enddate']));
                         $this->issuenumber= $this->crypt->decrypt(strip2($row['issuenumber']));
                         $this->securitynumber= $this->crypt->decrypt(strip2($row['securitynumber']));
                         $this->ipaddress= $row['ipaddress'];
                         $this->browser= $row['browser'];
                         $this->datecreated= $row['datecreated'];
                         $this->dateamended= $row['dateamended'];
                         $this->whoamended=  $row['whoamended'];
                     }



                }

                return mysql_num_rows($paymentrec);

        }

        function insertpayment() {
                global $encrypted;
                global $connection;

                $this->crypt = new crypt();


                $dater= new dater;

                if ($encrypted==false) {
                    $query = sprintf("insert into ".TBL_PREFIX."payments ( shopid, orderid, cardtype , cardnumber , startdate , enddate ,
                                                             issuenumber, securitynumber, ipaddress, browser , datecreated, dateamended, whoamended )
                                      values (%s ,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) ",
                                       GetSQLValueString($this->shopid,'text','','') ,
                                       GetSQLValueString($this->orderid,'text','',''),
                                       GetSQLValueString($this->cardtype,'text','',''),
                                       GetSQLValueString($this->cardnumber,'text','',''),
                                       GetSQLValueString($this->cardstartdate,'text','',''),
                                       GetSQLValueString($this->enddate,'text','',''),
                                       GetSQLValueString($this->issuenumber,'text','',''),
                                       GetSQLValueString($this->securitynumber,'text','',''),
                                       GetSQLValueString($this->ipaddress,'text','',''),
                                       GetSQLValueString($this->browser,'text','',''),
                                       GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                       GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                       GetSQLValueString($this->whoamended,'text','','')
                                      );
                } else {
                    $query = sprintf("insert into ".TBL_PREFIX."payments ( shopid, orderid, cardtype , cardnumber , startdate , enddate ,
                                                             issuenumber, securitynumber, ipaddress, browser , datecreated, dateamended, whoamended )
                                      values (%s ,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) ",
                                       GetSQLValueString($this->shopid,'text','','') ,
                                       GetSQLValueString($this->orderid,'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->cardtype),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->cardnumber),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->cardstartdate),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->enddate),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->issuenumber),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->securitynumber),'text','',''),
                                       GetSQLValueString($this->ipaddress,'text','',''),
                                       GetSQLValueString($this->browser,'text','',''),
                                       GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                       GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                       GetSQLValueString($this->whoamended,'text','','')
                                      );

                }

                                  $paymentins = dosql($query);
                                  $this->id = mysql_insert_id();

                                  return $this->id;

        }

        function updatepayment() {
                global $encrypted;
                global $connection;

                $this->crypt = new crypt();


                $dater= new dater;

                if ($encrypted==false) {
                        $query = sprintf("update ".TBL_PREFIX."payments set cardtype=%s, cardnumber=%s, startdate=%s, enddate=%s, issuenumber=%s,
                                                      securitynumber=%s, ipaddress=%s,browser=%s,  dateamended=%s, whoamnded=%s
                                                      where id=%s ",
                                       GetSQLValueString($this->cardtype,'text','',''),
                                       GetSQLValueString($this->cardnumber,'text','',''),
                                       GetSQLValueString($this->cardstartdate,'text','',''),
                                       GetSQLValueString($this->enddate,'text','',''),
                                       GetSQLValueString($this->issuenumber,'text','',''),
                                       GetSQLValueString($this->securitynumber,'text','',''),
                                       GetSQLValueString($this->ipaddress,'text','',''),
                                       GetSQLValueString($this->browser,'text','',''),
                                       GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                       GetSQLValueString($this->whoamended,'text','','') ,
                                       $this->id

                                       );
                } else {
                        $query = sprintf("update ".TBL_PREFIX."payments set cardtype=%s, cardnumber=%s, startdate=%s, enddate=%s, issuenumber=%s,
                                                      securitynumber=%s, ipaddress=%s,browser=%s,  dateamended=%s, whoamnded=%s
                                                      where id=%s",
                                       GetSQLValueString($this->crypt->encrypt($this->cardtype),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->cardnumber),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->cardstartdate),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->enddate),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->issuenumber),'text','',''),
                                       GetSQLValueString($this->crypt->encrypt($this->securitynumber),'text','',''),
                                       GetSQLValueString($this->ipaddress,'text','',''),
                                       GetSQLValueString($this->browser,'text','',''),
                                       GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                       GetSQLValueString($this->whoamended,'text','',''),
                                       $this->id);
                }

        }

        function deletepayment($orderid) {
                global $encrypted;
                global $connection;

                $query=sprintf("delete from ".TBL_PREFIX."payments where orderid=%s",$orderid);
                $orderins = dosql($query);

        }

}

class orderhistory {
        var $shopid="";
        var $id="";
        var $date="";
        var $title="";
        var $notes="";
        var $ipadress="";
        var $who="";
        var $lastmod="";
        var $created="";

}



class order {
        var $shopid="";
        var $id="";
        var $contactid="";
        var $deliverycontactid="";
        var $orderreference="";
        var $deliveryreference="";
        var $orderdatetime="";
        var $deliverydatetime="";
        var $ipaddress="";
        var $netamount="";
        var $currency="";
        var $deliveryamount="";
        var $taxamount="";
        var $grossamount="";
        var $paymentid="";
        var $status="";
        var $description="";
        var $notes="";
        var $deliverynotes="";
        var $orderitems=array();
        var $orderpayments=array();


        function createorder($connection) {
                $query="drop table if exists ".TBL_PREFIX."orders; ";
                $userrec = dosql($query);
                $query="drop table if exists ".TBL_PREFIX."orderitems; ";
                $userrec = dosql($query);
                $query="drop table if exists ".TBL_PREFIX."orderitemoptions; ";
                $userrec = dosql($query);
                $query="drop table if exists ".TBL_PREFIX."payments; ";
                $userrec = dosql($query);
                $query="drop table if exists ".TBL_PREFIX."orderhistory; ";
                $userrec = dosql($query);



                $query = "".
                "CREATE TABLE `".TBL_PREFIX."orders` (".
                "  `shopid` int(11)  NOT NULL default '0' ,".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `contactid` int(11) NOT NULL default '0', ".
                "  `deliverycontactid` int(11) default '0' ,".
                "  `orderreference` varchar(12)  default ' ',".
                "  `deliveryreference` varchar(12)  default '  ' ,".
                "  `orderdate` datetime default '0000-00-00 00:00:00', ".
                "  `deliverydate` datetime default '0000-00-00 00:00:00', ".
                "  `ipaddress` varchar(50) default ' ',".
                "  `netamount` float(11,2) default '0',".
                "  `currency` varchar(3) default 'GBP' ,".
                "  `deliveryamount` float(11,2) default '0',".
                "  `taxamount` float(11,2) default '0',".
                "  `grossamount` float(11,2) default '0',".
                "  `status` varchar(10) default 'New',".
                "  `description` text  ,".
                "  `notes` text  ,".
                "  `deliverynotes` text, ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateamended` datetime default '0000-00-00 00:00:00',".
                "  `whoamended` varchar(20) default '',".
                "  PRIMARY KEY  (`id`),".
                "  KEY `order` ( `shopid`, `id` ),".
                "  KEY `orderstatus` ( `shopid`, `status` ),".
                "  KEY `ordercontact` (  `contactid` )".
                ") ";

                $userrec = dosql($query);

                $query = ""."CREATE INDEX ".TBL_PREFIX."IDX_orderreference ON ".TBL_PREFIX."orders ( orderreference ); ";
                $userrec = dosql($query);


                $query = "".
                "CREATE TABLE `".TBL_PREFIX."orderitems` (".
                "  `shopid` int(11) NOT NULL default '0' , ".
                "  `orderid` int(11) NOT NULL , ".
                "  `id` int(11) NOT NULL auto_increment ,".
                "  `title` varchar(50) , ".
                "  `description` varchar(100) , ".
                "  `code` varchar(25) , ".
                "  `price` float(11,2) , ".
                "  `quantity` int(11) , ".
                "  `taxrate` float(11,2) , ".
                "  `taxamount` float(11,2) , ".
                "  `deliveryamount` float(11,2) , ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateamended` datetime default '0000-00-00 00:00:00',".
                "  `whoamended` varchar(20) default '',".
                "  PRIMARY KEY ( `id` ), ".
                "  KEY `orderitem` ( `shopid` ,`orderid` ,`id` )  ".
                " ) ";

                $userrec = dosql($query);

               $query = "".
                "CREATE TABLE `".TBL_PREFIX."orderitemoptions` (".
                "  `shopid` int(11) NOT NULL default '0' , ".
                "  `orderid` int(11) NOT NULL , ".
                "  `itemid` int(11) NOT NULL , ".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `code` varchar(25) , ".
                "  `title` varchar(100) , ".
                "  `price` float(11,2) , ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateamended` datetime default '0000-00-00 00:00:00',".
                "  `whoamended` varchar(20) default '',".
                "  PRIMARY KEY (`id`) , ".
                "  KEY itemoption (`shopid`, `orderid`,`itemid`, `id` )".
                ") ";


                $userrec = dosql($query);

               $query = "".
                "CREATE TABLE `".TBL_PREFIX."payments` (".
                "  `shopid` int(11) NOT NULL default '0' , ".
                "  `orderid` int(11) NOT NULL , ".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `cardtype` varchar(100) , ".
                "  `cardnumber` varchar(80) , ".
                "  `startdate` varchar(60) , ".
                "  `enddate` varchar(60) , ".
                "  `issuenumber` varchar(60) , ".
                "  `securitynumber` varchar(60) , ".
                "  `ipaddress` varchar(60) , ".
                "  `browser` varchar(40) , ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateamended` datetime default '0000-00-00 00:00:00',".
                "  `whoamended` varchar(20) default '',".
                "  PRIMARY KEY (`id`) , ".
                "  KEY orderpayments  (`shopid`, `orderid`, `id` )".
                ") ";

                $userrec = dosql($query);

               $query = "".
                "CREATE TABLE `".TBL_PREFIX."orderhistory` (".
                "  `shopid` int(11) NOT NULL default '0' , ".
                "  `orderid` int(11) NOT NULL , ".
                "  `id` int(11) NOT NULL auto_increment,".
                "  `date` datetime default '0000-00-00 00:00:00', ".
                "  `title` varchar(50) , ".
                "  `notes` text , ".
                "  `ipaddress` varchar(50) , ".
                "  `datecreated` datetime default '0000-00-00 00:00:00',".
                "  `dateamended` datetime default '0000-00-00 00:00:00',".
                "  `whoamended` varchar(20) default '',".
                "  PRIMARY KEY (`id`) , ".
                "  KEY orderhistory  (`shopid`, `orderid`, `id` )".
                ") ";

                $userrec = dosql($query);

        }



        function order() {
                $this->shopid="";
                $this->id="";
                $this->contactid="";
                $this->deliverycontactid="";
                $this->orderreference="";
                $this->deliveryreference="";
                $this->orderdatetime=date('d/m/y H:i:s');
                $this->deliverydatetime="";
                $this->ipaddress="";
                $this->netamount="";
                $this->currency="";
                $this->deliveryamount="";
                $this->taxamount="";
                $this->grossamount="";
                $this->status="";
                $this->description="";
                $this->notes="";
                $this->deliverynotes="";
                $this->paymentid="";

                 $this->crypt=new crypt();

        }


        function getorder($shopid,$id) {

                global $encrypted;
                global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."orders  WHERE ".TBL_PREFIX."orders.id='%s' ", $id );

            if ($shopid>0) {
             $query = $queryorder.sprintf(" and ".TBL_PREFIX."orders.shopid='%s'",$shopid);
            }

            $orderrec = dosql($query);
            $row_order = mysql_fetch_assoc($orderrec);

            if (mysql_num_rows($orderrec)>0) {

                        $dater = new dater();

                        $this->shopid=$row_order['shopid'];
                        $this->id=$row_order['id'];
                        $this->contactid=$row_order['contactid'];
                        $this->deliverycontactid=$row_order['deliverycontactid'];

                        $this->orderreference=$row_order['orderreference'];
                        $this->deliveryreference=$row_order['deliveryreference'];

                        $this->orderdatetime=$dater->sqltophpdate( $row_order['orderdate'] );
                        $this->deliverydatetime=$dater->sqltophpdate( $row_order['deliverydate'] );
                        $this->ipaddress=$row_order['ipaddress'];
                        $this->netamount=$row_order['netamount'];
                        $this->currency=$row_order['currency'];
                        $this->deliveryamount=$row_order['deliveryamount'];
                        $this->taxamount=$row_order['taxamount'];
                        $this->grossamount=$row_order['grossamount'];
                        $this->status=$row_order['status'];
                        $this->description=$row_order['description'];
                        $this->notes=$row_order['notes'];
                        $this->paymentid=$row_order['paymentid'];
            }


            return mysql_num_rows($orderrec);
        }

        function getordersbystatus($shopid,$status,$s,$f,$o ) {
                global $encrypted;
                global $connection;

                if ($s<'') {
                    $s=0;
                }
                if ($f<'') {
                   $f=999;
                }

                if ($shopid<1) {
                    $query = sprintf("SELECT * FROM orders  WHERE orders.status='%s' ORDER BY %s LIMIT %s,%s;", $status,$o, $s,$f );
                        } else {
                    $query = sprintf("SELECT * FROM orders  WHERE orders.shopid='%s' and orders.status='%s'  ORDER BY %s LIMIT %s,%s; ",$shopid, $status,$o,$s,$f );
                }
                $orderrec = dosql($query);

            return $orderrec;


        }

        function deleteorder() {
                global $encrypted;
                global $connection;

                $query=sprintf("delete from ".TBL_PREFIX."orderitems where orderid=%s",$this->id);
                $orderins = dosql($query);

                $query=sprintf("delete from ".TBL_PREFIX."orderitemoptions where orderid=%s",$this->id);
                $orderins = dosql($query);

                $query=sprintf("delete from ".TBL_PREFIX."payments where orderid=%s",$this->id);
                $orderins = dosql($query);

                $query=sprintf("delete from ".TBL_PREFIX."orderhistory where orderid=%s",$this->id);
                $orderins = dosql($query);

                $query=sprintf("delete from ".TBL_PREFIX."orders where id=%s",$this->id);
                $orderins = dosql($query);

        }


        function insertorder() {
                global $encrypted;
                global $connection;

                $dater= new dater;


                $query=sprintf("insert into ".TBL_PREFIX."orders ( shopid, contactid, deliverycontactid, orderreference,
                                                   deliveryreference, orderdate, deliverydate, ipaddress, netamount, currency,
                                                   deliveryamount, taxamount, grossamount, status, description, notes, deliverynotes,
                                                   datecreated, dateamended, whoamended) values
                                (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s )",
                                   GetSQLValueString($this->shopid,'text','',''),
                                   GetSQLValueString($this->contactid,'text',' ',' '),
                                   GetSQLValueString($this->deliverycontactid,'text',' ',' '),
                                   GetSQLValueString($this->orderreference,'text',' ',' '),
                                   GetSQLValueString($this->deliveryreference,'text','',''),
                                   GetSQLValueString($dater->phptosqldate( $this->orderdatetime )  ,'date','',''),
                                   GetSQLValueString($dater->phptosqldate( $this->deliverydatetime )  ,'date','',''),
                                   GetSQLValueString($this->ipaddress,'text',' ',' '),
                                   GetSQLValueString($this->netamount,'double','0','0'),
                                   GetSQLValueString($this->currency,'text','GBP','GBP'),
                                   GetSQLValueString($this->deliveryamount,'double','0','0'),
                                   GetSQLValueString($this->taxamount,'double','0','0'),
                                   GetSQLValueString($this->grossamount,'double','0','0'),
                                   GetSQLValueString($this->status,'text',' ',' '),
                                   GetSQLValueString($this->description,'text',' ',' '),
                                   GetSQLValueString($this->notes,'text',' ',' '),
                                   GetSQLValueString($this->deliverynotes,'text',' ',' '),
                                   GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                   GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','','')
                                );




           $orderins = dosql($query);

          $this->id=mysql_insert_id();

           return $this->id;
        }

        function updateorderbyid($id) {
                global $encrypted;
                global $connection;

                $dater= new dater;

                $query=sprintf("update ".TBL_PREFIX."orders set contactid=%s, deliverycontactid=%s, orderreference=%s, deliveryreference=%s,
                                              orderdate=%s, deliverydate=%s, ipaddress=%s,
                                              netamount=%s, currency=%s,
                                              deliveryamount=%s, taxamount=%s, grossamount=%s, status=%s, description=%s, notes=%s, deliverynotes=%s,
                                                   dateamended=%s, whoamended=%s where id=%s ",
                                   GetSQLValueString($this->contactid,'text','',''),
                                   GetSQLValueString($this->deliverycontactid,'text','',''),
                                   GetSQLValueString($this->orderreference,'text','',''),
                                   GetSQLValueString($this->deliveryreference,'text','',''),
                                   GetSQLValueString($dater->phptosqldate( $this->orderdatetime )  ,'date','',''),
                                   GetSQLValueString($dater->phptosqldate( $this->deliverydatetime )  ,'date','',''),
                                   GetSQLValueString($this->ipaddress,'text','',''),
                                   GetSQLValueString($this->netamount,'double','',''),
                                   GetSQLValueString($this->currency,'text','',''),
                                   GetSQLValueString($this->deliveryamount,'double','',''),
                                   GetSQLValueString($this->taxamount,'double','',''),
                                   GetSQLValueString($this->grossamount,'double','',''),
                                   GetSQLValueString($this->status,'text','',''),
                                   GetSQLValueString($this->description,'text','',''),
                                   GetSQLValueString($this->notes,'text','',''),
                                   GetSQLValueString($this->deliverynotes,'text','',''),
                                   GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                                   GetSQLValueString($this->whoamended,'text','',''),
                                   $this->id
                                );

           $orderupd = dosql($query);

        }

 }
?>
