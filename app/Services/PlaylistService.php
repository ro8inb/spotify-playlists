<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class PlaylistService
{
    protected $client;
    protected $tokenService;

    public function __construct(Client $client, TokenService $tokenService)
    {
        $this->client = $client;
        $this->tokenService = $tokenService;
    }

    /*
     * Get all playlists of the user
     */
    public function getUserPlaylists()
    {
        $accessToken = $this->tokenService->getAccessToken();
        $response = $this->client->get(config('services.spotify.base_url') . "me/playlists", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getOrCreateDiscoverPlaylist()
    {
        $playlists = $this->getUserPlaylists();
        $spotifyUser = Session::get('spotify_user');

        foreach ($playlists['items'] as $playlist) {
            if ($playlist['name'] == 'Discover Playlist' && $playlist['owner']['id'] == $spotifyUser['id']) {
                return $playlist;
            }
        }

        return $this->createDiscoverPlaylist($spotifyUser['id']);
    }

    /*
     * Create the discover playlist if it doesn't exist
     */
    private function createDiscoverPlaylist($userId)
    {
        $accessToken = $this->tokenService->getAccessToken();
        $response = $this->client->post(config('services.spotify.base_url') . "users/{$userId}/playlists", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => 'Discover Playlist',
                'description' => 'Playlist Discover pour découvrir de nouveaux morceaux',
                'public' => false,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /*
     * Add tracks to a playlist
     */
    public function addTracksToPlaylist($playlistId, $trackUris)
    {
        $accessToken = $this->tokenService->getAccessToken();
        $this->client->post(config('services.spotify.base_url') . "playlists/{$playlistId}/tracks", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'uris' => $trackUris,
            ],
        ]);
    }

    /*
     * Clear a playlist
     */
    public function clearPlaylist($playlistId)
    {
        $accessToken = $this->tokenService->getAccessToken();
        $response = $this->client->get(config('services.spotify.base_url') . "playlists/{$playlistId}/tracks", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        $tracks = json_decode($response->getBody(), true);
        $trackUris = array_map(function ($item) {
            return ['uri' => $item['track']['uri']];
        }, $tracks['items']);

        if (!empty($trackUris)) {
            $this->client->delete(config('services.spotify.base_url') . "playlists/{$playlistId}/tracks", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'tracks' => $trackUris,
                ],
            ]);
        }
    }
}
