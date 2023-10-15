<?php
class Concerto extends PDO //la classe Concerto utilizza anche la classe PDO
{
    //variabili per la connessione al database
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo;

    //private $dsn;

    //attributi della classe
    private $id;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data;
    private $salaId;
    private $orchestraId;

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

    public function getSalaId(){ return $this -> salaId; }
    public function setSalaId($value){ $this -> salaId = $value; }

    public function getOrchestraId(){ return $this -> orchestraId; }
    public function setOrchestraId($value){ $this -> orchestraId = $value; }

    public function credentials(){
        $filename = 'file.txt'; //file contenente le credenziali: user e password
        if (file_exists($filename)) {
            $file = fopen($filename, 'r');//apro il file in sola lettura
        
            while (!feof($file)) {
                $line = fgets($file);//leggo di volta in volta le righe del file
                $words = preg_split('/\s+/', $line);//splitto le righe e inserisco le parole all'interno di un array
        
                if (count($words) >= 2) {
                    $this -> username = $words[0];//la prima parola che trovo è l'username
                    $this -> password = $words[1];//la seconda parola che trovo è la password
                    break;
                }
            }
            fclose($file);//chiudo il file
        }
    }

    public function __construct($host, $database) {
        $this->host = $host;
        $this->database = $database;
    }

    public function connect() {
        //connessione al database
        try {
            //Data Source Name, contiene le informazioni necessarie per stabilire una connessione con il database
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            //connessione al database
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            //imposta l'attributo per la gestione degli errori
            //PDO::ATTR_ERRMODE: attributo relativo alla gestione degli errori
            //PDO::ERRMODE_EXCEPTION: indica che PDO dovrebbe generare eccezioni quando si verificano errori
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Connessione avvenuta con successo';
        } catch (PDOException $e) {
            die("Errore di connessione al database: " . $e->getMessage());
        }
    }


    public static function create($cod_, $tit, $descr, $date, $salaId_, $orchestraId_)
    {
        //creo un'istanza pdo che rappresenta la connesione al database
        $conc = new Concerto("localhost", "concerto");
        $conc -> credentials();
        $conc->connect();

        //set degli attributi
        $conc->setCodice($cod_);
        $conc->setTitolo($tit);
        $conc->setDescrizione($descr);
        $conc->setData($date);
        //$conc->setSalaId($salaId_);
        //$conc->setOrchestraId($orchestraId_);

        //get degli attributi
        $id = $conc->getId(); 
        $codice = $conc->getCodice(); 
        $titolo = $conc->getTitolo();
        $descrizione = $conc->getDescrizione(); 
        $data_ = $conc->getData();
        //$sala_id = $conc->getSalaId(); 
        //$orchestra_id = $conc->getOrchestraId();
        

        //preparo la query SQL per l'inserimento del record nel database
        $query = "INSERT INTO concerti (id, codice, titolo, descrizione, data_) VALUES (:id, :codice, :titolo, :descrizione, :data_)";
        
        try {
            //associo a ogni colonna il proprio valore
            //PDO::PARAM_INT indica il tipo (in questo caso si tratta di un intero)
            $stmt = $conc->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
            $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->bindParam(':data_', $data_, PDO::PARAM_STR);
            //$stmt->bindParam(':sala_id', $sala_id, PDO::PARAM_INT);
            //$stmt->bindParam(':orchestra_id', $orchestra_id, PDO::PARAM_INT);

            $stmt->execute();
            echo ' Dati inseriti';
        } catch (PDOException $e) {
            die("Errore nell'inserimento dei dati: " . $e->getMessage());
        }
    }
}

$cod = 123;
$titolo_ = "Prova per concerto";
$des = "Vediamo se funziona";
$data_ = "16/10/2023";
$salId = 1;
$orcId = 1;

//richiamo metodo statico per creare un record da inserire all'interno del database
Concerto::create($cod, $titolo_, $des, $data_, $salId, $orcId);

?>