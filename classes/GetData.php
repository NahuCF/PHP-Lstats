<?php

class GetData
{
    private $conection;

    public function __construct($db_config = '')
    {
        $this->conection = new PDO("mysql:host=localhost;dbname=lol_api", "root", "");
    }

    public function GetPuuid($table_name = "", $row_name = "puuid")
    {
        $statement = $this->conection->prepare("SELECT $row_name FROM $table_name");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>