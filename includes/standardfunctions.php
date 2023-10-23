<?php
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

#  $theValue=mysql_real_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }


  return $theValue;
}

class dater {

      var $month;
      var $year;
      var $mdate;
      var $day;
      var $hour;
      var $minute;
      var $second;

    function validdate($indate) {
           $r=true;
      if (strlen($indate)==10) {
             $this->year=substr($indate,6,4);    
             $this->month=substr($indate,3,2);
             $this->mdate=substr($indate,0,2);
             $odate=$this->year.'/'.$this->month.'/'.$this->mdate;
      }
      if (strlen($indate)==19) {
            $this->year=substr($indate,6,4);
            $this->month=substr($indate,3,2);
            $this->mdate=substr($indate,0,2);
            $this->hour=substr($indate,11,2);
            $this->minute=substr($indate,14,2);
            $this->second=substr($indate,17,2);
            $odate=$this->year.'/'.$this->month.'/'.$this->mdate.' '.$this->hour.':'.$this->minute.':'.$this->second;
      }
                $r= checkdate($this->month,$this->mdate,$this->year);
                if (($this->hour>24) || ($this->minute>60) || ($this->second>60) ) { $r=false; }

                return $r;
        }

    function sqltophpdatetime($indate,$n) {

      $odate=$indate;
      if (strlen($indate)==10) {
              $this->year=substr($indate,0,4);
              $this->month=substr($indate,5,2);
              $this->mdate=substr($indate,8,2);
              $this->hour = 0;
              $this->minute = 0;
              $this->second=0;
              $odate=$this->mdate.'/'.$this->month.'/'.$this->year;
      }

      if (strlen($indate)==19) {
              $this->year=substr($indate,0,4);
              $this->month=substr($indate,5,2);
              $this->mdate=substr($indate,8,2);
              $this->hour=substr($indate,11,2);
              $this->minute=substr($indate,14,2);
              $this->second=substr($indate,17,2);
              switch ($n) {
              case 1:
                      $odate=$this->mdate.'/'.$this->month.'/'.$this->year.' '.$this->hour.':'.$this->minute.':'.$this->second;
                      break;
              case 2:
                      $odate=$this->mdate.'/'.$this->month.'/'.$this->year.' '.$this->hour.':'.$this->minute;
                      break;
              case 3:
                      $odate=$this->mdate.'/'.$this->month.'/'.$this->year.' '.$this->hour;
                      break;
              }
      }


      return $odate;
    }

    function sqltophpdate($indate) {

      $odate=$indate;

      $this->year=substr($indate,0,4);
      $this->month=substr($indate,5,2);
      $this->mdate=substr($indate,8,2);
      $this->hour = 0;
      $this->minute = 0;
      $this->second=0;
      $odate=$this->mdate.'/'.$this->month.'/'.$this->year;

      return $odate;

    }


    function phptosqldate($indate) {
      $odate='';

      $indate = str_replace('-','/',$indate);
      $indate = str_replace('.','/',$indate);

//  echo $indate."<br/>";

      if (strlen($indate)==10) {
             $this->year=substr($indate,6,4);    
             $this->month=substr($indate,3,2);
             $this->mdate=substr($indate,0,2);
             $odate=$this->year.'/'.$this->month.'/'.$this->mdate;
      }
      if (strlen($indate)==19) {
            $this->year=substr($indate,6,4);
            $this->month=substr($indate,3,2);
            $this->mdate=substr($indate,0,2);
            $this->hour=substr($indate,11,2);
            $this->minute=substr($indate,14,2);
            $this->second=substr($indate,17,2);
            $odate=$this->year.'/'.$this->month.'/'.$this->mdate.' '.$this->hour.':'.$this->minute.':'.$this->second;
      }

       if (strlen($indate)==16) {
       # 26/06/2009 19:17
            $this->year=substr($indate,6,4);
            $this->month=substr($indate,3,2);
            $this->mdate=substr($indate,0,2);
            $this->hour=substr($indate,11,2);
            $this->minute=substr($indate,14,2);
        //    $this->second=substr($indate,15,2);
            $odate=$this->year.'/'.$this->month.'/'.$this->mdate.' '.$this->hour.':'.$this->minute;
      }
      if (strlen($indate)==17) {
        #    08/07/08 12:18:44
            $this->year=substr($indate,6,2);
            $this->month=substr($indate,3,2);
            $this->mdate=substr($indate,0,2);
            $this->hour=substr($indate,9,2);
            $this->minute=substr($indate,12,2);
            $this->second=substr($indate,15,2);
            $odate=$this->year.'/'.$this->month.'/'.$this->mdate.' '.$this->hour.':'.$this->minute.':'.$this->second;
      }


      return $odate;

    }

    function mkdate($d) {
//        dd/mm/yyyy hh:nn
        $dd = substr($d,0,2);
        $mm = substr($d,3,2);
        $yy = substr($d,6,4);
        $hh = substr($d,11,2);
        $mn = substr($d,14,2);

        $r=mktime($hh,$mn, 0, $mm,$dd , $yy)  ;
        return $r;

    }
}

