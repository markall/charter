<?php
    global $connection;
    global $template;
    $requested = empty($_SERVER['REQUEST_URI']) ? false : $_SERVER['REQUEST_URI'];


    if (MODXLINK==1) {
         $friendly_alias_urls = 0;
         $friendly_urls = 0;
         $friendly_url_prefix='';
         $friendly_url_suffix='.html';

         $query = "select * from modx_system_settings" ;
         $sysres = mysql_query($query, $connection) or die($query." ".mysql_error());


         while  ($row = mysql_fetch_assoc($sysres) ) {
            if ($row['setting_name']=='friendly_alias_urls') {
               $friendly_alias_urls = $row['setting_value'];
            }
            if ($row['setting_name']=='friendly_urls') {
               $friendly_urls = $row['setting_value'];
            }
            if ($row['setting_name']=='friendly_url_prefix') {
               $friendly_url_prefix = $row['setting_value'];
            }
            if ($row['setting_name']=='friendly_url_suffix') {
               if  (strlen($row['setting_value'])>0) {
                 $friendly_url_suffix = $row['setting_value'];
               }
            }
         }

         $query = "select * from modx_site_content" ;
         $res =   mysql_query($query, $connection) or die($query." ".mysql_error());
         while  ($row = mysql_fetch_assoc($res) ) {
             if ($row['published']==1) {
                $alias = $friendly_url_prefix.$row['alias'].$friendly_url_suffix;
                if ( strpos($requested,$alias)>0 ) {
                     $defaulttemplate= SITE_URL."/content/".$alias;
                }
             }
         }
    }


?>
