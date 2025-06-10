<?php
require_once 'autenticazione.php';
if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Carrello</title>
        <link rel="stylesheet" href="carrello.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
        <script src='carrello.js' defer></script>
    </head>
    <body>

    <div class="flex-container">
      <div class="flex-item-left">
       <span id="t1"> 2 campioni omaggio per ogni ordine* </span>
        <span id="t2">   Spedizione gratuita da 35,00€ </span>
      </div>
    </div>
        <div class="name">
        <p id="t"> Carrello </p>
        </div>

        <header class="navbar">
  
      <button class="icons"> 
      <a href = 'profilo.php'>   <img src="person_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="utente" class="icon"> </a>
        <a href='home.php'><img src="home_40dp_1F1F1F_FILL0_wght200_GRAD0_opsz48.png"alt="home" class="icon"></a>
      <a href='preferiti.php'>   <img src="favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Preferiti" class="icon" id="preferiti"> </a>
      </button>

    </header>
      <div class="logo">
        <img src="logo ufficiale.png" alt="Logo Douglas">
      </div>
    <div class="catalogo"> </div>
    <div class="acquisto">
       <strong><p class='scelta'>PRODOTTI DA TE SELEZIONATI: </p></strong>
    <div class="prodotti-da-acquistare"> </div>
   <div class="spedizione"><p>spedizione </p><p>+€5<p></div>
  
    <form name='buono_form' onsubmit='return false;'>
      <div class='buono'>
          <div class='codice'>
              <label for='codice_sconto_input'></label>
              <input type='text' id='codice_sconto_input' name='codice_sconto' placeholder='inserisci codice sconto'>
              <input type="submit" id="applica_buono" value="APPLICA BUONO">
       
          </div>
      </div>
    </form>
    <hr>
    <div class="costo-totale">
      <strong>TOTALE</strong>
      <p class="tot"></p>
    </div>
     <button id="al-pagamento">AL PAGAMENTO</button>
</div>
<div class="pagamento-hidden">
   <h2>Dettagli di Pagamento e Spedizione</h2>

    <div class="form-group">
        <label for="numeroCarta">Numero Carta di Credito:</label>
        <input type="text" id="numeroCarta" name="numeroCarta" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
        </div>

    <div class="form-group">
        <label for="codiceSicurezza">Codice di Sicurezza (CVV/CVC):</label>
        <input type="text" id="codiceSicurezza" name="codiceSicurezza" placeholder="XXX" maxlength="4" required>
        </div>

    <div class="form-group">
        <label for="paese">Paese:</label>
        <input type="text" id="paese" name="paese" placeholder="Italia" required>
    </div>

    <div class="form-group">
        <label for="via">Via e Numero Civico:</label>
        <input type="text" id="via" name="via" placeholder="Via Roma, 10" required>
    </div>

    <div class="form-group">
        <label for="citta">Città:</label>
        <input type="text" id="citta" name="citta" placeholder="Catania" required>
    </div>

    <div class="form-group">
        <label for="cap">Codice di Avviamento Postale (CAP):</label>
        <input type="text" id="cap" name="cap" placeholder="95100" maxlength="5" required>
        </div>
        <p id="messaggioPagamento"></p>
       <button class="verifica">verifica</button>
  <div>
</body>
</html>