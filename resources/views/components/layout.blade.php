<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Discover playlist</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
    <script src="https://open.spotify.com/embed/iframe-api/v1" async></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @routes
</head>

<body class="bg-gray-900 text-white flex flex-col spotify-bg h-[100vh]">
    <div class="flex-grow w-full flex flex-col justify-between items-center p-2">
        {{ $slot }}
    </div>

    <footer class="text-sm text-gray-300 mb-2 mx-auto">
        &copy; 2024 Spotify Discover Playlist
    </footer>
    <script></script>
</body>

</html>
