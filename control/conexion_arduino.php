<!DOCTYPE html>
<html><body>
<?php

$servername = "localhost:3306";
$dbname = "datos";
$username = "root";
$password = "edgar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id,location, ritmo_cardiaco, oxigenacion, temperatura, reading_time FROM SensorData ORDER BY id DESC";

echo '<table cellspacing="6" cellpadding="6">
      <tr> 
        <td>ID</td> 
        <td>location</td> 
        <td>ritmo_cardiaco</td> 
        <td>oxigenacion</td> 
        <td>Temperatura</td> 
        <td>Timestamp</td> 
      </tr>';
 
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_location = $row["location"];
        $row_value1 = $row["ritmo_cardiaco"];
        $row_value2 = $row["oxigenacion"]; 
        $row_value3 = $row["temperatura"]; 
        $row_reading_time = $row["reading_time"];
      
        echo '<tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_location . '</td> 
                <td>' . $row_value1 . '</td> 
                <td>' . $row_value2 . '</td>
                <td>' . $row_value3 . '</td>
                <td>' . $row_reading_time . '</td> 
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>
</body>
</html>