<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sla:check')->everyFiveMinutes();
Schedule::command('queue:work --stop-when-empty')->everyMinute();