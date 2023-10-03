<?php

namespace Techlove\GptTranslate\Console;

use Illuminate\Console\Command;
use Techlove\GptTranslate\OpenaiService;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class TranslateLang extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'translate:lang {--origin=} {--lang=} {--context=} {--model=gpt-3.5-turbo} {--path=resources/lang}';

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
            $service = new OpenaiService();
            $service->translate_file($this->option('path') ?? base_path("lang"), $this->option('origin') ?? "en", $this->option('lang') ?? "sv", $this->option('context') ?? "", $this->option('model') ?? "gpt-3.5-turbo");
        }, 'Translating strings...');
        info("Strings translated successfully!");
    }
}
