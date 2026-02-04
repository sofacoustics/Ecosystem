<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Datafiletype;
use App\Models\ServiceLog;
use App\Models\Widget;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;


class DatafileListener extends Component
{
	public string $id = "undefined";
	public string $asset = "";
	public Datafile $datafile;
	public Datasetdef $datasetdef;
	public Datafiletype $datafiletype;
	public ?ServiceLog $latestLog;
	public ?Widget $widget;
	public $counter = 0;
	public $counter_max = 1; 
	public $counter_min = 0;
	public $isExpanded = false; // for boxes to be expanded

	/* Disabled to resolve the problem of disappearance of other widgets on any notification. 
	/*protected $listeners = [
		'echo:sonicom-ecosystem,.datafile-processed' => 'datafileProcessed',
	];*/

	public function toggleExpand()
	{
		$this->isExpanded = !$this->isExpanded; // Toggle the boolean value
	}

	public function plus()
	{ 
		if($this->counter < $this->counter_max)
			$this->counter++;
	}

	public function minus()
	{ 
		if($this->counter > $this->counter_min)
			$this->counter--;
	}
		// it appears that just listening for an event will cause a re-render
	public function datafileProcessed($payload)
	{
		\Log::debug('DatafileListener('.$this->id.'): processed sonicom-ecosystem datafile-processed event(id='.$payload['id'].')');
	}

	public function mount(Datafile $datafile)
	{
		$this->datafile = $datafile;
		$this->id = $datafile->id;
		$this->datasetdef = $datafile->datasetdef;
		$this->datafiletype = $datafile->datasetdef->datafiletype;
		$this->widget = $datafile->datasetdef->widget;
		$this->result = 0;
		$this->isExpanded = false;
	}

