<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Datafiletype;
use App\Models\Widget;

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
	public ?Widget $widget;
	public $result;
	public $isExpanded = false; // for boxes to be expanded


	protected $listeners = [
		'echo:sonicom-ecosystem,.datafile-processed' => 'datafileProcessed',
	];

	public function toggleExpand()
	{
		$this->isExpanded = !$this->isExpanded; // Toggle the boolean value
	}

	public function plus()
	{ $this->result++;
	}

	public function minus()
	{ $this->result--;
	}
		// it appears that just listening for an event will cause a re-render
	public function datafileProcessed($payload)
	{
		\Log::info('DatafileListener('.$this->id.'): processed sonicom-ecosystem datafile-processed event(id='.$payload['id'].')');
	}

	public function mount(Datafile $datafile)
	{
		$this->datafile = $datafile;
		$this->id = $datafile->id;
		$this->datasetdef = $datafile->datasetdef;
		$this->datafiletype = $datafile->datasetdef->datafiletype;
		$this->widget = $datafile->datasetdef->widget;
		$this->result = 0;
	}

	public function render()
	{
		\Log::info('DatafileListener: datafiletype name = ' . ($this->datafiletype->name ?? 'NULL'));
		\Log::info('DatafileListener: widget view = ' . ($this->widget->view ?? 'NULL'));

		// view depending on file type
		$view = match($this->datafiletype->name)
		{
			'sofa-properties' => 'livewire.datafiles.sofa-properties',
			default => 'livewire.datafiles.generic'
		};

		// widget view?
		if ($this?->widget?->view) 
			$view = "livewire.datafiles." . $this->widget->view;

		// view existing?
		if (!View::exists($view)) 
		{
			\Log::info('DatafileListener: View not found, fallback to generic');
			$view = 'livewire.datafiles.generic';
		}

		$viewData = []; // clear the array which will be passed to the blade
		
		switch($view)
		{
				// GENERIC DATAFILE PROPERTIES
				//
			case 'livewire.datafiles.properties':
				$fullPath = $this->datafile->absolutepath();
				$viewData['fullPath'] = $fullPath; 
				//if (Storage::exists($fullPath)) 
					$fileSizeInBytes = filesize($fullPath);
				//else
				//	$fileSizeInBytes = -1;
				$viewData['fileSizeInBytes'] = $fileSizeInBytes;
				$viewData['fileSizeInKilobytes'] = round($fileSizeInBytes / 1024, 2);
				$viewData['fileSizeInMegabytes'] = round($fileSizeInBytes / (1024*1024), 2);
				$viewData['fileSizeInGigabytes'] = round($fileSizeInBytes / (1024*1024*1024), 2);
				$viewData['created_at'] = $this->datafile->created_at;
				$viewData['updated_at'] = $this->datafile->updated_at;
				$viewData['radar_id'] = $this->datafile->radar_id;
				$viewData['datasetdef_radar_id'] = $this->datafile->datasetdef_radar_id;
				$viewData['datasetdef_radar_upload_url'] = $this->datafile->datasetdef_radar_upload_url;
				break;

				// SOFA properties
				//
			case 'livewire.datafiles.sofa-properties': 
				$sofaAsset = $this->datafile->asset();
				$urlParts = parse_url($sofaAsset);
				$sofaPath = $urlParts['path']; // eg. /data/9/57/135/6 sofa-properties.sofa
				$dir = dirname($sofaPath); // eg. /data/9/57/135
				$filename = basename($sofaPath, '.sofa'); // eg. 6 sofa-properties

				// ====================
				// Load 1st CSV: .sofa_dim.csv
				// ====================
				$csvFilename = $filename . '.sofa_dim.csv'; // eg. 6 sofa-properties.sofa_1.csv
				$csvFilenameEncoded = rawurlencode($csvFilename); 			// Encoding file name
				$csvPath = $dir . '/' . $csvFilenameEncoded;

					// Logging
				\Log::info('DatafileListener: sofaAsset = ' . $sofaAsset);
				\Log::info('DatafileListener: sofaPath (no Query) = ' . $sofaPath);
				\Log::info('DatafileListener: csvPath (encoded) = ' . $csvPath);

					// Get domain
				$baseUrl = request()->getSchemeAndHttpHost(); // e.g., https://sonicom-dev.amtoolbox.org
				$csvUrl = $baseUrl . $csvPath;
					// load file
				$csvContent = '';
				try 
				{
					$csvContent = @file_get_contents($csvUrl);
				} catch (\Exception $e) {
					\Log::error('DatafileListener: Error loading: ' . $e->getMessage());
				}

				if ($csvContent === false || $csvContent === null) 
					$csvContent = '';

					// CSV as array for table
				$csvRows = [];
				if ($csvContent !== '') 
				{
					$lines = preg_split('/\r\n|\r|\n/', $csvContent);
					foreach ($lines as $line) 
					{
						if (trim($line) !== '') 
							$csvRows[] = str_getcsv($line, ';');
					}
				}

				// Pass first CSV data to view
				$viewData['csvContent'] = $csvContent;
				$viewData['csvPath'] = $csvUrl;
				$viewData['csvRows'] = $csvRows;

				// ====================
				// Load 2nd CSV: .sofa_prop.csv
				// ====================
				$csvFilenameProp = $filename . '.sofa_prop.csv';
				$csvFilenamePropEncoded = rawurlencode($csvFilenameProp);
				$csvPathProp = $dir . '/' . $csvFilenamePropEncoded;
				$csvUrlProp = $baseUrl . $csvPathProp;

				// Logging
				\Log::info('DatafileListener: csvPropPath (encoded) = ' . $csvPathProp);

				// load file
				$csvContentProp = '';
				try 
				{
					$csvContentProp = @file_get_contents($csvUrlProp);
				} catch (\Exception $e) {
						\Log::error('DatafileListener: Error loading .sofa_prop.csv: ' . $e->getMessage());
				}

				if ($csvContentProp === false || $csvContentProp === null) 
					$csvContentProp = '';

				$csvRowsProp = [];
				if ($csvContentProp !== '') 
				{
					$lines = preg_split('/\r\n|\r|\n/', $csvContentProp);
					foreach ($lines as $line) 
					{
						if (trim($line) !== '') 
							$csvRowsProp[] = str_getcsv($line, ';');
					}
				}

					// Pass second CSV data to view
				$viewData['csvContentProp'] = $csvContentProp;
				$viewData['csvPathProp'] = $csvUrlProp;
				$viewData['csvRowsProp'] = $csvRowsProp;
				break;
		
			case 'livewire.datafiles.sofa-directivity-polar':
				$this->result=5;
				break;
		}
		return view($view, $viewData);
	}
}
