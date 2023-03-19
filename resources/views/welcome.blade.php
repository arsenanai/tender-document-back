<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config('app.name', 'Refer to readme.md')}}</title>
        <link rel="icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-32x32.png" sizes="32x32">
        <link rel="icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-192x192.png" sizes="192x192">
        <link rel="apple-touch-icon" href="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-180x180.png">
        <meta name="msapplication-TileImage" content="/cropped-STEAM_RGB-без_дескриптора_сайтқа-1536x523-1-270x270.png">
        <style>
            html, body, #entries-app, .with-background {
                height: 100%;
            }
            body {
                background-repeat: no-repeat;
                background-position: left bottom;
                background-size: cover;
                width: 100%;
            }
        </style>
    </head>
    <body class="text-my-indigo">
        <div id="entries-app"></div>
        @vite(['resources/js/app.js'])
    </body>
</html>