<?php

class invoiceitem {
        var $itemid=0;
        var $itemtable="";
        var $delflag=0;


        function invoiceitem() {
                $this->itemid=0;
                $this->itemtable="";
                $this->delflag=0;
        }
}

class invoicepayment {
      var $shopid =0;
      var $id =0;
      var $invoiceid = 0;
      var $paymentdate = "";
      var $paymentamount = 0;
      var $paymentref = "";
      var $paymenttype = "";
      var $datecreated = "";
      var $dateamended = "";
      var $whoamended  = "";

      function invoicepayment() {
        $this->shopid =0;
        $this->id =0;
        $this->invoiceid = 0;
        $this->clientid = 0;
        $this->paymentdate = "";
        $this->paymentamount = 0;
        $this->paymentref = "";
        $this->paymenttype = "";
        $this->datecreated = "";
        $this->dateamended = "";
        $this->whoamended  = "";
      }
}

class invoice {


    var $shopid="";
    var $id="";
    var $clientid=-1;
    var $bookingid=-1;
    var $invoicenumber="";
    var $invoicedate="";
    var $clientref="";
    var $reference="";
    var $description="";
    var $nettamount=0;
    var $discount=0;
    var $vatrate=0;
    var $vatamount=0;
    var $grossamount=0;
    var $items = array();
    var $payments = array();
    var $datecreated="";
    var $dateamended="";
    var $whoamended="";


    function invoice() {
       $this->shopid=0;
       $this->id=0;
       $this->invoicenumber=0;
       $this->invoicedate='';
       $this->clientid="";
       $this->bookingid="";
       $this->clientref="";
       $this->reference="";
       $this->description="";
       $this->nettamount=0;
       $this->discount=0;
       $this->vatrate=0;
       $this->vatamount=0;
       $this->grossamount=0;

       $this->items = array();
       $this->payments = array();
       $this->datecreated="";
       $this->dateamended="";
       $this->whoamended="";

    }

    function createinvoicetable() {
                global $connection;
                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."invoices` (
                                  `shopid` int(11)  default '0',
                                  `id` int(11) NOT NULL auto_increment,
                                  `invoicenumber` int(11) NOT NULL  ,
                                  `invoicedate` datetime default NULL,
                                  `clientid`  int(11) NOT NULL default 0 ,
                                  `bookingid` int(11) default 0,
                                  `clientref` varchar(50) ,
                                  `reference` varchar(50) ,
                                  `description` text,
                                  `nettamount`  decimal(10,2),
                                  `discount`    decimal(10,2),
                                  `vatrate`     decimal(10,2),
                                  `datecreated` datetime default NULL,
                                  `dateamended` datetime default NULL,
                                  `whoamended` varchar(50) default NULL,
                                  PRIMARY KEY  (`ID`),
                                  UNIQUE KEY `INVNO` (`invoicenumber`)
                                ) ";

