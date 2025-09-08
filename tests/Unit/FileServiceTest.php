<?php

use Techlove\GptTranslate\FileService;

describe('FileService', function () {
    it('creates a file service instance', function () {
        $service = new FileService();

        expect($service)->toBeInstanceOf(FileService::class);
    });

    it('can extract translation patterns from content', function () {
        $service = new FileService();

        // Create a temporary file with translation strings
        $testContent = '<?php echo __("Hello World"); echo trans("Another string"); ?>';
        $tempFile = tempnam(sys_get_temp_dir(), 'test_translation');
        file_put_contents($tempFile, $testContent);

        $strings = $service->get_strings_in_file($tempFile);

        expect($strings)->toContain('Hello World', 'Another string');

        unlink($tempFile);
    });
});
