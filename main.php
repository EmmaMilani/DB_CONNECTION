<?php
include "concerto.php";
include "db_manager.php";

function chiediDatiConcerto() {
    
    $codice = null;
    do {
        //chiedo all'utente di inserire il codice
        $input = readline("Inserisci il codice (numero intero): ");
        //controllo se la variabile è un numero e se è un intero
        if (intval($input) == $input) {
            $codice = (int) $input;
        } else {
            echo "Il valore inserito non è un numero intero valido. Riprova.\n";
        }
    } while ($codice === null);
    //chiedo all'utente di inserire il titolo del concerto
    $titolo = readline("Inserisci il titolo del concerto: ");

    do {
        //chiedo all'utente di inserire la data del concerto
        $dataConcerto = readline("Inserisci la data del concerto (formato 'Y-m-d H:i:s'): ");
        $dataConcertoDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataConcerto);
    } while (!$dataConcertoDateTime);
    //chiedo all'utente di inserire la descrizione del concerto
    $descrizione = readline("Inserisci la descrizione del concerto: ");

    $concerto = [
        'codice' => $codice,
        'titolo' => $titolo,
        'descrizione' => $descrizione,
        'data_' => $dataConcerto,
    ];
    //ritorno un array associativo
    return $concerto;
}




while (true) {
    //menù
    echo "\nMenu:\n";
    echo "1. Crea record\n";
    echo "2. Find\n";
    echo "3. Delete\n";
    echo "4. FindAll\n";
    echo "5. Update\n";
    echo "0. Esci\n";
    
    $scelta = readline("Scegli un'opzione: ");
    
    switch ($scelta) {
        case '1':
            //dati da inserire all'interno di un record
            $params = chiediDatiConcerto();
            echo "Creazione record\n";
            //creazione record
            Concerto::create($params);
            break;
        case '2':
            echo "Find\n";
            $id_find = readline("Digita id record che stai cercando: ");
            //ricerca record all'interno della tabella
            $record = Concerto::find($id_find);
            if ($record) {
                echo "Dettagli del record:\n";
                //stampo record
                $record->show();
            } else 
                echo "Nessun record trovato con l'ID $id_find";
            break;
        case '3':
            echo "Elimina\n";
            //ricerca record all'interno della tabella
            $id_canc = readline("Digita id record che vuoi eliminare: ");
            $record = Concerto::find($id_canc);
            if ($record) {
                //elimino il record
                $record->delete();
            } else 
                    echo "Nessun record trovato con l'ID $id_canc";
            break;          
        case '4':
            //stampa di tutti i record presenti all'interno della tabella
            echo "Tutti i records:\n";
            Concerto::findAll();
            break;
        case '5':
            echo "Update\n";
            //ricerca record all'interno della tabella
            $id_find = readline("Digita id record che vuoi modificare: ");
            $record = Concerto::find($id_find);
            if ($record) {
                //chiedo all'utente i dati da modificare
                $params = chiediDatiConcerto();
                //modifica record
                $record->update($params);
            } else 
                echo "Nessun record trovato con l'ID $id_find";
            break;
        case '0':
            //esco dal programma
            echo 'Esci';
            exit;
    }
}


?>