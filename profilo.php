
    <?php
    require_once 'autenticazione.php';
    if (!$userid = checkAuth()) {
        header("Location: login.php");
        exit;
    }

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) 
   or die("Errore di connessione: " . mysqli_connect_error());
  $userid = mysqli_real_escape_string($conn, $userid); //protezione stringa
  $q = "SELECT * FROM users WHERE id = $userid";
  $res_1 = mysqli_query($conn, $q);
  $userinfo = mysqli_fetch_assoc($res_1);   
  
  $q2="SELECT* FROM punti  WHERE id_utente='$userid'";
  $res_2 = mysqli_query($conn, $q2);

if ($res_2) {
    $row = mysqli_fetch_assoc($res_2);
    $n_punti = $row['n_punti'];
} else {
    echo "Errore nella query: " . mysqli_error($conn);
}

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Profilo</title>
        <link rel="stylesheet" href="profilo.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
        <script src='profilo.js' defer></script>
    </head>
    <body>

    <div class="flex-container">
      <div class="flex-item-left">
       <span id="t1"> 2 campioni omaggio per ogni ordine*  </span>
        <span id="t2">  Spedizione gratuita da 35,00€ </span>
      </div>

    </div>
        <header>
        <p id="t"> My Douglas </p>
        </header>
        <div>
    <section>
  <div class="container">
    <button class="icons">
        <a href='home.php'><img src="home_40dp_1F1F1F_FILL0_wght200_GRAD0_opsz48.png"alt="home" class="icon"></a>
        <a href='preferiti.php'><img src="favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Preferiti" class="icon" id="preferiti"></a>
        <a href='carrello.php'><img src="shopping_cart_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Carrello" class="icon"></a>
    </button>
        <img src="https://www.douglas.it/public/fallback.534c5217d30ab76e.webp" class="immagine_profilo">

</div>


<div class="info">
  <div class="dati">
  
  <?php
  if ($userinfo['genere'] == 'm') {
      echo"<h1>Benvenuto, " .($userinfo['name']) . "!</h1>";
  } else if ($userinfo['genere'] == 'f') {
      echo"<h1>Benvenuta, " .($userinfo['name']) . "!</h1>";
  } else {
      echo"<h1>Ciao, " .($userinfo['name']) . "!</h1>";
  }
     echo "<p> I tuoi dati sono:</p>";
     echo " <p>Nome :".htmlspecialchars($userinfo['name'])."</p>";
     echo " <p>Cognome :".htmlspecialchars($userinfo['surname']) ."</p>";
     echo " <p>Username :".htmlspecialchars($userinfo['username']) ."</p>";
     echo " <p>Genere :".htmlspecialchars($userinfo['genere']) ."</p>";
     echo " <p>Email :".htmlspecialchars($userinfo['email']) ."</p>";
?>
</div>
 <h1> I tuoi Beauty Points </h1> 
 
<button id='vedi-lista'>VEDI LISTA BUONI</button>
<div class="lista-hidden">
    <div class="esci" id="exit-lista">
        <img src="exit.png">
    </div>
    <strong>Ecco tutti i tuoi buoni :</strong>
   <div class="buoni"></div>
</div>


  <div class="punti-box">
  <div class="punti">
    <?php
   echo "<p id='P'>".$n_punti."</p>"; ?>
   <p> beauty points </p>
  </div>
  <div>
   <p> I punti sono convertibili in codici sconto :</p>
   <p> 220 punti = 5€ di sconto </p>
   <p> 400 punti = 10€ di sconto </p>
   <p> 780 punti = 20€ di sconto </p>
   <?php
if ($n_punti >= 220) {
    echo "<p><strong>Hai abbastanza punti per ottenere un codice sconto!</strong></p>";
    echo "<form method='POST' action=''>";
    echo "<select name='valore-buono' class='finestra-conversione'>";
    echo "<option value='220'>Buono del valore di 5€</option>";
    echo "<option value='400'>Buono del valore di 10€</option>";
    echo "<option value='780'>Buono del valore di 20€</option>";
    echo "</select>";
    echo "<input type='submit' value='CONVERTI' class='converti'>";
    echo "</form>"; 
}
    mysqli_close($conn);
?>
<div class="messaggio"></div>
</div>
</div>
    

<div class="finestra-card-hidden">
    <div class="esci" id="exit-card" >
        <img src="exit.png" >
</div>
    <div class="card">
        <div class="logo">
        <img src="logo ufficiale.png" alt="Logo Douglas">
        <p> BEAUTY CARD</p>
      </div>
      <div>
        <p> Numero di carta:</p>
        <?php
        echo "<p id='code'>".($userinfo['carta'])."</p>";
        ?>
     </div>
      <div class="code">
      <img src="codice_a_barre.png">
    </div>
</div>
</div>
<h1> La tua carta my Douglas </h1>
<p>Puoi ottenere la tua carta my Douglas in tutti i punti vendita ! </p>
<p>Iscrivendoti al nostro sito i tuoi beauty points verranno cumulati automaticamnte</p>
<p>Ricorda, per ogni 1€ speso, riceverai 1 beauty point.</p>
<button class="card-button"> VISUALIZZA CARTA </button>
    <p> desideri uscire? <a href="logout.php">Logout</a></p>
</div>
</section>
</body>

</html>
