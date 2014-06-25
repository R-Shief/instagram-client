<?php

namespace Bangpound\Instagram;

use Instagram\Net\ClientInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Client implements ClientInterface
{
    private $client;

    public function __construct(\Guzzle\Http\Client $client, Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    public function get($url, array $data = null)
    {
        $request = $this->client->get(sprintf( "%s?%s", $url, http_build_query( $data)));
        $response = $request->send();
        $this->session->set('api_limit', $response->getHeader('X-Ratelimit-Remaining')->toArray()[0]);

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
