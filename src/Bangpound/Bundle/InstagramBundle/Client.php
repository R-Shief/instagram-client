<?php

namespace Bangpound\Bundle\InstagramBundle;

use Instagram\Net\ClientInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Client implements ClientInterface
{
    private $client;

    public function __construct(\GuzzleHttp\Client $client, Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    public function get($url, array $data = null)
    {
        $response = $this->client->get(sprintf("%s?%s", $url, http_build_query($data)));
        $this->session->set('api_limit', $response->getHeader('X-Ratelimit-Remaining'));

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
