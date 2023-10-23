<?php
    include "Connections/connection.php";
    include 'config/sitedetails.php';


    include "includes/standardfunctions.php";
    include "includes/db_category.php";
    include "includes/db_product.php";
    include "includes/db_contact.php";
    include "includes/db_contactdetail.php";
    include "includes/db_contacthistory.php";
    include "includes/db_order.php";
    include "includes/db_offers.php";
    include "includes/db_events.php";
    include "includes/db_rentalobjs.php";




    $thisip=$_SERVER['REMOTE_ADDR'];
    //Includes

    mysql_select_db($database_connection, $connection);

    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action']=='rental') {
                location::createlocationtable($connection);
                rentalobj::createrentalobjtable($connection);
                echo "done rentals";
        }

        exit;
    }


         $query="drop table if exists categories";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists categorymedia";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists categoryattributes";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists categoryparents";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists productattributes";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists productcategories";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists categories";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists productmedia";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists productrelations";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists products";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists contacts";
         $droprec = mysql_query($query, $connection) or die(mysql_error());

         $query="      drop table if exists contactdetail";
         $droprec = mysql_query($query, $connection) or die(mysql_error());
         

    category::createcategorytable( $connection) ;

    product::createproducttable( $connection );

    contact::createcontacttable( $connection);

    contactdetail::createcontactdetailtable($connection);

    contacthistory::createcontacthistorytable($connection);

    event::createeventstable($connection);

    offer::createofferstable($connection);

    order::createorder($connection);

                location::createlocationtable($connection);
                rentalobj::createrentalobjtable($connection);

                
    echo "Setup complete";

    exit;



?>
