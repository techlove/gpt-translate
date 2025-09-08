<?php

use Illuminate\Support\Facades\Artisan;

describe('Console Commands', function () {
    it('registers translate:make command', function () {
        $commands = Artisan::all();

        expect($commands)->toHaveKey('translate:make');
    });

    it('registers translate:lang command', function () {
        $commands = Artisan::all();

        expect($commands)->toHaveKey('translate:lang');
    });

    it('registers translate:extract command', function () {
        $commands = Artisan::all();

        expect($commands)->toHaveKey('translate:extract');
    });
});
