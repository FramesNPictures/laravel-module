<?php

namespace Fnp\Module\Services;

use Fnp\Module\ModuleProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class ServiceProviderRepository
{
    public function getModuleProviders()
    {
        $modules = new Collection();

        foreach($this->getServiceProviders() as $provider)
            if ($provider instanceof ModuleProvider)
                $modules->push($provider);

        return $modules;
    }

    /**
     * @return Collection
     * @throws \ReflectionException
     */
    public function getServiceProviders()
    {
        $app = new \ReflectionClass(Application::getInstance());
        $pro = $app->getProperty('serviceProviders');
        $pro->setAccessible(TRUE);
        $val = $pro->getValue(Application::getInstance());

        return new Collection($val);
    }
}