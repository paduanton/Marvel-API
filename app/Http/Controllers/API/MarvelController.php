<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class MarvelController extends Controller
{

    public function __construct()
    {
//        $cliente = new \GuzzleHttp\Client();
    }

    public function gera_hash($timestamp) {
        $chave_publica = config('app.marvel_publickey');
        $chave_privada = config('app.marvel_privatekey');

        return md5($timestamp.$chave_privada.$chave_publica);
    }

    public function getPersonagemId($nome) {
        $client = new \GuzzleHttp\Client();
        $hora_atual = new \DateTime();
        $timestamp = $hora_atual->date;
        $hash = gera_hash($timestamp);

        $chave_publica = config('app.marvel_publickey');
        $url_base = config('app.marvel_url');

        $res = $client->request('GET', $url_base.'/v1/public/characters', [
            'apikey' => $chave_publica,
            'hash' => $hash,
            'ts' => $timestamp,
            'limit' => 1,
            'name' => $nome,
        ]);

        return response()->json([
            'id' => $nome
        ], 200);
    }

    public function getHistorias($id_personagem) {

    }
/*
    public function getHistoriasInfo($id_personagem) {

    }*/

    public function getQuadrinhos($id_historia) {

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
