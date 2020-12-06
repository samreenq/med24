<?php

namespace App\Exports;

use App\UserSessions;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SessionsExport implements FromView
{

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // create log
        $activity = new Activity();
        activity()
            ->performedOn(new UserSessions())
            ->causedBy(\Auth::user())
            ->log('Export Sessions');

        // fetch data
        $data['sessions'] = UserSessions::sessions($this->request);

        // returns excel view
        return view('admin.sessions.export', $data);
    }
}
