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
        // Note (Jonnie): If possible, use only mimetypes. If, however, like 
        // with SOFA files, the mime type audio/sofa won't be recognised, then
        // use an extension as well. The extension will be used to filter the 
        // input dialog, whilst the mime type will be used to validate the 
        // file.
/*        Datafiletype::create([ 'name' => 'jpg',
					'description' => 'A jpg image file',
					'mimetypes' => 'image/jpeg', ]);
								
        Datafiletype::create([ 'name' => 'png',
				'description' => 'A png image file',
				'mimetypes' => 'image/png', ]);
				
        Datafiletype::create([ 'name' => 'HRTF.sofa',
					'description' => 'A HRTF file in SOFA format',
          'extension' => '.sofa',
          'mimetypes' => 'application/x-hdf,application/x-hdf5', ]);*/
								
        Datafiletype::create([ 'name' => 'Spatial acoustics: HRTFs (SOFA)',
 			    'default_widget' => null,
					'extension' => '.sofa',
          'mimetypes' => 'application/x-hdf,application/x-hdf5',
          'description' => 'Set of anechoic HRTFs (SOFA file, supported convention: SimpleFreeFieldHRIR, SimpleFreeFieldHRTF)']);
					
        Datafiletype::create([ 'name' => 'Spatial acoustics: BRIRs (SOFA)',
			    'default_widget' => null,
					'extension' => '.sofa',
					'mimetypes' => 'application/x-hdf,application/x-hdf5',
          'description' => 'Set of BRIRs, i.e., HRTFs measured in a room (SOFA file, supported convention: SingleRoomSRIR)']);
					
        Datafiletype::create([ 'name' => 'Spatial acoustics: SRIRs (SOFA)',
			    'default_widget' => null,
					'extension' => '.sofa',
					'mimetypes' => 'application/x-hdf,application/x-hdf5',
          'description' => 'Set of SRIRs, (SOFA file, supported conventions: SingleRoomSRIR, SingleRoomMIMOSRIR)']);
					
        Datafiletype::create([ 'name' => 'Spatial acoustics: Directivities (SOFA)',
			    'default_widget' => null,
					'extension' => '.sofa',
          'mimetypes' => 'application/x-hdf,application/x-hdf5',
          'description' => 'Set of directivities (SOFA file, supported convention: FreeFieldDirectivityTF)']);
					
        Datafiletype::create([ 'name' => 'Spatial acoustics: General (SOFA)',
			    'default_widget' => null,
          'extension' => '.sofa',
          'mimetypes' => 'application/x-hdf,application/x-hdf5',
					'description' => 'General spatial data (SOFA file, supported conventions: GeneralFIR, GeneralTF, GeneralFIR-E, GeneralTF-E, GeneralSOS)']);
					
        Datafiletype::create([ 'name' => 'Human and alike: Geometry: non-parametric (PLY, STL)',
			    'default_widget' => null,
					'extension' => '.ply,.stl',
					'mimetypes' => null,
					'description' => 'Point cloud and/or mesh of a human and alike (PLY or STL file)', ]);
					
        Datafiletype::create([ 'name' => 'Human and alike: Geometry: parametric (CSV)',
			    'default_widget' => null,
					'extension' => '.csv',
					'mimetypes' => 'text/csv',
					'description' => 'Parameters of models (e.g., BezierPPM) describing the human-and-alike geometry (CSV file)', ]);
					
        Datafiletype::create([ 'name' => 'Human and alike: Image (JPG, PNG, WEBP)',
			    'default_widget' => null,
					'extension' => '.jpg,.png,.webp',
					'mimetypes' => 'image/jpeg,image/png,image/webp',
					'description' => 'Photo of a human or alike (JPG, PNG, or WEBP file)', ]);
					
        Datafiletype::create([ 'name' => 'Human and human: Set of photos (Animated WEBP)',
			    'default_widget' => null,
					'extension' => '.webp',
					'mimetypes' => 'image/webp',
					'description' => 'Set of photos of a human or alike (Animated WEBP file)', ]);
					
        Datafiletype::create([ 'name' => 'Environment: Spherical image (JPG)',
			    'default_widget' => null,
					'extension' => '.jpg',
					'mimetypes' => 'image/jpeg',
					'description' => 'Spherical image of an environment (room or space, JPG file)', ]);
					
        Datafiletype::create([ 'name' => 'Environment: CAD models (Any)',
			    'default_widget' => null,
					'extension' => null,
					'mimetypes' => null,
					'description' => 'Any type of a CAD model', ]);
					
        Datafiletype::create([ 'name' => 'Multisensory: Explicit-spatial data (SOFA)',
			    'default_widget' => null,
					'extension' => '.sofa',
          'mimetypes' => 'application/x-hdf,application/x-hdf5',
					'description' => 'Binaural audio explicitly annotated with spatial data such as head-tracking data and subject\'s responses (SOFA file, supported convention: AnnotatedReceiverAudio)', ]);
					
        Datafiletype::create([ 'name' => 'Multisensory: Implicit-spatial data (WAV/MP3/FLAC)',
			    'default_widget' => null,
					'extension' => '.wav,.mp3,.flac',
					'mimetypes' => 'audio/wav,audio/wave,audio/vnd.wave,audio/x-wav,audio/mpeg,audio/mp3,audio/flac',
					'description' => 'Binaural audio recorded under varying spatial conditions, i.e., implicitly spatial (WAV, MP3 or FLAC file)', ]);
					
        Datafiletype::create([ 'name' => 'Non-spatial acoustic data (SOFA)',
			    'default_widget' => null,
					'extension' => '.sofa',
					'mimetypes' => 'application/x-hdf,application/x-hdf5',
					'description' => 'Non-spatial (general) acoustic data (SOFA file)', ]);
				
        Datafiletype::create([ 'name' => 'Non-spatial audio (WAV/MP3)',
			    'default_widget' => null,
					'extension' => '.wav,.mp3,.flac',
					'mimetypes' => 'audio/wav,audio/wave,audio/vnd.wave,audio/x-wav,audio/mpeg,audio/mp3',
					'description' => 'Non-spatial (monophonic) audio recordings (WAV, MP3 or FLAC file)', ]);
					
        Datafiletype::create([ 'name' => 'Non-spatial table (CSV)',
			    'default_widget' => null,
					'extension' => '.csv',
					'mimetypes' => 'text/csv',
					'description' => 'Non-spatial (general) multisensory data (CSV file)', ]);
					
        Datafiletype::create([ 'name' => 'Other (Any type)',
			    'default_widget' => null,
					'extension' => null,
					'mimetypes' => null,
					'description' => 'Any type of other data', ]);
    }
}
