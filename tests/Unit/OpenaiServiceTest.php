<?php

use Techlove\GptTranslate\OpenaiService;

describe('OpenaiService', function () {
    it('throws exception when source file does not exist', function () {
        $service = new OpenaiService;
        
        expect(fn() => $service->translate_file('/nonexistent/path', 'en', 'sv'))
            ->toThrow(\InvalidArgumentException::class, 'Source translation file not found');
    });
    
    it('throws exception when source file contains invalid JSON', function () {
        // Create a temporary directory and file with invalid JSON
        $tempDir = sys_get_temp_dir().'/test_translations_'.uniqid();
        mkdir($tempDir);
        $tempFile = $tempDir.'/en.json';
        file_put_contents($tempFile, 'invalid json content');
        
        $service = new OpenaiService;
        
        expect(fn() => $service->translate_file($tempDir, 'en', 'sv'))
            ->toThrow(\InvalidArgumentException::class, 'Invalid JSON');
        
        unlink($tempFile);
        rmdir($tempDir);
    });
});