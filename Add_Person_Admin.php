
<?php
    
require_once("add_ppl_class.php");

// Get input
if (isset($_GET['Navn'])){
    $Input_Navn = $_GET['Navn'];
    $Input_Alder = $_GET['Alder'];
    $Input_Bil = $_GET['Bil'];

    $class_object = new Database_Access();

    // Pass input
    $class_object->Set_Input($Input_Navn, $Input_Alder, $Input_Bil);

    // Update database
    $class_object->Update_Database();
}

?>
