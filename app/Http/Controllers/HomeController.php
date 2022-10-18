<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = [];

        if (auth()->user()->token) {

            if (auth()->user()->token->hasExpired()) {
                return redirect('/oauth/refresh');
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . auth()->user()->token->access_token
            ])->get(config('services.oauth_server.uri').'/api/user');
            // dd($response);
            if ($response->status() === 200) {
                $user = $response->json();
                dd($user);
            }
        }
        
        return view('home', [
            'posts' => $posts
        ]);
    }
}
