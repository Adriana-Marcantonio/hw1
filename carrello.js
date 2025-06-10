
function handleResponse(response) {
    if (!response.ok) {
        console.error('Errore HTTP:', response.status, response.statusText);
    }
    return response.json();
}

function verifica_dati(event) {
    const numeroCartaInput = document.querySelector('#numeroCarta');
    const verifica = document.querySelector(".verifica");
    const messaggioPagamento = document.querySelector("#messaggioPagamento");
    const codiceSicurezzaInput = document.querySelector('#codiceSicurezza');
    const paeseInput = document.querySelector('#paese');
    const viaInput = document.querySelector('#via');
    const cittaInput = document.querySelector('#citta');
    const capInput = document.querySelector('#cap');

    const numeroCarta = numeroCartaInput.value.trim();
    const codiceSicurezza = codiceSicurezzaInput.value.trim();
    const paese = paeseInput.value.trim();
    const via = viaInput.value.trim();
    const citta = cittaInput.value.trim();
    const cap = capInput.value.trim();

    if (!numeroCarta || !codiceSicurezza || !paese || !via || !citta || !cap) {
        if (messaggioPagamento) {
            messaggioPagamento.textContent = "Si prega di compilare tutti i campi.";
        }
        return;
    }

    if (numeroCarta.length !== 16) {
        if (messaggioPagamento) {
            messaggioPagamento.textContent = "Il numero della carta di credito non è valido (lunghezza errata).";
        }
        return;
    }

    if (codiceSicurezza.length !== 3) {
        if (messaggioPagamento) {
            messaggioPagamento.textContent = "Il codice di sicurezza (CVV/CVC) non è valido (lunghezza errata).";
        }
        return;
    }

    if (!/^\d{5}$/.test(cap)) {
        if (messaggioPagamento) {
            messaggioPagamento.textContent = "Il CAP deve contenere solo 5 cifre numeriche.";
        }
        return;
    }

    if (verifica) {
        verifica.removeEventListener("click", verifica_dati);
        verifica.classList.remove("verifica");
        verifica.classList.add("ordina");
        verifica.addEventListener("click", svuotaCarrello);
    }
}

function rimuovifinestra(event) {
    const finestra = document.querySelector('.pagamento');
    const paga = document.querySelector("#al-pagamento");
    
    if (finestra) {
        finestra.classList.remove('pagamento');
        finestra.classList.add('pagamento-hidden');
    }

    if (paga) {
        paga.removeEventListener("click", rimuovifinestra);
        paga.addEventListener("click", pagamento);
    }
}

function pagamento(event) {
    const finestra = document.querySelector('.pagamento-hidden');
    const paga = document.querySelector("#al-pagamento");
    const verifica = document.querySelector(".verifica");

    if (finestra) {
        finestra.classList.add('pagamento');
        finestra.classList.remove('pagamento-hidden');
    }

    if (paga) {
        paga.removeEventListener("click", pagamento);
        paga.addEventListener("click", rimuovifinestra);
    }

    if (verifica) {
        verifica.addEventListener("click", verifica_dati);
    }
}

const paga = document.querySelector("#al-pagamento");
if (paga) {
    paga.addEventListener("click", pagamento);
}



function noResultsCarrello() {
    if (catalogoContainer) {
        catalogoContainer.innerHTML = '';
        const vuoto = document.createElement('div');
        vuoto.classList.add('vuoto');
        vuoto.textContent = "Nessun prodotto nel carrello.";
        catalogoContainer.appendChild(vuoto);
    }

    if (acquistoBox) {
        acquistoBox.classList.add('hidden');
    }
    if (acquistoContainer) {
        acquistoContainer.innerHTML = ''; 
    }
    if (costoTotaleElement) {
        costoTotaleElement.textContent = '€0.00'; // Resetta il costo totale
    }
    numelementicarrello = 0; // Resetta il contatore globale
      scontoApplicato = 0;
      if (inputCodiceSconto) {
        inputCodiceSconto.value = ''; 
    }
       calcolaCostoTotale(); 
}


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
    iconPreferiti.dataset.sourceTable = p.sourceTable 
    if (p.isFavorited) {
        iconPreferiti.src = 'favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png';
        iconPreferiti.addEventListener('click', rimuoviPreferito);
    } else {
        iconPreferiti.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png';
        iconPreferiti.addEventListener('click', aggiungiPreferiti);
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
    prezzoProdotto.classList.add('prezzo-prodotto-valore'); 
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
   const inputNumero = document.createElement('input');
   inputNumero.type = "number";
   inputNumero.min = "1";
   inputNumero.max = p.pezzi;
   inputNumero.step = "1";
   inputNumero.value = p.quantita;
   inputNumero.dataset.idProdotto = p.id;
   inputNumero.dataset.sourceTable = p.sourceTable 
   inputNumero.classList.add('pezzi');
   inputNumero.addEventListener('change', gestionequantita);
   prodottoDiv.appendChild(inputNumero);
   const bottoneCarrello = document.createElement('button');
  bottoneCarrello.dataset.idProdotto = p.id;
    bottoneCarrello.dataset.sourceTable = p.sourceTable 
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


function rimuoviPreferito(event) {
    const icon = event.currentTarget;
    const Idprodotto = icon.dataset.idProdotto;
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
                icon.src = 'favorite_24dp_1F1F1F_FILL0_wght100_GRAD-25_opsz24.png';
                icon.removeEventListener('click', rimuoviPreferito);
                icon.addEventListener('click', aggiungiPreferiti);
            } else {
                console.error('Errore nella rimozione del preferito:', data.message);
            }
        });
}

