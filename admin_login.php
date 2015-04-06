<?php

// Gi også denne php fila tilgang til session'en vår
session_start();

// Get input 
if (isset($_POST['InputUser'])){
$Input_Username = $_POST['InputUser'];
if (isset($_POST['InputPassword'])){

$Input_Password = $_POST['InputPassword'];
$Input_Token = $_POST['token']; // ikke i bruk akuratt nå

$Encrypted_Password = hash('sha256', $Input_Password);
//echo $Encrypted_Password;
// Vi sjekker her om den token som blir sendt sammen med login informasjonen er den samme 
// token som ble generert når siden originalt ble lastet inn. Dette øker sikkerheten til innloggingssystemet.
// Forsøk på å hindre såkalte "CSRF" angrep.
//if($_SESSION["token"] != $Input_Token){

$randomvar = 1;
if ($randomvar == 0){ // sjekk token her, men ikke i bruk akuratt nå
    die('Please dont CSRF attack us, sir.');
}

else {
    // If tokens match,
    // Open socket to database to check if input match user + pass
    
    $hostname1 = 'mysql1025.servage.net';
    $username1 = 'olenorli_db_log';
    $password1 = 'eint464';
    $database1 = 'olenorli_db_log';

    $connection = new PDO("mysql:host=$hostname1;dbname=$database1", $username1, $password1);
    $query = 'SELECT * FROM table_admins WHERE User = ? and Password = ?';
        
    // Make PDO statement object. Statements are more secure when accessing database
    $prepare = $connection->prepare($query);
        
    // Fill inn this statement
    $prepare->bindParam(1, $Input_Username);
    $prepare->bindParam(2, $Encrypted_Password);

    // Execute the statement
    $prepare->execute();

    // Count nr of rows in result, this should be either 0 or 1 unless one user is registered multiple times in database
    $rows = $prepare->rowCount();

    if ($rows > 0){ // we set > 0 instead of == 1. This allows users who by error have been registered multiple times
        echo "Success"; // When Success string is output javascript calls function to modify the html code 
    }

    else {
        echo "Wrong pass or username"; // If his is output then nothing happens
    }

    // Close connection
    $connection = NULL;
}
}
else {
    return;
}
}

else {
    return;
}

?>
