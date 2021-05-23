<?php set_time_limit(0);

require "../classes/LolApi.php";
require "../classes/GetData.php";

$api_key = "RGAPI-bcc42bb6-57e7-40de-bbd6-6267f8677a6e";

$conection = new PDO("mysql:host=localhost;dbname=lol_api", "root", "");

//Get puuid from LOL API
$API = new LolApi($api_key);
$API->initTime();
// $diamonds_ids = $API->getDiamondsID();
// $diamonds_puuids = $API->getPuuidWithID($diamonds_ids);

// // Insert puuid
// for($i = 0; $i < count($diamonds_puuids); $i++)
// {
//     $statement = $conection->prepare("INSERT INTO puuid values(null, :puuid)");
//     $statement->execute(array("puuid" => $diamonds_puuids[$i]));
// }

// // Retrieve puuid from DB and insert the match_ids
$db = new GetData;
$result = $db->GetPuuid("puuid");

// // I will insert 100k matchs id
for($i = 0; $i < count($result); $i++)
{
    $matchs_id = $API->getMatchsDiamondID($result[$i]);
    
    for($a = 0; $a < count($matchs_id); $a++)
    {
        $statement = $conection->prepare("INSERT INTO matchs_id values(null, :match_id)");
        $statement->execute(array("match_id" => $matchs_id[$a]));
    }
}

?>