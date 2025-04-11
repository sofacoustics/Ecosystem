<?php

namespace App\Livewire\Forms\Radar;

use Livewire\Attributes\Validate;
use Livewire\Form;

class Dataset extends Form
{
    // https://fly.io/laravel-bytes/livewire-3-forms/
    // https://livewire.laravel.com/docs/forms

    #[Validate('required|min:5')]
    public $title = '';

    //jw:todo implement RADAR heirarchy here

    #[Validate('required|min:5')]
    public $content = '';

    public function setDataset(\App\Data\RadardatasetpureData $dataset)
    {
        // https://livewire.laravel.com/docs/forms
        //dd($dataset);
        $this->title = $dataset->descriptiveMetadata->title;
    }
}
