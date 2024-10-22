<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 *
 * Note: 'rightsHolders' object created in serializeJSON function:
 * RADAR Json
 * 
 * 
 * 
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
                },
 * 
 * 
 */


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('radardatasetrightsholders', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('name_identifier_scheme');
            $table->string('scheme_URI')->nullable();
            $table->string('name_identifier')->nullable();
            $table->foreignId('radardataset_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radardatasetrightsholders');
    }
};
