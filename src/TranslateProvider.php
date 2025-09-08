<?php

namespace Techlove\GptTranslate;

use Illuminate\Support\ServiceProvider;
use Techlove\GptTranslate\Console\TranslateExtract;
use Techlove\GptTranslate\Console\TranslateLang;
use Techlove\GptTranslate\Console\TranslateMake;

class TranslateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/gpt-translate.php' => config_path('gpt-translate.php'),
            ], 'config');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                TranslateMake::class,
                TranslateLang::class,
                TranslateExtract::class,
            ]);
        }
    }
}
