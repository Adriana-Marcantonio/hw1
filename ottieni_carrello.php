<?php
header('Content-Type: application/json; charset=UTF-8');

require_once 'autenticazione.php';


if (!$userid = checkAuth()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name'])
    or die("Errore di connessione: " . mysqli_connect_error());

$userid = mysqli_real_escape_string($conn, $userid);

$preferitiUtente = [];
$q_preferiti = "SELECT id_prodotto, sourceTable FROM preferiti WHERE id_utente = '$userid'";
$r_preferiti = mysqli_query($conn, $q_preferiti);
if ($r_preferiti) {
    while ($row = mysqli_fetch_assoc($r_preferiti)) {
        $preferitiUtente[$row['id_prodotto'] . '_' . $row['sourceTable']] = true;
    }
} else {
    error_log("Errore nella query preferiti per carrello: " . mysqli_error($conn));
}

$prodotti_carrello = [];

$query_prodotti_base = "
    SELECT p.id, p.nome, p.descrizione, p.prezzo, p.quantita_prodotto, p.pezzi, p.categoria, p.tipo, p.immagine, p.marca,
           c.pezzi AS quantita, 
           'prodotti' AS sourceTable 
    FROM prodotto p
    JOIN carrello c ON p.id = c.id_prodotto
    WHERE c.id_utente = '$userid' AND c.sourceTable = 'prodotti'
";
$res_prodotti_base = mysqli_query($conn, $query_prodotti_base);

if ($res_prodotti_base) {
    while ($row = mysqli_fetch_assoc($res_prodotti_base)) {
        $product_id_source = $row['id'] . '_' . $row['sourceTable'];
        $row['isFavorited'] = isset($preferitiUtente[$product_id_source]);
        $row['isInCarrello'] = true; 
        $prodotti_carrello[] = $row;
    }
} else {
    error_log("Errore nella query del carrello (prodotti base): " . mysqli_error($conn));
}

$query_prodotti_personalizzati = "
    SELECT pp.id, pp.nome, pp.descrizione, pp.prezzo, pp.quantita_prodotto, pp.pezzi, pp.categoria, pp.tipo, pp.immagine, pp.marca,
           c.pezzi AS quantita, -- Quantità dal carrello
           'prodotti_personalizzati' AS sourceTable -- Assegna esplicitamente 'prodotti_personalizzati'
    FROM prodotti_personalizzati pp
    JOIN carrello c ON pp.id = c.id_prodotto
    WHERE c.id_utente = '$userid' AND c.sourceTable = 'prodotti_personalizzati'
";
$res_prodotti_personalizzati = mysqli_query($conn, $query_prodotti_personalizzati);

if ($res_prodotti_personalizzati) {
    while ($row = mysqli_fetch_assoc($res_prodotti_personalizzati)) {
        $product_id_source = $row['id'] . '_' . $row['sourceTable'];
        $row['isFavorited'] = isset($preferitiUtente[$product_id_source]);
        $row['isInCarrello'] = true; 
        $prodotti_carrello[] = $row;
    }
} else {
    error_log("Errore nella query del carrello (prodotti personalizzati): " . mysqli_error($conn));
}

mysqli_close($conn);

echo json_encode(['prodotti_carrello' => $prodotti_carrello]);
?>