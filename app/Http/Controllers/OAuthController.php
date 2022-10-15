<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect() {
        $queries = http_build_query([
            'client_id' => '5',
            'redirect_uri'=> 'http://client.test/oauth/callback',
            'response_type' => 'code'
        ]);
        return redirect('http://127.0.0.1:8000/oauth/authorize?'. $queries);
    }
    
    public function callback(Request $request) {
        $response = Http::post('http://127.0.0.1:8000/oauth/token', [
            'grant_type'=>'authorization_code',
            'client_id' => '5',
            'client_secret' => 'gEOOk03B1Cad6HvUrfQD7YvtOIdCrdhFTSrpsHGn',
            'redirect_uri'=> 'http://client.test/oauth/callback',
            'code' => $request->code
        ]);
        // dd($response);

        $response = $response->json();

        $request->user()->token()->delete();

        $token = $request->user()->token()->create([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        // dd($token);
        return redirect('/home');
    }
}
