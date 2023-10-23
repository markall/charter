<?php
class productattribute {
        var $id;
        var $name;
        var $description;
        var $value;
        var $foption;
        var $showprice;
        var $price;
        var $vatcode;

}

class productrelation {
        var $productid2;
        var $reason;
        var $name;

}

class productmedia {
   var $id;
   var $mediaf;
}

class productoptiontitle {

        var $shopid=0;
        var $id=0;
        var $optiontitle="";
        var $optionvalues= array();


        function getoptiontitlebyid($shopid,$optiontitleid) {
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptiontitles WHERE
                                      ".TBL_PREFIX."productoptiontitles.shopid='%s' and
                                      ".TBL_PREFIX."productoptiontitles.id='%s' ", $shopid, $optiontitleid);

            $optionrec = dosql($query);
            $row = mysql_fetch_assoc($optionrec);

            if ( mysql_num_rows($optionrec)>0) {
                $result=$row['id'];
                $this->shopid=$row['shopid'];
                $this->id=$row['id'];
                $this->optiontitle=$row['optiontitle'];

            }

            return $result;
        }

        function getoptiontitlebytitle($shopid,$optiontitle) {
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptiontitles WHERE
                                      ".TBL_PREFIX."productoptiontitles.shopid='%s' and
                                      ".TBL_PREFIX."productoptiontitles.optiontitle='%s' ", $shopid, $optiontitle);

            $optionrec = dosql($query);
            $row = mysql_fetch_assoc($optionrec);

            if ( mysql_num_rows($optionrec)>0) {
                $result=$row['id'];
                $this->shopid=$row['shopid'];
                $this->id=$row['id'];
                $this->optiontitle=$row['optiontitle'];
            }

            return $result;

        }

        function getoptiontitlesbyproduct($shopid, $productid) {

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptiontitles".
                                " JOIN ".TBL_PREFIX."productoptiontitlelink on ".TBL_PREFIX."productoptiontitlelink.productoptiontitleid=".TBL_PREFIX."productoptiontitles.id".
                                " WHERE ".TBL_PREFIX."productoptiontitles.shopid='%s' and ".
                                          TBL_PREFIX."productoptiontitlelink.productid='%s' ", $shopid, $productid);

            $optionrec = dosql($query);

