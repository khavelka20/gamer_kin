<?php

namespace App\Services;

class GfycatService {
    
    private $_apiUrl = "https://upload.gfycat.com/transcodeRelease/";
    private $_keyPrefix = "gamerKin";
    private $_stdLength = 15;
    private $_youtubeUrl = "https://www.youtube.com/watch?v=";
    
    public function convertYoutubeClip($steamId, $videoId, $fetchMinutes){
        
        $url = $this->_apiUrl . $_keyPrefix . $steamId . "?fetchUrl=" . $this->_youtubeUrl . $videoId;
        $url .= "&fetchLength=15&fetchMinutes=" . round($fetchMinutes);
        
        $results = json_decode(file_get_contents($url));
        
    }    
}