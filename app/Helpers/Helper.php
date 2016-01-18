<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Validator;
use Flashy;
use GuzzleHttp;
use Parser;

class Helper
{
    public static function getAllPlexFriends()
    {
    	if ( ! Cache::has('plex_token')) {
    		return array();
		}

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'https://plex.tv/pms/friends/all', [
            'form_params' => [
                'user[login]' => env('PLEX_ADMIN_USERNAME'),
                'user[password]' => env('PLEX_ADMIN_PASSWORD'),
            ],
            'headers' => [
                'X-Plex-Token' => Cache::get('plex_token'),
            ],
            'http_errors' => false,
        ]);

        $body = (string) $res->getBody();
        $friends = array();

        foreach (Parser::xml($body)['User'] as $friend) {
        	$friends[] = $friend['@attributes']['username'];
        }

        return $friends;


    }
}