            return $optionrec;
        }



        function insertoptiontitle() {
           global $connection;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."productoptiontitles (
                   shopid,optiontitle )
                   VALUES ( %s, %s )",
                          GetSQLValueString($this->shopid,'text','',''),
                          GetSQLValueString($this->optiontitle,'text','','')
                 );

                 $productins = dosql($query);
                 $this->id = mysql_insert_id();

                return $this->id;
        }

        function getoptiontitlelink($shopid,$optiontitleid,$productid) {
           global $connection;
           $result=0;
           $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptiontitlelink WHERE
                   shopid=%s AND productoptiontitleid=%s AND productid=%s",
                          GetSQLValueString($shopid,'text','',''),
                          GetSQLValueString($optiontitleid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $optionrec = dosql($query);
                 if ( mysql_num_rows($optionrec)>0) {
                   $result =  mysql_num_rows($optionrec);
                 }
                 return $result;

        }

        function getoptiontitlelinkbyproduct($shopid,$productid) {
           global $connection;
           $result=0;
           $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptiontitlelink WHERE
                   shopid=%s AND productid=%s",
                          GetSQLValueString($shopid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $optionrec = dosql($query);
                 if ( mysql_num_rows($optionrec)>0) {
                   $result =  mysql_num_rows($optionrec);
                 }
                 return $result;

        }

        function insertoptiontitlelink($shopid,$optiontitleid,$productid) {
           global $connection;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."productoptiontitlelink(
                   shopid, productoptiontitleid, productid )
                   VALUES ( %s, %s,%s )",
                          GetSQLValueString($shopid,'text','',''),
                          GetSQLValueString($optiontitleid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $productins = dosql($query);
                 return mysql_insert_id();

        }

        function deleteoptiontitlelink($shopid,$optiontitleid,$productid) {
           global $connection;

           $query = sprintf("DELETE FROM ".TBL_PREFIX."productoptiontitlelink WHERE
                   shopid=%s AND optiontitle=%s AND productid=%s ",
                          GetSQLValueString($shopidid,'text','',''),
                          GetSQLValueString($optiontitleid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $productins = dosql($query);

        }


}

class productoption {
        var $optiontitle="";
        var $optiontitleid=0;
        var $optionvalue="";
        var $optionselected="";
        var $optioncode="";
        var $optionmedia=array();


        function getoptionbyid($shopid,$optionid) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvalues WHERE
                                      ".TBL_PREFIX."productoptionvalues.shopid='%s' and
                                      ".TBL_PREFIX."productoptionvalues.id='%s' ", $shopid, $optionid);

            $optionrec = dosql($query);
            $row_option = mysql_fetch_assoc($optionrec);

            if (mysql_num_rows($optionrec)>0) {
                $result=$row_option['id'];
                $this->shopid=$row_option['shopid'];
                $this->id=$row_option['id'];
                $this->productid=$row_option['productid'];
                $this->optiontitleid=$row_option['optiontitleid'];
                $this->selected=$row_option['selected'];


            }


        }

        function getoptionbycode($shopid,$productoptioncode) {
            $result=0;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvalues ".
                                     " WHERE ".
                                     TBL_PREFIX."productoptionvalues.shopid='%s' and ".
                                     TBL_PREFIX."productoptionvalues.optioncode='%s' ", $shopid, $productoptioncode );

            $optionrec = dosql($query);
            if (mysql_num_rows($optionrec)>0) {
                $result=mysql_num_rows($optionrec);
                while ($row=mysql_fetch_assoc($optionrec)) {
                        $this->shopid = $row['shopid'];
                        $this->id = $row['id'];
                        $this->optioncode = $row['optioncode'];
                        $this->optiontitleid=$row['optiontitleid'];
                        $this->optionvalue=$row['optionvalue'];
                        $this->selected=$row['selected'];
                        $this->optionprice=$row['optionprice'];
                }
            }

            return $result;
        }

        function getoptionbyvalue($shopid,$productid,$optiontitleid,$optionvalue) {
            $result=0;

            if ($productid>0) {
                    $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvalues ".
                                        "JOIN ".TBL_PREFIX."productoptionvaluelink ON ".TBL_PREFIX."productoptionvalues.id=".TBL_PREFIX."productoptionvaluelink.productoptionvalueid ".
                                     " WHERE ".
                                     TBL_PREFIX."productoptionvalues.shopid='%s' and ".
                                     TBL_PREFIX."productoptionvalues.optiontitleid='%s' and ".
                                     TBL_PREFIX."productoptionvalues.optionvalue='%s' and ".
                                     TBL_PREFIX."productoptionvaluelink.productid='%s' ", $shopid, $optiontitleid,$optionvalue, $productid );
            } else {
                    $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvalues ".
                                        "JOIN ".TBL_PREFIX."productoptionvaluelink ON ".TBL_PREFIX."productoptionvalues.id=".TBL_PREFIX."productoptionvaluelink.productoptionvalueid ".
                                     " WHERE ".
                                     TBL_PREFIX."productoptionvalues.shopid='%s' and ".
                                     TBL_PREFIX."productoptionvalues.optiontitleid='%s' and ".
                                     TBL_PREFIX."productoptionvalues.optionvalue='%s' ", $shopid, $optiontitleid,$optionvalue, $productid );

            }



            $optionrec = dosql($query);
            if (mysql_num_rows($optionrec)>0) {
                $result=mysql_num_rows($optionrec);
            }

            return $result;
        }

        function getoptions($shopid,$productid,$optiontitleid) {

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvalues ".
                                      " JOIN ".TBL_PREFIX."productoptionvaluelink ON ".TBL_PREFIX."productoptionvalues.id=".TBL_PREFIX."productoptionvaluelink..productoptionvalueid  ".
                                      "WHERE
                                      ".TBL_PREFIX."productoptionvalues.shopid='%s' and
                                      ".TBL_PREFIX."productoptionvalues.optiontitleid='%s' and
                                      ".TBL_PREFIX."productoptionvalues.productid='%s' ", $shopid, $optiontitleid, $productid );

            $optionrec = dosql($query);

            return $optionrec;
        }

        function getoptionvaluelink($shopid,$optionvalueid,$productid) {
           global $connection;
           $result=0;
           $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvaluelink WHERE
                   shopid=%s AND productoptionvalueid=%s AND productid=%s",
                          GetSQLValueString($shopid,'text','',''),
                          GetSQLValueString($optionvalueid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $result = dosql($query);


                 return $result;

        }

        function getoptionvaluelinkbycode($shopid,$optioncode) {
           global $connection;
           $result=0;
           $query = sprintf("SELECT * FROM ".TBL_PREFIX."productoptionvaluelink ".
                                "JOIN ".TBL_PREFIX."productoptionvalue on ".
                                TBL_PREFIX."productoptionvaluelink.productoptionvalueid=".TBL_PREFIX."productoptionvalues.id ".
                                " WHERE ".
                                "shopid=%s AND ".TBL_PREFIX."productoptionvalues.optioncode=%s",
                          GetSQLValueString($shopid,'text','',''),
                          GetSQLValueString($optioncode,'text','','')
                 );

                 $result = dosql($query);

                 return $result;

        }


        function insertoption() {
           global $connection;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."productoptionvalues (
                   shopid, optioncode, optionvalue, optiontitleid, selected, optionprice )
                   VALUES ( %s, %s, %s,%s, %s,%s )",
                          GetSQLValueString($this->shopid,'text','',''),
                          GetSQLValueString($this->optioncode,'text','',''),
                          GetSQLValueString($this->optionvalue,'text','',''),
                          GetSQLValueString($this->optiontitleid,'text','',''),
                          GetSQLValueString($this->selected,'text','',''),
                          GetSQLValueString($this->optionprice,'text','','')
                 );

                 $productins = dosql($query);
                 $this->id = mysql_insert_id();

                 $this->insertoptionvaluelink();


        }

        function updateoption() {
            global $connection;

           $query = sprintf("UPDATE ".TBL_PREFIX."productoptionvalues (
                   optioncode=%s,optionvalue=%s, selected=%s,optionprice=%s where shopid=%s and id=%s",
                          GetSQLValueString($this->optioncode,'text','',''),
                          GetSQLValueString($this->optionvalue,'text','',''),
                          GetSQLValueString($this->selected,'text','',''),
                          GetSQLValueString($this->optionprice,'text','',''),
                          GetSQLValueString($this->shopid,'text','',''),
                          GetSQLValueString($this->selected,'text','',''),
                          GetSQLValueString($this->id,'text','','')
                   );

                 $productupd = dosql($query);

        }

        function deleteoption($shopid, $id) {
            global $connection;
            $query = sprintf ("DELETE FROM ".TBL_PREFIX."productoptionvalues where shopid=% and id=%s " );
            $productupd = dosql($query);


        }



        function insertoptionvaluelink() {
           global $connection;

                 $res = $this->getoptionvaluelink($this->shopid, $this->productid,$this->id);
                 if ( mysql_num_rows($res)<1) {
                   $query = sprintf("INSERT INTO ".TBL_PREFIX."productoptionvaluelink(
                           shopid, productoptiontitleid , productid, productoptionvalueid,selected,optionprice )
                           VALUES ( %s, %s,%s,%s,%s,%s ) ",
                                  GetSQLValueString($this->shopid,'text','',''),
                                  GetSQLValueString($this->optiontitleid,'text','',''),
                                  GetSQLValueString($this->productid,'text','',''),
                                  GetSQLValueString($this->id ,'text','',''),
                                  GetSQLValueString($this->selected,'text','',''),
                                  GetSQLValueString($this->optionprice,'text','','')
                            );
                    $productins = dosql($query);
                 }

        }

        function deleteoptionvaluelink($shopid,$optiontitleid,$productid) {
           global $connection;

           $query = sprintf("DELETE FROM ".TBL_PREFIX."productoptionlink WHERE
                   shopid=%s AND optiontitle=%s AND productid=%s ",
                          GetSQLValueString($shopidid,'text','',''),
                          GetSQLValueString($optiontitleid,'text','',''),
                         GetSQLValueString($productid,'text','','')
                 );

                 $productins = dosql($query);

        }


}

