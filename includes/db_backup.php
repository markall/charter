<?php


class backup {
  var $output = "screen";

  function backup_tables($tables)
  {
  //    $link = mysql_connect($host,$user,$pass);      $host,$user,$pass,$name,
  //    mysql_select_db($name,$link);
      $return = "";

      // Get all of the tables
      if($tables == '*') {
          $tables = array();
          $result = dosql('SHOW TABLES');
          while($row = mysql_fetch_row($result)) {
              $tables[] = $row[0];
          }
      } else {
          if (is_array($tables)) {
              $tables = explode(',', $tables);
          }
      }

      // Cycle through each provided table
      foreach($tables as $table) {
          $result = dosql('SELECT * FROM '.$table);
          $num_fields = mysql_num_fields($result);

          // First part of the output – remove the table
          $return .= "DROP TABLE IF EXISTS `" . $table . "` ;\n\n";

          // Second part of the output – create table
          $row2 = mysql_fetch_row(dosql("SHOW CREATE TABLE ".$table));
          $return .= "\n\n" . $row2[1] . ";\n\n\n";

          // Third part of the output – insert values into new table
          for ($i = 0; $i < $num_fields; $i++) {
              while($row = mysql_fetch_row($result)) {
                  $return.= "\n\n\nINSERT INTO ".$table." VALUES(";
                  for($j=0; $j<$num_fields; $j++) {
                      $row[$j] = addslashes($row[$j]);
                      $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                      if (isset($row[$j])) {
                          $return .= '"' . $row[$j] . '"';
                      } else {
                          $return .= '""';
                      }
                      if ($j<($num_fields-1)) {
                          $return.= ',';
                      }
                  }
                  $return.= ");\n\n";
              }
          }
          $return.="\n\n\n";
      }

      if ($this->output=="screen") {
        header('Content-type: application/text');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="export.txt"');
        echo $return;
         exit;
      } else {

              // Generate the filename for the sql file
              $filess = 'backup/dbbackup_' . date("d.m.Y_H:i:s") . '.sql';

              // Save the sql file
              $handle = fopen($filess,'w+');
              fwrite($handle,$return);
              fclose($handle);

              // Print the message
              print("The backup has been created successfully. You can get the file <a href='$filess'>here</a>.<br>\n");
      }

      // Close MySQL Connection
      mysql_close();
  }

}

?>
