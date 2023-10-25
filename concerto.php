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


    private function connetti(){
        //creo un'istanza DbManager per connettermi al database
        $conn = new DbManager("localhost", "concerto", 'file.txt');
        //mi connetto al database
        $conn -> connect();
        return $conn;
    }

    public function delete()
    {
        $id = $this->getId();
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

    public function update($params)
    {
        $id = $this->getId();   
        $query = "UPDATE concerti SET codice = :codice, titolo = :titolo, descrizione = :descrizione, data_ = :data_ WHERE id = :id";

        try {
            //mi connetto al database
            $stmt = $this->connetti()->getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':codice', $params['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $params['titolo'], PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $params['descrizione'], PDO::PARAM_STR);
            $stmt->bindParam(':data_', $params['data_'], PDO::PARAM_STR);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            echo "\nCodice aggiornato con successo.";
        } catch (PDOException $e) {
            die("\nErrore nell'aggiornamento del codice: " . $e->getMessage());
        }
    }

    public static function find($id_find)
    {
        //istanza di tipo Concerto per poi connettermi
        $conc = new Concerto();
        $query = "SELECT * FROM concerti WHERE id = :id_find";
        try
        {
            //mi connetto al database
            $stmt = $conc->connetti()->getPdo()->prepare($query);
            //associo i valori
            $stmt->bindParam(':id_find', $id_find, PDO::PARAM_INT);
            //eseguo la query preparata precedentemente
            $stmt->execute();
            //ricerco un oggetto di tipo Concerto all'interno della tabella
            $record = $stmt->fetchObject('Concerto');
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
        $query = "SELECT * FROM concerti";
        try {
            $conc = new Concerto();
            $pdo = $conc->connetti()->getPdo();

            //Eseguo la query preparata precedentemente, restituisce oggetti della classe Concerto
            $stmt = $pdo->prepare($query);
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
        $dateTime = new DateTime($this->getData());
        echo "Data: " . $dateTime->format('Y-m-d H:i:s') . "\n";
        echo "--------------------------\n";
    }


    public static function create($params)
    {
        $conc = new Concerto();
        //preparo la query SQL per la modifica del record nel database
        $query = "INSERT INTO concerti (codice, titolo, descrizione, data_) VALUES (:codice, :titolo, :descrizione, :data_concerto)";
        
        try {
            //associo a ogni colonna il proprio valore
            $stmt = $conc -> connetti() -> getPdo() -> prepare($query);
            $stmt->bindParam(':codice', $params['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $params['titolo'], PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $params['descrizione'], PDO::PARAM_STR);
            $stmt->bindParam(':data_concerto', $params['data_'], PDO::PARAM_STR);

            $stmt->execute();
            echo "\nDati inseriti";
        } catch (PDOException $e) {
            die("\nErrore nell'inserimento dei dati: " . $e->getMessage());
        }
    }
}
?>