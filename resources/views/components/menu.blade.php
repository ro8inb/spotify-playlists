<nav class="relative">
    <div class="max-w-screen-xl flex justify-end p-3">
        <!-- Bouton hamburger -->
        <button id="menu-toggle" data-collapse-toggle="navbar-hamburger" type="button" aria-controls="navbar-hamburger"
            aria-expanded="false"
            class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-gray-500  rounded-lg dark:text-gray-400">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
    </div>

    <!-- Menu déroulant -->
    <div id="navbar-hamburger"
        class="absolute right-4 top-16 hidden w-48 bg-gray-700 shadow-lg dark:bg-gray-800 z-50">
        <ul class="flex flex-col font-medium">
            <li>
                <a href="{{ route('spotify.logout') }}"
                    class="block py-4 px-4 text-gray-300 hover:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">Me
                    déconnecter</a>
            </li>
        </ul>
    </div>
</nav>
