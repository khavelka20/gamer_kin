<?php

namespace App\Services;

class SteamService {
    
    var $apiKey = "8F451AE3DC1F264AF6BADC449E9D1419";
    var $apiBaseUrl = "http://api.steampowered.com/";
    var $userPrefix = "ISteamUser";
    var $playerSummarryPrefix = "GetPlayerSummaries";
    var $apiVersion = "v0002";
    
    public function getSteamPlayerSummary($steamId) 
    {
        $url = $apiBaseUrl . "/" . $userPrefix . "/" . $playerSummarryPrefix . "/" . $apiVersion . "/?key=" . $apiKey . "&steamids=" . $steamId;
        echo $json = file_get_contents($url);
    }

}