                $invrec = dosql($query);

                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."invoiceditems` (
                                  `shopid` int(11) NOT NULL,
                                  `invoiceid` int(11) NOT NULL,
                                  `itemid` int(11) NOT NULL,
                                  `itemdesc` varchar(50) NOT NULL,
                                  `itemqty` int(11) NOT NULL,
                                  `itemnett` decimal(10,2) NOT NULL,
                                  `itemvatrate` int(11) NOT NULL,
                                  `itempaid` decimal(10,2) NOT NULL,
                                  `itemstatus` varchar(10),
                                  `itemtable` varchar(50) ,
                                 PRIMARY KEY ( `shopid`, `invoiceid`, `itemid` )
                                 ) ";

                $rec = dosql($query);

                                 $query = "".
                                 "CREATE TABLE `".TBL_PREFIX."invoicepayments` (
                                        `shopid` int(11) NOT NULL,
                                        `id` int(11) NOT NULL auto_increment,
                                        `invoiceid` int(11) default 0 ,
                                        `clientid` int(11) default 0 ,
                                        `paymentdate` datetime default NULL,
                                        `paymentamount` decimal(10,2),
                                        `paymentref` varchar(50),
                                        `paymenttype` varchar(20),
                                        `datecreated` datetime default NULL,
                                        `dateamended` datetime default NULL,
                                        `whoamended` varchar(50) default NULL,
                                  PRIMARY KEY  (`ID`),
                                  UNIQUE KEY `INVOICE` (`invoiceid`)
                                ) ";

                $rec = dosql($query);




        }


        function getinvoice($invoiceid) {

            global $shopid;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoices WHERE ".TBL_PREFIX."invoices.shopid='%s' and ".TBL_PREFIX."invoices.id='%s'", $shopid, $invoiceid);

            $invoicerec = dosql($query);

            $row_invoice = mysql_fetch_assoc($invoicerec);

            $dater= new dater();


            if (mysql_num_rows($invoicerec)>0) {

                        $this->shopid=$row_invoice['shopid'];
                        $this->id=$row_invoice['id'];
                        $this->invoicenumber = $row_invoice['invoicenumber'];
                        $this->invoicedate = $dater->sqltophpdate($row_invoice['invoicedate']);
                        $this->clientid = $row_invoice['clientid'];
                        $this->bookingid = $row_invoice['bookingid'];
                        $this->clientref = $row_invoice['clientref'];
                        $this->reference = $row_invoice['reference'];
                        $this->description = $row_invoice['description'];
                        $this->nettamount = $row_invoice['nettamount'];
                        $this->discount = $row_invoice['discount'];
                        $this->vatrate = $row_invoice['vatrate'];
                        $this->vatamount=($this->nettamount-$this->discount)*$this->vatrate;
                        $this->grossamt = ($this->nettamount-$this->discount)+$this->vatamount;
                        $this->datecreated=$dater->sqltophpdate($row_invoice['datecreated']);
                        $this->dateamended=$dater->sqltophpdate($row_invoice['dateamended']);
                        $this->whoamended=$row_invoice['whoamended'];

                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoiceditems WHERE shopid='%s' and invoiceid='%s'",$shopid,$this->id);

                        $invoiceditemsrec = dosql($query);

                         unset ($this->items);
                         while  ($row_invoiceitems = mysql_fetch_assoc($invoiceditemsrec)) {
                              $item = new invoiceitem();
                              $item->itemid =  $row_invoiceitems['itemid'];
                              $item->itemtable = $row_invoiceitems['itemid'];
                              $this->items[] = $item;
                         }

                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoicepayments WHERE shopid='%s' and invoiceid='%s'",$shopid,$this->id);

                        $invoicedpaymentsrec = dosql($query);

                         unset ($this->paymentss);
                         while  ($row_invoicepayments = mysql_fetch_assoc($invoicedpaymentsrec)) {
                              $payment = new invoicepayment();

                              $payment->shopid =  $row_invoicepayments['shopid'];
                              $payment->id = $row_invoicepayments['id'];
                              $payment->invoiceid =  $row_invoicepayments['invoiceid'];
                              $payment->clientid = $row_invoicepayments['clientid'];
                              $payment->paymentdate =  $row_invoicepayments['paymentdate'];
                              $payment->paymentamount = $row_invoicepayments['paymentamount'];
                              $payment->paymentref =  $row_invoicepayments['paymentref'];
                              $payment->paymenttype = $row_invoicepayments['paymenttype'];
                              $payment->datecreated =  $row_invoicepayments['datecreated'];
                              $payment->dateamended = $row_invoicepayments['dateamended'];
                              $payment->whoamended =  $row_invoicepayments['whoamended'];

                              $this->payments[] = $payment;
                         }





            }

            return mysql_num_rows($invoicerec);

        }

        function getinvoicebyitemid($itemid) {
            global $shopid;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoices  ".
                              "LEFT JOIN ".TBL_PREFIX."invoiceditems on ".TBL_PREFIX."invoices.id=".TBL_PREFIX."invoiceditems.invoiceid ".
                              "WHERE ".TBL_PREFIX."invoiceditems.itemid='%s'",  $itemid);



          $invoicerec = dosql($query);

            $row_invoice = mysql_fetch_assoc($invoicerec);

            $dater= new dater();


            if (mysql_num_rows($invoicerec)>0) {

                        $this->shopid=$row_invoice['shopid'];
                        $this->id=$row_invoice['id'];
                        $this->invoicenumber = $row_invoice['invoicenumber'];
                        $this->invoicedate = $dater->sqltophpdate($row_invoice['invoicedate']);
                        $this->clientid = $row_invoice['clientid'];
                        $this->bookingid = $row_invoice['bookingid'];
                        $this->clientref = $row_invoice['clientref'];
                        $this->reference = $row_invoice['reference'];
                        $this->description = $row_invoice['description'];
                        $this->nettamount = $row_invoice['nettamount'];
                        $this->discount = $row_invoice['discount'];
                        $this->vatrate = $row_invoice['vatrate'];
                        $this->vatamount=($this->nettamount-$this->discount)*$this->vatrate;
                        $this->grossamt = ($this->nettamount-$this->discount)+$this->vatamount;
                        $this->datecreated=$dater->sqltophpdate($row_invoice['datecreated']);
                        $this->dateamended=$dater->sqltophpdate($row_invoice['dateamended']);
                        $this->whoamended=$row_invoice['whoamended'];

                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoiceditems WHERE shopid='%s' and invoiceid='%s'",$shopid,$this->id);

                        $invoiceditemsrec = dosql($query);

                         unset ($this->items);
                         while  ($row_invoiceitems = mysql_fetch_assoc($invoiceditemsrec)) {
                              $item = new invoiceitem();
                              $item->itemid =  $row_invoiceitems['itemid'];
                              $item->itemtable = $row_invoiceitems['itemid'];
                              $this->items[] = $item;
                         }

                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoicepayments WHERE shopid='%s' and invoiceid='%s'",$shopid,$this->id);

                        $invoicedpaymentsrec = dosql($query);

                         unset ($this->paymentss);
                         while  ($row_invoicepayments = mysql_fetch_assoc($invoicedpaymentsrec)) {
                              $payment = new invoicepayment();

                              $payment->shopid =  $row_invoicepayments['shopid'];
                              $payment->id = $row_invoicepayments['id'];
                              $payment->invoiceid =  $row_invoicepayments['invoiceid'];
                              $payment->clientid = $row_invoicepayments['clientid'];
                              $payment->paymentdate =  $row_invoicepayments['paymentdate'];
                              $payment->paymentamount = $row_invoicepayments['paymentamount'];
                              $payment->paymentref =  $row_invoicepayments['paymentref'];
                              $payment->paymenttype = $row_invoicepayments['paymenttype'];
                              $payment->datecreated =  $row_invoicepayments['datecreated'];
                              $payment->dateamended = $row_invoicepayments['dateamended'];
                              $payment->whoamended =  $row_invoicepayments['whoamended'];

                              $this->payments[] = $payment;
                         }





            }

            return mysql_num_rows($invoicerec);
        }


    function getallinvoices($shopid,$datefrom,$dateto) {
             $dater= new dater();

             $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoices where ".TBL_PREFIX."invoices.shopid='%s'",$shopid);

             if ($datefrom>"" ){
             $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoices where ".TBL_PREFIX."invoices.shopid='%s' and invoicedate>='%s' and invoicedate<='%s' "
             ,$shopid,$dater->phptosqldate($datefrom),$dater->phptosqldate($dateto) );

             }

             $invoicerec = dosql($query);

            return $invoicerec;
   }

   function getinvoicesbybookingid($shopid,$bookingid){
             $dater= new dater();

             $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoices where ".TBL_PREFIX."invoices.shopid='%s' and ".TBL_PREFIX."invoices.bookingid='%s' ",$shopid,$bookingid );
      //          echo $query;exit;
            $invoicerec = dosql($query);

            return $invoicerec;
   }

   function insertinvoice() {
           global $connection;
           global $shopid;

           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."invoices (
           shopid,invoicenumber,invoicedate,clientid,bookingid,clientref,
                   reference,description,nettamount,discount,vatrate,
                  datecreated,dateamended,whoamended )
           VALUES (  %s,  %s, %s, %s, %s, %s,%s, %s,%s,%s,%s,%s,%s,%s)",
                 GetSQLValueString($shopid,'text','',''),
                 GetSQLValueString($this->invoicenumber,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->invoicedate),'date','',''),
                 GetSQLValueString($this->clientid,'text','',''),
                 GetSQLValueString($this->bookingid,'text','',''),
                 GetSQLValueString($this->clientref,'text','',''),
                 GetSQLValueString($this->reference,'text','',''),
                 GetSQLValueString($this->description,'text','',''),
                 GetSQLValueString($this->nettamount,'text','',''),
                 GetSQLValueString($this->discount,'text','',''),
                 GetSQLValueString($this->vatrate,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->datecreated) ,'date','',''),
                 GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),
                 GetSQLValueString($this->whoamended,'text','','')
                 );

           $invins = dosql($query);

           $thisid=mysql_insert_id( $connection );

           foreach ($this->items as $key=>$value ) {
              $query = sprintf("INSERT INTO ".TBL_PREFIX."invoiceditems (
              shopid, invoiceid, itemid, itemtable ) values ( %s, %s, %s, %s ) ",
                 GetSQLValueString($shopid,'text','',''),
                 GetSQLValueString($thisid,'text','',''),
                 GetSQLValueString($value->itemid,'text','',''),
                 GetSQLValueString($value->itemtable,'text','','')
               );

                $invoiceitemins = dosql($query);

           }

           return $thisid;
    }

        function updateinvoice($id) {
           global $connection;
          $dater= new dater;
          $query = sprintf("UPDATE ".TBL_PREFIX."invoices set  invoicenumber=%s,invoicedate=%s,clientid=%s,bookingid=%s,clientref=%s,reference=%s,
                                description=%s, nettamount=%s,discount=%s, vatrate=%s,
                                dateamended=%s,whoamended=%s where id=%s",
                                                        GetSQLValueString($this->invoicenumber,'text','',''),
                                                        GetSQLValueString($dater->phptosqldate($this->invoicedate),'text','',''),
                                                        GetSQLValueString($this->clientid,'text','',''),
                                                        GetSQLValueString($this->bookingid,'text','',''),
                                                        GetSQLValueString($this->clientref,'text','',''),
                                                        GetSQLValueString($this->reference,'text','',''),
                                                        GetSQLValueString($this->description,'text','',''),
                                                        GetSQLValueString($this->nettamount,'text','',''),
                                                        GetSQLValueString($this->discount,'text','',''),
                                                        GetSQLValueString($this->vatrate,'text','',''),
                                                        GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),
                                                        GetSQLValueString($this->whoamended,'text','',''),
                                                 $id );

                 $invupd = dosql($query);

                 foreach ($this->items as $key=>$value ) {
                      if ($value->delflag) {
                              $query = sprintf("DELETE FROM ".TBL_PREFIX." WHERE shopid=%s and invoiceid=%s and itemid=%s " );
                              $invoiceitemins = dosql($query);
                      } else {
                              $query = sprintf("SELECT * FROM ".TBL_PREFIX." WHERE shopid=%s and invoiceid=%s and itemid=%s ",
                                $this->shopid, $this->invoiceid, $value->itemid );

                              $itemrec = dosql($query);
                              $row = mysql_fetch_assoc($itemrec);
                              if (mysql_num_rows($itemrec)>0) {

                              } else {
                                  $query = sprintf("INSERT INTO ".TBL_PREFIX."invoiceditems (
                                      shopid, invoiceid, itemid, itemtable ) values ( %s, %s, %s, %s ) ",
                                      GetSQLValueString($shopid,'text','',''),
                                      GetSQLValueString($thisid,'text','',''),
                                      GetSQLValueString($value->itemid,'text','',''),
                                      GetSQLValueString($value->itemtable,'text','','')
                               );

                               $invoiceitemins = dosql($query);
                              }
                        }
                }

        }

        function getnextinvoicenumber() {
                $invoicenumber=1;

                $query = "SELECT  max(invoicenumber) as invoicenumber from ".TBL_PREFIX."invoices" ;

                $invoicerec = dosql($query);
                $row_invoice = mysql_fetch_assoc($invoicerec);

                if (mysql_num_rows($invoicerec)>0) {
                    $invoicenumber = $row_invoice['invoicenumber'];
                    $invoicenumber = $invoicenumber+1;

                }

                return $invoicenumber;

        }


 }

 class invoicepayments {

     var $shopid=0;
     var $id=0;
     var $invoiceid=0;
     var $clientid=0;
     var $paymentdate='';
     var $paymentamount=0;
     var $paymentref='';
     var $paymenttype='';
     var $datecreated='';
     var $dateamended='';
     var $whoamended='';

     function payments() {
        $shopid=0;
        $id=0;
        $invoiceid=0;
        $clientid=0;
        $paymentdate='';
        $paymentamount=0;
        $paymentref='';
        $paymenttype='';
        $datecreated='';
        $dateamended='';
        $whoamended='';
     }

       function getpayment($shopid,$paymentid) {

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoicepayments WHERE ".TBL_PREFIX."invoicepayments.shopid='%s' and ".TBL_PREFIX."invoicepayments.id='%s'", $shopid, $paymentid);

            $paymentrec = dosql($query);

            $row_payment = mysql_fetch_assoc($paymentrec);

            $dater= new dater();


            if (mysql_num_rows($paymentrec)>0) {

                        $this->shopid=$row_payment['shopid'];
                        $this->id=$row_payment['id'];
                        $this->invoiceid = $row_payment['invoiceid'];
                        $this->clientid = $row_payment['clientid'];
                        $this->paymentdate = $dater->sqltophpdate($row_payment['paymentdate']);
                        $this->paymentamount = $row_payment['paymentamount'];
                        $this->paymentref = $row_payment['paymentref'];
                        $this->paymenttype = $row_paymenttype['paymenttype'];
                        $this->datecreated=$dater->sqltophpdate($row_category['datecreated']);
                        $this->dateamended=$dater->sqltophpdate($row_category['dateamended']);
                        $this->whoamended=$row_category['whoamended'];
            }

            return mysql_num_rows($paymentrec);

        }


    function getallpayments($shopid,$datefrom,$dateto) {

             $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoicepayments where ".TBL_PREFIX."invoicepayments.shopid='%s'",$shopid);

             if ($datefrom>"" ){
             $query = sprintf("SELECT * FROM ".TBL_PREFIX."invoicepayments where ".TBL_PREFIX."invoicepayments.shopid='%s' and invoicedate>=%s and invoicedate<='%s' "
             ,$shopid,$datefrom,$dateto);

             }

             $paymentrec = dosql($query);

            return $paymentrec;
   }

   function insertpayment() {
           global $connection;
           $dater= new dater;

                        $this->shopid=$row_payment['shopid'];
                        $this->id=$row_payment['id'];
                        $this->invoiceid = $row_payment['invoiceid'];
                        $this->paymentdate = $dater->sqltophpdate($row_payment['paymentdate']);
                        $this->paymentamount = $row_payment['paymentamount'];
                        $this->paymentref = $row_payment['paymentref'];
                        $this->paymenttype = $row_paymenttype['paymenttype'];
                        $this->datecreated=$dater->sqltophpdate($row_category['datecreated']);
                        $this->dateamended=$dater->sqltophpdate($row_category['dateamended']);
                        $this->whoamended=$row_category['whoamended'];

           $query = sprintf("INSERT INTO ".TBL_PREFIX."invoicepayments (
           shopid,invoiceid,clientid,paymentdate,paymentamount,paymentref,
                   paymenttype, datecreated,dateamended,whoamended )
           VALUES (  %s, %s, %s, %s, %s, %s, %s, %s,%s, %s )",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->invoiceid,'text','',''),
                 GetSQLValueString($this->clientid,'text','',''),
                 GetSQLValueString($this->paymentdate,'text','',''),
                 GetSQLValueString($this->paymentamount,'text','',''),
                 GetSQLValueString($this->paymentref,'text','',''),
                 GetSQLValueString($this->paymenttype,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->datecreated) ,'date','',''),
                 GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),
                 GetSQLValueString($this->whoamended,'text','','')
                 );

           $paymentins = dosql($query);

           $thisid=mysql_insert_id( $connection );

           return $thisid;
   }

   function updatepayment($id) {

          global $connection;
          $dater= new dater;
          $query = sprintf("UPDATE ".TBL_PREFIX."invoicepayments set shopid=%s, invoiceid=%s,clientid=%s,paymentdate=%s,paymentamount=%s,
                                paymentref=%s, paymenttype=%s, dateamended=%s,whoamended=%s where id=%s",
                                                        GetSQLValueString($this->shopid,'text','',''),
                                                        GetSQLValueString($this->invoiceid,'text','',''),
                                                        GetSQLValueString($this->clientid,'text','',''),
                                                        GetSQLValueString($dater->phptosqldate($this->paymentdate),'text','',''),
                                                        GetSQLValueString($this->paymentamount,'text','',''),
                                                        GetSQLValueString($this->paymentref,'text','',''),
                                                        GetSQLValueString($this->paymenttype,'text','',''),
                                                        GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),
                                                        GetSQLValueString($this->whoamended,'text','',''),
                                                 $id );

                 $invupd = dosql($query);
        }


 }



?>
