<x-layout>
    <div class="container mx-auto p-4 flex-grow h-screen flex flex-col items-center justify-center text-center">
        <h1 class="text-4xl font-bold mb-6">Explore de nouvelles recommandations musicales</h1>
        <p class="text-lg mb-8">Crée ta playlist Discover sur Spotify à partir d'un morceau de ton choix, sans doublon
            avec
            tes morceaux existants, et découvre de nouvelles pistes adaptées à tes préférences musicales.</p>
        <a href="{{ route('spotify.login') }}"
            class="spotify-button px-6 py-3 rounded-full text-lg font-semibold">
            Connecte-toi avec Spotify
        </a>
    </div>
</x-layout>
