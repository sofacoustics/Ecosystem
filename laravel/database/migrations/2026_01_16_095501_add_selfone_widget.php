<?php

namespace Database\Seeders;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Service;
use App\Models\Widget;
use App\Models\Datafiletype;

return new class extends Migration
{
    /**
     * Add the Selfone widget and link with the Headphones datafiletype
     */
     public function up(): void
    {
		/* jw:note moved to ServiceSeeder.php
		// Add the Selfone service
		if(Service::where('name', 'Octave: Selfone')->first() == null)
		{
			Service::create(array(
				'id' => 10,
				'name' => 'Octave: Selfone',
				'description' => 'Plot energy distribution, amplitude spectra, and the geometry of the Selfone measurements.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'Selfone.m',
				'timeout' => '600',
			));
		}
		 */
		/* jw:note moved to WidgetSeeder
		// Add the Selfone Widget
		if(Widget::where('name', 'SOFA: Selfone')->first() == null)
		{
			Widget::create(array(
				'id' => 15,
				'name' => 'SOFA: Selfone',
				'description' => 'Plot data	of a headphone with multiple microphones and drivers (Selfone): energy distribution, amplitude spectra, and the geometry.',
				'view' => 'headphones-selfone',
				'service_id' => Service::where('name', 'Octave: Selfone')->first()->id,
			));
		}
		 */
		/* jw:note moved to DatafiletypeWidgetSeeder
		// Attach that widget to the datafiletype of Headphones
		$dft = Datafiletype::find(14); // 14: "Non-spatial acoustic data, e.g., headphone IRs (SOFA)"
		$dft->widgets()->attach(Widget::where('view', 'headphones-selfone')->first());
		$dft->save();
		 */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
