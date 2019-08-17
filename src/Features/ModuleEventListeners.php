<?php


namespace Fnp\Module\Features;


use Illuminate\Support\Facades\Event;

trait ModuleEventListeners
{
    /**
     * Returns an array of events mapped to event listeners.
     * Event class as a key and listener class as a value.
     * @return array
     */
    abstract public function eventListeners(): array;

    public function bootModuleEventListenersFeature()
    {
        foreach($this->eventListeners() as $event=>$listener)
            Event::listen($event, $listener);
    }
}