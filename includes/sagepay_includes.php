<?
/**************************************************************************************************
* Form PHP Kit Includes File
**************************************************************************************************

**************************************************************************************************
* Change history
* ==============
*
* 10/02/2009 - Simon Wolfe - Updated for protocol 2.23
* 18/10/2007 - Nick Selby - New kit version
**************************************************************************************************
* Description
* ===========
*
* Page with no visible content, but defines the constants and functions used in other pages in the
* kit.  It can also be used to open database connections to the database and defines record sets for later use.
* It is included at the top of every other page in the kit and is paried with the closedown scipt.
**************************************************************************************************/

ob_start();

/**************************************************************************************************
* Values for you to update
**************************************************************************************************/



$strVirtualDir="pspfk2"; //Change if you have created a Virtual Directory in IIS with a different name


/**************************************************************************************************
* Global Definitions for this site
**************************************************************************************************/

$strProtocol="2.23";


class sageproduct {

    var $description='';
    var $quantity='';
    var $nett='';
    var $tax='';
    var $gross='';
    var $linetotal='';

}

class sagepay {
          var $PurchaseURL="";
          var $ConnectTo="LIVE";    //Set to SIMULATOR for the Simulator expert system, TEST for the Test Server and LIVE in the live environment
#          var $Vendor="merchantmakers";
#          var $EncryptionPassword="LYMuJR1mygu8VpDl";  /** Set this value to the XOR Encryption password assigned to you by Sage Pay **/
          var $Vendor="wokinghamphotog";
          var $EncryptionPassword="qkM53QrRbXRGw4JS";  /** Set this value to the XOR Encryption password assigned to you by Sage Pay **/

          var $VPSProtocol="";
          var $TxType="";

          var $Crypt="";
          var $ReferrerID="";      // Optional: If you are a Sage Pay Partner and wish to flag the transactions with your unique partner id, it should be passed here

      //**** Next lot get added to crypt
          var $VendorTxCode="";   // this needs to be unique     VendorTxCode
          var $Amount="";
          var $Currency="GBP";
          var $Description="Wokingham Photographic The Accessory Specialist";
          var $SuccessURL="";
          var $FailureURL="";
          var $o_CustomerName="";        //If provided the customer’s name will be included in the confirmation e-mails and stored in My Sage Pay.
          var $o_CustomerEMail="";       //If provided, the customer will be e-mailed on completion of a successful transaction (but not an unsuccessful one).
          var $o_VendorEMail="";         //If provided, an e-mail will be sent to this address when each transaction completes (successfully or otherwise).
          var $o_SendEMail="";           //If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided.
                                                              //0 = Do not send either customer or vendor e-mails
                                                              //1 = Send customer and vendor e-mails if addresses are provided (DEFAULT)
                                                              //2 = Sent vendor e-mail but NOT the customer e-mail

          var $o_eMailMessage="";       //If provided this message is included toward the top of the customer confirmation e-mails.
          var $BillingSurname="";
          var $BillingFirstnames="";
          var $BillingAddress1="";
          var $o_BillingAddress2="";
          var $BillingCity="";
          var $BillingPostCode="";
          var $BillingCountry="";
          var $o_BillingState="";
          var $o_BillingPhone="";
          var $DeliverySurname="";
          var $DeliveryFirstnames="";
          var $DeliveryAddress1="";
          var $o_DeliveryAddress2="";
          var $DeliveryCity="";
          var $DeliveryPostCode="";
          var $DeliveryCountry="";
          var $o_DeliveryState="";
          var $o_DeliveryPhone="";          //
          var $o_Basket="";
          var $o_AllowGiftAid="";          //This flag allows the gift aid acceptance box to appear for this transaction on the payment page. This only appears if your vendor account is Gift Aid enabled.
          var $o_ApplyAVSCV2="";           //Using this flag you can fine tune the AVS/CV2 checks and rule set you’ve defined at a transaction level. This is useful in circumstances where direct and trusted customer contact has been established and you wish to override the default security checks.
          var $o_Apply3DSecure="";         //Using this flag you can fine tune the 3D Secure checks and rule set you’ve defined at a transaction level. This is useful in circumstances where direct and trusted customer contact has been established and you wish to override the default security checks.

