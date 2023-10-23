<?php
    function generatepage($s,$contact,$contactdetail,$prefix) {
           global $session_contact;
           global $templatef;
           global $session;
           global $style;
           global $loginfailed;
           global $regfailed;
           global $registertext;
           global $register;
           global $forgottext;
           global $forgot;
//           global $contactlist;
           global $message;
           global $sid;
           global $phpsid;
           global $navigation;
           global $navigation1;
           global $offset;
           global $perpage;
           global $searchstr;
           global $cid;
           global $pid;
           global $content;
           global $shopid;


        $crypt=new crypt();



         $s=setpriorglobals($s);

              

 //**********************************************************************
 // CONTACTS
 //**********************************************************************

        while (strpos($s,'<%contactsasoptions')>0) {
               $options = tagoptions('<%contactsasoptions',$s);

               $t =     contactlist($contact->userid,'',$options);


               if ($options>"") {
                  $options="|".$options;
               }

               $s = str_replace("<%contactsasoptions".$options."%>", $t  , $s);       //all contacts

        }
		
        if (strpos($s,'<%contactasarray%>')>0) {
		         $options = tagoptions('<%contactsasoptions',$s);
                 $t = contactnameasarray($search,$options) ; 		//contactlist($contact->parentcontactid,'M','');
                 $s = str_replace("<%contactasarray%>", $t , $s);   // just Primary Members
        }


        if (strpos($s,'<%contactparentsasoptions%>')>0) {
                 $t = contactlist($contact->parentcontactid,'M','');
                 $s = str_replace("<%contactparentsasoptions%>", $t , $s); // just Primary Members
        }

		

         $s=setcontact($s,$contact,$contactdetail,$prefix );




 // category

        if (isset($_REQUEST['categoryid'])) {
                        $categoryid=$_REQUEST['categoryid'];
        } else {
                if (isset($contact->categories )) {
                         $categoryid=$contact->categories[0];
                } else {
                          $categoryid=0;
                }
        }
        if (isset($_REQUEST['categoryparentid'])) {
                        $categoryparentid=$_REQUEST['categoryparentid'];
        } else {
                          $categoryparentid=-1;
        }

        if (isset($_REQUEST['categorycode'])) {
                global $category;
                if (isset($category)) {
                    $categoryid=$category->id;
                }
        }


        $s=str_replace("<%offset%>",$offset,$s);

        $noffset=($offset+$perpage);
        $poffset=($offset-$perpage);

        if ($poffset<0) { $poffset=0; }

        $s=str_replace("<%nextoffset%>",$noffset,$s);
        $s=str_replace("<%previousoffset%>",$poffset,$s);
        $s=str_replace("<%perpage%>",$perpage,$s);
        $s=str_replace("<%searchstr%>" ,$searchstr,$s);

		
		
        if ( strpos($s,'<%contactsbycategory%>' ) ) {
                    $directoryitemtemplate = TEMPLATE_FOLDER."directoryitem.tpl";

                    if ($session->active) {
                          $directoryitemtemplate = TEMPLATE_FOLDER."directoryitem_loggedin.tpl";
                          if (file_exists(TEMPLATE_FOLDER."directoryitem_".$session_contact->type.".tpl")) {
                                  $directoryitemtemplate = TEMPLATE_FOLDER."directoryitem_".$session_contact->type.".tpl";
                          }
                    }

                     if ( ($session_contact->type!='A') || (!$session->active) ) {
                             if ($categoryid<1) {
                                     $t =   contactsbycategory($categoryid,"active",'A|M|S2', $directoryitemtemplate,'lastname',$searchstr,'' );
                                     $s=str_replace("<%contactsbycategory%>",$t,$s);
                             } else {
                                     $t = contactsbycategory($categoryid,"active",'A|M|S2', $directoryitemtemplate,'category',$searchstr,'' );
                                     $s=str_replace("<%contactsbycategory%>",$t,$s);
                             }
                     } else {
                             $t=contactsbycategory($categoryid,"","", $directoryitemtemplate,'lastname',$searchstr,'' );
                             $s=str_replace("<%contactsbycategory%>",$t,$s);
                     }
          }


// contacts by category= was here


//******************************************************************************
//CATEGORIES
//******************************************************************************

      //   $options=getoptions('categorylist',categorylist($categoryid) , $s);
      //   $s=replacetag('categorylist',categorylist($categoryid) , $s);



         if ( strpos($s,'<%categorylist%>' ) ) {
              $s = str_replace("<%categorylist%>", categorylist($categoryid,$categoryparentid,'O') , $s);
         }

         if ( strpos($s,'<%categorymenu_c%>' ) ) {
              $s = str_replace("<%categorymenu_c%>", categorylist($categoryid,'1','D') , $s);
         }

         if ( strpos($s,'<%categorymenu_d%>' ) ) {
              $s = str_replace("<%categorymenu_d%>", categorylist($categoryid,'1','D') , $s);
         }

         if ( strpos($s,'<%categorymenu_m%>' ) ) {
              $s = str_replace("<%categorymenu_m%>", categorylist($categoryid,'1','M') , $s);
         }



         $s=setcategory($s,$categoryid);

//******************************************************************************
//PRODUCTS
//******************************************************************************

      //   $options=getoptions('categorylist',categorylist($categoryid) , $s);
      //   $s=replacetag('categorylist',categorylist($categoryid) , $s);

        if (isset($_REQUEST['productid'])) {
                        $productid=$_REQUEST['productid'];
        } else {
                          $productid=-1;
        }


            if ( strpos($s,'<%productlistasoption%>' ) ) {
                  $s = str_replace("<%productlistasoption%>", productlist($productid,$productcategoryid,'O') , $s);
             }

             if ( strpos($s,'<%productlist%>' ) ) {
                  $s = str_replace("<%productlist%>", productlist($productid,$categoryid,'L') , $s);
             }

             $s=setproduct($s,-1);



//******************************************************************************
// OFFERS
//******************************************************************************
             if (OFFERS) {
               if (strpos($s,'<%'.$prefix.'offerlist%>') ) {
                 $owner=false;
                if ($prefix>' ') {
                  $offertemplate="<tr><td><a href='?template=templates/showoffer.html&oid=<%encryptedofferid%>&pid=<%offerencryptedcontactid%>"."&action=profile' ><%offertitle%></a></td>".
                                 "<td><%offerstart%></td><td><%offerexpires%></td><td><%offerstatus%></td>".
                                 "</tr>";
                } else {
                  $offertemplate="<tr><td><a href='?template=templates/admin/editoffers.html&oid=<%encryptedofferid%>' ><%offertitle%></a></td>".
                                 "<td><%offerstart%></td><td><%offerexpires%></td><td><%offerstatus%></td>".
                                 "<td><a href='?template=templates/admin/editoffers.html&doid=<%encryptedofferid%>&action=updateoffers' >Delete</a></td></tr>";
                  $owner=true;
                }

                $s=str_replace('<%'.$prefix.'offerlist%>',showoffersaslist($contact->userid,0,$offertemplate,$owner ),$s);

                $s=str_replace('<%'.$prefix.'fullofferlist%>',showoffersaslist(-1,0,$offertemplate,$owner ),$s);
               }

               if (strpos($s,'offersummary') ) {
                         $offertemplate="<tr><td><h4><%offerstart%></h4></td><td><h4><%offerexpires%></h4></td><td><h4><a href='?template=templates/showoffer.html&oid=<%encryptedofferid%>&prefix=p_&action=profile&pid=<%offerencryptedcontactid%>' ><%offertitle%></a></h4></td>".                                                 "</tr>";
                        $owner=true;
                        $s=str_replace('<%'.$prefix.'offersummary%>',showoffersummary(-1,-1, $offertemplate, $owner ),$s);
               }

               if (strpos($s,'mm_offers') ) {
                       $s=$s=str_replace('<%mm_offers%>',mm_offers(),$s);
               }


               if (strpos($s,'offer') ) {
                       $s=showoffer ($s);
               }

             }



//******************************************************************************
// Event Section
//******************************************************************************
             if (EVENTS) {
               if (strpos($s,'<%'.$prefix.'eventlist%>') ) {

                 $owner=false;

                if ($prefix>' ') {
                  $eventtemplate="<tr><td><a href='?template=templates/showevent.html&eid=<%eventid%>&pid=<%eventencryptedcontactid%>&action=profile' ><%eventtitle%></a></td>".
                                 "<td><%eventlocatio%></td><td><%eventpostcode%></td><td><%eventstatus%></td>".
                                 "</tr>";
                } else {
                  $eventtemplate="<tr><td><a href='?template=<%template%>&eid=<%encryptedeventid%>&pid=<%eventencryptedcontactid%>&action=profile' ><%eventtitle%></a></td>".
                                 "<td><%eventlocation%></td><td><%eventpostcode%></td><td><%eventstatus%></td>".
                                 "<td><a href='?template=templates/admin/editevents.html&deleid=<%encryptedeventid%>&action=updateevents' >Delete</a></td></tr>";
                  $owner=true;
                }

                 $s=str_replace('<%'.$prefix.'eventlist%>',showeventsaslist($contact->userid,$eventtemplate,$owner ),$s);
               }


               if (strpos($s,'mm_events_A') ) {
                       $s=$s=str_replace('<%mm_events_A%>',mm_events('A' ),$s);
               }

               if (strpos($s,'mm_events_M') ) {
                       $s=$s=str_replace('<%mm_events_M%>',mm_events('M' ),$s);
               }

               if (strpos($s,'showevent') ) {
                       $s=showevent ($s,$prefix,$contact);
               }

               if (strpos($s,'eventssummary') ) {
                         $eventtemplate="<tr><td><h4><%eventstart%></h4></td><td><h4><a href='?template=templates/showevent.html&eid=<%encryptedeventid%>&prefix=p_&pid=<%eventencryptedcontactid%>&action=profile' ><%eventtitle%></a></h4></td>".
                                                 "<td><%eventlocation%></td>".
                                                 "</tr>";
                        $owner=true;
                        $s=str_replace('<%'.$prefix.'eventssummary%>',showeventsummary($contactid,$eventtemplate, $owner ),$s);
               }
             }

//******************************************************************************
// FEEDBACK
//******************************************************************************

        if (strpos($s,'feedback_list_approved') ) {
                $s=showfeedbackaslist($s, $contact->userid ,'' , 1);
        }

        if (strpos($s,'feedback_list_all') ) {
                $s=showfeedbackaslist($s, $contact->userid ,'' , 0);
        }

//******************************************************************************
// SAGEPAY
//******************************************************************************
               if (strpos($s,'sageform' )) {
                        $s=writesagepay($s,$contact,$contactdetail);
               }

// *****************************************************************************
// FACEBOOK
// *****************************************************************************
               if (FACEBOOK==1) {
                   include_once( INCLUDES_FOLDER."facebookfunctions.php" );
                   global $facebook;
                   global $user_id;

                   $s = str_replace('<%profileid%>',$facebook->profile_user,$s);
                   $s = str_replace('<%canvasid%>',$facebook->canvas_user,$s);
                   $s = str_replace('<%facebookuser%>', $facebook->get_loggedin_user() ,$s);


               }


// *****************************************************************************
// CHARTER
// *****************************************************************************


               if (CHARTER==1) {
                   include_once( INCLUDES_FOLDER."charterfunctions.php" );

                   $s = generatecharter($s);
				   
               }

// the following has been moved to here because the rental object details are needed before the contactsbycategory

          if (strpos($s,'<%contactsbycategory=')>0) {

                          if (file_exists(TEMPLATE_FOLDER."contactasoptions".".tpl")) {
                                  $directoryitemtemplate = TEMPLATE_FOLDER."contactasoptions".".tpl";
                          }

                 $gcategory=new category();
                 $i = strpos($s,'<%contactsbycategory=');
                 $f=$s;

                 while ($i>0) {
                         $e=substr($s, $i+strlen('<%contactsbycategory='), strlen($s) );
                                                               // contactsbycategory=catname|contactid
                         $c = substr($e,0,strpos($e,'%>'));
                         $sel='';                              //set the contactid to blank
                         $ii=strpos($c,'|');                   // is a contactid needed
                         if ($ii>-1) {                         // yes
                            $sel = substr($c,$ii+1,strlen($c)-2);    //what is it
                            $c=substr($c,0,$ii );                    // seperate category from contactid
                            $r = "<%contactsbycategory=".$c."|".$sel."%>";      // put it back together
                         } else {
                            $r = "<%contactsbycategory=".$c."%>";
                         }

                         $gcategory->id=-1;
                         $gcategory->getcategory($shopid,$c);
                         $offset=0;
                         $perpage=99999;
						 
					
                        $t = contactsbycategory($gcategory->id,"active",'', $directoryitemtemplate,"lastname, firstname",$searchstr,$sel );

                         $s=str_replace($r,$t,$s);
                         $i = strpos($s,'<%contactsbycategory=');


                 }


         }
// *****************************************************************************
// INVOICING
// *****************************************************************************
         //      if (INVOICING==1) {
                    global $invoiceid;
                    include_once INCLUDES_FOLDER."db_invoice.php";
                    include_once INCLUDES_FOLDER."invoicefunctions.php";

                    if (!isset($invoiceid)) {
                        if (isset($_REQUEST['bookingid'])) {
                        
                         $invoice = new invoice();
                         $invoice->getinvoicebyitemid($_REQUEST['bookingid']);
                         $invoiceid=$invoice->id;
                        }
                    }

                   $s = showinvoice($s, $invoiceid );


//               }
//******************************************************************************
// COMMON
//******************************************************************************
//        if ($prefix<' ') {


    //        $s=str_replace("<%contactlist% >",$contactlist,$s);


            // url

            $s=str_replace('<%url%>',$_SERVER['REQUEST_URI'],$s );

            $s=setafterglobals($s);

            if (strpos($s,'navtree') ) {
                $s=str_replace("<%navtree%>",navtree(),$s);
            }

//        }


               return $s;
        }
 //END PAGE CONTROL



