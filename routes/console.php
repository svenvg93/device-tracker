<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Checked for new versions weekly on Thursday because
 * I usually do releases on Thursday or Friday.Mesh
 */
Schedule::command('app:version')
    ->weeklyOn(5);
