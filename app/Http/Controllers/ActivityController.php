<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function validateActivity(Activity $activity)
    {
        $activity->update([
            'status' => 'validated',
            'is_new' => 0
        ]);

        return back()->with('success', 'Activité validée');
    }

    public function rejectActivity(Activity $activity)
    {
        $activity->update([
            'status' => 'rejected',
            'is_new' => 0
        ]);

        return back()->with('error', 'Activité refusée');
    }
}