      //mine
         var $productarray = array();

      // decode result returned
        var $decode_Status="";
        var $decode_StatusDetail="";
        var $decode_VendorTxCode="";
        var $decode_VPSTxId="";
        var $decode_TxAuthNo="";
        var $decode_Amount="";
        var $decode_AVSCV2="";
        var $decode_AddressResult="";
        var $decode_PostCodeResult="";
        var $decode_CV2Result="";
        var $decode_GiftAid="";
        var $decode_3DSecureStatus="";
        var $decode_CAVV="";
        var $decode_CardType="";
        var $decode_Last4Digits="";
        var $decode_AddressStatus=""; // PayPal transactions only
        var $decode_PayerStatus="";    // PayPal transactions only



      function sagepay() {

          $this->SuccessURL=SITE_SECURE_URL."order.php?payprovider=sagepay&action=paymentsuccess";

          $this->FailureURL=SITE_SECURE_URL."order.php?payprovider=sagepay&action=paymentfailed";


  //  $sagepay->$o_VendorEMail="sagepay@merchantmakers.co.uk";

          $this->o_VendorEMail=SITE_ADMINEMAIL;         //If provided, an e-mail will be sent to this address when each transaction completes (successfully or otherwise).
          $this->o_SendEMail="";                        //If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided.
                                                              //0 = Do not send either customer or vendor e-mails
                                                              //1 = Send customer and vendor e-mails if addresses are provided (DEFAULT)
                                                              //2 = Sent vendor e-mail but NOT the customer e-mail

          $this->o_eMailMessage=SITE_RECEIPTTEXT;       //If provided this message is included toward the top of the customer confirmation e-mails.

          $this->Protocol="2.23";

          if ($this->ConnectTo=="LIVE")
                  $this->PurchaseURL="https://live.sagepay.com/gateway/service/vspform-register.vsp";
          elseif ($this->ConnectTo=="TEST")
                  $this->PurchaseURL="https://test.sagepay.com/gateway/service/vspform-register.vsp";
          else
                  $this->PurchaseURL="https://test.sagepay.com/simulator/vspformgateway.asp";

       //   $this->EncryptionPassword="UQSEpNTiAaiNiJSx";  /** Set this value to the XOR Encryption password assigned to you by Sage Pay **/
          $this->TxType="DEFERRED"; /** This can be DEFERRED PAYMENT or AUTHENTICATE if your Sage Pay account supports those payment types **/

// $this->PurchaseURL = "https://test.sagepay.com/showpost/showpost.asp  ";

      }

      function addtobasket($description,$quantity, $net,$tax,$gross,$total ) {

        $sproduct = new sageproduct();
        $sproduct->description=$description;
        $sproduct->quantity=$quantity;
        $sproduct->net=$net;
        $sproduct->tax=$tax;
        $sproduct->gross=$gross;
        $sproduct->linetotal=$total;
        $this->productarray[]=$sproduct;

      }

      function buildbaskettext() {
           $line = count($this->productarray);

           $this->o_Basket = $line;

           foreach ($this->productarray as $key=>$value) {
              $this->o_Basket= $this->o_Basket.':';
              $this->o_Basket = $this->o_Basket.$value->description.":";
              $this->o_Basket = $this->o_Basket.$value->quantity.":";
              $this->o_Basket = $this->o_Basket.$value->net.":";
              $this->o_Basket = $this->o_Basket.$value->tax.":";
              $this->o_Basket = $this->o_Basket.$value->gross.":";
              $this->o_Basket = $this->o_Basket.$value->total;
           }

           return $this->o_Basket;

      }

