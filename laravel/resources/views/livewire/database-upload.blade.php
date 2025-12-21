<div> {{-- component div:START --}}
	<div 
		x-data="{
			  nFilesExisting: @entangle('nFilesExisting'),

			  nFilesInDir: -1, // Number of files in the local directory. -1: not picked yet.
  			allFiles: [], // All files from the picked local directory
	  		directory: '', // Root of the local directory  
				
				dirMode: 0, // -1: filter not applied yet, 0: flat directory structure, 1: nested directory structure
				maxDisplayDatasets: 100, // Largest number of Datasets displayed in the table
				allDatasetNames: [], // 1D Array with dataset names
				allDatasetDescriptions: [], // 1D Array with dataset descriptions
				allDatafileNames: [], // 2D Array with datafile names per dataset
				allDatasetExisting: [], // 1D Array with true if that dataset already exists
				allDatasetdefIdsExisting: [], // 2D Array with datasetdefIDs of datafiles that exist
				nDatasetsFound: -1, // Number of datasets found by Apply Filter. -1: Filter not applied yet.
				selDatasetDescriptions: [], // 1D Array with selected dataset descriptions. 
				selDatasetNames: [], // Array with selected dataset names. 
				selDatafileNames: [], // Array with selected datafile names. 
				selDatasetExisting: [], // 1D Array with true if selected dataset already exists
				selDatasetdefIdsExisting: [], // 2D Array with datasetdefIDs of selected datafiles that exist
				
			  pendingFiles: [], // Array with pending filenames, i.e., files to be uploaded
			  pendingFilesMetadata: [], // Structure with arrays of pending datafiles, i.e., to be created/updated
				
			  uploading: false,
			  nUploaded: 0,
			  progress: 0,
			  progressText: '',		
			  status: '',
			overwriteExisting: @entangle('overwriteExisting'),
				nDatasetsToUpload: 0,
				nDatasetsToOverwrite: 0,
  			nFilesToUpload: 0,
				nFilesToOverwrite: 0,
				
			finished: false,
			error: false,
			cancelled: false,
		}" 
		
		x-init="setTimeout(() => { $wire.calculateExisting(); }, 100)"
			
		id='alpineComponent'>

	<form>
	
		<div x-show="nFilesExisting == -1">Stand by while the information about the database is collected...</div>
		<div x-show="nFilesExisting > -1">
			<p>We found <b><span x-text="nFilesExisting"></span></b> datafiles already present in your Ecosystem database.</p>	
		
			<h3>1) Pick a local directory with all your datafiles:</h3>
			<p>Maximal size per file: 2 GB</p>		
			<div>
				<x-button id="actual-directory-picker" 
								x-bind:class="nFilesToUpload == 0 | uploading? '{{ $buttonColorDisabled }}' : '{{ $buttonColorEnabled }}'"
								wire:ignore>Select a Directory</x-button>
				<input id="directory-picker" type="file" webkitdirectory directory style="display:none"
																			x-bind:disabled="uploading">
			</div>
			<span x-show="nFilesInDir == -1">After picking the directory, please stand by while we parse the directory structure...</span>
			<span x-show="nFilesInDir == 0">Directory not picked or no files in the selected directory. Nothing to do...</span>
		</div>
				
		<template x-if="nFilesInDir > 0"><div>
			<p>We found <b><span x-text="nFilesInDir"></span></b> files in the local directory.</p>
			<br>
			<hr>

			<h3>2) Apply filter on your datafiles:<h3>
			<small>Pattern for the datasets names: (use &lt;ID&gt; to encode an identifier changing with each dataset)</small>
				<input class="w-full" type="text" placeholder="Can include <ID>, e.g., name <ID>. Must not be empty." id="dsn_pattern" required
					wire:model.blur="datasetnamefilter" />
			@foreach ($database->datasetdefs as $index => $datasetdef)
				<small>#{{ $loop->index+1}}: Pattern for the datafile names of <b>{{ $datasetdef->name }}</b>:</small>
					<input class="w-full" type="text" placeholder="Can include <ID>, <NUM> or <ANY>, e.g. prefix<ID>_maytest<ANY>.ext. Can be empty to exclude a datasetfile."
						id="fn_pattern{{ $datasetdef->id }}" wire:model.blur="datafilenamefilters.{{ $datasetdef->id }}" />
			@endforeach
			<small>Pattern for the datasets descriptions (optional):</small>
				<input class="w-full" type="text" placeholder="Can include <ID>, e.g., description <ID>. Can be empty." id="description_pattern"
					wire:model.blur="descriptionfilter" />
			<br>
			<x-button wire:click="$js.doFilter($data)" x-bind:disabled="uploading">Apply filter</x-button>
			
			<div x-show="nDatasetsFound >= 0">
				<p>Analysis results:</p>
				<small><p id="analysis-summary" wire:ignore><br></p></small>
				<br>
				<hr>
				<h3>3) Prepare the filtering results for data upload:</h3>
				<small><p id="table-hint" wire:ignore><br></p></small>
				<table id="results" wire:ignore class="w-full table-auto border border-slate-399" >
					<thead class="bg-gray-50" >
						<tr>
							<th class="border p-2"><input type="checkbox" id="checkAll" wire:click="$js.checkAll($data)">: All</th>
							<th class="border p-2">Dataset Name</th>
							@foreach ($database->datasetdefs as $datasetdef)
								<th class="border p-2">{{ $datasetdef->name }}</th>
							@endforeach
						</tr>
						<tr>
							<th class="border p-2">Count:</th>
							<th class="border p-2">-</th>
							@foreach ($database->datasetdefs as $datasetdef)
								<th class="border p-2">-</th>
							@endforeach
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200 text-center">
						<!-- Rows will be added here -->
					</tbody>
				</table>

				<div class="read-more-container">
					<small style="text-align: right;">
						<input type="checkbox" id="read-more-toggle" wire:ignore class="read-more-checkbox">
						<label for="read-more-toggle" class="read-more-label" wire:ignore>
								<span class="read-more-text" id="skipped" wire:ignore></span>
								<span class="read-less-text" wire:ignore>Showing only first 40 files. Click to close the list...</span>
						</label>
						<div class="read-more-content">
								<p class="hidden-content" id="skipped-list" wire:ignore></p>
						</div>
					</small>

					<style>
						
						.read-more-checkbox { /* Hide the actual checkbox input from view, but keep it accessible for screen readers */
								position: absolute;
								opacity: 0;
								pointer-events: none; /* Prevents it from receiving mouse events */
						}
						.hidden-content { /* Initially hide the 'hidden-content' part */
								display: none;
						}
						.read-less-text { /* Hide the 'Read less' text initially */
								display: none;
						}
						.read-more-label { /* Style the button-like label */
							display: block;
							cursor: pointer;
							//color: #007bff;
							//margin-top: 10px;
							font-weight: bold;
							text-align: right;
						}
						/* Logic for when the checkbox is checked */
						.read-more-checkbox:checked ~ .read-more-content .hidden-content { /* When the checkbox is checked, display the hidden content */
								display: block;
						}
						.read-more-checkbox:checked ~ .read-more-label .read-more-text { /* When the checkbox is checked, hide the 'Read more' text */
								display: none;
						}
						.read-more-checkbox:checked ~ .read-more-label .read-less-text { /* When the checkbox is checked, display the 'Read less' text */
								display: inline;
						}
						.read-more-content { 
								max-height: 100px; /* A placeholder max-height for a transition effect */
								overflow: hidden;
								transition: max-height 0.3s ease-in-out;
						}
						.read-more-checkbox:checked ~ .read-more-content {
								max-height: 1000px; /* A very large max-height to ensure all content is visible */
						}
						.read-more-checkbox:focus + .read-more-label { /* For better accessibility, style the label when the checkbox is focused */
								outline: 2px solid #007bff;
								outline-offset: 2px;
						}					
					</style>
				</div>

				<!--label>Overwrite existing files?
					<input type="checkbox" x-model="overwriteExisting" @click="$js.toggleOverwriteExisting($data)">
				</label><br-->
				<small>
					<p><b>Datasets selected to create/update:</b> <span x-text="nDatasetsToUpload"></span> datasets</p>
					<p><b>Datasets that will be overwritten:</b> <span x-text="nDatasetsToOverwrite"></span> datasets</p>
					<p><b>Datafiles selected for upload:</b> <span x-text="nFilesToUpload"></span> files</p>
					<p><b>Datafiles that will be overwritten:</b> <span x-text="nFilesToOverwrite"> files</span></p>
				</small>
				<br>
				<hr>


				<h3>4) Upload the datafiles:</h3>

				<div>
					<button
						x-bind:disabled="uploading || nFilesToUpload == 0"
						@click="$js.doUpload($data)"
						x-text="uploading? 'Uploading...' : 'Start upload'"
						class="{{ $buttonStyle }}"
						x-bind:class="nFilesToUpload == 0 | uploading? '{{ $buttonColorDisabled }}' : '{{ $buttonColorEnabled }}'"
						>Start upload
					</button>
				</div>
				<div x-show="uploading">
					<p> Status:  <span x-text="status"></span></p>
					<p>Files pending upload: <span x-text="nFilesToUpload"></span></p>
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
					</div>
				<br>
				<hr>


				</div>
			</div> <!-- datasets found --> 
		</div></template>	<!-- files in local directory -->
	</form>

	@hasrole('admin')
		<p><small>(Livewire) Status: {{ $status }}</small></p>
		<p><small>(Alpine+Livewire) nFilesExisting: <span x-text="nFilesExisting"></span></small></p>
		<p><small>(Alpine) nFilesInDir: <span x-text="nFilesInDir"></span></small></p>
		<p><small>(Alpine) Directory: <span x-text="directory"></span></small></p>
		<p><small>(Alpine) dirMode: <span x-text="dirMode"></span></small></p>
		<p><small>(Alpine) maxDisplayDatasets: <span x-text="maxDisplayDatasets"></span></small></p>
		<p><small>(Alpine) nDatasetsFound: <span x-text="nDatasetsFound"></span></small></p>
		<p><small>(Alpine) uploading: <span x-text="uploading"></span></small></p>
		<p><small>(Alpine) nUploaded: <span x-text="nUploaded"></span></small></p>
		<p><small>(Alpine) progress: <span x-text="progress"></span></small></p>
		<p><small>(Alpine) progressText: <span x-text="progressText"></span></small></p>
		<p><small>(Alpine) status: <span x-text="status"></span></small></p>
		<p><small>(Alpine+Livewire) overwriteExisting: <span x-text="overwriteExisting"></span></small></p>
		<p><small>(Alpine) nDatasetsToUpload: <span x-text="nDatasetsToUpload"></span></small></p>
		<p><small>(Alpine) nDatasetsToOverwrite: <span x-text="nDatasetsToOverwrite"></span></small></p>
		<p><small>(Alpine) nFilesToUpload: <span x-text="nFilesToUpload"></span></small></p>
		<p><small>(Alpine) nFilesToOverwrite: <span x-text="nFilesToOverwrite"></span></small></p>
	@endhasrole

