<?php
require_once 'autenticazione.php';

 if (!$userid = checkAuth()) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
    exit;
}

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) 
   or die("Errore di connessione: " . mysqli_connect_error());
  $userid = mysqli_real_escape_string($conn, $userid); //protezione stringa

$buoni_list = [];
$q_buoni = "SELECT codice, valore FROM buoni WHERE id_utente = '$userid'";
$res_buoni = mysqli_query($conn, $q_buoni);

if ($res_buoni) {
    while ($row_buono = mysqli_fetch_assoc($res_buoni)) {
        $buoni_list[] = [
            "codice" => ($row_buono["codice"]),
            "valore" => ($row_buono["valore"])
        ];
    }
} else {
    error_log("Errore nel recupero buoni per l'utente " . $userid . ": " . mysqli_error($conn));
}
 $response["success"] = true;
 $response["buoni_list"] = $buoni_list; 
 echo json_encode($response);
 mysqli_close($conn);
?>