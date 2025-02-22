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
		<p>Input the pattern for the filename:<input type="text" id="fn_pattern" value="hrtf b_nh<NUM>.sofa"/></p>
		<p>Input the pattern for the ID:<input type="text" id="id_pattern" value="NH<NUM>" /></p>
		<h3>Analysis results:<h3>
		<p id="results"></p>
		
		<script>
		var inps = document.querySelectorAll('input');
		[].forEach.call(inps, function(inp) {
		inp.onchange = function(e) 
		{
			let fn_pattern = document.getElementById("fn_pattern").value;
			let id_pattern = document.getElementById("id_pattern").value;
			let s=fn_pattern + "; ID Pattern: " + id_pattern + "\n";
			if (this.type === "file") 
			{
				for (let i = 0; i < this.files.length; i++) 
				{ 
					fn = this.files[i].name;
					let fn_filter = RegExp(fn_pattern.replace("<NUM>", "[0-9]+"));
					let hit = fn_filter.test(fn);
					if (hit)
					{
						let end_filter = fn_pattern.indexOf(">")+"[0-9]+".length-"<NUM>".length; // find the end of the number
						let postfix = fn_pattern.substring(end_filter); // hole den postfix, d.h., text nach <NUM> raus
						let beg_num = fn_pattern.indexOf("<"); // zahl anfang: index von < in fn_pattern
						let end_num = fn.search(postfix); // zahl ende: beginn von postfix gefunden in fn
						let num = fn.substring(beg_num,end_num); // Nummer <NUM> gefunden
						let id = id_pattern.replace("<NUM>", num); // baue neue ID zusammen
						s = s + fn + ": ID: " + id + " filename: " + fn + "\n";
					}
				else
				{ s = s + fn + ": skipped\n"; }
			}
			document.getElementById("results").innerText = s;
		}
  }; });
	</script>
</x-app-layout>
