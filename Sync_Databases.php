<?php
// Dette skriptet skal synkronisere de to databasene vi har. Nemlig den alle har tilgang til, bruker-databasen, og 
// den kun admins kan skrive til, admin-databasen. 

// Synkroniseringen antar admin-databasen har mest pålitelig data og overskriver bruker-databasen med data fra admin-databasen først.
// Deretter tar den alt som ikke er i admin-databasen men som er i bruker-databasen og kopierer inn dette.

// Vi gjennbruker koden vi allerede har skrevet i add_ppl_class, siden denne er skrevet på en måte som kan gjennbrukes

require_once("add_ppl_class.php");

// Define first database connection 
$hostname1 = 'mysql1025.servage.net';
$username1 = 'Dbase'; // Brukernavn er samme som databasenavnet for denne databasen. Dette er ikke nødvendig
$password1 = 'eint464';
$database1 = 'Dbase';

// Define second database connection.
// hostname username and pass is same, but does not need to be the same
$hostname2 = 'mysql1025.servage.net';
$username2 = 'Adbase';
$password2 = 'eint464';
$database2 = 'Adbase';

// Try to connect to first database
try {
    // Connecter til database 1
    $connection1 = new PDO("mysql:host=$hostname1;dbname=$database1", $username1, $password1);

    // Connecter til database 2
    $connection2 = new PDO("mysql:host=$hostname2;dbname=$database2", $username2, $password2);

    // echo "Connected";

    // $query1 ber om å hente hele tabellen table_name_age
    $query1 = "SELECT * FROM table_name_age";

    // $query2 ber om å hente hele tabellen table_name_age, men dette er snakk om 
    // tabellen i en annen database. De to tabellene heter det samme men vi skriver koden 
    // som om de ikke het det samme, fordi vi har lyst å lære å jobbe med flere databaser samtidig
    // Altså blir $query1 = $query2 her, men de kan godt være ulike også
    $query2 = "SELECT * FROM table_name_age";

    // Kan skrive ut hele tabellen fra database 2 her
    foreach ($connection2->query($query2) as $row)
    {
        // Update value in database

        // Get input
        $Input_Navn = $row['Navn'];
        $Input_Alder = $row['Alder'];
        $Input_Bil = $row['Bil'];

        $class_object = new Database_Access();

        // Pass input
        $class_object->Set_Input($Input_Navn, $Input_Alder, $Input_Bil);

        // Update database
        $class_object->Update_Database();
    }

    foreach ($connection1->query($query1) as $row)
    {
        // Get input
        $Input_Navn = $row['Name'];
        $Input_Alder = $row['Age'];
        $Input_Bil = "DontUpdate";

        $class_object = new Database_Access();

        // Pass input
        $class_object->Set_Input($Input_Navn, $Input_Alder, $Input_Bil);

        // Update database
        $class_object->Update_Database();
    }
    
    // Close connections
    $connection1 = NULL;
    $connection2 = NULL;
}

// If problem, print this
catch(PDOException $e){
    echo $e->getMessage();
}

?>

