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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('database_id');
						$table->string('keywordName');
						$table->unsignedInteger('keywordSchemeIndex')->nullable();
						$table->string('schemeURI')->nullable();
						$table->string('valueURI')->nullable();
						$table->string('classificationCode')->nullable();
            $table->timestamps();
							// link tables
						$table->foreign('database_id')->references('id')->on('databases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
