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
                    'name' => 'Octave - CreateFigures (ETC horizontal plane, magnitude spectrum in the median plane, channel 2, and non-normalized magnitude spectrum in the median plane, channel 1) using Octave script CreateFigures.m',
                    'exe' => 'octave-cli',
                    'file' => 'CreateFigures.m'
                )
            );
            Service::create(array(
                    'name' => 'Octave - CreateFigures (ETC horizontal plane)',
                    'exe' => 'octave-cli',
                    'file' => 'CreateFigures.m'
                )
            );
    }
}
