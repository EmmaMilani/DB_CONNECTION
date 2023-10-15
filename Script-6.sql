create database concerto;
create table concerto.autori(
id int not null auto_increment primary key,
codice varchar(255),
nome varchar(255)
);
create table concerto.pezzi(
id int not null auto_increment primary key,
titolo varchar(255),
codice varchar(255)
);
create table concerto.orchestre(
id int not null auto_increment primary key,
direttore varchar(255),
nome varchar(255)
);
create table concerto.orchestrali(
id int not null auto_increment primary key,
matricola varchar(255),
nome varchar(255),
cognome varchar(255)
);
create table concerto.strumenti(
id int not null auto_increment primary key,
nome varchar(255),
descrizione varchar(255)
);
create table concerto.sale(
id int not null auto_increment primary key,
codice varchar(255),
nome varchar(255),
capienza int
);
create table concerto.concerti(
id int not null auto_increment primary key,
codice varchar(255),
titolo varchar(255),
descrizione varchar(255),
data_ datetime,
sala_id int,
orchestra_id int, 
FOREIGN KEY (sala_id) REFERENCES sale(id),
FOREIGN KEY (orchestra_id) REFERENCES orchestre(id)
);
create table concerto.concerti_pezzi(
concerto_id int,
pezzo_id int,
FOREIGN KEY (concerto_id) REFERENCES concerti(id),
FOREIGN KEY (pezzo_id) REFERENCES pezzi(id),
primary key (concerto_id,pezzo_id)
);
create table concerto.autori_pezzi(
autore_id int,
pezzo_id int,
FOREIGN KEY (autore_id) REFERENCES autori(id),
FOREIGN KEY (pezzo_id) REFERENCES pezzi(id),
primary key (autore_id,pezzo_id)
);
create table concerto.orchestre_archestrali(
orchestra_id int,
orchestrale_id int,
FOREIGN KEY (orchestra_id) REFERENCES orchestre(id),
FOREIGN KEY (orchestrale_id) REFERENCES orchestrali(id),
primary key (orchestra_id,orchestrale_id)
);
create table concerto.orchestrali_strumenti(
orchestrale_id int,
strumento_id int,
FOREIGN KEY (orchestrale_id) REFERENCES orchestrali(id),
FOREIGN KEY (strumento_id) REFERENCES strumenti(id),
primary key (orchestrale_id,strumento_id)
);

