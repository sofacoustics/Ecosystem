@php
    $labelClass = "text-gray-700 mb-2 block font-bold";
    $selectClass = "form-control text-gray-700 rounded-lg mb-2 block font-bold";
    $inputClass = "text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none";
    $buttonClass = "bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white";
@endphp

<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #e8ffff;
}

tr:hover {background-color: #D6EEEE;}

.cellclass {
  height: 50px;
  text-align: center;
  background-color: #800000;
}
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Database: <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
        </h2>
    </x-slot>
		

    <form wire:submit.prevent="save">
			<h3>Pattern to create a Name of a datasets:</h3>
			<div class="mb-4">				
					<p>Note: It must include "&lt;ID&gt;"</p>
					<input wire:model="name_pattern" type="text" id="name_pattern" class="{{ $inputClass }}" required value="NH<ID>"/>
					@error('name')
							<span class="text-red-500">{{ $message }}</span>
					@enderror
			</div>
			<h3>Patterns for the datafiles:</h3>
			<p>Note: At least one datafile pattern must include "&lt;ID&gt;"</p>

			@forelse($database->datasetdefs as $datasetdef)
        <div class="mb-4">
            <label for="fn_pattern{{ $datasetdef->id }}" class="{{ $labelClass }}">Pattern for datafile "{{ $datasetdef->name }}":</label>
            <input wire:model="fn_pattern{{ $datasetdef->id }}" type="text" id="fn_pattern{{ $datasetdef->id }}" 
							class="{{ $inputClass }}" required value="hrtf b_nh<ID>.sofa"/>
            @error('description')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
			@empty
				<li>There is no definition of a dataset yet.</li>
			@endforelse
        <div class="mt-4">
						<label for="analyze" class="{{ $buttonClass }}">Analyze!</label>
						<input type="file" class="{{ $buttonClass }}" id="analyze" webkitdirectory mozdirectory />
        </div>
				
    </form>

		<h3>Analysis results:</h3>
		<p id="mode"></p>
		<table id="results" class=""> 
        <thead> 
            <tr> 
                <th align="center">ID</th> 
								@foreach($database->datasetdefs as $datasetdef)
									<th align="center">{{ $datasetdef->name }}</th> 
								@endforeach
            </tr> 
        </thead> 
        <tbody> 
            <!-- Rows will be added here --> 
        </tbody> 
    </table> 
		<p id="skipped"></p>
		
		<script>
		var inps = document.querySelectorAll('input');
		[].forEach.call(inps, function(inp) 
		{
			inp.onchange = function(e) 
			{
				if (this.type === "file") 
				{ 
					mode=-1;
					let df_array = [ @foreach($database->datasetdefs as $datasetdef) {{ $datasetdef->id }}, @endforeach ];
					let fn_filter_array = [], postfix_array = [], beg_id_array = [], 
					dummy = new Array();
					for (let i=0; i<df_array.length; i++)
					{
						let fn_pattern = document.getElementById("fn_pattern"+df_array[i]).value;
						let fn_filter = fn_pattern.replace(/\[/g, "\\["); 
						fn_filter = fn_filter.replace(/\]/g, "\\]"); 
						fn_filter = fn_filter.replace(/\^/g, "\\^"); 
						fn_filter = fn_filter.replace(/\./g, "\\."); 
						fn_filter = fn_filter.replace(/<ANY>/g, ".+"); 
						fn_filter = RegExp(fn_filter.replace(/<ID>/g, ".+"));
						fn_filter_array[i]=fn_filter; 

						if (fn_pattern.indexOf("/") >= 0) mode=1; else mode=0; 
						
						let end_filter = fn_pattern.indexOf("ID>"); // find the end of the ID
						let postfix = fn_pattern.substring(end_filter+3); // hole den postfix, d.h., text nach <ID> raus
						postfix = postfix.replace(/\[/g, "\\["); 
						postfix = postfix.replace(/\]/g, "\\]"); 
						postfix = postfix.replace(/\^/g, "\\^"); 
						postfix = postfix.replace(/\./g, "\\.");
						postfix = RegExp(postfix.replace(/<ANY>/g, ".+"));
						postfix_array[i]=postfix; 
						let beg_id = fn_pattern.indexOf("<"); // zahl anfang: index von < in fn_pattern
						beg_id_array[i]=beg_id;
						console.log([fn_pattern, fn_filter, end_filter, postfix, beg_id]);
						
						dummy[i] = "<NONE>";
					}
					
					let name_pattern = document.getElementById("name_pattern").value;
					console.log([name_pattern]);

					document.getElementById("mode").innerHTML = "<b>Mode: " + mode + "</b>";
			
					s="<b>Skipped:</b><br>";
					const tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
					tableBody.innerHTML = "";
					let name_array = [], 
					fn_array = new Array();
					for (let i = 0; i < this.files.length; i++) 
					{   
						if (mode == 1)
						{   // we have a directory in the pattern
							fn = this.files[i].webkitRelativePath;
							fn = fn.substring(fn.indexOf("/")+1); // remove the root directory
						}
						else
						{	// we don't have a directory, use the filename
							fn = this.files[i].name;
						}
						for (let j=0; j<df_array.length; j++)
						{
							let hit = fn_filter_array[j].test(fn);
							if (hit)
							{
								let end_id = fn.search(postfix_array[j]); // zahl ende: beginn von postfix gefunden in fn
								let id = fn.substring(beg_id_array[j],end_id); // Nummer <ID> gefunden
								let name = name_pattern.replace("<ID>", id); // baue neue ID zusammen
									// Array
								idx = name_array.indexOf(name); 
								if (idx == -1)
								{   // new item in the list
									name_array[name_array.length] = name;
									x=dummy;
									x[j]=fn;
									fn_array[name_array.length-1] = [];
									for (k=0; k<df_array.length; k++) fn_array[name_array.length-1][k] = x[k];
								}
								else
								{	fn_array[idx][j] = fn;
								}
							}
							else
							{ s = s + fn + "<br>"; }
						}
					}
					document.getElementById("skipped").innerHTML = s;
						// Table
					for (let i=0; i<name_array.length; i++)
					{
						const newRow = tableBody.insertRow(-1);
						const cell1 = newRow.insertCell(-1); 
						cell1.textContent = name_array[i]; // insert Name to the table
						for (let j=0; j<df_array.length; j++) // for each column
						{
								const cell2 = newRow.insertCell(-1);
								cell2.textContent = fn_array[i][j]; // insert fn to the specific cell
								if (fn_array[i][j]=="<NONE>") 
								{ cell2.style.backgroundcolor = "red"; // this does not work, I dont know why...
								}
						}
					}
				} // if file type input
			}; // on change
		}); // for each call
	</script>
</x-app-layout>
