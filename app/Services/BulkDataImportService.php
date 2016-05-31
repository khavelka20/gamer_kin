<?php

namespace App\Services;

use Goutte\Client;
use App\Game;
use App\Genre;
use App\GameGenre;
use Storage;
use App\Services\YouTubeService;
use App\Services\GfycatService;

class BulkDataImportService {

    private $_gameInfoBaseUrl = "http://store.steampowered.com/api/appdetails?appids=";
    private $_gameTagsBaseUrl = "http://store.steampowered.com/app/";
    private $_genres;
    private $_storePage;

    public function importGameplayClips() {
        $youtubeService = new YouTubeService();
        $gfycatService = new GfycatService();
        
        //Get the List of games to create gameplay clips for
        $games = Game::where('steam_user_rating', '=', 'Overwhelmingly Positive')
                ->whereNull('youtube_video_id')
                ->take(1)
                ->get();

        forEach ($games as $game) {
            $clip = $youtubeService->findVideoByName($game->name);
            $clipInfo = $youtubeService->getVideoInfo($clip->id->videoId);
            $duration = $this->getDurationInMinutes($clipInfo->contentDetails->duration);
            $gfycatService->convertYoutubeClip($clip->id->videoId, $duration / 3);
        }
    }

    public function importGames() {
        $this->loadGenres();

        $data = Storage::disk('local')->get('temp/gamelist.json');
        $appList = json_decode($data);
        $games = $appList->applist->apps->app;
        $i = 1;

        foreach ($games as $game) {
            $i++;
            if ($this->isNewGame($game)) {
                $gameDetails = $this->getGameInfo($game->appid);
                $this->saveGame($game, $gameDetails);
            }
        }
    }

    function getDurationInMinutes($youtube_time) {
        preg_match_all('/(\d+)/', $youtube_time, $parts);

        // Put in zeros if we have less than 3 numbers.
        if (count($parts[0]) == 1) {
            array_unshift($parts[0], "0", "0");
        } elseif (count($parts[0]) == 2) {
            array_unshift($parts[0], "0");
        }

        $sec_init = $parts[0][2];
        $seconds = $sec_init % 60;
        $seconds_overflow = floor($sec_init / 60);

        $min_init = $parts[0][1] + $seconds_overflow;
        $minutes = ($min_init) % 60;
        $minutes_overflow = floor(($min_init) / 60);

        $hours = $parts[0][0] + $minutes_overflow;

        if ($hours != 0){
            return ($hours * 60) + $minutes;
        }
            
        else{
            return $minutes;
        }
    }

    private function isNewGame($game) {
        $isNewGame = true;

        if (Game::where('steam_id', $game->appid)->exists()) {
            $isNewGame = false;
        }

        return $isNewGame;
    }

    private function saveGame($game, $gameDetails) {
        $newGame = new Game;
        $newGame->steam_id = $game->appid;

        if ($this->isValidGame($gameDetails) !== false) {
            $this->_storePage = $this->getStorePage($newGame);
            $newGame->type = "Game";
            $newGame->name = $gameDetails->name;
            $newGame->description = $gameDetails->detailed_description;
            $newGame->logo = $gameDetails->header_image;

            if (isset($gameDetails->metacritic)) {
                $newGame->metacritic_rating = $gameDetails->metacritic->score;
            }

            $this->setSteamRating($newGame);
        }
        //Save this game as type other
        else {
            $newGame->type = "Other";
        }

        $newGame->save();

        if ($newGame->type === "Game") {
            $this->setGameGenres($newGame);
        }
    }

    private function loadGenres() {
        $genres = Genre::all();
        $this->_genres = $genres;
    }

    private function getStorePage($newGame) {
        $client = new Client();
        return $client->request('GET', $this->_gameTagsBaseUrl . $newGame->steam_id);
    }

    private function setSteamRating($newGame) {
        $steamRating = $this->_storePage->filter('div[itemprop="aggregateRating"]');

        $steamRating = $steamRating->filter('span.game_review_summary')->each(function($node) {
            return $node->text();
        });

        if (isset($steamRating[0])) {
            $newGame->steam_user_rating = $steamRating[0];
        }
    }

    private function setGameGenres($newGame) {
        $genre = null;
        $genreRank = 1;

        $gameGenres = $this->_storePage->filter('a[class="app_tag"]')->each(function ($node) {
            return trim($node->text());
        });

        if (isset($gameGenres)) {
            foreach ($gameGenres as $gameGenre) {
                $genre = $this->getGenre($gameGenre);

                if ($genre === null) {
                    $genre = $this->saveNewGenre($gameGenre);
                }

                $gameGenre = new GameGenre();
                $gameGenre->game_id = $newGame->id;
                $gameGenre->genre_id = $genre->id;
                $gameGenre->rank = $genreRank;
                $gameGenre->save();

                $genreRank++;
            }
        }
    }

    private function saveNewGenre($gameGenre) {
        $genre = new Genre();
        $genre->name = trim($gameGenre);
        $genre->save();
        //Reload Genres
        $this->loadGenres();
        return $genre;
    }

    private function getGenre($genreName) {
        $genreToFind = null;

        foreach ($this->_genres as $genre) {
            if ($genre->name == trim($genreName)) {
                $genreToFind = $genre;
                return $genreToFind;
            }
        }

        return $genreToFind;
    }

    private function isValidGame($gameDetails) {
        $isValidGame = false;

        if ($gameDetails !== false) {
            if ($gameDetails->type === "game") {
                $isValidGame = true;
            }
        }

        return $isValidGame;
    }

    private function getGameInfo($appId) {
        $gameDetails = json_decode(file_get_contents($this->_gameInfoBaseUrl . $appId));

        foreach ($gameDetails as $gameDetail) {
            if ($gameDetail->success) {
                return $gameDetail->data;
            } else {
                return false;
            }
        }
    }

}
