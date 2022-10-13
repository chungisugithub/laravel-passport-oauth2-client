<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect() {
        $queries = http_build_query([
            'client_id' => '5',
            'redirect_uri'=> 'http://127.0.0.1:8001/oauth/callback',
            'response_type' => 'code'
        ]);
        return redirect('http://127.0.0.1:8000/oauth/authorize?'. $queries);
    }
    
    public function callback(Request $request) {
        $response = Http::post('http://127.0.0.1:8000/oauth/token', [
            'grant_type'=>'authorization_code',
            'client_id' => '5',
            'client_secret' => 'jM9FdCfzFiAozOUsxzLbzvWqgLSx5LTrzDKKyokX',
            'redirect_uri'=> 'http://127.0.0.1:8001/oauth/callback',
            'code' => $request->code
        ]);

        dd($response->json());
    }
}