class product {
        var $connection="";
        var $imagedir="";
        var $shopid="";
        var $id="";
        var $code="";
        var $title="";
        var $reference="";
        var $status="";
        var $ptype="";
        var $header="";
        var $footer="";
        var $position="";
        var $keywords="";
        var $fname="";
        var $template="";
        var $shortdescription="";
        var $description="";
        var $metadescription="";
        var $rrp=0;
        var $price=0;
        var $vatcode=0;
        var $vatexempt;
        var $deliverycost=0;
        var $deliveryunit=0;
        var $discount=0;
        var $special=0;
        var $categories=array();
        var $attributes=array();
        var $relations=array();
        var $optiontitles=array();
        var $media=array();
        var $date_created="";
        var $date_amended="";
        var $whoamended="";
        var $datecreated="";
        var $amended="";


        function createproducttable($connection) {
                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."products` (".
                                "  `shopid` int(11) NOT NULL default '0',".
                                "  `id` int(11) NOT NULL auto_increment,".
                                "  `code` varchar(20) NOT NULL default '',".
                                "  `title` varchar(255) NOT NULL default '',".
                                "  `reference` varchar(255)  default '',".
                                "  `status` varchar(10) default 'hold',".
                                "  `ptype` varchar(10) default 'hold',".
                                "  `header` varchar(255)  default '',".
                                "  `footer` varchar(255)  default '',".
                                "  `position`    varchar(10)  default '',".
                                "  `keywords` varchar(255)  default '',".
                                "  `fname`    varchar(50)  default '',".
                                "  `template`    varchar(50)  default '',".
                                "  `shortdescription` varchar(255) default '',".
                                "  `description` text  ,".
                                "  `metadescription` text  , ".
                                "  `notes` text , ".
                                "  `rrp` float(11,2) default 0,".
                                "  `price` float(11,2) default 0,".
                                "  `vatcode` integer default 0,".
                                "  `vatexempt` integer default 0,".
                                "  `deliverycost` float(11,2) default 0,".
                                "  `deliveryunit` integer default 0, ".
                                "  `discount` float(11,2) default 0, ".
                                "  `special` boolean default false, ".
                                "  `datecreated` datetime default NULL, ".
                                "  `dateamended` datetime default NULL, ".
                                "  `whoamended` varchar(50) default NULL, ".
                                "  PRIMARY KEY  (`ID`),".
                                "  UNIQUE KEY `ID` (`ID`)".
                                ") ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."productcategories` (".
                                "   `shopid` int(11) NOT NULL default '0', ".
                                "   `productid` int(11) NOT NULL default '0', ".
                                "   `categoryid` int(11) NOT NULL default '0', ".
                                "   PRIMARY KEY (`shopid`,`categoryid`,`productid` ) ".
                                ") ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."productattributes` (".
                                "    `shopid` int(11) NOT NULL default '0' , ".
                                "    `productid` int(11) NOT NULL default '0' , ".
                                "    `id` int(11) NOT NULL auto_increment , ".
                                "    `name` varchar(30) default NULL , ".
                                "    `value` varchar(50) default NULL , ".
                                "    `description` text default NULL , ".
                                "    `foption` boolean, ".
                                "    `showprice` boolean, ".
                                "    `price` float(100,2), ".
                                "    `vatcode` int, ".
                                "     PRIMARY KEY (id) ".
                                " ) ";

                $productrec = dosql($query);
                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."productrelations` ( ".
                                "   `shopid` int(11) NOT NULL, ".
                                "   `productid1` int(11) NOT NULL, ".
                                "   `productid2` int(11) NOT NULL, ".
                                "   `reason` varchar(50), ".
                                "   `name` varchar(50) , ".
                                "  PRIMARY KEY ( shopid, productid1, productid2 ) ".
                                " ) ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productmedia` (".
                                " `shopid` int(11) NOT NULL,".
                                " `productid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                " `mediaf` varchar(30),".
                                "PRIMARY KEY ( id)".
                                ") ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productoptiontitles` (".
                                " `shopid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                " `optiontitle` varchar(50),".
                                "PRIMARY KEY ( id)".
                                ") ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productoptiontitlelink` (".
                                " `shopid` int(11) NOT NULL,".
                                " `productid` int(11) ,".
                                " `productoptiontitleid` int(11) ,".
                                "PRIMARY KEY ( shopid, productid,productoptiontitleid )".
                                ") ";


                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productoptionvalues` (".
                                " `shopid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                " `optioncode` varchar(50) ,".
                                " `optiontitleid` int(11) NOT NULL, ".
                                " `optionvalue` varchar(100),".
                                " `selected` boolean, ".
                                " `optionprice` float(100,2),".
                                "PRIMARY KEY ( id)".
                                ") ";

                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productoptionvaluelink` (".
                                " `shopid` int(11) NOT NULL,".
                                " `productid` int(11) ,".
                                " `productoptiontitleid` int(11) ,".
                                " `productoptionvalueid` int(11) ,".
                                " `selected` boolean, ".
                                " `optionprice` float(100,2),".
                                "PRIMARY KEY ( shopid, productid,productoptionvalueid )".
                                ") ";


                $productrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."productoptionmedia` (".
                                " `shopid` int(11) NOT NULL,".
                                " `productoptionvalueid` int(11) NOT NULL,".
                                " `id` int(11) NOT NULL auto_increment,".
                                " `mediaf` varchar(30),".
                                "PRIMARY KEY ( id)".
                                ") ";

                                $query = "".
                                       "CREATE INDEX ".TBL_PREFIX."IDX_product ON ".TBL_PREFIX."products (shopid,id); ";
                $productrec = dosql($query);

                                $query = "".
                                       "CREATE INDEX ".TBL_PREFIX."IDX_productcode ON ".TBL_PREFIX."products (shopid,code); ";
                $productrec = dosql($query);

                                $query = "".
                                       "CREATE INDEX ".TBL_PREFIX."IDX_productcategoriesp ON ".TBL_PREFIX."productcategories (shopid,productid); ";
                $productrec = dosql($query);

                                $query = "".
                                       "CREATE INDEX ".TBL_PREFIX."IDX_productcategoriesc ON ".TBL_PREFIX."productcategories (shopid,categoryid); ";
                $productrec = dosql($query);

                                $query = "".
                                       "CREATE INDEX ".TBL_PREFIX."IDX_productrel1 ON ".TBL_PREFIX."productcategories (shopid,categoryid); ";
                $productrec = dosql($query);

        }



        function getproductidbycode($shopid,$code) {
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."products WHERE
                                      ".TBL_PREFIX."products.shopid='%s' and
                                      ".TBL_PREFIX."products.code='%s' ", $shopid, $code);

            $productrec = dosql($query);
            $row_product = mysql_fetch_assoc($productrec);

            if (mysql_num_rows($productrec)>0) {
               $result=$row_product['id'];

                $this->$shopid=$row_product['shopid'];
                $this->id=$row_product['id'];
                $this->code=$row_product['code'];
                $this->title=$row_product['title'];
                $this->reference=$row_product['reference'];
                $this->status=$row_product['status'];
                $this->ptype=$row_product['ptype'];
                $this->header=$row_product['header'];
                $this->footer=$row_product['footer'];
                $this->fname=$row_product['fname'];
                $this->fname=$row_product['template'];
                $this->keywords=$row_product['keywords'];
                $this->shortdescription=$row_product['shortdescription'];
                $this->description=$row_product['description'];
                $this->metadescription=$row_product['metadescription'];
                $this->rrp=$row_product['rrp'];
                $this->price=$row_product['price'];
                $this->vatcode=$row_product['vatcode'];
                $this->vatexempt=$row_product['vatexempt'];
                $this->deliveryunit=$row_product['deliveryunit'];
                $this->deliverycost=$row_product['deliverycost'];
                $this->discount=$row_product['discount'];
                $this->special=$row_product['special'];
            }

            return $result;

        }

        function getproduct($shopid,$code) {
            global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."products WHERE
                                      ".TBL_PREFIX."products.shopid='%s' and
                                      ".TBL_PREFIX."products.code='%s' ", $shopid, $code);


            $productrec = dosql($query);
            $row_product = mysql_fetch_assoc($productrec);

            if (mysql_num_rows($productrec)>0) {
                                $this->$shopid=$row_product['shopid'];
                                $this->id=$row_product['id'];
                                $this->code=$row_product['code'];
                                $this->title=$row_product['title'];
                                $this->reference=$row_product['reference'];
                                $this->status=$row_product['status'];
                                $this->ptype=$row_product['ptype'];
                                $this->header=$row_product['header'];
                                $this->footer=$row_product['footer'];
                                $this->position=$row_product['position'];
                                $this->fname=$row_product['fname'];
                                $this->fname=$row_product['template'];
                                $this->keywords=$row_product['keywords'];
                                $this->shortdescription=$row_product['shortdescription'];
                                $this->description=$row_product['description'];
                                $this->metadescription=$row_product['metadescription'];
                                $this->rrp=$row_product['rrp'];
                                $this->price=$row_product['price'];
                                $this->vatcode=$row_product['vatcode'];
                                $this->vatexempt=$row_product['vatexempt'];
                                $this->deliveryunit=$row_product['deliveryunit'];
                                $this->deliverycost=$row_product['deliverycost'];
                                $this->discount=$row_product['discount'];
                                $this->special=$row_product['special'];

                            $this->getcategories($shopid,$this->id);

                            $this->getattributes($shopid,$this->id);

                            $this->getrelations($shopid,$this->id);

                            $this->getmedia($shopid, $this->id);

                        }




        }


        function getproductbyid($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."products WHERE ".TBL_PREFIX."products.shopid='%s' and ".TBL_PREFIX."products.id='%s'", $shopid, $id );
            $productrec = dosql($query);

            $row_product = mysql_fetch_assoc($productrec);

            if (mysql_num_rows($productrec)>0) {
                                $this->$shopid=$row_product['shopid'];
                                $this->id=$row_product['id'];
                                $this->code=$row_product['code'];
                                $this->title=$row_product['title'];
                                $this->reference=$row_product['reference'];
                                $this->status=$row_product['status'];
                                $this->ptype=$row_product['ptype'];
                                $this->header=$row_product['header'];
                                $this->footer=$row_product['footer'];
                                $this->position=$row_product['position'];
                                $this->fname=$row_product['fname'];
                                $this->fname=$row_product['template'];
                                $this->keywords=$row_product['keywords'];
                                $this->shortdescription=$row_product['shortdescription'];
                                $this->description=$row_product['description'];
                                $this->metadescription=$row_product['metadescription'];
                                $this->rrp=$row_product['rrp'];
                                $this->price=$row_product['price'];
                                $this->vatcode=$row_product['vatcode'];
                                $this->vatexempt=$row_product['vatexempt'];
                                $this->deliveryunit=$row_product['deliveryunit'];
                                $this->deliverycost=$row_product['deliverycost'];
                                $this->discount=$row_product['discount'];
                                $this->special=$row_product['special'];

                                $this->getcategories($shopid,$this->id);

                                $this->getattributes($shopid,$this->id);

                                $this->getrelations($shopid,$this->id);

                                $this->getmedia($shopid,$this->id);

                        }




            return (mysql_num_rows($productrec));

        }

        function getallproducts($shopid,$status,$ptype,$fields ) {
            global $connection;

            $query = sprintf("SELECT ".$fields." FROM ".TBL_PREFIX."products where shopid='%s'",$shopid);
            if ($status>'') {
                $query= $query." and status='".$status."'";
            }

            if ($ptype>'') {
                $query= $query." and ptype='".$ptype."'";
            }

            $products= dosql($query);
            return $products;
        }

        function getproductsbycategory($shopid,$categoryid) {
            global $connection;

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productcategories
                              right join ".TBL_PREFIX."products on ".TBL_PREFIX."productcategories.productid=".TBL_PREFIX."products.id
                              where ".TBL_PREFIX."productcategories.shopid='%s' and
                              ".TBL_PREFIX."productcategories.categoryid='%s' order by position ",$shopid, $categoryid);

            $products= dosql($query);

            return $products;
        }

        function getproductsasoption($shopid,$categoryid ,$chosen) {
            global $connection;

            $query = "SELECT *  FROM ".TBL_PREFIX."products ";

            $where="";
            $where =" where ".TBL_PREFIX."products.shopid='%s' ";

            if ($categoryid>0) {
              $where = $where." and ".TBL_PREFIX."productcategories.categoryid='%s' ";
              $query = $query."LEFT JOIN ".TBL_PREFIX."productcategories on ".TBL_PREFIX."productcategories.productid=".TBL_PREFIX."products.id ";
            }

            $query = sprintf( $query.$where ,$shopid, $categoryid );

            $products= dosql($query);

            $out="";

            while ($row=mysql_fetch_assoc( $products)) {
               $out = $out."<option value='".$row['id']."'";
                if ($row['id']==$chosen) {
                        $out = $out." selected ";
                }
               $out = $out." >".$row['code']."</option>";
            }

            return $out;

        }



        function getcategories($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productcategories
                                             WHERE shopid='%s' and productid=%s",
                                             $this->shopid, $id );

            $productcategoryrec = dosql($query);

            unset($this->categories);

            if (mysql_num_rows($productcategoryrec)>0) {

              while  ($row_productcategory = mysql_fetch_assoc($productcategoryrec)) {
                     $this->categories[] = $row_productcategory['categoryid'];
              }
            }


        }