function aggiungiPreferiti(event) {
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
                icon.src = 'favorite_24dp_1F1F1F_FILL1_wght100_GRAD-25_opsz24.png';
                icon.removeEventListener('click', aggiungiPreferiti);
                icon.addEventListener('click', rimuoviPreferito);
            } else {
                console.error('Errore aggiunta ai preferiti:', data.message);
            }
        });
    }

let numelementicarrello = 0;
let scontoApplicato = 0; 
let codiceScontoAttuale = null; 
function aggiungiAlCarrello(event) {
    const element = event.currentTarget;
    const Idprodotto = element.dataset.idProdotto;
     const sourceTable = element.dataset.sourceTable; 
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
                element.removeEventListener('click', aggiungiAlCarrello);
                element.classList.remove("aggiungi-al-carrello");
                element.classList.add("rimuovi-dal-carrello");
                element.addEventListener('click', rimuoviDalCarrello);
                element.textContent = "- rimuovi dal carrello";
                caricaCarrello();
            } else {
                console.error('Errore nell\' aggiunta al carrello:', data.message);
            }
        });
    }

function rimuoviDalCarrello(event) {
    const element = event.currentTarget;
    const Idprodotto = element.dataset.idProdotto;
     const sourceTable = element.dataset.sourceTable; 
    const formData = new FormData();
    formData.append('Idprodotto', Idprodotto);
    formData.append('action', 'remove2');
      formData.append('sourceTable', sourceTable);

    fetch('gestione_preferiti_carrello.php', {
            method: 'POST',
            body: formData
        })
        .then(handleResponse)
        .then(data => {
            if (data.success) {
                caricaCarrello();
            } else {
                console.error('Errore nella rimozione dal carrello:', data.message);
            }
        });
}

function gestionequantita(event){
     const inputQuantitaModificato = event.currentTarget;
  const Idprodotto = inputQuantitaModificato.dataset.idProdotto;
   const sourceTable = inputQuantitaModificato.dataset.sourceTable; 
 const nuovaQuantita = parseInt(inputQuantitaModificato.value) || 1;
    const formData = new FormData();
    formData.append('action', 'modifica_quantita'); 
    formData.append('Idprodotto', Idprodotto);
    formData.append('new_quantity', nuovaQuantita); 
      formData.append('sourceTable', sourceTable);
   fetch('gestione_preferiti_carrello.php', { 
        method: 'POST',
        body: formData
    })
    .then(handleResponse) 
    .then(data => {
        if (data.success) {
            caricaCarrello(); 
        } else {
            console.error('Errore durante l\'aggiornamento della quantità:', data.message);
           
        }
    });

    calcolaCostoTotale();
}

function calcolaCostoTotale() {
    let costoTotale = 0;
    const prodottiVisualizzati = catalogoContainer.querySelectorAll('.prodotto');

    for (let i = 0; i < prodottiVisualizzati.length; i++) {
        const prodottoDiv = prodottiVisualizzati[i];
        const inputQuantita = prodottoDiv.querySelector('.pezzi');
        const prezzoElement = prodottoDiv.querySelector('.prezzo-prodotto-valore');

        if (inputQuantita && prezzoElement) {
            const quantita = parseInt(inputQuantita.value) || 1;
            const prezzoTesto = prezzoElement.textContent;
            const prezzoPulito = parseFloat(prezzoTesto.replace('€', '').replace(',', '.'));
            costoTotale += prezzoPulito * quantita;
        }

    }
     costoTotale -= scontoApplicato; 
    if (costoTotale < 0) costoTotale = 0;
    costoTotale += 5;

    if (costoTotaleElement) {
        costoTotaleElement.textContent = '€' + costoTotale.toFixed(2);
    }

    return costoTotale;
}



