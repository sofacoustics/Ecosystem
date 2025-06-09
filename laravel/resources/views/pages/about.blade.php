<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			The SONICOM Ecosystem
		</h2>
		<p>Transforming Auditory-Based Social Interaction and Communication in AR/VR</p>
	</x-slot>

	<p>The SONICOM Ecosystem aims at providing an ecosystem for spatial auditory data closely linked with tools for binaural rendering and auditory modeling, reinforcing the idea of reproducible research. The stored data can be integrated in <a href="https://datathek.oeaw.ac.at">the ÖAW Datathek</a>, an electronic repository registered in re3data.org and providing a sustainable basis for further research within, outside and beyond SONICOM. The Ecosystem is open-accessible, citable via DOIs, documented, searchable by metadata via a web frontend, and provides an interface for machineable download of partial data. It enables other stakeholders such as researchers, end users, and practitioners to contribute to it in.</p>
		
	<p><b>SONICOM</b> is a research project that aims to revolutionise the way we interact socially within augmented and virtual reality (AR/VR) environments and applications, from online meetings to VR gaming. <a href="https://www.sonicom.eu/">More information on SONICOM...</a></p>

	<br><hr><br>
	
	<h3>The Ecosystem terminology:</h3>
	<ul class="list-disc list-inside">
		<li><b>Database</b>: A 'database' is a collection of data in a specific collation of formats, e.g., a collection of HRTF and image data, where each database record contains one HRTF and one image of the ear.
		<li><b>Dataset</b>: A 'dataset' is one record in the database, e.g., one HRTF and one image for each of the subjects. One record may contain multiple datafiles of various datatypes. The exact composition of each 'dataset' in a database is given by the dataset definition.
		<li><b>Dataset definition</b>: A 'dataset definition' is a definition the datafiles representing each dataset, e.g., a dataset may contain two different HRTFs and four different images per record. Each of the defined datafiles can be represented by a separate widget.
		<li><b>Datafile</b>: A 'datafile' is a file stored a the dataset. Each datafile is of the type defined in the dataset definition.
		<li><b>Datafile type</b>: A 'datafile type' is one of the predefined types of data which can be used in the dataset definition, e.g., 'HRTF', 'BRIR', 'SRIR' etc.
	</ul>
	
	<br><hr><br>
	
	<h3>The Ecosystem Team:</h3>
	<ul class="list-disc list-inside">
		<li>Piotr Majdak: Project lead and development
		<li>Jonnathan Stuefer: Main programming
		<li>Michael Mihocic: Widgets, Testing, Data management
		<li>Herwig Stöger: datathek
	</ul>
	
	<br><hr><br>
	
	<h3>Permissions:</h3>
	@role('admin')
	<p>You have the 'admin' role</p>
	@else
	<p>You do not have the 'admin' role</p>
	@endrole
</x-app-layout>
