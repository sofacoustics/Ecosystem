<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Datafiletype;

class DatafiletypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       Datafiletype::create([ 'name' => 'jpg', 
				'description' => 'A jpg image file', ]);
				'extension' => '.jpg',
       Datafiletype::create([ 'name' => 'png', 
				'description' => 'A png image file', ]);
				'extension' => '.png',
       Datafiletype::create([ 'name' => 'HRTF.sofa', 
				'description' => 'A HRTF file in SOFA format', ]);
				'extension' => '.sofa',
       Datafiletype::create([ 'name' => 'Spatial acoustics: HRTFs (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'Set of anechoic HRTFs (SOFA file, supported convention: SimpleFreeFieldHRIR, SimpleFreeFieldHRTF)']);
       Datafiletype::create([ 'name' => 'Spatial acoustics: BRIRs (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'Set of BRIRs, i.e., HRTFs measured in a room (SOFA file, supported convention: SingleRoomSRIR)']);
       Datafiletype::create([ 'name' => 'Spatial acoustics: SRIRs (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'Set of SRIRs, (SOFA file, supported conventions: SingleRoomSRIR, SingleRoomMIMOSRIR)']);
       Datafiletype::create([ 'name' => 'Spatial acoustics: Directivities (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'Set of directivities (SOFA file, supported convention: FreeFieldDirectivityTF)']);
       Datafiletype::create([ 'name' => 'Spatial acoustics: General (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'General spatial data (SOFA file, supported conventions: GeneralFIR, GeneralTF, GeneralFIR-E, GeneralTF-E, GeneralSOS)']);
       Datafiletype::create([ 'name' => 'Human and alike: Geometry: non-parametric (PLY, STL)', 
			  'default_widget' => null,
				'extension' => '.ply,.stl',
				'description' => 'Point cloud and/or mesh of a human and alike (PLY or STL file)', ]);
       Datafiletype::create([ 'name' => 'Human and alike: Geometry: parametric (CSV)', 
			  'default_widget' => null,
				'extension' => '.csv',
				'description' => 'Parameters of models (e.g., BezierPPM) describing the human-and-alike geometry (CSV file)', ]);
       Datafiletype::create([ 'name' => 'Human and alike: Image (JPG, PNG)', 
			  'default_widget' => null,
				'extension' => '.jpg,.png',
				'description' => 'Photo of a human or alike (JPG or PNG file)', ]);
       Datafiletype::create([ 'name' => 'Human and human: Set of photos (MPO)', 
			  'default_widget' => null,
				'extension' => '.mpo',
				'description' => 'Set of photos of a human or alike (MPO file)', ]);
       Datafiletype::create([ 'name' => 'Environment: Spherical image (JPG)', 
			  'default_widget' => null,
				'extension' => '.jpg',
				'description' => 'Spherical image of an environment (room or space, JPG file)', ]);
       Datafiletype::create([ 'name' => 'Environment: CAD models (Any)', 
			  'default_widget' => null,
				'description' => 'Spherical image of an environment (room or space, JPG file)', ]);
       Datafiletype::create([ 'name' => 'Multisensory: Explicit-spatial data (SOFA)', 
			  'default_widget' => null,
				'extension' => '.sofa',
				'description' => 'Binaural audio explicitly annotated with spatial data such as head-tracking data and subject\'s responses (SOFA file, supported convention: AnnotatedReceiverAudio)', ]);
       Datafiletype::create([ 'name' => 'Multisensory: Implicit-spatial data (WAV)', 
			  'default_widget' => null,
				'extension' => '.wav,.mp3',
				'description' => 'Binaural audio recorded under varying spatial conditions, i.e., implicitly spatial (WAV or MP3 file)', ]);
       Datafiletype::create([ 'name' => 'Non-spatial data (CSV)', 
			  'default_widget' => null,
				'extension' => '.csv',
				'description' => 'Non-spatial (general) multisensory data (CSV file)', ]);
       Datafiletype::create([ 'name' => 'Non-spatial audio (WAV)', 
			  'default_widget' => null,
				'extension' => '.wav,.mp3',
				'description' => 'Non-spatial (monophonic) audio recordings (WAV or MP3 file)', ]);
       Datafiletype::create([ 'name' => 'Other (Any type)', 
			  'default_widget' => null,
				'description' => 'Any type of other data', ]);
    }
}
