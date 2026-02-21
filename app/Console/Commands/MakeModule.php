<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create full module (Model, Controller, Requests, Policy, Observer, Resource)';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $this->info('Creating module "'.$name.'"');

        // Model + Migration + Factory + Seeder + Policy + Resource Controller
        $this->call('make:model', [
            'name' => $name,
            '--migration' => true,
            '--factory' => true,
            '--seed' => true,
            '--policy' => true,
            '--controller' => true,
            '--resource' => true,
        ]);

        // API Controller
        $this->call('make:controller', [
            'name' => "Api/{$name}Controller",
            '--api' => true,
            '--model' => $name,
        ]);

        // Requests
        $this->call('make:request', [
            'name' => "Store{$name}Request",
        ]);

        $this->call('make:request', [
            'name' => "Update{$name}Request",
        ]);

        // Observer
        $this->call('make:observer', [
            'name' => "{$name}Observer",
            '--model' => $name,
        ]);

        // API Resource
        $this->call('make:resource', [
            'name' => "{$name}Resource",
        ]);

        $this->info("Module {$name} created successfully.");
    }
}
