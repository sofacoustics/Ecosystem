<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('database_id');
						$table->string('creatorName');
						$table->string('givenName')->nullable();
						$table->string('familyName')->nullable();
						$table->string('nameIdentifier')->nullable();
						$table->enum('nameIdentifierScheme', ['ORCID', 'ROR'])->nullable();
						$table->string('creatorAffiliation')->nullable();
						$table->enum('affiliationIdentifierScheme', ['ROR'])->nullable();
						$table->string('affiliationIdentifier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creators');
    }
};
