let numpreferiti = 0; 
let numelementicarrello = 0;


function onResponse(response) {
    if (!response.ok) {
        console.error("Errore HTTP:", response.status, response.statusText);
    }
    return response.json();
}

function newsletter(event){
event.preventDefault();
const emailInput = document.querySelector('#emailInput');
const formMessage = document.querySelector('#formMessage');
const email = emailInput.value;
const formData = new FormData();
      formData.append('email', email);
 fetch('gestione_newsletter.php', {
        method: 'POST',
        body: formData
    })  .then(response => response.json())
    .then(data => {
        if (data.success) {
            formMessage.textContent=data.message;
            
            }
    });
 
}
  const newsletterForm = document.querySelector('#newsletterForm'); 
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', newsletter); 
    }


function cercaProdotti() {
    const input = document.querySelector('#searchInput').value.trim();
    const lowerCase = input.toLowerCase();

    fetch("http://makeup-api.herokuapp.com/api/v1/products.json")
        .then(onResponse)
        .then(data => {
            if (!Array.isArray(data)) {
                console.error("Dati inattesi dall'API Makeup:", data);
                mostraRisultati([]);
                return;
            }

            const prodottiFiltrati = data.filter(product => {
                const name = product.name ? product.name.toLowerCase() : '';
                const description = product.description ? product.description.toLowerCase() : '';
                const category = product.category ? product.category.toLowerCase() : '';
                const brand = product.brand ? product.brand.toLowerCase() : '';
                const type = product.product_type ? product.product_type.toLowerCase() : '';

                return name.includes(lowerCase) ||
                       description.includes(lowerCase) ||
                       category.includes(lowerCase) ||
                       brand.includes(lowerCase) ||
                       type.includes(lowerCase);
            });

            const risultatiLimitati = prodottiFiltrati.slice(0, 15); 

            mostraRisultati(risultatiLimitati); 
        })
        .catch(error => console.error("Errore durante la ricerca:", error));
}

function aggiunginuovoelemento(product,event) {
      const bottone = event.target.closest('.aggiungi');
    fetch('inserisci_prodotto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            nome: product.name,
            descrizione: "prodotto aggiunto da te!", 
            prezzo: product.price,
            quantita_prodotto: "non specificata ", 
            pezzi: 10 ,
            categoria: product.category,
            immagine: product.image_link, 
            marca:product.brand
        }) 
    })
    .then(response => response.json())
    .then(data => {
        console.log("Prodotto salvato nel database:", data);
        bottone.classList.add("rimuovi");
        bottone.classList.remove("aggiungi");
        bottone.textContent="RIMUOVI";
        bottone.addEventListener("click", (event) => rimuovielemento(product, event)); 
        bottone.dataset.idProdotto = data.id_prodotto_inserito; 
          loadProducts(); 
    })
    .catch(error => console.error("Errore nell'aggiunta del prodotto:", error));
}
function rimuovielemento(product, event) {
    const bottone = event.target.closest('.rimuovi');

    const formData = new FormData();
    formData.append('id_prodotto', product.id); 

    fetch('rimuovi_prodotto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Prodotto rimosso dal database:", data);
        bottone.classList.add("aggiungi");
        bottone.classList.remove("rimuovi");
        bottone.textContent = "AGGIUNGI";
        bottone.addEventListener("click", (event) => aggiunginuovoelemento(product, event));
        delete bottone.dataset.idProdotto; 
        loadProducts(); 
    })
    .catch(error => console.error("Errore nella rimozione del prodotto:", error));
}



