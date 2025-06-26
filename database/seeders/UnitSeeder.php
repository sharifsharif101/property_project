<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UnitSeeder extends Seeder
{
     public function run(): void
    {
        $statuses = ['vacant', 'rented', 'under_maintenance', 'under_renovation'];
        $propertyIds = [7, 8, 9];

        for ($i = 1; $i <= 10; $i++) {
            Unit::create([
                'property_id'   => $propertyIds[array_rand($propertyIds)],
                'unit_number'   => 'U-' . Str::random(4),
                'bedrooms'      => rand(1, 5),
                'bathrooms'     => rand(1, 4),
                'area'          => rand(50, 200) + (rand(0, 999) / 1000), // مثل 123.456
                'floor_number'  => rand(0, 10),
                'status'        => $statuses[array_rand($statuses)],
                'created_at'    => now()->subDays(rand(0, 365)),
                'updated_at'    => now(),
                'deleted_at'    => null, // اتركه فارغًا (غير محذوف منطقيًا)
            ]);
        }
    }

 
}
