<?php
require_once 'autenticazione.php';

if (!$userid = checkAuth()) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name'])
    or die("Errore di connessione: " . mysqli_connect_error());

$userid = mysqli_real_escape_string($conn, $userid);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_prodotto']) || empty($_POST['id_prodotto'])) {
        echo json_encode(["success" => false, "message" => "ID prodotto mancante."]);
        mysqli_close($conn);
        exit;
    }

    $id_prodotto = mysqli_real_escape_string($conn, $_POST['id_prodotto']);

    $query = "DELETE FROM prodotti_personalizzati WHERE id_utente = '$userid' AND id = '$id_prodotto'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Prodotto rimosso con successo"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore nella rimozione del prodotto"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Richiesta non valida"]);
}

mysqli_close($conn);
?>
