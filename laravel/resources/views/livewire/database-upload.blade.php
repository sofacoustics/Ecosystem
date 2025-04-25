<div> {{-- component div:START --}}
	<div x-data="{
		allFiles: [],
		filteredFiles: [],
		pendingFiles: [],
		uploading: false,
		nFiltered: 0,
		nUploaded: 0,
		nSelected: -1,
		progress: 0,
		progressText: '',
		finished: false,
		error: false,
		cancelled: false,
		status: '',
		directory: ''
		}" id='alpineComponent'>
	
	<form wire:submit="save">
		<h3>1) Select a directory with all your datafiles:</h3>
		<div>
			<x-button id="actual-directory-picker" x-bind:disabled="uploading" wire:ignore>Select a Directory</x-button>
			<input id="directory-picker" type="file" webkitdirectory directory style="display:none"
				x-bind:disabled="uploading"
				x-on:change="allFiles = Array.from($event.target.files);">
		</div>
		@if($nFilesInDir > 0)
			<p>Files in the selected directory: {{ $nFilesInDir }}</p>
		@elseif($nFilesInDir === 0)
			<p>No files in the selected directory.</p>
		@endif
		<br>
		<hr>
		
		<h3>2) Apply filter on your datafiles:<h3>
		<small>Pattern for the datasets names:</small>
			<input class="w-full" type="text" placeholder="Must include <ID>, e.g., name<ID>. Must not be empty." id="dsn_pattern"
				wire:model.blur="datasetnamefilter" />
		@foreach ($database->datasetdefs as $index => $datasetdef)
			<small>#{{ $loop->index+1}}: Pattern for the datafile names of {{ $datasetdef->name }}:</small>
				<input class="w-full" type="text" placeholder="Must include <ID> and may include <NUM> or <ANY>, e.g. prefix<ID>_maytest<ANY>.ext. Can be empty to exclude a datasetfile."
					id="fn_pattern{{ $datasetdef->id }}" wire:model.blur="datafilenamefilters.{{ $datasetdef->id }}" />
				{{-- https://www.perplexity.ai/search/how-can-i-access-and-update-ha-xiLcN4hYTKajSuuB3IMR4A --}}
		@endforeach 
		<small>Pattern for the datasets descriptions (optional):</small>
			<input class="w-full" type="text" placeholder="Must include <ID>, e.g., name<ID>. Can be empty." id="description_pattern"
				wire:model.blur="datasetdescriptionfilter" />
		<br>
		<div>
			<x-button wire:click="$js.doFilter($data)" :disabled="$nFilesInDir < 1 || $uploading">Apply filter</x-button>
		</div>
		<p>Analysis results:</p>
		<small><p id="analysis-summary" wire:ignore><br></p></small>
		<br>
		<hr>

		<h3>3) Prepare the filtering results for data upload:</h3>
		<table id="results" class="w-full table-auto border border-slate-399" wire:ignore>
			<thead class="bg-gray-50" >
				<tr>
					<th class="border p-2"><input type="checkbox" id="checkAll" wire:click="$js.checkAll()">: All</th>
					<th class="border p-2">ID</th>
					@foreach ($database->datasetdefs as $datasetdef)
						<th class="border p-2">{{ $datasetdef->name }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200 text-center">
				<!-- Rows will be added here -->
			</tbody>
		</table>
		<div class="extendable-text-container"><small>
			<p class="short-text" id="skipped" wire:ignore></p>
			<p class="long-text" id="skipped-list" wire:ignore></p></small>
		
			<style>
				.extendable-text-container {
					position: relative; /* Needed for absolute positioning if you want */
					width: 100%; /* Adjust as needed */
					border: 1px solid #ccc;
					padding: 2px;
				}

				.short-text {
					cursor: pointer; /* Indicate it's interactive */
				}

				.long-text {
					max-height: 0;
					overflow: hidden;
					transition: max-height 0.3s ease-in-out; /* Smooth transition */
					margin-top: 5px; /* Add some space when expanded */
				}

				.extendable-text-container:hover .long-text {
					max-height: 100%; /* Adjust to accommodate your longer text */
				}
			</style>
		</div>
		<br>
		<hr>

		<h3>4) Upload the datafiles:</h3>
		<label>Overwrite existing files?
				<input type="checkbox" wire:model.live="overwriteExisting">
		</label><br>

		<p>(Livewire) Status: {{ $status }}</p>
		<p>(Livewire) File to upload: {{ $nFilesToUpload }}</p>
		<p>(Alpine) Directory: <span x-text="directory"></span></p>
		<p>(Alpine) Status:  <span x-text="status"></span></p>
		<p>(Alpine) Files filtered: <span x-text="nFiltered"></span></p>
		<p id="nUploaded" wire:ignore></p>
		<p id="nUploadProgress" wire:ignore></p>

		<div x-cloak>
			<div class="bg-gray-100">
				<x-message show="cancelled" timeout="2000">The upload has been cancelled</x-message>
				<x-message type="error" show="error">Error: there was an error uploading. Please try again!</x-message>
				<x-message show="uploading">Uploading to server</x-message>
			</div>
		</div>
		<div>
			<p>Upload progress: <span x-text="progressText"></span></p>
			<div class="relative h-2 mt-2 rounded-full bg-base-200">
				<div
					x-bind:style="'width: ' + progress + '%;'"
					class="absolute top-0 left-0 h-full bg-orange-500 rounded-full">
				</div>
		</div>
		<div>
			<x-button wire:loading:attr="disabled" wire:click="$js.doUpload($data)" :disabled="!$canUpload">Start upload</x-button>
		</div>
		<br>
		<hr>
		
		<h3>5) Save the uploaded datafiles to the database:</h3>
		<x-button :disabled="$nFilesToUpload > $nFilesUploaded || $nFilesToUpload === 0" type="submit">Save files to database</x-button>

	</form>


