<?php

namespace App\Services;

class SteamService {
    
    private static $apiKey = "8F451AE3DC1F264AF6BADC449E9D1419";
    private static $apiBaseUrl = "http://api.steampowered.com/";
    private static $userPrefix = "ISteamUser";
    private static $playerSummarryPrefix = "GetPlayerSummaries";
    private static $apiVersion = "v0002";
    
    public function getSteamPlayerSummary($steamId) 
    {
        $url = this::$apiBaseUrl . "/" . this::$userPrefix . "/" . this::$playerSummarryPrefix . "/" . this::$apiVersion . "/?key=" . this::$apiKey . "&steamids=" . $steamId;
        echo $json = file_get_contents($url);
    }

}
