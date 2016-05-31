<?php

namespace App\Services;

use Alaouy\Youtube\Youtube;

class YouTubeService {

    private $key = 'AIzaSyDaN4NFxNxBGi011jwSWPmvTN89OUzBM-E';

    public function findVideoByName($name) {
        $youtube = new Youtube($this->key);
        $results = $youtube->searchVideos($name . ' PC gameplay');
        return $results[0];
    }
    
    public function getVideoInfo($videoId){
        $youtube = new Youtube($this->key);
        $results = $youtube->getVideoInfo($videoId);
        return $results;
    }

}
