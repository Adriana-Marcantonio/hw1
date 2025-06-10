function creaProdottoHTML(p) {
    const prodottoDiv = document.createElement('div');
    prodottoDiv.classList.add('prodotto');

    const imgContainer = document.createElement('div');
    imgContainer.classList.add('img-container');
    const imgProdotto = document.createElement('img');
    imgProdotto.src = p.immagine;
    imgProdotto.alt = p.nome;
    imgContainer.appendChild(imgProdotto);
    const iconPreferiti = document.createElement('img');
    iconPreferiti.classList.add('icon');
    iconPreferiti.alt = 'Preferiti';
    iconPreferiti.dataset.idProdotto = p.id; 
    iconPreferiti.dataset.sourceTable = p.sourceTable; 
    if (p.isFavorited) { 
        iconPreferiti.src = 'favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png'; 
        iconPreferiti.addEventListener('click', rimuoviPreferito);
    } else {
        iconPreferiti.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png';
        iconPreferiti.addEventListener('click', aggiungiPreferito); 
    }

    imgContainer.appendChild(iconPreferiti);
    prodottoDiv.appendChild(imgContainer);

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
    prezzoProdotto.textContent = '€' + p.prezzo; 
    descrizioneProdotto.appendChild(prezzoProdotto);
    const quantitaProdotto = document.createElement('p');
    quantitaProdotto.classList.add('light-font');
    quantitaProdotto.textContent = p.quantita_prodotto;
    descrizioneProdotto.appendChild(quantitaProdotto);
    prodottoDiv.appendChild(descrizioneProdotto);

    const offertaDiv = document.createElement('div');
    offertaDiv.classList.add('offerta');
    const sorpresaDiv = document.createElement('div');
    sorpresaDiv.textContent = ' + SORPRESA ';
    offertaDiv.appendChild(sorpresaDiv);
    const bottoneCarrello = document.createElement('button');
    bottoneCarrello.dataset.idProdotto = p.id;
     bottoneCarrello.dataset.sourceTable = p.sourceTable;
    if (p.isInCarrello) {
        bottoneCarrello.classList.add('rimuovi-dal-carrello');
        bottoneCarrello.textContent = '- rimuovi dal carrello';
        bottoneCarrello.addEventListener('click', rimuoviDalCarrello);
    } else {
        bottoneCarrello.classList.add('aggiungi-al-carrello');
        bottoneCarrello.textContent = '+ aggiungi al carrello';
        bottoneCarrello.addEventListener('click', aggiungiAlCarrello);
    }
    offertaDiv.appendChild(bottoneCarrello);
    prodottoDiv.appendChild(offertaDiv);
    return prodottoDiv;
}


function noResults() {
    const container = document.querySelector('.catalogo');
    container.innerHTML = ''; 
    const vuoto = document.createElement('div');
    vuoto.classList.add("vuoto");
    vuoto.textContent = "Nessun prodotto nei preferiti.";
    container.appendChild(vuoto);
}


function handleResponse(response) {
    if (!response.ok) {
        console.error('Errore HTTP:', response.status, response.statusText);
        throw new Error('Errore di rete o server: ' + response.statusText);
    }
    return response.json(); 
}


function displayPreferiti(json) {
    const container = document.querySelector('.catalogo');
    container.innerHTML = '';
    if (!json.preferiti || json.preferiti.length === 0) {
        noResults();
        return;
    }
    json.preferiti.forEach(prodotto => {
        const prodottoElemento = creaProdottoHTML(prodotto);
        container.appendChild(prodottoElemento);
    });
}

function caricaPreferiti() {
    fetch('ottieni_preferiti.php') 
        .then(handleResponse) 
        .then(displayPreferiti);
}


function aggiungiPreferito(event) {
    const icon = event.currentTarget;
    const Idprodotto = icon.dataset.idProdotto;
    const sourceTable = icon.dataset.sourceTable; 
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto); 
    formData.append('action', 'add');
          formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', { 
        method: 'POST',
        body: formData
    })
    .then(handleResponse) 
    .then(data => {
        if (data.success) {
            icon.src = 'favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png'; // Icona piena (preferito)
            icon.removeEventListener('click', aggiungiPreferito);
            icon.addEventListener('click', rimuoviPreferito); // Aggiungi listener per rimuovere
        } else {
            console.error('Errore durante l\'aggiunta ai preferiti:', data.message);
        }
    });
}

function rimuoviPreferito(event) {
    const icon = event.currentTarget;
    const Idprodotto = icon.dataset.idProdotto;
    const prodottoElemento = icon.closest('.prodotto'); 
       const sourceTable = icon.dataset.sourceTable; 
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'remove'); 
       formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', { 
        method: 'POST',
        body: formData
    })
    .then(handleResponse)
    .then(data => {
        if (data.success) {
            if (prodottoElemento) {
                 icon.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png'; // Icona vuota
                 icon.removeEventListener('click', rimuoviPreferito);
                 icon.addEventListener('click', aggiungiPreferito); // Aggiungi listener per rimuovere
            }
            const catalogo = document.querySelector('.catalogo');
            if (catalogo && catalogo.children.length === 0) {
                noResults();
            }
        } else {
            console.error('Errore durante la rimozione dai preferiti:', data.message);
        }
    });
}

function aggiungiAlCarrello(event) {
    const button = event.currentTarget;
    const Idprodotto = button.dataset.idProdotto;
    const sourceTable = button.dataset.sourceTable; 
    const formData = new FormData();
     formData.append('Idprodotto', Idprodotto); 
    formData.append('action', 'add2'); 
    formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', { 
        method: 'POST',
        body: formData
    })
    .then(handleResponse)
    .then(data => {
        if (data.success) {
            button.removeEventListener('click', aggiungiAlCarrello);
            button.classList.remove('aggiungi-al-carrello');
            button.classList.add('rimuovi-dal-carrello');
            button.textContent = '- rimuovi dal carrello';
            button.addEventListener('click', rimuoviDalCarrello);
        } else {
            console.error('Errore durante l\'aggiunta al carrello:', data.message);
        }
    });
}

function rimuoviDalCarrello(event) {
    const button = event.currentTarget;
    const Idprodotto = button.dataset.idProdotto;
    const sourceTable = button.dataset.sourceTable; 
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'remove2'); // Azione per il PHP
    formData.append('sourceTable', sourceTable);
    fetch('gestione_preferiti_carrello.php', { // Assicurati che questo sia l'endpoint corretto per la gestione carrello
        method: 'POST',
        body: formData
    })
    .then(handleResponse) 
    .then(data => {
        if (data.success) {
            button.removeEventListener('click', rimuoviDalCarrello);
            button.classList.remove('rimuovi-dal-carrello');
            button.classList.add('aggiungi-al-carrello');
            button.textContent = '+ aggiungi al carrello';
            button.addEventListener('click', aggiungiAlCarrello);
        } else {
            console.error('Errore durante la rimozione dal carrello:', data.message);
        }
    });
}

 caricaPreferiti();