<?php

namespace App\Service;

use Doctrine\DBAL\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
class SpotifyService
{

    private $client;
    private $accessToken;

    public function __construct(string $clientId='2bcc179262aa43f6bcdf865d4cb5cc66', string $clientSecret='df45bf0cf9d840ae933c14f3b463734b')
    {
        $this->client = HttpClient::create();
        $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
            'auth_basic' => [$clientId, $clientSecret],
            'body' => ['grant_type' => 'client_credentials']
        ]);
        $data = $response->toArray();
        $this->accessToken = $data['access_token'];
    }

    public function getArtists(): array
    {
        $response = $this->client->request('GET', "https://api.spotify.com/v1/search/", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}"
            ],
            'query' => [
                'q'=>'artist',
                'type'=>'artist',
                'limit'=> 4,

            ]
        ]);
        return $response->toArray()['artists']['items'];
    }

    public function getAlbums(): array
    {
        $response = $this->client->request('GET', "https://api.spotify.com/v1/albums/", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}"
            ],
            'query' => [
                'limit'=>5,
                'ids'=>'2ePFIvZKMe8zefATp9ofFA,2ODvWsOgouMbaA5xf0RkJe,3cWJo9bnoGFkrAehMS33mT,3I9Z1nDCL4E0cP62flcbI5'

            ]
        ]);
        return $response->toArray()['albums'];
    }

    public function getTracks(): array
    {
        $response = $this->client->request('GET', "https://api.spotify.com/v1/tracks/", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}"
            ],
            'query' => [
                'limit'=>5,
                'ids'=>'2dWJ5xYr3QqcOeSuatbdzg,3AJwUDP919kvQ9QcozQPxg,7oHpmG8BzdbW2RTWZn0srN,0VjIjW4GlUZAMYd2vXMi3b'
            ]
        ]);
        return $response->toArray()['tracks'];
    }

    public function  play(){
        $response = $this->request('PUT', "https://api.spotify.com/v1/me/player/play", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'uris' => ['spotify:track:' .$this->request->get('track_id')],
            ]
        ]);
        return $this->render('spotify/play.html.twig', [
            'track' => $this->request->get('track_id'),
        ]);
    }
}