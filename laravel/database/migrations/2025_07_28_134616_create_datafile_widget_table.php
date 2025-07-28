<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Widget;
use App\Models\Datafiletype;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
			// Activate BRIR-related Widgets
		$w = Widget::find(4); // SOFA: BRIR ListenerView
		$w->view = 'brir-listenerview';
		$w->save();
		$w = Widget::find(6); // SOFA: BRIR General
		$w->view = 'brir-general';
		$w->save();
		
			// Create a pivot table linking Datafiletypes with Widgets
		Schema::create('datafiletype_widget', function (Blueprint $table) {
			$table->foreignId('datafiletype_id')->constrained()->onDelete('cascade');
			$table->foreignId('widget_id')->constrained()->onDelete('cascade');
			$table->boolean('is_active')->default(true);
			$table->primary(['datafiletype_id', 'widget_id']);
		});
		
			// Fill the that table & assign default widgets
			
		$w_prop = Widget::where('view', 'properties')->first(); // general properties for every datafiletype
		$s_prop = Widget::where('view', 'sofa-properties')->first();	// SOFA Properties for many datafiletypes
			// Spatial acoustics: HRTFs (SOFA)
		$dft = Datafiletype::find(1);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'hrtf-general')->first());
		$dft->default_widget = Widget::where('view', 'hrtf-general')->first()->id; 
		$dft->save(); 
			// Spatial acoustics: BRIRs (SOFA)
		$dft = Datafiletype::find(2);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'brir-general')->first());
		$dft->widgets()->updateExistingPivot(Widget::where('view', 'brir-general')->first()->id, ['is_active' => false]);
		$dft->widgets()->attach(Widget::where('view', 'brir-listenerview')->first());
		$dft->widgets()->updateExistingPivot(Widget::where('view', 'brir-listenerview')->first()->id, ['is_active' => false]);
		$dft->default_widget = $s_prop->id; 
		$dft->save();
			// Spatial acoustics: SRIRs (SOFA)
		$dft = Datafiletype::find(3);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'srir-general')->first());
		$dft->default_widget = Widget::where('view', 'srir-general')->first()->id; 
		$dft->save();
			// Spatial acoustics: Directivities (SOFA)
		$dft = Datafiletype::find(4);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'directivity-general')->first());
		$dft->default_widget = Widget::where('view', 'directivity-general')->first()->id; 
		$dft->save();
			// Spatial acoustics: General (SOFA)
		$dft = Datafiletype::find(5);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->default_widget = $s_prop->id; 
		$dft->save();
			// Human and alike: Geometry: non-parametric (PLY, STL)
		$dft = Datafiletype::find(6);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'mesh')->first());
		$dft->default_widget = Widget::where('view', 'mesh')->first()->id; 
		$dft->save();
			// Human and alike: Geometry: parametric (CSV)
		$dft = Datafiletype::find(7);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'bezierppm')->first());
		$dft->default_widget = Widget::where('view', 'bezierppm')->first()->id; 
		$dft->save();
			// Human and alike: Image (JPG, PNG, WEBP)
		$dft = Datafiletype::find(8);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'image')->first());
		$dft->default_widget = Widget::where('view', 'image')->first()->id; 
		$dft->save();
			// Human and human: Set of photos (Animated WEBP)
		$dft = Datafiletype::find(9);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'image')->first());
		$dft->default_widget = Widget::where('view', 'image')->first()->id; 
		$dft->save();
			// Environment: Spherical image (JPG)
		$dft = Datafiletype::find(10);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'image')->first());
		$dft->widgets()->attach(Widget::where('view', 'image-360')->first());
		$dft->default_widget = Widget::where('view', 'image-360')->first()->id; 
		$dft->save();
			// Environment: CAD models (Any)
		$dft = Datafiletype::find(11);
		$dft->widgets()->attach($w_prop);
		$dft->default_widget = $w_prop->id; 
		$dft->save();
			// Multisensory: Explicit-spatial data (SOFA)
		$dft = Datafiletype::find(12);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'annotated-receiver')->first());
		$dft->default_widget = $s_prop->id; 
		$dft->save();
			// Multisensory: Implicit-spatial data (WAV/MP3/FLAC)
		$dft = Datafiletype::find(13);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'audio')->first());
		$dft->default_widget = Widget::where('view', 'audio')->first()->id; 
		$dft->save();
			// Non-spatial acoustic data, e.g., headphone IRs (SOFA)
		$dft = Datafiletype::find(14);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach($s_prop);
		$dft->widgets()->attach(Widget::where('view', 'headphones-general')->first());
		$dft->default_widget = Widget::where('view', 'headphones-general')->first()->id; 
		$dft->save();
			// Non-spatial audio (WAV/MP3)
		$dft = Datafiletype::find(15);
		$dft->widgets()->attach($w_prop);
		$dft->widgets()->attach(Widget::where('view', 'audio')->first());
		$dft->default_widget = Widget::where('view', 'audio')->first()->id; 
		$dft->save();
			// Non-spatial table (CSV)
		$dft = Datafiletype::find(16);
		$dft->widgets()->attach($w_prop);
		$dft->default_widget = $w_prop->id; 
		$dft->save();
			// Other (Any type)
		$dft = Datafiletype::find(17);
		$dft->widgets()->attach($w_prop);
		$dft->default_widget = $w_prop->id; 
		$dft->save();
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('datafiletype_widget');
	}
};
