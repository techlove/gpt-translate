<?php

namespace Techlove\GptTranslate\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

use Techlove\GptTranslate\OpenaiService;

class TranslateLang extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'translate:lang {--origin=} {--lang=} {--context=} {--model=gpt-4o} {--exclude=} {--path=resources/lang}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a json file with all strings to translate to a given language';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        spin(function () {
            $context = $this->option('context') ?? config('gpt-translate.default_context', '');
            $model = $this->option('model') ?? 'gpt-4o';
            $exclude = $this->getExcludedWords();

            if (! empty($exclude)) {
                $excludeText = "IMPORTANT: Never translate the following words or phrases: '".implode("', '", $exclude)."'. These should always remain in their original form.";
                $context .= "\n\n".$excludeText;
            }

            $service = new OpenaiService;
            $service->translate_file(
                $this->option('path') ?? base_path('lang'),
                $this->option('origin') ?? 'en',
                $this->option('lang') ?? 'sv',
                $context,
                $model
            );
        }, 'Translating strings...');
        info('Strings translated successfully!');
    }

    /**
     * Get the excluded words from command option or configuration.
     */
    private function getExcludedWords(): array
    {
        $rawWords = [];
        
        // Check if exclude option was provided via command line
        if ($this->option('exclude')) {
            $rawWords = explode(',', $this->option('exclude'));
        } elseif (config('gpt-translate.exclude_words')) {
            // Fall back to configuration file setting
            $rawWords = explode(',', config('gpt-translate.exclude_words'));
        }

        // Sanitize and filter the words
        return array_values(array_filter(array_map(function ($word) {
            // Trim whitespace
            $word = trim($word);
            // Sanitize: only allow alphanumeric characters, spaces, hyphens, and underscores
            $word = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $word);
            // Remove extra spaces
            $word = preg_replace('/\s+/', ' ', $word);
            return trim($word);
        }, $rawWords), function ($word) {
            // Filter out empty strings
            return !empty($word);
        }));
    }
}
