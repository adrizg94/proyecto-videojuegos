<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('release-alerts:send')
    ->dailyAt('09:00')
    ->withoutOverlapping();
