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
							// create columns
            $table->id();
						$table->unsignedBigInteger('commentable_id');
						$table->string('commentable_type');
						$table->string('creatorName');
						$table->string('givenName')->nullable();
						$table->string('familyName')->nullable();
						$table->string('nameIdentifier')->nullable();
						$table->unsignedInteger('nameIdentifierSchemeIndex')->nullable();
						$table->string('creatorAffiliation')->nullable();
						$table->string('affiliationIdentifierScheme')->nullable();
						$table->string('affiliationIdentifier')->nullable();
            $table->timestamps();
							// link tables
						//$table->foreign('database_id')->references('id')->on('databases')->onDelete('cascade');
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
