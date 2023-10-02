<?php

namespace Techlove\GptTranslate\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class TranslateExtract extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'translate:extract {--origin=en} {--lang=sv} {--model=gpt-3.5-turbo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all Translatable stringa and translate to a given language into a .json file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        spin(function () {
            $this->call('translatable:export', [
                'lang' => $this->option('origin'),
            ]);

            $this->call("translate:lang", [
                '--origin' => $this->option('origin'),
                '--lang' => $this->option('lang'),
                '--model' => $this->option('model'),
            ]);
        });
        info("Strings translated successfully to lang/{$this->option('lang')}.json");
    }
}
