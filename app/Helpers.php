<?php
namespace App;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Storage;

class Helpers {
    public static function getResource($filename){
        return json_decode(Storage::disk('resources')->get($filename));
    }

    public static function pointsToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('.', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

    /**
     * @throws \Exception
     */
    public static function getTimeInterval($interval){
        return CarbonInterval::minutes(30)->toPeriod('2023-04-21 10:00', '2023-04-22 18:00');
    }

}
