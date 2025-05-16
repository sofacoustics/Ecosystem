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
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('commentable_id');
						$table->string('commentable_type');
						$table->string('publisherName');
						$table->string('nameIdentifier')->nullable();
						$table->unsignedInteger('nameIdentifierSchemeIndex')->nullable();
						$table->string('schemeURI')->nullable();
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
        Schema::dropIfExists('publishers');
    }
};
