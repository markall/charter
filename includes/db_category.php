<?php

class categoryattribute {
        var $id;
        var $name;
        var $description;
        var $value;
}

class categorymedia {
   var $id;
   var $mediaf;
}

class category {

    var $imagedir="";
    var $shopid="";
    var $id="";
    var $ctype="";
    var $code="";
    var $title="";
    var $status="";
    var $description="";
    var $header="";
    var $footer="";
    var $orderstr="";
    var $filename="";
    var $showproducts="";
    var $showrelations="";
    var $media=array();
    var $parents=array();
    var $listtemplate="";
    var $childtemplate="";
    var $peertemplate="";
    var $productlisttemplate="";
    var $productpagetemplate="";
    var $inline="";
    var $attributes=array();
    var $datecreated="";
    var $dateamended="";
    var $whoamended="";

        function createcategorytable() {
                global $connection;
                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."categories` (".
                                "  `shopid` int(11)  default '0',".
                                "  `id` int(11) NOT NULL auto_increment,".
                                "  `ctype` varchar(1) NOT NULL default ' ',".
                                "  `code` varchar(100) NOT NULL default ' ',".
                                "  `title` varchar(100) NOT NULL default ' ',".
                                "  `status` varchar(10) default ' ',".
                                "  `description` text,".
                                "  `header` varchar(100)  default ' ',".
                                "  `footer` varchar(100)  default ' ',".
                                "  `orderstr` varchar(20)  default ' ',".
                                "  `filename` varchar(30)  default ' ',".
                                "  `showproducts` boolean  default false,".
                                "  `showrelations` boolean default false,".
                                "  `listtemplate` varchar(30) default ' ',".
                                "  `childtemplate` varchar(30)  default ' ',".
                                "  `peertemplate` varchar(30)  default ' ',".
                                "  `productlisttemplate` varchar(30)  default ' ',".
                                "  `productpagetemplate` varchar(30)  default ' ',".
                                "  `inline` boolean default false, ".
                                "  `datecreated` datetime default NULL,".
                                "  `dateamended` datetime default NULL,".
                                "  `whoamended` varchar(50) default NULL,".
                                "  PRIMARY KEY  (`ID`),".
                                "  UNIQUE KEY `ID` (`ID`)".
                                ") ";
                                
                $categoryrec = dosql($query);

                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."categoryparents` (".
                                "  `shopid` int(11) NOT NULL, ".
                                "  `categoryid` int(11) NOT NULL, ".
                                "  `parentid` int(11) NOT NULL, ".
                                " PRIMARY KEY ( `shopid`, `categoryid`, `parentid` )".
                                " ) ";

               $categoryrec = dosql($query);

                                $query = "".
                                "CREATE TABLE  `".TBL_PREFIX."categorymedia` (".
                                " `shopid` int(11) NOT NULL,".
                                " `categoryid` int(11) NOT NULL,".
                                "  `id` int(11) NOT NULL auto_increment,".
                                " `mediaf` varchar(200),".
                                "PRIMARY KEY ( `id` )".
                                ") ";

               $categoryrec = dosql($query);

                                $query = "".
                                "CREATE TABLE `".TBL_PREFIX."categoryattributes` (".
                                "    `shopid` int(11) NOT NULL default '0' , ".
                                "    `categoryid` int(11) NOT NULL default '0' , ".
                                "    `id` int(11) NOT NULL auto_increment , ".
                                "    `name` varchar(30)  default '' , ".
                                "    `value` varchar(250)  default '' , ".
                                "    `description` text  , ".
                                "     PRIMARY KEY ( id ) ".
                                " ) ";

               $categoryrec = dosql($query);

                }

