<?php

// Klasse for å oppdatere de to databasene våre
// Input navn, alder og bil
// bruker php PDO for å accesse databasen

class Database_Access{
    public $Input_Navn;
    public $Input_Alder;
    public $Input_Bil;

    // Define first database connection 
    public $hostname1 = 'mysql1025.servage.net';
    public $username1 = 'Dbase';
    public $password1 = 'eint464';
    public $database1 = 'Dbase';

    // Define second database connection.
    // hostname username and pass is same, but does not need to be the same
    public $hostname2 = 'mysql1025.servage.net';
    public $username2 = 'Adbase';
    public $password2 = 'eint464';
    public $database2 = 'Adbase';

    // Simplified error output, if error is set to 1 during the code we have an error... if not its good
    public $Error = 0;

    public function Set_Input($Input1, $Input2, $Input3){
        $this->Input_Navn = $Input1;
        $this->Input_Alder = $Input2;
        $this->Input_Bil = $Input3;
    }

    public function Update_Database(){
        if ($this->Error == 0){
            try {
                // Connecter til database 1
                $connection1 = new PDO("mysql:host=$this->hostname1;dbname=$this->database1", $this->username1, $this->password1);

                // Connecter til database 2
                $connection2 = new PDO("mysql:host=$this->hostname2;dbname=$this->database2", $this->username2, $this->password2);

                // echo "Connected";

                // $query1 ber om å hente hele tabellen table_name_age
                $query1 = "SELECT * FROM table_name_age";

                // $query2 ber om å hente hele tabellen table_name_age, men dette er snakk om 
                // tabellen i en annen database. De to tabellene heter det samme men vi skriver koden 
                // som om de ikke het det samme, fordi vi har lyst å lære å jobbe med flere databaser samtidig
                // Altså blir $query1 = $query2 her, men de kan godt være ulike også
                $query2 = "SELECT * FROM table_name_age";

                // Kan skrive ut hele tabellen fra database 2 her
                $Value_Found2 = 0;
                foreach ($connection2->query($query2) as $row)
                {
                    // If value already in database, update it
                    if ($row['Navn'] == $this->Input_Navn){
                        // Update value in database
                        $Value_Found2 = 1;

                        if (($row['Alder'] != $this->Input_Alder) || ($row['Bil'] != $this->Input_Bil)){

                            // Noe skal oppdateres

                            // Sjekk om bil skal oppdateres
                            if ($this->Input_Bil == "DontUpdate"){
                                if ($this->Input_Alder != $row['Alder']){
                                    // Forbered en query
                                    $query4 = "UPDATE table_name_age SET Alder=? WHERE Navn='$this->Input_Navn'";

                                    // Prepare en PDO statement og fyll inn argumentene
                                    $prepare4 = $connection2->prepare($query4);
                                    $prepare4->bindParam(1, $this->Input_Alder);

                                    // Execute, aka skriv til databasen
                                    $prepare4->execute();
                                }
                            }

                            // Hvis ikke, oppdater bil også
                            else {
                                // Forbered en query
                                $query4 = "UPDATE table_name_age SET Alder=?, Bil=? WHERE Navn='$this->Input_Navn'";// Update

                                // Prepare en PDO statement og fyll inn argumentene
                                $prepare4 = $connection2->prepare($query4);
                                $prepare4->bindParam(1, $this->Input_Alder);
                                $prepare4->bindParam(2, $this->Input_Bil);

                                // Execute, aka skriv til databasen
                                $prepare4->execute();
                            }
                        }
                    }
                }

                if ($Value_Found2 == 0){
                    // Hvis denne if-testen funker fant vi ikke verdien i databasen vår fra før, og vi må legge den inn

                    if ($this->Input_Bil == "DontUpdate"){
                        // HVis denne if-testen funker henter vi en verdi fra den andre databasen, eller bil var ikke definert, 
                        // uansett skal bil ikke oppdateres
                        $query3 = "INSERT INTO table_name_age(Navn, Alder) VALUES (?, ?)";

                        // Få en PDO statement
                        $prepare2 = $connection2->prepare($query3);

                        // Bind input vaules
                        $prepare2->bindParam(1, $this->Input_Navn);
                        $prepare2->bindParam(2, $this->Input_Alder);

                        // Execute, aka skriv til databasen
                        $prepare2->execute();
                    }

                    else {
                        // Insert value in database if value NOT already in database
                        $query3 = "INSERT INTO table_name_age(Navn, Alder, Bil) VALUES (?, ?, ?)";

                        // Få en PDO statement
                        $prepare2 = $connection2->prepare($query3);

                        // Bind input vaules
                        $prepare2->bindParam(1, $this->Input_Navn);
                        $prepare2->bindParam(2, $this->Input_Alder);
                        $prepare2->bindParam(3, $this->Input_Bil);

                        // Execute, aka skriv til databasen
                        $prepare2->execute();
                    }
                }

                // Kan skrive ut hele tabellen fra database 1 her
                $Existing_Val = 0;
                foreach ($connection1->query($query1) as $row)
                {
                    if ($row['Name'] == $this->Input_Navn){
                        $Existing_Val = 1;

                        if ($row['Age'] != $this->Input_Alder){
                            // Forbered en query
                            $query6 = "UPDATE table_name_age SET Age=? WHERE Name='$this->Input_Navn'";// Update

                            // Prepare en PDO statement og fyll inn argumentene
                            $prepare6 = $connection1->prepare($query6);
                            $prepare6->bindParam(1, $this->Input_Alder);

                            // Execute, aka skriv til databasen
                            $prepare6->execute();
                        }
                    }
                    //print $row['Name'] .' - '. $row['Age'] . '<br />';
                }

                if ($Existing_Val == 0){
                    // Skriv inn ny verdien
                    $query5 = "INSERT INTO table_name_age(Name, Age) VALUES (?, ?)";

                    // Få en PDO statement
                    $prepare5 = $connection1->prepare($query5);

                    // Bind input vaules
                    $prepare5->bindParam(1, $this->Input_Navn);
                    $prepare5->bindParam(2, $this->Input_Alder);

                    // Execute, aka skriv til databasen
                    $prepare5->execute();
                }
    
                // Close connections
                $connection1 = NULL;
                $connection2 = NULL;
            }

            // If problem, print this
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }

        else {
            echo "Error :(";
        }
    }
}
?>
