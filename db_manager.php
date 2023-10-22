<?php
class DbManager{
    //variabili per la connessione al database
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo;

    public function __construct($host, $database) { 
        $this->host = $host;
        $this->database = $database;
    }

    public function getPdo(){ return $this -> pdo; }

    public function connect() { 
        //connessione al database
        try {
            //Data Source Name, contiene le informazioni necessarie per stabilire una connessione con il database
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            //connessione al database
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connessione avvenuta con successo";
        } catch (PDOException $e) {
            die("Errore di connessione al database: " . $e->getMessage());
        }
    }

    public function credentials($filename){ 
        //file contenente le credenziali: user e password
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
}
?>