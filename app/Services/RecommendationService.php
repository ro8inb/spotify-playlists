<?php

namespace App\Services;

use GuzzleHttp\Client;

class RecommendationService
{
    protected $client;
    protected $tokenService;
    protected $trackService;

    public function __construct(Client $client, TokenService $tokenService, TrackService $trackService)
    {
        $this->client = $client;
        $this->tokenService = $tokenService;
        $this->trackService = $trackService;
    }

    /*
     * Get recommendations based on the params and excluding the tracks already presents in the user's playlists
     */
    public function getRecommendations(array $params)
    {
        $accessToken = $this->tokenService->getAccessToken();
        $trackIds = $this->trackService->getAllTrackIdsFromPlaylists();

        $response = $this->client->get(config('services.spotify.base_url') . 'recommendations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'query' => [
                'seed_tracks' => $params['search_tracks_input_id'] ?? '',
                'target_energy' => $params['target_energy'] ?? null,
                'target_acousticness' => $params['target_acousticness'] ?? null,
                'min_popularity' => $params['min_popularity'] ?? null,
                'max_popularity' => $params['max_popularity'] ?? null,
                'target_instrumentalness' => $params['target_instrumentalness'] ?? null,
                'target_valence' => $params['target_valence'] ?? null,
                'min_tempo' => $params['min_tempo'] ?? null,
                'limit' => 100,
                'market' => 'FR'
            ]
        ]);

        $recommendations = json_decode($response->getBody(), true)['tracks'];

        $finalRecommendations = [];
        foreach ($recommendations as $track) {
            if (!in_array($track['id'], $trackIds)) {
                $finalRecommendations[] = $track;
            }

            if (count($finalRecommendations) >= 50) {
                break;
            }
        }
        return array_slice($finalRecommendations, 0, 50);
    }
}
