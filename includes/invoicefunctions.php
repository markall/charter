<?php
    function insertinvoice() {
        global $shopid;

   $invoice = new invoice();


        if (isset($_REQUEST['invoicenumber'])) {
          $invoice->invoicenumber = $_REQUEST['invoicenumber'];
        }

        if (isset($_REQUEST['invoicecontactid'])) {
          $invoice->clientid = $_REQUEST['invoicecontactid'];
        }

        if (isset($_REQUEST['invoicebookingid'])) {
          $invoice->bookingid = $_REQUEST['invoicebookingid'];
        }

        if (isset($_REQUEST['invoicedate'])) {
          $invoice->invoicedate = $_REQUEST['invoicedate'];
        }
        if (isset($_REQUEST['invoiceclientref'])) {
          $invoice->clientref = $_REQUEST['invoiceclientref'];
        }
        if (isset($_REQUEST['invoicereference'])) {
          $invoice->reference = $_REQUEST['invoicereference'];
        }

        if (isset($_REQUEST['invoicedescription'])) {
         $invoice->description = $_REQUEST['invoicedescription'];
        }

        if (isset($_REQUEST['nettamount'])) {
          $invoice->nettamount = $_REQUEST['nettamount'];
        }
        if (isset($_REQUEST['vatrate'])) {
          $invoice->vatrate = $_REQUEST['vatrate'];
        }
        if (isset($_REQUEST['vatamount'])) {
          $invoice->vatamount = $_REQUEST['vatamount'];
        }

        if (isset($_REQUEST['discount'])) {
          $invoice->discount = $_REQUEST['discount'];
        }



        if (CHARTER==1) {
          // BOOKING ID

                  if (isset($_REQUEST['invoicebookingid'])) {
                    $bookingid = $_REQUEST['invoicebookingid'];
                    $invoiceitem = new invoiceitem();
                    $invoiceitem->itemid=$bookingid;
                    $invoiceitem->itemtable="bookings";
                    $invoice->items[] = $invoiceitem;

//                    include_once( INCLUDES_FOLDER."charterfunctions.php" );
                    include_once( INCLUDES_FOLDER."db_bookings.php" );

                    $bookingobj = new booking();

                    $bookingobj->getbooking( $bookingid );
                    $bookingobj->status="invoiced";
                    $bookingobj->updatebooking($bookingid) ;



                  }



        }

// PAYMENTS
        if (isset($_REQUEST['paid'])) {
          $invoice->paid = $_REQUEST['paid'];
        }

// CONTACT

        if (isset($_REQUEST['invoicecontacttitle'])) {
          $invoice_contacttitle = $_REQUEST['invoicecontacttitle'];
        }
        if (isset($_REQUEST['invoicecontactfirstname'])) {
          $invoice_contactfirstname = $_REQUEST['invoicecontactfirstname'];
        }
        if (isset($_REQUEST['invoicecontactlastname'])) {
          $invoice_ = $_REQUEST['invoicecontactlastname'];
        }
        if (isset($_REQUEST['invoicecontactcompany'])) {
          $invoice_ = $_REQUEST['invoicecontactcompany'];
        }
        if (isset($_REQUEST['invoicecontactaddress1'])) {
          $invoice_contactaddress1 = $_REQUEST['invoicecontactaddress1'];
        }
        if (isset($_REQUEST['invoicecontactaddress2'])) {
          $invoice_contactaddress2 = $_REQUEST['invoicecontactaddress2'];
        }
        if (isset($_REQUEST['invoicecontacttown'])) {
          $invoice_contacttown = $_REQUEST['invoicecontacttown'];
        }
        if (isset($_REQUEST['invoicecontactpostcode'])) {
          $invoice_contactpostcode = $_REQUEST['invoicecontactpostcode'];
        }

      if (strlen($invoice->invoicenumber)<1)  {
          $invoice->invoicenumber = $invoice->getnextinvoicenumber();
      }


        $invoiceid =  $invoice->insertinvoice();

        return $invoiceid;

    }

    function showinvoice($s,$id) {

        $invoice = new invoice();

        $invoice->getinvoice($id);


        $s=str_replace('<%invoiceid%>',$invoice->id,$s);
        $s=str_replace('<%invoicenumber%>',sprintf('%06d',$invoice->invoicenumber),$s);
        $s=str_replace('<%invoiceclientid%>',$invoice->clientid,$s);

        $invoiceclient = new contact();
        $invoiceclientdetail = new contactdetail();

        $invoiceclient->getcontactbyid($invoice->clientid);
        $invoiceclientdetail->getcontactdetailbyid($invoice->clientid);
        $s=setcontact($s,$invoiceclient,$invoiceclientdetail,"invoiceclient_" );

        $s=str_replace('<%invoicedate%>',$invoice->invoicedate,$s);
        $s=str_replace('<%invoiceclientref%>',$invoice->clientref,$s);
        $s=str_replace('<%invoicereference%>',$invoice->reference,$s);
        $s=str_replace('<%invoicedescription%>',$invoice->description,$s);
        $s=str_replace('<%invoicenettamount%>',sprintf('%01.2f',$invoice->nettamount),$s);
        $s=str_replace('<%invoicevatrate%>',sprintf('%01.2f',$invoice->vatrate),$s);
        $s=str_replace('<%invoicevatamount%>',sprintf('%01.2f',$invoice->vatamount),$s);
        $s=str_replace('<%invoicegrossamount%>',sprintf('%01.2f',$invoice->grossamt),$s);
        $s=str_replace('<%invoicediscount%>',sprintf('%01.2f',$invoice->discount),$s);

        return $s;
    }

