<?php
    // Verifica che l'utente sia già loggato, in caso positivo va direttamente alla home
    include 'autenticazione.php';
    if (checkAuth()) {
        header('Location: home.php');
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) )
    {
        // Se username e password sono stati inviati
        // Connessione al DB
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        // ID e Username per sessione, password per controllo
        $query = "SELECT * FROM users WHERE username = '".$username."'";
        // Esecuzione
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga, il che ci basta perché l'utente autenticato è solo uno
            $entry = mysqli_fetch_assoc($res);
           if (strcmp($_POST['password'], $entry['password']) === 0) {

                // Imposto una sessione dell'utente
                $_SESSION["_agora_username"] = $entry['username'];
                $_SESSION["_agora_user_id"] = $entry['id'];
                header("Location: home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Username e/o password errati.";
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci username e password.";
    }

?>

<html>
    <head>
        <link rel='stylesheet' href='signup.css'>
       <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Accedi - Douglas</title>
    </head>
    <body>
      <div class="flex-container">
      <div class="flex-item-left">
       <span id="t1"> 2 campioni omaggio per ogni ordine*  </span>
        <span id="t2">  Spedizione gratuita da 35,00€ </span>
      </div>
            <nav class="flex-item-right">
        <a>SERVIZI</a>
        <a >BEAUTY CARD</a>
        <a>I NEGOZI</a>
        <a>SPEDIZIONI</a>
      </nav>

    </div>
         <div class="codici-sconto"> 
        <p>-20% UTILIZZANDO IL CODICE SCONTO "FJRIKG20" E SOLO OGGI SPEDIZIONE GRATUITA !</p> 
    </div>
      <div class="logo">
        <img src="logo ufficiale.png" alt="Logo Douglas">
      </div>


        <section>

            <?php
                // Verifica la presenza di errori
                if (isset($error)) {
                    echo "<p class='errorj'>$error</p>";
                }
                
            ?>
            <form name='login' method='post'>
         
                <div class="username">
                    <label for='username'></label>
                    <input type='text' name='username' placeholder="Username*" <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                </div>
                <div class="password">
                    <label for='password'></label>
                    <input type='password' name='password'placeholder="Password*" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                </div>
                <div class="submit-container">
                    <div class="submit">
                        <input type='submit' value="ACCEDI">
                    </div>
                </div>
            </form>
            <div class="login"><h4>Non hai un account?</h4>
            <span><a href="signup.php">iscriviti a Douglas</a></span>
            </div>
        </section>
        </main>
    </body>
</html>