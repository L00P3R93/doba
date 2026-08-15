<?php

namespace App\Facades;

use App\Services\TMDBService;
use Illuminate\Support\Facades\Facade;

/**
 * @see TMDBService
 */
class TMDB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TMDBService::class;
    }

}
