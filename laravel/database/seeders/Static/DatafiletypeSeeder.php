<?php

namespace Database\Seeders\Static;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Datafiletype;
use App\Models\Widget;

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

		// 'updateOrCreate' uses first argument to find existing rows
		Datafiletype::updateOrCreate([ 'key' => 'spatial-acoustics-hrtfs-sofa' ],
			[
				'name' => 'Spatial acoustics: HRTFs (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-hrtf-general')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Set of anechoic HRTFs (SOFA file, supported convention: SimpleFreeFieldHRIR, SimpleFreeFieldHRTF)'
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'spatial-acoustics-brirs-sofa' ],
			[
				'name' => 'Spatial acoustics: BRIRs (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-metadata')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Set of BRIRs, i.e., HRTFs measured in a room (SOFA file, supported convention: SingleRoomSRIR)'
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'spatial-acoustics-srirs-sofa' ],
			[
				'name' => 'Spatial acoustics: SRIRs (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-srir-general')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Set of SRIRs, (SOFA file, supported conventions: SingleRoomSRIR, SingleRoomMIMOSRIR)'
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'spatial-acoustics-directivities-sofa' ],
			[
				'name' => 'Spatial acoustics: Directivities (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-directivities-polar')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Set of directivities (SOFA file, supported convention: FreeFieldDirectivityTF)'
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'spatial-acoustics-general-sofa' ],
			[
				'name' => 'Spatial acoustics: General (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-metadata')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'General spatial data (SOFA file, supported conventions: GeneralFIR, GeneralTF, GeneralFIR-E, GeneralTF-E, GeneralSOS)'
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'human-and-alike-geometry-non-parametric' ],
			[
				'name' => 'Human and alike: Geometry: non-parametric (PLY, STL)',
				'default_widget' => Widget::where('key', 'geometry-mesh')->first()->id,
				'extension' => '.ply,.stl',
				'mimetypes' => null,
				'description' => 'Point cloud and/or mesh of a human and alike (PLY or STL file)', 
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'human-and-alike-geometry-parametric-csv' ],
			[
				'name' => 'Human and alike: Geometry: parametric (CSV)',
				'default_widget' => Widget::where('key', 'geometry-bezierppm')->first()->id,
				'extension' => '.csv',
				'mimetypes' => 'text/csv',
				'description' => 'Parameters of models (e.g., BezierPPM) describing the human-and-alike geometry (CSV file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'human-and-alike-image' ],
			[
				'name' => 'Human and alike: Image',
				'default_widget' => Widget::where('key', 'image')->first()->id,
				'extension' => '.jpg,.png,.webp,.svg',
				'mimetypes' => 'image/jpeg,image/png,image/webp,image/svg+xml',
				'description' => 'Photo of a human or alike (JPG, PNG, WEBP, or SVG file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'human-and-human-set-of-photos-animated-webp' ],
			[
				'name' => 'Human and human: Set of photos (Animated WEBP)',
				'default_widget' => Widget::where('key', 'image')->first()->id,
				'extension' => '.webp',
				'mimetypes' => 'image/webp',
				'description' => 'Set of photos of a human or alike (Animated WEBP file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'environment-spherical-image' ],
			[
				'name' => 'Environment: Spherical image (JPG)',
				'default_widget' => Widget::where('key', 'image-spherical')->first()->id,
				'extension' => '.jpg',
				'mimetypes' => 'image/jpeg',
				'description' => 'Spherical image of an environment (room or space, JPG file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'environment-cad-models-any' ],
			[
				'name' => 'Environment: CAD models (Any)',
				'default_widget' => Widget::where('key', 'properties')->first()->id,
				'extension' => null,
				'mimetypes' => null,
				'description' => 'Any type of a CAD model',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'multisensory-explicit-spatial-data-sofa' ],
			[
				'name' => 'Multisensory: Explicit-spatial data (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-annotated-receiver')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Binaural audio explicitly annotated with spatial data such as head-tracking data and subject\'s responses (SOFA file, supported convention: AnnotatedReceiverAudio)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'multisensory-implicit-spatial-data-audio' ],
			[
				'name' => 'Multisensory: Implicit-spatial data (WAV/MP3/FLAC)',
				'default_widget' => Widget::where('key', 'audio')->first()->id,
				'extension' => '.wav,.mp3,.flac',
				'mimetypes' => 'audio/wav,audio/wave,audio/vnd.wave,audio/x-wav,audio/mpeg,audio/mp3,audio/flac',
				'description' => 'Binaural audio recorded under varying spatial conditions, i.e., implicitly spatial (WAV, MP3 or FLAC file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'non-spatial-acoustic-data-sofa' ],
			[
				'name' => 'Non-spatial acoustic data, e.g., headphone IRs (SOFA)',
				'default_widget' => Widget::where('key', 'sofa-headphones-general')->first()->id,
				'extension' => '.sofa',
				'mimetypes' => 'application/x-hdf,application/x-hdf5',
				'description' => 'Non-spatial (general) acoustic data (SOFA file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'non-spatial-audio' ],
			[
				'name' => 'Non-spatial audio (WAV/MP3)',
				'default_widget' => Widget::where('key', 'audio')->first()->id,
				'extension' => '.wav,.mp3,.flac',
				'mimetypes' => 'audio/wav,audio/wave,audio/vnd.wave,audio/x-wav,audio/mpeg,audio/mp3',
				'description' => 'Non-spatial (monophonic) audio recordings (WAV, MP3 or FLAC file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'non-spatial-table-csv' ],
			[
				'name' => 'Non-spatial table (CSV)',
				'default_widget' => Widget::where('key', 'properties')->first()->id,
				'extension' => '.csv',
				'mimetypes' => 'text/csv',
				'description' => 'Non-spatial (general) multisensory data (CSV file)',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'other-any-type' ],
			[
				'name' => 'Other (Any type)',
				'default_widget' => Widget::where('key', 'properties')->first()->id,
				'extension' => null,
				'mimetypes' => null,
				'description' => 'Any type of other data',
			]
		);

		Datafiletype::updateOrCreate([ 'key' => 'neural-network-pytorch' ],
			[
				'name' => 'Neural Network: (PT/PTH)',
				'default_widget' => Widget::where('key', 'properties')->first()->id,
				'extension' => '.pt,.pth',
				'mimetypes' => 'application/octet-stream',
				'description' => 'Structure or weights of a neural network (Pytorch-based formats PT or PTH)'
			]
		);
    }
}
