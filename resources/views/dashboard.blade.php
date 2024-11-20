<x-layout>
    <div class="container mx-auto p-1 flex-grow flex flex-col justify-between h-full">
        <div class="flex flex-col justify-between">
            <div class="flex flex-row justify-between items-center">
                <h1 class="text-3xl font-extrabold">Bienvenue {{ $user['name'] }}</h1>
                <x-menu />
            </div>
            <p class="mb-2 sm:mb-5 text-sm">Crées une playlist de recommandations basée sur un morceau de ton choix, avec
                la
                garantie que
                chaque titre proposé sera une véritable découverte, sans répétitions de morceaux présents dans tes
                playlists
                existantes. <br>
                Personnalises les suggestions musicales selon différents critères : énergie, popularité,
                acousticité, et
                bien plus encore. <br>
                <strong>Laisse-toi surprendre par des recommandations sur mesure !</strong>
            </p>
        </div>
        <div id="spotify-iframe-wrapper" class="flex flex-grow w-full relative">
            <iframe id="spotify-playlist" src="https://open.spotify.com/embed/playlist/{{ $playlist['id'] }}"
                frameborder="0" allowtransparency="true" allow="encrypted-media" class="absolute inset-0 w-full h-full">
            </iframe>
        </div>

        <div class="flex w-full justify-center">
            <button id="generate-playlist"
                class="spotify-button px-6 mt-2 py-3 mb-1 rounded-full text-lg font-semibold">
                Générer la Playlist Discover
            </button>
        </div>

        <div id="playlist-modal" class="fixed inset-0 hidden z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-gray-700 shadow-2xl w-full max-w-lg p-6 text-white">
                    <h2 class="text-3xl font-bold mb-6 text-white">Paramètres de la Playlist</h2>

                    <form id="playlist-settings-form">
                        <!-- Recherche de morceaux -->
                        <div class="mb-6 relative">
                            <label for="search_tracks" class="block text-sm font-medium mb-2">
                                Recherche de morceaux
                                <span class="fas fa-info-circle fa-lg ml-2 text-gray-400 cursor-pointer"
                                    data-tippy-content="Saisissez du texte pour rechercher des morceaux dans Spotify"></span>
                            </label>
                            <input type="text" id="search_tracks" name="search_tracks"
                                class="block w-full p-2 bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-0"
                                placeholder="Saisissez un morceau à rechercher..." autocomplete="off">
                            <input type="text" id="search_tracks_input_id" name="search_tracks_input_id" hidden>

                            <ul id="suggestionsList"
                                class="absolute z-10 bg-gray-800 border-0 border-gray-600 w-full max-h-48 overflow-y-auto mt-1">
                            </ul>
                        </div>

                        <p class="text-gray-400 mb-4">Personnalisez les recommandations selon vos critères :</p>

                        <div class="space-y-6">
                            <!-- Champ Énergie -->
                            <x-checkbox-range-input label="Énergie" id="target_energy" name="target_energy"
                                min="0" max="1" step="0.1" checked="true"
                                tooltip="Définit l'énergie cible pour les morceaux recommandés." />
                            <!-- Champ Acousticité -->
                            <x-checkbox-range-input label="Acousticité" id="target_acousticness"
                                name="target_acousticness" min="0" max="1" step="0.1" checked="false"
                                tooltip="Définit la proportion d'acousticité des morceaux." />
                            <x-multi-range-input label="Popularité" id="popularity_range" name="popularity_range"
                                min="0" max="100" step="1" checked="false"
                                tooltip="Choisissez une plage de popularité des morceaux." firstRangeId="min_popularity"
                                secondRangeId="max_popularity" firstRangeName="min_popularity"
                                secondRangeName="max_popularity" />

                            <!-- Champ Instrumentalité -->
                            <x-checkbox-range-input label="Instrumentalité" id="target_instrumentalness"
                                name="target_instrumentalness" min="0" max="1" step="0.1"
                                checked="false" tooltip="Permet de cibler des morceaux plus ou moins instrumentaux." />

                            <!-- Champ Valence -->
                            <x-checkbox-range-input label="Valence" id="target_valence" name="target_valence"
                                min="0" max="1" step="0.1" checked="false"
                                tooltip="Indique le caractère émotionnel des morceaux." />

                            <!-- Champ Tempo minimal -->
                            <x-checkbox-range-input label="Tempo minimal" id="min_tempo" name="min_tempo" min="0"
                                max="300" step="1" checked="false"
                                tooltip="Définit le tempo minimal des morceaux en BPM." />
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="mt-8 flex justify-center space-x-4">
                            <button type="button" id="close-modal"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md transition hover:bg-gray-600">Annuler</button>
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded-md transition hover:bg-green-600">Générer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </div>

    @vite(['resources/js/generate-playlist.js', 'resources/css/modale.css'])
</x-layout>
