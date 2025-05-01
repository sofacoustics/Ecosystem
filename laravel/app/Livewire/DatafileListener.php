<?php

namespace App\Livewire;

use Illuminate\Support\Facades\View;
use Livewire\Component;
use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Datafiletype;
use App\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DatafileListener extends Component
{
    public string $id = "undefined";
    public string $asset = "";
    public Datafile $datafile;
    public Datasetdef $datasetdef;
    public Datafiletype $datafiletype;
    public ?Widget $widget;

    public function mount(Datafile $datafile)
    {
        $this->datafile = $datafile;
        $this->id = $datafile->id;
        $this->datasetdef = $datafile->datasetdef;
        $this->datafiletype = $datafile->datasetdef->datafiletype;
        $this->widget = $datafile->datasetdef->widget;
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
			if ($this?->widget?->view) {
					$view = "livewire.datafiles." . $this->widget->view;
			}

			// view existing?
			if (!View::exists($view)) {
					\Log::info('DatafileListener: View not found, fallback to generic');
					$view = 'livewire.datafiles.generic';
			}

			$viewData = []; // clear the array which will be passed to the blade
				
				// *****
				// Widget: Properties
			if ($view === 'livewire.datafiles.properties')
			{		
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
			} // Widget: Properties
			
			
				// *****
				// Widget: SOFA properties
			if ($view === 'livewire.datafiles.sofa-properties') 
			{
				$sofaAsset = $this->datafile->asset();

				// remove query string
				$urlParts = parse_url($sofaAsset);
				$sofaPath = $urlParts['path']; // eg. /data/9/57/135/6 sofa-properties.sofa

				$dir = dirname($sofaPath); // eg. /data/9/57/135
				$filename = basename($sofaPath, '.sofa'); // eg. 6 sofa-properties

				$csvFilename = $filename . '.sofa_1.csv'; // eg. 6 sofa-properties.sofa_1.csv

				// Encoding file name
				$csvFilenameEncoded = rawurlencode($csvFilename);

				$csvPath = $dir . '/' . $csvFilenameEncoded;

				// Logging
				\Log::info('DatafileListener: sofaAsset = ' . $sofaAsset);
				\Log::info('DatafileListener: sofaPath (no Query) = ' . $sofaPath);
				\Log::info('DatafileListener: csvPath (encoded) = ' . $csvPath);

					// Get domain
				$csvUrl = 'https://sonicom.amtoolbox.org' . $csvPath; 

					// load file
				$csvContent = '';
				try 
				{
					$csvContent = @file_get_contents($csvUrl);
				} catch (\Exception $e) {
					\Log::error('DatafileListener: Error loading: ' . $e->getMessage());
				}

				if ($csvContent === false || $csvContent === null) 
				{
					$csvContent = '';
				}
	
					// CSV as array for table
				$csvRows = [];
				if ($csvContent !== '') 
				{
					$lines = preg_split('/\r\n|\r|\n/', $csvContent);
					foreach ($lines as $line) {
							if (trim($line) !== '') {
									$csvRows[] = str_getcsv($line, ';');
							}
					}
				}

				$viewData['csvContent'] = $csvContent;
				$viewData['csvPath'] = $csvUrl;
				$viewData['csvRows'] = $csvRows;
			} // Widget: SOFA Properties

        return view($view, $viewData);
    }
}
