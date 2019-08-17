<?php

namespace Fnp\Module\Console;

use Cni\Utils\Dumper;
use Fnp\Module\Frontend\FrontendModuleDefinition;
use Fnp\Module\ModuleProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class ModuleBuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build modules';

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
    public function handle()
    {
        $this->buildFolder();
        $this->iterateServiceProviders();
        $this->dumpFiles();
    }

    protected function buildFolder()
    {
        $buildFolder = resource_path('module');

        if (!file_exists($buildFolder)) {
            $this->info('Creating module resource folder...');
            mkdir($buildFolder);
        }

        if (!file_exists($buildFolder . '/.gitignore')) {
            $this->info('Creating gitignore file...');

            file_put_contents(
                $buildFolder . '/.gitignore',
                '!.gitignore' . PHP_EOL . '*'
            );
        }
    }

    protected function iterateServiceProviders()
    {
        $providers = $this->getServiceProviders();

        foreach ($providers as $provider) {

            if (!$provider instanceof ModuleProvider)
                continue;

            $this->processFrontend($provider);
        }

        foreach ($this->frontendModules as $definition)
            $this->buildFrontend($definition);
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

        if ($definition->getVueRootElement() ||
            $definition->getVueComponents() ||
            count($definition->getJs())) {
            $definition->setBuildInfo('hasJs', TRUE);
            $this->buildJs($definition);
        }

        if (count($definition->getCss()) || count($definition->getSass())) {
            $definition->setBuildInfo('hasCss', TRUE);
            $this->buildCss($definition);
        }

        $this->buildMix();
    }

    protected function buildCss(FrontendModuleDefinition $definition)
    {
        $content = View::make('fnp-module::frontend.css', compact('definition'));

        $this->frontendFiles->put(
            $definition->getTargetModuleFilePath('sass'),
            $content->render()
        );
    }

    protected function buildMix()
    {
        $definitions = $this->frontendModules;
        $filename    = $this->modulePath . '/mix.js';

        $this->frontendFiles->put(
            $filename,
            View::make('fnp-module::frontend.mix', compact('definitions'))->render()
        );
    }

    protected function buildJs(FrontendModuleDefinition $definition)
    {
        $content = View::make('fnp-module::frontend.js', compact('definition'));

        $this->frontendFiles->put(
            $definition->getTargetModuleFilePath('js'),
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

    protected function getServiceProviders()
    {
        $app = new \ReflectionClass($this->getApplication()->getLaravel());
        $pro = $app->getProperty('serviceProviders');
        $pro->setAccessible(TRUE);
        $val = $pro->getValue($this->getApplication()->getLaravel());

        return $val;
    }
}
