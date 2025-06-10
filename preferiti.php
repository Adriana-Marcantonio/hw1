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
        <title>Preferiti</title>
        <link rel="stylesheet" href="preferiti.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
        <script src='preferiti.js' defer></script>
    </head>
    <body>

    <div class="flex-container">
      <div class="flex-item-left">
        <span id="t1"> 2 campioni omaggio per ogni ordine* </span>
        <span id="t2">   Spedizione gratuita da 35,00€ </span>
      </div>
    </div>
        <div class="name">
        <p id="t"> Preferiti </p>
        </div>

        <header class="navbar">
      <button class="icons"> 
      <a href = 'profilo.php'>  <img src="person_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="utente" class="icon"> </a>
        <a href='home.php'><img src="home_40dp_1F1F1F_FILL0_wght200_GRAD0_opsz48.png"alt="home" class="icon"></a>
      <a href='carrello.php'>  <img src="shopping_cart_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Carrello" class="icon"> </a>
      </button>

    </header>
      <div class="logo">
        <img src="logo ufficiale.png" alt="Logo Douglas">
      </div>

    <div class="catalogo">
        </div>

    </body>
</html>