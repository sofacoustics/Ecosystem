<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Data\RadardatasetpureData;

use App\Models\MenuItem;
use App\Models\Database;
use App\Models\Radardatasetrightsholder;
use App\Models\Radar\Metadataschema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        Database::create(array(
            'title' => 'ARI B',
						'subtype' => 'Spatial acoustics',
            'description' => 'Specialty: hrtfs, dtfs, and photos of the ears',
            'radardataset' => RadardatasetpureData::from('{
                "id": "f3dxp4vswdc2xe7z",
                "parentId": "aIoFEMniUZoLoDyh",
                "descriptiveMetadata": {
                    "title": "ARI B",
                    "productionYear": "2024",
                    "publishers": {
                        "publisher": [
                            {
                                "value": "Acoustics Research Institute",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Austrian Academy of Sciences",
                                "nameIdentifierScheme": "ROR",
                                "schemeURI": "https://ror.org/",
                                "nameIdentifier": "https://ror.org/03anc3s24"
                            }
                        ]
                    },
                    "creators": {
                    "creator": [
                            {
							    "creatorName": "Austrian Academy of Sciences",
								"givenName": null,
								"familyName": null,
								"nameIdentifier": [
									{
										"value": "https://ror.org/03anc3s24",
										"schemeURI": "https://ror.org/",
										"nameIdentifierScheme": "ROR"
									}
								],
								"creatorAffiliation": null
							},
							{            
								"creatorName": "Stuefer, Jonathan",
                                "givenName": "Jonathan",
                                "familyName": "Stuefer",
                                "nameIdentifier": [],
                                "creatorAffiliation": null
                            },
                            {
                                "creatorName": "Majdak, Piotr",
                                "givenName": "Piotr",
                                "familyName": "Majdak",
                                "nameIdentifier": [
                                    {
                                        "value": "0000-0003-1511-6164",
                                        "schemeURI": "http://orcid.org/",
                                        "nameIdentifierScheme": "ORCID"
                                    }
                                ],
                                "creatorAffiliation": {
                                    "value": "Austrian Academy of Sciences",
                                    "schemeURI": "https://ror.org/",
                                    "affiliationIdentifierScheme": "ROR",
                                    "affiliationIdentifier": "https://ror.org/03anc3s24"
                                }
                            }
                        ]
                    },
                    "subjectAreas": {
                        "subjectArea": [
                            {
                                "controlledSubjectAreaName": "COMPUTER_SCIENCE",
                                "additionalSubjectAreaName": null
                            },
                            {
                                "controlledSubjectAreaName": "OTHER",
                                "additionalSubjectAreaName": "The second free text subject area"
                            }
                        ]
                    },
                    "resource": {
                        "value": "Acoustics",
                        "resourceType": "AUDIOVISUAL"
                    },
                    "rights": {
                        "controlledRights": "CC_BY_4_0_ATTRIBUTION",
                        "additionalRights": null
                    },
                    "rightsHolders": {
                        "rightsHolder": [
                            {
                                "value": "Stuefer",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Majdak",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Austrian Academy of Sciences",
                                "nameIdentifierScheme": "ROR",
                                "schemeURI": "https://ror.org/",
                                "nameIdentifier": "https://ror.org/03anc3s24"
                            }
                        ]
                    }
                }
            }'),
            'user_id' => 1));
        Database::create(array('title' => 'ARI BezierPPM',
						'subtype' => 'Geometries',
            'description' => 'Specialty: csv, dtfs and hrtfs',
            'radardataset' => RadardatasetpureData::from('{
                "id": null,
                "parentId": "aIoFEMniUZoLoDyh",
                "descriptiveMetadata": {
                    "title": "ARI BezierPPM",
                    "productionYear": "2024",
                    "publishers": {
                        "publisher": [
                            {
                                "value": "Acoustics Research Institute",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Austrian Academy of Sciences",
                                "nameIdentifierScheme": "ROR",
                                "schemeURI": "https://ror.org/",
                                "nameIdentifier": "https://ror.org/03anc3s24"
                            }
                        ]
                    },
                    "creators": {
                        "creator": [
                            {
                                "creatorName": "Stuefer, Jonathan",
                                "nameIdentifier": [
                                    {
                                        "value": "https://ror.org/0387prb75",
                                        "schemeURI": "https://ror.org/",
                                        "nameIdentifierScheme": "ROR"
                                    }
                                ],
                                "creatorAffiliation": null
                            },
                            {
                                "creatorName": "Majdak, Piotr",
                                "givenName": "Piotr",
                                "familyName": "Majdak",
                                "nameIdentifier": [
                                    {
                                        "value": "0000-0003-1511-6164",
                                        "schemeURI": "http://orcid.org/",
                                        "nameIdentifierScheme": "ORCID"
                                    }
                                ],
                                "creatorAffiliation": {
                                    "value": "Austrian Academy of Sciences",
                                    "schemeURI": "https://ror.org/",
                                    "affiliationIdentifierScheme": "ROR",
                                    "affiliationIdentifier": "https://ror.org/03anc3s24"
                                }
                            }
                        ]
                    },
                    "subjectAreas": {
                        "subjectArea": [
                            {
                                "controlledSubjectAreaName": "COMPUTER_SCIENCE",
                                "additionalSubjectAreaName": null
                            },
                            {
                                "controlledSubjectAreaName": "OTHER",
                                "additionalSubjectAreaName": "The second free text subject area"
                            }
                        ]
                    },
                    "resource": {
                        "value": "Acoustics",
                        "resourceType": "AUDIOVISUAL"
                    },
                    "rights": {
                        "controlledRights": "CC_BY_4_0_ATTRIBUTION",
                        "additionalRights": null
                    },
                    "rightsHolders": {
                        "rightsHolder": [
                            {
                                "value": "Stuefer",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Majdak",
                                "nameIdentifierScheme": "OTHER",
                                "schemeURI": null,
                                "nameIdentifier": null
                            },
                            {
                                "value": "Austrian Academy of Sciences",
                                "nameIdentifierScheme": "ROR",
                                "schemeURI": "https://ror.org/",
                                "nameIdentifier": "https://ror.org/03anc3s24"
                            }
                        ]
                    }

                }
            }'),
            'user_id' => 1, ));
        //Database::create(array('name' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'user_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
        $this->call([
            DatasetSeeder::class,
            DatafiletypeSeeder::class,
            WidgetSeeder::class,
            DatasetdefSeeder::class,
            DatafileSeeder::class,
            MenuItemSeeder::class,
            MetadataschemaSeeder::class,
            /*
            RadardatasetresourcetypeSeeder::class,
            RadardatasetsubjectareaSeeder::class,
            RadardatasetrightsholderSeeder::class,
            RadardatasetSeeder::class,
             */
        ]);

    }
}
