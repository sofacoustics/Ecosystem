<div>
    @can('update', $dataset)
        <form wire:submit="save">

            <div x-data="{ uploading: false, progress: 0, finished: false }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false; finished = true"
                x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <!-- File Input -->
                <div x-show="!uploading">
                    <input type="file" max-size="10" wire:model="file">
                </div>

                {{-- use x-cloak so that progress bar doesn't pop up on page load before alpine variables are set --}}
                <div x-cloak>
                    <!-- Progress Bar -->
                    {{-- <div x-show="uploading"> <progress max="100" x-bind:value="progress"></progress> </div> --}}
                    <div x-show="uploading">
                        <div class="w-full h-4 bg-slate-100 rounded-lg shadow-inner mt-3">
                            <div class="bg-green-500 h-4 rounded-lg" :style="{ width: `${progress}%` }"></div>
                        </div>
                    </div>
                    <div class="bg-gray-100">
                        <div wire:loading>
                            Livewire communication with server
                        </div>
                        <div x-show="uploading">
                            Uploading to server
                        </div>
                        <div x-show="finished">
                            Upload finished
                        </div>
                        {{--
                        <div wire:loading wire:target="file">
                            Updating file ...
                        </div>
                        --}}
                    </div>
                </div>
                <x-error attribute='file' />
                {{-- <button type="submit">Save file</button> --}}
            </div>
        </form>
    @endcan
</div>
