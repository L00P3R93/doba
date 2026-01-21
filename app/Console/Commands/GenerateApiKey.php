<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiKey:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API key';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $key = Str::random(32);
        ApiKey::query()->create([
            'key' => $key,
            'name' => $this->argument('name'),
        ]);

        $this->info("API key {$key} generated successfully.");
    }
}
