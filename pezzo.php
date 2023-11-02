<?php
class Pezzo{
    private $id;
    private $codice;
    private $titolo;

    public function getId(){ return $this -> id; }

    public function getCodice(){ return $this -> codice; }
    public function setCodice($value){ $this -> codice = $value; }

    public function getTitolo(){ return $this -> titolo; }
    public function setTitolo($value){ $this -> titolo = $value; }

    public static function getLastPezzoId()
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $query = "SELECT MAX(id) as last_id FROM pezzi";

        try {
            $stmt = DbManager::getPdo()->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['last_id'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            die("\nErrore nell'ottenere l'ultimo ID dalla tabella 'pezzi': " . $e->getMessage());
        }
    }
}

?>