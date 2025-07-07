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
        Schema::create('service_logs', function (Blueprint $table) {
			$table->id();
			$table->integer('exit_code');
			$table->string('exit_code_text');
			$table->text('stdout');
			$table->text('stderr');
			$table->double('execution_time');
			// 'service' settings
            $table->string('name'); // displayed in GUI
            $table->string('description'); // displayed in GUI
			$table->string('exe');
			$table->string('parameters');
			// relationships
			$table->foreignId('service_id')->constrained()->onDelete('cascade');
			$table->foreignId('datafile_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_logs');
    }
};
