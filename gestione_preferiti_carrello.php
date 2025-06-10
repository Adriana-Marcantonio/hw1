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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodo di richiesta non valido.']);
    exit;
}

if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti nella richiesta.']);
    exit;
}

$Idprodotto = $new_quantity = $codice = $sourceTable = $Npunti = null; 
$action = $_POST['action'];

if (in_array($action, ['add', 'remove', 'add2', 'remove2', 'modifica_quantita'])) {
    if (!isset($_POST['Idprodotto']) || !isset($_POST['sourceTable'])) {
        echo json_encode(['success' => false, 'message' => 'ID prodotto o sourceTable mancanti.']);
        mysqli_close($conn);
        exit;
    }
    $Idprodotto = mysqli_real_escape_string($conn, $_POST['Idprodotto']);
    $sourceTable = mysqli_real_escape_string($conn, $_POST['sourceTable']);

    if (!in_array($sourceTable, ['prodotti', 'prodotti_personalizzati'])) {
        echo json_encode(['success' => false, 'message' => 'SourceTable non valida.']);
        exit;
    }
}

if ($action === 'modifica_quantita') {
    if (!isset($_POST['new_quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Nuova quantità mancante.']);
        mysqli_close($conn);
        exit;
    }
    $new_quantity = mysqli_real_escape_string($conn, $_POST['new_quantity']);
}

if ($action === 'applica_sconto') {
    if (!isset($_POST['codice'])) {
        echo json_encode(['success' => false, 'message' => 'Codice sconto mancante.']);
        mysqli_close($conn);
        exit;
    }
    $codice = mysqli_real_escape_string($conn, $_POST['codice']);
}

if ($action === 'clear') {
    if (!isset($_POST['Npunti'])) {
        echo json_encode(['success' => false, 'message' => 'Punti totali mancanti per l\'acquisto.']);
        mysqli_close($conn);
        exit;
    }
    $Npunti = mysqli_real_escape_string($conn, $_POST['Npunti']);
    if (isset($_POST['codice_sconto_applicato']) && !empty($_POST['codice_sconto_applicato'])) {
        $codice = mysqli_real_escape_string($conn, $_POST['codice_sconto_applicato']);
    }
}

$query = "";
$response = ['success' => false, 'message' => 'Errore sconosciuto.'];

switch ($action) {
    case 'add':
        $query = "INSERT INTO preferiti (id_utente, id_prodotto, sourceTable) VALUES ('$userid', '$Idprodotto', '$sourceTable')";
        break;

    case 'remove':
        $query = "DELETE FROM preferiti WHERE id_utente = '$userid' AND id_prodotto = '$Idprodotto' AND sourceTable = '$sourceTable'";
        break;

    case 'add2':
        $query = "INSERT INTO carrello (id_utente, id_prodotto, sourceTable, pezzi) VALUES ('$userid', '$Idprodotto', '$sourceTable', 1)";
        break;

    case 'remove2':
        $query = "DELETE FROM carrello WHERE id_utente = '$userid' AND id_prodotto = '$Idprodotto' AND sourceTable = '$sourceTable'";
        break;

    case 'clear':
        $punti_aggiornati = false;
        $carrello_svuotato = false;
        $buono_eliminato = false;
        $error_messages = [];
         $update_punti_query = "UPDATE punti SET n_punti = n_punti + '$Npunti' WHERE id_utente = '$userid'";
        if (mysqli_query($conn, $update_punti_query)) {
            $punti_aggiornati = true;
        } else {
            error_log("Errore aggiornamento punti per utente " . $userid . ": " . mysqli_error($conn));
            $error_messages[] = 'Errore durante l\'aggiornamento dei punti.';
        }

        if ($punti_aggiornati) { 
            $clear_cart_query = "DELETE FROM carrello WHERE id_utente = '$userid'";
            if (mysqli_query($conn, $clear_cart_query)) {
                $carrello_svuotato = true;
            } else {
                error_log("Errore svuotamento carrello per utente " . $userid . ": " . mysqli_error($conn));
                $error_messages[] = 'Errore durante lo svuotamento del carrello.';
            }
        } else {
            $error_messages[] = 'Svuotamento carrello non eseguito a causa del fallimento nell\'aggiornamento punti.';
        }

        if (isset($codice) && !empty($codice) && $punti_aggiornati && $carrello_svuotato) {
            $delete_buono_query = "DELETE FROM buoni WHERE id_utente = '$userid' AND codice = '$codice'";
            if (mysqli_query($conn, $delete_buono_query)) {
                $buono_eliminato = true;
            } else {
                error_log("Errore eliminazione buono sconto: " . mysqli_error($conn));
                $error_messages[] = 'Errore durante l\'eliminazione del buono sconto.';
            }
        } else if (isset($codice) && !empty($codice)) {
            $error_messages[] = 'Il buono sconto non è stato eliminato';
        } else {
            $buono_eliminato = true; 
        }

        if ($punti_aggiornati && $carrello_svuotato && $buono_eliminato) {
            $response = ['success' => true, 'message' => 'Acquisto completato con successo! Punti aggiornati.'];
        } else {
            $response = [
                'success' => false,
                'message' => 'Errore durante l\'acquisto: ' . (empty($error_messages) ? 'Si è verificato un errore inatteso.' : implode(' ', $error_messages))
            ];
        }
        break;

    case 'modifica_quantita':
        $query = "UPDATE carrello SET pezzi = '$new_quantity' WHERE id_utente = '$userid' AND id_prodotto = '$Idprodotto' AND sourceTable = '$sourceTable'";
        break;

    case 'applica_sconto':
        $query = "SELECT valore FROM buoni WHERE id_utente = '$userid' AND codice = '$codice'";
        $result_buono = mysqli_query($conn, $query);

        if ($result_buono && mysqli_num_rows($result_buono) > 0) {
            $buono = mysqli_fetch_assoc($result_buono);
            $valore_sconto = $buono['valore'];
            $response = ['success' => true, 'message' => 'Codice sconto applicato!', 'valore_sconto' => $valore_sconto];
        } else {
            error_log("Errore applicazione codice sconto: " . mysqli_error($conn));
            $response = ['success' => false, 'message' => 'Errore nell\'applicazione del codice sconto.'];
        }
        break;

    default:
        $response = ['success' => false, 'message' => 'Azione non riconosciuta.'];
        break;
}
if (!empty($query) && $action !== 'applica_sconto') {
    if (mysqli_query($conn, $query)) {
        $response = ['success' => true, 'message' => 'Azione completata con successo.'];
    } else {
        $response = ['success' => false, 'message' => 'Errore nel database: ' . mysqli_error($conn)];
        error_log("Errore DB per azione $action: " . mysqli_error($conn));
    }
}


mysqli_close($conn);
echo json_encode($response);
?>

