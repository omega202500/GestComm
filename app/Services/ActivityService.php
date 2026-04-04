<?php

namespace App\Services;

use App\Models\Activity;

class ActivityService
{
    public function getLatest($limit = 10)
    {
        return Activity::with('user')
            ->latest()
            ->take($limit)
            ->get();
    }


}
