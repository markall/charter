<?php
    global $sitename;
    global $siteadminemail;
    global $wikilink;
    global $phpBBLink;
    global $useadmin;
    global $checkstatus;
    global $autosignin;
    global $phpv;
    global $thisip;
    global $templatefolder;
    global $company;
    global $orderemailreceipttext;
    global $siteorderemail;
    global $shopid;

      $thisip=$_SERVER['REMOTE_ADDR'];

      define("TBL_PREFIX", "mm_");
      define("SITE_NAME", "HSC Charter");
      define("SITE_DESCRIPTION", "HSC Charter");
      $path_part = pathinfo($_SERVER['CONTEXT_DOCUMENT_ROOT']);

      define("SITE_URI", $path_part['dirname']);

//$path_parts['dirname'], "\n";
// $path_parts['basename'], "\n";
// $path_parts['extension'], "\n";
// $path_parts['filename'], "\n"; //

      if ($thisip=='127.0.0.1') {
              define("SITE_URL", "http://charter/");
      } else {
              define("SITE_URL", "http://charter.hscboats.co.uk/members/");
      }


      define("SITE_SECURE_URL", "https://www1.securesiteserver.co.uk/hscharter/members/");

      define("SITE_ADMINEMAIL", "hsccharter@merchantmakers.co.uk");

      define("SITE_RECEIPTTEXT", "Thankyou for your prompt payment");
      define("THANKYOU_PAGE",SITE_URL."content.php?template=templates/thankyou.html");
      define("SIGNOUT_PAGE",SITE_URL."content.php");

      define("STYLESHEET_FOLDER", "styles/");
      define("TEMPLATE_FOLDER", "templates/");
      define("DEFAULT_TEMPLATEbase", "templates/content.html");
      define("IMAGE_DIR","bni/");

   //  echo $_SERVER['SERVER_ADDR'];
    if ($_SERVER['SERVER_ADDR']=='127.0.0.1' || strpos(" ".$_SERVER['SERVER_NAME'],'test' )>0  ) {
        define("ORDER_ACTION","order.php?template=templates/shop/order.html");
    } else {
      define("ORDER_ACTION","https://www1.securesiteserver.co.uk/hsccharter/members/order.php");
    }

    if (strpos(getcwd(),'shopadmin')>0) {
            define("INCLUDES_FOLDER",'../includes/');
    } else {
            define("INCLUDES_FOLDER",'includes/');
    }

      define("WIKILINK", 0);
      define("PHPBBLINK", 0);
      define("MODXLINK", 0);
      define("USEADMIN", 1);
      define("CHECKSTATUS", 0);
      define("AUTOSIGNIN", 0);
      define("ENCRYPT", 0);
      define("SUBSCRIPTIONS", 0);
      define("PRODUCTS", 1);
      define("OFFERS", 0);
      define("EVENTS", 0);
      define("USESHOPID",1);

      define("THUMBHEIGHT",150);
      define("THUMBWIDTH",150);
      define("MAINHEIGHT",0);
      define("MAINWIDTH",0);
      define("GROSSPRICE",0);
      define("FACEBOOK",0);
      define("CHARTER",1);

      $shopid="1";


      $sitedomain="http://charter.hscboats.co.uk/members";
      $sitename="Merchant Makers Charter System";
      $siteadminemail="hsc@merchantmakers.co.uk";
      $siteorderemail="hsc@merchantmakers.co.uk";
      $orderemailreceipttext = "Thankyou\n".
                   "Please note your order reference number: #<%orderid%> \n".
                   "One of our sales team will check your order to ensure it is complete\n
                        \n
                        \n
                        We will contact you to arrange a delivery time.\n ".
                "";
       $orderemailreceiptsubject = "Merchant Makers Demo Order ";

      $phpv = substr(phpversion(),0,1);

      $company="HSC Charter system";
      $categorytextdir = "../text/category/";
      $producttextdir = "/text/product/";
      $orderaction = "https://charter.hscboats.co.uk/shop/order.php";

      if ($_SERVER['SERVER_ADDR']=='127.0.0.1' ) {
              $orderaction = "order.php";
      }

      $vatrates = array();
      $vatrates[0] = 0.0;
      $vatrates[1] = 20;

      if (ENCRYPT) {
                $encrypted=ENCRYPT;
      }



?>
