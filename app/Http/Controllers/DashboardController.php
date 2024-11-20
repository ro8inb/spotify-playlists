<?php

namespace App\Http\Controllers;

use App\Services\PlaylistService;
use App\Services\TrackService;
use App\Services\RecommendationService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected PlaylistService $playlistService;
    protected TrackService $trackService;
    protected RecommendationService $recommendationService;

    public function __construct(PlaylistService $playlistService, TrackService $trackService, RecommendationService $recommendationService)
    {
        $this->playlistService = $playlistService;
        $this->trackService = $trackService;
        $this->recommendationService = $recommendationService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View  $view
     */
    public function index()
    {
        if (!Session::has('spotify_user')) {
            return view('/home');
        }
        $user = Session::get('spotify_user');
        Log::info('User {id} has loaded his discover playlist', [
            'id' => $user['id'],
        ]);
        $discoverPlaylist = $this->playlistService->getOrCreateDiscoverPlaylist();
        return view('dashboard', [
            'playlist' => $discoverPlaylist,
            'user' => $user,
        ]);
    }

    /**
     * Reload the Discover Playlist
     *  - Clear the playlist
     *  - Add the new tracks to the playlist
     *
     * @return \Illuminate\Http\JsonResponse  $response
     */
    public function reloadDiscoverPlaylist(HttpRequest $request)
    {
        if (!Session::has('spotify_user')) {
            return view('/home');
        }
        $user = Session::get('spotify_user');
        $discoverPlaylist = $this->playlistService->getOrCreateDiscoverPlaylist();
        $this->playlistService->clearPlaylist($discoverPlaylist['id']);
        $recommendations = $this->recommendationService->getRecommendations($request->all());
        $trackUris = [];
        foreach ($recommendations as $track) {
            $trackUris[] = 'spotify:track:' . $track['id'];
        }
        $this->playlistService->addTracksToPlaylist($discoverPlaylist['id'], $trackUris);
        Log::info('User {id} has reloaded the Discover Playlist', [
            'id' => $user['id'],
        ]);
        return response()->json([
            'success' => true,
            'message' => 'La Discover Playlist a été générée avec succès.'
        ]);
    }

    /**
     *  Search for tracks
     *
     * @return \Illuminate\Http\JsonResponse  $response
     */
    public function searchTrack(HttpRequest $request)
    {
        if (!Session::has('spotify_user')) {
            return view('/home');
        }

        $tracks = $this->trackService->searchTracks($request);

        return response()->json([
            'success' => true,
            'listTracks' => $tracks
        ]);
    }
}