// **********************************************************************************************************************************
// START FUNCTIONS
//******************************************************************************

//******************************************************************************
// CATEGORY LIST
//******************************************************************************
        function categorylist($chosen,$categoryparentid,$outtype) {
                include_once INCLUDES_FOLDER."db_category.php";
                global $connection;
                global $shopid;
                $s="";
                $category = new category;
                $category->connection=$connection;
                $category->shopid=$shopid;

//                $categoryrecs = $category->getcategorychildren(0,0);
//                while ($rec = mysql_fetch_assoc($categoryrecs) ) {
//                        $s = $s."<option value='".$rec['id']."'";
//                        if ($chosenid==$rec['id']) { $s=$s." selected "; }
//                       $s = $s." >".$rec['title']."</option>/n";
//                }
// categories top level

              switch ($outtype) {
              case 'O':
                        $s = $category->getcatoriesasoption($shopid,$categoryparentid ,$chosen);
                        break;
              case 'L':
                        $s = $category->getcatoriesaslist($shopid,$categoryparentid,$categoryid);
                        break;

              case 'C':
                       $categoryrecs = $category->getallcategories($shopid,$categoryparentid);
                        break;
              case 'D':
                   //     $s = $category->getcatoriesaslist($shopid,$categoryparentid,$categoryid);

                       $categoryrecs = $category->getallcategories($shopid,$chosen);

                       $template="<li><%category_code%></li>";
                       $templatef= TEMPLATE_FOLDER.'shop/categorychildlist_d.html';

                       if (file_exists($category->childtemplate)) {
                                      $templatef=$category->childtemplate;
                       }
                       if (file_exists($templatef)) {
                               $template=file_get_contents($templatef);
                       }

                        $out='';
                        while ($row=mysql_fetch_assoc( $categoryrecs )) {
                           if ($row['status']=='A') {
                                if  ( $category->hasproducts($row['id'])>0 ) {
                                         $out=$out.setcategory($template,$row['id'] );
                                }
                           }
                        }
                        $s=$out;
                       break;
                case 'M':

                       $categoryrecs = $category->gettopcategories($shopid);
                       $out='';
                       while ($row = mysql_fetch_assoc($categoryrecs) ) {


                               $category->getcategorybyid($shopid,$row['id']);

                               $categoryrecs2 = $category->getallcategories($shopid,$row['id'] );

                               $template="<li><%category_title%></li>";

                               $templatef = TEMPLATE_FOLDER.'/shop/categorychildlist_m.html';

                               if (file_exists($category->childtemplate)) {
                                      $templatef = $category->childtemplate;
                               }

                               if (file_exists($templatef)) {
                                      $template=file_get_contents($templatef);
                               }

                               while ($row2=mysql_fetch_assoc( $categoryrecs2 )) {
                                if ($row2['status']=='A') {
                                        $out=$out.setcategory($template,$row2['id'] );
                                }
                               }

                       }

                       $s=$out;
                       break;

              }


              return $s;

        }


//******************************************************************************
//   SET CATEGORY
//******************************************************************************

        function setcategory($s,$categoryid) {
           include_once INCLUDES_FOLDER."db_category.php";
           global $shopid;
           global $connection;

           $category=new category();
           $category->shopid= $shopid;
           $category->connection=$connection;

           if ($categoryid>0) {
               $category->getcategorybyid($shopid,$categoryid);
           }


            $s = str_replace('<%category_id%>',$category->id, $s);
            $s = str_replace('<%category_ctype%>',$category->ctype, $s);

                $ct="";
                $ct = $ct."<option value='P' ";
                if ($category->ctype=="P") { $ct=$ct." selected "; }
                $ct = $ct." >P</option>";
                $ct = $ct."<option value='C' ";
                if ($category->ctype=="C") { $ct=$ct." selected "; }
                $ct = $ct." >C</option>";

            $s = str_replace('<%category_ctypeasoption%>',$ct, $s);

                $ct="";
                $ct = $ct."<option value='A' ";
                if ($category->status=="A") { $ct=$ct." selected "; }
                $ct = $ct." >Active</option>";
                $ct = $ct."<option value='H' ";
                if ($category->status=="H") { $ct=$ct." selected "; }
                $ct = $ct." >Hold</option>";
                $ct = $ct."<option value='D' ";
                if ($category->status=="D") { $ct=$ct." selected "; }
                $ct = $ct." >Delete</option>";


            $s = str_replace('<%category_statusasoption%>',$ct, $s);

            $s = str_replace('<%category_code%>',$category->code, $s);
            $s = str_replace('<%category_title%>',$category->title, $s);
            $s = str_replace('<%category_description%>',$category->description, $s);
            $s = str_replace('<%category_header%>',$category->header, $s);
            $s = str_replace('<%category_footer%>',$category->footer, $s);
            $s = str_replace('<%category_orderstr%>',$category->orderstr, $s);
            $s = str_replace('<%category_filename%>',$category->filename, $s);

            $idx = 0;

            if (isset($category->attributes)) {
              if ($category->attributes!=null) {
                foreach ( $category->attributes as $key=>$value) {
                   $s = str_replace('<%category_attributes['.$idx.']name%>',$value->name, $s);
                   $s = str_replace('<%category_attributes['.$idx.']description%>',$value->description, $s);
                   $s = str_replace('<%category_attributes['.$idx.']value%>',$value->value, $s);
                   $idx++;
                }
              }
            }

            $idx = 0;
            $medialstadmin="";

            if (isset($category->media)) {
              if ($category->media!=null) {
                foreach ( $category->media as $key=>$value) {

                   $cfile= IMAGE_DIR.'categories/'.$category->id.'/'.$value->mediaf;

                   $s = str_replace('<%category_media['.$idx.']%>',$value->mediaf, $s);

                   $s = str_replace('<%category_media['.$idx.']%>',$value->mediaf, $s);
                   $s = str_replace('<%category_mediaimage['.$idx.']%>','<img src="'.SITE_URL.'imagegen.php?pfile='.$cfile.'" alt="'.$category->title.'" />', $s);
                   $s = str_replace('<%category_mediathumb['.$idx.']%>','<img src="'.SITE_URL.'imagegen.php?thumb=yes&pfile='.$cfile.'"'.' height="'.THUMBHEIGHT.'"  alt="'.$category->title.'" />', $s);


                   $medialstadmin = "<tr id=\"med_$value->id\"><td>$value->mediaf</td><td><a href=\"javascript:removerow('tblmedia','med_".
                                        $value->id."');\">remove</a>".
                                        "<input type=\"hidden\" value=\"$value->id\" id=\"imed_$value->id\" name=\"categorymedia[]\" />".
                                        "<input type=\"hidden\" value=\"0\" id=\"dmed_$value->id\" name=\"mediadeleteflag[$value->id]\" />".
                                        "</td></tr>".$medialstadmin;
                   $idx++;
                }
              }

              $s = str_replace('<%category_mediaadmin%>',$medialstadmin,$s);
            }

            $parentnav = "";
            $parentlst = "";
            $parentlstadmin = "";


            $checkid=$category->id;

            $parents=$category->getparents($category->id);

            $parentcount = mysql_num_rows($parents);
            $parentrec = new category();
            $parentrec->connection=$category->connection;
               $tdx=0;   // we need this to stop loops
            while ($row_parents = mysql_fetch_assoc($parents) ) {
                    $tdx++;

                    $parentid= $row_parents['parentid'];
                    $parentrec->getcategorybyid($shopid,$parentid);
                    $parentnav= "<a href='shop.php?categorycode=".$parentrec->code."'>".$parentrec->header."</a> &middot;".$parentnav;
                    $parentlst = $parentrec->title." ".$parentlst;
                    $parentlstadmin = "<tr id=\"cat_$parentrec->id\"><td>$parentrec->title</td><td> <a href=\"javascript:removerow('tblparents','cat_".
                                      $parentrec->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$parentrec->id\" id=\"icat_$parentrec->id\" name=\"categoryparentidlist[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dcat_$parentrec->id\" name=\"parentdeleteflag[$parentrec->id]\" />".
                                      "</td></tr>".$parentlstadmin;

               }


            $cattext = $category->getcategorytext();

            foreach ($cattext as $key=>$value) {
              $s = "<%categorytext_$key%>";
              $s = str_replace($s,$value,$s);
            }

            $s = str_replace('<%category_parentnav%>',$parentnav,$s);
            $s = str_replace('<%category_parents%>',$parentlst,$s);
            $s = str_replace('<%category_parentsadmin%>',$parentlstadmin,$s);

           return $s;


        }

