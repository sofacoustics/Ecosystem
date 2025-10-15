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
        Schema::table('databases', function (Blueprint $table) {
            //
					$table->text('descriptionabstract', 5000)->nullable()->change();
					$table->text('descriptiongeneral', 5000)->nullable()->change();
					$table->text('descriptionmethods', 5000)->nullable()->change();
					$table->text('descriptionremarks', 5000)->nullable()->change();
        });

       Schema::table('tools', function (Blueprint $table) {
            //
					$table->text('descriptionabstract', 5000)->nullable()->change();
					$table->text('descriptiongeneral', 5000)->nullable()->change();
					$table->text('descriptionmethods', 5000)->nullable()->change();
					$table->text('descriptionremarks', 5000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            //
					$table->string('descriptionabstract', 500)->nullable()->change();
					$table->string('descriptiongeneral', 500)->nullable()->change();
					$table->string('descriptionmethods', 500)->nullable()->change();
					$table->string('descriptionremarks', 500)->nullable()->change();
        });
				
        Schema::table('tools', function (Blueprint $table) {
            //
					$table->string('descriptionabstract', 500)->nullable()->change();
					$table->string('descriptiongeneral', 500)->nullable()->change();
					$table->string('descriptionmethods', 500)->nullable()->change();
					$table->string('descriptionremarks', 500)->nullable()->change();
        });
    }
};
