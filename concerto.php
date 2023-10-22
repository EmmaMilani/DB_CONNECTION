<?php
class Concerto
{
    //attributi della classe
    private $id;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data;

    //metodi get e set
    public function getId(){ return $this -> id; }
    public function getCodice(){ return $this -> codice; }
    public function setCodice($value){ $this -> codice = $value; }

    public function getTitolo(){ return $this -> titolo; }
    public function setTitolo($value){ $this -> titolo = $value; }

    public function getDescrizione(){ return $this -> descrizione; }
    public function setDescrizione($value){ $this -> descrizione = $value; }

    public function getData(){ return $this -> data; }
    public function setData($value){ $this -> data = $value; }

    //fine metodi get e set

    private function connetti(){
        //creo un'istanza DbManager per connettermi al database
        $conn = new DbManager("localhost", "concerto");
        //estraggo le credenziali dal file per poter accedere al database
        $conn -> credentials('file.txt');
        //mi connetto al database
        $conn -> connect();
        return $conn;
    }

    public function delete($id)
    {
        $query = "DELETE FROM concerti WHERE id = :id";
        try {
            //mi connetto al database
            $stmt = $this->connetti()->getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            echo "Record eliminato con successo.";
        } catch (PDOException $e) {
            die("Errore nell'eliminazione del record: " . $e->getMessage());
        }
    }

