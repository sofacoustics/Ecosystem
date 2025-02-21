<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Service::create(array(
                    'name' => 'Octave - ETC & Magnitude spectrum',
                    'description' => 'Plot ETC horizontal plane, magnitude spectrum in the median plane, channel 2, and non-normalized magnitude spectrum in the median plane, channel 1) using Octave script CreateFigures.m',
                    'exe' => 'octave-cli',
                    'parameters' => 'CreateFigures.m'
                )
            );
            Service::create(array(
                    'name' => 'Octave - ETC horizontal plane',
                    'description' => 'Plot ETC horizontal plane using Octave script CreateFigures.m',
                    'exe' => 'octave-cli',
                    'parameters' => 'CreateFigures.m'
                )
            );
    }
}
