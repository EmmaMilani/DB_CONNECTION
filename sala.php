<?php
class Sala{
    private $id;
    private $codice;
    private $nome;
    private $capienza;

    public function getId(){ return $this -> id; }

    public function getCodice(){ return $this -> codice; }
    public function setCodice($value){ $this -> codice = $value; }

    public function getNome(){ return $this -> nome; }
    public function setNome($value){ $this -> nome = $value; }

    public function getCapienza(){ return $this -> capienza; }
    public function setCapiena($value){ $this -> capienza = $value; }

    public static function getLastSalaId()
    {
        DbManager::initialize("localhost", "concerto", "file.txt");
        $query = "SELECT MAX(id) as last_id FROM sale";

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
            die("\nErrore nell'ottenere l'ultimo ID dalla tabella 'sale': " . $e->getMessage());
        }
    }
}

?>