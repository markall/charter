<?php

  class navtree {
    var $connection;
    var $shopid;
    var $tid;


    function buildtree($shopid) {
            $text = array();
            $text[] = "<div class='dtree'>";
            $text[] = '<p><a href="javascript: d.openAll();">open all</a>';
            $text[] = ' | <a href="javascript: d.closeAll();">close all</a></p>';
            $text[] = "<script type='text/javascript'>
                                <!--
                                d = new dTree('d');
                                d.add(0,-1,'Navigation tree');
                                ";
            $category = new category();
            $category->connection=$this->connection;
            $category->shopid=$this->shopid;

            $categories=$category->gettopcategories($category->shopid);
            $this->tid = 1;

            if (mysql_num_rows($categories)>0) {
              while  ($row_category= mysql_fetch_assoc( $categories )) {
                 if ($row_category['parents']<1) {  // only needed iv sql v4
                     $category->getcategorybyid($this->shopid,$row_category['id']);
                     $text[]= "d.add(".$row_category['id'].",0,'".$category->code."','#' );";
//                     $text = $text.$this->writechildren($this->tid,$category);
                     $this->tid++;
                 }

              }
            }

            $query = sprintf ( "SELECT * FROM ".TBL_PREFIX."categoryparents right join ".TBL_PREFIX."categories on ".TBL_PREFIX."categoryparents.categoryid=".TBL_PREFIX."categories.id where ".TBL_PREFIX."categoryparents.shopid='%s' ", $this->shopid );
            $children= dosql($query);
            if (mysql_num_rows($children)>0) {
              while  ($row_child= mysql_fetch_assoc( $children )) {
                     $text[] = "d.add(".$row_child['categoryid'].",".$row_child['parentid'].",'".$row_child['code']."','content.php?template=templates/admin/managecategories.html&prefix=p&categoryid=".$row_child['id']."' );";
              }
            }

// id Number Unique identity number.
// pid Number Number refering to the parent node. The value for the root node has to be -1.
// name String Text label for the node.
// url String Url for the node.
// title String Title for the node.
// target String Target for the node.
// icon String Image file to use as the icon. Uses default if not specified.
// iconOpen String Image file to use as the open icon. Uses default if not specified.
// open Boolean Is the node open.

           $query = sprintf ( "SELECT * FROM ".TBL_PREFIX."productcategories right join ".TBL_PREFIX."products on ".TBL_PREFIX."productcategories.productid=".TBL_PREFIX."products.id where ".TBL_PREFIX."productcategories.shopid='%s' ", $this->shopid );
           $products= dosql($query);
            if ((mysql_num_rows($products)>0) && (mysql_num_rows($products)<1000)) {
              while  ($row_product= mysql_fetch_assoc( $products )) {
                     $text[]= "d.add(1000".$row_product['id'].",".
                                           $row_product['categoryid'].",'".
                                           $row_product['code'].
                                           "','content.php?template=templates/admin/manageproducts.html&prefix=p&productid=".$row_product['id']."',".
                                           "'".$row_product['title']." ',".
                                           "'',".
                                           "'scripts/dtree/img/product.gif',".
                                           "'scripts/dtree/img/productopen.gif'".
                                           " );";
              }
            }

            $text[] = 'document.write(d);
                                //-->
                                </script></div>';

            return $text;

    }

    function writechildren($pid,$category ) {
            $query = sprintf ( "SELECT * FROM categoryparents where shopid='%s' ", $this->shopid );
            $children= dosql($query);
 //           $children = $category->getcategorychildren( $category->id );
                     $this->tid++;
            if (mysql_num_rows($children)>0) {
              while  ($row_child= mysql_fetch_assoc( $children )) {
                     $category->getcategorybyid($this->shopid,$row_children['categoryid']);
                     $text=$text."d.add(".$row_children['categoryid'].",".$row_children['parentid'].",'".$category->code."','#' );";
//                     $text = $text.$this->writechildren($this->tid,$category);
              }
            }

        return $text;
    }

  }

?>
