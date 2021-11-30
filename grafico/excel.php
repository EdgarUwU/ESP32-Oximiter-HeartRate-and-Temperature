<?php

  $DB_TBLName = "SensorData"; 
  $xls_filename = 'export_'.date('Y-m-d').'.csv'; 
  $conn = new mysqli("localhost", "root", "edgar", "datos");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "Select * from $DB_TBLName";
  $conn->query("SET NAMES 'utf8'");
  $data = array();
  $result = $conn->query($sql);
  $fields_Name = [];
  if ($result) {
    $finfo = $result->fetch_fields();
    foreach ($finfo as $val) {
        array_push($fields_Name,$val->name);
    }

  }
   
  header("Content-Type: application/xls");
  header("Content-Disposition: attachment; filename=$xls_filename");
  header("Pragma: no-cache");
  header("Expires: 0");

  echo chr(0xEF).chr(0xBB).chr(0xBF);
  $sep = ","; 
  foreach ($fields_Name as $value) {
    echo $value .  $sep;
  }
  print("\n");
  while($row = $result->fetch_assoc())
  {
    $schema_insert = "";
    foreach ($fields_Name as $value) 
    {
      if(!isset($row[$value])) {
        $schema_insert .= "NULL".$sep;
      }
      elseif ($row[$value] != "") {
        $field_value = $row[$value];
        $field_value = str_replace($sep , "",$field_value );
        $schema_insert .= $field_value.$sep;
      }
      else {
        $schema_insert .= "".$sep;
      }
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= $sep;
    print(trim($schema_insert));
    print "\n";
  }
  $conn->close();
?>