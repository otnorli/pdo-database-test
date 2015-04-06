function Login_Function() {
    // Get input
    var InputUser = document.getElementById("USERNAME").value;
    var InputPassword = document.getElementById("PASSWORD").value;
    var token = document.getElementById("TOKEN").value; // Get token value

    // Call php page using ajax shorthand
    $.post("admin_login.php", {
        InputUser: InputUser,
        InputPassword: InputPassword,
        token: token
    },

    function (data) {

        if (data == 'Success') {
            Change_Page_To_Admin();
        }

        else {
            alert(data);
        }
    });
}

function Change_Page_To_Admin() {
    // Endre nesten hele siden
    var changethis = document.getElementById("form_som_skal_endres");
    changethis.innerHTML = 
    "Navn:<br> \
    <input type='text' name='Navn' id='NAVN'> \
    <br> \
    Alder:<br> \
    <input type='number' name='Alder' id='ALDER'> \
    <br> \
    Bil:<br> \
    <input type='text' name='Bil' id='BIL'> \
    <br> <br> ";
    document.getElementById("forste_paragraf").innerHTML = "Nå er du logget inn som admin. Du brukte passordet root. Dette er enkryptert og lagret med hex formatet. ";
    document.getElementById("tredje_paragraf").innerHTML = "Siden er noe forandret. Formen hvor \
    vi kunne legge til folk i databasen er borte og erstattet med en ny form. Data som sendes inn her \
    vil fortsatt registrere navn og alder i den gamle databasen, men denne formen er for admins. \
    Admins kan registrere en variabel til, Bil. Denne informasjonen sendes i tillegg til en ny database, \
    admin-databasen. Dette er en helt annen database ikke bare en ny tabell.  <br> <br> \
    Vi har også en ny knapp, Sync, som synkroniserer de to databasene. Dette er laget for å \
    jobbe litt med flere databaser samtidig. Databasene accesses stort sett ved bruk av PDO statements. \
    Alle knappene her kaller på scripts gjennom ajax, så vi vil aldri refreshe siden.";
    document.getElementById("Hemmelig_Knapp").style.visibility = "visible";
    document.getElementById("Syncronization_Knapp").style.visibility = "visible";
    document.getElementById("admin_query_div").style.visibility = "visible";
    document.getElementById("sjette_paragraf").innerHTML = "Her kan vi hente ut informasjon fra den orginale databasen.";
    document.getElementById("syvende_paragraf").innerHTML = "";
    document.getElementById("siste_paragraf").innerHTML = "Her kan vi hente ut informasjon fra den nye databasen (Admin-databasen).";
    
    var adminform_en = document.getElementById('admin_form_en');
    adminform_en.parentNode.removeChild(adminform_en);

    document.getElementById("avslutt_knappen_hemmelig").style.visibility = "visible";

}

function Admin_Query(){
    // Get input
    var InputUser = document.getElementById("ADMINNAVN").value;

    // Call php page using ajax get function
    $.post("query_admin_database.php", {
        InputUser: InputUser
    },

    function (data) {
        document.getElementById("ADMINBIL").value = data.Bil;
        document.getElementById("ADMINALDER").value = data.Alder;
    }, 
    'json');
}

function Avslutt_Siden() {
    document.getElementById("forste_paragraf").innerHTML = "";
    document.getElementById("tredje_paragraf").innerHTML = "";

    var changethis = document.getElementById("form_som_skal_endres");
    changethis.parentNode.removeChild(changethis);
    document.getElementById("Hemmelig_Knapp").style.visibility = "hidden";
    document.getElementById("Syncronization_Knapp").style.visibility = "hidden";
    document.getElementById("admin_query_div").style.visibility = "hidden";
    document.getElementById("sjette_paragraf").innerHTML = "Takk for besøket på olenorli.info.";
    document.getElementById("siste_paragraf").innerHTML = "En ting som ikke er lagt til så mye av her er security. \
    Passordet encrypteres, og vi bruker stort sett PDO for å accesse databasene, men når vi overførte \
    siden til nettet ble det et problem med blant annet token for å hindre CSRF angrep, men uansett en morsom side med litt bruk av flere databaser samtidig.";
    document.getElementById("request_fra_original_database").style.visibility = "hidden";

    
}