@script
<script>
			
		////////////////////////////////////////////////////////////////////////////////
		//	Events
		////////////////////////////////////////////////////////////////////////////////
		
		// Trigger the actual directory picker when clicked on the fake but nicely looking button
	document.querySelector('#actual-directory-picker').addEventListener('click', e =>
	document.querySelector('#directory-picker').click());
		
		// Process the actual directory picker
	document.getElementById("directory-picker").addEventListener(
		"change",
		(e) => {
			console.log('EVENT: directory-picker: files: ', Array.from(e.target.files));
			const files = event.target.files;
			console.log('EVENT: directory-picker: # of files', files.length);
			if (files.length > 0) {
					// Extract the first file's relative path and get the directory name
				const firstFilePath = files[0].webkitRelativePath;
				const directoryName = firstFilePath.split('/')[0]; // First segment is the directory name
				let data = Alpine.$data(document.getElementById('alpineComponent'));
				data.directory = directoryName;
			}
			$wire.set("nFilesToUpload", 0);
			$wire.set('nFilesInDir', e.target.files.length); // set immediately using $wire.set(), otherwise set when next $wire.$refresh or another action that triggers a refresh. See
			document.getElementById("skipped").innerHTML = "";
			document.getElementById("skipped-list").innerHTML = "";
			document.getElementById("analysis-summary").innerHTML = "";
			tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
			tableBody.innerHTML = "";
			table = document.getElementById('results'); 
			console.log('Table:', table.style);
			table.style.visibility = "hidden";
		},
		false,
	);

		// Update the upload progress bar
	window.addEventListener('livewire-upload-progress', event => {
		console.log("EVENT: processing livewire-upload-progress event");
		@this.set('progress', event.detail.progress);
	});

		// On saved to database: Update the "data" structure 
	window.addEventListener('saved-to-database', event => {
		console.log('EVENT: saved-to-database (window)');
		let data = Alpine.$data(document.getElementById('alpineComponent'));
		resetUpload();
	});

		// On saved to database Wire update
	$wire.on('saved-to-database', () => {
		console.log('EVENT: saved-to-database ($wire.on)');
		document.getElementById("nUploadProgress").innerHTML = "";
	});

		// On the trigger of file upload
	$wire.on('upload-file', () => {
		//jw:todo use index parameter: https://livewire.laravel.com/docs/events
		console.log('EVENT: upload-file event triggered');
	});

		// On the trigger of upload finished
	$wire.on('upload-finished', () => {
		console.log('EVENT: upload-finished event triggered');
	});

		// On the trigger of upload progress
	$wire.on('upload-progress', () => {
		console.log('EVENT: upload-progress event triggered');
	});

		// On the trigger of upload start
	$wire.on('livewire-upload-start', () => {
		console.log('EVENT: livewire-upload-start event triggered');
	});

		// On the trigger of upload error
	$wire.on('livewire-upload-error', () => {
			console.log('EVENT: livewire-upload-error');
	});

		// On the trigger of any errors
	window.addEventListener('livewire:error', event => {
		console.error('EVENT: livewire:error:', event.detail);
		console.log('EVENT: livewire:error:', event.detail);
	});

		// On the trigger of upload error with details
	document.addEventListener('livewire:init', () => {
			Livewire.on('livewire-upload-error', (event) => {
					console.log('EVENT: livewire-upload-error', event.detail);
			});
	});

	
		////////////////////////////////////////////////////////////////////////////////
		//	Global variables
		////////////////////////////////////////////////////////////////////////////////
		
	let uploadQueue = []; // Upload queue, will be filled by ??? and processed by processQueue()

		////////////////////////////////////////////////////////////////////////////////
		//	Livewire functions
		////////////////////////////////////////////////////////////////////////////////
		
		
	$js('updateSelected', (data) => {
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0];
		let cnt = 0;
		rows = tableBody.rows; 
		let fn_array = $wire.get('pdatafilenames');
		console.log(fn_array);
		for (let i=1; i<rows.length; i++)
			if(document.getElementById("check"+i).checked) 
			{
				fn = fn_array[i-1];
				for (let col=0; col<fn.length; col++)
				{
					if(fn[col] != null)
						rows[i].cells[col+2].textContent = fn[col]; 
				}
			}
			else
			{
				for (let col=1; col<rows[0].cells.length; col++)
				{
					rows[i].cells[col].textContent = ""; 
				}
			}
	});
	
		// On "Check All Datasets" or "Check None of the Datasets"
	$js('checkAll', () => {
		var checkBox = document.getElementById("checkAll");
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
		if (checkBox.checked == true)
		{
			for (let i=1; i<tableBody.rows.length; i++)
				document.getElementById("check"+i).checked=true;
		}
		else
		{
			for (let i=1; i<tableBody.rows.length; i++)
				document.getElementById("check"+i).checked=false;
		}
	});
	
	
		// Apply the filter and prepare a table with filenames for the upload
	$js('doFilter', (data) => {
		if (data) {
			resetUpload();
			setStatus('Filtering started');
			mode = 0;
			let df_array = $wire.datasetdefIds; // get the dataset definition (=array with dataset filetypes)
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

			let dsn_pattern = document.getElementById("dsn_pattern").value; // pattern of the dataset names
	
			s="";
			const tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
			tableBody.innerHTML = "";

			let dsn_array = []; // array with filtered dataset names
			let fn_array = []; // 2D array of filtered filenames (outer dim: datasets, inner dim: datafile defs)
			let dsn_cnt = 0; skipped_cnt = 0;
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
						let name = dsn_pattern.replace("<ID>", id); // baue Name mit neuem ID zusammen
							// Array
						idx = dsn_array.indexOf(name); 
						if (idx == -1)
						{   // new item in the list
							dsn_array[dsn_array.length] = name; // extend the name array
							dsn_cnt++;
							idx = dsn_array.length-1;
							fn_array[dsn_array.length-1] = []; // extend the fn array with dummies
							x=dummy; x[j]=fn; // prepare the correct row
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
			
				// Display skipped filenames
			if(s!="") 
			{
				document.getElementById("skipped").innerHTML = "Hover to see the list of skipped files...";
				document.getElementById("skipped-list").innerHTML = s;
			}
			else
			{
				document.getElementById("skipped").innerHTML = "No skipped files...";
				document.getElementById("skipped-list").innerHTML = "";
			}
				// Display analysis summary
			mode_str=(mode)?("Nested"):("Flat");
			str = "" + 
				"<b>Files matched:</b> " + String(fn_cnt.reduce((a, b) => a + b)) + " files<br>" +
				"<b>Files missing:</b> " + String(dsn_cnt * df_array.length - fn_cnt.reduce((a, b) => a + b)) + " files<br>" +
				"<b>Files skipped:</b> " + String(skipped_cnt) + " files<br>"; 
			document.getElementById("analysis-summary").innerHTML = str;

				// Table - Summary row
			newRow = tableBody.insertRow(-1);
			cell = newRow.insertCell(-1);
			cell.textContent = "Sum:";
			cell = newRow.insertCell(-1); 
			cell.textContent = dsn_cnt; // insert count of Names
			for (let j=0; j<df_array.length; j++) // for each column
			{
					cell = newRow.insertCell(-1);
					cell.textContent = fn_cnt[j]; // insert the count of fns
			}
			
				// Table - Filenames
			for (let i=0; i<dsn_array.length; i++)
			{
				newRow = tableBody.insertRow(-1);
				cell = newRow.insertCell(-1); 
				cell.innerHTML = '<input type="checkbox" id="check' + (i+1) + '" wire:click="$js.updateSelected($data)">: #' + (i+1); // insert checkbox for a dataset
				cell = newRow.insertCell(-1); 
				cell.textContent = dsn_array[i]; // insert Name to the table
				for (let j=0; j<df_array.length; j++) // for each column
				{
					cell = newRow.insertCell(-1);
					cell.textContent = fn_array[i][j]; // insert fn to the specific cell
					if (fn_array[i][j]=="<NONE>")
					{ cell.style.backgroundcolor = "red"; // this does not work, I dont know why...
					}
				}
			}
			table = document.getElementById('results'); 
			table.style.visibility = "visible"; // show the table
			checkBox = document.getElementById("checkAll").checked; // select all

				// save dataset and datafile lists in Livewire
			$wire.set('pdatasetnames', dsn_array);
			console.log("dsn_array (pdatasetnames): ", dsn_array);
			data.nFiltered = fn_array.flat().length; // since this is a multi-dimensional array, we need to flatten it first for length to count all elemets

			// flat list of files to upload. This is a relative path
			// from the directory chosen as input. E.g. P0002/3DSCAN/P0002_watertight.stl
			let filenamesToUpload = fn_array.flat();
			console.log("filenamesToUpload: " + filenamesToUpload);
			console.log("filenamesToUpload.length: " + filenamesToUpload.length);
			// the data.allFiles 'name' is *just* the file name. There is a relative path,
			// but it also contains the parent folder. E.g. AXD-small/P0002/3DSCAN/P0002_watertight.stl
			console.log("allFiles: ", data.allFiles);
			$wire.set('pdatafilenames', fn_array);
			if(mode == 0) 
			{			// flat: get filtered list of file objects
					data.pendingFiles = data.allFiles.filter((file) => {
							return filenamesToUpload.includes(file.name);
					});
			}
			else
			{			// nested: prepend filenamesToUpload with directory for comparison with allFiles
					let prefix = data.directory+'/';
					dirPrefixed = filenamesToUpload.map(item => prefix + item);
					console.log('dirPrefixed: ', dirPrefixed);
					data.pendingFiles = data.allFiles.filter((file) => {
							return dirPrefixed.includes(file.webkitRelativePath);
					});

			}
			console.log("data.pendingFiles.length: " + data.pendingFiles.length);
			console.log("data.allFiles.length: " + data.allFiles.length);

			// set number of files to upload, so Livewire knows how many
			// files to expect.
			$wire.set('nFilesToUpload', data.pendingFiles.length);

			setStatus('Filtering finished');
		} else
			console.log('doFilter() - data parameter *does not* exist!');
	});

		// Process the upload
	$js('doUpload', (data) => {
		resetUpload();
		data.error = false;
		$wire.set('canUpload', false);
		$wire.set('uploading', true); // set immediately. This is used within the Livewire component
		data.uploading = true; // use this for input button state (enabled/disabled)
		setStatus("Upload started");

		let uploads = Object.values($wire.uploads);
		let offset = uploads.length;
		//console.log("doUpload() - existing upload count: ", offset);
		// https://fly.io/laravel-bytes/multi-file-upload-livewire/
		data.pendingFiles.forEach( (file, index) => { uploadQueue.push({ index, file }); } );
		console.log("uploadQueue.length: ", uploadQueue.length);
		processQueue();
		setStatus('Upload finished!?');
	});
	
		// Cancel an upload (jw:note not used yet (no button), but works.)
	$js('cancelUpload', (data) => {
		console.log('User has cancelled the upload');
		resetUpload();
	});
	

		////////////////////////////////////////////////////////////////////////////////
		//	Javascript functions
		////////////////////////////////////////////////////////////////////////////////

	let maxParallelUploads = 2; // Maximum concurrent uploads

	function processQueue() {
		//console.log('processQueue():start');
		while (uploadQueue.length > 0 && maxParallelUploads > 0) {
			const { index, file } = uploadQueue.shift();
			maxParallelUploads--;
			let data = Alpine.$data(document.getElementById('alpineComponent'));

			@this.upload( 'uploads.' + index,
				file,
				() => { 
						/* Success handler */
					data.nUploaded++;
						setStatus("File " + index + " now successfully uploaded");
						data.progressText = data.nUploaded + "/" + $wire.nFilesToUpload + " files successfully uploaded";
						maxParallelUploads++; // Free up a slot
						// if this is the last upload, set 'uploading' to false
						if(data.nUploaded == $wire.nFilesToUpload)
						{
								data.uploading = false; // finished
								setStatus("Uploading is now finished. You may save the files to the database!");
						}
						processQueue(); // Process next in queue
				},
				(error) => {
						/* Error handler */
						setStatus("Error " + index + " (" + error + ")");
						data.error = true;
						resetUpload();
				},
				(progress) => {
						/* Progress updates */
						setStatus("Uploading " + index);
						data.progress = event.detail.progress;
				},
				() => {
						/* cancelled handler */
						setStatus("Cancelled " + index);
				}
			);
		}
	}

		// set both Livewire, Alpine and inner HTML status.
	function setStatus(string)
	{
			console.log("Status (alpine): ", string);
			let data = Alpine.$data(document.getElementById('alpineComponent'));
			data.status = string;
	};

	function resetUpload()
	{
		$wire.resetUploads();
		let data = Alpine.$data(document.getElementById('alpineComponent'));
		data.progress = 0;
		data.progressText = '';
		data.nUploaded = 0;
		data.uploading = false;
		uploadQueue = [];
		maxParallelUploads = 2; // Maximum concurrent uploads
		setStatus("resetUpload()");
	}

	</script>
@endscript
	</div>
</div> {{-- component div:END --}}