    public function update($params, $id)
    {
        $query = "UPDATE concerti SET codice = :codice, titolo = :titolo, descrizione = :descrizione, data_ = :data_ WHERE id = :id";
        $data_concerto = $params['data_'];
        //formatto la data
        $data_formattata = $data_concerto->format('Y-m-d H:i:s');

        try {
            //mi connetto al database
            $stmt = $this->connetti()->getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':codice', $params['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $params['titolo'], PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $params['descrizione'], PDO::PARAM_STR);
            $stmt->bindParam(':data_', $data_formattata, PDO::PARAM_STR);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            echo "Codice aggiornato con successo.";
        } catch (PDOException $e) {
            die("Errore nell'aggiornamento del codice: " . $e->getMessage());
        }
    }

    public static function find($id_find)
    {
        //istanza di tipo Concerto
        $conc = new Concerto();
        $query = "SELECT * FROM concerti WHERE id = :id_find";
        try
        {
            //mi connetto al database
            $stmt = $conc -> connetti() -> getPdo() -> prepare($query);
            //associo i valori
            $stmt->bindParam(':id_find', $id_find, PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            //ricerco un oggetto all'interno della tabella
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($record) {
                return $record;
            } else {
                return null; //nessun record trovato
            }
        } 
        catch(PDOException $e)
        {
            die("Errore nella ricerca dell'elemento: " . $e->getMessage());
        }
    }


    public static function findAll()
    {
        $conc = new Concerto();
        $query = "SELECT * FROM concerti";

        try {
            //mi connetto al database
            $stmt = $conc -> connetti() -> getPdo() -> prepare($query);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            //ricerco tutti i record all'interno della tabella con fetchAll
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($records)) {
                foreach ($records as $record) {
                    $conc = new Concerto();
                    $id = $record['id'];
                    $conc->setCodice($record['codice']);
                    $conc->setTitolo($record['titolo']);
                    $conc->setDescrizione($record['descrizione']);
                    $conc->setData($record['data_']);

                    echo "Dettagli del record:\n";
                    //visualizzo i record
                    $conc->show($id);
                }
            } else {
                echo "Nessun record trovato nel database.";
            }
        } catch (PDOException $e) {
            die("Errore nella ricerca di tutti i record: " . $e->getMessage());
        }
    }

    public function show($id_find)
    {
        //visualizzazione dettagli record
        echo "--------------------------\n";
        echo "ID: " . $id_find . "\n";
        echo "Codice: " . $this->getCodice() . "\n";
        echo "Titolo: " . $this->getTitolo() . "\n";
        echo "Descrizione: " . $this->getDescrizione() . "\n";
        echo "Data: " . $this->getData() . "\n";
        echo "--------------------------\n";
    }


    public static function create($params)
    {
        $conc = new Concerto();

        //set degli attributi

        $conc->setCodice($params['codice']);
        $conc->setTitolo($params['titolo']);
        $conc->setDescrizione($params['descrizione']);
        $conc->setData($params['data_']);

        //get degli attributi
        $codice = $conc->getCodice(); 
        $titolo = $conc->getTitolo();
        $descrizione = $conc->getDescrizione(); 
        $data_concerto = $conc->getData();
        //formatto la data
        $data_formattata = $data_concerto->format('Y-m-d H:i:s');
        
        //preparo la query SQL per l'inserimento del record nel database
        $query = "INSERT INTO concerti (codice, titolo, descrizione, data_) VALUES (:codice, :titolo, :descrizione, :data_concerto)";
        
        try {
            //associo a ogni colonna il proprio valore
            $stmt = $conc -> connetti() -> getPdo() -> prepare($query);
            $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->bindParam(':data_concerto', $data_formattata, PDO::PARAM_STR);

            $stmt->execute();
            echo "\nDati inseriti";
        } catch (PDOException $e) {
            die("Errore nell'inserimento dei dati: " . $e->getMessage());
        }
    }
}






$codice_concerto = 123;
$titolo_concerto = "Prova per concerto";
$descr_concerto = "Vediamo se funziona";
$data_concerto = new DateTime('2023-10-16 14:30:00'); 

//preparo array con i valori da caricare all'interno della tabella concerti
$params = array("codice" => $codice_concerto, "titolo" =>$titolo_concerto, "descrizione" => $descr_concerto, "data_" => $data_concerto);

//menu opzioni modifica tabella
while (true) {
    echo "Menu:\n";
    echo "1. Crea record\n";
    echo "2. Find\n";
    echo "3. Delete\n";
    echo "4. FindAll\n";
    echo "5. Update\n";
    echo "0. Esci\n";
    
    $scelta = readline("Scegli un'opzione: ");
    
    switch ($scelta) {
        case '1':
            echo "Creazione record\n";
            //creazione record tramite metodo statico create
            Concerto::create($params);
            break;
        case '2':
            echo "Find\n";
            $id_find = readline("Digita id record che stai cercando: ");
            //ricerca record all'interno della tabella
            $record = Concerto::find($id_find);

            if ($record) {
                $conc = new Concerto();
                $conc->setCodice($record['codice']);
                $conc->setTitolo($record['titolo']);
                $conc->setDescrizione($record['descrizione']);
                $conc->setData($record['data_']);

                echo "Dettagli del record:\n";
                //visualizzazione record
                $conc->show($id_find);
            } else {
                echo "Nessun record trovato con l'ID $id_find";
            }
            break;
        case '3':
            echo "Elimina\n";
            $id_canc = readline("Digita id record che vuoi eliminare: ");
            //ricerca record all'interno della tabella
            $record = Concerto::find($id_canc);
                
            if ($record) {
                $conc = new Concerto();
                $id = $record['id'];
                $conc->setCodice($record['codice']);
                $conc->setTitolo($record['titolo']);
                $conc->setDescrizione($record['descrizione']);
                $conc->setData($record['data_']);
                //eliminazione record
                $conc->delete($id);
            } else {
                    echo "Nessun record trovato con l'ID $id_canc";
            }
            break;          
        case '4':
            echo "Tutti i records:\n";
            //ricerto tutti i record all'interno della tabella
            Concerto::findAll();
            break;
        case '5':
            echo "Update\n";
            $id_find = readline("Digita id record che stai cercando: ");
            $record = Concerto::find($id_find);
            if ($record) {
                $conc = new Concerto();
                $codice_concerto = 555;
                $titolo_concerto = "Update";
                $descr_concerto = "Vediamo se funziona";
                $data_concerto = new DateTime('2017-10-16 22:45:00'); 

                $params = array("codice" => 555, "titolo" =>$titolo_concerto, "descrizione" => $descr_concerto, "data_" => $data_concerto);
                //modifica valori di un dato record
                $conc->update($params, $id_find);
            } else {
                echo "Nessun record trovato con l'ID $id_find";
            }
            break;
        case '0':
            echo 'Esci';
            exit;
    }
}

?>
