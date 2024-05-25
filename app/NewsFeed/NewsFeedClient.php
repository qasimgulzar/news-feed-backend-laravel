<?php

namespace App\NewsFeed;

use Illuminate\Support\Facades\Http;

class NewsFeedClient
{
    private $provider;
    private $request;

    public function __construct($provider, $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function params($request)
    {
        $params = [];
        $query = $request->all();
        switch ($this->provider) {
            case 'newsapi':
            {

                $params['from'] = $request->query('from');
                $params['sortBy'] = $request->query('sortBy');
                $params['q'] = $request->query('q');
                $params['page'] = $request->query('page');
                $params['pageSize'] = $request->query('pageSize');
                $params['section'] = $request->query('section');
                $params['sources'] = $request->query('source');
                $params['apiKey'] = 'b4bfc52e4dcc473e95503028dcf838e2';
                $params['from'] = '2024-05-22';
                break;
            }
            case 'guardianapis':
            {
                $params['from-date'] = $request->query('from');
                $params['order-by'] = $request->query('sortBy');
                $params['section'] = $request->query('section');
                $params['source'] = $request->query('source');
                $params['q'] = $request->query('q');
                $params['page'] = $request->query('page');
                $params['page-size'] = $request->query('pageSize');
                $params['api-key'] = 'test';
                break;
            }
        }

        $params = array_filter((array)$params, function ($val) {
            return !is_null($val);
        });
        if(!isset($params['q']) && !isset($params['sources']) && isset($params['section']))
            $params['q'] = $params['section'];
        else if(!isset($params['q']) && !isset($params['sources']) && !isset($params['section']))
            $params['q'] = 'International';
        return $params;
    }

    private function getEndpoint($provider)
    {
        if ($provider == 'newsapi') {
            return "https://newsapi.org/v2/everything";
        } else if ($provider == 'guardianapis') {
            return 'https://content.guardianapis.com/search';
        }

    }

    public function fetchSources()
    {
        $endpoint = "https://newsapi.org/v2/top-headlines/sources";
        $params = ["apiKey" => 'b4bfc52e4dcc473e95503028dcf838e2'];
        return Http::get($endpoint, $params)->json();
    }

    public function fetchFeed()
    {

        $endpoint = $this->getEndpoint($this->provider);
        $params = $this->params($this->request);
        return $this->transformResponse($this->provider, Http::get($endpoint, $params)->json());
    }

    public function transformResponse($provider, $response)
    {
        $transformed = $response;
        switch ($provider) {
            case 'guardianapis' :
            {
                $response = $response["response"];
                $transformed = [
                    "totalResults" => $response["total"],
                    "articles" => array_map(function ($ele) {
                        return [
                            "title" => $ele['webTitle'],
                            "urlToImage" => "",
                            "url" => $ele["webUrl"],
                            "sectionId" => $ele["sectionId"],
                            "sectionName" => $ele["sectionName"],
                            "pillarName" => isset($ele["pillarName"])?$ele["pillarName"]:''
                        ];
                    }, $response["results"]),
                    "status" => "ok"
                ];
                break;
            }
        }
        return $transformed;
    }
}
