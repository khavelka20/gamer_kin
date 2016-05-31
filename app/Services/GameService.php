<?php

namespace App\Services;

use App\Game;
use App\Gamer;
use DB;

class GameService {

    public function getGameBySteamId($steamId) {

        $game = Game::where("steam_id", $steamId)->first();

        return $game;
    }

    public function loadGamesToBrowse(Gamer $gamer) {

        $gamesToBrowse = DB::table('games')
                ->leftJoin('gamer_games', function ($join) use ($gamer) {
                    $join->on('games.id', '=', 'gamer_games.game_id')
                        ->where('gamer_games.gamer_id', '=', $gamer->id);
                })
                ->whereNull('gamer_games.id')
                ->where('games.type', '=', 'Game')
                ->where('games.steam_user_rating', '=', 'Overwhelmingly Positive')
                ->select(
                        'games.name', 
                        'games.logo', 
                        DB::raw('true as showHeader'),
                        DB::raw('false as showPreview'),
                        DB::raw("'GIF' as previewType"))
                ->take(20)
                ->get();

        return $gamesToBrowse;
    }
}
