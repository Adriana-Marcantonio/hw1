<?php
require_once 'autenticazione.php'; 

header('Content-Type: application/json');

if (!$userid = checkAuth()) {
    echo json_encode(['error' => 'User not authenticated', 'products' => []]);
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) 
or die("Errore di connessione: " . mysqli_connect_error());
$userid = mysqli_real_escape_string($conn, $userid); 

$preferitiUtente = [];
$carrelloUtente = [];

$qq1 = "SELECT id_prodotto FROM preferiti WHERE id_utente = $userid";
$r1 = mysqli_query($conn, $qq1);
if ($r1) {
    while ($row = mysqli_fetch_assoc($r1)) {
        $preferitiUtente[] = intval($row['id_prodotto']);
    }
} 

$qq2 = "SELECT id_prodotto FROM carrello WHERE id_utente = $userid"; 
$r2 = mysqli_query($conn, $qq2);
if ($r2) {
    while ($row = mysqli_fetch_assoc($r2)) {
        $carrelloUtente[] = intval($row['id_prodotto']);
    }
} 
$prodotti_scelti = []; 
$query_prodotti_scelti = "SELECT id, nome, descrizione, prezzo, quantita_prodotto, pezzi, categoria,tipo, immagine, marca 
                         FROM prodotti_personalizzati 
                         WHERE id_utente = '$userid'"; 
$res_prodotti_scelti = mysqli_query($conn, $query_prodotti_scelti);
if ($res_prodotti_scelti) {
    while ($row = mysqli_fetch_assoc($res_prodotti_scelti)) {
        $row['isFavorited'] = in_array(intval($row['id']), $preferitiUtente); 
        $row['isInCarrello'] = in_array(intval($row['id']), $carrelloUtente); 
         $row['sourceTable'] = 'prodotti_personalizzati'; 
        $prodotti_scelti[] = $row;
         
    }
}

$prodotti_A = [];
$prodotti_B = [];
$query_products = "SELECT * FROM prodotto";
$res_products = mysqli_query($conn, $query_products);

if ($res_products) {
    while ($row = mysqli_fetch_assoc($res_products)) {
        $row['isFavorited'] = in_array(intval($row['id']), $preferitiUtente);
        $row['isInCarrello'] = in_array(intval($row['id']), $carrelloUtente);
        $row['sourceTable'] = 'prodotti';
        // Rimuovi la riga successiva:
        // $prodotti[] = $row; // Questa riga può essere rimossa
        if ($row['tipo'] === 'A') {
            $prodotti_A[] = $row;
        } elseif ($row['tipo'] === 'B') {
            $prodotti_B[] = $row;
        }
    }
} else {
    echo json_encode(['error' => 'Query error: ' . mysqli_error($conn), 'products_A' => [], 'products_B' => [], 'prodotti_scelti' => []]);
    mysqli_close($conn);
    exit;
}

mysqli_close($conn);
echo json_encode([
    'success' => true, 
    'products_A' => $prodotti_A,
    'products_B' => $prodotti_B,
    'prodotti_scelti' => $prodotti_scelti
]);
?>