// *****************************************************************************
// PRODUCTS
// *****************************************************************************

        function productlist($chosen,$productcategoryid,$outtype) {
                include_once INCLUDES_FOLDER."db_product.php";
                global $connection;
                global $shopid;
                $s="";
                $product = new product;
                $product->connection=$connection;
                $category = new category;
                $category->getcategorybyid($shopid,$productcategoryid);

//                $categoryrecs = $category->getcategorychildren(0,0);
//                while ($rec = mysql_fetch_assoc($categoryrecs) ) {
//                        $s = $s."<option value='".$rec['id']."'";
//                        if ($chosenid==$rec['id']) { $s=$s." selected "; }
//                       $s = $s." >".$rec['title']."</option>/n";
//                }
// categories top level

              switch ($outtype) {
              case 'O':

                       $s = $product->getproductsasoption($shopid,$productcategoryid ,$chosen);
                       break;
              case 'L':

                       $productrecs = $product->getproductsbycategory($shopid,$productcategoryid);

                       $template="<li><%product_code%><br/><%product_title%></li>";
                       $templatef= TEMPLATE_FOLDER.'shop/productlist.html';

                       if (file_exists($category->listtemplate)) {
                                      $templatef=$category->listtemplate;
                       }
                       if (file_exists($templatef)) {
                               $template=file_get_contents($templatef);
                       }

                        $s='';
                        while ($row=mysql_fetch_assoc( $productrecs)) {

                           if ($row['status']=='A') {
                                $out = setproduct( $template,$row['id']);

                               $out = setcategory( $out ,$productcategoryid);

                                $s=$s.$out;
                           }

                        }

                       break;
              }


               return $s;

        }

        function setproduct($s,$productid ) {
             global $vatrates;
             global $sitedomain;
             global $shopid;
             global $connection;

                $product=new product();

                $product->shopid=$shopid;
                $product->connection = $connection;

            if (isset($_REQUEST['productid'])) {
                $productid = $_REQUEST['productid'];
            }

           if ($productid>0) {
               $product->getproductbyid($shopid,$productid);

           }

            $s = str_replace('<%product_id%>',$product->id, $s);
            $s = str_replace('<%product_code%>',$product->code, $s);
            $s = str_replace('<%product_title%>',$product->title, $s);
            $s = str_replace('<%product_reference%>',$product->reference, $s);
            $s = str_replace('<%product_status%>',$product->status, $s);
            $s = str_replace('<%product_ptype%>',$product->ptype, $s);
            $s = str_replace('<%product_header%>',$product->header, $s);
            $s = str_replace('<%product_footer%>',$product->footer, $s);
            $s = str_replace('<%product_position%>',$product->position, $s);
            $s = str_replace('<%product_keywords%>',$product->keywords, $s);
            $s = str_replace('<%product_fname%>',$product->fname, $s);
            $s = str_replace('<%product_template%>',$product->template, $s);

            $s = str_replace('<%product_description%>',$product->description, $s);
            $s = str_replace('<%product_shortdescription%>',$product->shortdescription, $s);

            $s = str_replace('<%product_price%>',$product->price, $s);
            $s = str_replace('<%product_vatexempt%>',$product->vatexempt, $s);
            $ct='';
            $ct="<option value='0' ";
            if ($product->vatexempt=='0') {
              $ct=$ct." selected ";
            }
            $ct=$ct.">No</option>";

            $ct=$ct."<option value='1' ";
            if ($product->vatexempt=='1') {
              $ct=$ct." selected ";
            }
            $ct=$ct.">Yes</option>";

            $s = str_replace('<%product_vatexemptasoption%>',$ct, $s);

            $s = str_replace('<%product_vatcode%>',$product->vatcode, $s);
            $vatrate = $vatrates[$product->vatcode];
//            echo $vatrate."<br/>";
            $s = str_replace('<%product_vatrate%>',$vatrate, $s);

            $vatcodeasoption="";
            foreach ($vatrates as  $key=>$value) {
              $vatcodeasoption=$vatcodeasoption."<option value='$key' ";
              if ($key==$product->vatcode) {
                 $vatcodeasoption = $vatcodeasoption." selected ";
              }
              $vatcodeasoption = $vatcodeasoption.">$key</option>";
            }

            $s=str_replace("<%vatcodeasoption%>",$vatcodeasoption,$s);

            $s = str_replace('<%product_deliverycost%>',$product->deliverycost, $s);
            $s = str_replace('<%product_deliveryunit%>',$product->deliveryunit, $s);
            $s = str_replace('<%product_discount%>',$product->discount, $s);
            $s = str_replace('<%product_special%>',$product->special, $s);

            if (GROSSPRICE) {
                    $netprice =  $product->price - ( ($product->price/100)*$vatrate );    }
            else {
                $netprice = $product->price;
            }

            $netamount =  round( $netprice,2 );

            $netamountrounded = round(($netprice+0.4),0);

            $s = str_replace('<%product_netamount%>',$netamount, $s);
            $s = str_replace('<%product_netamountrounded%>',$netamountrounded, $s);


            $vatamount = ($netprice/100) * $vatrate;
            $grossamount = round(($netprice + $vatamount),2) ;
            $grossamountrounded = round(($grossamount+0.4),0);

            $rrpvatamount = ($product->price/100) * $vatrate;
            $rrpgrossamount = round( ($product->price + $rrpvatamount),2);
            $rrpgrossamountrounded = round(( $rrpgrossamount+0.4),0);

            $s = str_replace('<%product_vatamount%>',$vatamount, $s);
            $s = str_replace('<%product_grossamount%>',$grossamount, $s);
            $s = str_replace('<%product_grossamountrounded%>',$grossamountrounded, $s);

            $s = str_replace('<%product_rrpvatamount%>',$rrpvatamount, $s);
            $s = str_replace('<%product_rrpgrossamount%>',$rrpgrossamount, $s);
            $s = str_replace('<%product_rrpgrossamountrounded%>',$rrpgrossamountrounded, $s);

            $s = str_replace('<%product_datecreated%>',$product->datecreated, $s);
            $s = str_replace('<%product_amended%>',$product->amended, $s);
            $s = str_replace('<%product_whoamended%>',$product->whoamended, $s);

            $idx = 0;


            $ct="";
                $ct = $ct."<option value='P' ";
                if ($product->ptype=="P") { $ct=$ct." selected "; }
                $ct = $ct." >P</option>";
                $ct = $ct."<option value='C' ";
                if ($product->ptype=="C") { $ct=$ct." selected "; }
                $ct = $ct." >C</option>";

            $s = str_replace('<%product_ptypeasoption%>',$ct, $s);

                $ct="";
                $ct = $ct."<option value='A' ";
                if ($product->status=="A") { $ct=$ct." selected "; }
                $ct = $ct." >Active</option>";
                $ct = $ct."<option value='H' ";
                if ($product->status=="H") { $ct=$ct." selected "; }
                $ct = $ct." >Hold</option>";
                $ct = $ct."<option value='D' ";
                if ($product->status=="D") { $ct=$ct." selected "; }
                $ct = $ct." >Delete</option>";


            $s = str_replace('<%product_statusasoption%>',$ct, $s);


            if ($product->attributes!=null) {
              foreach ( $product->attributes as $key=>$value) {
                 $s = str_replace('<%product_attributes['.$idx.']name%>',$value->name, $s);
                 $s = str_replace('<%product_attributes['.$idx.']description%>',$value->description, $s);
                 $s = str_replace('<%product_attributes['.$idx.']value%>',$value->value, $s);
                 $s = str_replace('<%product_attributes['.$idx.']valuer%>',round(($value->value+0.4),0), $s);
                 $idx++;
              }
            }

            $idx = 0;
            $medialstadmin="";


            if ($product->media!=null) {
              foreach ( $product->media as $key=>$value) {

                 $pfile= IMAGE_DIR.'products/'.$product->id.'/'.$value->mediaf;

                 $s = str_replace('<%product_media['.$idx.']%>',$value->mediaf, $s);

                 $s = str_replace('<%product_mediaimage['.$idx.']%>','<img src="imagegen.php?pfile='.$pfile.'" alt="'.$product->title.'" />', $s);

                $s = str_replace('<%product_mediathumb['.$idx.']%>','<img src="imagegen.php?thumb=yes&pfile='.$pfile.'"'.' height="'.THUMBHEIGHT.'"  alt="'.$product->title.'" />', $s);

                 $medialstadmin = "<tr id=\"med_$value->id\"><td><a target='blank' href='".$pfile."'>$value->mediaf</a></td><td> <a href=\"javascript:removerow('tblmedia','med_".
                                      $value->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$value->id\" id=\"imed_$value->id\" name=\"productmedia[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dmed_$value->id\" name=\"mediadeleteflag[$value->id]\" />".
                                      "</td></tr>".$medialstadmin;


                 $idx++;
              }
            }

            $s = str_replace('<%product_mediaadmin%>',$medialstadmin,$s);


            $category=new category();
            $ci = count($product->categories)-1;
            if ($ci>=0)  {
                $category->getcategorybyid($product->shopid,$product->categories[$ci]);
            }

            $s = str_replace('<%product_url%>',$sitedomain."/shop/shop.php?categorycode=".$category->code ,$s);

            $mi =  count($category->media)-1;
            if ($mi>-1) {
                    $s = str_replace('<%product_mainimg%>',$sitedomain."/shop/".$category->media[$mi]->mediaf,$s);
            }
            $s = str_replace('<%product_currency%>','GBP' ,$s);





            $parentnav="";
            $parentlst="";
            $parentlstadmin="";
            if (count($product->categories)>0) {
               foreach ($product->categories as $key=>$value) {
                    $category->getcategorybyid($shopid,$value);
                    $parentnav= "<a href='shop.php?categorycode=".$category->code."'>".$category->header."</a> &middot;".$parentnav;
                    $parentlst = $category->title." ".$parentlst;
                    $parentlstadmin = "<tr id=\"cat_$category->id\"><td>$category->title</td><td> <a href=\"javascript:removerow('tblparents','cat_".
                                      $category->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$category->id\" id=\"icat_$category->id\" name=\"productcategoryid[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dcat_$category->id\" name=\"categorydeleteflag[$category->id]\" />".
                                      "</td></tr>".$parentlstadmin;

               }
            }

            $s = str_replace('<%product_parentnav%>',$parentnav,$s);
            $s = str_replace('<%product_parents%>',$parentlst,$s);
            $s = str_replace('<%product_parentsadmin%>',$parentlstadmin,$s);


            // **********************************
            // PRODUCT OPTIONS
            // **********************************
            $productoptiontitle = new productoptiontitle() ;
            $recs=$productoptiontitle->getoptiontitlesbyproduct($product->shopid,$product->id);

            $optiontitles="";

            while ($row=mysql_fetch_assoc($recs)) {
                $optiontitles=$optiontitles."<option value='".$row['optiontitle']."'>".$row['optiontitle']."</option>";
            }
            $s=str_replace('<%product_optiontitles%>',$optiontitles,$s);


           return $s;
        }
