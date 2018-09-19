<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Schema\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Builder $schema)
    {
        // исправление ошибки связанной с utf8mb4 которая появляется, начиная с Laravel 5.4
        $schema->defaultStringLength(191);
        
        // добавление blade-дериктивы для удобного подключения js-библиотек 
        Blade::directive('jscode', function ($expression) {
            $templ      = '<script src="%s"></script>';
            $exp        = trim($expression,"'");
            $filePath   = asset('/js/views/'.$exp);
            return sprintf('<script src="%s.js"></script>', $filePath);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
