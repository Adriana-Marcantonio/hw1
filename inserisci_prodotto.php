<?php
require_once 'autenticazione.php';

if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name'])
    or die("Errore di connessione: " . mysqli_connect_error());

$userid = mysqli_real_escape_string($conn, $userid);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);


    if (!$data) {
        echo json_encode(["error" => "Dati JSON non validi"]);
        mysqli_close($conn);
        exit;
    }

    $nome = isset($data['nome']) ? mysqli_real_escape_string($conn, $data['nome']) : "";
    $descrizione = isset($data['descrizione']) ? mysqli_real_escape_string($conn, $data['descrizione']) : "";
    $prezzo = isset($data['prezzo']) ? $data['prezzo'] : 0.00; 
    $quantita_prodotto = isset($data['quantita_prodotto']) ? mysqli_real_escape_string($conn, $data['quantita_prodotto']) : "Non specificato";
    $pezzi = isset($data['pezzi']) ? intval($data['pezzi']) : 0; 
    $categoria = isset($data['categoria']) ? mysqli_real_escape_string($conn, $data['categoria']) : "non specificata";
    $immagine = isset($data['immagine']) ? mysqli_real_escape_string($conn, $data['immagine']) : "";
    $marca = isset($data['marca']) ? mysqli_real_escape_string($conn, $data['marca']) : "";
    $tipo = 'S';

 
    $query = "INSERT INTO prodotti_personalizzati (id_utente, nome, descrizione, prezzo, quantita_prodotto, pezzi, tipo, categoria, immagine, marca) 
              VALUES ('$userid', '$nome', '$descrizione', '$prezzo', '$quantita_prodotto', '$pezzi', '$tipo', '$categoria', '$immagine', '$marca')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Prodotto inserito con successo"]);
    } else {
        echo json_encode(["error" => "Errore nell'inserimento"]);
    }
} else {
    echo json_encode(["error" => "Richiesta non valida"]);
}

mysqli_close($conn);
?>
