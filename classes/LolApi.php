<?php

// NOTES:
// -Cron Job

class LolApi
{
    private $api_key;

    private $petitions = 0;
    private $last_time;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }
    
    public function getItemsID()
    {
        $items_array = array();
        
        $this->getMatchsID();

        for($i = 0; $i < 1; $i++)
        {
            $match_data = $this->curl("https://americas.api.riotgames.com/lol/match/v5/matches/" . $this->matchs_id_array[$i] . "?api_key=" . $this->api_key); 

            for($y = 0; $y < 10; $y++)
            {
                $champion_name = $match_data["info"]["participants"][$y]["championName"];

                $champion_items = array(
                    "item0" => $match_data["info"]["participants"][$y]["item0"],
                    "item1" => $match_data["info"]["participants"][$y]["item1"],
                    "item2" => $match_data["info"]["participants"][$y]["item2"],
                    "item3" => $match_data["info"]["participants"][$y]["item3"],
                    "item4" => $match_data["info"]["participants"][$y]["item4"],
                    "item5" => $match_data["info"]["participants"][$y]["item5"],
                    "item6" => $match_data["info"]["participants"][$y]["item6"]
                );

                $items_array[$champion_name] = $champion_items;
            }
        }

        return $items_array;
    }

    public function getWinRate()
    {
        $data = $this->curl("https://la2.api.riotgames.com/lol/league/v4/entries/by-summoner/" . $this->getEncryptedSummonerID() . "?api_key=" . $this->api_key);

        $total_matchs = $data[0]["wins"] + $data[0]["losses"];
        return round(($data[0]["wins"] * 100) / $total_matchs);
    }

    public function getMatchsDiamondID($summoner_puuid, $begin = 0, $end = 100)
    {
        $total_matchs_id = [];

        try
        {
            $summoner_matchs_id = $this->curl("https://americas.api.riotgames.com/lol/match/v5/matches/by-puuid/"
                . $summoner_puuid["puuid"] 
                . "/ids?start=" . $begin 
                . "&count=" . $end
                . "&api_key=" . $this->api_key
            );

            $this->petitions++;
            $this->checkRequest();

            for($x = 0; $x < count($summoner_matchs_id); $x++)
            {
                array_push($total_matchs_id, $summoner_matchs_id[$x]);
            }         
        }
        catch(Exception $e)
        {
            echo $e;
            exit;
        }

        return $total_matchs_id;
    }

    public function initTime() // This MUST be called before you start request to the API
    {
        $this->last_time = time();
    }

    public function getDiamondsID() //This class will get the summonerId of all the diamonsds
    {
        $summoner_ids = [];
        $page = 1;

        $divisions = [
            1 => "I",
            2 => "II",
            3 => "III",
            4 => "IV"
        ];

        for($i = 1; $i <= 1; $i++) // This will me loop for the four divisions in Diamond
        {   
            while(true) // Here I am gonna retrieve the summonerId of all the players in Diamond
            {
                try
                {
                    $summoners_array = $this->curl("https://la2.api.riotgames.com/lol/league/v4/entries/RANKED_SOLO_5x5/DIAMOND/" 
                        . $divisions[$i] . "?page=" . $page . "&api_key=" . $this->api_key
                    );

                    $this->petitions++;
                    $this->checkRequest();

                    if(!empty($summoners_array))
                    {
                        for($x = 0; $x < count($summoners_array); $x++)
                        {
                            array_push($summoner_ids, $summoners_array[$x]["summonerId"]);
                        }

                        $page++;
                    }
                    else
                    {   
                        $page = 1;
                        break;
                    }
                }
                catch(Exception $e)
                {
                    echo $e;
                    exit;
                }
            }
        }

        return $summoner_ids;
    }

    public function getPuuidWithID($summoner_ids)
    {
        $summoner_puuid = [];

        for($i = 0; $i < count($summoner_ids); $i++) // This will loop for all the players in Diamond
        {   
            try
            {
                $summoner_data = $this->curl("https://la2.api.riotgames.com/lol/summoner/v4/summoners/" 
                    . $summoner_ids[$i] 
                    . "?api_key=" . $this->api_key
                );

                $this->petitions++;
                $this->checkRequest();

                array_push($summoner_puuid, $summoner_data["puuid"]);
            }
            catch(Exception $e)
            {
                echo $e;
                exit;
            }
        }

        return $summoner_puuid;
    }

    private function checkRequest() // This function will make sure that you does not surpass the developer limit of requests
    {
        if($this->petitions > 97)
        {
            $first = $this->last_time;
            $current = time(); 
            $until = $first + 122;
        
            if($until - $current > 0)
            {
                sleep($until - $current);
            }

            $this->petitions = 0;
            $this->last_time = time();
        }
    }

    private function curl($url) // This is the basic set up of all the curls that I will use
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = json_decode(curl_exec($curl), true);
        curl_close($curl);

        $this->checkHTTPcode($curl);

        return $data;
    }

    private function checkHTTPcode($curl) // This will throw an exception if there is a problem with the request
    {
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $http_codes = array(
            400 => "Bad request",
            401 => "Unauthorized",
            403 => "Forbidden",
            404 => "Data not found",
            405 => "Method not allowed",
            415 => "Unsupported media type",
            429 => "Rate limit exceeded",
            500 => "Internal server error",
            502 => "Bad gateway",
            503 => "Service unavailable",
            504 => "Gateway timeout"
        );

        if(!empty($http_codes[$http_code]))
            throw new Exception($http_code . " " . $http_codes[$http_code]);
        else if($http_code == 0)
            throw new Exception("Bad URL.");
    }
}

?>