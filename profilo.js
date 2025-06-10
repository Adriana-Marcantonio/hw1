function mostracarta(event) {
    const finestra = document.querySelector('.finestra-card-hidden');
    
    if (finestra) {
        finestra.classList.remove('finestra-card-hidden');
        finestra.classList.add('finestra-card');
         document.body.classList.add("stop");
    }
}
function mostralista(event) {
    const finestra = document.querySelector('.lista-hidden');
    const buoni=document.querySelector('.buoni');
    if (finestra) {
        finestra.classList.remove('lista-hidden');
        finestra.classList.add('lista');
    }
     fetch("lista_buoni.php").then(fetchResponse).then(json=>{
       
        if (json.success) {    
             if(json.buoni_list.length===0){
              const nuovo = document.createElement('p');
              nuovo.textContent="non hai ancora nessun buono";
              buoni.appendChild(nuovo);
             }
             json.buoni_list.forEach(buono => {
                    const Div= document.createElement('div');
                    const nuovoBuono = document.createElement('p');
                    nuovoBuono.textContent=buono.codice;
                    const nuovoCodice= document.createElement('p');
                    nuovoCodice.textContent="€"+ buono.valore;
                    Div.appendChild(nuovoBuono);
                    Div.appendChild(nuovoCodice);
                    buoni.appendChild(Div);
                });
        }
    })

    }




function nascondicarta(event) {
    const finestra = document.querySelector('.finestra-card');
    
    if (finestra) {
        finestra.classList.remove('finestra-card');
        finestra.classList.add('finestra-card-hidden');
         document.body.classList.remove("stop");
    }
}
function nascondiListaBuoni(event) {
    const finestra = document.querySelector('.lista');
    
    if (finestra) {
        finestra.classList.remove('lista');
        finestra.classList.add('lista-hidden');
    }
}


function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function handleFormSubmit(event) {
    event.preventDefault(); // Evita il refresh
    const formData = new FormData(event.target);
    fetch("gestione_buono.php", {
        method: "POST",
        body: formData
    })
    .then(fetchResponse)
    .then(json => {
        const messaggioElement = document.querySelector('.messaggio'); 
        messaggioElement.innerHTML = "";
        const mess = document.createElement('p'); 

        mess.textContent = json.message;
        console.log("Messaggio generato:", json.message);

        if (json.success) {
             document.querySelector('#P').textContent = json.n_punti; 
            }   
        messaggioElement.appendChild(mess); // Aggiunge il nuovo messaggio
    })
    
}



document.querySelector("form").addEventListener('submit', handleFormSubmit);

const cardbutton = document.querySelector(".card-button");

if (cardbutton) {
    cardbutton.addEventListener("click", mostracarta);
}
const exit1=document.getElementById("exit-card");
const exit2= document.getElementById("exit-lista");


if(exit1){
     exit1.addEventListener("click", nascondicarta);
}
if(exit2){
     exit2.addEventListener("click", nascondiListaBuoni);
}


const vedi_lista= document.getElementById("vedi-lista");

if(vedi_lista){
    vedi_lista.addEventListener("click", mostralista);
}