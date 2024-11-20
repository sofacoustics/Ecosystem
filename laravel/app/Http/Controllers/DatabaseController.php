<?php

namespace App\Http\Controllers;

use Carbon;

use App\Models\User;
use App\Models\Database;
use App\Models\Radardataset;
use App\Models\Radardatasetresourcetype;

use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;

use App\Data\RadardatasetData;
use App\Data\RadardatasetpureData;
use App\Data\RadardatasetresourcetypeData;
use App\Data\RadardatasetsubjectareaData;
use App\Data\RadarcreatorData;
use App\Data\RadarpublisherData;

class DatabaseController extends Controller
{
    public function __construct()
    {
        // https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
        //$this->middleware('auth', ['only' => ['create', 'edit']]);
        // Users must be authenticated for all functions except index and show.
        // Guests will be redirected to login page
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $databases = \App\Models\Database::all();

        //        //jw:tmp
        $testjson = '{
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
                                "creatorName": "Stuefer, Jonathan",
                                "givenName": null,
                                "familyName": null,
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
                }';


        /* 
        $datasetdata = new RadardatasetpureData(title: 'title',
            creators: [
                new RadarcreatorData(
                    givenName: 'Jonathan',
                    familyName: 'Stuefer',
                    creatorAffiliation: 'Austrian Academy of Sciences',
                ),
                new RadarcreatorData(
                    givenName: 'Piotr', 
                    familyName: 'Majdak',
                    nameIdentifier: '0000-0003-1511-6164',
                    creatorAffiliation: 'Austrian Academy of Sciences',
                ),
            ],
            publishers: [
                new RadarpublisherData(
                    name: 'Piotr Majdak', 
                    orcidid: '0000-0003-1511-6164'
                )
            ],
            productionYear: '2024',
            subjectAreas: [
                new RadardatasetsubjectareaData(
                    controlledSubjectAreaName: 'Other',
                    additionalSubjectAreaName: 'Mathematics',
                ),
               new RadardatasetsubjectareaData(
                    controlledSubjectAreaName: 'Other',
                    additionalSubjectAreaName: 'Acoustics',
                ),
            ],
            resource: new RadardatasetresourcetypeData(
                resourceType: 'Other',
                value: 'Acoustics',
                createdAt: Carbon\CarbonImmutable::now(),
                updatedAt: Carbon\CarbonImmutable::now(),
                ),

        ); //print_r($datasetdata);
         */

