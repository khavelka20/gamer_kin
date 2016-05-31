<?php

namespace App\Services;

use DB;
use App\Gamer;
use App\GamerGame;
use App\Services\SteamService;
use Carbon\Carbon;

class GamerService {

    private $refreshThresholdHours = 2;

    public function loadGamerBySteamId($steamId) {

        $gamer = Gamer::where("steam_id", $steamId)->first();

        return $gamer;
    }

    public function gamerExists($steamId) {

        $gamerExists = Gamer::where("steam_id", $steamId)->exists();

        return $gamerExists;
    }

    public function loadGamerById($gamerId) {
        $gamer = Gamer::where("id", $gamerId)->first();

        return $gamer;
    }

    public function loadGamerLibrary(Gamer $gamer) {

        //Check to see if the gamer has games
        if ($this->gamerHasGames($gamer)) {
            //Check to see if the gamer's library needs to be refreshed
            if ($this->gamerGamesRequireRefresh($gamer)) {
                
            }
        } else {
            $this->populateGamerGames($gamer);
        }
        
        $gamerGames = $this->getGamerGames($gamer);
        
        return $gamerGames;
    }

    public function getGamerGames(Gamer $gamer) {
        $gamerGames = GamerGame::where("gamer_id", $gamer->id)
                ->with('game')
                ->orderBy('time_played', 'desc')
                ->get();

        return $gamerGames;
    }

    public function getGamerGameIdList(Gamer $gamer){
     
        $gamerGameIdList = DB::table('gamer_games')
                ->select('gamer_games.game_id')
                ->where('gamer_games.gamer_id', '=', $gamer->id)
                ->get();
                
        return $gamerGameIdList;
    }
    
    public function populateGamerGames(Gamer $gamer) {
        $steamService = new SteamService();
        $steamLibraryGames = $steamService->getSteamPlayerLibrary($gamer);

        $gameService = new GameService();

        foreach ($steamLibraryGames as $steamLibraryGame) {
            //Load the game by steam (app) id
            $game = $gameService->getGameBySteamId($steamLibraryGame->appid);
            if ($game->type == "Game") {
                $gamerGame = new GamerGame();
                $gamerGame->gamer_id = $gamer->id;
                $gamerGame->game_id = $game->id;
                $gamerGame->time_played = $steamLibraryGame->playtime_forever;
                $gamerGame->save();
            }
        }

        $this->setGamesUpdated($gamer);
    }

    public function gamerGamesRequireRefresh(Gamer $gamer) {

        $gamerGamesRequireRefresh = false;

        $now = Carbon::now();

        $gamerGamesRefreshed = Carbon::parse($gamer->games_updated_at);

        if ($gamerGamesRefreshed->diffInHours($now) >= $this->refreshThresholdHours) {
            $gamerGamesRequireRefresh = true;
        }

        return $gamerGamesRequireRefresh;
    }

    public function gamerHasGames(Gamer $gamer) {

        $gamerHasGames = GamerGame::where("gamer_id", $gamer->id)->exists();

        return $gamerHasGames;
    }

    public function setGamesUpdated(Gamer $gamer) {
        $gamer->games_updated_at = Carbon::now();
        $gamer->save();
    }

    public function createNewGamer($steamId) {

        $steamService = new SteamService();
        //Create a new gamer record and set the steam id
        $gamer = new Gamer();
        $gamer->steam_id = $steamId;

        //Call steam API and get their player summary
        $steamInfo = $steamService->getSteamPlayerSummary($steamId);

        //If the summary was returned from the steam api fill the details.
        if ($steamInfo != null) {
            $gamer->name = $steamInfo->personaname;
            $gamer->avatar = $steamInfo->avatar;
            $gamer->avatar_medium = $steamInfo->avatarmedium;
        }

        $gamer->save();
    }

    public function rateGamerGame($gamerId, $gamerGameId, $rating){
    
        $gamerGame = GamerGame::where('id', $gamerGameId)
                ->where('gamer_id', $gamerId)
                ->first();
        
        $gamerGame->rating = $rating;
        
        $gamerGame->save();
        
    }
    
}