      function buildcrypt() {

          $strPost=$strPost . "VendorTxCode=" . $this->VendorTxCode;

          $strPost=$strPost . "&Amount=" . number_format($this->Amount,2); // Formatted to 2 decimal places with leading digit
          $strPost=$strPost . "&Currency=" . $this->Currency;

          // Up to 100 chars of free format description
          $strPost=$strPost . "&Description=" . $this->Description;

          /* The SuccessURL is the page to which Form returns the customer if the transaction is successful
          ** You can change this for each transaction, perhaps passing a session ID or state flag if you wish */
          $strPost=$strPost . "&SuccessURL=" . $this->SuccessURL;

          /* The FailureURL is the page to which Form returns the customer if the transaction is unsuccessful
          ** You can change this for each transaction, perhaps passing a session ID or state flag if you wish */
          $strPost=$strPost . "&FailureURL=" . $this->FailureURL;

          // This is an Optional setting. Here we are just using the Billing names given.
          $strPost=$strPost . "&CustomerName=" . $this->firstname . " " . $this->lastname;

          /* Email settings:
          ** Flag 'SendEMail' is an Optional setting.
          ** 0 = Do not send either customer or vendor e-mails,
          ** 1 = Send customer and vendor e-mails if address(es) are provided(DEFAULT).
          ** 2 = Send Vendor Email but not Customer Email. If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided. **/
          if ($this->o_SendEMail == 0)
              $strPost=$strPost . "&SendEMail=0";
          else {

              if ($this->o_SendEMail == 1) {
                  $strPost=$strPost . "&SendEMail=1";
              } else {
                  $strPost=$strPost . "&SendEMail=2";
              }

              if (strlen($this->o_CustomerEMail) > 0)
                  $strPost=$strPost . "&CustomerEMail=" . $this->o_CustomerEMail;  // This is an Optional setting

              if ( $this->o_VendorEMail <> "" )
                      $strPost=$strPost . "&VendorEMail=" . $this->o_VendorEMail;  // This is an Optional setting

              // You can specify any custom message to send to your customers in their confirmation e-mail here
              // The field can contain HTML if you wish, and be different for each order.  This field is optional
              $strPost=$strPost . "&eMailMessage=".$this->o_eMailMessage;
          }

          // Billing Details:
          $strPost=$strPost . "&BillingFirstnames=" .$this->BillingFirstnames;
          $strPost=$strPost . "&BillingSurname=" . $this->BillingSurname;
          $strPost=$strPost . "&BillingAddress1=" . $this->BillingAddress1;
          if (strlen($this->BillingAddress2) > 0) $strPost=$strPost . "&BillingAddress2=" . $this->BillingAddress2;
          $strPost=$strPost . "&BillingCity=" . $this->BillingCity;
          $strPost=$strPost . "&BillingPostCode=" . $this->BillingPostCode;
          $strPost=$strPost . "&BillingCountry=" . $this->BillingCountry;
          if (strlen($this->BillingState) > 0) $strPost=$strPost . "&BillingState=" . $this->BillingState;
          if (strlen($this->BillingPhone) > 0) $strPost=$strPost . "&BillingPhone=" . $this->BillingPhone;

          // Delivery Details:
          $strPost=$strPost . "&DeliveryFirstnames=" . $this->DeliveryFirstnames;
          $strPost=$strPost . "&DeliverySurname=" . $this->DeliverySurname;
          $strPost=$strPost . "&DeliveryAddress1=" . $this->DeliveryAddress1;
          if (strlen($contact->address2) > 0) $strPost=$strPost . "&DeliveryAddress2=" . $this->DeliveryAddress2;
          $strPost=$strPost . "&DeliveryCity=" . $this->DeliveryCity;
          $strPost=$strPost . "&DeliveryPostCode=" . $this->DeliveryPostCode;
          $strPost=$strPost . "&DeliveryCountry=" . $this->DeliveryCountry;
          if (strlen($contact2->county) > 0) $strPost=$strPost . "&DeliveryState=" . $this->DeliveryState;
          if (strlen($contact2->telephone1) > 0) $strPost=$strPost . "&DeliveryPhone=" . $this->DeliveryPhone;


          $strPost=$strPost . "&Basket=" . $this->o_Basket; // As created above

          // For charities registered for Gift Aid, set to 1 to display the Gift Aid check box on the payment pages
          $strPost=$strPost . "&AllowGiftAid=0";

          /* Allow fine control over AVS/CV2 checks and rules by changing this value. 0 is Default
          ** It can be changed dynamically, per transaction, if you wish.  See the Server Protocol document */
          if ($strTransactionType!=="AUTHENTICATE")
                  $strPost=$strPost . "&ApplyAVSCV2=0";

          /* Allow fine control over 3D-Secure checks and rules by changing this value. 0 is Default
          ** It can be changed dynamically, per transaction, if you wish.  See the Form Protocol document */
          $strPost=$strPost . "&Apply3DSecure=0";

         
          // Encrypt the plaintext string for inclusion in the hidden field
          $strCrypt = base64Encode(SimpleXor($strPost,$this->EncryptionPassword));

          return $strCrypt;

      }

