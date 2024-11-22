<?php

namespace App\Traits\Radar\Rules;

trait Dataset
{
    /*
     * rules and messages used by livewire component and laravel-data
     *
     * Add 'use App\Traits\Radar\Rules\Dataset as Radardatasetrules;' above class
     *
     *
     *
     * and
     *
     * Add 'use Radardatasetrules;' in the class
     */
    public static function rules() : array
    {
        return [
            'id' => ['required', 'min:3'],
            'descriptiveMetadata.title' => ['required','min:2'],
            'descriptiveMetadata.productionYear' => 'required|date_format:Y|digits:4',
            'descriptiveMetadata.creators.creator.*.givenName' => ['required','min:5' ],
        ];
    }

    public static function messages()
    {
        return [
            'descriptiveMetadata.title.min' => "You must specify a title with a minimum of 2 characters.",
            'descriptiveMetadata.title.required' => "The field descriptiveMetadata.title is required.",
            'descriptiveMetadata.productionYear.digits' => "TRAIT: You must specify a 4 digit year.",
            'descriptiveMetadata.productionYear.date_format' => "You must specify a 4 digit year.",
            'descriptiveMetadata.creators.creator.*.givenName' => "The given name must be at least 5 characters",
        ];
    }
}