	public function render()
	{
		// get latest service log
		$this->latestLog = ServiceLog::where('datafile_id', $this->datafile->id)->latest()->first();
		\Log::debug('DatafileListener: datafiletype name = ' . ($this->datafiletype->name ?? 'NULL'));

		$view = 'livewire.datafiles.generic'; // default view for any datafile
		if($this->widget)
		{
			\Log::debug('DatafileListener: widget id = ' . $this->widget->id . ' widget view = ' . ($this->widget->view ?? 'NULL'));
			if($this->widget->is_active($this->datafiletype)) // if widget available and active then use it
				if(View::exists("livewire.datafiles." . $this->widget->view)) // if view exist, use it
					$view = "livewire.datafiles." . $this->widget->view;	
		}

		$viewData = []; // clear the array which will be passed to the blade
		$viewData['csvRows']=[]; // assume no CSV file
		$viewData['csvRowsProp']=[]; // assume no CSV property file
		
		$fullPath = $this->datafile->absolutepath();
		$viewData['fullPath'] = $fullPath;
		if(file_exists($fullPath))
		{
			$fileSizeInBytes = filesize($fullPath);
			$viewData['fileSizeInBytes'] = $fileSizeInBytes;
		}
		else
		{
			\Log::error("The datafile " . $this->datafile->id . "('$fullPath') is missing!");
			$viewData['fileSizeInBytes'] = 0;
		}
		$viewData['created_at'] = $this->datafile->created_at;
		$viewData['updated_at'] = $this->datafile->updated_at;

		switch($view)
		{
				// DATAFILE PROPERTIES
			case 'livewire.datafiles.properties':
				$viewData['radar_id'] = $this->datafile->radar_id;
				$viewData['datasetdef_radar_id'] = $this->datafile->datasetdef_radar_id;
				$viewData['datasetdef_radar_upload_url'] = $this->datafile->datasetdef_radar_upload_url;
				break;

				// SRIR GENERAL
			case 'livewire.datafiles.srir-general':
				$files = glob($fullPath . '_iso_1_Mmax=*.png');
				$postfixes=[];
				$Mmax = 0;
				if(!empty($files))
				{
					preg_match('/_Mmax=\d+\.png/', $files[0], $match);
					sscanf($match[0], "_Mmax=%d.png", $Mmax);
					for ($i=0; $i<$Mmax; $i++)
						array_push($postfixes,'_'.($i+1).'_Mmax='.$Mmax.'.png');
				}
				$viewData['postfixes'] = $postfixes;
				if($this->counter<1) $this->counter=1;
					// SOFA properties
				$sofaFile = $this->datafile->absolutepath();
				$viewData['csvRows'] = $this->readCSV($sofaFile, '_dim.csv');
				$viewData['csvRowsProp'] = $this->readCSV($sofaFile, '_prop.csv');
				break;

				// brir-listenerview
			case 'livewire.datafiles.brir-listenerview':
				$files = glob($fullPath . '_1_*.png');
				//dd([$fullPath . '_1_*.png', $files]);
				$postfixes=[];
				$Zoommax = 0;
				if(!empty($files))
				{
					preg_match('/_1_\d+\.png/', $files[0], $match);
					sscanf($match[0], "_1_%d.png", $Zoommax);
					for ($i=0; $i<$Zoommax; $i++)
						array_push($postfixes,'_'.($i+1).'.png');
				}
				$viewData['postfixes'] = $postfixes;
				if($this->counter<1) $this->counter=1;
				$this->counter_min = 1;
				$this->counter_max = $Zoommax;
					// SOFA properties
				$sofaFile = $this->datafile->absolutepath();
				$viewData['csvRows'] = $this->readCSV($sofaFile, '_dim.csv');
				$viewData['csvRowsProp'] = $this->readCSV($sofaFile, '_prop.csv');
				break;
			
				// SOFA PROPERTIES and other SOFA-related viewers
			case 'livewire.datafiles.sofa-properties':
			case 'livewire.datafiles.brir-general':
			case 'livewire.datafiles.headphones-general':
			case 'livewire.datafiles.hrtf-general':
			case 'livewire.datafiles.annotated-receiver': 
				$sofaFile = $this->datafile->absolutepath();
				$viewData['csvRows'] = $this->readCSV($sofaFile, '_dim.csv');
				$viewData['csvRowsProp'] = $this->readCSV($sofaFile, '_prop.csv');
				break;

				// HEADPHONES: SELFONE 
			case 'livewire.datafiles.headphones-selfone':
					// Effect of R: M=1, E varies
				$files = glob($fullPath . '_spectrum_M=1_E=*.png');
				$spectrumEs = [];
				if(!empty($files))
				{
					for ($i=0; $i<count($files); $i++)
					{
						preg_match('/_spectrum_M=1_E=\d+\.png/', $files[$i], $match);
						sscanf($match[0], "_spectrum_M=1_E=%d.png", $f);
						array_push($spectrumEs,$f);
					}
					asort($spectrumEs);
				}
				$viewData['spectrumEs'] = $spectrumEs;
				if($spectrumEs)
					if($this->counter==0)
					{
						$this->counter=1;
					}				
					// Effect of E: M=1, R varies
				$files = glob($fullPath . '_spectrum_M=1_R=*.png');
				$spectrumRs = [];
				if(!empty($files))
				{
					for ($i=0; $i<count($files); $i++)
					{
						preg_match('/_spectrum_M=1_R=\d+\.png/', $files[$i], $match);
						sscanf($match[0], "_spectrum_M=1_R=%d.png", $f);
						array_push($spectrumRs,$f);
					}
					asort($spectrumRs);
				}				
				$viewData['spectrumRs'] = $spectrumRs;
				if($spectrumRs)
					if($this->counter==0)
					{
						$this->counter=1;
					}	
					// Energy distribution of R: M=1, E varies
				$files = glob($fullPath . '_energy_M=1_E=*.png');
				$energyEs = [];
				if(!empty($files))
				{
					for ($i=0; $i<count($files); $i++)
					{
						preg_match('/_energy_M=1_E=\d+\.png/', $files[$i], $match);
						sscanf($match[0], "_energy_M=1_E=%d.png", $f);
						array_push($energyEs,$f);
					}
					asort($energyEs);
				}				
				$viewData['energyEs'] = $energyEs;				
				if($energyEs)
					if($this->counter==0)
					{
						$this->counter=1;
					}	
					// SOFA properties
				$sofaFile = $this->datafile->absolutepath();
				$viewData['csvRows'] = $this->readCSV($sofaFile, '_dim.csv');
				$viewData['csvRowsProp'] = $this->readCSV($sofaFile, '_prop.csv');
				break;
				
				// DIRECTIVITY GENERAL
			case 'livewire.datafiles.directivity-general':
				$files = glob($fullPath . '_amphorizontal_*.png');
				$postfixes=[];
				$freqs = [];
				if(!empty($files))
				{
					for ($i=0; $i<count($files); $i++)
					{
						preg_match('/_amphorizontal_\d+\.png/', $files[$i], $match);
						sscanf($match[0], "_amphorizontal_%d.png", $f);
						array_push($freqs,$f);
					}
					asort($freqs);
				}
				$viewData['frequencies'] = $freqs;
				if($freqs)
					if($this->counter==0) // If we run this for the first time
					{
						$idx=array_search(1000, $freqs);
						if($idx)
							$this->counter=1000; // If 1000 Hz found, set to 1000 Hz
						else
							$this->counter=$freqs[0]; // If 1000 Hz not available, set to the first frequency
					}
					// SOFA properties
				$sofaFile = $this->datafile->absolutepath();
				$viewData['csvRows'] = $this->readCSV($sofaFile, '_dim.csv');
				$viewData['csvRowsProp'] = $this->readCSV($sofaFile, '_prop.csv');
				break;
		}
		return view($view, $viewData);
	}
	
	private function readCSV($sofaFile, $postfix) 
	{
			// load file
		$csvRows = [];
		try 
		{
			$handle = fopen($sofaFile . $postfix, "r");
			while (($row = fgetcsv($handle, null, chr(9))) !== false) 
				$csvRows[] = $row;
			fclose($handle);
		} 
		catch (\Exception $e) 
		{
			\Log::error('DatafileListener: Error loading: ' . $e->getMessage());
		}
		return $csvRows;
	}

}