function mostraRisultati(prodotti) {
    const risultatiContainer = document.querySelector('#risultati');
    risultatiContainer.innerHTML = ""; 

    if (prodotti.length === 0) {
        const vuoto = document.createElement('p');
        vuoto.textContent = "Nessun prodotto trovato";
        risultatiContainer.appendChild(vuoto);
        return; 
    }

    for (let i = 0; i < prodotti.length; i++) {
        const product = prodotti[i];

        const prodottoDiv = document.createElement('div');
        prodottoDiv.classList.add('prodotto');

        const imgContainer = document.createElement('div');
        imgContainer.classList.add('img-container');

        const imgProdotto = document.createElement('img');
       
        imgProdotto.src = product.image_link || 'path/to/placeholder-image.png'; // Aggiunto fallback per sicurezza
        imgProdotto.alt = product.name || 'Immagine non trovata '; 
        imgContainer.appendChild(imgProdotto);
        prodottoDiv.appendChild(imgContainer);

        const descrizioneProdotto = document.createElement('div');
        descrizioneProdotto.classList.add('descrizione-prodotto');

        const nomeProdotto = document.createElement('p');
        nomeProdotto.classList.add('t');
        nomeProdotto.textContent = "" + product.name; 
        descrizioneProdotto.appendChild(nomeProdotto);

        const prezzoProdotto = document.createElement('strong');
        prezzoProdotto.classList.add('prezzo-prodotto-valore');
        prezzoProdotto.textContent = '€' + product.price; 
        descrizioneProdotto.appendChild(prezzoProdotto);
        
        prodottoDiv.appendChild(descrizioneProdotto);

        const Div = document.createElement('div');
        Div.classList.add('offerta');
    
        const aggiungi= document.createElement('button');
        aggiungi.classList.add('aggiungi');
        aggiungi.textContent="AGGIUNGI"
        aggiungi.addEventListener("click", (event) => aggiunginuovoelemento(product, event)); // Passa il prodotto specifico
        Div.appendChild(aggiungi);
        prodottoDiv.appendChild(Div); 
        risultatiContainer.appendChild(prodottoDiv); 
    }
}
document.querySelector('#invia').addEventListener('click', gestisciClick);
document.querySelector('#searchInput').addEventListener('keypress', gestisciInvio);

function gestisciClick(event) {
    event.preventDefault();
    cercaProdotti();
}

function gestisciInvio(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        cercaProdotti();
    }
}



function handleResponse(response) {
    if (!response.ok) {
        console.error('Errore HTTP:', response.status, response.statusText);
    }
    return response.json();
}


function creaProdottoHTML(p) {
    const productDiv = document.createElement('div');
    productDiv.classList.add('prodotto');

    const imgContainer = document.createElement('div');
    imgContainer.classList.add('img-container');

    const imgProduct = document.createElement('img');
    imgProduct.src = p.immagine;
    imgProduct.alt = p.nome; 
    imgContainer.appendChild(imgProduct);

    const iconFavorite = document.createElement('img');
    iconFavorite.classList.add('icon');
    iconFavorite.alt = 'Preferiti';
    iconFavorite.dataset.idProdotto = p.id;
    iconFavorite.dataset.sourceTable = p.sourceTable;

    if (p.isFavorited) {
        iconFavorite.src = 'favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png';
        iconFavorite.addEventListener('click', rimuovi_preferito);
    } else {
        iconFavorite.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png';
        iconFavorite.addEventListener('click',preferiti);
    }
    imgContainer.appendChild(iconFavorite);

    if (p.tipo === 'B') {
        const newButton = document.createElement('button');
        newButton.classList.add('new');
        newButton.textContent = 'NUOVO';
        imgContainer.appendChild(newButton);
    }
    if (p.tipo==='S'){
    const icontogli = document.createElement('img');
    icontogli.classList.add('esci');
    icontogli.alt = 'rimuovi';
    icontogli.dataset.idProdotto = p.id;
    icontogli.src = 'exit.png';
    icontogli.addEventListener("click", (event) => rimuovielemento(p, event)); 
    productDiv.style.position = "relative";
   productDiv.appendChild(icontogli);

    }
    productDiv.appendChild(imgContainer);
    const descrizioneProdotto = document.createElement('div');
    descrizioneProdotto.classList.add('descrizione-prodotto');
   const nomeProdotto = document.createElement('p');
    nomeProdotto.classList.add('t');
    nomeProdotto.textContent = p.nome;
    descrizioneProdotto.appendChild(nomeProdotto);
    const descProdotto = document.createElement('p');
    descProdotto.classList.add('desc');
    descProdotto.textContent = p.descrizione;
    descrizioneProdotto.appendChild(descProdotto);
    const prezzoProdotto = document.createElement('strong');
    prezzoProdotto.classList.add('prezzo-prodotto-valore'); 
    prezzoProdotto.textContent = '€' + p.prezzo;
    descrizioneProdotto.appendChild(prezzoProdotto);
    const quantitaProdotto = document.createElement('p');
    quantitaProdotto.classList.add('light-font');
    quantitaProdotto.textContent = p.quantita_prodotto;
    descrizioneProdotto.appendChild(quantitaProdotto);
    productDiv.appendChild(descrizioneProdotto);

    const offertaDiv = document.createElement('div');
    offertaDiv.classList.add('offerta');

    const sorpresaDiv = document.createElement('div');
    sorpresaDiv.textContent = ' + SORPRESA ';
    offertaDiv.appendChild(sorpresaDiv);

    const cartButton = document.createElement('button');
    cartButton.dataset.idProdotto = p.id; 
          cartButton.dataset.sourceTable = p.sourceTable;
    if (p.isInCarrello) {
        cartButton.classList.add('rimuovi-dal-carrello');
        cartButton.textContent = '- rimuovi dal carrello';
        cartButton.addEventListener('click', rimuoviDalCarrello);
    } else { //default
        cartButton.classList.add('aggiungi-al-carrello');
        cartButton.textContent = '+ aggiungi al carrello';
        cartButton.addEventListener('click', aggiungiAlCarrello);
    }
    offertaDiv.appendChild(cartButton);
    productDiv.appendChild(offertaDiv);

    return productDiv;
}


