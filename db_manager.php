<?php
class DbManager {

    private static $host;
    private static $username;
    private static $password;
    private static $database;
    private static $pdo;

    public static function initialize($host, $database, $filename) {
        self::$host = $host;
        self::$database = $database;
        self::credentials($filename);
        self::connect();
    }

    public static function getPdo() {
        return self::$pdo;
    }

    private static function connect() {
        try {
            //Data Source Name, contiene info necessarie per conettersi al database
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$database;
            self::$pdo = new PDO($dsn, self::$username, self::$password);
            //imposto attributi gestione errori ed eccezioni
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connessione avvenuta con successo";
        } catch (PDOException $e) {
            die("Errore di connessione al database: " . $e->getMessage());
        }
    }

    private static function credentials($filename) {
        //legge il contenuto del file, e restituisce un array in cui ogni elemento corrisponde a una riga del file
        //FILE_IGNORE_NEW_LINES -> omette il ​​ritorno a capo alla fine di ogni elemento dell'array
        //FILE_SKIP_EMPTY_LINES -> omette le righe vuote
        if (file_exists($filename)) {
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (count($lines) >= 1) {
                //estraggo i primi due valori dalla prima riga, il primo è l'username, il secondo è la password
                list(self::$username, self::$password) = preg_split('/\s+/', $lines[0], 2);
            }
        }
    }
}

?>
