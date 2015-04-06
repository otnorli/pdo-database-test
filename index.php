<?php 
// Start a session
session_start();

// Definer token som sesjons variabel for å gjøre siden sikker mot angrep, spesifikk for å countre CSRF
$token = md5(uniqid(rand(), TRUE)); // Denne tokenen er en random verdi, så lenge den ikke kan gjettes av andre er den bra
$_SESSION["token"] = $token; // Token not in use currently, problem when transfering to website. See admin_login.php to activate

// Siden dette er en testside kan vi printe ut token'en. Bare for å se hva slags verdier vi har, og se om dette er vanskelig å gjette.
//echo "As a user of this site you have been granted a random token.";
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <style>
        #HEAD_OF_PAGE {
            background-color:lightgrey;
            color:white;
            width:97%;
            text-align:center;
            padding:5px;
            width:auto;
        }
        
        #SIDE_OF_PAGE {
            line-height:30px;
            background-color:lightgrey;
            height:500px;
            width:20%;
            float:left;
            padding:5px;
        }
            
        #BODY_OF_PAGE {
            background-color: lightgrey;
            width:77%;
            float:left;
            padding:10px;	 	 
        }
        </style>

        <meta charset="utf-8" />

        <!-- Set title to what we all know -->
        <title>
            Ole is Boss
        </title>
        
        <!-- Use external CSS -->
        <link rel="stylesheet" href="StyleSheet.css">

    </head>
    <body>

    <div id="HEAD_OF_PAGE">
    <h1> Oles webprosjekt </h1>
    </div>
        
    <div id="SIDE_OF_PAGE">
        <!-- Fyller ut form'en med innloggingsinformasjon for å logge inn som admin -->
        <div id="admin_form_en">
            <h3>Admin Login</h3>
            <input type="text" name="Username" id="USERNAME" placeholder="Username">
            <input type="password" name="Password" id="PASSWORD" placeholder="Password">
            <input type="submit" id="knapp_numero_uno" data-inline="false" onclick="JavaScript:Login_Function()" value="Login">
            <input type="text" name="Check" id="CHECK" style="background-color:transparent; border: 0px solid;">
            <!-- Legger til en token for security -->
            <input type="hidden" name="token" id="TOKEN" value="<?php echo $token; ?>"/>
            <br>
            User: admin <br>
            Pass: root
        </div>
       
    </div>

    <div id="BODY_OF_PAGE">

        <p id="forste_paragraf">
            Velkommen til Oles webprosjekt. Dette prosjektet er laget for å freshe opp litt html, php osv, men mest databaser. 
            Denne siden består primært av to databaser og et innloggingsystem.
            
            <br><br>

            Slik siden du er på nå ser ut har du tilgang som bruker til å skrive inn data i en database. 
            Denne har en tabell med to kolonner Navn og Alder. Her kan man 
            registrere personer med Navn og Alder. Du har også tilgang til å hente ut alderen til folk som er registrert ved å oppgi navn. 
            
            <br><br>
            
            Til venstre på siden er det et innloggingsystem for å logge inn som "admin". Dette vil forandre siden, nye kommentarer vil komme 
            og nye funksjonaliteter vil bli tilgjengelig. Disse er nå hidden, og vil bli synlig ved innlogging. Dette er laget med 
            javascript. Brukernavn er admin og passord er root. Siden vil ikke refreshes ved innlogging, men innholdet endres ved javascript.
        </p>
        
        <p id="tredje_paragraf"> 
            Oppdatere databasen vil refreshe siden, pga vi kaller et eksternt php script direkte også bruker header for å komme tilbake. 
            Når vi henter ut data fra databasen kalles php scriptet ved ajax, så vi refresher ikke siden men bruker javascript til å endre 
            sidens innhold bare. 
        </p>

        <!-- Kjør update_table_name_age.php fila og skriv til databasen vår -->
        <form action="update_table_name_age.php" method="post" id="form_som_skal_endres">
            Navn:<br>
            <input type="text" name="Navn" id="NAVN">
            <br>
            Alder:<br>
            <input type="number" name="Alder" id="ALDER">
            <br><br>
            <input type="submit" value="Register person">
        </form>

        <button id="Hemmelig_Knapp" style="visibility:hidden" onclick='JavaScript:Add_Person_To_Database()'> Add Person </button>
        
        <button id="Syncronization_Knapp" style="visibility:hidden" onclick='JavaScript:Sync_Databases()'> Sync Databases </button>

        <hr>

        <p id="sjette_paragraf">
            Her kan man querye databasen og hente ut alder ved oppgitt navn på personer som er registrert. Hvis personen ikke eksisterer 
            i brukerdatabasen skal det komme opp "Ikke funnet". 
        </p>

        <p id="syvende_paragraf">
            
        </p>

        <!-- Here we query the database. Input a name in the first boks and we call javascript to get 
        the age of the person. Script defined  -->
        <div id="request_fra_original_database">
            Person: <br>
            <input type="text" id="NavnOutput"><br>
            Alder: <br>
            <input type="text" id="AlderOutput"><br><br>
            <button onclick="Get_Info()">Find age</button>
        </div>
        <hr>

        <p id="siste_paragraf">
            Vi kan nevne at i denne databasen er den unike verdien Navn. Det går altså ikke ann å registrere samme navnet 
            to ganger. Dersom man alikevel forsøker dette vil verdien alder oppdateres istede.
        </p>

        <div style="visibility: hidden" id="admin_query_div">
            Navn:<br> 
            <input type='text' name='Navn' id='ADMINNAVN'> 
            <br> 
            Alder:<br> 
            <input type='number' name='Alder' id='ADMINALDER'> 
            <br> 
            Bil:<br> 
            <input type='text' name='Bil' id='ADMINBIL'> 
            <br>
            <input type="submit" id="knapp_numero_uno" data-inline="false" onclick="JavaScript:Admin_Query()" value="Get Info!">
            <br> <br> 
        </div>

        <button style="visibility: hidden" id="avslutt_knappen_hemmelig" onclick="Avslutt_Siden()">Avslutt</button>

    </div> <!-- end of div body-of-page -->


    <script>
        function Get_Info() {
            var InputValue = document.getElementById("NavnOutput").value;
            var Call_PHP_Function = new XMLHttpRequest();

            Call_PHP_Function.onreadystatechange = function () {
                document.getElementById("AlderOutput").value = Call_PHP_Function.responseText;
            }

            Call_PHP_Function.open("GET", "Query_Table_Age_Name.php?q=" + InputValue, true);
            Call_PHP_Function.send();
        }
    </script>

    <script type="text/javascript" src="Script.js"></script>

    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>

    
    <script>
        function Add_Person_To_Database() {
            var InputNavn = document.getElementById("NAVN").value;
            var InputAlder = document.getElementById("ALDER").value;
            var InputBil = document.getElementById("BIL").value;

            var Call_PHP_Function = new XMLHttpRequest();

            Call_PHP_Function.onreadystatechange = function () {

            }

            Call_PHP_Function.open("GET", "Add_Person_Admin.php?Navn=" + InputNavn + "&Alder=" + InputAlder + "&Bil=" + InputBil, true);
            Call_PHP_Function.send();
        }
    </script>

    <script>
        function Sync_Databases() {
            var Call_PHP_Function = new XMLHttpRequest();
            Call_PHP_Function.onreadystatechange = function () {
            }
            Call_PHP_Function.open("GET", "Sync_Databases.php?", true);
            Call_PHP_Function.send();
        }
    </script>

    </body>
</html>

