<x-layout>
    <div class="flex items-center justify-center min-h-screen flex-grow">
        <div class="flex items-center flex-col">
            <div class="loader mb-4"></div>
            <div class="text-center">
                <p class="text-lg font-semibold">Analyse de tes playlists en cours... <br> Merci de patienter.</p>
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/js/loading.js', 'resources/css/loading.css'])

</x-layout>
