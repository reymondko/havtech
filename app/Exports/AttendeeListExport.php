<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\EventAttendees;

use DB;

class AttendeeListExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(int $event_id)
    {
        $this->event_id = $event_id;
    }

    public function query()
    {
        $event_id =$this->event_id;
        $attendees=EventAttendees::select('users.first_name', 'users.last_name','users.company','users.email','users.phone')->where('event_id', $event_id)
        ->leftJoin('users' , function($q) use ($event_id)
        {
            $q->on('users.id', '=', 'event_attendees.user_id')
                ->where('event_attendees.event_id', '=', $event_id)
                ->where('event_attendees.email_sent_approved', '=', 1);
        });
        return $attendees;
    }

    public function headings() : array
    {
        return [
            'First Name',
            'Last name',
            'Company',
            'Email',
            'Phone',
        ];
    }
}
