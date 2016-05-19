<?php

namespace App\Services;

class SteamService {
    
    private $apiKey = "8F451AE3DC1F264AF6BADC449E9D1419";
    private $apiBaseUrl = "http://api.steampowered.com/";
    private $userPrefix = "ISteamUser";
    private $playerSummarryPrefix = "GetPlayerSummaries";
    private $apiVersion = "v0002";
    
    public function getSteamPlayerSummary($steamId) 
    {
        $url = $this->apiBaseUrl . "/" . $this->userPrefix . "/" . $this->playerSummarryPrefix . "/" . 
                $this->apiVersion . "/?key=" . $this->apiKey . "&steamids=" . $steamId;
        
        return $json = file_get_contents($url);
    }

}
