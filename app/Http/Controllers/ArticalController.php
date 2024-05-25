<?php

namespace App\Http\Controllers;

use App\NewsFeed\NewsFeedClient;
use Illuminate\Http\Request;


class ArticalController extends Controller
{
    public function feed(Request $request)
    {
        $provider = $request->query('provider');
        $client = new NewsFeedClient($provider, $request);
        return response()->json($client->fetchFeed());
    }

    public function sources(Request $request)
    {
        $provider = $request->query('provider');
        $client = new NewsFeedClient($provider, $request);
        return response()->json($client->fetchSources());
    }
}