function loadProducts() {
  const catalogoSceltiPerTe = document.querySelector('#catalogo-scelti-per-te');
    const catalogoNuoviProdotti = document.querySelector('#catalogo-nuovi-prodotti');
    const catalogoprodottiaggiunti = document.querySelector('.catalogo-prodotti-aggiunti'); 
    if (catalogoprodottiaggiunti) catalogoprodottiaggiunti.innerHTML = "";

    numpreferiti = 0; //resetto e ricalcolo
    numelementicarrello = 0;
    fetch("caricamento_dati_home.php")
        .then(handleResponse)
        .then(data => {
            if (data.success) {
                if (catalogoSceltiPerTe && data.products_A) { // Controllo esistenza e null/undefined
                    for (let i = 0; i < data.products_A.length; i++) {
                        const product = data.products_A[i];
                        catalogoSceltiPerTe.appendChild(creaProdottoHTML(product));
                           if (product.isFavorited) numpreferiti++;
                          if (product.isInCarrello) numelementicarrello++;
                    }
                } 
                if (catalogoNuoviProdotti && data.products_B) { 
                    for (let i = 0; i < data.products_B.length; i++) {
                        const product = data.products_B[i];
                        catalogoNuoviProdotti.appendChild(creaProdottoHTML(product));
                         if (product.isFavorited) numpreferiti++;
                          if (product.isInCarrello) numelementicarrello++;
                    }
                }
                 if (catalogoprodottiaggiunti && data.prodotti_scelti) { 
                    if(data.prodotti_scelti.length==0){
                        const nuovo= document.createElement('p');
                        nuovo.textContent="Ancora nessun prodotto aggiunto..."
                        catalogoprodottiaggiunti.appendChild(nuovo);
                    }
                    for (let i = 0; i < data.prodotti_scelti.length; i++) {
                        const product = data.prodotti_scelti[i];
                        catalogoprodottiaggiunti.appendChild(creaProdottoHTML(product));
                         if (product.isFavorited) numpreferiti++;
                         if (product.isInCarrello) numelementicarrello++;
                         console.log(product.isFavorited);
                    }
                }
                 aggiornaBadge(); 
                aggiornaBadgeCarrello(); 
                inizializzaCataloghi();
            }
        });

}

loadProducts();



function mostramenu() {
    if (document.querySelector('.menu-mobile')) {
        chiudiMenu();
        return;
    }
    let overlay = document.createElement("div");
    overlay.classList.add("overlay");
    let menuDiv = document.createElement("div");
    menuDiv.classList.add("menu-mobile");
    const menuItems = [
        { text: "PROFUMI", url: "#" },
        { text: "MAKE-UP", url: "#" },
        { text: "CAPELLI", url: "#" },
        { text: "MARCHI", url: "#" },
        { text: "SPEDIZIONI", url: "#" },
        { text: "BEAUTY CARD", url: "#" }
    ];
    for (let i = 0; i < menuItems.length; i++) {
        const item = document.createElement("a");
        item.textContent = menuItems[i].text;
        item.href = menuItems[i].url;
        item.classList.add("menu-link"); 
        menuDiv.appendChild(item);
    }
    document.body.appendChild(overlay);
    document.body.appendChild(menuDiv);
    document.body.classList.add("body-no-scroll");

    // Delay per evitare chiusura immediata
    setTimeout(() => {
        document.body.addEventListener("click", chiudidalBody);
    }, 10);

    overlay.addEventListener("click", chiudiMenu);
}

