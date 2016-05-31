<?php

namespace App\Services;

class SteamService {

    private $apiKey = "8F451AE3DC1F264AF6BADC449E9D1419";
    private $apiBaseUrl = "http://api.steampowered.com/";
    private $userPrefix = "ISteamUser";
    private $libraryPrefix = "IPlayerService/GetOwnedGames";
    private $playerSummarryPrefix = "GetPlayerSummaries";
    private $apiVersion = "v0002";
    private $oldApiVersion = "v0001";

    public function getSteamPlayerSummary($steamId) {
        $playerSummary = null;

        $url = $this->apiBaseUrl . "/" . $this->userPrefix . "/" . $this->playerSummarryPrefix . "/" .
                $this->apiVersion . "/?key=" . $this->apiKey . "&steamids=" . $steamId;

        $playerSummaryResponse = json_decode(file_get_contents($url));

        if (isset($playerSummaryResponse->response->players[0]->steamid)) {
            $playerSummary = $playerSummaryResponse->response->players[0];
        }

        return $playerSummary;
    }

    public function getSteamPlayerLibrary($gamer) {

        $steamPlayerLibrary = null;

        $url = $this->apiBaseUrl . $this->libraryPrefix . "/" . $this->oldApiVersion .
                "/?key=" . $this->apiKey . "&steamid=" . $gamer->steam_id . "&format=json&include_played_free_games=1";
        
        $steamPlayerLibraryResponse = json_decode(file_get_contents($url));

        if (isset($steamPlayerLibraryResponse->response->game_count)) {
            $steamPlayerLibrary = $steamPlayerLibraryResponse->response->games;
        }

        return $steamPlayerLibrary;
    }

}
