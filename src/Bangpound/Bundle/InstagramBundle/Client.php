<?php

namespace Bangpound\Bundle\InstagramBundle;

use Instagram\Net\ClientInterface;

class Client implements ClientInterface
{
    private $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function get($url, array $data = null)
    {
        $response = $this->client->get(sprintf("%s?%s", $url, http_build_query($data)));

        return $response->getBody();
    }

    public function post($url, array $data = null)
    {
        // TODO: Implement post() method.
    }

    public function put($url, array $data = null)
    {
        // TODO: Implement put() method.
    }

    public function delete($url, array $data = null)
    {
        // TODO: Implement delete() method.
    }
}
