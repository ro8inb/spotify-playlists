<x-layout>
    <div class="container mx-auto p-4 flex-grow h-screen flex flex-col items-center justify-center text-center">
        <p class="text-4xl font-bold text-white mb-6">404</p>
        <p class="text-2xl text-gray-300 mb-8">Oups ! La page que tu cherches est introuvable.</p>
        <a href="{{ route('home') }}"
            class="spotify-button px-6 py-3 rounded-full text-lg font-semibold">
            Retour à l'accueil
        </a>
    </div>
</x-layout>
