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

    public function setBuildInfo($key, $value)
    {
        Arr::set($this->buildInfo, $key, $value);
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
        $this->vueRootElement = $element;

        return $this;
    }

    public function addVueData($key, $value = NULL): FrontendModuleDefinition
    {
        $this->vueData[ $key ] = $value;

        return $this;
    }

    public function addVueComponent($name, $path): FrontendModuleDefinition
    {
        $this->vueComponents[ $name ] = $path;

        return $this;
    }

    public function addJs($path): FrontendModuleDefinition
    {
        $this->js[] = $path;

        return $this;
    }

    public function addCss($path): FrontendModuleDefinition
    {
        $this->css[] = $path;

        return $this;
    }

    public function addSass($path): FrontendModuleDefinition
    {
        $this->sass[] = $path;

        return $this;
    }

    public function addLess($path): FrontendModuleDefinition
    {
        $this->less[] = $path;

        return $this;
    }

    public function addImage($key, $path): FrontendModuleDefinition
    {
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
        return Config::get('module.path.resource') .
               '/' . $this->getModuleFileName($extension);
    }

    public function getRelativeTargetModuleFilePath($extension): string
    {
        return '.' . str_replace(base_path(), '', $this->getTargetModuleFilePath($extension));
    }
}