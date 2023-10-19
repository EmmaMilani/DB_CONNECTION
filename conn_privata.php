<?php 
    private function Connetti(){
        $conn = new Connessione("localhost", "concerto");
        $conn -> credentials('file.txt');
        $conn -> connect();
    }

    public static function create($cod_, $tit, $descr, $date, $salaId_, $orchestraId_)
    {
        $conc = new Concerto();
        $conc->Connetti();
    }
?>
