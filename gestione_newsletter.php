<?php
 require_once 'autenticazione.php';

 if (!$userid = checkAuth()) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
    exit;
}

header('Content-Type: application/json');

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) ;
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . mysqli_connect_error()]);
    exit();
}
 
$userid = mysqli_real_escape_string($conn, $userid); 


if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email non valida o mancante.']);
    mysqli_close($conn); 
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email']); 
$email = trim($email);     // Rimuove spazi bianchi all'inizio e alla fine
$email = strtolower($email); // Converte l'email in minuscolo per coerenza 

$sql_check = "SELECT id FROM newsletter WHERE email = '$email' and id_utente='$userid'";
$result_check = mysqli_query($conn, $sql_check);


if (mysqli_num_rows($result_check) > 0) {
    echo json_encode(['success' => true, 'message' => 'Sei già iscritto alla newsletter ! ']);
} else {
    
    $sql_insert = "INSERT INTO newsletter (id_utente, email) VALUES ('$userid','$email')";

    if (mysqli_query($conn, $sql_insert)) { 
        echo json_encode(['success' => true, 'message' => 'Grazie per esserti iscritto alla newsletter! Controlla posta..']);
    } else {
        // Errore durante l'inserimento
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'iscrizione: ']);
    }
}


mysqli_close($conn);
?>