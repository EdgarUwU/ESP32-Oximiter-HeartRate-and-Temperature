<?php
$servername = "localhost:3306";
$dbname = "datos";
$username = "root";
$password = "edgar";

$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $location = $ritmo_cardiaco = $oxigenacion = $temperatura= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $location = test_input($_POST["location"]);
        $ritmo_cardiaco = test_input($_POST["ritmo_cardiaco"]);
        $oxigenacion = test_input($_POST["oxigenacion"]);
        $temperatura = test_input($_POST["temperatura"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO SensorData (location, ritmo_cardiaco, oxigenacion, temperatura)
        VALUES ('" . $location . "', '" . $ritmo_cardiaco . "', '" . $oxigenacion . "', '" . $temperatura . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?> 