<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="w-100 h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config('app.name', 'Refer to readme.md')}}</title>
        <link rel="icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-32x32.png" sizes="32x32">
        <link rel="icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-192x192.png" sizes="192x192">
        <link rel="apple-touch-icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-180x180.png">
        <meta name="msapplication-TileImage" content="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-270x270.png">
    </head>
    <body class="bg-light w-100 h-100">
        <div id="entries-app" class="w-100 h-100"></div>
        @vite(['resources/js/app.js'])
    </body>
</html>