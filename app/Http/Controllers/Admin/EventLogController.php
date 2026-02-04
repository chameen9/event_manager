<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventLog;

class EventLogController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'event_registration_id' => ['required', 'exists:event_registrations,id'],
            'comment'               => ['required', 'string'],
        ]);

        EventLog::create([
            'event_registration_id' => $validated['event_registration_id'],
            'action' => 'New Comment',
            'description' => $validated['comment'] . ' ~'. auth()->user()->name,
            'created_at' => now(), 
            'update_at' => now(), 
        ]);

        return back()->with('info', 'Comment Added');
    }
}
