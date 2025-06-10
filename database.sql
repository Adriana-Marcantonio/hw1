Create DATABASE hmw;
USE hmw:

CREATE TABLE if not exists prodotto(
    id integer primary key auto_increment,
    nome varchar(255) not null,
    descrizione text not null,
    prezzo decimal(10, 2) not null,
    quantita_prodotto varchar(255) not null,
    pezzi integer not null default 0,
    categoria varchar(255) not null,
    immagine TEXT NOT null,
    tipo CHAR(1) NOT NULL,
    marca varchar(255) not null
) Engine = INNODB;

CREATE TABLE if not exists users (
    id integer primary key auto_increment,
    username VARCHAR(255) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    name varchar(255) not null,
    surname varchar(255) not null,
    genere char(1) not NULL,
    carta CHAR(12) NOT NULL unique
) Engine = INNODB;

CREATE TABLE if NOT EXISTS punti(
    id integer primary key auto_increment,
    id_utente integer NOT null,
    FOREIGN KEY(id_utente) REFERENCES USERs(id),
    n_punti INTEGER NOT NULL DEFAULT 0
   );

   
CREATE TABLE if NOT EXISTS buoni(
id INTEGER PRIMARY KEY AUTO_INCREMENT,
id_utente integer NOT NULL,
DATA_creazione DATE,
FOREIGN KEY(id_utente) REFERENCES USERs(id),
valore INTEGER  DEFAULT NULL,
codice CHAR(6)  DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS preferiti(
    id_utente INTEGER NOT NULL,
    id_prodotto INTEGER NOT NULL,
    sourceTable VARCHAR(255) NOT NULL, 
    PRIMARY KEY(id_prodotto, id_utente,sourceTable), 
    FOREIGN KEY(id_utente) REFERENCES users(id)
);

CREATE TABLE if not exists carrello (
    id_utente integer not null,
    id_prodotto integer not null,
    sourceTable VARCHAR(255) NOT NULL, 
   PRIMARY KEY(id_prodotto, id_utente, sourceTable), 
    foreign key (id_utente) references users(id),
    pezzi integer not null default 1
);

CREATE TABLE if NOT EXISTS prodotti_personalizzati(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,  
    id_utente INTEGER NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES users(id),
    nome VARCHAR(255) NOT NULL,
    descrizione TEXT NOT NULL,
    prezzo DECIMAL(10, 2) NOT NULL,
    quantita_prodotto VARCHAR(255) NOT NULL,
    pezzi INTEGER NOT NULL DEFAULT 0,
    categoria VARCHAR(255) NOT NULL,
    tipo CHAR(1),
    immagine TEXT NOT NULL unique,
    marca VARCHAR(255) NOT NULL
) ;
ALTER TABLE prodotti_personalizzati AUTO_INCREMENT = 300;

CREATE TABLE if NOT EXISTS newsletter(
id INTEGER PRIMARY KEY AUTO_INCREMENT,  
id_utente INTEGER NOT NULL,
email varchar(255) not null UNIQUE,
FOREIGN KEY (id_utente) REFERENCES users(id)
);


DELIMITER //
CREATE TRIGGER inserisci_punti
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO punti (id_utente) VALUES (NEW.id);
END;
//

DELIMITER ;

                        