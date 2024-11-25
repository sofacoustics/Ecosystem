<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Radar\Metadataschema;

class MetadataschemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'AUDIOVISUAL', 'display' => 'Audiovisual'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'COLLECTION', 'display' => 'Collection'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'DATASET', 'display' => 'Dataset'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'EVENT', 'display' => 'Event'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'IMAGE', 'display' => 'Image'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'INTERACTIVE_RESOURCE', 'display' => 'Interactive Resource'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'MODEL', 'display' => 'Model'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'PHYSICAL_OBJECT', 'display' => 'Physical Object'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SERVICE', 'display' => 'Service'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SOFTWARE', 'display' => 'Software'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SOUND', 'display' => 'Sound'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'TEXT', 'display' => 'Text'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'WORKFLOW', 'display' => 'Workflow'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'OTHER', 'display' => 'Other'));

        // VIM notes
        // gUiw - capitalise word
        // P - paste before cursor
        // p - paste after cursor
        // g_ - got to end of line minus linebreak

        // 0   Start at beginning of line
        // vg_  Select to end of line except linebreak
        // "dy  Yank to register d
        // "aP  Paste from register a
        // l    Got to next character
        // gU$  Capitalise to end of line
        // vg_  Select to end of line minus linebreak (only for entries with a space)
        // :s/\%>.c /_/g  Replace spaces with underliners from current position to end of line (only for entries with a space)
        // :noh Remove highlighting (only for entries with a space)
        // $    Go to end of line
        // "bp  Paste from register b
        // "dp  Paste from register d
        // "cp  Paste from register c
        // j   Go to next line

        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'AGRICULTURE', 'display' => 'Agriculture'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ARCHITECTURE', 'display' => 'Architecture'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ARTS_AND_MEDIA', 'display' => 'Arts and Media'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ASTROPHYSICS_AND_ASTRONOMY', 'display' => 'Astrophysics and Astronomy'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BIOCHEMISTRY', 'display' => 'Biochemistry'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BIOLOGY', 'display' => 'Biology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BEHAVIOURAL_SCIENCES', 'display' => 'Behavioural Sciences'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'CHEMISTRY', 'display' => 'Chemistry'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'COMPUTER_SCIENCE', 'display' => 'Computer Science'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ECONOMICS', 'display' => 'Economics'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ENGINEERING', 'display' => 'Engineering'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ENVIRONMENTAL_SCIENCE_AND_ECOLOGY', 'display' => 'Environmental Science and Ecology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ETHNOLOGY', 'display' => 'Ethnology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'GEOLOGICAL_SCIENCE', 'display' => 'Geological Science'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'GEOGRAPHY', 'display' => 'Geography'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'HISTORY', 'display' => 'History'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'HORTICULTURE', 'display' => 'Horticulture'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'INFORMATION_TECHNOLOGY', 'display' => 'Information Technology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'LIFE_SCIENCE', 'display' => 'Life Science'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'LINGUISTICS', 'display' => 'Linguistics'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MATERIALS_SCIENCE', 'display' => 'Materials Science'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MATHEMATICS', 'display' => 'Mathematics'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MEDICINE', 'display' => 'Medicine'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PHILOSOPHY', 'display' => 'Philosophy'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PHYSICS', 'display' => 'Physics'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PSYCHOLOGY', 'display' => 'Psychology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SOCIAL_SCIENCES', 'display' => 'Social Sciences'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SOFTWARE_TECHNOLOGY', 'display' => 'Software Technology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SPORTS', 'display' => 'Sports'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'THEOLOGY', 'display' => 'Theology'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'VETERINARY_MEDICINE', 'display' => 'Veterinary Medicine'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'OTHER', 'display' => 'Other'));



    }
}