        function isincategory($categoryid) {
            global $connection;
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productcategories
                                      where shopid='%s' and productid='%s'
                                      and categoryid='%s'",
                                        $this->shopid, $this->id, $categoryid );
            $catprorec = dosql($query);
            $row_catpro = mysql_fetch_assoc($catprorec);
            if (mysql_num_rows($catprorec)>0) {
                        $result=1;
            }
            return $result;
        }



        function getattributes($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productattributes
                                          WHERE shopid='%s' and productid=%s",$this->shopid, $this->id );

              $productattrrec = dosql($query);

            unset($this->attributes);
            if (mysql_num_rows($productattrrec)>0) {
                while  ( $row_productattr = mysql_fetch_assoc($productattrrec) ) {
                        $attr = new productattribute();
                        $attr->id   = $row_productattr['id'];
                        $attr->name  = $row_productattr['name'];
                        $attr->description  = $row_productattr['description'];
                        $attr->value = $row_productattr['value'];
                        $attr->foption  = $row_productattr['foption'];
                        $attr->showprice  = $row_productattr['showprice'];
                        $attr->price  = $row_productattr['price'];
                        $attr->vatcode  = $row_productattr['vatcode'];
                        $this->attributes[] = $attr;
                }
            }
        }

        function getrelations($shopid,$id) {
             global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productrelations WHERE shopid='%s' and productid1=%s",$this->shopid, $this->id );

            $productrelationsrec = dosql($query);

            unset($this->relations);
            if (mysql_num_rows($productrelationsrec)>0) {
              while  ($row_productrelations = mysql_fetch_assoc($productrelationsrec)) {
                      $relation = new productrelation();
                      $relation->productid2= $row_productrelations['productid2'];
                      $relation->reason = $row_productrelations['reason'];
                      $relation->name = $row_productrelations['name'];
                      $this->relations[] = $relation;
              }
            }
        }

        function getrelationsnames($shopid,$id,$reason ) {
            global $connection;
            $result=array();
            $query = sprintf("SELECT DISTINCT ".TBL_PREFIX."productrelations.name FROM ".TBL_PREFIX."productrelations WHERE
                                                shopid='%s' and productid1=%s and reason='%s' ",
                                                $this->shopid, $this->id, $reason );

            $productrelationsrec = dosql($query);

            $result=array();
            if (mysql_num_rows($productrelationsrec)>0) {
              while  ($row_productrelations = mysql_fetch_assoc($productrelationsrec)) {
                        $result[] = $row_productrelations['name'];
              }
            }
            return $result;
        }

        function getmedia($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productmedia WHERE shopid='%s' and productid=%s",$this->shopid, $this->id );
            $productmediarec = dosql($query);
            if (mysql_num_rows($productmediarec)>0) {
              while  ($row_productmedia = mysql_fetch_assoc($productmediarec)) {
                      $media = new productmedia();
                      $media->id=  $row_productmedia['id'];
                      $media->mediaf = $row_productmedia['mediaf'];
                      $this->media[] = $media;
              }
            }
            return $media;
        }

        function getmediabyid($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."productmedia WHERE shopid='%s' and id=%s",$this->shopid, $id );
            $productmediarec = dosql($query);
            if (mysql_num_rows($productmediarec)>0) {
              while  ($row_productmedia = mysql_fetch_assoc($productmediarec)) {
                      $media = new productmedia();
                      $media->id=  $row_productmedia['id'];
                      $media->mediaf = $row_productmedia['mediaf'];
              }
            }
           
            return $media;
        }

        function deleteattributes($shopid,$id) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."productattributes WHERE shopid='%s' and productid='%s' ",$shopid,$id );
              $rec = dosql($query);
              return $rec;
        }

        function deletefromcategories($shopid,$id) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."productcategories WHERE shopid='%s' and productid='%s' ",$shopid,$id );
              $rec = dosql($query);
              return $rec;
        }

        function deleterelations($shopid,$id) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."productrelations WHERE shopid='%s' and (productid1='%s' or productid2='%s') ",$shopid,$id,$id );
              $rec = dosql($query);
              return $rec;

        }

        function delete($shopid,$id) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."products WHERE shopid='%s' and id='%s' ",$shopid,$id );
              $rec = dosql($query);
              return $rec;

        }

        function insertproduct() {
           global $connection;
           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."products (
                   shopid,code,title,reference,
                   status,ptype,header,footer,position,keywords,fname,template,shortdescription,description,metadescription, rrp,
                   price,vatcode, vatexempt, deliveryunit,deliverycost, discount,  special,
                   datecreated,dateamended,whoamended )
                   VALUES ( %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->code,'text','',''),
                 GetSQLValueString($this->title,'text','',''),
                 GetSQLValueString($this->reference,'text','',''),
                 GetSQLValueString($this->status,'text','',''),
                 GetSQLValueString($this->ptype,'text','',''),
                 GetSQLValueString($this->header,'text','',''),
                 GetSQLValueString($this->footer,'text','',''),
                 GetSQLValueString($this->position,'text','',''),
                 GetSQLValueString($this->keywords,'text','',''),
                 GetSQLValueString($this->fname,'text','',''),
                 GetSQLValueString($this->template,'text','',''),
                 GetSQLValueString($this->shortdescription,'text','',''),
                 GetSQLValueString($this->description,'text','',''),
                 GetSQLValueString($this->metadescription,'text','',''),
                 GetSQLValueString($this->rrp,'text','',''),
                 GetSQLValueString($this->price,'text','',''),
                 GetSQLValueString($this->vatcode,'text','',''),
                 GetSQLValueString($this->vatexempt,'text','',''),
                 GetSQLValueString($this->deliveryunit,'text','',''),
                 GetSQLValueString($this->deliverycost,'text','',''),
                 GetSQLValueString($this->discount,'text','',''),
                 GetSQLValueString($this->special,'text','',''),
                 GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                 GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                 GetSQLValueString($this->whoamended,'text','','')
                 );

                 $productins = dosql($query);
                 $this->id = mysql_insert_id();


                 if (isset( $this->categories ) ) {
                   foreach (  $this->categories as $key=>$value) {
                          $found=$this->isincategory($value);
                          if ($found==0) {
                              $this->addtocategory($value);
                          }
                   }
                 }

                 if (isset($this->attributes) ) {
                   foreach ( $this->attributes as $key=>$value ) {
                          $value->productid = $this->id;
                          $this->addattribute($value);
                   }
                 }

                 if (isset( $this->relations ) ) {
                   foreach ( $this->relations as $key=>$value ) {
                          $this->addrelation($value);
                   }
                 }

                 return $this->id;

        }

        function addtocategory($categoryid) {
           global $connection;
           $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."productcategories (
                shopid, productid, categoryid )
                VALUES ( %s, %s, %s ) ",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->id,'text','',''),
                 GetSQLValueString($categoryid,'text','','')
                );

                $productcatins = dosql($query);

                return mysql_insert_id();

        }

        function deletecategory($categoryid) {
           global $connection;
           $dater= new dater;
           $query = sprintf("DELETE FROM ".TBL_PREFIX."productcategories where
                shopid=%s and productid=%s and categoryid=%s ",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->id,'text','',''),
                 GetSQLValueString($categoryid,'text','','')
                );

                $productcatins = dosql($query);

                return mysql_insert_id();

        }
        function addattribute($attribute) {
           global $connection;
           $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."productattributes (
             shopid, productid, name, description, value ,foption, showprice , price , vatcode )
             VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s ) ",
                 GetSQLValueString($attribute->shopid ,'text','',''),
                 GetSQLValueString($attribute->productid ,'text','',''),
                 GetSQLValueString($attribute->name ,'text','',''),
                 GetSQLValueString($attribute->description ,'text','',''),
                 GetSQLValueString($attribute->value ,'text','',''),
                 GetSQLValueString($attribute->option ,'text','',''),
                 GetSQLValueString($attribute->showprice ,'text','',''),
                 GetSQLValueString($attribute->price ,'text','',''),
                 GetSQLValueString($attribute->vatcode ,'text','','')
             );

             $productattrins = dosql($query);
             $attribute->id = mysql_insert_id();

             return $attribute;
        }

        function addrelation($relation) {
           global $connection;
           $dater= new dater;

           $query = sprintf("INSERT INTO ".TBL_PREFIX."productrelations (
                shopid, productid1, productid2, reason,name )
                VALUES ( %s, %s, %s, %s, %s ) ",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->id,'text','',''),
                 GetSQLValueString($relation->productid2,'text','',''),
                 GetSQLValueString($relation->reason,'text','',''),
                 GetSQLValueString($relation->name,'text','','')
                );

                $productrelins = dosql($query);
                $relation->id=mysql_insert_id();

                return $relation;

        }

        function addmedia($shopid,$productid,$mediaf) {
           global $connection;
           $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."productmedia ( shopid,productid,mediaf ) VALUES (%s, %s, %s)",
                              GetSQLValueString($shopid,'text','',''),
                              GetSQLValueString($productid,'text','',''),
                              GetSQLValueString($mediaf,'text','','')
                                );
           $query_produpd = dosql($query);
           return mysql_insert_id();

        }

        function deletemedia($shopid,$productid,$id) {
          global $connection;
          if ($productid>0) {
              $query = sprintf( "DELETE FROM ".TBL_PREFIX."productmedia where shopid='%s' and productid='%s'", $shopid, $productid );
          } else {
              $query = sprintf ("DELETE FROM ".TBL_PREFIX."productmedia where shopid='%s' and id='%s'", $shopid ,  $id );
          }

          $query_catupd = dosql($query);


        }

        function addoption($shopid,$productid,$mediaf, $selected, $price, $value ) {



        }

        function updateproduct($id) {
           $dater= new dater;
           global $connection;

           $query = sprintf("UPDATE ".TBL_PREFIX."products set
                   shopid=%s,code=%s,title=%s,reference=%s,
                   status=%s,ptype=%s,header=%s,footer=%s,position=%s,keywords=%s,fname=%s,
                   template=%s,shortdescription=%s,description=%s,metadescription=%s, rrp=%s,
                   price=%s,vatcode=%s,vatexempt=%s,deliveryunit=%s,deliverycost=%s, discount=%s,special=%s,
                   datecreated=%s,dateamended=%s,whoamended=%s
                   where id=%s",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->code,'text','',''),
                 GetSQLValueString($this->title,'text','',''),
                 GetSQLValueString($this->reference,'text','',''),
                 GetSQLValueString($this->status,'text','',''),
                 GetSQLValueString($this->ptype,'text','',''),
                 GetSQLValueString($this->header,'text','',''),
                 GetSQLValueString($this->footer,'text','',''),
                 GetSQLValueString($this->position,'text','',''),
                 GetSQLValueString($this->keywords,'text','',''),
                 GetSQLValueString($this->fname,'text','',''),
                 GetSQLValueString($this->template,'text','',''),
                 GetSQLValueString($this->shortdescription,'text','',''),
                 GetSQLValueString($this->description,'text','',''),
                 GetSQLValueString($this->metadescription,'text','',''),
                 GetSQLValueString($this->rrp,'text','',''),
                 GetSQLValueString($this->price,'text','',''),
                 GetSQLValueString($this->vatcode,'text','',''),
                 GetSQLValueString($this->vatexempt,'text','',''),
                 GetSQLValueString($this->deliveryunit,'text','',''),
                 GetSQLValueString($this->deliverycost,'text','',''),
                 GetSQLValueString($this->discount,'text','',''),
                 GetSQLValueString($this->special,'text','',''),
                 GetSQLValueString($dater->phptosqldate( date('d/m/y H:i:s') )  ,'date','',''),
                 GetSQLValueString($dater->phptosqldate(  date('d/m/y H:i:s') ) ,'date','',''),
                 GetSQLValueString($this->whoamended,'text','',''),
                 $id
                 );


                $query_proupd = dosql($query);

        }


        function settemplate($text) {
             global $vatrates;
             global $sitedomain;

            $text = str_replace('<%product_id%>',$this->id, $text);
            $text = str_replace('<%product_code%>',$this->code, $text);
            $text = str_replace('<%product_title%>',$this->title, $text);
            $text = str_replace('<%product_description%>',$this->header, $text);
            $text = str_replace('<%product_reference%>',$this->reference, $text);
            $text = str_replace('<%product_status%>',$this->status, $text);
            $text = str_replace('<%product_ptype%>',$this->status, $text);
            $text = str_replace('<%product_header%>',$this->header, $text);
            $text = str_replace('<%product_footer%>',$this->footer, $text);
            $text = str_replace('<%product_position%>',$this->position, $text);               
            $text = str_replace('<%product_keywords%>',$this->keywords, $text);
            $text = str_replace('<%product_fname%>',$this->fname, $text);
            $text = str_replace('<%product_template%>',$this->template, $text);
            $text = str_replace('<%product_shortdescription%>',$this->description, $text);  
            $text = str_replace('<%product_description%>',$this->description, $text);
            $text = str_replace('<%product_price%>',$this->price, $text);
            $text = str_replace('<%product_vatcode%>',$this->vatcode, $text);
            $text = str_replace('<%product_vatexempt%>',$this->vatcode, $text);
            $text = str_replace('<%product_deliverycost%>',$this->deliverycost, $text);
            $text = str_replace('<%product_deliveryunit%>',$this->deliveryunit, $text);
            $text = str_replace('<%product_discount%>',$this->discount, $text);
            $text = str_replace('<%product_special%>',$this->special, $text);

            if ($this->price>0) {
                    $netprice =  $this->price - ( ($this->price/100)*$this->discount);    }
            else {
                $netprice = $this->price;
            }

            $netamount =  round( $netprice,2 );

            $netamountrounded = round(($netprice+0.4),0);

            $text = str_replace('<%product_netamount%>',$netamount, $text);
            $text = str_replace('<%product_netamountrounded%>',$netamountrounded, $text);

            $vatrate = $vatrates[$this->vatcode];
            $vatamount = ($netprice/100) * $vatrate;
            $grossamount = round(($netprice + $vatamount),2) ;
            $grossamountrounded = round(($grossamount+0.4),0);

            $rrpvatamount = ($this->price/100) * $vatrate;
            $rrpgrossamount = round( ($this->price + $rrpvatamount),2);
            $rrpgrossamountrounded = round(( $rrpgrossamount+0.4),0);

            $text = str_replace('<%product_vatamount%>',$vatamount, $text);
            $text = str_replace('<%product_grossamount%>',$grossamount, $text);
            $text = str_replace('<%product_grossamountrounded%>',$grossamountrounded, $text);

            $text = str_replace('<%product_rrpvatamount%>',$rrpvatamount, $text);
            $text = str_replace('<%product_rrpgrossamount%>',$rrpgrossamount, $text);
            $text = str_replace('<%product_rrpgrossamountrounded%>',$rrpgrossamountrounded, $text);

            $text = str_replace('<%product_datecreated%>',$this->datecreated, $text);
            $text = str_replace('<%product_amended%>',$this->amended, $text);
            $text = str_replace('<%product_whoamended%>',$this->whoamended, $text);

            $idx = 0;

            if ($this->attributes!=null) {
              foreach ( $this->attributes as $key=>$value) {
                 $text = str_replace('<%product_attributes['.$idx.']name%>',$value->name, $text);
                 $text = str_replace('<%product_attributes['.$idx.']description%>',$value->description, $text);
                 $text = str_replace('<%product_attributes['.$idx.']value%>',$value->value, $text);
                 $text = str_replace('<%product_attributes['.$idx.']valuer%>',round(($value->value+0.4),0), $text);
                 $idx++;
              }
            }

            $idx = 0;
            if ($this->media!=null) {
              foreach ( $this->media as $key=>$value) {
                 $text = str_replace('<%product_media['.$idx.']%>',$value, $text);
                 $idx++;
              }
            }

            $category=new category();
            $category->getcategorybyid($this->shopid,$this->categories[count($this->categories)-1]);
            $text = str_replace('<%product_url%>',$sitedomain."/shop/shop.php?categorycode=".$category->code ,$text);
            $text = str_replace('<%product_mainimg%>',$sitedomain."/shop/".$category->media[count($category->media)-1],$text);
            $text = str_replace('<%product_currency%>','GBP' ,$text);

           return $text;
        }

}


 ?>