function chiudiMenu() {
    const menuDiv = document.querySelector('.menu-mobile');
    const overlay = document.querySelector('.overlay');
    if (menuDiv) document.body.removeChild(menuDiv);
    if (overlay) document.body.removeChild(overlay);
    document.body.removeEventListener("click", chiudidalBody);
    document.body.classList.remove("body-no-scroll");
}

function chiudidalBody(event) {
    const menuDiv = document.querySelector('.menu-mobile');
    const locationDiv = document.querySelector('.location-mobile');
    

    if (menuDiv && !menuDiv.contains(event.target) && event.target !== document.querySelector('#menu')) {
        chiudiMenu();
    }

    if (locationDiv && !locationDiv.contains(event.target) && event.target !== document.querySelector('#location')) {
        chiudinegozi();
    }
}


// Evento per aprire il menu
const menu = document.querySelector('#menu');
if (menu) {
    menu.addEventListener('click', mostramenu);
}


// scorrimento immagini

//funzione di supporto 
function aggiornaImmagini( vet,dim) {
    for (let i = 0; i < dim; i++) {
        vet[i].classList.remove("img-showed");
        vet[i].classList.add("img-hidden");
    }
    vet[contatoreimmagini].classList.remove("img-hidden");
    vet[contatoreimmagini].classList.add("img-showed");
}
function mostraImmaginePrecedente(event) {
    const backphoto = event.currentTarget;
        contatoreimmagini--;
        const images = Array.from(document.querySelectorAll("#immagine-principale-da-computer .img-showed, #immagine-principale-da-computer .img-hidden"));
        const Tot = images.length;
        const nextphoto = document.querySelector("#immagine-principale-da-computer .next")||document.querySelector("#immagine-principale-da-computer .next-hidden");
        if (contatoreimmagini == 0) {
            backphoto.classList.remove("back");
            backphoto.classList.add("back-hidden");
        } else {
            backphoto.classList.remove("back-hidden");
            backphoto.classList.add("back");
        }

        if (contatoreimmagini < Tot - 1) {
            nextphoto.classList.remove("next-hidden");
            nextphoto.classList.add("next");
        }
        aggiornaImmagini(images,Tot);
}

function mostraImmagineSuccessiva(event) {
    const images =Array.from(document.querySelectorAll("#immagine-principale-da-computer .img-showed, #immagine-principale-da-computer .img-hidden"));
    const Tot = images.length;
    const nextphoto= event.currentTarget;
    const backphoto=document.querySelector("#immagine-principale-da-computer .back")|| document.querySelector(" #immagine-principale-da-computer .back-hidden");
    contatoreimmagini++;
     if (contatoreimmagini >= Tot-1){
        nextphoto.classList.remove("next");
        nextphoto.classList.add("next-hidden");
    }
        
    if(contatoreimmagini>=1){
        backphoto.classList.remove("back-hidden");
        backphoto.classList.add("back");
    }
   aggiornaImmagini(images,Tot);
}
function mostraImmagineSuccessivaMobile(event) {
    const images =Array.from(document.querySelectorAll("#immagine-principale-mobile .img-showed, #immagine-principale-mobile .img-hidden"));
    const Tot = images.length;
    const nextphoto= event.currentTarget;
    const backphoto = document.querySelector("#immagine-principale-mobile .back") || document.querySelector("#immagine-principale-mobile .back-hidden");

    contatoreimmagini++;
     if (contatoreimmagini >= Tot-1){
        nextphoto.classList.remove("next");
        nextphoto.classList.add("next-hidden");
    }
        
    if(contatoreimmagini>=1){
        backphoto.classList.remove("back-hidden");
        backphoto.classList.add("back");
    }
   aggiornaImmagini(images,Tot);
}
function mostraImmaginePrecedenteMobile(event) {
    const backphoto = event.currentTarget;
        contatoreimmagini--;
        const images = Array.from(document.querySelectorAll("#immagine-principale-mobile .img-showed, #immagine-principale-mobile .img-hidden"));
        const Tot = images.length;
        const nextphoto = document.querySelector("#immagine-principale-mobile .next") || document.querySelector("#immagine-principale-mobile .next-hidden");

        if (contatoreimmagini == 0) {
            backphoto.classList.remove("back");
            backphoto.classList.add("back-hidden");
        } else {
            backphoto.classList.remove("back-hidden");
            backphoto.classList.add("back");
        }

        if (contatoreimmagini < Tot - 1) {
            nextphoto.classList.remove("next-hidden");
            nextphoto.classList.add("next");
        }
        aggiornaImmagini(images,Tot);
}