class crypt {
#    private $securekey, $iv;
#    function __construct($textkey) {
#        $this->securekey = hash('sha256',$textkey,TRUE);
#        $this->iv = mcrypt_create_iv(32);
#    }
#    function encrypt($input) {
#        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
#    }
#    function decrypt($input) {
#        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
#    }

        var $iv_size;
        var $iv;

        function crypt() {
                    $this->securekey = hash('sha256',"This is a very secret key",TRUE);
 //                 $this->securekey = 'Not so secret key';
                    $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
                    $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);

        }



        function encrypt( $value ) {
          $res = (base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $value, MCRYPT_MODE_ECB, $this->iv )));
          if (strlen($res)>0) {
                $e= $res;
          } else {
                $e=$value;
          }
          return $e;
        }

        function decrypt( $value) {
               $res=(mcrypt_decrypt( MCRYPT_RIJNDAEL_256,$this->securekey,(base64_decode($value)),MCRYPT_MODE_ECB,$this->iv));
          if (strlen($res)>0) {
                $e= $res;
          } else {
                $e=$value;
          }

               return $e;
        }

}

function lastinsertid() {
   global $contacts;
   $query = 'SELECT LAST_INSERT_ID()';
   $lastid = dosql($query);
   $lpos =  strpos($lastid,'#',0);
   $lsub =  substr($lastid,$lpos+1);
   $temp_array = mysql_fetch_array(mysql_query("select last_insert_id()"));
   $my_last_id = $temp_array['last_insert_id()'];
   return $my_last_id;
}

function getfile($filename) {

    $theresult= @file_get_contents($filename);

    return $theresult;
}

function savefile($filename,$content) {
                $file = fopen($filename,"w");
                fwrite($file,$content);
                fclose($file);
}

function strip2($value) {
        return trim(stripslashes($value));
}

function tidytags($value) {

   $sdx=strpos($value,'<%');
   $edx=strpos($value,'%>');

   while (($sdx>0) && ($edx>0) && ($sdx<$edx) ) {
      $value = substr($value,0,$sdx).substr($value,$edx+2,strlen($value)-$edx);
      $sdx = strpos($value,'<%');
      $edx = strpos($value,'%>');
   }

     return $value;
}

class xml  {
    var $parser;

    function xml()
    {
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
    }

    function parse($data)
    {
        xml_parse($this->parser, $data);
    }

    function tag_open($parser, $tag, $attributes)
    {
        var_dump($parser, $tag, $attributes);
    }

    function cdata($parser, $cdata)
    {
        var_dump($parser, $cdata);
    }

    function tag_close($parser, $tag)
    {
        var_dump($parser, $tag);
    }

} // end of class xml

function uploadfiles( $uploaddir, $delexisting ) {

    $uploadedfiles = array();

    $dir = getcwd();
    $backdir='';

    foreach ($uploaddir as $key=>$value) {
        if ($value>'') {
         if (!file_exists($value)) {
                    mkdir($value);
         }
                $dir = $dir."/".$value;
//              chdir($dir);
                chdir ($value);
                $backdir=$backdir.'../';
         }
     }

     if ($delexisting) {
             if ($handle = opendir($dir)) {
                   /* This is the correct way to loop over the directory. */
                 while (false !== ($file = readdir($handle))) {
                     if ($file!='.' && $file!='..') {
                             unlink($dir.'/'.$file);
                     }
                 }
                closedir($handle);
             }
     }


     if (isset($_FILES['upload']['name'])) {
             foreach ($_FILES['upload']['name'] as $a1=>$a1b ) {

                  $fname=$_FILES['upload']['name'][$a1];
                  $ftmp_name=$_FILES['upload']['tmp_name'][$a1];
                  if ($fname!="") {
                     if (move_uploaded_file($ftmp_name, $dir ."/". $fname )) {
                        $uplfile = $fname;
                        $uploadedfiles[] = $fname;
                        chmod( $dir."/".$fname, 0754);
                     } else {
                        $uplfile = "";
                     }
                  }
             }
     }
     chdir($backdir);
     return  $uploadedfiles;

}

function tagoptions($tag,$s) {

        $op='';
        $idx = strpos($s,$tag."|");
          
        if ($idx>-1) {
           $t = substr($s,$idx,strlen($s));
           $edx = strpos($t,'%>');

           $tgwith = substr($t,0,$edx);

           $tgi = strpos($tgwith,'|');
           $tg = substr($tgwith,0,$tgi );
           $op = substr($tgwith,$tgi+1,strlen($tgwith));

        }

       return $op;
}

function dosql($query) {
 global $connection;
      $res = mysql_query($query, $connection) or die($query." ".mysql_error() );
 return   $res;

}

function objecttojson($json,$object) {

        foreach ($object as $key=>$value) {

         if (is_array($value) ) {
//                  $json = $json.arraytojson($value);
                } else {

                        if (is_object($value)) {
                      //    $json = $json.$objecttojson($value);
                        } else {
                           $json = $json.'"'.addslashes($key).'":"'.addslashes($value).'"'.',';

                        }
                }

        }

        return $json;
}

function arraytojson($json, $array) {
        echo "p";
        foreach ($array as $key=>$value) {

                if (is_array($value)) {
                        $json = $json.arraytojson($value);
                } else {
                      $json = $json. addslashes($key).":".addslashes($value).",";
                }

        }
        return $json;

}

?>