      function decrypt($strCrypt) {
        // Now decode the Crypt field and extract the results

        $strDecoded=simpleXor(Base64Decode($strCrypt),$this->EncryptionPassword);

        $values = getToken($strDecoded);
        // Split out the useful information into variables we can use
        $this->decode_Status=$values['Status'];
        $this->decode_StatusDetail=$values['StatusDetail'];
        $this->decode_VendorTxCode=$values["VendorTxCode"];
        $this->decode_VPSTxId=$values["VPSTxId"];
        $this->decode_TxAuthNo=$values["TxAuthNo"];
        $this->decode_Amount=$values["Amount"];
        $this->decode_AVSCV2=$values["AVSCV2"];
        $this->decode_AddressResult=$values["AddressResult"];
        $this->decode_PostCodeResult=$values["PostCodeResult"];
        $this->decode_CV2Result=$values["CV2Result"];
        $this->decode_GiftAid=$values["GiftAid"];
        $this->decode_3DSecureStatus=$values["3DSecureStatus"];
        $this->decode_CAVV=$values["CAVV"];
        $this->decode_CardType=$values["CardType"];
        $this->decode_Last4Digits=$values["Last4Digits"];
        $this->decode_AddressStatus=$values["AddressStatus"]; // PayPal transactions only
        $this->decode_PayerStatus=$values["PayerStatus"];     // PayPal transactions only
      }

      function generateform() {

          $s="";
          $s=$s."<!-- *************************************************************************************-->";
          $s=$s."<!-- This form is all that is required to submit the payment information to the system -->";
          $s=$s."<form action=\"".$this->PurchaseURL."\" method=\"POST\" id=\"SagePayForm\" name=\"SagePayForm\">";
          $s=$s."<input type=\"hidden\" name=\"navigate\" value=\"\" />";
          $s=$s."<input type=\"hidden\" name=\"VPSProtocol\" value=\"".$this->Protocol."\">";
          $s=$s."<input type=\"hidden\" name=\"TxType\" value=\"".$this->TxType."\">";
          $s=$s."<input type=\"hidden\" name=\"Vendor\" value=\"".$this->Vendor."\">";
          $s=$s."<input type=\"hidden\" name=\"Crypt\" value=\"".$this->buildcrypt()."\">";
          $s=$s."<input type='submit' name='subbtn' value='Proceed To Payment.....' />";
          $s=$s."</form>";


          return $s;

      }

}




/**************************************************************************************************
* Useful functions for all pages in this kit
***************************************************************************************************/
//Function to redirect browser to a specific page
function redirect($url) {
   if (!headers_sent())
       header('Location: '.$url);
   else {
       echo '<script type="text/javascript">';
       echo 'window.location.href="'.$url.'";';
       echo '</script>';
       echo '<noscript>';
       echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
       echo '</noscript>';
   }
}

/* The getToken function.                                                                                         **
** NOTE: A function of convenience that extracts the value from the "name=value&name2=value2..." reply string **
** Works even if one of the values is a URL containing the & or = signs.                                          */