// **********************************************************************************************************************************

        function contactsbycategory($categoryid,$status,$ctype, $directoryitemtemplate,$orderby,$searchstr,$id ) {
            include_once INCLUDES_FOLDER."db_subscription.php";
            include_once INCLUDES_FOLDER."db_contactdetail.php";

            global $perpage;
            global $offset;
            global $connection;

            $contactr=contact::getcontactsbycategory($categoryid,$offset,$perpage,$status,$ctype,$orderby,$searchstr );

            $oldsector="";
            $s="";
            $subscriptioni= new subscription();

            while ($row = mysql_fetch_assoc($contactr)) {
                       $contacti = new contact();
                       $contacti->getcontactbyid($row['contactid']);
                       $contactdi = new contactdetail();
                       $contactdi->getcontactdetailbyid($row['contactid'] );

               $showthis=1;
               if (SUBSCRIPTIONS) {
                  $subscriptioni->getsubscriptionsbycontact($row['contactid'],'1');
                  $showthis = !$subscriptioni->expired;
               }

               if ( ( $showthis )  ) {
                       if (($row['title']!=$oldsector) && ($orderby=='category')) {
                         $s=$s. "<h2>".$row['title']."</h2>";
                         $oldsector=$row['title'];
                       }

                       if ($contacti->parentcontactid>0) {
//is a child
//                  $s=$s."<p><i><small>"."<%childcontactcompany % > "."<%childcontacttelephone1% >"." "."<%childcontactusername% >"." "."<%contactpassword% >"."</small></i></p>";
                        } else  {
                                if (file_exists( $directoryitemtemplate) ) {
                                     $s=$s.getfile( $directoryitemtemplate );
                                }
                        }

                        if (strpos($s,'feedback_') ) {
                                $s=showfeedbackaslist($s, $contacti->userid ,'' , 0);
                        }

                        $contacti->selected = "";
						
						
						
                        if (trim($id)==trim($contacti->userid) ) {
                                $contacti->selected = "selected";
								
                        }

                        $s=(setcontact($s,$contacti,$contactdi, '' ));

               }
            }


               return $s;
        }

// **********************************************************************************************************************************

        function setcontact($s,$contact,$contactdetail,$prefix ) {
             global $shopid;

             include_once INCLUDES_FOLDER."db_subscription.php";
             include_once INCLUDES_FOLDER."db_contactdetail.php";
             include_once INCLUDES_FOLDER."db_category.php";
             $crypt=new crypt();

			
                if ($contact->userid=="") {
                   $s=str_replace("<%".$prefix."contactid%>","-1",$s);
                   $s=str_replace("<%".$prefix."encryptedcontactid%>","-1" , $s );
                } else {
                   $s=str_replace("<%".$prefix."contactid%>",$contact->userid,$s);
                   $s=str_replace("<%".$prefix."encryptedcontactid%>",rawurlencode( $crypt->encrypt($contact->userid) ) , $s );
                }


                $s=str_replace("<%".$prefix."contactusername%>",$contact->username,$s);
                $s=str_replace("<%".$prefix."contactpassword%>",$contact->password,$s);

 // bespoke for subscription sites
                if (SUBSCRIPTIONS) {
                    $s1=getsubscriptionsastext ($contact->userid,'1');
                    $s=str_replace("<%subscription1%>",$s1['list'],$s);
                    $s=str_replace("<%subscriptionr1%>",$s1['next'],$s);
                    $s=str_replace("<%subscriptionexpired1%>",$s1['expired'],$s);
                    $s=str_replace("<%subscriptionexpires1%>",$s1['expires'],$s);
                    $s=str_replace("<%subscriptionproductid1%>", '1', $s);

                    $s2=getsubscriptionsastext ($contact->userid , '2');
                    $s=str_replace("<%subscription2%>",$s2['list'],$s);
                    $s=str_replace("<%subscriptionr2%>",$s2['next'],$s);
                    $s=str_replace("<%subscriptionexpired2%>",$s2['expired'],$s);
                    $s=str_replace("<%subscriptionexpires2%>",$s2['expires'],$s);
                    $s=str_replace("<%subscriptionproductid2%>",'2',$s);

                   if ( ($s1['expired']) && ($contact->type!='A') ) {
                           $s=str_replace("<%subscriptionstatus%>",'Subscription Expired',$s);
                   } else {
                           $s=str_replace("<%subscriptionstatus%>",'Subscribed',$s);
                   }

                   if ( ($s2['expired'])  ) {
                            $s=str_replace("<%".$prefix."contacthyperlink%>",'',$s);
                   } else {
                           $s=str_replace("<%".$prefix."contacthyperlink%>","<a href='http://".$contact->webaddress."'>".$contact->webaddress."</a>",$s);
                   }
                } else {
                           $s=str_replace("<%".$prefix."contacthyperlink%>","<a href='http://".$contact->webaddress."'>".$contact->webaddress."</a>",$s);
                }

                $ct="";
                $ct = $ct."<option value='Mrs' ";
                if ($contact->title=="Mrs") { $ct=$ct." selected "; }
                $ct = $ct." >Mrs</option>";
                $ct = $ct."<option value='Miss' ";
                if ($contact->title=="Miss") { $ct=$ct." selected "; }
                $ct = $ct.">Miss</option>";
                $ct = $ct."<option value='Ms' ";
                if ($contact->title=="Ms") { $ct=$ct." selected "; }
                $ct = $ct.">Ms</option>";
                $ct = $ct."<option value='Mr' ";
                if ($contact->title=="Mr") { $ct=$ct." selected "; }
                $ct = $ct.">Mr</option>";
                $ct = $ct."<option value='Dr' ";
                if ($contact->title=="Dr") { $ct=$ct." selected "; }
                $ct = $ct." >Dr</option>";

                $s=str_replace("<%".$prefix."contacttitle%>",$ct,$s);

                $s=str_replace("<%".$prefix."contacttitledisplay%>",$contact->title,$s);

                $s=str_replace("<%".$prefix."contactfirstname%>",$contact->firstname,$s);
                $s=str_replace("<%".$prefix."contactlastname%>",$contact->lastname,$s);
                $s=str_replace("<%".$prefix."contactinitials%>",$contact->initials,$s);
                $s=str_replace("<%".$prefix."contactcompany%>",$contact->company,$s);

                $s=str_replace("<%".$prefix."contactaddress1%>",$contact->address1,$s);
                $s=str_replace("<%".$prefix."contactaddress2%>",$contact->address2,$s);
                $s=str_replace("<%".$prefix."contactaddress3%>",$contact->address3,$s);
                $s=str_replace("<%".$prefix."contacttown%>",$contact->town,$s);
                $s=str_replace("<%".$prefix."contactcounty%>",$contact->county,$s);
                $s=str_replace("<%".$prefix."contactcountry%>",$contact->country,$s);
                $s=str_replace("<%".$prefix."contactpostcode%>",$contact->postcode,$s);

                $s=str_replace("<%".$prefix."contacttelephone1%>",$contact->telephone1,$s);
                $s=str_replace("<%".$prefix."contacttelephone2%>",$contact->telephone2,$s);
                $s=str_replace("<%".$prefix."contactmobile%>",$contact->mobile,$s);
                $s=str_replace("<%".$prefix."contactemail%>",$contact->email,$s);
                $s=str_replace("<%".$prefix."contactwebaddress%>",$contact->webaddress,$s);
                $s=str_replace("<%".$prefix."contactdob%>",$contact->dob,$s);
                $s=str_replace("<%".$prefix."contactdescription%>",$contact->description,$s);
                $s=str_replace("<%".$prefix."contacttype%>",$contact->type,$s);
                $s=str_replace("<%".$prefix."contactselected%>",$contact->selected,$s); 

                $ct="";
                $ct = $ct."<option value='Male' ";
                if ($contact->sex=="Male") { $ct=$ct." selected "; }
                $ct = $ct." >Male</option>";
                $ct = $ct."<option value='Female' ";
                if ($contact->sex=="Female") { $ct=$ct." selected "; }
                $ct = $ct.">Female</option>";

                $s=str_replace("<%".$prefix."contactsex%>",$ct,$s);
                $s=str_replace("<%".$prefix."contactsexdisplay%>",$contact->title,$s);



                $s=str_replace("<%".$prefix."contactdetailreferralsource%>",$ct,$s);

/*
                $ct="";
                $ct = $ct."<option value='A' ";
                if ($contactdetail->mclass=="A") { $ct=$ct." selected "; }
                $ct = $ct." >1 to 20</option>";
                $ct = $ct."<option value='B' ";
                if ($contactdetail->mclass=="B") { $ct=$ct." selected "; }
                $ct = $ct.">21 to 50</option>";
                $ct = $ct."<option value='C' ";
                if ($contactdetail->mclass=="C") { $ct=$ct." selected "; }
                $ct = $ct.">50+</option>";

                $s=str_replace("<%".$prefix."contactdetailmclassasoption%>",$ct,$s);

                $s=str_replace("<%".$prefix."contactdetailmclass%>",$contactdetail->mclass,$s);
*/

                $ct="";
                $ct = $ct."<option value='Y' ";
                if ($contactdetail->newsletter=="Y") { $ct=$ct." selected "; }
                $ct = $ct." >Y</option>";
                $ct = $ct."<option value='N' ";
                if ($contactdetail->newsletter=="N") { $ct=$ct." selected "; }
                $ct = $ct." >N</option>";

                $s=str_replace("<%".$prefix."contactdetailnewsletter%>",$ct,$s);

                $s=str_replace("<%".$prefix."contactdetailnewsletteremail%>",$contactdetail->newsletteremail,$s);

                $ct="";
                $ct = $ct."<option value='active' ";
                if ( ($contact->status=="approved") || ($contact->status=="active") ){ $ct=$ct." selected "; }
                $ct = $ct." >active</option>";
                $ct = $ct."<option value='pending' ";
                if ($contact->status=="pending") { $ct=$ct." selected "; }
                $ct = $ct." >pending</option>";
                $ct = $ct."<option value='S' ";
                if ($contact->status=="S") { $ct=$ct." selected "; }
                $ct = $ct." >suspended</option>";
                $ct = $ct."<option value='deleted' ";
                if ($contact->status=="deleted") { $ct=$ct." selected "; }
                $ct = $ct." >deleted</option>";

                $s=str_replace("<%".$prefix."contactstatusasoption%>",$ct,$s);


		if (CHARTER) {
		
				include_once INCLUDES_FOLDER."db_rentalobjs.php";
                $contact->getrentalsbycontact();
	
                $rentallst = "";
				$rentaltext = "";
                $rentalc = new rentalobj();
				

 
                if (isset($contact->rentals)) {
                   $rdx=0;   // we need this to stop loops
                   foreach ($contact->rentals as $key=>$value) {
                      $rdx++;

                      $rentalc->getrentalobj($value);
					  
                      $rentaltext = $rentaltext.$rentalc->title."<br/>";

                      $rentallst = "<tr id=\"ren_$rentalc->id\"><td>$rentalc->title</td><td> <a href=\"javascript:removerow('tblrentals','ren_".
                                      $rentalc->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$rentalc->id\" id=\"iren_$rentalc->id\" name=\"".$prefix."rentalidlist[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dren_$rentalc->id\" name=\"".$prefix."rentaldeleteflag[$rentalc->id]\" />".
                                      "</td></tr>".$rentallst;

                   }
                }
				
                $s=str_replace("<%".$prefix."contactrentals%>",$rentaltext,$s);

                $s = str_replace("<%".$prefix."contact_rentals%>",$rentallst,$s); 
		}

                $contact->getcategoriesbycontact();
				
                $parentlstadmin = "";
                $cattext="";
                $categoryc = new category();
     //           $categoryc->connection=$connection;

                if (isset($contact->categories)) {
                   $tdx=0;   // we need this to stop loops
                   foreach ($contact->categories as $key=>$value) {
                      $tdx++;
                      $categoryc->getcategorybyid($shopid,$value);
                      $cattext = $cattext.$categoryc->title."<br/>";

                      $parentlstadmin = "<tr id=\"cat_$categoryc->id\"><td>$categoryc->title</td><td> <a href=\"javascript:removerow('tblparents','cat_".
                                      $categoryc->id."');\">remove</a>".
                                      "<input type=\"hidden\" value=\"$categoryc->id\" id=\"icat_$categoryc->id\" name=\"".$prefix."categoryparentidlist[]\" />".
                                      "<input type=\"hidden\" value=\"0\" id=\"dcat_$categoryc->id\" name=\"".$prefix."categorydeleteflag[$categoryc->id]\" />".
                                      "</td></tr>".$parentlstadmin;

                   }
                }

                $s=str_replace("<%".$prefix."contactcategories%>",$cattext,$s);

                $s = str_replace("<%".$prefix."contact_parentsadmin%>",$parentlstadmin,$s);


                if (isset($contactdetail->media)) {
                        if (sizeof($contactdetail->media)>0) {
                                foreach ($contactdetail->media as $key=>$value) {
                                        $s=str_replace("<%mediaf[".$key."]%>",$value['mediaf'],$s);
                                        $s=str_replace("<%medianame[".$key."]%>",$value['medianame'],$s);
                                }
                        }

                     $idx = 0;
                     $contactmedialist="";

                     if ($contactdetail->media!=null) {
                        foreach ( $contactdetail->media as $key=>$value) {
                                $s = str_replace('<%".$prefix."contact_media['.$value['medianame'].']%>',$value['mediaf'], $s);
//                     $s = str_replace('<%".$prefix."contact_media['.$idx.']%>',$value['mediaf'], $s);

                                if (file_exists('profiles/'.$contact->userid.'/'.$value['mediaf'])) {
                                    $m = "<%".$prefix."contact_mediaimg[".$value['medianame']."]%>";
                                    $s = str_replace($m,'<img src="'.SITE_URL.'profiles/'.$contact->userid.'/'.$value['mediaf'].'" />', $s);
                                    $contactmedialist=$contactmedialist."<li>".$value['mediaf']."<a href='".SITE_URL."content.php?template=<%template%>&action=deletecontactmedia&filen=".$value['mediaf']."' >  remove</a></li>";
                                }

                                $idx++;
                        }
                     }

                $s=str_replace("<%".$prefix."contactmedialist%>",$contactmedialist,$s);
         }
                return $s ;


        }

        function contactlist($id,$type,$options) {
            include_once INCLUDES_FOLDER."db_contact.php";
            $crypt = new crypt();
            $o='';
            $order='ORDER BY company, status desc,sortorder';
            $optionsa = explode('&',$options);
            foreach ($optionsa as $key=>$value) {
               $oa = explode("=",$value);
               if ($oa[0]=='orderby') {
                  $order=' order by '.$oa[1];
               }
            }
            
            $recs=contact::getallcontacts( '',$type,0,2000," ".$order." " );

            while ($row=mysql_fetch_assoc($recs)) {
               if ($order=='ORDER BY company, status desc,sortorder') {
                 $o=$o."<option value='".rawurlencode($crypt->encrypt($row['id']))."'";
                 if ($id==$row['id']) {
                   $o=$o." selected ";
                 }
                 $o=$o.">".$row['company']." ".$row['firstname']." ".$row['lastname']." (".$row['type']." ".$row['username']." )"."</option>\n";
               } else {
                 $o=$o."<option value='".rawurlencode($crypt->encrypt($row['id']))."'";
                 if ($id==$row['id']) {
                   $o=$o." selected ";
                 }
                 $o=$o.">".$row['lastname']." ".$row['firstname']." ".$row['company']." (".$row['type']." ".$row['username']." )"."</option>\n";

               }
            }

            return $o;

        }
		
		function contactnameasarray($search,$options) {
            include_once INCLUDES_FOLDER."db_contact.php";
            $crypt = new crypt();
            $o='';
            $order=' lastname, status desc,sortorder';
            $optionsa = explode('&',$options);
            foreach ($optionsa as $key=>$value) {
               $oa = explode("=",$value);
               if ($oa[0]=='orderby') {
                  $order=' order by '.$oa[1];
               }
            }
            
			$categoryid =-1;
			$offset = 0;
			$perpage = 3000;
			$status = '';
			$ctype = '';
			$orderby = $order;
			$searchstr = $search;
			
            $recs=contact::getcontactsbycategory($categoryid,$offset,$perpage,$status,$ctype,$orderby,$searchstr );
							
			$o = "";
			$comma=0;
            while ($row=mysql_fetch_assoc($recs)) {
				if ($comma!=0) {
					$o .= ",";
				}
				$comma=1;
				
				$o .= "{ label: '".
				       addslashes($row['lastname'])." ".addslashes($row['firstname']).
					   "', value:'".
					   rawurlencode($crypt->encrypt($row['contactid'])).
					   "' }\n";
				
            }
			
			$o .= "";
            
			return $o;		
		}

