<?php
class Concerto
{
    //attributi della classe
    private $id;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data_;
    private $sala_id;
    private $pezzo_id;

    //metodi get e set
    public function getId(){ return $this -> id; }
    public function getCodice(){ return $this -> codice; }
    public function setCodice($value){ $this -> codice = $value; }

    public function getTitolo(){ return $this -> titolo; }
    public function setTitolo($value){ $this -> titolo = $value; }

    public function getDescrizione(){ return $this -> descrizione; }
    public function setDescrizione($value){ $this -> descrizione = $value; }

    public function getData(){ return $this -> data_; }
    public function setData($value){ $this -> data_ = $value; }

    public function getSalaId(){ return $this -> sala_id; }
    public function setSalaId($value){ $this -> sala_id = $value; }

    public function getPezzoId(){ return $this -> pezzo_id; }
    public function setPezzoId($value){ $this -> pezzo_id = $value; }

    public function delete()
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $id = $this->getId();
        $query = "DELETE FROM concerti WHERE id = :id";
        try {
            $stmt = DbManager::getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            echo "Record eliminato con successo.";
        } catch (PDOException $e) {
            die("Errore nell'eliminazione del record: " . $e->getMessage());
        }
    }

    public function sala(){
        DbManager::initialize("localhost", "concerto", "file.txt");
        $salaId = $this->getSalaId();
        echo "salaId: ". $salaId;
        $query = "SELECT * FROM sale WHERE id = :sala_id";
        try {
            $stmt = DbManager::getPdo()->prepare($query);
            $stmt->bindParam(':sala_id', $salaId, PDO::PARAM_INT);
            $stmt->execute();
            $record = $stmt->fetchObject('Sala');
            if ($record) {
                return $record;
            } else {
                return null; 
            }
        } catch (PDOException $e) {
            die("\nErrore nella ricerca dell'elemento: " . $e->getMessage());        
        }
    }

    public function pezzo(){
        DbManager::initialize("localhost", "concerto", "file.txt");
        $pezzoId = $this->getPezzoId();
        echo "pezzoId: ". $pezzoId;
        $query = "SELECT p.titolo , p.codice FROM (SELECT c.id 
        FROM concerto.concerti c INNER JOIN concerto.concerti_pezzi cp 
        ON c.id = cp.concerto_id) conc INNER JOIN concerto.pezzi p 
        ON conc.id = :pezzo_id";
        try {
            $stmt = DbManager::getPdo()->prepare($query);
            $stmt->bindParam(':pezzo_id', $pezzoId, PDO::PARAM_INT);
            $stmt->execute();
            $record = $stmt->fetchAll(PDO::FETCH_CLASS, "Pezzo");
            if ($record) {
                return $record;
            } else {
                return null; 
            }
        } catch (PDOException $e) {
            die("\nErrore nella ricerca dell'elemento: " . $e->getMessage());        
        }
    }

    public function update($params)
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $id = $this->getId();   
        $query = "UPDATE concerti SET codice = :codice, titolo = :titolo, descrizione = :descrizione, data_ = :data_, sala_id = :sala_id, pezzo_id = :pezzo_id WHERE id = :id";

        try {
            //mi connetto al database
            $stmt = DbManager::getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':codice', $params['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $params['titolo'], PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $params['descrizione'], PDO::PARAM_STR);
            $stmt->bindParam(':data_', $params['data_'], PDO::PARAM_STR);
            $stmt->bindParam(':sala_id', $params['sala_id'], PDO::PARAM_INT);
            $stmt->bindParam(':pezzo_id', $params['pezzo_id'], PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            echo "\nCodice aggiornato con successo.\n";
        } catch (PDOException $e) {
            die("\nErrore nell'aggiornamento del codice: " . $e->getMessage());
        }
    }

    public static function find($id_find)
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $query = "SELECT * FROM concerti WHERE id = :id_find";
        try
        {
            //mi connetto al database
            $stmt = DbManager::getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id_find', $id_find, PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            //ricerco un oggetto di tipo Concerto all'interno della tabella
            $record = $stmt->fetchObject(__CLASS__);
            if ($record) {
                return $record;
            } else {
                return null; //nessun record trovato
            }
        } 
        catch(PDOException $e)
        {
            die("\nErrore nella ricerca dell'elemento: " . $e->getMessage());
        }
    }

    public static function findAll()
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $query = "SELECT * FROM concerti";
        try {
            $conc = new Concerto();
            //Eseguo la query preparata precedentemente, restituisce oggetti della classe Concerto
            $stmt = DbManager::getPdo()->prepare($query);
            $stmt->execute();
            $concerti = $stmt->fetchAll(PDO::FETCH_CLASS, "Concerto");

            if (!empty($concerti)) {
                foreach ($concerti as $conc) {
                    echo "\nDettagli del record:\n";
                    //Visualizzo i record
                    $conc->show();
                }
            } else {
                echo "Nessun record trovato nel database.";
            }
        } catch (PDOException $e) {
            die("Errore nella ricerca di tutti i record: " . $e->getMessage());
        }
    }


    public function show()
    {
        //visualizzazione dettagli record
        echo "--------------------------\n";
        echo "ID: " . $this->getId(). "\n";
        echo "Codice: " . $this->getCodice() . "\n";
        echo "Titolo: " . $this->getTitolo() . "\n";
        echo "Descrizione: " . $this->getDescrizione() . "\n";
        echo "Data: " . $this->getData() . "\n";
        echo "SalaId: " . $this->getSalaId() . "\n";
        echo "PezzoId: " . $this->getPezzoId() . "\n";
        echo "--------------------------\n";
    }


    public static function create($params)
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        //preparo la query SQL per la modifica del record nel database
        $query = "INSERT INTO concerti (codice, titolo, descrizione, data_, sala_id, pezzo_id) VALUES (:codice, :titolo, :descrizione, :data_concerto, :sala_id, :pezzo_id)";
        
        try {
            //associo a ogni colonna il proprio valore
            $stmt = DbManager::getPdo()->prepare($query);
            $stmt->bindParam(':codice', $params['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $params['titolo'], PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $params['descrizione'], PDO::PARAM_STR);
            $stmt->bindParam(':data_concerto', $params['data_'], PDO::PARAM_STR);
            $stmt->bindParam(':sala_id', $params['sala_id'], PDO::PARAM_INT);
            $stmt->bindParam(':pezzo_id', $params['pezzo_id'], PDO::PARAM_INT);

            $stmt->execute();
            echo "\nDati inseriti";
        } catch (PDOException $e) {
            die("\nErrore nell'inserimento dei dati: " . $e->getMessage());
        }
    }
}
?>