let contatoreimmagini=0;
const nextphoto=document.querySelector("#immagine-principale-da-computer .next");
const nextphotomobile=document.querySelector("#immagine-principale-mobile .next");
nextphoto.addEventListener("click", mostraImmagineSuccessiva);
nextphotomobile.addEventListener("click", mostraImmagineSuccessivaMobile);
const backphotomobile = document.querySelector("#immagine-principale-mobile .back") || document.querySelector("#immagine-principale-mobile .back-hidden");
const backphoto = document.querySelector("#immagine-principale-da-computer .back")||  document.querySelector("#immagine-principale-da-computer .back-hidden");

backphoto.addEventListener("click", mostraImmaginePrecedente);
backphotomobile.addEventListener("click", mostraImmaginePrecedenteMobile);


// gestione prodotti preferiti
 function aggiornaBadge(){
    const iconContainer = document.querySelector("#preferiti").parentElement; // Seleziona il pulsante <button class="icons"> (il parente del nodo)
    // Controlla se il badge esiste già
    let badge = iconContainer.querySelector(".badge");
     if(numpreferiti>0){
        if (!badge) { 
            //se non esiste lo crea
            badge = document.createElement("span");
            badge.classList.add("badge");
            iconContainer.appendChild(badge); 
        } //altrimenti aggiorna solo il text
        badge.textContent = numpreferiti;
     } else{
        if(badge)
            badge.remove();
     }
   
 }
  function aggiornaBadgeCarrello(){
    const iconContainer = document.querySelector("#carrello").parentElement; // Seleziona il pulsante <button class="icons"> (il parente del nodo)
    // Controlla se il badge esiste già
    let badge = iconContainer.querySelector(".badge-carrello");
     if(numelementicarrello>0){
        if (!badge) { 
            //se non esiste lo crea
            badge = document.createElement("span");
            badge.classList.add("badge-carrello");
            iconContainer.appendChild(badge); 
        } //altrimenti aggiorna solo il text
        badge.textContent = numelementicarrello;
     } else{
        if(badge)
            badge.remove();
     }
   
 }

function rimuovi_preferito(event) {
    const icon = event.currentTarget;
    const Idprodotto= icon.dataset.idProdotto;
    const sourceTable = icon.dataset.sourceTable;
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto); // Chiave 'Idprodotto'
    formData.append('action', 'remove');
     formData.append('sourceTable', sourceTable);   

    fetch('gestione_preferiti_carrello.php', {
       method: 'POST',
        body:formData
        })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            icon.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png';
            icon.removeEventListener('click', rimuovi_preferito);
            icon.addEventListener('click', preferiti);
            numpreferiti--;
            aggiornaBadge();
        } else {
            console.error('preferito non rimosso:', data.message);
        }
    });
    }


function preferiti(event) {
  const icon=event.currentTarget;
  const Idprodotto= icon.dataset.idProdotto;
  const sourceTable = icon.dataset.sourceTable;
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'add');
     formData.append('sourceTable', sourceTable);
 fetch('gestione_preferiti_carrello.php', {
        method: 'POST',
        body: formData
    })  .then(response => response.json())
    .then(data => {
        if (data.success) {
             icon.src='favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png';
             numpreferiti++;
             icon.removeEventListener('click', preferiti);
             icon.addEventListener('click', rimuovi_preferito);
             aggiornaBadge();} else{
                console.error('Error', data.message);
             }
               });
}


