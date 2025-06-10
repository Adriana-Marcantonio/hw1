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
?>

<!DOCTYPE html>
 <html>
 <head>
   <meta charset="utf-8">
   <title>Douglas - Profumeria Online</title>
   <link rel="stylesheet" href="home.css">
   <script src="home.js" defer></script> <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
   <meta name="viewport" content="width=device-width, initial-scale=1">
 </head>
 <body>

   <div class="flex-container">
     <div class="flex-item-left">
      <span id="t1"> 2 campioni omaggio per ogni ordine* </span>
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
       <p> UTILIZZA I TUOI BEAUTY POINTS PER OTTENERE SCONTI VANTAGGIOSI !</p>
   </div>

   <header class="navbar">
     <div class="search-bar">

       <div id="menu">
         <div></div>
         <div></div>
         <div></div>
       </div>

       <div id="search">
<input type="text" id="searchInput" placeholder="<?php echo $userinfo['name'] . ', cosa stai cercando?';  mysqli_close($conn)  ?>">
         <img src="search_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="search" class="icon">
         <input type="submit" id="invia">
     </div>

     </div>
     <div class="logo">
       <img src="logo ufficiale.png" alt="Logo Douglas">
     </div>

     <button class="icons">
     <img src="location_on_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Posizione"class="icon" id="location">
     <a href = 'profilo.php'>   <img src="person_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="utente" class="icon"> </a>
     <a href='preferiti.php'>   <img src="favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Preferiti" class="icon" id="preferiti"> </a>
     <a href='carrello.php'>   <img src="shopping_cart_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png" alt="Carrello" class="icon" id="carrello"> </a>
     </button>

   </header>


   <div id ="category">
     <a>PROFUMI</a>
     <a >MAKE-UP</a>
     <a>SKINCARE</a>
     <a>CAPELLI</a>
     <a>MARCHI</a>
   </div>


     <div id="risultati">

     </div>


   <div id="immagine-principale-da-computer">

     <button class="back-hidden">
       <img src="chevron_backward_24dp_1F1F1F_FILL0_wght400_GRAD0_opsz24.png" alt="back">
     </button>
     <button class="next">
       <img src="navigate_next_24dp_1F1F1F.png" alt="next">
     </button>
     <img src="https://media.douglas.it/medias/Hero-XL-3072x1314-RZ2.jpg?context=bWFzdGVyfGltYWdlc3wyNjc3MDl8aW1hZ2UvanBlZ3xhRGd6TDJnd1pTODJOREkyTURrMU1qWTROall5TWk5SVpYSnZMVmhNTFRNd056SjRNVE14TkMxU1dqSXVhbkJufGI4MjViODQ2MjA2MjcwNGE5YjM0MmE0YTlhMWE0OTY5MzRiYThhY2ZlZDU4NmFkYTRlNDVlYmI0NzM1Zjk0ZGY&imdensity=1&imwidth=3072" class="img-showed">
   <img src="https://media.douglas.it/medias/Hero-Teaser-XL-3072x1314px-GWP.jpg?context=bWFzdGVyfGltYWdlc3w0NjMyOTZ8aW1hZ2UvanBlZ3xhRGhsTDJnd09DODJORFUzT1RRNE5EQTVNRE01T0M5SVpYSnZMVlJsWVhObGNpMVlUQzB6TURjeWVERXpNVFJ3ZUMxSFYxQXVhbkJufDhhZGYxNWFjMTRjMWFlMTg2ZDBlOTk5Mjk0YzVhODg2NTYyOTljYjMwYjg5ZTcwMGIzZDEwY2I3OTVjNzM2MzI&imdensity=1&imwidth=3072" class="img-hidden">
   </div>

   <div id="immagine-principale-mobile">
     <button class="back-hidden">
       <img src="chevron_backward_24dp_1F1F1F_FILL0_wght400_GRAD0_opsz24.png" alt="back">
     </button>
     <button class="next">
       <img src="navigate_next_24dp_1F1F1F.png" alt="next">
     </button>
    <img src="https://media.douglas.it/medias/Hero-S-1170x2079.jpg?context=bWFzdGVyfGltYWdlc3wyNTc3NjJ8aW1hZ2UvanBlZ3xhRGsxTDJneE1TODJOREkyTURrMU1qYzFNakUxT0M5SVpYSnZMVk10TVRFM01IZ3lNRGM1TG1wd1p3fDQxOGIxNGMwM2QwNzRiNmM1MDI5NDAwNjdlODlhNWUxZjE2Yjc1MmQ0MTg1OTAyMmZlNDFkNzBlODNhMTVhMWE&imdensity=1&imwidth=767" class="img-showed">
     <img src="https://media.douglas.it/medias/Hero-Teaser-S-1170x2079px-GWP.jpg?context=bWFzdGVyfGltYWdlc3wzNTMyNTh8aW1hZ2UvanBlZ3xhRGt4TDJnd05TODJORFUzT1RRNE5ERTFOVGt6TkM5SVpYSnZMVlJsWVhObGNpMVRMVEV4TnpCNE1qQTNPWEI0TFVkWFVDNXFjR2N8YzY2YWMyMjJiZTVlYWE0OTA4MjE1NWY3YmEyZWJhZmViZmFiZjQ3YjNiYzk2N2RmMmYyNmM2ZGZmNWQ0YmRlNg&imdensity=1&imwidth=767" class="img-hidden">
   </div>

   <div class="standard">
     <p class="title-intestazione">SCELTI PER TE</p>
   </div>

   <section class="catalogo" data-index="1" id="catalogo-scelti-per-te">
     <button class="back-hidden">
       <img src="chevron_backward_24dp_1F1F1F_FILL0_wght400_GRAD0_opsz24.png" alt="back">
     </button>
       <button class=" next">
         <img src="navigate_next_24dp_1F1F1F.png" alt="next">
       </button>
       </section>

   <div class="servizi">
     <div class="intestazione">
       <p class="title-intestazione">I SERVIZI DOUGLAS</p>
       <a href="">trova il tuo negozio</a>
     </div>
     <div class="container">
     <div class="content-section">
     <img src="https://media.douglas.it/medias/Services-assetsbeautyservices.jpg?context=bWFzdGVyfGltYWdlc3wxODI3MjV8aW1hZ2UvanBlZ3xhREl5TDJnek15ODFNREk0TXpjME56UTBNamN4T0M5VFpYSjJhV05sY3kxaGMzTmxkSE5pWldGMWRIbHpaWEoyYVdObGN5NXFjR2N8ZjAxYWQ4MDNiNGZlMWVkN2MzYjAzMDZmY2UxYjEzMDk0MzM2ZTgyMzNkMTcwMWQxYjE5NDI4Mzk0YTBhNTFkOA&imdensity=1&imwidth=1338">
     <div class="description">
       <p class="title">SERVIZI BEAUTY </p>
       <p>Scopri e prenota online i nostri servizi di make-up, skincare, haircare e molto altro!</p>
       <a href="">Prenota ora</a>
     </div>
     </div>

   <div class="content-section">
     <img src="https://media.douglas.it/medias/services-make-up-school-bts-16-0925.jpg?context=bWFzdGVyfGltYWdlc3wxNjk4ODN8aW1hZ2UvanBlZ3xhREl6TDJnek1DODFNREk0TXpjME56VXdPREkxTkM5elpYSjJhV05sY3kxdFlXdGxMWFZ3TFhOamFHOXZiQzFpZEhNdE1UWXRNRGt5TlM1cWNHY3w5MTFiNWQ0YWVmYjAxODhkNGExODYwODJlMmQwOGY4YWViZWRmMjk5OTM2ODhkNmU3MGUyZmRkNGU5MGI2NmJl&imdensity=1&imwidth=768">
     <div class="description">
       <p class="title">SCOPRI TUTTI I NOSTRI SERVIZI </p>
         <p>I nostri servizi unici ed esclusivi pensati per esaltare la tua bellezza
         </p>
       <a href="">Scopri di più</a>
     </div>
   </div>

   <div class="content-section">
  <img src="https://media.douglas.it/medias/services-gift-wrapping-CC-DK-03-153-BW-092025-RGB.jpg?context=bWFzdGVyfGltYWdlc3wyNDA5MjR8aW1hZ2UvanBlZ3xhR05oTDJoak1pODJOREkwTnpreE9UZ3pOekl4TkM5elpYSjJhV05sY3kxbmFXWjBMWGR5WVhCd2FXNW5MVU5EWDBSTFh6QXpMVEUxTTE5Q1YxOHdPVEl3TWpWZlVrZENMbXB3Wnd8OWNjMWUyMmQ5NGZkMjJiZWM0NzVkYjM3NmExZmM1N2I3ZjNiN2ZkZjhhZWVjNTE0ZDgxY2Q2NDUyNTFiMjRiYQ&imdensity=1&imwidth=954">
     <div class="description">
       <p class="title">GIFT CARD</p>
          <p>Idea regalo: la nostra gift card, anche in formato digitale. Utilizzabile on-line e in negozio
         </p>
       <a href="">Scopri di più</a>
     </div>
   </div>
     </div>
   </div>


   <div class="standard">
     <p class="title-intestazione">NUOVI PRODOTTI</p>
   </div>

   <section class="catalogo" data-index="2" id="catalogo-nuovi-prodotti">
     <button class="back-hidden">
       <img src="chevron_backward_24dp_1F1F1F_FILL0_wght400_GRAD0_opsz24.png" alt="back">
     </button>
     <button class="next">
       <img src="navigate_next_24dp_1F1F1F.png" alt="next">
     </button>
       </section>

        <div class="standard">
     <p class="title-intestazione">PRODOTTI AGGIUNTI DA TE</p>
   </div>
   <section class="catalogo-prodotti-aggiunti">
