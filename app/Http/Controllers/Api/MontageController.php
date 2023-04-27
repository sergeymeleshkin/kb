<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;

class MontageController extends Controller
{
    public function getSchedule(){
        $params = Request()->all();
        $service = Service::where('id', $params['service_id'])->first();
        $booking = $service->bookings()
            ->whereDate('datetime',
                Carbon::parse($params['date'])->format('Y-m-d')
            )->get()->pluck('datetime');
        return Response()->json([
            'all'      => $service->schedules,
            'reserved' => $booking
        ]);
    }

    public function enroll(){
        $params = Request()->all();
        $datetime = Carbon::parse(implode(', ',[$params['date'],$params['time']]));
        $service = Service::where('id', $params['service_id'])->first();
        if($service->bookings()->where('datetime', $datetime)->count()){
            return Response()->json('Указанное время недоступно!');
        }
        $booking = Booking::create([
            'name'      => $params['name'],
            'tel'       => $params['tel'],
            'datetime'  => $datetime
        ]);
        $service->bookings()->attach([$booking->id]);
        return Response()->json('Создана запись c id: '.$booking->id);
    }
}

