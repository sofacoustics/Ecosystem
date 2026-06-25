<?php

namespace Database\Seeders\Static;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Metadataschema;

class MetadataschemaSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// ResourceType
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'AUDIOVISUAL' ], [ 'display' => 'Audiovisual' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'COLLECTION' ], [ 'display' => 'Collection' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'DATASET' ], [ 'display' => 'Dataset' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'EVENT' ], [ 'display' => 'Event' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'IMAGE' ], [ 'display' => 'Image' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'INTERACTIVE_RESOURCE' ], [ 'display' => 'Interactive Resource' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'MODEL' ], [ 'display' => 'Model' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'PHYSICAL_OBJECT' ], [ 'display' => 'Physical Object' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SERVICE' ], [ 'display' => 'Service' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SOFTWARE' ], [ 'display' => 'Software' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'SOUND' ], [ 'display' => 'Sound' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'TEXT' ], [ 'display' => 'Text' ]);
		//Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'WORKFLOW' ], [ 'display' => 'Workflow' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'OTHER' ], [ 'display' => 'Other' ]);
		// SubjectArea
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'AGRICULTURE' ], [ 'display' => 'Agriculture' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ARCHITECTURE' ], [ 'display' => 'Architecture' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ARTS_AND_MEDIA' ], [ 'display' => 'Arts and Media' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ASTROPHYSICS_AND_ASTRONOMY' ], [ 'display' => 'Astrophysics and Astronomy' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BIOCHEMISTRY' ], [ 'display' => 'Biochemistry' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BIOLOGY' ], [ 'display' => 'Biology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'BEHAVIOURAL_SCIENCES' ], [ 'display' => 'Behavioural Sciences' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'CHEMISTRY' ], [ 'display' => 'Chemistry' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'COMPUTER_SCIENCE' ], [ 'display' => 'Computer Science' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ECONOMICS' ], [ 'display' => 'Economics' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ENGINEERING' ], [ 'display' => 'Engineering' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ENVIRONMENTAL_SCIENCE_AND_ECOLOGY' ], [ 'display' => 'Environmental Science and Ecology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'ETHNOLOGY' ], [ 'display' => 'Ethnology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'GEOLOGICAL_SCIENCE' ], [ 'display' => 'Geological Science' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'GEOGRAPHY' ], [ 'display' => 'Geography' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'HISTORY' ], [ 'display' => 'History' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'HORTICULTURE' ], [ 'display' => 'Horticulture' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'INFORMATION_TECHNOLOGY' ], [ 'display' => 'Information Technology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'LIFE_SCIENCE' ], [ 'display' => 'Life Science' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'LINGUISTICS' ], [ 'display' => 'Linguistics' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MATERIALS_SCIENCE' ], [ 'display' => 'Materials Science' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MATHEMATICS' ], [ 'display' => 'Mathematics' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'MEDICINE' ], [ 'display' => 'Medicine' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PHILOSOPHY' ], [ 'display' => 'Philosophy' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PHYSICS' ], [ 'display' => 'Physics' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'PSYCHOLOGY' ], [ 'display' => 'Psychology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SOCIAL_SCIENCES' ], [ 'display' => 'Social Sciences' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SOFTWARE_TECHNOLOGY' ], [ 'display' => 'Software Technology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'SPORTS' ], [ 'display' => 'Sports' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'THEOLOGY' ], [ 'display' => 'Theology' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'VETERINARY_MEDICINE' ], [ 'display' => 'Veterinary Medicine' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'subjectArea', 'value' => 'OTHER' ], [ 'display' => 'Other' ]);
		// controlledRights
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_4_0_ATTRIBUTION' ], [ 'display' => 'CC BY 4.0 Attribution' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_ND_4_0_ATTRIBUTION_NO_DERIVS' ], [ 'display' => 'CC BY-ND 4.0 Attribution-NoDerivs' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_ND_4_0_ATTRIBUTION_NON_COMMERCIAL_NO_DERIVS' ], [ 'display' => 'CC BY-NC-ND 4.0 Attribution- N on Commercial -NoDerivs' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_SA_4_0_ATTRIBUTION_SHARE_ALIKE' ], [ 'display' => 'CC BY-SA 4.0 Attribution-ShareAlike' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_4_0_ATTRIBUTION_NON_COMMERCIAL' ], [ 'display' => 'CC BY-NC 4.0 Attribution- NonCommercial' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_SA_4_0_ATTRIBUTION_NON_COMMERCIAL_SHARE_ALIKE' ], [ 'display' => 'CC BY-NC-SA 4.0 Attribution- NonCommercial-ShareAlike' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_0_1_0_UNIVERSAL_PUBLIC_DOMAIN_DEDICATION' ], [ 'display' => 'CC0 1.0 Universal Public Domain Dedication' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'PUBLIC_DOMAIN_MARK_1_0' ], [ 'display' => 'Public Domain Mark 1.0' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ATTRIBUTION_LICENSE_ODC_BY' ], [ 'display' => 'Attribution License (ODC-By)' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'OPEN_DATABASE_LICENSE_ODC_O_DB_L' ], [ 'display' => 'Open Database License (ODC-ODbL)' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'PUBLIC_DOMAIN_DEDICATION_AND_LICENSE_PDDL' ], [ 'display' => 'Public Domain Dedication and License (PDDL)' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'APACHE_LICENSE_2_0' ], [ 'display' => 'Apache License 2.0' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'COMMON_DEVELOPMENT_AND_DISTRIBUTION_LICENSE_1_0' ], [ 'display' => 'Common Development and Distribution License 1.0' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ECLIPSE_PUBLIC_LICENSE_1_0' ], [ 'display' => 'Eclipse Public License 1.0' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ECLIPSE_PUBLIC_LICENSE_2_0' ], [ 'display' => 'Eclipse Public License 2.0' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'GNU_GENERAL_PUBLIC_LICENSE_V_3_0_ONLY' ], [ 'display' => 'GNU General Public License v3.0 only' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'GNU_LESSER_GENERAL_PUBLIC_LICENSE_V_3_0_ONLY' ], [ 'display' => 'GNU Lesser General Public License v3.0 only' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'BSD_2_CLAUSE_SIMPLIFIED_LICENSE' ], [ 'display' => 'BSD 2-Clause Simplified License' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'BSD_3_CLAUSE_NEW_OR_REVISED_LICENSE' ], [ 'display' => 'BSD 3-Clause New or Revised License' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'MIT_LICENSE' ], [ 'display' => 'MIT License' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ALL_RIGHTS_RESERVED' ], [ 'display' => 'All rights reserved' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'OTHER' ], [ 'display' => 'Other' ]);
		// nameIdentifierScheme (currently unused because hard-coded in the corresponding classes)
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'OTHER' ], [ 'display' => 'Other' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'ROR' ], [ 'display' => 'Research Organization Registry' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'ORCID' ], [ 'display' => 'Orcid' ]);
		// additionalTitleType (currently unused because fixed to "Subtitle")
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Subtitle' ], [ 'display' => 'Subtitle' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Translated Title' ], [ 'display' => 'Translated Title' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Alternative Title' ], [ 'display' => 'Alternative Title' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Other' ], [ 'display' => 'Other' ]);
		// descriptionType (currently unused hard coded)
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Abstract' ], [ 'display' => 'Abstract' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Method' ], [ 'display' => 'Method' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Object' ], [ 'display' => 'Object' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Table of Contents' ], [ 'display' => 'Table of Contents' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Technical Info' ], [ 'display' => 'Technical Info' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Technical Remarks' ], [ 'display' => 'Technical Remarks' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Other' ], [ 'display' => 'Other' ]);
		// relatedIdentifierType
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ARK' ], [ 'display' => 'ARK' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'AR_XIV' ], [ 'display' => 'arXiv' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'BIBCODE' ], [ 'display' => 'bibcode' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'DOI' ], [ 'display' => 'DOI' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'EAN_13' ], [ 'display' => 'EAN13' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'EISSN' ], [ 'display' => 'EISSN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'E_PIC' ], [ 'display' => 'ePIC' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'HANDLE' ], [ 'display' => 'Handle' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'IGSN' ], [ 'display' => 'IGSN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISBN' ], [ 'display' => 'ISBN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISSN' ], [ 'display' => 'ISSN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISTC' ], [ 'display' => 'ISTC' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'LISSN' ], [ 'display' => 'LISSN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'LSID' ], [ 'display' => 'LSID' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'PMID' ], [ 'display' => 'PMID' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'PURL' ], [ 'display' => 'PURL' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'UPC' ], [ 'display' => 'UPC' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'URL' ], [ 'display' => 'URL' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'URN' ], [ 'display' => 'URN' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'W_3_ID' ], [ 'display' => 'w3Id' ]);
		// relationType
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_CITED_BY' ], [ 'display' => 'Is Cited By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'CITES' ], [ 'display' => 'Cites' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_SUPPLEMENT_TO' ], [ 'display' => 'Is Supplement To' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_SUPPLEMENTED_BY' ], [ 'display' => 'Is Supplemented By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_CONTINUED_BY' ], [ 'display' => 'Is Continued By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'CONTINUES' ], [ 'display' => 'Continues' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_DESCRIBED_BY' ], [ 'display' => 'Is Described By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'DESCRIBES' ], [ 'display' => 'Describes' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HAS_METADATA' ], [ 'display' => 'Has Metadata' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_METADATA_FOR' ], [ 'display' => 'Is Metadata For' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HAS_VERSION' ], [ 'display' => 'Has Version' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_VERSION_OF' ], [ 'display' => 'Is Version Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_NEW_VERSION_OF' ], [ 'display' => 'Is New Version Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_PREVIOUS_VERSION_OF' ], [ 'display' => 'Is Previous Version Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_PART_OF' ], [ 'display' => 'Is Part Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HAS_PART' ], [ 'display' => 'Has Part' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_PUBLISHED_IN' ], [ 'display' => 'Is Published In' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_REFERENCED_BY' ], [ 'display' => 'Is Referenced By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'REFERENCES' ], [ 'display' => 'References' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_DOCUMENTED_BY' ], [ 'display' => 'Is Documented By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'DOCUMENTS' ], [ 'display' => 'Documents' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_COMPILED_BY' ], [ 'display' => 'Is Compiled By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'COMPILES' ], [ 'display' => 'Compiles' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_VARIANT_FORM_OF' ], [ 'display' => 'Is Variant Form Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_ORIGINAL_FORM_OF' ], [ 'display' => 'Is Original Form Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_IDENTICAL_TO' ], [ 'display' => 'Is Identical To' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_REVIEWED_BY' ], [ 'display' => 'Is Reviewed By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'REVIEWS' ], [ 'display' => 'Reviews' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_DERIVED_FROM' ], [ 'display' => 'Is Derived From' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_SOURCE_OF' ], [ 'display' => 'Is Source Of' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_REQUIRED_BY' ], [ 'display' => 'Is Required By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'REQUIRES' ], [ 'display' => 'Requires' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IS_OBSOLETE_BY' ], [ 'display' => 'Is Obsolete By' ]);
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'OBSOLETES' ], [ 'display' => 'Obsoletes' ]);
		// Additional row for the EUPL License
		Metadataschema::firstOrCreate([ 'version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ECOSYSTEM_EUPL' ], [ 'display' => 'EUPL-1.2: European Union Public Licence version 1.2' ]);
	}
}
