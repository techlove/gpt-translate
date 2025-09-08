<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Techlove\GptTranslate\TranslateProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TranslateProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up test environment
    }
}