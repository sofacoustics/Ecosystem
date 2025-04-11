<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-file {filename : The file name to save to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a file (for testing)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
			// create a file here
			//jw:todo it is possible to add this to a queue, which might
			//jw:todo be a good idea for longer jobs
			$tdir=storage_path('app/public/test');
			exec("mkdir -p $tdir");
			exec("octave-cli octave/generate-file.m " . $tdir . "/" . $this->argument('filename'));
    }
}
