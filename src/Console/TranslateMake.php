<?php

namespace Techlove\GptTranslate\Console;

use Illuminate\Console\Command;
use Techlove\GptTranslate\FileService;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class TranslateMake extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'translate:make {--lang=} {--path=resources/lang}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a json file with all strings to translate';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        spin(function () {
            $service = new FileService();
            $service->strings_file($this->option('lang') ?? "en", $this->option('path') ?? base_path("lang"));
        }, "Creating {$this->option('lang')}.json file in {$this->option('path')} directory...");
        info("File created successfully!");
    }
}
