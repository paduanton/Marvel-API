<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Carbon\Carbon;

class MarvelController extends Controller
{

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        $this->timestamp = uniqid(); // Carbon::now();
        $this->hash = $this->gera_hash($this->timestamp);
        $this->public_key = config('app.marvel_publickey');
        $this->base_url = config('app.marvel_url');
    }

    public function gera_hash($timestamp)
    {
        $chave_privada = config('app.marvel_privatekey');
        $public_key = config('app.marvel_publickey');

        return md5($timestamp . $chave_privada . $public_key);
    }

    public function getCharacterId($name)
    {
        $response_body = $this->guzzle->request('GET', $this->base_url . '/characters', [
            'query' => [
                'apikey' => $this->public_key,
                'hash' => $this->hash,
                'ts' => $this->timestamp,
                'limit' => 1,
                'name' => $name,
            ]
        ]);

        $response = $response_body->getBody();

        $character = json_decode($response, true);
        $id = $character['data']['results'][0]['id'];

        return response()->json([
            'id' => $id
        ], 200);
    }

    public function getStoriesByCharacterId($id_character)
    {
        $response_body = $this->guzzle->request('GET', $this->base_url . '/characters/' . $id_character . '/stories', [
            'query' => [
                'apikey' => $this->public_key,
                'hash' => $this->hash,
                'ts' => $this->timestamp,
                'limit' => 5,
                'orderBy' => 'id'
            ]
        ]);

        $response = $response_body->getBody();

        $stories = json_decode($response, true);
        $info_stories = $stories['data']['results'];

        foreach ($info_stories as $key => $value) {
            $response_stories[$key] = array
            (
                'id' => $value["id"],
                'title' => $value["title"],
                'type' => $value["type"],
                'modified' => $value["modified"],
                'creators' => $value["creators"]["available"],
                'series' => $value["series"]["available"],
                'comics' => $value["comics"]["available"],
                'heroes' => $value["characters"]["available"],
                'events' => $value["events"]["available"]
            );
        }
        return response()->json([
            'stories' => $response_stories
        ], 200);
    }

    public function getComicsByStoryId($story_id)
    {
        $response_body = $this->guzzle->request('GET', $this->base_url . '/stories/' . $story_id . '/comics', [
            'query' => [
                'apikey' => $this->public_key,
                'hash' => $this->hash,
                'ts' => $this->timestamp,
                'orderBy' => 'onsaleDate'
            ]
        ]);

        $response = $response_body->getBody();

        $comics = json_decode($response, true);
        $info_comics = $comics['data']['results'];

        foreach ($info_comics as $key => $value) {
            $image = $value["thumbnail"]["path"] . "/portrait_uncanny." . $value["thumbnail"]["extension"];
            $response_comics[$key] = array
            (
                'id' => $value["id"],
                'digitalId' => $value["digitalId"],
                'titulo' => $value["title"],
                'description' => $value["description"],
                'modified' => $value["modified"],
                'format' => $value["format"],
//                'url_compra' => $value["urls"]["1"]["url"],
                'saleDate' => $value["dates"]["0"]["date"],
//                'data_compra_digital' => $value["dates"]["3"]["date"],
                'digitalPrice' => $value["prices"]["0"]["price"],
//                'preco_versao_fisica' => $value["prices"]["1"]["price"],
                'image' => $image
            );
        }
        return response()->json([
            'comics' => $response_comics
        ], 200);
    }

    public function getCharacteryById($id_character)
    {
        $response_body = $this->guzzle->request('GET', $this->base_url . '/characters/' . $id_character, [
            'query' => [
                'apikey' => $this->public_key,
                'hash' => $this->hash,
                'ts' => $this->timestamp,
            ]
        ]);

        $response = $response_body->getBody();
        $character = json_decode($response, true);
        $image = $character['data']['results'][0]['thumbnail']['path'] . "/portrait_uncanny." . $character['data']['results'][0]['thumbnail']['extension'];

        $hero = [
            'name' => $character['data']['results'][0]['name'],
            'description' => $character['data']['results'][0]['description'],
            'modified' => $character['data']['results'][0]['modified'],
            'image' => $image
        ];

        return response()->json([
            'hero' => $hero
        ], 200);
    }
}
