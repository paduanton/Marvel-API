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
//        $cliente = new \GuzzleHttp\Client();
    }

    public function gera_hash($timestamp)
    {
        $chave_publica = config('app.marvel_publickey');
        $chave_privada = config('app.marvel_privatekey');

        return md5($timestamp . $chave_privada . $chave_publica);
    }

    public function getPersonagemId($nome)
    {
        $cliente = new \GuzzleHttp\Client();
        $timestamp = uniqid(); // Carbon::now();
        $hash = $this->gera_hash($timestamp);

        $chave_publica = config('app.marvel_publickey');
        $url_base = config('app.marvel_url');

        $response_body = $cliente->request('GET', $url_base . '/v1/public/characters', [
            'query' => [
                'apikey' => $chave_publica,
                'hash' => $hash,
                'ts' => $timestamp,
                'limit' => 1,
                'name' => $nome,
            ]
        ]);

        $response = $response_body->getBody();

        $personagem = json_decode($response, true);
        $id = $personagem['data']['results'][0]['id'];

        return response()->json([
            'id_personagem' => $id
        ], 200);
    }

    public function getHistorias($id_personagem)
    {
        $cliente = new \GuzzleHttp\Client();
        $timestamp = uniqid(); // Carbon::now();
        $hash = $this->gera_hash($timestamp);

        $chave_publica = config('app.marvel_publickey');
        $url_base = config('app.marvel_url');

        $response_body = $cliente->request('GET', $url_base . '/v1/public/characters/' . $id_personagem . '/stories', [
            'query' => [
                'apikey' => $chave_publica,
                'hash' => $hash,
                'ts' => $timestamp,
                'limit' => 5,
                'orderBy' => 'id'
            ]
        ]);

        $response = $response_body->getBody();

        $historias = json_decode($response, true);
        $info_historias = $historias['data']['results'];

        foreach ($info_historias as $key => $value) {
            $response_historias[$key] = array
            (
                'id' => $value["id"],
                'titulo' => $value["title"],
                'tipo' => $value["type"],
                'data_modificacao' => $value["modified"],
                'num_criadores' => $value["creators"]["available"],
                'num_series' => $value["series"]["available"],
                'num_quadrinhos' => $value["comics"]["available"],
                'num_herois' => $value["characters"]["available"],
                'num_eventos' => $value["events"]["available"]
            );
        }
        return response()->json([
            'historias' => $response_historias
        ], 200);
    }

    public function getQuadrinhos($id_historia)
    {
        $cliente = new \GuzzleHttp\Client();
        $timestamp = uniqid(); // Carbon::now();
        $hash = $this->gera_hash($timestamp);

        $chave_publica = config('app.marvel_publickey');
        $url_base = config('app.marvel_url');

        $response_body = $cliente->request('GET', $url_base . '/v1/public/stories/' . $id_historia . '/comics', [
            'query' => [
                'apikey' => $chave_publica,
                'hash' => $hash,
                'ts' => $timestamp,
                'orderBy' => 'onsaleDate'
            ]
        ]);

        $response = $response_body->getBody();

        $quadrinhos = json_decode($response, true);
        $info_quadrinhos = $quadrinhos['data']['results'];

        foreach ($info_quadrinhos as $key => $value) {
            $imagem = $value["thumbnail"]["path"]."/portrait_uncanny.".$value["thumbnail"]["extension"];
            $response_quadrinhos[$key] = array
            (
                'id' => $value["id"],
                'digital_id' => $value["digitalId"],
                'titulo' => $value["title"],
                'descricao' => $value["description"],
                'data_modificacao' => $value["modified"],
                'formato' => $value["format"],
//                'url_compra' => $value["urls"]["1"]["url"],
                'data_venda' => $value["dates"]["0"]["date"],
//                'data_compra_digital' => $value["dates"]["3"]["date"],
                'preco_versao_digital' => $value["prices"]["0"]["price"],
//                'preco_versao_fisica' => $value["prices"]["1"]["price"],
                'imagem' => $imagem
            );
        }
        return response()->json([
            'quadrinhos' => $response_quadrinhos
        ], 200);
    }

}
