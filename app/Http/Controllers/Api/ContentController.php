<?php

namespace App\Http\Controllers\Api;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Booking;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

class ContentController extends Controller
{   //Без логики, только контент для страниц
    public function get(): \Illuminate\Http\JsonResponse
    {
        $method = isset(Request()->route) ?
            Helpers::pointsToCamelCase(Request()->route) :
            null;
        return match ($method && method_exists($this, $method)) {
            true => Response()->json($this->$method()),
            default => Response()->json(null),
        };
    }

    /**
     * @throws \Exception
     */
    private function home(): array
    {
        return [
            'services' => Service::get()
        ];
    }
}
