<?php


namespace Fnp\Module\Features;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\App;

trait ModuleSchedule
{
    abstract public function schedule(Schedule $schedule);

    public function bootModuleScheduleFeature(Schedule $schedule)
    {
        if (App::runningInConsole())
            $this->schedule($schedule);
    }
}