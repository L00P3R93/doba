<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Streaming Provider
    |--------------------------------------------------------------------------
    |
    | The primary streaming provider used for movie and TV show playback.
    | Supported: 'vixsrc', 'vidfast', 'vidking'
    |
    */

    'primary' => env('STREAMING_PROVIDER', 'vixsrc'),

    /*
    |--------------------------------------------------------------------------
    | Provider Configurations
    |--------------------------------------------------------------------------
    |
    | Each provider defines its base URL, path templates for movies and TV
    | shows, and default query parameters appended to every embed URL.
    | The {id}, {season}, and {episode} placeholders are replaced at runtime.
    |
    */

    'providers' => [

        'vixsrc' => [
            'name' => 'VixSRC',
            'base_url' => env('VIXSRC_BASE_URL', 'https://vixsrc.to'),
            'movie_path' => '/movie/{id}',
            'tv_path' => '/tv/{id}/{season}/{episode}',
            'params' => [
                'primaryColor' => 'ff6b1a',
                'autoplay' => 'false',
            ],
        ],

        'vidfast' => [
            'name' => 'VidFast',
            'base_url' => env('VIDFAST_BASE_URL', 'https://vidfast.pro'),
            'movie_path' => '/movie/{id}',
            'tv_path' => '/tv/{id}/{season}/{episode}',
            'params' => [
                'theme' => 'ff6b1a',
                'autoPlay' => 'false',
            ],
        ],

        'vidking' => [
            'name' => 'VidKing',
            'base_url' => env('VIDKING_BASE_URL', 'https://www.vidking.net'),
            'movie_path' => '/embed/movie/{id}',
            'tv_path' => '/embed/tv/{id}/{season}/{episode}',
            'params' => [
                'color' => 'ff6b1a',
                'autoPlay' => 'false',
            ],
        ],

    ],

];
