<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Widget;
use App\Models\Datafiletype;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		// Create a pivot table linking Datafiletypes with Widgets
		Schema::create('datafiletype_widget', function (Blueprint $table) {
			$table->foreignId('datafiletype_id')->constrained()->onDelete('cascade');
			$table->foreignId('widget_id')->constrained()->onDelete('cascade');
			$table->boolean('is_active')->default(true);
			$table->primary(['datafiletype_id', 'widget_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('datafiletype_widget');
	}
};
