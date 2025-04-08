<div> {{-- component div:START --}}
	<x-property name="Database">{{ $database->title }}</x-property>
	<p>The {{ $database->title }} currently contains {{ $datasetsCount }}</p>
	<ul class="list-disc list-inside">
		@forelse($database->datasets as $dataset)
			<li>
				<x-dataset.list link='true' datafiles='true' :dataset="$dataset" />
			</li>
		@empty
			<li>There are no datasets associated with this database</li>
		@endforelse
	</ul>
	@if (count($database->datasets))
		<x-button wire:click="resetDatasets" class="flex items-center gap-1">Reset Datasets</x-button>
	@endif
	<div x-data="{
		allFiles: [],
		filteredFiles: [],
		pendingFiles: [],
		uploadStarted: false,
		uploadFinished: false,
		uploading: false,
		nUploaded: 0,
		nUploading: 0,
		nSelected: 0,
		progress: 0,
		finished: false,
		error: false,
		cancelled: false
	}" id="alpineComponent">
		{{--
		<p>Steps to bulk uploading files:</p>
		<ol class="list-decimal list-inside">
			<li>Select the directory you want to upload files from. This must contain all the files (does not recurse to
				subdirectories yet)</li>
			<li>Apply the filters you have specified.</li>
			<li>Start the upload. Note that only files from the filtered list will actually be uploaded.</li>
			<li>Save the files to the database.</li>
		</ol>
		--}}
		<label>Dataset name pattern
			<input class="w-full" type="text" placeholder="E.g. name<ID>" id="name_pattern"
				wire:model.blur="datasetnamefilter" />
		</label>
		{{-- just working for one datasetdef at the moment --}}
		<ul class="list-disc list-inside">
			@foreach ($database->datasetdefs as $index => $datasetdef)
				<li>{{ $datasetdef->name }}
					<label>Datafile name pattern
						<input class="w-full" type="text" placeholder="E.g. prefix<ID>.ext"
							id="fn_pattern{{ $datasetdef->id }}"
							wire:model.blur="datafilenamefilters.{{ $datasetdef->id }}" />
						{{-- https://www.perplexity.ai/search/how-can-i-access-and-update-ha-xiLcN4hYTKajSuuB3IMR4A --}}
					</label>
				</li>
			@endforeach
		</ul>
		<label>Would you like to overwrite existing files?
			<input type="checkbox" wire:model.live="overwriteExisting">
		</label>

		<form wire:submit="save">
			{{-- BUTTONS --}}
			<h3>BUTTONS</h3>
			<div x-show="!$wire.uploading">
				<input id="directory-picker" type="file" webkitdirectory directory
					x-on:change="allFiles = Array.from($event.target.files);">
			</div>
			<x-button wire:click="$js.piotrsFilter($data)" :disabled="$nFilesSelected == 0 || $uploading" class="flex items-center gap-1">Apply filter</x-button>
			<x-button wire:loading:attr="disabled" wire:click="$js.doPiotrUpload($data)" :disabled="!$canUpload">Start
				upload</x-button>
			<x-button :disabled="$nFilesToUpload > $nFilesUploadedByEvent || $nFilesToUpload === 0" type="submit">Save files to database</x-button>
			<h3>STATUS</h3>
			<h4>Livewire Properties:</h4>
			<p>Status: {{ $status }}</p>
			<p>nFilesFiltered: {{ $nFilesFiltered }}</p>
			<p>nFilesToUpload: {{ $nFilesToUpload }}</p>
			<h4>Alpine:</h4>
			<p>Status: <span id="status" wire:ignore></span></p>
			<p id="nUploaded" wire:ignore></p>
			<p id="nUploadProgress" wire:ignore></p>
			{{--
			<progress max="100" :style="`width: ${progress}%;`" />
			--}}
		</form>

		<h3>Analysis results:</h3>
		<p id="mode" wire:ignore></p>

		<h3>Details:</h3>
		<table id="results" class="w-full text-left even:bg-red odd:bg-green" wire:ignore>
			<thead>
				<tr>
					<th>#</th>
					<th>ID</th>
					@foreach ($database->datasetdefs as $datasetdef)
						<th>{{ $datasetdef->name }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<!-- Rows will be added here -->
			</tbody>
		</table>
		<p id="skipped" wire:ignore></p>

		@script
			<script>
				////////////////////////////////////////////////////////////////////////////////
				//	Events
				////////////////////////////////////////////////////////////////////////////////
				document.getElementById("directory-picker").addEventListener(
					"change",
					(e) => {
						console.log('EVENT: directory-picker event listener:', Array.from(e.target.files));
						console.log('EVENT: directory-picker event listener: length', e.target.files.length);
						$wire.set('nFilesSelected', e.target.files
							.length
						); // set immediately using $wire.set(), otherwise set when next $wire.$refresh or another action that triggers a refresh. See
					},
					false,
				);

				window.addEventListener('livewire-upload-progress', event => {
					console.log("EVENT: processing livewire-upload-progress event");
					@this.set('progress', event.detail.progress);
				});

				window.addEventListener('saved-to-database', event => {
					console.log('EVENT: saved-to-database (window)');
				});

				$wire.on('saved-to-database', () => {
					console.log('EVENT: saved-to-database ($wire.on)');
					document.getElementById("nUploadProgress").innerHTML = "";
				});

				$wire.on('upload-file', () => {
					//jw:todo use index parameter
					// .https://livewire.laravel.com/docs/events
					console.log('EVENT: upload-file event triggered');
				});

				$wire.on('upload-finished', () => {
					console.log('EVENT: upload-finished event triggered');
				});

				$wire.on('upload-progress', () => {
					console.log('EVENT: upload-progress event triggered');
				});

				$wire.on('livewire-upload-start', () => {
					console.log('EVENT: livewire-upload-start event triggered');
				});

				document.addEventListener("upload-job", async function(event) {
					console.log('EVENT: upload-job event triggered', event.detail);
					await new Promise(resolve => setTimeout(resolve, 60000)); // 10s delay
					let data = Alpine.$data(document.getElementById('alpineComponent'));
					let nPending = data.pendingFiles.length;
					console.log("       upload-job: nPending: ", nPending);
					file = data.pendingFiles.at(event.detail.index);
					let uploads = Object.values($wire.uploads);
					let offset = uploads.length;
					let index = event.detail.index;
					console.log("       upload-job: uploading ", file, " (index: ", index, " offset+index: ", (
						index + offset), ")");
					// https://www.perplexity.ai/search/in-php-if-dd-displays-an-array-oyxbqrAVQPK_4xxPBLYF8Q?4=d#4
					@this.upload(
						'uploads.' + (index + offset),
						file,
						finish = (n) => {
							console.log('upload-job: this.upload() finished');
							data.nUploaded++;
							//document.getElementById("nUploaded").innerHTML = "File upload count: " + data
							//	  .nUploaded + "/" + $wire.nFilesToUpload;
						}, error = () => {}, progress = (event) => {
							let html = document.getElementById("nUploadProgress").innerHTML;
							document.getElementById("nUploadProgress").innerHTML = html + ".";
							//document.getElementById("nUploadProgress").innerHTML = data.nUploaded + " files have been uploaded";
						}
					);
				});
				//                document.querySelector('[x-data]').addEventListener('upload-job', (e) => {
				//                        console.log('EVENT: upload-job');
				//                        const alpineData = e.target.__x.getUnobservedData();
				//                        console.log(alpineData);
				//                });
				//data.pendingFiles.forEach((file, index) => {
				//await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate delay
				//console.log("upload-job Event handled!");
				////////////////////////////////////////////////////////////////////////////////
				//	Functions
				////////////////////////////////////////////////////////////////////////////////

				$js('doPiotrUpload', (data) => {
					console.log("doPiotrUpload() started");
					$wire.resetUploads();
					$wire.set('canUpload', false);
					data.uploading = true; // use this for button state (enabled/disabled)
					// either @this.set('uploading', true) or $wire.uploading = true works.
					$wire.set('uploading', true); // set immediately
					//@this.set('uploading', true);
					$wire.set('status', 'Uploading');
					data.nUploaded = 0;
					data.uploadStarted = true;
					data.uploadFinished = false;

					// flat list of files to upload
					let filenamesToUpload = $wire.pdatasetfilenames.flat();
					//console.log("filenamesToUpload (flat): ", filenamesToUpload);
					//jw:todo filter data.allFiles using $wire.pdatasetfilenames
					data.pendingFiles = data.allFiles.filter((file) => {
						return filenamesToUpload.includes(file.name);
					});
					//console.log("data.allFiles: ", data.allFiles);
					//console.log("data.pendingFiles: ", data.pendingFiles);

					// set number of files to upload, so we can say we're finished
					// when $this->uploads == $this->nFilesToUpload in Livewire component
					$wire.set('nFilesToUpload', data.pendingFiles.length);

					//$wire.status = "Uploading";
					let uploads = Object.values($wire.uploads);
					let offset = uploads.length;
					console.log("  uploads.length", uploads.length);
					// https://fly.io/laravel-bytes/multi-file-upload-livewire/
					document.getElementById("nUploadProgress").innerHTML = "";
					for( let i = 0; i < data.pendingFiles.length; i++) {
					//data.pendingFiles.forEach((file, index) => {
						console.log("data.pendingFiles.length: ", data.pendingFiles.length);
						//$wire.dispatch('upload file ', index);
						//const event = new Event("upload-job");
						/*
						const event = new CustomEvent("upload-job", {
							detail: {
								index: index
							}, // Custom data
							bubbles: true, // Allows the event to bubble up through the DOM
							cancelable: true // Allows the event to be canceled
						});
						document.dispatchEvent(event); // For global events
						*/

						//document.querySelector('[x-data]').dispatchEvent(event); // For global events

						// https://sinnbeck.dev/posts/making-a-complete-file-uploader-with-progressbar-using-livewire-and-alpinejs
						data.isUploading = true;
						//document.getElementById("nUploaded").innerHTML = "File upload count: " + data.nUploaded + "/" + $wire.nFilesToUpload;
						let status = "Upload started";
						$wire.set('status', status);
						document.getElementById("status").innerHTML = status;
						@this.uploadMultiple('uploads', data.pendingFiles,
							function(success) { //upload was a success and was finished
								data.nUploaded++;
								data.uploading = false;
								$wire.set('nFilesUploadedByEvent', data.nUploaded);
								let status = 'uploadMultiple:  uploading ' + (data.nUploading) + '/' + $wire.nFilesToUpload + '. ' + data.nUploaded + ' files successfully uploaded';
								$wire.set('status', status);
								document.getElementById("status").innerHTML = status;
								//document.getElementById("nUploaded").innerHTML = "File upload count: " + data.nUploaded + "/" + $wire.nFilesToUpload;

							//document.getElementById("       data.uploading = false
								//$this.progress = 0
								data.progress = 0;
							},
							function(error) { //an error occured
								console.log('error', error)
							},
							function(event) { //upload progress was made
								data.nUploading = data.nUploaded + 1;
								let status = 'uploadMultiple:  uploading ' + (data.nUploading) + '/' + $wire.nFilesToUpload + '. ' + data.nUploaded + ' files successfully uploaded';
								$wire.set('status', status);
								document.getElementById("status").innerHTML = status;
								//$this.progress = event.detail.progress
								data.progress = event.detail.progress;
								//let html = document.getElementById("nUploadProgress").innerHTML;
								//document.getElementById("nUploadProgress").innerHTML = html + ".";
							}
						)



						/* //jw:note moved to upload-job event
						console.log("doPiotrUpload(): uploading ", file, " (index: ", index, " offset+index: ", (
							index + offset), ")");
						// https://www.perplexity.ai/search/in-php-if-dd-displays-an-array-oyxbqrAVQPK_4xxPBLYF8Q?4=d#4
						@this.upload(
							'uploads.' + (index + offset),
							file,
							finish = (n) => {
								console.log('doPiotrUpload() - this.upload() finished');
								data.nUploaded++;
								//document.getElementById("nUploaded").innerHTML = "File upload count: " + data
								//	  .nUploaded + "/" + $wire.nFilesToUpload;
							}, error = () => {}, progress = (event) => {
								let html = document.getElementById("nUploadProgress").innerHTML;
								document.getElementById("nUploadProgress").innerHTML = html + ".";
								//document.getElementById("nUploadProgress").innerHTML = data.nUploaded + " files have been uploaded";
							}
						);
						*/
					//});
					} // end of for loop
					console.log("doPiotrUpload() finished");
				});
				$js('piotrsFilter', (data) => {
					console.log('piotrsFilter()');
					if (data) {
						console.log('piotrsFilter() - data', data);
						$wire.resetUploads();
						mode = 0;
						console.log('$datasetdefIds: ', $wire.datasetdefIds);
						let df_array = $wire.datasetdefIds;
						console.log('df_array', df_array);
						let fn_filter_array = [], postfix_array = [], beg_id_array = [], dummy = [], fn_cnt = [];
						for (let i=0; i<df_array.length; i++)
						{
							let fn_pattern = document.getElementById("fn_pattern"+df_array[i]).value;
							fn_pattern = fn_pattern.split('\\').join('/');
							let fn_filter = fn_pattern.replace(/\[/g, "\\[");
							fn_filter = fn_filter.replace(/\]/g, "\\]"); 
							fn_filter = fn_filter.replace(/\^/g, "\\^");
							fn_filter = fn_filter.replace(/\./g, "\\."); 
							fn_filter = fn_filter.replace(/\$/g, "\\$"); 
							fn_filter = fn_filter.replace(/\(/g, "\\(");
							fn_filter = fn_filter.replace(/\)/g, "\\)"); 
							fn_filter = fn_filter.replace(/<NUM>/g, "[0-9]+"); 
							fn_filter = fn_filter.replace(/<ANY>/g, ".+"); 
							fn_filter = RegExp(fn_filter.replace(/<ID>/g, ".+"));
							fn_filter_array[i]=fn_filter;

							if (mode == 0 && fn_pattern.indexOf("/") >= 0) mode=1;

							let end_filter = fn_pattern.indexOf("ID>")+3; // find the end of the ID
							let postfix = fn_pattern.substring(end_filter); // hole den postfix, d.h., text nach <ID> raus
							postfix = postfix.replace(/\[/g, "\\["); 
							postfix = postfix.replace(/\]/g, "\\]"); 
							postfix = postfix.replace(/\^/g, "\\^"); 
							postfix = postfix.replace(/\./g, "\\.");
							postfix = postfix.replace(/\$/g, "\\$");
							postfix = postfix.replace(/\(/g, "\\(");
							postfix = postfix.replace(/\)/g, "\\)");
							postfix = postfix.replace(/<NUM>/g, "[0-9]+");
							postfix = RegExp(postfix.replace(/<ANY>/g, ".+"));
							postfix_array[i]=postfix; 
							let beg_id = fn_pattern.indexOf("<"); // zahl anfang: index von < in fn_pattern
							beg_id_array[i]=beg_id;
							console.log([fn_pattern, fn_filter, postfix, beg_id, end_filter]);

							dummy[i] = "<NONE>";
							fn_cnt[i] = 0;
						}
						
						let name_pattern = document.getElementById("name_pattern").value;
				
						s="<b>Skipped:</b><br>";
						const tableBody = document.getElementById('results').getElementsByTagName('tbody')[0];
						tableBody.innerHTML = "";

						let name_array = [], fn_array = []; name_cnt = 0; skipped_cnt = 0;
						for (let i = 0; i < data.allFiles.length; i++)
						{   
							if (mode == 1)
							{   // we have a directory in the pattern
								fn = data.allFiles[i].webkitRelativePath;
								fn = fn.substring(fn.indexOf("/")+1); // remove the root directory
							}
							else
							{	// we don't have a directory, use the filename
								fn = data.allFiles[i].name;
							}
							skipped=1;
							for (let j=0; j<df_array.length; j++)
							{
								let hit = fn_filter_array[j].test(fn);
								if (hit)
								{
									skipped=0;
									let end_id = fn.substring(beg_id_array[j]).search(postfix_array[j])+beg_id_array[j]; // zahl ende: beginn von postfix gefunden in fn, beginnend mit beg_id, falls postfix im fn VOR <id> wäre
									let id = fn.substring(beg_id_array[j],end_id); // <ID> gefunden
									let name = name_pattern.replace("<ID>", id); // baue Name mit neuem ID zusammen
										// Array
									idx = name_array.indexOf(name); 
									if (idx == -1)
									{   // new item in the list
										name_array[name_array.length] = name; // extend the name array
										name_cnt++;
										idx = name_array.length-1;
										fn_array[name_array.length-1] = []; // extend the fn array with dummies
										x=dummy; x[j]=fn; // prepare the correct row
										//for (k=0; k<df_array.length; k++) fn_array[name_array.length-1][k] = x[k];
										fn_array[idx][j] = fn;
										fn_cnt[j]++;
									}
									else
									{	fn_array[idx][j] = fn; 
										fn_cnt[j]++;
									}
								} // if hit
							} // for all fn_patterns
							if(skipped) 
							{
								s = s + fn + "<br>"; 
								skipped_cnt++;
							}
						} // for all fns
						document.getElementById("skipped").innerHTML = s;

						mode_str=(mode)?("Nested"):("Flat");
						str = "<b>Mode: " + mode_str + "</b><br>" +
						  "<b>Matched:</b> " + String(fn_cnt.reduce((a, b) => a + b)) + " files<br>" +
							"<b>Missing:</b> " + String(name_cnt * df_array.length - fn_cnt.reduce((a, b) => a + b)) + " files<br>" +
							"<b>Skipped:</b> " + String(skipped_cnt) + " files<br>"; 
						document.getElementById("mode").innerHTML = str;

							// Table - Summary row
						newRow = tableBody.insertRow(-1);
						cell = newRow.insertCell(-1); 
						cell.textContent = "Sum:"; 
						cell = newRow.insertCell(-1); 
						cell.textContent = name_cnt; // insert count of Names
						for (let j=0; j<df_array.length; j++) // for each column
						{
								cell = newRow.insertCell(-1);
								cell.textContent = fn_cnt[j]; // insert the count of fns
						}
						
							// Table - Filenames
						for (let i=0; i<name_array.length; i++)
						{
							newRow = tableBody.insertRow(-1);
							cell = newRow.insertCell(-1);
							cell.textContent = i+1; // insert the index
							cell = newRow.insertCell(-1); 
							cell.textContent = name_array[i]; // insert Name to the table
							for (let j=0; j<df_array.length; j++) // for each column
							{
									cell = newRow.insertCell(-1);
									cell.textContent = fn_array[i][j]; // insert fn to the specific cell
									if (fn_array[i][j]=="<NONE>") 
									{ cell.style.backgroundcolor = "red"; // this does not work, I dont know why...
									}
							}
						}
						console.log(tableBody); //jw:tmp
						$wire.set('pdatasetnames', name_array);
						console.log('$wire.pdatasetnames: ', $wire.pdatasetnames);
						$wire.set('pdatasetfilenames', fn_array);
						console.log('$wire.pdatasetfilenames: ', $wire.pdatasetfilenames);
					} else
						console.log('piotrsFilter() - data parameter *does not* exist!');
				});
			</script>
		@endscript
	</div>
</div> {{-- component div:END --}}
