<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    if (Session::has('spotify_user')) {
        return redirect('/dashboard');
    }

    return view('home');
})->name('home');

Route::get('/login/spotify', function () {
    return Socialite::driver('spotify')
        ->scopes(['user-library-read', 'playlist-read-private', 'playlist-read-collaborative', 'playlist-modify-private', 'playlist-modify-public'])
        ->with(['show_dialog' => 'true'])
        ->redirect();
})->name('spotify.login');

Route::get('/logout', function () {
    if (Session::has('spotify_user')) {
        $user = Session::get('spotify_user');
        $cacheKey = 'spotify_tracks_' . $user['id'];
        Cache::forget($cacheKey);
        Session::forget('spotify_user');
        return redirect('/');
    }

    return view('/');
})->name('spotify.logout');

Route::get('/callback/spotify', function () {
    if (Session::has('spotify_user')) {
        Session::forget('spotify_user');
        return redirect('/login/spotify');
    }

    $user = Socialite::driver('spotify')->user();
    $cacheKey = 'spotify_tracks_' . $user['id'];
    Cache::forget($cacheKey);
    Session::put('spotify_user', [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $user->avatar,
        'access_token' => $user->token,
        'refresh_token' => $user->refreshToken,
        'expires_in' => now()->addSeconds($user->expiresIn),
    ]);
    Log::info('User {id} has been connected to Spotify', [
        'id' => $user['id'],
    ]);
    return view('loading');
});

Route::get('/process-spotify-data', function () {
    try {
        $trackService = app(App\Services\TrackService::class);
        $trackIds = $trackService->getAllTrackIdsFromPlaylists();

        $user = Session::get('spotify_user');
        Cache::put('spotify_tracks_' . $user['id'], $trackIds, now()->addDay());
        Log::info('Tracks of user {id} have been registered in the cache', [
            'id' => $user['id'],
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
})->name('processing.data');

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::post('/generate-playlist', [DashboardController::class, 'reloadDiscoverPlaylist'])->name('generate.playlist');

Route::get('/search-track', [DashboardController::class, 'searchTrack'])->name('search.tracks');

Route::fallback(function() {
    return view('404');
 });
