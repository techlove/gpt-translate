<?php

namespace Rdosgroup\GptTranslate\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;

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
        info('Creating translation file for ' . $this->option('lang') . ' at ' . Carbon::now()->toDateTimeString());
        info('Processing... Please wait.');
        try {
            $this->call('translatable:export en');
            info('Translation file created successfully at ' . Carbon::now()->toDateTimeString());

            info('Starting translation at ' . Carbon::now()->toDateTimeString());
            $this->call("translate:lang", [
                '--model' => $this->option('model'),
                '--lang' => $this->option('lang'),
                '--origin' => $this->option('origin')
            ]);
            info('Translation finished successfully at ' . Carbon::now()->toDateTimeString());
        } catch (\Throwable $th) {
            $this->stopSpinner();
            $this->error($th->getMessage());
        }
    }
}
