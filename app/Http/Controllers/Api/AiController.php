<?php

namespace App\Http\Controllers\Api;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiController extends ApiBaseController
{
    public function searchArticle(){
        
        $apiKey = 'AIzaSyDmyFs7X7a55UymSNxM7_eUfNmeqmM1XN8';
        $searchEngineId = '000888210889775888983:pqb3ch1ewhg';
        $query = 'artikel balita sehat';
        $countryCode = 'ID';
        $url = 'https://www.googleapis.com/customsearch/v1?key=' . $apiKey . '&cx=' . $searchEngineId . '&q=' . urlencode($query) . '&cr=' . $countryCode;
        $response = file_get_contents($url);
        $results = json_decode($response);

        if (isset($results->error)) {
            echo 'Error: ' . $results['error']['message'];
        } else {
            $searchResults = [];
            foreach ($results->items as $item) {
                $searchResult = [
                    'title' => $item->title,
                    'link' => $item->link,
                    'snippet' => $item->snippet
                ];
                $searchResults[] = $searchResult;
            }
        }
        return $this->successResponse("data artikel", $searchResults);
    }

    // public function searchArticle(){
    //     $query = 'balita sehat';
    //     $url = 'https://news.google.com/rss/search?q=' . urlencode($query);
    //     $xml = file_get_contents($url);
    //     $feed = simplexml_load_string($xml);

    //     if ($feed) {
    //         $searchResults = [];
    //         foreach ($feed->channel->item as $item) {
    //             $searchResult = [
    //                 'title' => $item->title->__toString(),
    //                 'link' => $item->link->__toString()
    //             ];
    //             $searchResults[] = $searchResult;
    //         }
    //     } else {
    //         echo 'Failed to load feed.';
    //     }
    //     return $this->successResponse("data artikel", $searchResults);
    // }
}