function aggiungiAlCarrello(event){ 
    const element=event.currentTarget;
    const Idprodotto= element.dataset.idProdotto;
    const sourceTable = element.dataset.sourceTable;
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'add2');
    formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', {
        method: 'POST',
        body: formData
    })  .then(response => response.json())
    .then(data => {
        if (data.success) {
            element.removeEventListener('click', aggiungiAlCarrello);
            element.classList.remove("aggiungi-al-carrello");
            element.classList.add("rimuovi-dal-carrello");
            element.addEventListener('click', rimuoviDalCarrello);
            element.textContent = "- rimuovi dal carrello";
            numelementicarrello++;
            aggiornaBadgeCarrello();
            } else{
                console.error('Error:', data.message);
             }
               });

}
function rimuoviDalCarrello(event){
    const element = event.currentTarget;
    const Idprodotto= element.dataset.idProdotto;
    const sourceTable = element.dataset.sourceTable;

    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'remove2');   
    formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', {
       method: 'POST',
        body:formData
        })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            element.removeEventListener('click', rimuoviDalCarrello);
            element.classList.remove("rimuovi-dal-carrello");
            element.classList.add("aggiungi-al-carrello");
            element.addEventListener('click', aggiungiAlCarrello);
            element.textContent = "+ aggiungi al carrello";
            numelementicarrello--;
            aggiornaBadgeCarrello();
    
        } else {
            console.error('error:', data.message);
        }
    });

}


aggiornaBadgeCarrello();
// funzioni di supporto
function mostraProdotto(el) {
    el.classList.remove("prodotto-hidden");
    el.classList.add("prodotto");
  }
  
  function nascondiProdotto(el) {
    el.classList.remove("prodotto");
    el.classList.add("prodotto-hidden");
  }
  
// scorrimento prodotti in avanti aggiunta del bottone back
function aggiornaCatalogo(event) {
    const catalogoAttivo = event.currentTarget.closest(".catalogo");
    let itemsPerRow = parseInt(getComputedStyle(catalogoAttivo).getPropertyValue("--items-per-row").trim(), 10); //getComputedStyle visualizza tutte le proprietà css di CatalogoAttivo, prendiamo il valore della variabile con getpropertyvalue ed eliminiamo possibili spazi con trim (forse non necessario)
    let visualizzatiIndex = parseInt(catalogoAttivo.getAttribute("data-visualizzati")) || parseInt(itemsPerRow); //get restituisce null se non esiste quindi al primo click su ogni catalogo visualizzatiIndex è 5
    const prodotti = catalogoAttivo.querySelectorAll(".prodotto, .prodotto-hidden");
    const totaleProdotti = prodotti.length;
    const next = catalogoAttivo.querySelector(".next, .next-hidden");
    const back = catalogoAttivo.querySelector(".back, .back-hidden");
    let resto = totaleProdotti %  itemsPerRow;

    let startIndex = visualizzatiIndex -  itemsPerRow;
    for (let i = startIndex; i < visualizzatiIndex; i++) {
        nascondiProdotto(prodotti[i]);
    }

    if (resto !== 0 && visualizzatiIndex >= totaleProdotti - resto) {
        for (let i = totaleProdotti -  itemsPerRow; i < totaleProdotti; i++) {
            if (!prodotti[i].classList.contains("prodotto")) { 
                mostraProdotto(prodotti[i]);
            }
        }
         if(itemsPerRow == 5){ 
        if (next && visualizzatiIndex >= totaleProdotti -  itemsPerRow*2) {  
            next.classList.remove("next");
            next.classList.add("next-hidden"); 
        }
    }   else{
        if (next && visualizzatiIndex >= totaleProdotti -  itemsPerRow) {  
            next.classList.remove("next");
            next.classList.add("next-hidden"); 
        }
    }
        if (back) {  
            back.classList.remove("back-hidden");
            back.classList.add("back");    
        } 
    }
   //ultimi prodotti n prodotti multiplo di 5 o di 2
    if (visualizzatiIndex < totaleProdotti -  itemsPerRow) {
        for (let i = visualizzatiIndex; i < visualizzatiIndex +  itemsPerRow; i++) {
            mostraProdotto(prodotti[i]);
        }
        visualizzatiIndex +=  itemsPerRow;
    } else {
        for (let i = visualizzatiIndex; i < totaleProdotti; i++) {
            mostraProdotto(prodotti[i]);
        }    
        if (next) {
            next.classList.remove("next");
            next.classList.add("next-hidden");
        }
        if (back && visualizzatiIndex > itemsPerRow) {
            back.classList.remove("back-hidden");
            back.classList.add("back");
        }
        
    }

    catalogoAttivo.setAttribute("data-visualizzati", visualizzatiIndex); //aggiungiamo o aggiorniamo valore
}

