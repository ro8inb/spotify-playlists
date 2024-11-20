<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class TokenService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /*
     * Refresh the access token if it's expired
     */
    public function refreshSpotifyToken()
    {
        $spotifyUser = Session::get('spotify_user');
        $basicAuth = base64_encode(config('services.spotify.client_id') . ':' . config('services.spotify.client_secret'));

        if (now()->greaterThan($spotifyUser['expires_in'])) {
            $response = $this->client->post('https://accounts.spotify.com/api/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . $basicAuth,
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $spotifyUser['refresh_token'],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $spotifyUser['access_token'] = $data['access_token'];
            $spotifyUser['expires_in'] = now()->addSeconds($data['expires_in']);
            Session::put('spotify_user', $spotifyUser);
        }
    }

    /*
     * Get the access token
     * If it's expired, refresh it
     */
    public function getAccessToken()
    {
        $this->refreshSpotifyToken();
        return Session::get('spotify_user')['access_token'];
    }
}
