<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			SONICOM: Transforming Auditory-Based Social Interaction and Communication in AR/VR
		</h2>
	</x-slot>

		<p>SONICOM is a research project that aims to revolutionise the way we interact socially within augmented and virtual reality (AR/VR) environments and applications, from online meetings to VR gaming.
		</p>
		<p>The SONICOM Ecosystem aims at providing an ecosystem for spatial auditory data closely linked with tools for binaural rendering and auditory modeling, reinforcing the idea of reproducible research. The stored data can be integrated in <a href="https://datathek.oeaw.ac.at">the ÖAW Datathek</a>, an electronic repository registered in re3data.org and providing a sustainable basis for further research within, outside and beyond SONICOM. The Ecosystem is open-accessible, citable via DOIs, documented, searchable by metadata via a web frontend, and provides an interface for machineable download of partial data. It enables other stakeholders such as researchers, end users, and practitioners to contribute to it in.
		<h3>Ecosystem terminology:</h3>
		<dl>
				<dt>Database</dt>
				<dd>A 'database' is a collection of data in a specific collation of formats, e.g., a collection of HRTF and image data, where each database record contains one HRTF and one image of the ear.</dd>
				<dt>Dataset</dt>
				<dd>A 'dataset' is one record in the database, e.g., one HRTF and one image for each of the subjects. One record may contain multiple datafiles of various datatypes. The exact composition of each 'dataset' in a database is given by the dataset definition.</dd>
				<dt>Dataset definition</dt>
				<dd>A 'dataset definition' is a definition the datafiles representing each dataset, e.g., a dataset may contain two different HRTFs and four different images per record. Each of the defined datafiles can be represented by a separate widget.</dd>
				<dt>Datafile</dt>
				<dd>A 'datafile' is a file stored a the dataset. Each datafile is of the type defined in the dataset definition.</dd>
				<dt>Datafile type</dt>
				<dd>A 'datafile type' is one of the predefined types of data which can be used in the dataset definition, e.g., 'HRTF', 'BRIR', 'SRIR' etc.</dd>
		</dl>
		<h3>Permissions:</h3>
		@role('admin')
		<p>You have the 'admin' role</p>
		@else
		<p>You do not have the 'admin' role</p>
		@endrole
</x-app-layout>