function Back(event) {
    const catalogoAttivo = event.currentTarget.closest(".catalogo");
    let itemsPerRow = parseInt(getComputedStyle(catalogoAttivo).getPropertyValue("--items-per-row").trim(),10);
    let visualizzatiIndex = parseInt(catalogoAttivo.getAttribute("data-visualizzati")) || parseInt(itemsPerRow); //converte stringa in numero e riprende l'indice del prodotto del catalogo attivo
    const prodotti = catalogoAttivo.querySelectorAll(".prodotto, .prodotto-hidden");
    const totaleProdotti = prodotti.length;
    const back = catalogoAttivo.querySelector(".back, .back-hidden");
    const next = catalogoAttivo.querySelector(".next, .next-hidden"); 
    const currentGroupStartIndex = visualizzatiIndex - itemsPerRow;

    for (let i = currentGroupStartIndex - itemsPerRow; i < visualizzatiIndex; i++) {
        if (i >= 0 && i < totaleProdotti) {
            nascondiProdotto(prodotti[i]);
        }
    }

    visualizzatiIndex -= itemsPerRow;
    if (visualizzatiIndex < itemsPerRow) visualizzatiIndex = itemsPerRow;

     const newGroupStartIndex = visualizzatiIndex - itemsPerRow;
    for (let i = newGroupStartIndex; i < visualizzatiIndex; i++) {
        if (i >= 0 && prodotti[i]) {
            mostraProdotto(prodotti[i]);
        }
    }

    catalogoAttivo.setAttribute("data-visualizzati", visualizzatiIndex);
    if (back) { // Assicurati che back esista
        if (visualizzatiIndex <= itemsPerRow) {
            back.classList.remove("back");
            back.classList.add("back-hidden");
        } else {

            back.classList.remove("back-hidden");
            back.classList.add("back");
        }
    }

    // Riattiva il pulsante next se non siamo all'ultima "pagina"
    if (next) { 
        if (visualizzatiIndex < totaleProdotti) { 
            next.classList.remove("next-hidden");
            next.classList.add("next");
        }
       
    }
  
}


function inizializzaCataloghi() {
    const cataloghi = document.querySelectorAll(".catalogo");
    cataloghi.forEach(catalogoAttivo => {
        const nextButton = catalogoAttivo.querySelector(".next, .next-hidden");
        const backButton = catalogoAttivo.querySelector(".back, .back-hidden");

        if (nextButton) {
            nextButton.removeEventListener("click", aggiornaCatalogo);
            nextButton.addEventListener("click", aggiornaCatalogo);
        }
        if (backButton) {
            backButton.removeEventListener("click", Back);
            backButton.addEventListener("click", Back);
        }

        let itemsPerRow = parseInt(getComputedStyle(catalogoAttivo).getPropertyValue("--items-per-row").trim(), 10);
        const prodotti = catalogoAttivo.querySelectorAll(".prodotto, .prodotto-hidden");
        const totaleProdotti = prodotti.length;

       
        catalogoAttivo.setAttribute("data-visualizzati", itemsPerRow);

        // Nascondi tutti i prodotti tranne i primi N
        for (let i = 0; i < totaleProdotti; i++) {
            if (i < itemsPerRow) {
                mostraProdotto(prodotti[i]);
            } else {
                nascondiProdotto(prodotti[i]);
            }
        }


        if (backButton) {
            backButton.classList.remove("back");
            backButton.classList.add("back-hidden");
        }
        if (nextButton) {
            if (totaleProdotti > itemsPerRow) {
                nextButton.classList.remove("next-hidden");
                nextButton.classList.add("next");
            } else {
                nextButton.classList.remove("next");
                nextButton.classList.add("next-hidden");
            }
        }
    });
}
// trova negozi vicino a me

function onJson(json) {
    console.log(json);
}

function onResponse(response) {
    return response.json();
}

function fetchData(url) {
   // return fetch(url, { mode: "no-cors" }).then(onResponse);
   return fetch(url).then(onResponse);
}

