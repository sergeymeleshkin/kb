<?php

namespace Database\Seeders;

use App\Helpers;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateServices extends Seeder
{
    private $content = [];
    function __construct(){
        $this->content = Helpers::getResource('services.json');
    }
    public function run(): void
    {
        foreach($this->content->list as $service){
            Service::create((array)$service);
        }
    }
}
