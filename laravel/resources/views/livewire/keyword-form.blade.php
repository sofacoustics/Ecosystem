@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = 'text-gray-700 mb-2 block font-bold';
    $selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
    $inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
    @can('update', $database)
        @if ($keyword) 
            <h3>Edit a keyword:</h3>
        @else
            <h3>Add a new keyword:</h3>
        @endif
        <form wire:submit.prevent="save">
            <div class="block">
						
							<input wire:model="keywordName" type="text" id="keywordName" required
								class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
							@error('keywordName') <span class="text-red-500">{{ $message }}</span> @enderror

              <label class="{{ $labelClass }}" for="keywordSchemeIndex">Keyword:</label>
							<select wire:model.live="keywordSchemeIndex">
								<option value="">Select a keyword scheme...</option>
								@foreach(\App\Models\Keyword::keywordSchemeList() as $key=>$val)
										<option value="{{ $key }}">{{ $val }}</option>
								@endforeach
							@error('keywordSchemeIndex') <span class="text-red-500">{{ $message }}</span> @enderror
							</select>
							
              <label class="{{ $labelClass }}" for="schemeURI">Scheme URI:</label>
							<input wire:model="schemeURI" type="text" id="schemeURI"
								class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
							@error('schemeURI') <span class="text-red-500">{{ $message }}</span> @enderror

              <label class="{{ $labelClass }}" for="valueURI">URI:</label>
							<input wire:model="valueURI" type="text" id="valueURI"
								class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
							@error('valueURI') <span class="text-red-500">{{ $message }}</span> @enderror

              <label class="{{ $labelClass }}" for="classificationCode">Classification Code:</label>
							<input wire:model="classificationCode" type="text" id="classificationCode"
								class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
							@error('classificationCode') <span class="text-red-500">{{ $message }}</span> @enderror

            <div class="mt-4">
                <button type="submit" class="{{ $buttonClass }}">
                    {{ $keyword ? 'Update keyword' : 'Create keyword' }}
                </button>
            </div>

        </form>
    @endcan
</div>