</section>

 <div class="info">
  <h1>Douglas Onlineshop – Il tuo posto per profumi, Premium Beauty & le ultime tendenze</h1>
  <p>Stai cercando una nuova fragranza, vuoi ricomprare il tuo rossetto preferito o semplicemente lasciarti ispirare? Nel Douglas Onlineshop ti mostriamo i brand più recenti, i prodotti di tendenza e i look più alla moda dei nostri Douglas Creators come Short Clips o in livestream. </p>
 <h2>Douglas Profumeria – Make Life More Beautiful</h2>
 <p>Già nel <strong>1910</strong> le sorelle Maria e Anna Carsten avevano capito quello che oggi è scontato: i cosmetici rendono la vita più bella.
   Il piccolo lusso di un nuovo rossetto, il tanto desiderato High-end-profumo o la perfetta routine di skincare arricchiscono la nostra quotidianità e regalano momenti di glow-up.
    Maria e Anna Carsten hanno aperto la <strong>prima Profumeria Douglas</strong> ad Amburgo, nata dal negozio di saponi "J.S. Douglas & Söhne".
    Successivamente seguirono altre filiali e il business dei profumi diventa indissolubilmente legato al nome "Douglas".
    Da quello che iniziò come negozio fisico, oggi Douglas è diventato anche online un brand che ti presenta le <strong>ultime fragranze, make-up e skincare di tendenza</strong>.
    Inoltre, vediamo come ovvi i seguenti servizi per il tuo shopping:
  </p>
  <ul>
    <li>2 campioncini gratuiti con ogni ordine</li>
    <li>consegna rapida in filiale o a casa (BRT, Fermopoint Poste Italiane o Amazon Shipping) </li>
    <li> buoni regalo online da usare in negozio, nell'app o nell'onlineshop </li>
  </ul>


  <h3>Douglas online – Trova i tuoi Beauty Must-haves, Servizi & Ispirazione</h3>
  <p>Siamo tuoi esperti in profumi, make-up, skincare & co.!
    Inoltre, nelle nostre sezioni stagionali, da San Valentino a estate fino a Natale troverai tutto quello che si adatta al fascino dei momenti più emozionanti dell'anno. Con i <strong>premium brands</strong> come <strong>Yves Saint Laurent, Chanel, Charlotte Tilbury o Rituals</strong>,
    ti concedi un tocco di glamour o regali un momento wow.</p>

   <p>Da Douglas trovi una vasta gamma di brand e prodotti, oltre a tanta conoscenza su come utilizzarli al meglio. Nel nostro Cosmetic Hub rispondiamo a domande come "Quale rossetto mi sta bene?" o "Quale tipo di colore sono?", che interessano particolarmente gli appassionati di beauty.
     Ci muoviamo nel sapere del make-up, da "Truccarsi per principianti" fino ai look di tendenza.
     Nel nostro Skincare Guide esaminiamo attentamente gli ingredienti e le routine:
     cosa c'è dietro il retinolo? E come funziona lo skin cycling? Ti aspettano articoli curati con consigli e idee preziose.</p>

   <p>Da noi l'esperienza di acquisto in-store e online si fondono perfettamente insieme. Ecco alcuni dei nostri <strong>servizi</strong> e <strong>vantaggi</strong> di cui puoi godere come cliente Douglas:</p>
   <ul>
   <li>Douglas Card & Beauty Points: con la Douglas Beauty Card puoi raccogliere punti preziosi sia nello shopping online che in negozio, da utilizzare sul prossimo acquisto per ottenere sconti diretti.</li>

   <li>App Douglas: metti like, salva, aggiungi al carrello – l'App Douglas ti offre tantissime possibilità per fare shopping in modo semplice e personalizzato. Strumenti di consulenza come il nostro trova-profumi ti aiutano a trovare la fragranza giusta. Inoltre, con l'App Douglas hai sempre a portata di mano la tua Douglas Beauty Card digitale.</li>
   <li> Click & Collect: scegli i tuoi nuovi preferiti comodamente online e ritira il tuo pacco in 1-3 giorni lavorativi gratuitamente nel tuo punto vendita desiderato. In questo modo risparmi sulle spese di spedizione e puoi ritirare il pacco mentre fai shopping in città. Puoi pagare direttamente online al momento dell'ordine o in filiale.</li>
   <li>Servizi in negozio: che si tratti del ballo di fine anno, di un compleanno importante o un invito a un matrimonio – ci pensiamo noi! Con i nostri servizi di make-up in negozio per trucco giorno e trucco sera i nostri esperti di bellezza ti truccheranno con look personalizzati per grandi occasioni. I nostri rituali gratuiti in negozio ti entusiasmeranno con un refresh del make-up o un'analisi della pelle personalizzata.
   Hai altre domande sui nostri prodotti cosmetici? Saremo felici di consigliarti alla nostra newsletter gratuita.</li>
   </ul>
   <p>Vuoi saperne di più su Douglas come azienda, i nostri valori, le opportunità di carriera e altro, visita il nostro sito web Douglas Group.</p>
   <h4>Douglas International:</h4>
   <p>Oggi siamo presenti con Douglas in tutta Europa con circa 1.850 negozi e online in oltre 19 paesi:</p>

    <p> Douglas Germania | Douglas Bulgaria | Douglas Italia | Douglas Croazia | Douglas Lettonia | Douglas Lituania | Douglas Paesi Bassi | Douglas Austria | Douglas Polonia | Douglas Portogallo | Douglas Romania | Douglas Svizzera | Douglas Spagna | Douglas Repubblica Ceca | Douglas Slovacchia | Douglas Ungheria | Nocibé | Douglas Belgio | Douglas Slovenia</p>
 </div>


 <footer class="newsletter-footer">
  <div class="newsletter-footer-content">
    <p><strong>Iscriviti ora alla Newsletter</strong></p>
    <p>Iscriviti e ricevi un buono regalo da 5 euro</p>
    <form class="newsletter-form" id="newsletterForm">
      <div class="newsletter-input">
      <input type="email" class="email" id="emailInput" placeholder="Indirizzo email*" required>
      <input type="submit" class="newsletter-submit" value="REGISTRATI">
      </div>
        <p id="formMessage"></p>
    </form>
  </div>
</footer>

  <footer class="end-page">
    <div class="end-page-box">
        <div class="block">
            <img src="local_shipping_24dp_1F1F1F_FILL0_wght200_GRAD0_opsz24.png">
            <strong>Spedizione</strong>
            <p>Consegna entro 3/6 giorni</p>
        </div>
        <div class="block">
          <img src="orders_24dp_1F1F1F_FILL0_wght200_GRAD0_opsz24.png">
            <strong>Spedizione gratuita</strong>
            <p>Da €35,00</p>
        </div>
        </div>
        <div class="end-page-box">
        <div class="block">
          <img src="redeem_24dp_1F1F1F_FILL0_wght200_GRAD0_opsz24.png">
            <strong>2 campioni omaggio</strong>
            <p>A tua scelta</p>
        </div>
        <div class="block">
            <img src="featured_seasonal_and_gifts_24dp_1F1F1F_FILL0_wght200_GRAD0_opsz24.png">
            <strong>Pacchetto regalo</strong>
            <p>Biglietto di auguri</p>
        </div>
    </div>
</footer>
 </body>
</html>