@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@can('update', $database)
		@if ($subjectarea) 
			<h3>Edit a subject area:</h3>
		@else
			<h3>Add a new subject area:</h3>
		@endif
		<form wire:submit.prevent="save">
			<div class="block">
						
			  <label class="{{ $labelClass }}" for="subjectareaindex">Subject Area:</label>
							<select wire:model.live="controlledSubjectAreaIndex">
								<option value="">Select a subject area...</option>
								@foreach(\App\Models\Database::subjectareasList() as $r => $t)
										<option value="{{ $r }}">{{ $t->display }}</option>
								@endforeach
							@error('subjectareaindex') <span class="text-red-500">{{ $message }}</span> @enderror
							</select>
							
							<input wire:model="additionalSubjectArea" type="text" 
								placeholder="Optional free text."
								id="additionalSubjectArea"
								class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
							@error('additionalSubjectArea') <span class="text-red-500">{{ $message }}</span> @enderror

			<div class="mt-4">
				<button type="submit" class="{{ $buttonClass }}">
					{{ $subjectarea ? 'Update Subject Area' : 'Create Subject Area' }}
				</button>
			</div>

		</form>
	@endcan
</div>
