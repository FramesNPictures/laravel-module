<?php

namespace Fnp\Module\Frontend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class FrontendModuleDefinition
{
    protected $name = 'app';

    /**
     * @var string
     */
    protected $vueRootElement;

    /**
     * @var array
     */
    protected $vueData = [];

    /**
     * @var array
     */
    protected $vueComponents = [];

    /**
     * @var array
     */
    protected $js = [];

    /**
     * @var array
     */
    protected $css = [];

    /**
     * @var array
     */
    protected $sass = [];

    /**
     * @var array
     */
    protected $less = [];

    /**
     * @var array
     */
    protected $images = [];

    /**
     * @var array
     */
    public $buildInfo = [];

    public function getBuildInfo($key, $default = NULL)
    {
        return Arr::get($this->buildInfo, $key, $default);
    }

    public function addBuildInfo($key, $value = TRUE)
    {
        Arr::set($this->buildInfo, $key, $value);
    }

    public function useAxios()
    {
        $this->addBuildInfo('axios');
        $this->addBuildInfo('js');

        return $this;
    }

    public function useBootstrap()
    {
        $this->addBuildInfo('bootstrap');
        $this->addBuildInfo('js');

        return $this;
    }

    public function merge(FrontendModuleDefinition $definition)
    {
        if ($definition->getVueRootElement())
            $this->setVueRootElement($definition->getVueRootElement());

        foreach ($definition->getVueComponents() as $key => $value)
            $this->addVueComponent($key, $value);

        foreach ($definition->getCss() as $path)
            $this->addCss($path);

        foreach ($definition->getSass() as $path)
            $this->addSass($path);

        foreach ($definition->getLess() as $path)
            $this->addLess($path);

        foreach ($definition->getJs() as $path)
            $this->addJs($path);

        foreach ($definition->getImages() as $key => $path)
            $this->addImage($key, $path);

        foreach ($definition->getVueData() as $key => $value)
            $this->addVueData($key, $value);
    }

    public function setName($name): FrontendModuleDefinition
    {
        $this->name = $name;

        return $this;
    }

    public function setVueRootElement($element): FrontendModuleDefinition
    {
        $this->addBuildInfo('vue');
        $this->addBuildInfo('js');

        $this->vueRootElement = $element;

        return $this;
    }

    public function addVueData($key, $value = NULL): FrontendModuleDefinition
    {
        $this->addBuildInfo('vue-data');
        $this->addBuildInfo('js');

        $this->vueData[ $key ] = $value;

        return $this;
    }

    public function addVueComponent($name, $path): FrontendModuleDefinition
    {
        $this->addBuildInfo('components');
        $this->addBuildInfo('js');

        $this->vueComponents[ $name ] = $path;

        return $this;
    }

    public function addJs($path): FrontendModuleDefinition
    {
        $this->addBuildInfo('js');

        $this->js[] = $path;

        return $this;
    }

    public function addCss($path): FrontendModuleDefinition
    {
        $this->addBuildInfo('css');

        $this->css[] = $path;

        return $this;
    }

    public function addSass($path): FrontendModuleDefinition
    {
        $this->addBuildInfo('sass');
        $this->addBuildInfo('css');

        $this->sass[] = $path;

        return $this;
    }

    public function addLess($path): FrontendModuleDefinition
    {
        $this->addBuildInfo('less');

        $this->less[] = $path;

        return $this;
    }

    public function addImage($key, $path): FrontendModuleDefinition
    {
        $this->addBuildInfo('images');

        $this->images[ $key ] = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getVueRootElement(): ?string
    {
        return $this->vueRootElement;
    }

    /**
     * @return array
     */
    public function getVueData(): array
    {
        return $this->vueData;
    }

    /**
     * @param bool $relative
     *
     * @return mixed
     */
    public function getVueComponents()
    {
        return $this->vueComponents;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getJs(): array
    {
        return $this->js;
    }

    /**
     * @return array
     */
    public function getCss(): array
    {
        return $this->css;
    }

    /**
     * @return array
     */
    public function getSass(): array
    {
        return $this->sass;
    }

    /**
     * @return array
     */
    public function getLess(): array
    {
        return $this->less;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function getModuleFileName($extension): string
    {
        return $this->getName() . '.' . $extension;
    }

    public function getTargetModuleFilePath($extension): string
    {
        return Config::get('module.path') .
               '/' . $this->getModuleFileName($extension);
    }

    public function getRelativeTargetModuleFilePath($extension): string
    {
        return '.' . str_replace(base_path(), '', $this->getTargetModuleFilePath($extension));
    }

    public function relative($filename): string
    {
        $rel = str_replace(base_path(), '', $filename);
        $up  = str_replace(base_path(), '', Config::get('module.path'));
        $up  = explode('/', $up);
        $up  = str_repeat('../', count($up) - 1);

        return str_replace('//', '/', $up . $rel);
    }
}