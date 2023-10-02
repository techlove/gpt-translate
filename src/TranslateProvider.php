<?php

namespace Techlove\GptTranslate;

use Illuminate\Support\ServiceProvider;
use Techlove\GptTranslate\Console\TranslateExtract;
use Techlove\GptTranslate\Console\TranslateMake;
use Techlove\GptTranslate\Console\TranslateLang;

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
            $this->publishes([__DIR__ . '/../config/openai.php' => config_path('openai.php')], 'config');
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