function getToken($thisString) {

  // List the possible tokens
  $Tokens = array(
    "Status",
    "StatusDetail",
    "VendorTxCode",
    "VPSTxId",
    "TxAuthNo",
    "Amount",
    "AVSCV2",
    "AddressResult",
    "PostCodeResult",
    "CV2Result",
    "GiftAid",
    "3DSecureStatus",
    "CAVV",
        "AddressStatus",
        "CardType",
        "Last4Digits",
        "PayerStatus","CardType");



  // Initialise arrays
  $output = array();
  $resultArray = array();

  // Get the next token in the sequence
  for ($i = count($Tokens)-1; $i >= 0 ; $i--){
    // Find the position in the string
    $start = strpos($thisString, $Tokens[$i]);
        // If it's present
    if ($start !== false){
      // Record position and token name
      $resultArray[$i]->start = $start;
      $resultArray[$i]->token = $Tokens[$i];
    }
  }

  // Sort in order of position
  sort($resultArray);
        // Go through the result array, getting the token values
  for ($i = 0; $i<count($resultArray); $i++){
    // Get the start point of the value
    $valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;
        // Get the length of the value
    if ($i==(count($resultArray)-1)) {
      $output[$resultArray[$i]->token] = substr($thisString, $valueStart);
    } else {
      $valueLength = $resultArray[$i+1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
          $output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
    }

  }

  // Return the ouput array
  return $output;
}

// Filters unwanted characters out of an input string.  Useful for tidying up FORM field inputs.
function cleanInput($strRawText,$strType) {

        if ($strType=="Number") {
                $strClean="0123456789.";
                $bolHighOrder=false;
        }
        else if ($strType=="VendorTxCode") {
                $strClean="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
                $bolHighOrder=false;
        }
        else {
                $strClean=" ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,'/{}@():?-_&£$=%~<>*+\"";
                $bolHighOrder=true;
        }

        $strCleanedText="";
        $iCharPos = 0;

        do
                {
                // Only include valid characters
                        $chrThisChar=substr($strRawText,$iCharPos,1);

                        if (strspn($chrThisChar,$strClean,0,strlen($strClean))>0) {
                                $strCleanedText=$strCleanedText . $chrThisChar;
                        }
                        else if ($bolHighOrder==true) {
                                // Fix to allow accented characters and most high order bit chars which are harmless
                                if (bin2hex($chrThisChar)>=191) {
                                        $strCleanedText=$strCleanedText . $chrThisChar;
                                }
                        }

                $iCharPos=$iCharPos+1;
                }
        while ($iCharPos<strlen($strRawText));

        $cleanInput = ltrim($strCleanedText);
        return $cleanInput;

}

/* Base 64 Encoding function **
** PHP does it natively but just for consistency and ease of maintenance, let's declare our own function **/

function base64Encode($plain) {
  // Initialise output variable
  $output = "";

  // Do encoding
  $output = base64_encode($plain);

  // Return the result
  return $output;
}

/* Base 64 decoding function **
** PHP does it natively but just for consistency and ease of maintenance, let's declare our own function **/

function base64Decode($scrambled) {
  // Initialise output variable
  $output = "";

  // Fix plus to space conversion issue
  $scrambled = str_replace(" ","+",$scrambled);

  // Do encoding
  $output = base64_decode($scrambled);

  // Return the result
  return $output;
}


/*  The SimpleXor encryption algorithm                                                                                **
**  NOTE: This is a placeholder really.  Future releases of Form will use AES or TwoFish.  Proper encryption      **
**  This simple function and the Base64 will deter script kiddies and prevent the "View Source" type tampering        **
**  It won't stop a half decent hacker though, but the most they could do is change the amount field to something     **
**  else, so provided the vendor checks the reports and compares amounts, there is no harm done.  It's still          **
**  more secure than the other PSPs who don't both encrypting their forms at all                                      */

function simpleXor($InString, $Key) {
  // Initialise key array
  $KeyList = array();
  // Initialise out variable
  $output = "";

  // Convert $Key into array of ASCII values
  for($i = 0; $i < strlen($Key); $i++){
    $KeyList[$i] = ord(substr($Key, $i, 1));
  }

  // Step through string a character at a time
  for($i = 0; $i < strlen($InString); $i++) {
    // Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
    // % is MOD (modulus), ^ is XOR
    $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
  }

  // Return the result
  return $output;
}

// Function to check validity of email address entered in form fields
function is_valid_email($email) {
  $result = TRUE;
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
    $result = FALSE;
  }
  return $result;
}


?>

