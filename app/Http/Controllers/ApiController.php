<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiService;

class ApiController extends Controller {

    public function loadGamer($steamId) {
        $apiService = new ApiService();

        $gamer = $apiService->loadGamer($steamId);

        return response()->json($gamer);
    }
    
    public function loadGamerGames($gamerId){
        
        $apiService = new ApiService();
        
        $gamerGames = $apiService->loadGamerGames($gamerId);
        
        return response()->json($gamerGames);
    }
    
    public function rateGamerGame($gamerId, $gamerGameId, $rating){
        
        $apiService = new ApiService();
        $apiService->rateGamerGame($gamerId, $gamerGameId, $rating);
        
    }

    public function loadGamesToBrowse($gamerId){
        
        $apiService = new ApiService(); 
        $gamesToBrowse = $apiService->loadGamesToBrowse($gamerId);
        return $gamesToBrowse;
    }
}