<div>
    @can('update', $tool)
        <form wire:submit="save">

            <div x-data="{ uploading: false, progress: 0, finished: false, error: false, cancelled: false }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false; finished = true"
                x-on:livewire-upload-cancel="uploading = false; finished = false; progress = 0; cancelled = true"
                x-on:livewire-upload-error="uploading = false; finished = false; error = true"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <!-- File Input -->
                <div x-show="!uploading">
                    <input type="file" wire:model="file">
                </div>

                {{-- use x-cloak so that progress bar doesn't pop up on page load before alpine variables are set --}}
                <div x-cloak>
                    <!-- Progress Bar -->
                    {{-- <div x-show="uploading"> <progress max="100" x-bind:value="progress"></progress> </div> --}}
                    <div x-show="uploading">
                        <div class="w-full h-4 bg-slate-100 rounded-lg shadow-inner mt-3">
                            <div class="bg-green-500 h-4 rounded-lg" :style="{ width: `${progress}%` }"></div>
                        </div>
                        <!-- Cancel upload button -->
                        <x-button type="button" wire:click="$cancelUpload('file')">Cancel Upload</x-button>
                    </div>
                    <div class="bg-gray-100">
                        {{-- <div wire:loading>Livewire communication with server</div> --}}
                        <x-message show="cancelled" timeout="2000">The upload has been cancelled</x-message>
                        <x-message show="error">Error: there was an error uploading</x-message>
                        <x-message show="uploading">Uploading to server</x-message>
                        {{-- <x-message show="finished" timeout="5000">Upload finished</x-message> --}}
                    </div>
                </div>
                <x-error attribute='file' />
                {{-- <button type="submit">Save file</button> --}}
            </div>
        </form>
    @endcan
</div>
