<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Datafile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::table('datafiles', function (Blueprint $table) {
			    $table->string('mimetype')->nullable();
		});

		foreach (Datafile::all() as $datafile) {
			// Access $file properties or methods here
			$absolutepath = $datafile->absolutepath();
			$mimetype = File::mimeType("$absolutepath");
			$datafile->mimetype = $mimetype;
			$datafile->save();
		}

		Schema::table('datafiles', function (Blueprint $table) {
			$table->string('mimetype')->nullable(false)->change();
		});

	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafile', function (Blueprint $table) {
			 $table->dropColumn('mimetype');
        });
    }
};
