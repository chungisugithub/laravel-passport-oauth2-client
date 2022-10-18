<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect() {
        $queries = http_build_query([
            'client_id' => config('services.oauth_server.client_id'),
            'redirect_uri'=> config('services.oauth_server.redirect'),
            'response_type' => 'code',
            'scope' => 'view-posts view-user'
        ]);
        // dd(config('services.oauth_server.uri'));
        return redirect(config('services.oauth_server.uri').'/oauth/authorize?'. $queries);
    }
    
    public function callback(Request $request) {
        // dd($request);
        $response = Http::post(config('services.oauth_server.uri').'/oauth/token', [
            'grant_type'=>'authorization_code',
            'client_id' => config('services.oauth_server.client_id'),
            'client_secret' => config('services.oauth_server.client_secret'),
            'redirect_uri'=> config('services.oauth_server.redirect'),
            'code' => $request->code
        ]);
        // dd($response);

        $response = $response->json();
        dd($response);
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
