<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Datafiletype;
use App\Models\Widget;

class DatafiletypeSeeder_2025_12_02_230239 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
			if(Datafiletype::where('name', 'Neural Network: (PT/PTH)')->first() == null)
			{
				Datafiletype::create([ 'name' => 'Neural Network: (PT/PTH)',
					'default_widget' => null,
					'extension' => '.pt,.pth',
					'mimetypes' => null,
					'description' => 'Structure or weights of a neural network (Pytorch-based formats PT or PTH)']);	

				$w_prop = Widget::where('view', 'properties')->first(); // general properties for every datafiletype
					// Other (Any type)
				$dft = Datafiletype::where('name', 'Neural Network: (PT/PTH)')->first();
				$dft->widgets()->attach($w_prop);
				$dft->default_widget = $w_prop->id; 
				$dft->save();				
			}
    }
}
