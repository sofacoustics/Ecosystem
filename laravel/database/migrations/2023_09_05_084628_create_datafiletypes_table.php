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
        Schema::create('datafiletypes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('default_widget')->nullable();
            $table->string('extension')->nullable();
            $table->string('mimetypes')->nullable(); // comma separated list of valid mime types, e.g. "image/jpg, image/png" (see // https://www.iana.org/assignments/media-types/media-types.xhtml or https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datafiletypes');
    }
};
