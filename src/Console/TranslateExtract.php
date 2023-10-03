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
    protected $signature = 'translate:extract {--origin=en} {--lang=sv} {--model=gpt-3.5-turbo} {--path=}';

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
            $this->call('translate:make', [
                '--lang' => $this->option('origin'),
                '--path' => $this->option('path') ?? base_path('lang')
            ]);

            $this->call("translate:lang", [
                '--origin' => $this->option('origin'),
                '--lang' => $this->option('lang'),
                '--model' => $this->option('model'),
                '--path' => $this->option('path') ?? base_path('lang')
            ]);
        });
        info("Strings translated successfully to lang/{$this->option('lang')}.json");
    }
}
