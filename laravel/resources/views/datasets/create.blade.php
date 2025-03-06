<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Database: <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
        </h2>
    </x-slot>
    <div>
        <p>resources/views/datasets/create.blade.php</p>
        <livewire:dataset-form :database=$database />
    </div>
		
		<p>Select The Directory to be analysed:
			<input type="file" id="files" webkitdirectory mozdirectory />
		</p>
		<p>Input the pattern for the filename:<input type="text" id="fn_pattern" value="hrtf b_nh<ID>.sofa"/></p>
		<p>Input the pattern for the dataset name:<input type="text" id="name_pattern" value="NH<ID>" /></p>
		<h3>Analysis results:</h3>
		<p id="mode"></p>
		<table id="results" border="1"> 
        <thead> 
            <tr> 
                <th align="center">ID</th> 
                <th align="center">{{ $database->title }}</th> 
            </tr> 
        </thead> 
        <tbody> 
            <!-- Rows will be added here --> 
        </tbody> 
    </table> 
		<p id="skipped"></p>
		
		<script>
var inps = document.querySelectorAll('input');
		[].forEach.call(inps, function(inp) {
		inp.onchange = function(e) 
		{
			let fn_pattern = document.getElementById("fn_pattern").value;
			let fn_filter = fn_pattern.replace(/\[/g, "\\["); 
			fn_filter = fn_filter.replace(/\]/g, "\\]"); 
			fn_filter = fn_filter.replace(/\^/g, "\\^"); 
			fn_filter = fn_filter.replace(/\./g, "\\."); 
			fn_filter = fn_filter.replace(/<ANY>/g, ".+"); 
			fn_filter = RegExp(fn_filter.replace(/<ID>/g, ".+"));
			let end_filter = fn_pattern.indexOf("ID>"); // find the end of the ID
			let postfix = fn_pattern.substring(end_filter+3); // hole den postfix, d.h., text nach <ID> raus
			postfix = postfix.replace(/\[/g, "\\["); 
			postfix = postfix.replace(/\]/g, "\\]"); 
			postfix = postfix.replace(/\^/g, "\\^"); 
			postfix = postfix.replace(/\./g, "\\.");
			postfix = RegExp(postfix.replace(/<ANY>/g, ".+"));
			let beg_id = fn_pattern.indexOf("<"); // zahl anfang: index von < in fn_pattern
			console.log([fn_pattern, fn_filter, end_filter, postfix, beg_id]);

			let name_pattern = document.getElementById("name_pattern").value;
			console.log([fn_pattern, fn_filter, end_filter, postfix, beg_id]);

      if (fn_pattern.indexOf("/") >= 0)
				document.getElementById("mode").innerHTML = "<b>Nested mode</b>";
			else
				document.getElementById("mode").innerHTML = "<b>Flat mode</b>";
			
			if (this.type === "file") 
			{ 
				s="<b>Skipped:</b><br>";
				const tableBody = document.getElementById('results').getElementsByTagName('tbody')[0]; 
				tableBody.innerHTML = "";
				for (let i = 0; i < this.files.length; i++) 
				{   
          if (fn_pattern.indexOf("/") >= 0)
          {   // we have a directory in the pattern
            fn = this.files[i].webkitRelativePath;
            fn = fn.substring(fn.indexOf("/")+1); // remove the root directory
          }
          else
          {	// we don't have a directory, use the filename
          	fn = this.files[i].name;
          }
					let hit = fn_filter.test(fn);
					if (hit)
					{
						let end_id = fn.search(postfix); // zahl ende: beginn von postfix gefunden in fn
						let id = fn.substring(beg_id,end_id); // Nummer <ID> gefunden
						let name = name_pattern.replace("<ID>", id); // baue neue ID zusammen
						const newRow = tableBody.insertRow();  
							// Insert new cells (columns) into the row 
						const cell1 = newRow.insertCell(0); 
						const cell2 = newRow.insertCell(1); 
							// Add data to the cells 
						cell1.textContent = name; // Replace with actual data 
						cell2.textContent = fn;        // Replace with actual data 
					}
					else
					{ s = s + fn + "<br>"; }
				}
				document.getElementById("skipped").innerHTML = s;
		}
  }; });
	</script>
</x-app-layout>
