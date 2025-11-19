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
      DB::table('datafiletypes')->where('id', 8)->update([
				'name' => 'Human and alike: Image',
				'description' => 'Photo of a human or alike (JPG, PNG, WEBP, or SVG file)',
				'extension' => '.jpg,.png,.webp,.svg',
				'mimetypes' => 'image/jpeg,image/png,image/webp,image/svg+xml',
			]);
			
      DB::table('widgets')->where('id', 3)->update([
				'description' => 'Display an image (PNG, JPG, WEBP, SVG, animated or static).',
			]);			
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafiletype', function (Blueprint $table) {
            //
        });
    }
};
