<?php

namespace Fnp\Module\Console;

use Fnp\Module\ModuleProvider;
use Fnp\Module\Services\ServiceProviderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class BuildModulePersistenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:module:persistence 
                            {--S|src= : Specify writable source folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Module Persistence IDE Helpers';

    /**
     * @var Collection
     */
    protected $persistencePaths;

    /**
     * @var string
     */
    protected $sourceFolder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->persistencePaths = new Collection();
    }

    /**
     * Execute the console command.
     *
     * @param ServiceProviderRepository $repository
     *
     * @return mixed
     */
    public function handle(ServiceProviderRepository $repository)
    {
        $this->sourceFolder = $this->option('src');
        $providers          = $repository->getModuleProviders();

        foreach ($providers as $provider)
            $this->processPersistence($provider);

        $this->buildPersistence();
    }

    protected function processPersistence(ModuleProvider $provider)
    {
        if (!method_exists($provider, 'persistenceFolders'))
            return;

        if (!class_exists('Barryvdh\\LaravelIdeHelper\\IdeHelperServiceProvider', TRUE))
            return;

        $folders = $provider->persistenceFolders();

        foreach ($folders as $folder) {
            $relativeFolder = str_replace(base_path() . '/', '', $folder);

            if ($this->sourceFolder && Str::startsWith($relativeFolder, $this->sourceFolder)) {
                $cmd = 'ide-helper:models --write  --reset --dir="' . $relativeFolder . '"';
                Artisan::call($cmd);
            } else {
                $this->persistencePaths->push($relativeFolder);
            }
        }
    }

    protected function buildPersistence()
    {
        $cmd = 'ide-helper:models --nowrite';

        foreach ($this->persistencePaths as $folder) {
            $cmd .= ' --dir="' . $folder . '"';
        }

        Artisan::call($cmd);
    }
}