function showbookinginvoices($bookingid) {
        global $shopid;
        $invoice = new invoice();

        $invoicerecs = $invoice->getinvoicesbybookingid($shopid,$bookingid);

        $templ = "<%invoicenumber%> <%invoicedate%>  n: &pound;<%invoicenettamount%> v: &pound;<%invoicevatamount%> g: &pound;<%invoicegrossamount%> <br/>";
        $st = "";
        while ($row = mysql_fetch_assoc( $invoicerecs ) ) {
        
          $st=$st.showinvoice($templ,$row['id']);

        }

        return $st;


}

 function exportinvoices($s) {

        global $shopid;

        $datefrom='01/01/1901';
        $dateto=='01/01/2100';

        $dater = new dater();

        if (isset($_REQUEST['datefrom'])) {
                $datefrom = $_REQUEST['datefrom'];
        }

        if (isset($_REQUEST['dateto'])) {
                $dateto = $_REQUEST['dateto'];
        }

/*      QUICK BOOKS INVOICE
NAME    (Required) The name of the invoice item.
TIMESTAMP       (Export files only) A unique number that identifies the company file from which you exported the Item list.
REFNUM  (Export files only) A unique number that identifies an entry in the list.
INVITEMTYPE     (Required) Indicates the type of invoice item. If you are creating an import file, use one of these keywords to indicate the item type.
DISC    Discount item
GRP     Group item (groups several invoice items into a single item)
STOCK   Stock part item
OTHC    Other charge item
PART    Non-stock part item
PMT     Payment item
SERV    Service item
SUBT    Subtotal item
DESC    A description of the item as you want it to appear in the Description column on invoices, credit memos, and sales receipts.
PURCHASEDESC    (Stock part items only) A description of the item as you want it to appear on purchase orders.
ACCNT   (Required) The name of the income account you use to track sales of the item. The type of this account should be INC.
ASSETACCNT      (Stock part items only) The name of the asset account you use to track the value of your stock. The type of this account should be OASSET.
COGSACCNT       (Stock part items only) The name of the account you use to track the cost of your sales. The type of this account should be COGS.
QNTY
PRICE   (All item types except group, payment, and subtotal) The rate or price you charge for the item. If you are creating an import file, add a percent sign (%) if the amount is a percentage.
COST    (Stock part items only) The unit cost of the item.
VATCODE The VAT code assigned to the item.
PURCHVATCODE    The VAT code assigned when purchasing the item.
PAYMETH (Payment items only) The payment method customers use (cheque, Visa, etc.).
PREFVEND        (Stock part items only) The name of the supplier from whom you normally purchase the item.
REORDERPOINT    (Stock part items only) The minimum quantity you want to keep in stock at any given time. When your stock reaches this level, QuickBooks informs you that it is time to reorder the item.
EXTRA   Adds additional information about the invoice item. These keywords can appear in the EXTRA field:
REXPGROUP       Indicates that the item is a group of reimbursable expenses that you included on the invoice.
REXPSUBTOT      Indicates that the item is the subtotal amount for a group of reimbursable expenses you included on the invoice.
CUSTFLD1
CUSTFLD2
.
.CUSTFLD5       The custom field entries for the item (you can have up to five custom field entries). Custom fields let you track special information about the item, such as colour, unit or measure, or size. What you use custom fields for is entirely up to you.
DEP_TYPE        (Payment items only) Indicates how you want QuickBooks to handle deposits of the payment item.
1       You want QuickBooks to deposit the payment in the bank account of your choice when you record the payment. The payment does not go into the Undeposited Funds account, and you do not have to use the Make Deposits window to deposit the payment.
0       You want QuickBooks to "hold" all the payments in a special account named Undeposited Funds. To move the payments to a bank account, you must use the Make Deposits window to group the payments into one deposit.
ISPASSEDTHRU
(QuickBooks Pro)        (Service, non-stock part, and other charge items) Indicates whether you pass the item through as an expense to the customer.
Y       Yes. You pass the item through as an expense.
N       No. You do not pass the item through as an expense.
 */

        $templatef = TEMPLATE_FOLDER."export_invoiceheader.txt";

        if (file_exists( $templatef ) ) {
           $exportinvoice=getfile($templatef);  // the template
        } else {
           $exportinvoice="<%invoicelines%>";
        }

        $templatef = TEMPLATE_FOLDER."export_invoiceline.txt";

        if (file_exists( $templatef ) ) {
           $exportline=getfile($templatef);  // the template
        } else {
           $exportline="\"<%invoicenumber%>\",\"SERV\",\"<%invoicedate%>\",\"<%invoicedescription1%>\", <%customeraccount%>, <%invoicenettamount%>,<%invoiceqty%>, <%invoicevatrate%>,<%invoiceprice%> ";
        }



        $res = invoice::getallinvoices($shopid, $datefrom,$dateto);

        $lines = "";
        $idx=0;
        while ($row = mysql_fetch_assoc($res) ) {
         //  var_dump($row);
           $t=str_replace("<%invoiceshopid%>",$row['shopid'],$exportline);
           $t=str_replace("<%invoiceid%>",$row['id'],$t);
           $t=str_replace("<%invoicenumber%>",$row['invoicenumber'],$t);
           $t=str_replace("<%invoicedate%>",$dater->sqltophpdate($row['invoicedate']),$t);
           $t=str_replace("<%invoicedescription%>",$row['description'],$t);
           $t=str_replace("<%invoiceclientid%>",$row['clientid'],$t);
           $t=str_replace("<%invoiceclientref%>",$row['clientref'],$t);
           $t=str_replace("<%invoicereference%>",$row['reference'],$t);
           $t=str_replace("<%invoiceqty%>",$row['qty']." ",$t);
           $t=str_replace("<%invoicenettamount%>",$row['nettamount'],$t);
           $t=str_replace("<%invoicediscount%>",$row['discount'],$t);
           $t=str_replace("<%invoicevatrate%>",$row['vatrate'],$t);

           $vatamount=($row['nettamount']-$row['discount'])*$row['vatrate'];
           $grossamt = ($row['nettamount']-$row['discount'])+$vatamount;
           $t=str_replace("<%invoiceprice%>",$grossamount,$t);

           $lines = $lines.$t."\n\r";


        }

        $s = str_replace("<%invoicelines%>",$lines,$exportinvoice);

        return $s;

    }
?>