@script
<script>
			
		////////////////////////////////////////////////////////////////////////////////
		//	Events
		////////////////////////////////////////////////////////////////////////////////
		
		// Trigger the actual directory picker when clicked on the fake but nicely looking button
	document.querySelector('#actual-directory-picker').addEventListener('click', e =>
	{ 
		let data = Alpine.$data(document.getElementById('alpineComponent'));
		setTimeout(() => { document.querySelector('#directory-picker').click(); } ,0); 
	});

		// Processes the actual directory picker
		//   Updated variables: allFiles, directory, nFilesInDir
	document.getElementById("directory-picker").addEventListener(
		"change",
		(e) => {
			console.log("directory-picker: change");
			let files = Array.from(event.target.files);
				// sort alphabetically
			files.sort((a, b) => a.webkitRelativePath.localeCompare(b.webkitRelativePath));
			let data = Alpine.$data(document.getElementById('alpineComponent'));
			data.allFiles = files;
			data.nFilesInDir = files.length;
			if (files.length > 0) { // Extract the first file's relative path and get the directory name					
				const firstFilePath = files[0].webkitRelativePath;
				data.directory = firstFilePath.split('/')[0]; // First segment is the directory name;
			}
			data.nDatasetsFound = -1; // Apply filter not applied yet
			 // clear the form
			a = document.getElementById("skipped"); if (a!=null) a.innerHTML = "";
			a = document.getElementById("skipped-list"); if (a!=null) a.innerHTML = "";
			a = document.getElementById("analysis-summary"); if (a!=null) a.innerHTML = "";
			a = tableBody = document.getElementById('results'); if (a!=null) a.getElementsByTagName('tbody')[0].innerHTML = "";
		},
		false,
	);

		// Update the upload progress bar
	window.addEventListener('livewire-upload-progress', event => {
		debugConsole("EVENT: processing livewire-upload-progress event");
		@this.set('progress', event.detail.progress);
	});

		// On the trigger of file upload
	$wire.on('upload-file', () => {
			//jw:todo use index parameter: https://livewire.laravel.com/docs/events
		debugConsole('EVENT: upload-file event triggered');
	});

		// On the trigger of upload finished
	$wire.on('upload-finished', () => {
		debugConsole('EVENT: upload-finished event triggered');
	});

		// On the trigger of upload progress
	$wire.on('upload-progress', () => {
		debugConsole('EVENT: upload-progress event triggered');
	});

		// On the trigger of upload start
	$wire.on('livewire-upload-start', () => {
		debugConsole('EVENT: livewire-upload-start event triggered');
	});

		// On the trigger of upload error
	$wire.on('livewire-upload-error', () => {
			debugConsole('EVENT: livewire-upload-error');
	});

		// On the trigger of any errors
	window.addEventListener('livewire:error', event => {
		console.error('EVENT: livewire:error:', event.detail);
		debugConsole('EVENT: livewire:error:', event.detail);
	});

		// On the trigger of upload error with details
	document.addEventListener('livewire:init', () => {
			Livewire.on('livewire-upload-error', (event) => {
					debugConsole('EVENT: livewire-upload-error', event.detail);
			});
	});

	
	////////////////////////////////////////////////////////////////////////////////
	//	Global variables
	////////////////////////////////////////////////////////////////////////////////
		
	let uploadQueue = []; // Upload queue, will be filled by ??? and processed by processQueue()
	let uploadStart = 0;  // The time the upload started. Used for duration calculation
	let debugLevel = 1; // set to 1 to turn debugging console messages on. set to 2 to list all files. 



	////////////////////////////////////////////////////////////////////////////////
	//	Livewire functions
	////////////////////////////////////////////////////////////////////////////////

	$js('updateSelected', (data) => {
		data = _updateSelected(data);
	});

		// On "Check All Datasets" or "Check None of the Datasets"
	$js('checkAll', (data) => {
		data = _checkAll(data);
	});
			
		// Apply the filter and prepare tables with filenames for the upload
		//  Updated variables: dirMode, 
	$js('doFilter', (data) => 
	{
		console.time("Parse files");
		// load the pattern of the dataset names and description
		let dsn_pattern = document.getElementById("dsn_pattern").value.trim();
		let descr_pattern = document.getElementById("description_pattern").value.trim();

		if(dsn_pattern.length==0)
		{
			window.alert("Dataset name must not be empty");
			return;
		}

		data.dirMode = 0;
		let df_array = $wire.datasetdefIds; // get the dataset definition (=array with dataset filetypes)
		let fn_filter_array = [], postfix_array = [], beg_id_array = [], dummy = [], fn_cnt_array = [];
		for (let i=0; i<df_array.length; i++)
		{
			let fn_pattern = document.getElementById("fn_pattern"+df_array[i]).value.trim();
			if (fn_pattern == "")
			{	// empty pattern --> ignore
				fn_filter_array[i]="";
				postfix_array[i]="";
				beg_id_array[i]=0;
			}
			else
			{	// nonempty pattern --> create filters
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
				fn_filter = "^" + fn_filter; // ensure that pattern starts from beginning of the file name only
				fn_filter = RegExp(fn_filter.replace(/<ID>/g, ".+"));
				//debugConsole(fn_filter);
				fn_filter_array[i]=fn_filter;
				if (data.dirMode == 0 && fn_pattern.indexOf("/") >= 0) data.dirMode=1;
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
			}
			dummy[i] = "<NONE>";
			fn_cnt_array[i] = 0;
		}
			// clear the table
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
		tableBody.innerHTML = "";

		s=""; // string with skipped files
		let dsn_array = []; // array with filtered dataset names
		let descr_array = []; // array with filtered descriptions
		let fn_array = []; // 2D array of filtered filenames (outer dim: datasets, inner dim: datafile defs)
		let dsn_cnt = 0; skipped_cnt = 0; matched_cnt = 0; conflict_cnt = 0;
		let existingFilesMetadata = $wire.get('existingFilesMetadata');
		let datasetdefIds = $wire.get('datasetdefIds');
		for (let i = 0; i < data.allFiles.length; i++)
		{
			if (data.dirMode == 1)
			{   // we have a directory in the pattern
				fn = data.allFiles[i].webkitRelativePath;
				fn = fn.substring(fn.indexOf("/")+1); // remove the root directory
			}
			else
			{	// we don't have a directory, use the filename
				fn = data.allFiles[i].name;
			}
			skipped=1; used=0;
			for (let j=0; j<df_array.length; j++)
			{
				if(fn_filter_array[j]!="")
				{		// if fn_filter not empty
					let hit = fn_filter_array[j].test(fn);
					if (hit)
					{
						skipped=0;
						if(!used) { used=1; matched_cnt++; }
						let end_id = fn.substring(beg_id_array[j]).search(postfix_array[j])+beg_id_array[j]; // zahl ende: beginn von postfix gefunden in fn, beginnend mit beg_id, falls postfix im fn VOR <id> wäre
						let id = fn.substring(beg_id_array[j],end_id); // <ID> gefunden
						let name = dsn_pattern.replace("<ID>", id); // baue Name mit neuem ID zusammen
						let descr = descr_pattern.replace("<ID>", id); // baue Description mit neuem ID zusammen
							// Array
						idx = dsn_array.indexOf(name);
						if (idx == -1)
						{   // new dataset name
							dsn_array[dsn_array.length] = name; // extend the datasetname array
							descr_array[descr_array.length] = descr; // extend the datasetname array
							dsn_cnt++;
							idx = dsn_array.length-1;
							fn_array[dsn_array.length-1] = []; // extend the fn array with dummies
							x=dummy; x[j]=fn; // prepare the correct columns
							fn_array[idx][j] = fn;
							fn_cnt_array[j]++;
						}
						else
						{		// existing dataset name
							if (fn_array[idx][j] == null)
							{		// new filename found
								fn_array[idx][j] = fn;
								fn_cnt_array[j]++;
							}
							else
							{		// a conflict found
								s = s + "Conflict in " + dsn_array[idx] + ": " + fn + " overwrote " + fn_array[idx][j] + "<br>";
								fn_array[idx][j] = fn;
								conflict_cnt++;
							}
						} // if new dataset found
					} // if hit = filename matches the pattern
				} // if fn_filter not empty
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
			document.getElementById("skipped").innerHTML = "Click here to see the list of skipped/conflicting files...";
			document.getElementById("skipped-list").innerHTML = s;
		}
		else
		{
			document.getElementById("skipped").innerHTML = "";
			document.getElementById("skipped-list").innerHTML = "";
		}
			// Display analysis summary
		mode_str=(data.dirMode)?("Nested"):("Flat");
		str = "" + 
			"<b>Matching files:</b> " + String(matched_cnt) + " files (includes conflicting files)<br>" +
			"<b>Matching but conflicting files:</b> " + String(conflict_cnt) + " files<br>" +
			"<b>Non-matching files:</b> " + String(skipped_cnt) + " files<br>" +
			"<b>Datasets matched</b>: " + String(dsn_cnt) + "<br>" + 
			"<b>Datafiles with assigned files:</b> " + String(fn_cnt_array.reduce((a, b) => a + b)) + "<br>" + 
			"<b>Datafiles remaining empty:</b> " + String(dsn_cnt * df_array.length - fn_cnt_array.reduce((a, b) => a + b)) + "<br>";
		document.getElementById("analysis-summary").innerHTML = str;

			// Table - Summary header
		headers = document.getElementById('results').getElementsByTagName('th');
		headers[df_array.length+3].textContent = dsn_cnt; // insert count of Names
		for (let j=0; j<df_array.length; j++) // for each column
			headers[df_array.length+4+j].textContent = fn_cnt_array[j]; // insert the count of fns

			// Table - Filenames
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0];
		rows_max = (dsn_array.length > data.maxDisplayDatasets) ? data.maxDisplayDatasets : dsn_array.length;
		allDatasetExisting = [];
		allDatasetdefIdsExisting = [];
		for (let i=0; i<rows_max; i++)
		{
			newRow = tableBody.insertRow(-1);
			cell = newRow.insertCell(-1);
			cell.innerHTML = '<input type="checkbox" id="check' + (i+1) + '" wire:click="$js.updateSelected($data)">: #' + (i+1); // insert checkbox for a dataset
			cell = newRow.insertCell(-1);
			cell.textContent = dsn_array[i]; // insert Name to the table
			cell.title = descr_array[i]; // insert Description as cell title
			allDatasetExisting[i] = existingFilesMetadata.some((ds) => ds.datasetName === dsn_array[i]); // this dataset Name exists already -> to be overwritten
			if (allDatasetExisting[i]) cell.classList.add('bg-red-100'); // mark red if needs to be overwritten
			result = [];
			for (let k = 0; k < existingFilesMetadata.length; k++) 
			{
				if (existingFilesMetadata[k].datasetName === dsn_array[i]) { result.push(existingFilesMetadata[k].datasetdefId); }
			}
			allDatasetdefIdsExisting[i]=result; 
			for (let j=0; j<df_array.length; j++) // for each column
			{
				cell = newRow.insertCell(-1);
				cell.textContent = fn_array[i][j]; // insert fn to the specific cell
				if(allDatasetdefIdsExisting[i].includes(datasetdefIds[j]))
					cell.classList.add('bg-red-100');		// mark red if needs to be overwritten
			}
		}
		table = document.getElementById('results');
		table.style.visibility = "visible"; // show the table
		document.getElementById("table-hint").innerHTML = "Showing " + (rows_max == dsn_array.length ? "all" : (rows_max + " first")) + " datasets (red: datasets will be updated, datafiles will be overwritten):";

		// save variables in Livewire for upload procedure
		data.allDatasetNames = dsn_array;
		data.allDatasetDescriptions = descr_array;
		data.allDatafileNames = fn_array;
		data.nDatasetsFound = dsn_array.length;
		data.allDatasetExisting = allDatasetExisting; // true/false; same size as data.allDatasetNames
		data.allDatasetdefIdsExisting = allDatasetdefIdsExisting; // datasetdefids; same size as data.allDatafileNames
		console.timeEnd("Parse files");
		
			// select all Datasets in the table, which also calls createPendingfiles
		document.getElementById("checkAll").checked = true; // select all
		data = _checkAll(data);
	});

	// Process the upload
	$js('doUpload', (data) => {
	  uploadStart = performance.now();
		response=confirm("This will start the upload and this might take a long time. Do not leave this page while uploading.\n\nTo cancel the upload, refresh or close the page. ");
		if(response==false) return;
		data = _createPendingFiles(data); // create the list with PendingFiles
		data.nUploaded = 0;
		data.error = false;
		$wire.set('uploading', true); // set immediately. This is used within the Livewire component
		data.uploading = true; // use this for input button state (enabled/disabled)
		setStatus("Upload process started.");

		let uploads = Object.values($wire.uploads); //jw:todo can delete
		let offset = uploads.length; //jw:todo can delete
		// https://fly.io/laravel-bytes/multi-file-upload-livewire/
		data.pendingFiles.forEach( (file, index) => { uploadQueue.push({ index, file }); } );
		debugConsole("uploadQueue: ", uploadQueue);
		debugConsole("uploadQueue.length: ", uploadQueue.length);
		processQueue();
		setStatus('Leaving doUpload()...');
	});

		// Cancel an upload (jw:note not used yet (no button), but works.)
	$js('cancelUpload', (data) => {
		debugConsole('User has cancelled the upload');
		resetUpload();
	});

	$js('toggleOverwriteExisting', (data)  => {
		let newValue = data.overwriteExisting ? false : true;
		data.overwriteExisting = newValue;
		_createPendingFiles(data);
	});

	////////////////////////////////////////////////////////////////////////////////
	//	Javascript functions
	////////////////////////////////////////////////////////////////////////////////

	function _createPendingFiles(data)
	{
		console.time("createPendingFiles");
		let fn_array = data.selDatafileNames;
		let dsn_array = data.selDatasetNames;
		let descr_array = data.selDatasetDescriptions;
		let df_array = $wire.datasetdefIds;
		let existingFilesMetadata = $wire.get('existingFilesMetadata');

			// Create list with dataset, datasetdefid and relative file path to facilitate saving one file to the correct dataset/datafile.
			// Array's index corresponds to pendingFiles array.
		data.pendingFilesMetadata = [];
		for(let i = 0; i < fn_array.length; i++) {
			for(let j = 0; j < fn_array[i].length; j++) {
				if(fn_array[i][j] != undefined)
				{
					data.pendingFilesMetadata.push({ datasetName: dsn_array[i], datasetDesc: descr_array[i], datasetdefId: df_array[j], relativePath: fn_array[i][j]});
				}
			}
		}
		data.pendingFilesMetadata.sort((a, b) => a.relativePath.localeCompare(b.relativePath));
			// Create list with pending files: data.pendingFiles
		let filenamesToUpload = fn_array.flat(); // flat list of files to upload. This is a relative path from the directory chosen as input. E.g. P0002/3DSCAN/P0002_watertight.stl
		if(data.dirMode == 0)
		{ // flat: get filtered list of file objects
			data.pendingFiles = data.allFiles.filter((file) => { return filenamesToUpload.includes(file.name);
				// the data.allFiles 'name' is *just* the file name. There is a relative path, but it also contains the parent folder. 
				// E.g. AXD-small/P0002/3DSCAN/P0002_watertight.stl
			});
		}
		else
		{	// nested: prepend filenamesToUpload with directory for comparison with allFiles
			let prefix = data.directory+'/';
			dirPrefixed = filenamesToUpload.map(item => prefix + item);
			data.pendingFiles = data.allFiles.filter((file) => { return dirPrefixed.includes(file.webkitRelativePath); });
		}

		/*if(data.overwriteExisting == false) // because of a bug, the files will be overwritten ALWAYS
		{	// We will *not* overwrite existing files. Hence, we're removing existing files from the pendingFiles list			
			//
			// filter pendingFiles again, removing any entries which correspond to existing datafiles
			//
				// first add 'exists' field and set to true if datafile already exists
			data.pendingFilesMetadata.forEach(obj1 => {
				obj1.exists = existingFilesMetadata.some(obj2 =>
					obj1.datasetName === obj2.datasetName &&
					obj1.datasetdefId === obj2.datasetdefId
				);
			});
			console.log(data.pendingFilesMetadata);
				// filter pendingFiles based on the 'exists' field
			nonexistentPendingFiles = data.pendingFiles.filter((obj2, index) => {
				// condition based on array1 at the same index
				return data.pendingFilesMetadata[index].exists === false;
			});
			console.log(data.pendingFilesMetadata);			
			// filter pendingFilesMetadata based on the 'exists' field
			nonexistentPendingFilesMetadata = data.pendingFilesMetadata.filter((obj2, index) => {
				return obj2.exists === false;
			});

			// assign nonexistent filtered results
			data.pendingFiles = nonexistentPendingFiles;
			data.pendingFilesMetadata = nonexistentPendingFilesMetadata;
		}*/

		// update the actual number of files to upload, so Livewire knows how many files to expect.
		//data.nFilesToUpload = data.pendingFiles.length;
		console.timeEnd("createPendingFiles");
		return(data);
	}

	function _checkAll(data)
	{
		debugConsoleTable("_checkAll");
		var checkBox = document.getElementById("checkAll");
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0];
		if (tableBody.rows.length > 0)
		{
			if (checkBox.checked == true)
			{
				for (let i=0; i<tableBody.rows.length; i++)
					document.getElementById("check"+(i+1)).checked=true;
			}
			else
			{
				for (let i=0; i<tableBody.rows.length; i++)
					document.getElementById("check"+(i+1)).checked=false;
			}
			return _updateSelected(data);
		}
	}

		// Updates the data and table on click of dataset selection
		//   Creates Alpine variables: selDatasetNames, selDatasetDescriptions, selDatafileNames, 
		//   Updates Alpine counters: nDatasetsToUpload, nDatasetsToOverwrite, nFilesToUpload, nFilesToOverwrite
	function _updateSelected(data)
	{
		debugConsoleTable("_updateSelected");
		tableBody = document.getElementById('results').getElementsByTagName('tbody')[0];
		checkAll = document.getElementById("checkAll");
		rows = tableBody.rows; 
		if (data.nDatasetsFound > 0)
		{
			console.time("_updateSelected");
			let nDatasetdef = $wire.datasetdefIds.length; // number of dataset definitions (i.e., table columns)
			let fn_cnt_array = new Array(nDatasetdef).fill(0);
			let dsn_cnt = 0;
			let selDatafileNames = []; // 2D array of selected filenames (outer dim: datasets, inner dim: datafile defs)
			let selDatasetNames = [];
			let selDatasetDescriptions = [];
			let selDatasetExisting = []; // 1D Array with true if that dataset already exists
			let selDatasetdefIdsExisting = [];
			let nFilesToOverwrite = 0;
			for (let i=0; i<data.nDatasetsFound; i++)
			{
				fn = data.allDatafileNames[i];
				if(i<rows.length)
					checked = document.getElementById("check"+(i+1)).checked; // within the table range -> checked if selected
				else
					checked = checkAll.checked; // outside the table range -> checked if all selected
				if(checked) 
				{  // Dataset selected
					dsn_cnt++; // count the number of selected datasets
					selDatasetNames[selDatasetNames.length] = data.allDatasetNames[i];
					selDatasetExisting[selDatasetExisting.length] = data.allDatasetExisting[i];
					selDatasetDescriptions[selDatasetDescriptions.length] = data.allDatasetDescriptions[i];
					if(i<rows.length) rows[i].cells[1].textContent = data.allDatasetNames[i]; // update the table
						// insert selected datafilenames
					selDatafileNames[selDatafileNames.length] = fn;
					selDatasetdefIdsExisting[selDatasetdefIdsExisting.length] = data.allDatasetdefIdsExisting[i];
					nFilesToOverwrite = nFilesToOverwrite + data.allDatasetdefIdsExisting[i].length;
					for (let col=0; col<fn.length; col++)
					{	if(fn[col] != null)
						{
							if(i<rows.length) rows[i].cells[col+2].textContent = fn[col];
							fn_cnt_array[col]++; // count the number of datafiles in the corresponding datafiledef
						}
					}
				}
				else // Dataset not selected
				{ if(i<rows.length)
						for (let col=0; col<fn.length; col++)
							rows[i].cells[col+2].textContent = ""; // update the table
				}
			}
				// Update table summary header
			headers = document.getElementById('results').getElementsByTagName('th');
			headers[nDatasetdef+3].textContent = selDatasetNames.length; // insert count of Names
			for (let j=0; j<nDatasetdef; j++) // for each column
				headers[nDatasetdef+4+j].textContent = fn_cnt_array[j]; // insert the count of fns
				// Update Alpine counters
			data.nDatasetsToUpload = selDatasetNames.length;
			data.nDatasetsToOverwrite = count = selDatasetExisting.reduce((acc, val) => (val === true ? acc + 1 : acc), 0);
			const flatselDFN = new Set(selDatafileNames.flat(Infinity));
			data.nFilesToUpload = flatselDFN.size; // save the number of unique files to upload			
			data.nFilesToOverwrite = nFilesToOverwrite; // number of datafiles to be overwitten
				// Update Alpine arrays
			data.selDatasetNames = selDatasetNames; // save the selected dataset names
			data.selDatasetDescriptions = selDatasetDescriptions; // save the selected descriptons
			data.selDatafileNames = selDatafileNames; // save the selected filenames
			data.selDatasetExisting = selDatasetExisting;
			data.selDatasetdefIdsExisting = selDatasetdefIdsExisting;
			
			console.timeEnd("_updateSelected");
		}
		return data;
	}

	let maxParallelUploads = 1; // Maximum concurrent uploads

	function processQueue() {

		while (uploadQueue.length > 0 && maxParallelUploads > 0) {
			const { index, file } = uploadQueue.shift();
			maxParallelUploads--;
			let data = Alpine.$data(document.getElementById('alpineComponent'));
			let totalSeconds = Math.floor((performance.now() - uploadStart) / 1000);
			const hours = Math.floor(totalSeconds / 3600);
			const minutes = Math.floor((totalSeconds % 3600) / 60);
			const seconds = totalSeconds % 60;
			let elapsedString = " (Duration: " + String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0') + ")";

			// set metadata
			debugConsole('Uploading ' + file.name + ' for dataset ' + data.pendingFilesMetadata[index].datasetName + ' datasetdef ' + data.pendingFilesMetadata[index].datasetdefId);
			@this.set('uploadsMetadata.'+index+'.datasetName', data.pendingFilesMetadata[index].datasetName );
			@this.set('uploadsMetadata.'+index+'.datasetdefId', data.pendingFilesMetadata[index].datasetdefId);
			@this.set('uploadsMetadata.'+index+'.fileName', file.name);
			// only set if it exists (otherwise setting it destroys the uploadsMetadat array!)
			if(data.pendingFilesMetadata[index].datasetDesc != '')
				@this.set('uploadsMetadata.'+index+'.datasetDesc', data.pendingFilesMetadata[index].datasetDesc);
			// upload file
			@this.upload( 'uploads.' + index,
				file,
				() => {
					/* Success handler */
					data.nUploaded++;
					setStatus("File #" + (index+1) + " (" + file.name + ") now successfully uploaded");
					data.progressText = data.nUploaded + "/" + data.nFilesToUpload + " files successfully uploaded." + elapsedString;
					// save each datafile after it has been uploaded
					debugConsole("Calling saveDatafile(" + index + ")");
					$wire.saveDatafile(index);
					maxParallelUploads++; // Free up a slot
					// if this is the last upload, set 'uploading' to false
					// all files have been uploaded. Clearn up.
					if(data.nUploaded == data.nFilesToUpload)
					{
						data.uploading = false; // finished
						data.nFilesToUpload = 0;
						/*$wire.call('calculateExisting').then(calculatedValue => {
							    debugConsole("Calling calculateExisting() and waiting for return value before continuing. nFilesExisint = " + calculatedValue);
								//data.nFilesExisting = calculatedValue; 
							    // continue with logic here
							});*/

						setStatus("Uploading has finished. All files have been saved to the database.");
						$wire.dispatch('status-message', { message: 'Uploading has finished' });

						// output redirect message and redirect to 'Datasets'
						$wire.dispatch('status-message', { message: 'You will be redirected to the datasets page' });
						setTimeout(() => { $wire.call('redirectToDatasets'); }, 3000); // 3 second delay
						return;
					}
					processQueue(); // Process next in queue
				},
				(error) => {
					/* Error handler */
					setStatus("Error " + (index+1) + " (" + error + ")");
					data.error = true;
					//jw:tmp maybe don't reset the upload, but carry on? 
				},
				(progress) => {
					/* Progress updates */
					setStatus("File #" + (index+1) + " (" + file.name + ") uploading");
					data.progress = event.detail.progress;
				},
				() => {
					/* cancelled handler */
					setStatus("Cancelled at file #" + (index+1));
				}
			);
		}
	}

	// set both Livewire, Alpine and inner HTML status.
	function setStatus(string)
	{
		debugConsole("Status (alpine): ", string);
		let data = Alpine.$data(document.getElementById('alpineComponent'));
		data.status = string;
	};

	/*
	 * only log to console if debugLevel > 0
	 */
  function debugConsole(...args)
	{
		if(debugLevel > 0)
			console.log(...args);
	}
	function debugConsoleTable(...args)
	{
		if(debugLevel > 0)
			console.table(...args);
	}


	</script>
@endscript
	</div>
</div> {{-- component div:END --}}
