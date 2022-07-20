<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('not_contains', function($attribute, $value, $parameters){
            // Banned words
            $words = array('Free', 'Offer', 'Book', 'Website');
            foreach ($words as $word){
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });
    }
}