        //$datasetdata = RadardatasetpureData::from('{"id":"f3dxp4vswdc2xe7z","parentId":"aIoFEMniUZoLoDyh","createdDate":"2024-10-10T08:54:51.490311157Z","lastModifiedDate":"2024-11-19T12:27:25.428109229Z","state":"PENDING","oaiUrl":null,"uploadUrl":"https://datathektest.oeaw.ac.at/radar-ingest/upload/f3dxp4vswdc2xe7z/file","ingestUrl":"https://datathektest.oeaw.ac.at/radar-ingest/upload/f3dxp4vswdc2xe7z/ingest","technicalMetadata":{"retentionPeriod":0,"archiveDate":null,"archiveCreator":null,"responsibleEmail":null,"embargoEndingDate":null,"numberOfPendingNotificationMailsSent":1,"reviewToken":null,"size":0,"access":null,"blocked":false,"blockMessage":null,"schema":{"key":"RDDM","version":"9.1"},"oaiUrl":null,"uploadToken":null,"uploadFolder":null},"descriptiveMetadata":{"identifier":null,"alternateIdentifiers":null,"relatedIdentifiers":null,"creators":{"creator":[{"creatorName":"Stuefer, Jonathan","givenName":null,"familyName":null,"nameIdentifier":[{"value":"https://ror.org/0387prb75","schemeURI":"https://ror.org/","nameIdentifierScheme":"ROR"}],"creatorAffiliation":null},{"creatorName":"Majdak, Piotr","givenName":"Piotr","familyName":"Majdak","nameIdentifier":[{"value":"0000-0003-1511-6164","schemeURI":"http://orcid.org/","nameIdentifierScheme":"ORCID"}],"creatorAffiliation":{"value":"Austrian Academy of Sciences","schemeURI":"https://ror.org/","affiliationIdentifierScheme":"ROR","affiliationIdentifier":"https://ror.org/03anc3s24"}}]},"contributors":null,"title":"ARI B mod","additionalTitles":null,"descriptions":null,"keywords":null,"publishers":{"publisher":[{"value":"Acoustics Research Institute","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Austrian Academy of Sciences","nameIdentifierScheme":"ROR","schemeURI":"https://ror.org/","nameIdentifier":"https://ror.org/03anc3s24"}]},"productionYear":"2024","publicationYear":null,"language":null,"subjectAreas":{"subjectArea":[{"controlledSubjectAreaName":"COMPUTER_SCIENCE","additionalSubjectAreaName":null},{"controlledSubjectAreaName":"OTHER","additionalSubjectAreaName":"The second free text subject area"}]},"resource":{"value":"Acoustics","resourceType":"AUDIOVISUAL"},"geoLocations":null,"dataSources":null,"software":null,"processing":null,"rights":{"controlledRights":"CC_BY_4_0_ATTRIBUTION","additionalRights":null},"rightsHolders":{"rightsHolder":[{"value":"Stuefer","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Majdak","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Austrian Academy of Sciences","nameIdentifierScheme":"ROR","schemeURI":"https://ror.org/","nameIdentifier":"https://ror.org/03anc3s24"}]},"relatedInformations":null,"fundingReferences":null},"descriptiveMetadataCorrection":null,"archive":null}');
        $brokenJSON = '{"id":"f3dxp4vswdc2xe7z","parentId":"aIoFEMniUZoLoDyh","createdDate":"2024-10-10T08:54:51.490311157Z","lastModifiedDate":"2024-11-19T12:27:25.428109229Z","state":"PENDING","oaiUrl":null,"uploadUrl":"https://datathektest.oeaw.ac.at/radar-ingest/upload/f3dxp4vswdc2xe7z/file","ingestUrl":"https://datathektest.oeaw.ac.at/radar-ingest/upload/f3dxp4vswdc2xe7z/ingest","technicalMetadata":{"retentionPeriod":0,"archiveDate":null,"archiveCreator":null,"responsibleEmail":null,"embargoEndingDate":null,"numberOfPendingNotificationMailsSent":1,"reviewToken":null,"size":0,"access":null,"blocked":false,"blockMessage":null,"schema":{"key":"RDDM","version":"9.1"},"oaiUrl":null,"uploadToken":null,"uploadFolder":null},"descriptiveMetadata":{"identifier":null,"alternateIdentifiers":null,"relatedIdentifiers":null,"creators":{"creator":[{"creatorName":"Stuefer, Jonathan","givenName":null,"familyName":null,"nameIdentifier":[{"value":"https://ror.org/0387prb75","schemeURI":"https://ror.org/","nameIdentifierScheme":"ROR"}],"creatorAffiliation":null},{"creatorName":"Majdak, Piotr","givenName":"Piotr","familyName":"Majdak","nameIdentifier":[{"value":"0000-0003-1511-6164","schemeURI":"http://orcid.org/","nameIdentifierScheme":"ORCID"}],"creatorAffiliation":{"value":"Austrian Academy of Sciences","schemeURI":"https://ror.org/","affiliationIdentifierScheme":"ROR","affiliationIdentifier":"https://ror.org/03anc3s24"}}]},"contributors":null,"title":"ARI B mod","additionalTitles":null,"descriptions":null,"keywords":null,"publishers":{"publisher":[{"value":"Acoustics Research Institute","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Austrian Academy of Sciences","nameIdentifierScheme":"ROR","schemeURI":"https://ror.org/","nameIdentifier":"https://ror.org/03anc3s24"}]},"productionYear":"2024","publicationYear":null,"language":null,"subjectAreas":{"subjectArea":[{"controlledSubjectAreaName":"COMPUTER_SCIENCE","additionalSubjectAreaName":null},{"controlledSubjectAreaName":"OTHER","additionalSubjectAreaName":"The second free text subject area"}]},"resource":{"value":"Acoustics","resourceType":"AUDIOVISUAL"},"geoLocations":null,"dataSources":null,"software":null,"processing":null,"rights":{"controlledRights":"CC_BY_4_0_ATTRIBUTION","additionalRights":null},"rightsHolders":{"rightsHolder":[{"value":"Stuefer","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Majdak","nameIdentifierScheme":"OTHER","schemeURI":null,"nameIdentifier":null},{"value":"Austrian Academy of Sciences","nameIdentifierScheme":"ROR","schemeURI":"https://ror.org/","nameIdentifier":"https://ror.org/03anc3s24"}]},"relatedInformations":null,"fundingReferences":null},"descriptiveMetadataCorrection":null,"archive":null}';
        RadardatasetpureData::validate(json_decode($brokenJSON, true));
        //dd($datasetdata);

        // Test Model -> Data
        //$resource_type = RadarresourcetypeData::from(Radardatasetresourcetype::find(1));
        //dd($resource_type);
        //$dataset = RadardatasetData::from(Radardataset::find(1));
        //dd($dataset);
        //$dataset = Radardataset::find(1);
        //dd($dataset);
        //
        //$dataset = RadardatasetData::from(Radardataset::find(1));
        //$test = RadardatasetData::from(Radardataset::find(1));
        //dd($test);
        //$test->toJson();