        function getcategory($shopid,$code) {

            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categories WHERE ".TBL_PREFIX."categories.shopid='%s' and ".TBL_PREFIX."categories.code='%s'", $shopid, $code);

            $categoryrec = dosql($query);

            $row_category = mysql_fetch_assoc($categoryrec);

            if (mysql_num_rows($categoryrec)>0) {

                        $this->shopid=$row_category['shopid'];
                        $this->id=$row_category['id'];
                        $this->ctype=$row_category['ctype'];
                        $this->code=$row_category['code'];
                        $this->title=$row_category['title'];
                        $this->status=$row_category['status'];
                        $this->description=$row_category['description'];
                        $this->header=$row_category['header'];
                        $this->footer=$row_category['footer'];
                        $this->orderstr=$row_category['orderstr'];
                        $this->filename=$row_category['filename'];
                        $this->showproducts=$row_category['showproducts'];
                        $this->showrelations=$row_category['showrelations'];
                        $this->listtemplate=$row_category['listtemplate'];
                        $this->childtemplate=$row_category['childtemplate'];
                        $this->peertemplate=$row_category['peertemplate'];
                        $this->productlisttemplate=$row_category['productlisttemplate'];
                        $this->productpagetemplate=$row_category['productpagetemplate'];
                        $this->datecreated=$row_category['datecreated'];
                        $this->dateamended=$row_category['dateamended'];
                        $this->whoamended=$row_category['whoamended'];

                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and categoryid='%s'",$shopid,$this->id);

                        $categoryparentrec = dosql($query);
                         unset ($this->parents);
                         while  ($row_categoryparent = mysql_fetch_assoc($categoryparentrec)) {
                              $this->parents[] = $row_categoryparent['parentid'];
                         }


                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."categorymedia WHERE shopid='%s' and categoryid='%s'",$shopid,$this->id);

                        $categorymediarec = dosql($query);
                         unset($this->media);
                         while  ($row_categorymedia = mysql_fetch_assoc($categorymediarec)) {
                              $media = new categorymedia();
                              $media->id = $row_categorymedia['id'];
                              $media->mediaf = $row_categorymedia['mediaf'];
                              $this->media[] = $media;
                         }


                      $this->getattributes($shopid,$this->id);

            }

