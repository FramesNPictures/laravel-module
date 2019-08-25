<?php

namespace Fnp\Module\Console;

use Cni\Utils\Dumper;
use Fnp\Module\Definitions\FrontendModuleDefinition;
use Fnp\Module\ModuleProvider;
use Fnp\Module\Services\ServiceProviderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class BuildModuleFrontendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:module:frontend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Frontend Module Files';

    /**
     * @var Collection
     */
    protected $frontendModules;

    /**
     * @var Collection
     */
    protected $frontendFiles;

    /**
     * @var string
     */
    protected $modulePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->frontendModules = new Collection();
        $this->frontendFiles   = new Collection();
        $this->modulePath      = config('module.path', resource_path('module'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ServiceProviderRepository $repository)
    {
        $this->buildFolder();

        $providers = $repository->getModuleProviders();

        foreach ($providers as $provider)
            $this->processFrontend($provider);


        foreach ($this->frontendModules as $definition)
            $this->buildFrontend($definition);

        $this->dumpFiles();
    }

    protected function buildFolder()
    {
        if (!file_exists($this->modulePath)) {
            $this->info('Creating module resource folder...');
            mkdir($this->modulePath);
        }
    }

    protected function processFrontend(ModuleProvider $provider)
    {
        if (!method_exists($provider, 'frontend'))
            return;

        $definition = new FrontendModuleDefinition();
        $provider->frontend($definition);

        if (!$this->frontendModules->has($definition->getName())) {
            $this->frontendModules->put($definition->getName(), $definition);
        } else {
            /** @var FrontendModuleDefinition $existing */
            $existing = $this->frontendModules->get($definition->getName());
            $existing->merge($definition);
        }
    }

    protected function buildFrontend(FrontendModuleDefinition $definition)
    {
        Dumper::dump($definition->getName(), 'M');

        $this->build('bootstrap', 'bootstrap', 'js', $definition);
        $this->build('axios', 'axios', 'js', $definition);
        $this->build('vue', 'vue', 'js', $definition);
        $this->build('components', 'components', 'js', $definition);
        $this->build('js', 'js', 'js', $definition);
        $this->build('css', 'css', 'scss', $definition);
        // $this->build(NULL, 'mix', 'js', $definition);
    }

    protected function build($check, $name, $extension, FrontendModuleDefinition $definition)
    {
        if ($check && !$definition->getBuildInfo($check))
            return;

        $content = View::make('fnp-module::frontend.' . $name, compact('definition'));

        $this->frontendFiles->put(
            $definition->getTargetModuleFilePath($name . '.' . $extension),
            $content->render()
        );
    }

    protected function dumpFiles()
    {
        foreach ($this->frontendFiles as $file => $content) {
            Dumper::dump($file, '>');
            file_put_contents($file, $content);
        }
    }
}