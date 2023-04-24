<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

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
        $service = Service::where('id', $params['service_id'])->first();
        if($service->bookings()->where('datetime',
            Carbon::parse($params['dateStr'])
        )->count()){
            return Response()->json('Указанное время недоступно!');
        }
        $booking = Booking::create([
            'name'      => $params['name'],
            'tel'       => $params['tel'],
            'datetime'  => Carbon::parse($params['dateStr'])
        ]);
        $service->bookings()->attach([$booking->id]);
        return Response()->json('Создана запись c id: '.$booking->id);
    }
}