            return mysql_num_rows($categoryrec);
        }

        function getcategorycodebyid($shopid,$id) {
          //  global $shopid;
            global $connection;
            $query = sprintf("SELECT code FROM ".TBL_PREFIX."categories WHERE ".TBL_PREFIX."categories.shopid='%s' and ".TBL_PREFIX."categories.id='%s'", $shopid, $id);
                
            $categoryrec = dosql($query);

            $row_category = mysql_fetch_assoc($categoryrec);
            $text="";
            if (mysql_num_rows($categoryrec)>0) {
                        $text=$row_category['code'];
            }
            return $text;

        }
        function getcategorybyid($shopid,$id) {

            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categories WHERE ".TBL_PREFIX."categories.shopid='%s' and ".TBL_PREFIX."categories.id='%s'", $shopid, $id);
            $categoryrec = dosql($query);

            $row_category = mysql_fetch_assoc($categoryrec);

            if (mysql_num_rows($categoryrec)>0) {
                     $this->shopid=$row_category['shopid'];
                        $this->id=$row_category['id'];
                        $this->ctype=$row_category['ctype'];
                        $this->code=$row_category['code'];
                        $this->title=$row_category['title'];
                        $this->status=$row_category['status'];
                        $this->description=$row_category['description'];
                        $this->header=$row_category['header'];
                        $this->footer=$row_category['footer'];
                        $this->orderstr=$row_category['orderstr'];
                        $this->filename=$row_category['filename'];
                        $this->showproducts=$row_category['showproducts'];
                        $this->showrelations=$row_category['showrelations'];
                        $this->listtemplate=$row_category['listtemplate'];
                        $this->childtemplate=$row_category['childtemplate'];
                        $this->peertemplate=$row_category['peertemplate'];
                        $this->productlisttemplate=$row_category['productlisttemplate'];
                        $this->productpagetemplate=$row_category['productpagetemplate'];
                        $this->datecreated=$row_category['datecreated'];
                        $this->dateamended=$row_category['dateamended'];
                        $this->whoamended=$row_category['whoamended'];
                                        
                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and categoryid='%s'",$shopid,$this->id);

                        $categoryparentrec = dosql($query);
                         unset ($this->parents);
                         while  ($row_categoryparent = mysql_fetch_assoc($categoryparentrec)) {
                              $this->parents[] = $row_categoryparent['parentid'];
                         }


                        $query = sprintf("SELECT * FROM ".TBL_PREFIX."categorymedia WHERE shopid='%s' and categoryid='%s'",$shopid,$this->id);

                        $categorymediarec = dosql($query);
                         unset ($this->media);
                         while  ($row_categorymedia = mysql_fetch_assoc($categorymediarec)) {
                              $media = new categorymedia();
                              $media->id = $row_categorymedia['id'];
                              $media->mediaf = $row_categorymedia['mediaf'];
                              $this->media[] = $media;
                         }


                         $this->getattributes($shopid,$this->id);
            }



            return mysql_num_rows($categoryrec);
        }


        function getattributes($shopid,$id) {
            global $connection;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryattributes
                                          WHERE shopid='%s' and categoryid=%s",$this->shopid, $this->id );

            $categoryattrrec = dosql($query);

            unset($this->attributes);
            if (mysql_num_rows($categoryattrrec)>0) {
                while  ( $row_categoryattr = mysql_fetch_assoc($categoryattrrec) ) {
                        $attr = new categoryattribute();
                        $attr->id   = $row_categoryattr['id'];
                        $attr->name  = $row_categoryattr['name'];
                        $attr->description  = $row_categoryattr['description'];
                        $attr->value = $row_categoryattr['value'];
                        $this->attributes[] = $attr;
                }
            }

        }
    function getallcategories($shopid,$parentid) {

             $query = sprintf("SELECT * FROM ".TBL_PREFIX."categories where ".TBL_PREFIX."categories.shopid='%s'",$shopid);

             if ($parentid>0) {
               $query = sprintf("SELECT * FROM ".TBL_PREFIX."categories
                               RIGHT JOIN ".TBL_PREFIX."categoryparents on ".TBL_PREFIX."categories.id=".TBL_PREFIX."categoryparents.categoryid ".
                               "where ".TBL_PREFIX."categories.shopid='%s' ",$shopid);

               $query = $query.sprintf(" and ".TBL_PREFIX."categoryparents.parentid='%s' ",$parentid);
             }
               $query = $query." order by orderstr ";
// add by parent here

            $categories= dosql($query);

            return $categories;
   }

   function gettopcategories($shopid) {
        $query = sprintf("select * from
                                (select ".TBL_PREFIX."categories.shopid, id,count(categoryid) as parents from ".TBL_PREFIX."categories
                                        left join ".TBL_PREFIX."categoryparents on id=categoryid
                                        group by id ) as pcount
                                        where parents<1 and pcount.shopid='%s' ",$shopid );       // use this for SQL v 5+

    //    $query = sprintf(" select ".TBL_PREFIX."categories.shopid, id,count(categoryid) as parents from ".TBL_PREFIX."categories
    //                                    left join ".TBL_PREFIX."categoryparents on id=categoryid  where ".TBL_PREFIX."categories.shopid='%s'
    //                                    group by id ",$shopid );

                 $categories = dosql($query);

         return $categories;
   }

   function getcategorychildren( $parentid, $ctype) {
                    global $connection;
            if (!isset($ctype)) {
              $ctype=0;
            }
            $query = sprintf ( "SELECT * FROM ".TBL_PREFIX."categoryparents
                                left join ".TBL_PREFIX."categories on ".TBL_PREFIX."categoryparents.categoryid = ".TBL_PREFIX."categories.id
                                where ".TBL_PREFIX."categoryparents.shopid='%s' and parentid='%s' and inline=%s ", $this->shopid, $parentid,$ctype );
                                                
            $categories=dosql($query);
            return $categories;         
   }

   function getcategorymediabyid($shopid,$categoryid) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categorymedia where shopid='%s' and categoryid='%s' ",$shopid,$categoryid ) ;
            $categorymediarec = dosql($query);

            unset($this->media);
            $this->media = array();
            while  ($row_categorymedia= mysql_fetch_assoc($categorymediarec)) {
                              $media = new categorymedia();
                              $media->id = $row_categorymedia['id'];
                              $media->mediaf = $row_categorymedia['mediaf'];
                              $this->media[] = $media;
            }
   }


   function isincategory($parentid) {
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents where shopid='%s' and categoryid='%s' and parentid='%s'",
                                        $this->shopid, $this->id, $parentid );
            $catparrec =dosql($query);
            $row_catpar = mysql_fetch_assoc($catparrec);
            if (mysql_num_rows($catparrec)>0) {
                        $result=1;
            }
            return $result;
   }


   function issibling($parentid, $categoryid, $found ) {

       if ($found==0) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents where shopid='%s' and categoryid='%s' ",
                                        $this->shopid, $categoryid );

            $rec =dosql($query);

            while ($row=mysql_fetch_assoc($rec) ) {
               if ($row['parentid']==$parentid) {
                   $found=1;
                   break;
               }
               $found=$this->issibling($parentid,$row['parentid'],$found);
            }
       }



       return $found;


   }

   function haschildren($parentid) {
            $result=0;
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents where shopid='%s' and parentid='%s'",
                                        $this->shopid, $parentid );
            $catparrec = dosql($query);
            if (mysql_num_rows($catparrec)>0) {
                        $result=1;
            }
            return $result;
   }

   function hasproducts($categoryid) {
                $result=0;
                $query = sprintf("SELECT * FROM ".TBL_PREFIX."productcategories
                                      where shopid='%s' and categoryid='%s'",
                                        $this->shopid, $categoryid );
            $catprorec =dosql($query);
            $row_catpro = mysql_fetch_assoc($catprorec);
            if (mysql_num_rows($catprorec)>0) {
                        $result=1;
            }
            return $result;

   }

   function insertcategory() {
           $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."categories (
           shopid,ctype,code,title,status,description,
                   header,footer,orderstr,filename,showproducts,showrelations,
                   listtemplate,childtemplate,peertemplate,
                   productlisttemplate,productpagetemplate,datecreated,dateamended,whoamended )
           VALUES (  %s, %s, %s, %s, %s, %s, %s,%s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->ctype,'text','',''),
                 GetSQLValueString($this->code,'text','',''),
                 GetSQLValueString($this->title,'text','',''),
                 GetSQLValueString($this->status,'text','',''),
                 GetSQLValueString($this->description,'text','',''),
                 GetSQLValueString($this->header,'text','',''),
                 GetSQLValueString($this->footer,'text','',''),
                 GetSQLValueString($this->orderstr,'text','',''),
                 GetSQLValueString($this->filename,'text','',''),
                 GetSQLValueString($this->showproducts,'text','',''),
                 GetSQLValueString($this->showrelations,'text','',''),
                 GetSQLValueString($this->listtemplate,'text','',''),
                 GetSQLValueString($this->childtemplate,'text','',''),
                 GetSQLValueString($this->peertemplate,'text','',''),
                 GetSQLValueString($this->productlisttemplate,'text','',''),
                 GetSQLValueString($this->productpagetemplate,'text','',''),
                 GetSQLValueString($dater->phptosqldate($this->datecreated) ,'date','',''),
                 GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),
                 GetSQLValueString($this->whoamended,'text','','')
                 );

           $catins = dosql($query);
           $query = sprintf("select id from ".TBL_PREFIX."categories where code = '%s'",$this->code);
           $rec = dosql($query);
           $row=mysql_fetch_assoc($rec);
           $this->id= $row['id'];  //

           if (isset($this->parents)) {
               if ($this->parents!=null) {
                   foreach (  $this->parents as $key=>$value) {
                       if ($value!=NUll) {
                                $found=$this->isincategory($value);
                                if ($found==0) {
                                      $this->addparent($value);
                                }
                       } else {
                          //  echo 'parent not found '.$this->id;
                       }
                   }
               }
           }

           if (isset($this->media) ) {
                if ($this->media!=null) {
                   foreach ( $this->media as $key=>$value) {
                        if ($value!=null) {
                              $this->addmedia($value);
                        } else  {
                        }
                    }
                 }
            }

             if (count($this->attributes)>0)   {
                 foreach ( $this->attributes as $key=>$value ) {
                            $value->categoryid = $this->id;
                            $this->addattribute($value);
                 }
             }
    }
   function addparent($parentid) {
        $query = sprintf("INSERT INTO ".TBL_PREFIX."categoryparents ( shopid, categoryid, parentid ) VALUES ( %s,%s,%s )",
                 GetSQLValueString($this->shopid,'text','',''),
                 GetSQLValueString($this->id,'text','',''),
                 GetSQLValueString($parentid,'text','','')
              );

           $catparins = dosql($query);


   }
        function updatecategorybyid($id) {
          $dater= new dater;
          $query = sprintf("UPDATE ".TBL_PREFIX."categories set shopid=%s, ctype=%s,code=%s,title=%s,status=%s,description=%s,
                                header=%s, footer=%s,orderstr=%s,
                                filename=%s,showproducts=%s,showrelations=%s,
                                listtemplate=%s,childtemplate=%s,peertemplate=%s,
                                productlisttemplate=%s,productpagetemplate=%s,
                                dateamended=%s,whoamended=%s where id=%s",
                                                        GetSQLValueString($this->shopid,'text','',''),
                                                        GetSQLValueString($this->ctype,'text','',''),
                                                        GetSQLValueString($this->code,'text','',''),
                                                        GetSQLValueString($this->title,'text','',''),
                                                        GetSQLValueString($this->status,'text','',''),
                                                        GetSQLValueString($this->description,'text','',''),
                                                        GetSQLValueString($this->header,'text','',''),
                                                        GetSQLValueString($this->footer,'text','',''),
                                                        GetSQLValueString($this->orderstr,'text','',''),
                                                        GetSQLValueString($this->filename,'text','',''),
                                                        GetSQLValueString($this->showproducts,'text','',''),
                                                        GetSQLValueString($this->showrelations,'text','',''),
                                                        GetSQLValueString($this->listtemplate,'text','',''),
                                                        GetSQLValueString($this->childtemplate,'text','',''),
                                                        GetSQLValueString($this->peertemplate,'text','',''),
                                                        GetSQLValueString($this->productlisttemplate,'text','',''),
                                                        GetSQLValueString($this->productpagetemplate,'text','',''),
                                                        GetSQLValueString($dater->phptosqldate($this->dateamended) ,'date','',''),                               
                                                        GetSQLValueString($this->whoamended,'text','',''),
                                                 $id );

                 $query_catupd = dosql($query);
        }
                
        function addmedia( $mediaf ) {
           $dater= new dater;
           if ( $this->id >0 ) {
           $query = sprintf("INSERT INTO ".TBL_PREFIX."categorymedia ( shopid,categoryid,mediaf ) VALUES (%s, %s, %s)",
                                                                GetSQLValueString($this->shopid,'text','',''),
                                                        GetSQLValueString($this->id,'text','',''),
                                                        GetSQLValueString($mediaf,'text','','')
                                                                );
           $query_catupd =dosql($query);
           } else {
             //   echo 'no cat id ';
           }
           return mysql_insert_id();
        }

        function addattribute($attribute) {
           $dater= new dater;
           $query = sprintf("INSERT INTO ".TBL_PREFIX."categoryattributes (
             shopid, categoryid, name, description, value  )
             VALUES ( %s, %s, %s, %s,%s ) ",
                 GetSQLValueString($this->shopid ,'text','',''),
                 GetSQLValueString($attribute->categoryid ,'text','',''),
                 GetSQLValueString($attribute->name ,'text','',''),
                 GetSQLValueString($attribute->description ,'text','',''),
                 GetSQLValueString($attribute->value ,'text','','')
             );

             $categoryattrins = dosql($query);
             $attribute->id = mysql_insert_id();

             return $attribute;
        }

        function updateattribute($id,$aname,$adescription,$avalue ) {
                $dater = new dater;
                $query = sprintf ("Update ".TBL_PREFIX."categoryattributes set
                                                name = %s, description=%s, value=%s where shopid=%s and categoryid=%s and id=%s",
                 GetSQLValueString($aname ,'text','',''),
                 GetSQLValueString($adescription ,'text','',''),
                 GetSQLValueString($avalue ,'text','',''),
                 GetSQLValueString($this->shopid ,'text','',''),
                 GetSQLValueString($this->id ,'text','',''),
                 GetSQLValueString($id ,'text','','')
             );

             $categoryattrupd = dosql($query);
        }

        function deletemedia($shopid, $id, $categoryid ) {
                        if ($categoryid>0) {
                           $query = sprintf( "DELETE FROM ".TBL_PREFIX."categorymedia where shopid='%s' and categoryid='%s'", $shopid, $categoryid );
                        } else {
                                $query = sprintf ("DELETE FROM ".TBL_PREFIX."categorymedia where shopid='%s' and id='%s'", $shopid ,  $id );
                        }

                        $query_catupd = dosql($query);

                        // remove the file here

         }

         function deleteparent($shopid,$categoryid,$parentid ) {
              if ($parentid>0) {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and categoryid='%s' and parentid='%s'",$shopid,$categoryid,$parentid);
              } else {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and (categoryid='%s' or parentid='%s')",$shopid,$categoryid,$categoryid);
              }
              $categoryrec = dosql($query);
              return $categoryrec;
         }

         function deletecontactlink($shopid,$categoryid,$contactid ) {
              if ($contactid>0) {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."contactcategories WHERE shopid='%s' and categoryid='%s' and contactid='%s'",$shopid,$categoryid,$contactid);
              } else {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."contactcategories WHERE shopid='%s' and categoryid='%s' ",$shopid,$categoryid );
              }
              $categoryrec = dosql($query);
              return $categoryrec;
         }

         function deleteproductlink($shopid,$categoryid,$productid ) {
              if ($productid>0) {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."productcategories WHERE shopid='%s' and categoryid='%s' and contactid='%s'",$shopid,$categoryid,$productid);
              } else {
                      $query= sprintf("DELETE FROM ".TBL_PREFIX."productcategories WHERE shopid='%s' and categoryid='%s' ",$shopid,$categoryid );
              }
              $categoryrec = dosql($query);
              return $categoryrec;
         }

         function deleteattributes($shopid,$categoryid ) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."categoryattributes WHERE shopid='%s' and categoryid='%s' ",$shopid,$categoryid );
              $categoryrec = dosql($query);
              return $categoryrec;
         }


         function getparents($categoryid) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and categoryid='%s'",
                 $this->shopid, $categoryid);
            $categoryrec = dosql($query);
            return $categoryrec;

         }
         function delete($shopid,$categoryid ) {
              $query= sprintf("DELETE FROM ".TBL_PREFIX."categories WHERE shopid='%s' and id='%s' ",$shopid,$categoryid );
              $categoryrec = dosql($query);
              return $categoryrec;
         }

         function getpeers($parentid) {
            $query = sprintf("SELECT * FROM ".TBL_PREFIX."categoryparents WHERE shopid='%s' and parentid='%s'",
                 $this->shopid, $parentid);
            $categoryrec = dosql($query);
            return $categoryrec;
         }

         function settemplate($text) {
            $text = str_replace('<%category_id%>',$this->id, $text);
            $text = str_replace('<%category_ctype%>',$this->ctype, $text);  
            $text = str_replace('<%category_code%>',$this->code, $text);
            $text = str_replace('<%category_title%>',$this->title, $text);
            $text = str_replace('<%category_description%>',$this->description, $text);
            $text = str_replace('<%category_header%>',$this->header, $text);
            $text = str_replace('<%category_footer%>',$this->footer, $text);
            $text = str_replace('<%category_ordestr%>',$this->orderstr, $text);
            $text = str_replace('<%category_filename%>',$this->filename, $text);

            $idx = 0;

            if ($this->attributes!=null) {
              foreach ( $this->attributes as $key=>$value) {
                 $text = str_replace('<%category_attributes['.$idx.']name%>',$value->name, $text);
                 $text = str_replace('<%category_attributes['.$idx.']description%>',$value->description, $text);
                 $text = str_replace('<%category_attributes['.$idx.']value%>',$value->value, $text);
                 $idx++;
              }
            }


            $idx = 0;
            if ($this->media!=null) {
              foreach ( $this->media as $key=>$value) {
                 $text = str_replace('<%category_media['.$idx.']%>',$value, $text);
                 $idx++;
              }
            }

            $parentnav = "";
            $parentlst = "";

            $checkid=$this->id;
            $parents=$this->getparents($this->id);
            $parentcount = mysql_num_rows($parents);
            $parentrec = new category();
            $parentrec->connection=$this->connection;

            while ($parentcount>0) {
               while ($row_parents = mysql_fetch_assoc($parents) ) {
                    $parentid= $row_parents['parentid'];
                    $parentrec->getcategorybyid($this->shopid,$parentid);
                    $parentnav= "<a href='shop.php?categorycode=".$parentrec->code."'>".$parentrec->header."</a> &middot;".$parentnav;
                    $parentlst = $parentrec->title." ".$parentlst;
               }
               $parents=$this->getparents($parentid);
               $parentcount = mysql_num_rows($parents);
            }

            $cattext = $this->getcategorytext();

            foreach ($cattext as $key=>$value) {
              $s = "<%categorytext_$key%>";

              $text = str_replace($s,$value,$text);
            }

            $text = str_replace('<%category_parentnav%>',$parentnav,$text);
            $text = str_replace('<%category_parents%>',$parentlst,$text);


           return $text;
         }

         function getcatoriesasoption($shopid,$parentid,$categoryid) {

            $categories = $this->getallcategories($shopid,$parentid);

            $out="";

            while ($row=mysql_fetch_assoc( $categories)) {
               $out = $out."<option value='".$row['id']."'";
                if ($row['id']==$categoryid) {
                        $out = $out." selected ";
                }
               $out = $out." >".$row['title']."</option>";
            }

            return $out;

         }

         function getcatoriesaslist($shopid,$parentid,$categoryid) {
            $categories = $this->getallcategories($shopid,$parentid);

            $out="";

            while ($row=mysql_fetch_assoc( $categories)) {

               $hasproducts=$this->hasproducts($row['id'] );
               $haschildren=$this->haschildren($row['id'] );

               if ($haschildren || $hasproducts ) {
                   $out = $out."<li><a href='content.php?action=showcategory&categoryid=".$row['id']."&categorycode=".$row['code']."'>".$row['title']."";
                   $out = $out."</a>";
                   if ( $haschildren ) {
                        $out = $out."<ul>".$this->getcatoriesaslist($shopid, $row['id'], $categoryid)."</ul>";
                   }
                  $out=$out."</li>\n";
               }

            }

            return $out;

         }

         function getcategorytext() {
             global $categorytextdir;
             $result=array();

             if (is_dir($categorytextdir)) {

               $dh  = opendir($categorytextdir);
               while (false !== ($filename = readdir($dh))) {
                      $parts=explode("-",$filename);
                      if ($parts[0]==$this->code ) {
                                $notext = explode(".",$parts[1]);
                                $result[ $notext[0] ]=getfile($categorytextdir."/".$filename);
                      }

               }
             }
             return $result;
         }



 }



?>
