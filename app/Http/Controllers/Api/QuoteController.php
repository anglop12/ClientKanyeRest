<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $client = new Client();
        $headers = [];
        $consult = new Psr7Request('GET', 'https://api.kanye.rest', $headers);
        $response = $client->send($consult);

        return response()->json(json_decode($response->getBody()->getContents()));
    }

    public function indexMulti(Request $request, string $num = '1')
    {
        $quotes = [];
        for ($i=0; $i < (Integer)$num; $i++) {
            $client = new Client();
            $headers = [];
            $consult = new Psr7Request('GET', 'https://api.kanye.rest', $headers);
            $response = $client->send($consult);
            array_push($quotes, json_decode($response->getBody()->getContents()));
        }

        return response()->json($quotes);
    }

}