// **********************************************************************************************************************************

        function getsubscriptionsastext ($contactid,$productid ) {
             include_once INCLUDES_FOLDER."db_subscription.php";
             include_once INCLUDES_FOLDER."db_contactdetail.php";
             include_once INCLUDES_FOLDER."db_product.php";
                global $shopid;
                global $session_contact;

                $crypt=new crypt();


                $rsubscription = array();
                $subscription=new subscription();
                $subscriptions=$subscription->getsubscriptionsbycontact($contactid,$productid);

                $product=new product();
                $product->getproductbyid($shopid,$productid);

                $dater=new dater();
                $out="";
                if ($subscription->expires>' ') {
                        $d=substr($subscription->expires,0,2);
                        $m=substr($subscription->expires,3,2);
                        $y=substr($subscription->expires,7,4)+1;
                } else {
                        $d='01';        // bespoke mcoc
                        $m='11';
                        $y=date('Y');

                        if (round(date('Ymd'))>round($y."1101")) {
                           $y=$y+1;
                        }

                }

                $r= date("d/m/Y", mktime(0, 0, 0, $m, $d, $y));

                $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                $dc2= date("Ymd");    // today

                $lastexpiry = "";
                $expired=0;
                if ($dc1<$dc2) {
                    $expired=1;
                }

                $out=$out."<table class='subscriptions' >";
                $out=$out."<caption>".$product->title."</caption>";
                $out=$out."<tr><th>"."Start"."</th>";
                $out=$out."<th>"."Expires"."</th>";
                $out=$out."<th>"."Paid"."</th>";
                if ($session_contact->type=='A') {
                      $out=$out."<th>"." "."</th>";
                }

                $out=$out."</tr>";

                $lastexpiry='';
                while ($row=mysql_fetch_assoc($subscriptions)) {
                   if ($lastexpiry<' ') {
                       $lastexpiry =$dater->sqltophpdate($row['expires']);
                   }

                   $out=$out."<tr><td>".$dater->sqltophpdate($row['start'])."</td>";
                   $out=$out."<td>".$dater->sqltophpdate($row['expires'])."</td>";
                   $out=$out."<td> &pound;".$row['paid']."</td>";

                   if ($session_contact->type=='A') {
                           $esid = urlencode($crypt->encrypt($row['id']));
                           $esid=$row['id'];
                           $out=$out."<td> <a href=\"javascript:deletesubscription('".$esid."')\">delete</a></td>";
                   }

                   $out=$out."</tr>";
                }
                $out=$out."</table>";

                $rsubscription['expired']=$expired;


                $rsubscription['list']=$out;
                $rsubscription['next']=$r;
                $rsubscription['expires']=$lastexpiry;
                $rsubscription['productid']=$productid;

                return $rsubscription;

        }
// **********************************************************************************************************************************

function showoffersaslist($contactid,$productid,$offertemplate,$owner ) {

include_once INCLUDES_FOLDER."db_offers.php";

global $connection;
global $session;


        $offer=new offer();
        $offer->connection=$connection;

        $crypt=new crypt();


        $dater=new dater();
        $out="";


        $recs= $offer->getoffers($contactid,$productid,'','',$session->active,false );

        while ($row=mysql_fetch_assoc($recs) ){
          $show = true;

          if ($row['start']>' ')   {
                 $d=substr($dater->sqltophpdate($row['start']),0,2);
                 $m=substr($dater->sqltophpdate($row['start']),3,2);
                 $y=substr($dater->sqltophpdate($row['start']),7,4);

                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //start date
                 $dc2= date("Ymd");    // today

                 if (round($dc2,0)<round($dc1,0)) {
                        if (!$owner) {
                                 $show=false;
                        }
                 } // not yet started.
          }

          if ($row['expires']>' ')  {
                 $d=substr($dater->sqltophpdate($row['expires']),0,2);
                 $m=substr($dater->sqltophpdate($row['expires']),3,2);
                 $y=substr($dater->sqltophpdate($row['expires']),7,4);

                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                 $dc2= date("Ymd");    // today

                 $expired=0;

                 if (round($dc1,0)<round($dc2,0)) {
                       $expired='1';
                 }
          }

          if (!$owner) {
                if ($row['target']=='members') {
                        if (!$session->active) {
                                $show=false;
                        }
                }
                if ($expired) {
                   $show=false;
                }
                if ($row['status']!='active') {
                   $show=false;
                }
          }

          $to=$offertemplate;

          if ($show) {
                $to=str_replace('<%offerid%>',$row['id'],$to);
                $to=str_replace('<%encryptedofferid%>',rawurlencode($crypt->encrypt($row['id'])),$to);
                $to=str_replace('<%offershopid%>',$row['shopid'],$to);
                $to=str_replace('<%offercontactid%>',$row['contactid'],$to);
                $to=str_replace('<%offerencryptedcontactid%>',rawurlencode($crypt->encrypt($row['contactid'])),$to);
                $to=str_replace('<%offertitle%>',$row['title'],$to);
                $to=str_replace('<%offerstart%>',$dater->sqltophpdate($row['start']) ,$to);
                $to=str_replace('<%offerexpires%>',$dater->sqltophpdate($row['expires']) ,$to);
                $to=str_replace('<%offerexpired%>',$expired ,$to);
                $to=str_replace('<%offerdescription%>',$row['description'],$to);
                $to=str_replace('<%offervouchercode%>',$row['vouchercode'],$to);
                $to=str_replace('<%statustitle%>',$row['status'],$to);
                $to=str_replace('<%offertarget%>',$row['target'],$to);
                $to=str_replace('<%offerstatus%>',$row['status'],$to);
                $to=str_replace('<%offerproductid%>',$row['productid'],$to);
                $to=str_replace('<%offerimpressions%>',$row['impressions'],$to);
                $out=$out.$to;
          }

        }

        return $out;

}

