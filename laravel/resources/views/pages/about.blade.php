<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			<div>
				<img id="logo" src="{{ asset('images/logo/logo_only.png') }}" alt="Logo" class="inline h-8">&nbsp;The SONICOM Ecosystem
			</div>
		</h2>
		<p>Data and tool repository related to spatial hearing and binaural audio</p>
	</x-slot>

	<p>The SONICOM Ecosystem aims at providing an ecosystem for spatial auditory data closely linked with tools for binaural rendering and auditory modeling, reinforcing the idea of reproducible research. The stored data can be integrated in <a href="https://datathek.oeaw.ac.at">the ÖAW Datathek</a>, an electronic repository registered in re3data.org and providing a sustainable basis for further research within, outside and beyond SONICOM. The Ecosystem is open-accessible, citable via DOIs, documented, searchable by metadata via a web frontend, and provides an interface for machineable download of partial data. It enables other stakeholders such as researchers, end users, and practitioners to contribute to it in.</p>
	<br>
	<p>The Ecosystem result from the SONICOM project.</p>
	<p><b>SONICOM</b> is a research project that aims to investigate the way we interact socially within augmented and virtual reality (AR/VR) environments and applications, from online meetings to VR gaming. <a href="https://www.sonicom.eu/">More information on SONICOM...</a></p>

	<br><hr><br>
	
	<h3>The Ecosystem Team:</h3>
	The Ecosystem is developed and maintained at the <a href="https://www.oeaw.ac.at/en/ari/">Acoustics Research Institute</a> of the <a href="https://www.oeaw.ac.at/en/">Austrian Academy of Sciences</a>:
	<ul class="list-disc list-inside">
		<li><b><a href="https://www.oeaw.ac.at/en/ari/our-team/majdak-piotr">Piotr Majdak</a>:</b> Project lead, Development, Programming
		<li><b><a href="https://www.oeaw.ac.at/en/ari/our-team/stuefer-jonathan">Jonnathan Stuefer</a>:</b> Main programming, IT management
		<li><b><a href="https://www.oeaw.ac.at/en/ari/our-team/mihocic-michael">Michael Mihocic</a>:</b> Widgets, Testing, Data management
	</ul>
	The Ecosystem uses the <a href="https://datathek.oeaw.ac.at">the ÖAW Datathek</a> as a backend. The ÖAW Datathek is maintained and supported by the <a href="https://verlag.oeaw.ac.at/en/press/contact/c-40">Austrian Academy of Sciences Press</a> with <b>Herwig Stöger</b> as the contact person. 
	
	<br><hr><br>
	
	<h3>The Ecosystem terminology:</h3>
	<ul class="list-disc list-inside">
		<li><b>Database</b>: A 'Database' is a collection of data in a specific collation of formats, e.g., a collection of HRTF and image data, where each database record contains one HRTF and one image of the ear.
		<li><b>Dataset</b>: A 'Dataset' is one record in the database, e.g., one HRTF and one image for each of the subjects. One record may contain multiple datafiles of various datatypes. The exact composition of each 'dataset' in a database is given by the dataset definition.
		<li><b>Dataset definition</b>: A 'Dataset definition' is a definition the datafiles representing each dataset, e.g., a dataset may contain two different HRTFs and four different images per record. Each of the defined datafiles can be represented by a separate widget.
		<li><b>Datafile</b>: A 'Datafile' is a file stored a the dataset. Each datafile is of the type defined in the dataset definition.
		<li><b>Datafile type</b>: A 'Datafile type' is one of the predefined types of data which can be used in the dataset definition, e.g., 'HRTF', 'BRIR', 'SRIR' etc.
		<li><b>RADAR Dataset</b>: The data stored at the Datathek are structured in 'datasets' because of the unterlying <a href="https://radar.products.fiz-karlsruhe.de/en">RADAR functionality</a>. The correspond to the Ecosystem Databases and, when interacting with RADAR, we call them 'RADAR Datasets' to make a distinction to our (Ecosystem) Datasets.</ul>
	</ul>
	<br><hr><br>
	
	<h3>Resources and Support:</h3>
	<ul class="list-disc list-inside">
		<li><b><a href="https://github.com/sofacoustics/Ecosystem/wiki">Wiki</a>:</b> We use Github Wiki pages to provide documentation. 
		<li><b><a href="https://github.com/sofacoustics/Ecosystem/issues">Issues</a>:</b> Check the Github Issue pages in case of troubles. If your problem has not been reported yet, issue a ticket.
		<li><b><a href="https://github.com/sofacoustics/Ecosystem">Code</a>:</b> The Ecosystem is open source: We use the <a href="https://github.com/sofacoustics/Ecosystem/tree/live">live</a> branch for the system tested and available to the users, and the <a href="https://github.com/sofacoustics/Ecosystem/tree/dev">dev</a> branch for improvements before going live. Check our source code or fork it for your own projects. 
	</ul>
	<br><hr><br>
	
	<h3>Permissions:</h3>
	<ul class="list-disc list-inside">
		@role('admin')
			<li>You have the 'admin' role
		@else
			<li>You do not have the 'admin' role
		@endrole
	</ul>
	<br><hr><br>
	
	<h3>System information:</h3>
	<ul class="list-disc list-inside">
		<li>SONICOM Ecosystem version: {{ config('version.string') }}
	</ul>
	<br><hr><br>
	
	<h3>Attributions:</h3>
	<ul class="list-disc list-inside">
		<li>
			<img src="{{ asset('images/envelope.png') }}" style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
			by <a href="https://thenounproject.com/creator/iconisland/" target="_blank">Icon Island</a> licensed under CC BY 3.0.
		</li>
		<li>
			<img src="{{ asset('images/copy-to-clipboard.png') }}" style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
			by <a href="https://github.com/javisperez/toe-icons?ref=svgrepo.com" target="_blank">Javisperez</a>
			licensed under <a href="https://www.svgrepo.com/page/licensing#Apache" target="_blank">Apache license</a>
			via <a href="https://www.svgrepo.com/" target="_blank">SVG Repo</a>.
		</li>
		<li>
			<img src="{{ asset('images/ROR_logo.svg') }}" style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
			by the <a href="https://ror.org/" target="_blank">Research Organization Registry (ROR) initiative</a>
			licensed under <a href="https://creativecommons.org/licenses/by-nd/4.0/" target="_blank">CC BY-ND 4.0 license</a>.
		</li>
		<li>
			<img src="{{ asset('images/visible.svg') }}" style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
			<img src="{{ asset('images/hidden.svg') }}" style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
			are from <a href="https://iconmonstr.com/" target="_blank">iconmonstr</a>
			licensed under the <a href="https://iconmonstr.com/license/" target="_blank">iconmonstr license</a>.
		</li>
		<li>
			All other logos and images are either public domain or created by the Ecosystem.
		</li>
	</ul>
</x-app-layout>
