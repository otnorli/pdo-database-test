<?php

// Her skal vi query'e admin databasen og hente ut to verdier,
// alder og bil
// når man gir input navn

// Få input
if (isset($_POST['InputUser'])){
    $Input_Username = $_POST['InputUser'];

    // Alloker array for output
    $ret = array();

    // Definer server, brukernavn og database vi skal connecte til
    $hostname = 'mysql1025.servage.net';
    $username = 'Adbase';
    $password = 'eint464';
    $database = 'Adbase';

    // Connect til server
    $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

    // Gjør klar en query
    $query = "SELECT * FROM table_name_age WHERE Navn=?";
    $prepare = $connection->prepare($query);
    $prepare->bindParam(1, $Input_Username);

    // Execute
    $prepare->execute();

    // Hent ut informasjonen som et objekt
    $row = $prepare->fetchObject();

    // Definer output
    $ret['Alder'] = $row->Alder;
    $ret['Bil'] = $row->Bil;

    // Lukk socket
    $connection = NULL;

    // Returner arrar
    echo json_encode($ret);
}
?>

