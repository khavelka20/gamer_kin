<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SteamService;

class UserController extends Controller
{
    
    public function showProfile($id)
    {
        $steamService = new SteamService();
        
        return $steamService->getSteamPlayerSummary($id);
    }
}