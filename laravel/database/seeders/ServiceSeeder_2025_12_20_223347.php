<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder_2025_12_20_223347 extends Seeder
{
	public function run(): void
	{
		$ids = [1, 4];

		Service::whereIn('id', $ids)->update([
			'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh']);
	}
}