function extractCoordinates(geoData) {
    const messaggio = document.createElement("p");
    messaggio.innerHTML = "";
    const resultsContainer = document.querySelector(".results");

    if (!geoData || !geoData.features || geoData.features.length === 0) {
        messaggio.classList.add("mess-error");
        messaggio.textContent = "Non è stato trovato nessun luogo! Riprova...";
        resultsContainer.appendChild(messaggio);
        return null;
    }

    const lat = geoData.features[0].geometry.coordinates[1];
    const lon = geoData.features[0].geometry.coordinates[0];
    console.log(" Coordinate trovate:", lat, lon); //verifica
    return { lat, lon };
}

function getReverseAddress(lat, lon) {
    const reverseUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lon;

   
    return fetchData(reverseUrl)
        .then(json => json.display_name || "Indirizzo non disponibile");
           //display_name è una variabile del json di nomination

}

function displayResults(searchData) {
    const resultsContainer = document.querySelector(".results");
    resultsContainer.innerHTML = "";

    if (!searchData || !searchData.elements) {
        const messaggio = document.createElement("p");
        messaggio.classList.add("mess-error");
        messaggio.textContent = "Errore nel caricamento dei dati.";
        resultsContainer.appendChild(messaggio);
        return;
    }

    for (let store of searchData.elements) {
        if (store.tags.brand && store.tags.brand === "Douglas") {
            const storeAddress = document.createElement("p");
            getReverseAddress(store.lat, store.lon).then(address => {
                storeAddress.textContent = address;
            });
    
          
            resultsContainer.appendChild(storeAddress);
        }
    }
    
}

function trovaDouglas(location) {
    const radius = 100000; // Raggio di ricerca in metri
    const geoUrl = "https://nominatim.openstreetmap.org/search?format=geojson&q=" + encodeURIComponent(location);
    fetchData(geoUrl)
        .then(extractCoordinates)
        .then(coordinates => { // quando i dati arrivano se non sono validi -> return null
            if (!coordinates) return;

            const overpassQuery = "[out:json];" +
            "node[\"shop\"=\"perfumery\"][\"brand\"=\"Douglas\"](around:" + radius + "," + coordinates.lat + "," + coordinates.lon + ");" +
            "out;";
        
            const searchUrl = "https://overpass-api.de/api/interpreter?data=" + encodeURIComponent(overpassQuery);

            fetchData(searchUrl).then(displayResults);
        });
}

function leggi(event) {
    const input = event.currentTarget.parentElement.querySelector("input");
    console.log("Cliccato!");
    const location = input.value;
    if (location) {
        trovaDouglas(location);
    }
    console.log("Location:", location);
}

function mostranegozi() {
    if (document.querySelector('.location-mobile')) {
        chiudinegozi();
        return;
    }

    let overlay = document.createElement("div");
    overlay.classList.add("overlay");

    let locationDiv = document.createElement("div");
    locationDiv.classList.add("location-mobile");

    let searchDiv = document.createElement("div");
    searchDiv.classList.add("search-location");

    let navbar = document.createElement("nav");
    navbar.classList.add("navlocation");

    let cerca = document.createElement("button");
    cerca.classList.add("cerca");
    cerca.textContent = "Cerca";

    let input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Cerca negozio nei pressi di...";

    let results = document.createElement("div");
    results.classList.add("results");

    cerca.addEventListener("click", leggi);

    navbar.appendChild(input);
    searchDiv.appendChild(navbar);
    searchDiv.appendChild(cerca);
    locationDiv.appendChild(searchDiv);
    locationDiv.appendChild(results);
    document.body.appendChild(overlay);
    document.body.appendChild(locationDiv);
    document.body.classList.add("body-no-scroll");

    setTimeout(() => {
        locationDiv.classList.add("attivo");
    }, 10);

    setTimeout(() => {
        document.body.addEventListener("click", chiudidalBody);
    }, 10);

    overlay.addEventListener("click", chiudinegozi);
}

function chiudinegozi() {
    const locationDiv = document.querySelector('.location-mobile');
    const overlay = document.querySelector('.overlay');
    if (locationDiv) document.body.removeChild(locationDiv);
    if (overlay) document.body.removeChild(overlay);
    document.body.removeEventListener("click", chiudidalBody);
    document.body.classList.remove("body-no-scroll");
}

const posizione = document.querySelector('#location');
posizione.addEventListener('click', mostranegozi);
