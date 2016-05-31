<?php

namespace App\Services;
use App\Services\GamerService;
use App\Services\GameService;

class ApiService {
    
    public function loadGamer($steamId){
        
        $gamerService = new GamerService();
                
        if(!$gamerService->gamerExists($steamId)){
            //If there is no gamer record then create a new one.
            $gamerService->createNewGamer($steamId);
        }
        
        //Load the gamer
        $gamer = $gamerService->loadGamerBySteamId($steamId);        
        
        return $gamer;
    }
    
    public function loadGamerGames($gamerId){
        
        $gamerService = new GamerService();
        $gamer = $gamerService->loadGamerById($gamerId);
        $gamerGames = $gamerService->loadGamerLibrary($gamer);
                
        return $gamerGames;
        
    }
    
    public function rateGamerGame($gamerId, $gamerGameId, $rating){
        
        $gamerService = new GamerService();
        $gamerService->rateGamerGame($gamerId, $gamerGameId, $rating);
        
    }
    
    public function loadGamesToBrowse($gamerId){
        
        $gamerService = new GamerService();
        $gameService = new GameService();
        
        $gamer = $gamerService->loadGamerById($gamerId);
        $gamesToBrowse = $gameService->loadGamesToBrowse($gamer);
        
        return $gamesToBrowse;
    }
    
}