function showoffersummary($contactid,$productid,$offertemplate,$owner ) {

include_once INCLUDES_FOLDER."db_offers.php";

global $connection;
global $session;


        $offer=new offer();
        $offer->connection=$connection;

        $crypt=new crypt();


        $dater=new dater();
        $out="";

        $recs= $offer->getoffers(-1,-1,date('d/m/Y'),'active',$session->active,0 );

        while ($row=mysql_fetch_assoc($recs) ){
          $show = true;
          $contactid = $row['contactid'];
          if ($row['start']>' ')   {
                 $d=substr($dater->sqltophpdate($row['start']),0,2);
                 $m=substr($dater->sqltophpdate($row['start']),3,2);
                 $y=substr($dater->sqltophpdate($row['start']),7,4);

                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //start date
                 $dc2= date("Ymd");    // today

                 if (round($dc2,0)<round($dc1,0)) {
                        if (!$owner) {
                                 $show=false;
                        }
                 } // not yet started.
          }

          if ($row['expires']>' ')  {
                 $d=substr($dater->sqltophpdate($row['expires']),0,2);
                 $m=substr($dater->sqltophpdate($row['expires']),3,2);
                 $y=substr($dater->sqltophpdate($row['expires']),7,4);

                 $dc1= date("Ymd", mktime(0, 0, 0, $m, $d, $y)); //expiry date
                 $dc2= date("Ymd");    // today

                 $expired=0;

                 if (round($dc1,0)<round($dc2,0)) {
                       $expired='1';
                 }
          }

          if (!$owner) {
                if ($row['target']=='members') {
                        if (!$session->active) {
                                $show=false;
                        }
                }
                if ($expired) {
                   $show=false;
                }
                if ($row['status']!='active') {
                   $show=false;
                }
          }

          $to=$offertemplate;

          if ($show) {

                $to=str_replace('<%offerid%>',$row['id'],$to);
                $to=str_replace('<%encryptedofferid%>',rawurlencode($crypt->encrypt($row['id'])),$to);
                $to=str_replace('<%offershopid%>',$row['shopid'],$to);
                $to=str_replace('<%offercontactid%>',$row['contactid'],$to);

                $to=str_replace('<%offerencryptedcontactid%>',rawurlencode($crypt->encrypt(trim($row['contactid']))),$to);

                $to=str_replace('<%offertitle%>',$row['title'],$to);
                $to=str_replace('<%offerstart%>',$dater->sqltophpdate($row['start']) ,$to);
                $to=str_replace('<%offerexpires%>',$dater->sqltophpdate($row['expires']) ,$to);
                $to=str_replace('<%offerexpired%>',$expired ,$to);
                $to=str_replace('<%offerdescription%>',$row['description'],$to);
                $to=str_replace('<%offervouchercode%>',$row['vouchercode'],$to);
                $to=str_replace('<%statustitle%>',$row['status'],$to);
                $to=str_replace('<%offertarget%>',$row['target'],$to);
                $to=str_replace('<%offerstatus%>',$row['status'],$to);
                $to=str_replace('<%offerproductid%>',$row['productid'],$to);
                $to=str_replace('<%offerimpressions%>',$row['impressions'],$to);
                $out=$out.$to;

          }

        }

        return $out;

}

function showoffer ($s) {
include_once INCLUDES_FOLDER."db_offers.php";
global $connection;

        $crypt=new crypt();


        if (isset($_REQUEST['oid'])) {
            $offerid=$crypt->decrypt(rawurldecode($_REQUEST['oid']));
        } else { $offerid=-1; }


        $dater=new dater();

        $offer=new offer();
        $offer->connection=$connection;
        $dater=new dater();
        $offer->getofferbyid($offerid );

        $s=str_replace('<%offerid%>',$offer->id,$s);
        $s=str_replace('<%offershopid%>',$offer->shopid,$s);
        $s=str_replace('<%offercontactid%>',$offer->contactid,$s);
        $s=str_replace('<%offerencryptedcontactid%>',rawurlencode($crypt->encrypt($offer->contactid)),$s);
        $s=str_replace('<%offertitle%>',$offer->title,$s);
        $s=str_replace('<%offerstart%>',$offer->start ,$s);
        $s=str_replace('<%offerexpires%>',$offer->expires ,$s);
        $s=str_replace('<%offerdescription%>',$offer->description,$s);
        $s=str_replace('<%offervouchercode%>',$offer->vouchercode,$s);
        $s=str_replace('<%offerproductid%>',$offer->productid,$s);
        $s=str_replace('<%offerimpressions%>',$offer->impressions,$s);
        $s=str_replace('<%offerexpired%>',$offer->expired,$s);

       $ct="";
       $ct = $ct."<option value='everybody' ";
                if ($offer->target=="everybody") { $ct=$ct." selected "; }
                $ct = $ct." >Everybody</option>";
                $ct = $ct."<option value='members' ";
                if ($offer->target=="members") { $ct=$ct." selected "; }
                $ct = $ct." >Members</option>";
                $s=str_replace("<%offertarget%>",$ct,$s);

       $ct="";
       $ct = $ct."<option value='active' ";
                if ($offer->status=="active") { $ct=$ct." selected "; }
                $ct = $ct." >Active</option>";
                $ct = $ct."<option value='hold' ";
                if ($offer->status=="hold") { $ct=$ct." selected "; }
                $ct = $ct." >Hold</option>";
                $s=str_replace("<%offerstatus%>",$ct,$s);


       return $s;
}

function mm_offers() {

include_once "db_offers.php";

global $connection;
global $session;

    $s='';
    $offer = new offer();
    $dater=new dater();
    $recs = $offer->getoffers(0,0,date('d/m/Y'),'active',$session->active , 1 );
          ;
    $idx=0;
    while ($row=mysql_fetch_assoc($recs) ) {
            $idx++;
            if ($idx>2) { break; }
            $s=$s."<h4>".$row['title']."</h4>";
            $s=$s."<p>Expires: ".$dater->sqltophpdate($row['expires'])." "."</p>";
            $offer->getofferbyid($row['id']);
            $offer->impressions=$offer->impressions+1;
            $offer->updateofferbyid($row['id']);
    }

    return $s;
}

// **********************************************************************************************************************************

function showeventsaslist($contactid,$eventtemplate, $owner ) {
 include_once INCLUDES_FOLDER."db_events.php";

global $connection;
global $session;

        $event=new event();
        $event->connection=$connection;

        $eventdate = new eventdate();
        $eventdate->connection=$connection;

        $dater=new dater();

        $crypt=new crypt();

        $out="";

        $recs= $event->geteventsbycontact( $contactid );


        while ($row=mysql_fetch_assoc($recs) ) {
                  $show = true;

          if (!$owner) {
                if ($row['target']=='members') {
                        if (!$session->active) {
                                $show=false;
                        }
                }

                if ($row['status']!='active') {
                   $show=false;
                }
          }

          $to=$eventtemplate;

          if ($show) {

                $to=str_replace('<%eventid%>',$row['id'],$to);
                $to=str_replace('<%encryptedeventid%>',rawurlencode($crypt->encrypt($row['id'])),$to);
                $to=str_replace('<%eventshopid%>',$row['shopid'],$to);
                $to=str_replace('<%eventcontactid%>',$row['contactid'],$to);
                $to=str_replace('<%eventencryptedcontactid%>',rawurlencode($crypt->encrypt(trim($row['contactid']))),$to);

                $to=str_replace('<%eventtitle%>',$row['title'],$to);
                $to=str_replace('<%eventdescription%>',$row['description'],$to);
                $to=str_replace('<%eventstatus%>',$row['status'],$to);
                $to=str_replace('<%eventtarget%>',$row['target'],$to);
                $to=str_replace('<%eventlocation%>',$row['location'],$to);
                $to=str_replace('<%eventpostcode%>',$row['postcode'],$to);

               $out=$out.$to;


          }

        }

        return $out;
}

function showeventsummary($contactid,$eventtemplate, $owner ) {
include_once INCLUDES_FOLDER."db_events.php";

global $connection;
global $session;

        $event=new event();
        $event->connection=$connection;

        $eventdate = new eventdate();
        $eventdate->connection=$connection;

        $dater=new dater();

        $crypt=new crypt();


        $out="";

        $recs= $event->getallevents( 'active',date('d/m/Y'),'' );


        while ($row=mysql_fetch_assoc($recs) ) {
                  $show = true;

          if (!$owner) {
                if ($row['target']=='members') {
                        if (!$session->active) {
                                $show=false;
                        }
                }
          }

          $to=$eventtemplate;

          if ($show) {
//          title,location,description,target,start,end,displaytext,cost,availability
                $to=str_replace('<%eventid%>',$row['id'],$to);
                $to=str_replace('<%encryptedeventid%>',rawurlencode($crypt->encrypt($row['id'])),$to);

                $to=str_replace('<%eventdateid%>',$row['eventdateid'],$to);
                $to=str_replace('<%encryptedeventdateid%>',rawurlencode($crypt->encrypt($row['eventdateid'])),$to);

                $to=str_replace('<%eventcontactid%>',$row['contactid'],$to);
                $to=str_replace('<%eventencryptedcontactid%>',rawurlencode($crypt->encrypt(trim($row['contactid']))),$to);

                $to=str_replace('<%eventtitle%>',$row['title'],$to);
                $to=str_replace('<%eventlocation%>',$row['location'],$to);
                $to=str_replace('<%eventpostcode%>',$row['postcode'],$to);
                $to=str_replace('<%eventdescription%>',$row['description'],$to);
                $to=str_replace('<%eventtarget%>',$row['target'],$to);

                $to=str_replace('<%eventstart%>',$dater->sqltophpdatetime($row['start'],2),$to);
                $to=str_replace('<%eventend%>',$dater->sqltophpdatetime($row['end'],2),$to);
                $to=str_replace('<%eventdisplaytext%>',$row['displaytext'],$to);
                $to=str_replace('<%eventcost%>',$row['availability'],$to);


               $out=$out.$to;


          }

        }

        return $out;


}

