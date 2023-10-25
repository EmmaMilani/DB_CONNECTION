<?php
class DbManager{

    //attributi della classe
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo;

    public function __construct($host, $database, $filename) { 
        $this->host = $host;
        $this->database = $database;
        $this->credentials($filename);
    }

    public function getPdo(){ return $this -> pdo; }

    public function connect() { 
        try {
            //Data Source Name, contiene info necessarie per conettersi al database
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            //imposto attributi gestione errori ed eccezioni
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connessione avvenuta con successo";
        } catch (PDOException $e) {
            die("Errore di connessione al database: " . $e->getMessage());
        }
    }

    private function credentials($filename) { 
        if (file_exists($filename)) {
            //legge il contenuto del file, e restituisce un array in cui ogni elemento corrisponde a una riga del file
            //FILE_IGNORE_NEW_LINES -> omette il ​​ritorno a capo alla fine di ogni elemento dell'array
            //FILE_SKIP_EMPTY_LINES -> omette le righe vuote
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            //verifico se c'è almeno una riga
            if (count($lines) >= 1) {
                //estraggo i primi due valori dalla prima riga, il primo è l'username, il secondo è la password
                list($this->username, $this->password) = preg_split('/\s+/', $lines[0], 2);
            }
        }
    }
}
?>