function displayCarrello(json) {
    catalogoContainer.innerHTML = '';
    acquistoContainer.innerHTML = '';

    if (!json.prodotti_carrello || json.prodotti_carrello.length === 0) {
        noResultsCarrello();
        return;
    }

    acquistoBox.classList.remove('hidden');
    numelementicarrello = 0;

    json.prodotti_carrello.forEach(prodotto => {
        const prodottoElemento = creaProdottoHTML(prodotto);
        catalogoContainer.appendChild(prodottoElemento);
        numelementicarrello++;

        const acquistoItem = document.createElement('div');
        const nome = document.createElement('p');
        const prezzo = document.createElement('p');
        const inputQuantitaCorrente = prodottoElemento.querySelector('.pezzi');
       let quantita=1;
        if (inputQuantitaCorrente) {
        
          quantita = parseInt(inputQuantitaCorrente.value) || 1; // Accedi a .value
        
        }
        console.log(quantita);
        nome.textContent = prodotto.nome;
        if(quantita>1){
            prezzo.textContent='€' + prodotto.prezzo +"x"+ quantita;
        }else 
        prezzo.textContent = '€' + prodotto.prezzo;

        acquistoItem.appendChild(nome);
        acquistoItem.appendChild(prezzo);
        acquistoContainer.appendChild(acquistoItem);
    }); 

    calcolaCostoTotale();
}


function caricaCarrello() {
       scontoApplicato = 0;  //resetto e ricalcolo
    fetch('ottieni_carrello.php')
        .then(handleResponse)
        .then(displayCarrello) ;
}


    //efettua ordine
function svuotaCarrello() {
    const TotPunti = parseInt(calcolaCostoTotale()); 
    const formData = new FormData();
    formData.append('action', 'clear'); 
    formData.append('Npunti',TotPunti)
     if (codiceScontoAttuale) {
        formData.append('codice_sconto_applicato', codiceScontoAttuale);
    }
    fetch('gestione_preferiti_carrello.php', {
        method: 'POST',
        body: formData
    })
    .then(handleResponse) 
    .then(data => {
        if (data.success) {
    
            const Div=document.createElement('div');
            const messaggioSuccesso = document.createElement('p');
            Div.classList.add('acquisto-avvenuto');
            messaggioSuccesso.textContent = "Acquisto avvenuto con successo!";
            Div.appendChild(messaggioSuccesso);
            catalogoContainer.appendChild(Div); 
              const finestraPagamento = document.querySelector('.pagamento');
                if (finestraPagamento) {
                    finestraPagamento.classList.remove('pagamento');
                    finestraPagamento.classList.add('pagamento-hidden');
                }
                setTimeout(() => {
                caricaCarrello(); 
                Div.remove(); 
            }, 1500);
        } else {
            console.error('Errore nello svuotamento del carrello:', data.message);
            const messaggioErrore = document.createElement('p');
            messaggioErrore.classList.add('messaggio', 'errore'); 
            messaggioErrore.textContent = "Errore durante l'acquisto: " + data.message;
            catalogoContainer.appendChild(messaggioErrore);
        }
    });
}
function applicaCodiceSconto(event) {
        event.preventDefault(); 
    const codice = inputCodiceSconto.value.trim(); // rimuove spazi
    const formData = new FormData();
    formData.append('action', 'applica_sconto');
    formData.append('codice', codice);

    fetch('gestione_preferiti_carrello.php', {
        method: 'POST',
        body: formData
    })
    .then(handleResponse)
    .then(data => {
        if (data.success) {
            scontoApplicato = parseFloat(data.valore_sconto); // Aggiorna lo sconto applicato
             codiceScontoAttuale = codice; 
            calcolaCostoTotale(); // Ricalcola il totale
             const nuovo=document.createElement('div');
             const p1=document.createElement('p');
             p1.textContent="codice sconto";
            const p2=document.createElement('p');
             p2.textContent="-€"+ scontoApplicato.toFixed(2);
             acquistoContainer.appendChild(nuovo);
             nuovo.appendChild(p1);
             nuovo.appendChild(p2);
          //  inputCodiceSconto.value = codice; 
        } else {
            console.error("sconto non applicato");
            scontoApplicato = 0; 
            codiceScontoAttuale = null; 
            calcolaCostoTotale(); 
            inputCodiceSconto.value = ''; 
        }
    });
}

 
const catalogoContainer = document.querySelector('.catalogo');
const acquistoContainer = document.querySelector('.prodotti-da-acquistare');
const costoTotaleElement = document.querySelector('.costo-totale .tot');
const acquistoBox = document.querySelector('.acquisto');
const inputCodiceSconto = document.querySelector('#codice_sconto_input'); 
const applicaScontoBtn = document.querySelector('#applica_buono'); 
const ordina=document.querySelector('.ordina');
if(ordina){
ordina.addEventListener('click',svuotaCarrello);
}
if (applicaScontoBtn) {
    applicaScontoBtn.addEventListener('click', applicaCodiceSconto);
}

caricaCarrello();

