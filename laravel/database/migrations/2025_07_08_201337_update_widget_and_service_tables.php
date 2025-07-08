<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Widget;
use App\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
			
			Service::where('id', 1)->update([		// update HRTF General
							'parameters' => 'HRTFGeneral.m',
							'updated_at' => now(),
					 ]);
			Service::where('id', 2)->update([		// modify the old ETC horizontal plane to BRIR ListenerView
							'name' => 'Octave: BRIR ListenerView',
							'description' => 'Plot BRIR with ListenerView as parameter.',
							'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
							'parameters' => 'BRIRListenerView.m',
							'updated_at' => now(),
					 ]);
			Service::where('id', 4)->update([		// update SRIR Geometry
							'name' => 'Octave: SRIR General',
							'description' => 'Plot SRIR General.',
							'parameters' => 'SRIRGeneral.m',
							'updated_at' => now(),
					 ]);
			Service::where('id', 5)->update([		// update Octave: Directivities Polar
							'name' => 'Octave: Directivities General',
							'description' => 'Plot the directivities as polar plots, and as filled contour plot.',
							'parameters' => 'DirectivityGeneral.m',
							'updated_at' => now(),
					 ]);
			Service::where('id', 7)->update([		// update Blender: Render PPM
							'exe' => '/var/www/.local/bin/uv',
							'parameters' => 'run CSVppm.py --input',
							'updated_at' => now(),
					 ]);
			Service::where('id', 8)->update([		// update Octave: AnnotatedReceiver
							'parameters' => 'AnnotatedReceiver.m',
							'updated_at' => now(),
					 ]);
			
			
			Widget::where('id', 7)->update([		// update SRIR Geometry
							'name' => 'SOFA: SRIR General',
							'description' => 'Plot the geometry, and amplitude spectra of SRIRs.',
							'updated_at' => now(),
					 ]);
			if(Widget::where('id', 15)->get()==null)		// add 15th widget if not added yet
			{
				Widget::create(array(
							'name' => 'SOFA: BRIR ListenerView',
							'description' => 'Plot ETC, and the geometry of the BRIRs with ListenerView as parameter.',
							'service_id' => 2,
							'view' => 'brir-listenerview',
						));
			}
					
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
