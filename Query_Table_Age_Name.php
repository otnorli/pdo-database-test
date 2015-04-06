<?php

// PHP , not PDO on this one

// Initialize database
global $conn;
//$conn = new mysqli('mysql1094.servage.net', 'olenorli_db', 'eint464', 'Dbase');
$conn = new mysqli('mysql1025.servage.net', 'Dbase', 'eint464', 'Dbase');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from user
$Global_Name = $_REQUEST["q"];
$sqll = "SELECT Name, Age FROM table_name_age";
$result = $conn->query($sqll);

$Value_Found = 0;

if ($result->num_rows > 0) {
    // If database table contains more than 0 input and we are able to read it this checks if the input name is already registered
    while($row = $result->fetch_assoc()) {
        if ($row["Name"] == $Global_Name){
            //echo "Age of person is: ";
            echo $row["Age"];
            $Value_Found = 1;
        }
    }
} 

else {
    // No entries in database, or error fetching values
}

if ($Value_Found == 0){
    echo "Ikke funnet";
}

// Close socket connection to database
mysqli_close($conn);
?>