function showevent ($s, $prefix, $contact ) {

include_once INCLUDES_FOLDER."db_events.php";
global $connection;

        $crypt=new crypt();


        if (isset($_REQUEST['eid'])) {
            $eventid=$crypt->decrypt(rawurldecode($_REQUEST['eid']));
        } else { $eventid=-1; }

        if (isset($_REQUEST['edid'])) {
            $eventdateid=$crypt->decrypt(rawurldecode($_REQUEST['edid']));
        } else { $eventdateid=-1; }

        $dater=new dater();

        $event=new event();
        $event->connection=$connection;
        $dater=new dater();

        if ($eventdateid>0) {
          $event->geteventbydateid(trim($eventdateid) );
        } else {
          $event->geteventbyid(trim($eventid) );
        }

      $econtact = new contact();
      $econtact->getcontactbyid($event->contactid);

// now do the eventdates

            if (strpos($s,'<%'.$prefix.'eventdatelist%>') ) {

                $owner=false;
                if ($prefix>' ') {
                        $eventdatetemplate= "<tr>\n";
                        $eventdatetemplate=$eventdatetemplate."<td> <%eventdatestart%></td>";
                        $eventdatetemplate=$eventdatetemplate."<td> <%eventdateend%></td>\n";
                        $eventdatetemplate=$eventdatetemplate."<td> &pound;<%eventdatecost%> <%eventdatedisplaytext%></td>\n";
                        if (($econtact->type=='A') && ($event->acceptpayment) ) {
                                $eventdatetemplate=$eventdatetemplate."<td> <input type='text' value='1' id='qty_<%eventdateid%>' name='qty_<%eventdateid%>' size='2' /></td>\n";
                                $eventdatetemplate=$eventdatetemplate."<td> <input type='button' value='Pay Now' onclick='javascript:payevent( <%eventdateid%>, <%eventdatecost%>,\"<%eventtitle%>\", <%eventdateid%> )' /></td>\n";
                        }
                        $eventdatetemplate=$eventdatetemplate."</tr>";
                } else {
                        $eventdatetemplate= "<tr id='<%eventdateid%>' >\n";
                        $eventdatetemplate=$eventdatetemplate."<td><input type='text' value='<%eventdatestart%>' id='eventstart[<%eventdateid%>]' name='eventstart[<%eventdateid%>]' size='15' />";
                        $eventdatetemplate=$eventdatetemplate."<a href=\"javascript:NewCal('eventstart[<%eventdateid%>]','ddmmyyyy',true,24)\" >";
                        $eventdatetemplate=$eventdatetemplate."<img src='".SITE_URL."images/cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></td>\n";

                        $eventdatetemplate=$eventdatetemplate."<td><input type='text' value='<%eventdateend%>'  id='eventend[<%eventdateid%>]'  name='eventend[<%eventdateid%>]' size='15' />";
                        $eventdatetemplate=$eventdatetemplate."<a href=\"javascript:NewCal('eventend[<%eventdateid%>]','ddmmyyyy',true,24)\" >";
                        $eventdatetemplate=$eventdatetemplate."<img src='".SITE_URL."images/cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></td>\n";

                        $eventdatetemplate=$eventdatetemplate."<td><select id='eventdatestatus[<%eventdateid%>]' name='eventdatestatus[<%eventdateid%>]' >\n";
                        $eventdatetemplate=$eventdatetemplate."<%eventdatestatusasoption%>";
                        $eventdatetemplate=$eventdatetemplate."</select></td>\n";

                        $eventdatetemplate=$eventdatetemplate."<td><input type='text' value='<%eventdatecost%>' id='eventcost[<%eventdateid%>]' name='eventcost[<%eventdateid%>]' size='8'  /></td>\n";
                        $eventdatetemplate=$eventdatetemplate."<td><input type='text' value='<%eventdatedisplaytext%>' id='eventdisplaytext[<%eventdateid%>]' name='eventdisplaytext[<%eventdateid%>]' size='8' /></td>\n";
//                        $eventdatetemplate=$eventdatetemplate."<td><input type='hidden' value='<%eventdateavailability%>' id='eventavailability[<%eventdateid%>]' name='eventavailability[<%eventdateid%>]' size='3' /></td>\n";
                        $eventdatetemplate=$eventdatetemplate."<td><input type='hidden' value='<%eventdateid%>' id='eventdateid[<%eventdateid%>]' name='eventdateid[<%eventdateid%>]' /><a href=\"javascript:deleterow('<%eventdateid%>'); \">delete</a></td>\n";
                        $eventdatetemplate=$eventdatetemplate."</tr>";
                  $owner=true;
                }

                 $s=str_replace('<%'.$prefix.'eventdatelist%>',showeventdatesaslist($contact->userid,$eventid,$eventdatetemplate,$owner ),$s);

               }


        $s=str_replace('<%eventid%>',$event->id,$s);
        $s=str_replace('<%encryptedeventid%>',rawurlencode($crypt->encrypt($event->id)),$s);
        $s=str_replace('<%eventshopid%>',$event->shopid,$s);
        $s=str_replace('<%eventcontactid%>',$event->contactid,$s);
        $s=str_replace('<%eventencryptedcontactid%>',rawurlencode($crypt->encrypt(trim($event->contactid))),$s);
        $s=str_replace('<%eventtitle%>',$event->title,$s);
        $s=str_replace('<%eventdescription%>',$event->description,$s);
        $s=str_replace('<%eventstatus%>',$event->status,$s);
        $s=str_replace('<%eventtarget%>',$event->target,$s);
        $s=str_replace('<%eventacceptpayment%>',$event->acceptpayment,$s);
        $s=str_replace('<%eventlocation%>',$event->location,$s);
        $s=str_replace('<%eventpostcode%>',$event->postcode,$s);


       $ct="";
       $ct = $ct."<option value='everybody' ";
                if ($event->target=="everybody") { $ct=$ct." selected "; }
                $ct = $ct." >Everybody</option>";
                $ct = $ct."<option value='members' ";
                if ($event->target=="members") { $ct=$ct." selected "; }
                $ct = $ct." >Members</option>";
                $s=str_replace("<%eventtargetasoption%>",$ct,$s);


       $ct="";
       $ct = $ct."<option value='active' ";
                if ($event->target=="active") { $ct=$ct." selected "; }
                $ct = $ct." >Active</option>";
                $ct = $ct."<option value='hold' ";
                if ($event->target=="hold") { $ct=$ct." selected "; }
                $ct = $ct." >Hold</option>";
                $s=str_replace("<%eventstatus%>",$ct,$s);

       $ct="";
       $ct = $ct."<option value='1' ";
                if ($event->acceptpayment) { $ct=$ct." selected "; }
                $ct = $ct." >Yes</option>";
                $ct = $ct."<option value='0' ";
                if (!$event->acceptpayment) { $ct=$ct." selected "; }
                $ct = $ct." >No</option>";
                $s=str_replace("<%eventacceptpaymentasoption%>",$ct,$s);



       return $s;
}

function showeventdatesaslist($contactid,$eventid, $eventdatetemplate, $owner ) {
 global $connection;
 global $session;

        $eventdate = new eventdate();
        $eventdate->connection=$connection;

        $dater=new dater();
        $out="";
               // now do the event dates


        $edrecs=$eventdate->getalleventdates( $eventid,'' );

        while ($row=mysql_fetch_assoc($edrecs) ) {
                    $to = $eventdatetemplate;
                    $to=str_replace('<%eventdateid%>',$row['id'],$to);
                    $to=str_replace('<%eventdateshopid%>',$row['shopid'],$to);
                    $to=str_replace('<%eventdatecontactid%>',$row['contactid'],$to);
                    $to=str_replace('<%eventdatestart%>',$dater->sqltophpdatetime($row['start'],2),$to);
                    $to=str_replace('<%eventdateend%>',$dater->sqltophpdatetime($row['end'],2),$to);
                    $to=str_replace('<%eventdatedisplaytext%>',$row['displaytext'],$to);
                    $to=str_replace('<%eventdatecost%>',$row['cost'],$to);
                    $to=str_replace('<%eventdateavailability%>',$row['availability'],$to);
                    $to=str_replace('<%eventdatebooked%>',$row['booked'],$to);
                    $to=str_replace('<%eventdatestatus%>',$row['status'],$to);

       $ct="";
       $ct = $ct."<option value='active' ";
                if ($row['status']=="active") { $ct=$ct." selected "; }
                $ct = $ct." >Active</option>";
                $ct = $ct."<option value='hold' ";
                if ($row['status']=="hold") { $ct=$ct." selected "; }
                $ct = $ct." >Hold</option>";
                $ct = $ct."<option value='fully booked' ";
                if ($row['status']=="fully booked") { $ct=$ct." selected "; }
                $ct = $ct." >Fully Booked</option>";
                
                $to=str_replace('<%eventdatestatusasoption%>',$ct,$to);


                $out=$out.$to;
         }
        return $out;
}

function mm_events($src) {
include_once INCLUDES_FOLDER."db_events.php";
global $connection;

    $s='';
    $event = new event();
    $eventdate = new eventdate();

    $dater=new dater();

    $recs = $event->getallevents( 'active' ,date('d/m/Y'),$src );

    $idx=0;
    while ($row=mysql_fetch_assoc($recs) ) {
            $idx++;
            if ($idx>2) { break; }
            $s=$s."<h4>".$row['title']."</h4>";
            $s=$s."<p>".$dater->sqltophpdatetime($row['start'],2)." ".$row['eventlocation']."</p>";
    }
    return $s;
}


function showfeedbackaslist($s, $id,$whichid,$publish) {

        include_once INCLUDES_FOLDER."db_feedback.php";
        global $connection;
        $dater= new dater();



        $feedback = new feedback();

        $ts = "<li>By <%feedback_name%> on <%feedback_datecreated%> rated <%feedback_rating%> <%feedback_comment%></li>";

        $recs = $feedback->getfeedbackbychoice($id,0,$publish )  ;

        $idx=0;
        $fs="";
        while ($rec = mysql_fetch_assoc($recs)) {
                $idx++;
                $fs=$fs.$ts;
                $fs=str_replace('<%feedback_rating%>',$rec['rating'],$fs);
                $fs=str_replace('<%feedback_title%>',$rec['title'],$fs);
                $fs=str_replace('<%feedback_comment%>',$rec['comment'],$fs);
                $fs=str_replace('<%feedback_name%>',$rec['name'],$fs);
                $fs=str_replace('<%feedback_publish%>',$rec['publish'],$fs);
                $fs=str_replace('<%feedback_ip%>',$rec['ip'],$fs);
                $fs=str_replace('<%feedback_datecreated%>',$dater->sqltophpdate( $rec['datecreated'] ),$fs);
                $fs=str_replace('<%feedback_datemodified%>',$dater->sqltophpdate(  $rec['datemodified']),$fs);

        }

        $s=str_replace('<%feedback_list%>',$fs,$s);

        $s=str_replace('<%feedback_count%>',$idx,$s);

        return $s;
}



function writesagepay($s,$contact,$contactdetil) {
    include_once INCLUDES_FOLDER."sagepay_includes.php";
    global $strcrypt;
    global $order;

    $sagepay = new sagepay();

// $this->addtobasket($_REQUEST['description'],$quantity, $nett,$tax,$itemgross,$linetotal )
         $qty = $_REQUEST['qty'];
         $net = $_REQUEST['price'];
         $tax = $_REQUEST['tax'];
         $gross = ($tax+$net);
         $total = $gross*$qty;

    if (isset($_REQUEST['cart'])) {
             $cart = $_REQUEST['cart'];
             $amount = 0;
             $cartlines = explode('|',$cart);
             foreach ($cartlines as $key=>$value ) {

               $linedetail = explode('~',$value);

               $code = $linedetail[0];
               $description =  $linedetail[1];
               $qty = $linedetail[3];
               $net = $linedetail[2];
               $taxrate = $linedetail[5];
               $deliveryrate = $linedetail[6];
               $tax   = $net/100 * $taxrate;
               $gross = ($tax+$net);
               $total = $gross*$qty;

// $this->addtobasket($_REQUEST['description'],$quantity, $nett,$tax,$itemgross,$linetotal )
               if ($net>0) {
                       $sagepay->addtobasket($code."-".$description ,$qty, $net,$tax,$gross,$total );
                    //   echo "descriptin: ",$code."-".$description."<br/>";
                    //   echo "Quantity ".$qty."<br/>";
                    //   echo "Nett ".$net."<br/>";
                    //   echo "Tax ".$tax."<br/>";
                    //   echo "Gross ".$gross."<br/>";
                   //    echo "Total ".$total."<br/>";
                       $amount = $amount + $total;
               }


             }
         }


         $sagepay->buildbaskettext();


    $intRandNum = rand(0,32000)*rand(0,32000);

    if (isset($_REQUEST['paytype'])) {
      $paytype=$_REQUEST['paytype'];
    }

    switch ($paytype) {
        case 'S':        //subscription
                        $sagepay->VendorTxCode = $contact->userid."-S-".$_REQUEST['productid']."-".
                                 substr($_REQUEST['subscribeto'],6,4).
                                 substr($_REQUEST['subscribeto'],3,2).
                                 substr($_REQUEST['subscribeto'],0,2).
                                 '-'.
                                 substr($_REQUEST['subscribefrom'],6,4).
                                 substr($_REQUEST['subscribefrom'],3,2).
                                 substr($_REQUEST['subscribefrom'],0,2).
                                 '-'.
                                 $intRandNum;
                        break;
        CASE 'E':        //event
                        $sagepay->VendorTxCode = $contact->userid."-".$_REQUEST['paytype']."-".$_REQUEST['eventdateid'].'-'.$intRandNum;
                        break;
    }





    $sagepay->VendorTxCode =  '';

    $sagepay->VendorTxCode = "O"."-".$order->id.'-'.$intRandNum;



    if (isset($_REQUEST['grossamount'])) {
        $sagepay->Amount = $_REQUEST['grossamount'];
    } else {
        $sagepay->Amount = $amount;
    }



    $sagepay->Description = "Order Form" ;
    $sagepay->o_CustomerName=$contact->company;
    $sagepay->o_CustomerEMail=$contact->email;

    $sagepay->BillingSurname=$contact->lastname;
    $sagepay->BillingFirstnames=$contact->firstname;
    $sagepay->BillingAddress1=$contact->address1;
    $sagepay->o_BillingAddress2=$contact->address2;
    $sagepay->BillingCity=$contact->town;
    $sagepay->BillingPostCode=$contact->postcode;
    $sagepay->BillingCountry=$contact->country;
    $sagepay->o_BillingState=$contact->county;
    $sagepay->o_BillingPhone=$contact->telephone1;

    if (isset($contact->addresses)) {
        foreach ($contact->addresses as $key=>$address) {
            if ($address->addresstype="D") {
                    $sagepay->DeliverySurname=$contact->lastname;
                    $sagepay->DeliveryFirstnames=$contact->firstname;
                    $sagepay->DeliveryAddress1=$address->address1;
                    $sagepay->o_DeliveryAddress2=$address->address2;
                    $sagepay->DeliveryCity=$address->town;
                    $sagepay->DeliveryPostCode=$address->postcode;
                    $sagepay->DeliveryCountry=$address->country;
                    $sagepay->o_DeliveryState=$address->county;
                    $sagepay->o_DeliveryPhone=$contact->telephone1;

            }
        }
    }
//     echo $sagepay->o_Basket;


    $s=str_replace( '<%paymentdescription%>',$sagepay->Description,$s );

    $s=str_replace( '<%paymentamount%>',$sagepay->Amount,$s );

    $s=str_replace( '<%sageform%>',$sagepay->generateform(),$s );

    return $s;

}


