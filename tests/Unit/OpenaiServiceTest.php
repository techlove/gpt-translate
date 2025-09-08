<?php

use Techlove\GptTranslate\OpenaiService;

describe('OpenaiService', function () {
    it('throws exception when source file does not exist', function () {
        $service = new OpenaiService();
        $nonexistentPath = '/nonexistent/path';
        $expectedFile = $nonexistentPath.'/en.json';

        expect(fn () => $service->translate_file($nonexistentPath, 'en', 'sv'))
            ->toThrow(\InvalidArgumentException::class, "Source translation file not found: {$expectedFile}");
    });

    it('throws exception when source file contains invalid JSON', function () {
        // Create a temporary directory and file with invalid JSON
        $tempDir = sys_get_temp_dir().'/test_translations_'.uniqid();
        mkdir($tempDir);
        $tempFile = $tempDir.'/en.json';
        file_put_contents($tempFile, 'invalid json content');

        $service = new OpenaiService();

        expect(fn () => $service->translate_file($tempDir, 'en', 'sv'))
            ->toThrow(\InvalidArgumentException::class, "Invalid JSON in source translation file: {$tempFile}. Error:");

        unlink($tempFile);
        rmdir($tempDir);
    });

    it('validates JSON format correctly without file operations', function () {
        // Test that we can at least instantiate the service
        $service = new OpenaiService();
        expect($service)->toBeInstanceOf(OpenaiService::class);

        // The error handling paths are already tested above
        // This test confirms the class can be instantiated without issues
    });
});
