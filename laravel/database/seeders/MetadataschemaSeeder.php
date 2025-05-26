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
	{		// ResourceType
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
			// SubjectArea
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
		// controlledRights
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_4_0_ATTRIBUTION', 'display' => 'CC BY 4.0 Attribution'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_ND_4_0_ATTRIBUTION_NO_DERIVS', 'display' => 'CC BY-ND 4.0 Attribution-NoDerivs'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_ND_4_0_ATTRIBUTION_NON_COMMERCIAL_NO_DERIVS', 'display' => 'CC BY-NC-ND 4.0 Attribution- N on Commercial -NoDerivs'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_SA_4_0_ATTRIBUTION_SHARE_ALIKE', 'display' => 'CC BY-SA 4.0 Attribution-ShareAlike'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_4_0_ATTRIBUTION_NON_COMMERCIAL', 'display' => 'CC BY-NC 4.0 Attribution- NonCommercial'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_BY_NC_SA_4_0_ATTRIBUTION_NON_COMMERCIAL_SHARE_ALIKE', 'display' => 'CC BY-NC-SA 4.0 Attribution- NonCommercial-ShareAlike'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'CC_0_1_0_UNIVERSAL_PUBLIC_DOMAIN_DEDICATION', 'display' => 'CC0 1.0 Universal Public Domain Dedication'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'PUBLIC_DOMAIN_MARK_1_0', 'display' => 'Public Domain Mark 1.0'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ATTRIBUTION_LICENSE_ODC_BY', 'display' => 'Attribution License (ODC-By)'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'OPEN_DATABASE_LICENSE_ODC_O_DB_L', 'display' => 'Open Database License (ODC-ODbL)'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'PUBLIC_DOMAIN_DEDICATION_AND_LICENSE_PDDL', 'display' => 'Public Domain Dedication and License (PDDL)'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'APACHE_LICENSE_2_0', 'display' => 'Apache License 2.0'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'COMMON_DEVELOPMENT_AND_DISTRIBUTION_LICENSE_1_0', 'display' => 'Common Development and Distribution License 1.0'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ECLIPSE_PUBLIC_LICENSE_1_0', 'display' => 'Eclipse Public License 1.0'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ECLIPSE_PUBLIC_LICENSE_2_0', 'display' => 'Eclipse Public License 2.0'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'GNU_GENERAL_PUBLIC_LICENSE_V_3_0_ONLY', 'display' => 'GNU General Public License v3.0 only'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'GNU_LESSER_GENERAL_PUBLIC_LICENSE_V_3_0_ONLY', 'display' => 'GNU Lesser General Public License v3.0 only'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'BSD_2_CLAUSE_SIMPLIFIED_LICENSE', 'display' => 'BSD 2-Clause Simplified License'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'BSD_3_CLAUSE_NEW_OR_REVISED_LICENSE', 'display' => 'BSD 3-Clause New or Revised License'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'MIT_LICENSE', 'display' => 'MIT License'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'ALL_RIGHTS_RESERVED', 'display' => 'All rights reserved'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'controlledRights', 'value'=> 'OTHER', 'display' => 'Other'));
			// nameIdentifierScheme
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'OTHER', 'display' => 'Other'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'ROR', 'display' => 'Research Organization Registry'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'nameIdentifierScheme', 'value'=> 'ORCID', 'display' => 'Orcid'));
			// additionalTitleType
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Subtitle', 'display' => 'Subtitle'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Translated Title', 'display' => 'Translated Title'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Alternative Title', 'display' => 'Alternative Title'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'additionalTitleType', 'value'=> 'Other', 'display' => 'Other'));
			// descriptionType
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Abstract', 'display' => 'Abstract'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Method', 'display' => 'Method'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Object', 'display' => 'Object'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Table of Contents', 'display' => 'Table of Contents'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Technical Info', 'display' => 'Technical Info'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Technical Remarks', 'display' => 'Technical Remarks'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'descriptionType', 'value'=> 'Other', 'display' => 'Other'));
			// relatedIdentifierType
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ARK', 'display' => 'ARK'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'arXiv', 'display' => 'arXiv'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'bibcode', 'display' => 'bibcode'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'DOI', 'display' => 'DOI'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'EAN13', 'display' => 'EAN13'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'EISSN', 'display' => 'EISSN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ePIC', 'display' => 'ePIC'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'Handle', 'display' => 'Handle'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'IGSN', 'display' => 'IGSN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISBN', 'display' => 'ISBN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISSN', 'display' => 'ISSN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'ISTC', 'display' => 'ISTC'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'LISSN', 'display' => 'LISSN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'LSID', 'display' => 'LSID'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'PMID', 'display' => 'PMID'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'PURL', 'display' => 'PURL'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'UPC', 'display' => 'UPC'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'URL', 'display' => 'URL'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'URN', 'display' => 'URN'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relatedIdentifierType', 'value'=> 'w3Id', 'display' => 'w3Id'));
			// relationType
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsCitedBy', 'display' => 'Is Cited By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Cites', 'display' => 'Cites'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsSupplementTo', 'display' => 'Is Supplement To'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsSupplementedBy', 'display' => 'Is Supplemented By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsContinuedBy', 'display' => 'Is Continued By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Continues', 'display' => 'Continues'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsDescribedBy', 'display' => 'Is Described By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Describes', 'display' => 'Describes'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HasMetadata', 'display' => 'Has Metadata'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsMetadataFor', 'display' => 'Is Metadata For'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HasVersion', 'display' => 'Has Version'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsVersionOf', 'display' => 'Is Version Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsNewVersionOf', 'display' => 'Is New Version Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsPreviousVersionOf', 'display' => 'Is Previous Version Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsPartOf', 'display' => 'Is Part Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'HasPart', 'display' => 'Has Part'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsPublishedIn', 'display' => 'Is Published In'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsReferencedBy', 'display' => 'Is Referenced By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'References', 'display' => 'References'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsDocumentedBy', 'display' => 'Is Documented By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Documents', 'display' => 'Documents'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsCompiledBy', 'display' => 'Is Compiled By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Compiles', 'display' => 'Compiles'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsVariantFormOf', 'display' => 'Is Variant Form Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsOriginalFormOf', 'display' => 'Is Original Form Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsIdenticalTo', 'display' => 'Is Identical To'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsReviewedBy', 'display' => 'Is Reviewed By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Reviews', 'display' => 'Reviews'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsDerivedFrom', 'display' => 'Is Derived From'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsSourceOf', 'display' => 'Is Source Of'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsRequiredBy', 'display' => 'Is Required By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Requires', 'display' => 'Requires'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'IsObsoleteBy', 'display' => 'Is Obsolete By'));
		Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'relationType', 'value'=> 'Obsoletes', 'display' => 'Obsoletes'));
		
	}
}
