<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class TrackService
{
    protected $client;
    protected $tokenService;
    protected $playlistService;

    public function __construct(Client $client, TokenService $tokenService, PlaylistService $playlistService)
    {
        $this->client = $client;
        $this->tokenService = $tokenService;
        $this->playlistService = $playlistService;
    }

    /*
     * Get all tracks from a playlist
     */
    public function getPlaylistTracks($playlistId)
    {
        $accessToken = $this->tokenService->getAccessToken();
        $limit = 100;
        $offset = 0;
        $allTracks = [];

        do {
            $response = $this->client->get(config('services.spotify.base_url') . "playlists/{$playlistId}/tracks", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'query' => [
                    'limit' => $limit,
                    'offset' => $offset,
                ],
            ]);

            $tracks = json_decode($response->getBody(), true);
            $allTracks = array_merge($allTracks, $tracks['items']);
            $offset += $limit;
        } while (count($tracks['items']) == $limit);

        return $allTracks;
    }

    /*
     * Get all track ids from playlists
     */
    public function getAllTrackIdsFromPlaylists()
    {
        $spotifyUser = Session::get('spotify_user');
        $cachedTracks = Cache::get('spotify_tracks_' . $spotifyUser['id']);

        if ($cachedTracks) {
            return $cachedTracks;
        }

        $playlists = $this->playlistService->getUserPlaylists();
        $trackIds = [];

        foreach ($playlists['items'] as $playlist) {
            $tracks = $this->getPlaylistTracks($playlist['id']);
            foreach ($tracks as $track) {
                $trackIds[] = $track['track']['id'];
            }
        }

        Cache::put('spotify_tracks_' . $spotifyUser['id'], $trackIds, now()->addDay());
        return $trackIds;
    }

    /*
     * Search for a track
     */
    public function searchTracks(Request $request)
    {
        if (!Session::has('spotify_user')) {
            return view('/home');
        }
        $accessToken = $this->tokenService->getAccessToken();
        $response = $this->client->get(config('services.spotify.base_url') . 'search', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'query' => [
                'q' => $request->query()['nameTrack'],
                'type' => 'track',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
