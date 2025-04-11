<?php

namespace App\Livewire;

use App\Models\Dataset;

use Livewire\Component;
use Livewire\WithFileUploads;

/*
 * <https://www.perplexity.ai/search/is-it-possible-to-upload-the-c-q.SxQg5jQr.Qc1H6NlGFfg#0>
 */
class DatasetUpload extends Component
{
    use WithFileUploads;

    public $files = [];

	public Dataset $dataset;

	function uploadFiles()
    {
/*
        $this->validate([
            'files.*' => 'file|max:10240', // 10MB Max
        ]);
*/

		dd($this->files);

		//dd('uploadFiles');
        foreach ($this->files as $file) {
			//dd($file);
			$originalName = $file->getClientOriginalName();
			//app('log')->info($file->path());
			//app('log')->info("original name: $originalName");

			foreach($this->dataset->database->datasetdefs as $datasetdef)
			{
				if("$datasetdef->name" == "$originalName")
				{
					app('log')->info("uploadFiles(): We have a match ($datasetdef->name == $originalName)");
				}

			}
            //$file->store('uploads');
        }

        // Clear the files array after upload
        $this->files = [];
    }

    public function render()
    {
        return view('livewire.dataset-upload');
    }
}