        //jw:tmp test creating database from JSON
        $databaseJSON='{"title": "ARI B RADAR dataset","publishers": {
        "publisher": [
            {
                "id": 1,
                "value": "Stuefer",
                "name_identifier_scheme": "OTHER",
                "scheme_URI": null,
                "name_identifier": null,
                "radardataset_id": 1,
                "created_at": "2024-10-18T10:27:12.000000Z",
                "updated_at": "2024-10-18T10:27:12.000000Z"
            },
            {
                "id": 2,
                "value": "Majdak",
                "name_identifier_scheme": "OTHER",
                "scheme_URI": null,
                "name_identifier": null,
                "radardataset_id": 1,
                "created_at": "2024-10-18T10:27:12.000000Z",
                "updated_at": "2024-10-18T10:27:12.000000Z"
            }
        ]
    },
    "rightsHolders": {
        "rightsHolder": [
            {
                "id": 1,
                "value": "Stuefer",
                "name_identifier_scheme": "OTHER",
                "scheme_URI": null,
                "name_identifier": null,
                "radardataset_id": 1,
                "created_at": "2024-10-18T10:27:12.000000Z",
                "updated_at": "2024-10-18T10:27:12.000000Z"
            },
            {
                "id": 2,
                "value": "Majdak",
                "name_identifier_scheme": "OTHER",
                "scheme_URI": null,
                "name_identifier": null,
                "radardataset_id": 1,
                "created_at": "2024-10-18T10:27:12.000000Z",
                "updated_at": "2024-10-18T10:27:12.000000Z"
            }
        ]
    }
}';
        $databaseArray = json_decode($databaseJSON, true);
        $databaseArray['database_id'] = 1;
        $databaseArray['id'] = 1;
        //dd($databaseArray);
        //$testdatabase = Radardataset::updateOrCreate($databaseArray);

      
        $radardatasetpureJSON = '{
            "title": "ARI B RADAR dataset",
            "publishers": {
                "publisher": [
                    {
                        "value": "OEAW",
                        "nameIdentifierScheme": "OTHER",
                        "schemeURI": null,
                        "nameIdentifier": null
                    },
                    {
                        "value": "TU Wien",
                        "nameIdentifierScheme": "OTHER",
                        "schemeURI": null,
                        "nameIdentifier": null
                    }
                ]
                }
        }';

        if(json_validate($radardatasetpureJSON)) {
            $radardatasetpureArray = json_decode($radardatasetpureJSON, true);
            //dd($radardatasetpureArray);
            $radardatasetpureData = RadardatasetpureData::from($radardatasetpureJSON);
            //dd($radardatasetpureData);
        }


        return view('databases.index', ['allDatabases' => $databases]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Database::class);
        // https://pusher.com/blog/laravel-mvc-use/#controllers-creating-our-controller
        return view('databases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatabaseRequest $request)
    {
        // An alternative - where rules are in the model: https://medium.com/@konafets/a-better-place-for-your-validation-rules-in-laravel-f5e3f5b7cc

        Database::create($request->validated()); // https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12

        return redirect('databases')->with('success', "Database $request->title successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Database $database)
    {
        //jw:tmp
        //dd($database->radardataset->rules());
        //dd($database->radardataset);
        //$database->radardataset::validate(null, $database->radardataset, $database->radardataset->rules());
        //jw:tmp:end
        $user = \App\Models\User::where('id', $database->user_id)->first();
        return view('databases.show',[ 'database' => $database, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Database $database)
    {
        $this->authorize($database);
        //$this->authorize('update', $database);
/*        if(auth()->user()->cannot('edit', $database)) {
            abort(403);
        }
        */
        //
        return view('databases.edit', [ 'database' => $database ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        $this->authorize($database);
        /*if($request->user()->cannot('update', $database)) {
            abort(403);
        }    */

        //dd($request);
        $radardataset = RadardatasetpureData::from($request);
        //dd($radardataset);
        //jw:tmp test updating radar value
        //$database->radardataset->title = 4
        //
        //$request->merge(['radardataset' => $radardataset]);

        $database->update(['radardataset' => $radardataset]);
        //$database->update($request->except('_method', '_token', 'submit'));
        return redirect('databases')->with('success', 'Database successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Database $database)
    {
        $this->authorize($database);
        // delete database. Note that due to onDelete('cascade') in files database, the related files
        // will be deleted too!
        $database->delete();
        return redirect()->route('databases.index')->with('success', 'Database deleted successfully');
    }
}
