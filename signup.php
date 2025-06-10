<?php
    require_once 'autenticazione.php';

    if (checkAuth()) {
        header("Location: home.php");
        exit;
    }

function CreaCodiceCarta(){
    $str1=substr(uniqid(), 0, 15);
    $pos = rand(1, strlen($str1));
    $str2 = substr_replace($str1, '', $pos - 1, 4); 
    $caratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char = $caratteri[random_int(0, strlen($caratteri) - 1)]; 
    return substr_replace($str2, $char, $pos - 1, 0); 
}

    $errors = []; 

    // Verifica l'esistenza di dati POST
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && !empty($_POST["genere"]) &&
        !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"]))
    {
        // Connessione al database
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if (!$conn) {
            $errors[] = "Errore di connessione al database.";
          
        } else {
            //  pulisce le variabili
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email'])); // Convertiamo in minuscolo 
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);
            $genere = mysqli_real_escape_string($conn, $_POST['genere']);
            $password_input = $_POST['password']; 
            $confirm_password_input = $_POST['confirm_password'];
            $carta=CreaCodiceCarta();
            # VALIDAZIONI

            // USERNAME
            if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $username)) {
                $errors[] = "Username non valido: deve contenere lettere, numeri, underscore e massimo 15 caratteri.";
            } else {
                $query = "SELECT username FROM users WHERE username = '$username'";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) > 0) {
                    $errors[] = "Username già utilizzato.";
                }
            }

            // EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email non valida.";
            } else {
                $query = "SELECT email FROM users WHERE email = '$email'";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) > 0) {
                    $errors[] = "Email già utilizzata.";
                }
            }

            // PASSWORD
            if (strlen($password_input) < 8) {
                $errors[] = "La password deve contenere almeno 8 caratteri.";
            }
            // Controllo complessità password
            if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[._%$&£]).{8,}$/', $password_input)) {
                $errors[] = "La password deve contenere almeno una maiuscola, un numero e un carattere speciale (._%$&£).";
            }

            // CONFERMA PASSWORD
            if (strcmp($password_input, $confirm_password_input) != 0) {
                $errors[] = "Le password non coincidono.";
            }

            # REGISTRAZIONE NEL DATABASE
            if (count($errors) == 0) {
              
                $query = "INSERT INTO users(username, password, name, surname, genere, email,carta) VALUES('$username', '$password_input', '$name', '$surname', '$genere' ,'$email','$carta')";
            
                if (mysqli_query($conn, $query)) {
                    $_SESSION["_agora_username"] = $_POST["username"]; // Usiamo il valore POST originale
                    $_SESSION["_agora_user_id"] = mysqli_insert_id($conn);
                    mysqli_close($conn);
                    header("Location: home.php");
                    exit;
                } else {
                    $errors[] = "Errore durante la registrazione. Riprova più tardi."; 
                }
            }
            mysqli_close($conn); 
        }
    }
    else if (isset($_POST["username"])) {
        
        $errors[] = "Riempi tutti i campi";
    }

?>

<html>
<head>
<link rel="stylesheet" href="signup.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
<script src='signup.js' defer></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

    <title>Iscriviti - Douglas</title>
</head>
<body>
      <div class="flex-container">
      <div class="flex-item-left">
        <span id="t1"> 2 campioni omaggio per ogni ordine* </span>
        <span id="t2"> Spedizione gratuita da 35,00€ </span>
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
<section >
            <p>Iscriviti gratuitamente e ottieni fantastici vantaggi!</p>
            <form name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
                <div class="names">
                    <div class="name">
                        <label for='name'></label>

                        <input type='text' name='name' placeholder="Nome*" <?php if(isset($_POST["name"])){echo "value=\"".$_POST["name"]."\"";} ?> >
                    </div>
                    <div class="surname">
                        <label for='surname'></label>
                        <input type='text' name='surname'placeholder="Cognome*" <?php if(isset($_POST["surname"])){echo "value=\"".$_POST["surname"]."\"";} ?> >

                    </div>
                </div>
                <div class="username">
                    <label for='username'></label>
                    <input type='text' name='username' placeholder="Nome Utente*" <?php if(isset($_POST["username"])){echo "value=\"".$_POST["username"]."\"";} ?>>
                </div>

                <div class="gender">
                   <input type="radio" name="genere" value="m" <?php if(isset($_POST["genere"]) && $_POST["genere"] == "m") echo "checked"; ?>> Maschio
                   <input type="radio" name="genere" value="f" <?php if(isset($_POST["genere"]) && $_POST["genere"] == "f") echo "checked"; ?>> Femmina
                   <input type="radio" name="genere" value="o" <?php if(isset($_POST["genere"]) && $_POST["genere"] == "o") echo "checked"; ?>> Altro
                </div>

                <div class="email">
                    <label for='email'></label>
                    <input type='text' name='email' placeholder="Email*" <?php if(isset($_POST["email"])){echo "value=\"".$_POST["email"]."\"";} ?>>
                </div>
                <div class="password">
                    <label for='password'></label>
                    <input type='password' name='password'placeholder="Password*" <?php if(isset($_POST["password"])){echo "value=\"".$_POST["password"]."\"";} ?>>
                    <div><span>Inserisci almeno 8 caratteri</span></div>
                </div>
                <div class="confirm_password">
                    <label for='confirm_password'></label>
                    <input type='password' name='confirm_password' placeholder="Conferma Password*" <?php if(isset($_POST["confirm_password"])){echo "value=\"".$_POST["confirm_password"]."\"";} ?>>
                    <div>
                </div>
                <div class="allow">
                    <input type='checkbox' name='allow' value="1" <?php if(isset($_POST["allow"]) && $_POST["allow"] == "1"){echo "checked";} ?>>
                    <label for='allow'>Accetto i termini e condizioni d'uso di Douglas.</label>
                </div>
                <?php
                // Mostra tutti gli errori raccolti
                if (!empty($errors)) {
                    echo "<div class='error-messages'>";
                    foreach ($errors as $err) {
                        echo "<p class='errorj'>" . $err . "</p>"; 
                    }
                    echo "</div>";
                }
                ?>
                <div class="submit">
                    <input type='submit' value="REGISTRATI" id="submit">
                </div>
            </form>
            <div class="signup">Hai un account? <a href="login.php">Accedi</a>
        </section>
    <footer>
    </footer>
</body>
</html>