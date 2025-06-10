<?php
 require_once 'autenticazione.php';

 if (!$userid = checkAuth()) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
    exit;
}

header('Content-Type: application/json');

    function CreaCodiceBuono(){
    $str1=substr(uniqid(), 0, 12);
    $pos = rand(1, strlen($str1));
    $str2 = substr_replace($str1, '', $pos - 1, 7); 
    $caratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char = $caratteri[random_int(0, strlen($caratteri) - 1)]; 
    return substr_replace($str2, $char, $pos - 1, 0); 
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) 
   or die("Errore di connessione: " . mysqli_connect_error());
  $userid = mysqli_real_escape_string($conn, $userid); //protezione stringa
  $q2="SELECT* FROM punti  WHERE id_utente='$userid'";
  $res_2 = mysqli_query($conn, $q2);
if ($res_2) {
    $row = mysqli_fetch_assoc($res_2);
    $n_punti = $row['n_punti'];
} else {
    echo "Errore nella query: " . mysqli_error($conn);
}

$response = ["success" => false, "message" => "", "n_punti" => $n_punti];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["valore-buono"])) {
    $valoreBuono = $_POST["valore-buono"];


    if ($n_punti - $valoreBuono < 0) {
        $response["message"] = "Ops... non hai abbastanza punti per generare un buono!";
    } else {
        $codice = CreaCodiceBuono();
        // Determina il valore in base alla selezione
        if ($valoreBuono == 220) {
            $valore = 5;
        } elseif ($valoreBuono == 400) {
            $valore = 10;
        } else {
            $valore = 20;
        }
        $n_punti-=$valoreBuono;

      
        // Aggiornamento database
        $q1 = "INSERT INTO buoni (id_utente, codice, valore) VALUES ('$userid', '$codice', '$valore')";
        mysqli_query($conn, $q1);
        $q2 = "UPDATE punti SET n_punti='$n_punti' WHERE id_utente='$userid'";
        mysqli_query($conn, $q2);


        $response["success"] = true;
        $response["message"] = "Buono generato con successo! Codice $codice ";
        $response["n_punti"] = $n_punti; // Punti aggiornati
    }
}

echo json_encode($response);
mysqli_close($conn);
?>

