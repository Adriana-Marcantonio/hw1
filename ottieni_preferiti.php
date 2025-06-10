<?php
header('Content-Type: application/json; charset=UTF-8');

require_once 'autenticazione.php';

if (!$userid = checkAuth()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) 
   or die("Errore di connessione: " . mysqli_connect_error());
$userid= mysqli_real_escape_string($conn, $userid);
$carrelloUtente = [];
$q2 = "SELECT id_prodotto, sourceTable FROM carrello WHERE id_utente = '$userid'";
$r2 = mysqli_query($conn, $q2);
if ($r2) {
    while ($row = mysqli_fetch_assoc($r2)) {
 $carrelloUtente[$row['id_prodotto'] . '_' . $row['sourceTable']] = true; //chiave composta
;
    }
} else {
    error_log("Errore nella query del carrello: " . mysqli_error($conn));
}

$preferiti = [];

$query_prodotti_base = "
    SELECT p.id, p.nome, p.descrizione, p.prezzo, p.quantita_prodotto, p.pezzi, p.categoria, p.tipo, p.immagine, p.marca,
           'prodotti' AS sourceTable 
    FROM prodotto p
    JOIN preferiti pp ON p.id = pp.id_prodotto
    WHERE pp.id_utente = '$userid' AND pp.sourceTable = 'prodotti'
";
$res_prodotti_base = mysqli_query($conn, $query_prodotti_base);

$res_prodotti_base = mysqli_query($conn, $query_prodotti_base);

if ($res_prodotti_base) {
    while ($row = mysqli_fetch_assoc($res_prodotti_base)) {
        // Check isInCarrello usando la chiave composta
        $product_id_source = $row['id'] . '_' . $row['sourceTable'];
        $row['isInCarrello'] = isset($carrelloUtente[$product_id_source]);
        $row['isFavorited'] = true; // È un preferito, quindi è sempre true
        $preferiti[] = $row;
    }
} else {
    error_log("Errore nella query dei preferiti (prodotti base): " . mysqli_error($conn));
}
$query_prodotti_personalizzati = "
    SELECT pp_table.id, pp_table.nome, pp_table.descrizione, pp_table.prezzo, pp_table.quantita_prodotto, pp_table.pezzi, pp_table.categoria, pp_table.tipo, pp_table.immagine, pp_table.marca,
           'prodotti_personalizzati' AS sourceTable 
    FROM prodotti_personalizzati pp_table
    JOIN preferiti fav ON pp_table.id = fav.id_prodotto
    WHERE fav.id_utente = '$userid' AND fav.sourceTable = 'prodotti_personalizzati'
";
$res_prodotti_personalizzati = mysqli_query($conn, $query_prodotti_personalizzati);

if ($res_prodotti_personalizzati) {
    while ($row = mysqli_fetch_assoc($res_prodotti_personalizzati)) {
 
        $product_id_source = $row['id'] . '_' . $row['sourceTable'];
        $row['isInCarrello'] = isset($carrelloUtente[$product_id_source]);
        $row['isFavorited'] = true; 
        $preferiti[] = $row;
    }
} else {
    error_log("Errore nella query dei preferiti (prodotti personalizzati): " . mysqli_error($conn));
}

mysqli_close($conn);

echo json_encode(['preferiti' => $preferiti]);
?>