function navtree() {
    include_once INCLUDES_FOLDER."db_navtree.php";
    global $connection;
    global $shopid;

    $navtree= new navtree();
    $navtree->connection = $connection;
    $navtree->shopid=$shopid;
    $content = "";


    foreach ($navtree->buildtree(0) as $key=>$value ) {
         $content = $content.$value;
    };

    return $content;
}

function getoptions($tagname,$value,$s) {
      $starttag="<%";
      $endtag="%>";

      $sdx = strpos($s,$starttag.$tagname );

      if ($sdx>0) {
              $startstr=substr($s,0,$sdx);
              $wrkstr2 = substr($s,$sdx+strlen( $starttag.$tagname ) , strlen($s) );
              $edx = strpos($wrkstr2,$endtag );
              $optionstr = substr($wrkstr2,0,$edx);

              $options = explode(" ",$optionstr);

      }
             print_r($options);
      return $options;
}

function replacetag($tagname,$value,$s) {
      $starttag="<%";
      $endtag="%>";

      $sdx = strpos($s,$starttag.$tagname );

      if ($sdx>0) {
              $startstr=substr($s,0,$sdx);
              $wrkstr2 = substr($s,$sdx+strlen( $starttag.$tagname ) , strlen($s) );

              $edx = strpos($wrkstr2,$endtag );
              $options = substr($wrkstr2,0,$edx);
              $endstr = substr($wrkstr2,$edx+strlen($endtag),strlen($wrkstr2) );

              $s = $startstr.$value.$endstr;
      }

      return $s;



}

function setpriorglobals($s) {
           global $session;
           global $style;
           global $printstyle;
           global $loginfailed;
           global $regfailed;
           global $registertext;
           global $register;
           global $forgottext;
           global $forgot;
           global $session_contact;

        $style=STYLESHEET_FOLDER.'stylesheet.css';
        $printstyle=STYLESHEET_FOLDER.'printstyle.css';

        if ($session->active) {
             $login=getfile(TEMPLATE_FOLDER.'loggedin.tpl');
             if (file_exists(STYLESHEET_FOLDER.'stylesheet-loggedin.css')) {
                     $style=STYLESHEET_FOLDER.'stylesheet-loggedin.css';
             }
        } else {
             $login=getfile(TEMPLATE_FOLDER.'login.tpl');
             if (file_exists(STYLESHEET_FOLDER.'stylesheet-loggedout.css')) {
                     $style=STYLESHEET_FOLDER.'stylesheet-loggedout.css';
             }
        }

        $footer=getfile(TEMPLATE_FOLDER.'footer.tpl');
        $navigation=getfile(TEMPLATE_FOLDER.'navigation.tpl');

//        include('templates/navigation1.php');


        if (file_exists(TEMPLATE_FOLDER.'header.tpl')) {
                $header=getfile(TEMPLATE_FOLDER.'header.tpl');
        }

        if (file_exists(TEMPLATE_FOLDER.'header-pre.tpl')) {
                 $headerpre=getfile(TEMPLATE_FOLDER.'header-pre.tpl');
        }

        if (file_exists(TEMPLATE_FOLDER.'footer.tpl')) {
                $footer=getfile(TEMPLATE_FOLDER.'footer.tpl');
        }

        if ($session->active) {


           if (file_exists(TEMPLATE_FOLDER.'header_li.tpl')) {
                   $header=getfile(TEMPLATE_FOLDER.'header_li.tpl');
           }

           if (file_exists(TEMPLATE_FOLDER.'footer_li.tpl')) {
                   $footer=getfile(TEMPLATE_FOLDER.'footer_li.tpl');
           }

            if (file_exists(TEMPLATE_FOLDER.'header_'.$session_contact->type.'.tpl')) {
                $header=getfile(TEMPLATE_FOLDER.'header_'.$session_contact->type.'.tpl');
            }
            if (file_exists(TEMPLATE_FOLDER.'footer_'.$session_contact->type.'.tpl')) {
                $footer=getfile(TEMPLATE_FOLDER.'footer_'.$session_contact->type.'.tpl');
            }
        }



        $dater= new dater();
            $s=str_replace("<%header%>",$header,$s);
            $s=str_replace("<%headerpre%>",$headerpre,$s);
            $s=str_replace("<%footer%>",$footer,$s);

            $s=str_replace("<%stylesheet%>",$style,$s);
            $s=str_replace("<%printstyle%>",$printstyle,$s);
            $s=str_replace("<%stylesheetfolder%>",STYLESHEET_FOLDER,$s);
            $s=str_replace("<%login%>",$login,$s);
            $s=str_replace("<%loginfailed%>",$loginfailed,$s);
            $s=str_replace("<%regfailed%>",$regfailed,$s);
            $s=str_replace("<%registertext%>",$registertext,$s);
            $s=str_replace("<%register%>",$register,$s);
            $s=str_replace("<%forgottext%>",$forgottext,$s);
            $s=str_replace("<%forgot%>",$forgot,$s);

        return $s;
}

function setafterglobals($s) {

           global $session;
           global $style;
           global $loginfailed;
           global $regfailed;
           global $registertext;
           global $register;
           global $forgottext;
           global $forgot;
           global $session_contact;
           global $message;
           global $content;
           global $navigation;
         //  global $$navigation1;
           global $templatef;
           global $sid;
           global $vatrates;


        $style=STYLESHEET_FOLDER.'stylesheet.css';


        $topnav=getfile(TEMPLATE_FOLDER.'topnav.tpl');

        if (file_exists(TEMPLATE_FOLDER.'menu.tpl')) {
                $menu=getfile(TEMPLATE_FOLDER.'menu.tpl');
        }
        if (file_exists(TEMPLATE_FOLDER.'sidemenu.tpl')) {
                $sidemenu=getfile(TEMPLATE_FOLDER.'sidemenu.tpl');
        }

        if ($session->active) {


           if (file_exists(TEMPLATE_FOLDER.'menu_li.tpl') ) {
                   $menu=getfile(TEMPLATE_FOLDER.'menu_li.tpl');
           }

           if (file_exists(TEMPLATE_FOLDER.'sidemenu_li.tpl')) {
                   $sidemenu=getfile(TEMPLATE_FOLDER.'sidemenu_li.tpl');
           }

           if (file_exists(TEMPLATE_FOLDER.'menu_'.$session_contact->type.'.tpl')) {
                $menu=getfile(TEMPLATE_FOLDER.'menu_'.$session_contact->type.'.tpl');
            }


            if (file_exists(TEMPLATE_FOLDER.'sidemenu_'.$session_contact->type.'.tpl')) {
                $sidemenu=getfile(TEMPLATE_FOLDER.'sidemenu_'.$session_contact->type.'.tpl');
            }
      
        }

             $dater= new dater();

            $s=str_replace("<%stylesheet%>",$style,$s);
            $s=str_replace("<%stylesheetfolder%>",STYLESHEET_FOLDER,$s);
            $s=str_replace("<%sidemenu%>",$sidemenu,$s);
            $s=str_replace("<%menu%>",$menu,$s);



      //      $s=str_replace("<%phpsid%>",$phpsid,$s);
            $s=str_replace("<%message%>",$message,$s);
            $s=str_replace("<%content%>",$content,$s);

            $s=str_replace("<%navigation%>",$navigation,$s);
         //   $s=str_replace("<%navigation1%>",$navigation1,$s);


            $s=str_replace("<%template%>",$templatef,$s);
            $s=str_replace("<%sid%>",$sid,$s);

            $s=str_replace("<%topnav%>",$topnav,$s);

            $s=str_replace("<%IMAGE_DIR%>",IMAGE_DIR,$s);
            $s=str_replace("<%SITE_NAME%>",SITE_NAME,$s);
            $s=str_replace("<%SITE_URL%>",SITE_URL,$s);
            $s=str_replace("<%SITE_ADMINEMAIL%>",SITE_ADMINEMAIL,$s);
            $s=str_replace("<%SITE_SECURE_URL%>",SITE_SECURE_URL,$s);
            $s=str_replace("<%orderaction%>",ORDER_ACTION,$s);

            $s=str_replace("<%TODAY%>",date('d/m/Y'),$s);

            $ct="";
            foreach ($vatrates as  $key=>$value) {
              $ct=$ct."vatrate[".$key."]=".$value.";";
            }
            $s=str_replace("<%vatrates%>",$ct,$s);

        return $s;
}

// AJAX

function ajax_getcontactsbycategory( ) {
            global $perpage;
            global $offset;

     $directoryitemtemplate ="";
     $categoryid=-1;
     $searchstr="";
     $sel="";
     $perpage=10000;
     $offset=0;

     if (file_exists(TEMPLATE_FOLDER."contactasoptions".".tpl")) {
         $directoryitemtemplate = TEMPLATE_FOLDER."contactasoptions".".tpl";
     }

     if (isset($_REQUEST['categoryid'])) {
             $categoryid = $_REQUEST['categoryid'];
     }

     if (isset($_REQUEST['searchstr']))   {
             $searchstr = $_REQUEST['searchstr'];
     }

     if (isset($_REQUEST['sel'])) {
             $sel = $_REQUEST['contactid'];
     }

     $t =contactsbycategory($categoryid, "active",'', $directoryitemtemplate,"lastname",$searchstr,$sel ) ;

     echo $t;exit;
}

?>
