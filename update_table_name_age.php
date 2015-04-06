<?php

// PHP 

// Initialize database
global $conn;
$conn = new mysqli('mysql1025.servage.net', 'Dbase', 'eint464', 'Dbase');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from user
$Global_Name = mysqli_real_escape_string($conn, $_POST['Navn']);
$Global_Age = mysqli_real_escape_string($conn, $_POST['Alder']);

// Check if our database already contain this person
$Value_Found = 0;
// WHERE name = 'Name' ellerno kan potensielt erstatte denne while loopen

$sqll = "SELECT Name, Age FROM table_name_age";
$result = $conn->query($sqll);

if ($result->num_rows > 0) {
    // If database table contains more than 0 input and we are able to read it this checks if the input name is already registered
    while($row = $result->fetch_assoc()) {
        if ($row["Name"] == $Global_Name){
            $Value_Found = 1;

            if ($row["Age"] != $Global_Age){

                // Check if age is already correctly inputed, if so do not update further.
                // If not we update age in database-table
                $sql = "UPDATE table_name_age SET Age=$Global_Age WHERE Name='$Global_Name'";
                $resultatet = $conn->query($sql);

                if ($resultatet === TRUE){
                }

                else {
                    echo "Error";
                }
            }
        }
    }
} 

else {
    // No entries in database, or error fetching values
}

if ($Value_Found == 0){
    // If value is not present in database, add it
    $sql = "INSERT INTO table_name_age(Name, Age)
    VALUES ('$Global_Name', $Global_Age)";

    // Check if we succeeded inserting value into database
    if ($conn->query($sql) === TRUE) {
    } 

    // If not output error
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close socket to database
mysqli_close($conn);

// Redirect back to normal page
header('Location: index.php');
?>
