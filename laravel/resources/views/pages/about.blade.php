<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            About SONICOM-Ecosystem
        </h2>
    </x-slot>
		<p>SONICOM: Transforming Auditory-Based Social Interaction and Communication in AR/VR</p>
		<p>Immersive audio is our everyday experience of being able to hear and interact with sounds around us. Simulating spatially located sounds in virtual or augmented reality (VR/AR) must be done in a unique way for each individual and currently requires expensive, time-consuming individual measurements, making it commercially unfeasible. The impact of immersive audio beyond perceptual metrics such as presence and localisation is still an unexplored area of research, specifically when related with social interaction, entering the behavioural and cognitive realms.</p>
        <h3>Terminology</h3>
        <dl>
            <dt>Database</dt>
            <dd>A 'database' is a collection of data in a specific collation of formats. E.g. a collection of HRTF data, where each database record contains one HRTF and one PNG.</dd>
            <dt>Dataset</dt>
            <dd>A 'dataset' is one record in the database. One record may contain multiple files of different datatypes. The exact composition of a 'dataset' is defined in the database's 'datasetdef'.</dd>
            <dt>Datatype</dt>
            <dd>A 'datatype' is one of a predefined type of data which can be assigned to a datasetdef. E.g. there may be 'HRTF', 'PNG',of 'CSV'.</dd>
            <dt>Datasetdef</dt>
            <dd>A 'datasetdef' is a definition of which datatypes are in each dataset. E.g. a dataset may contain two different hrtfs and one png per record.</dd>
        </dl>
        @role('admin')
        <p>You have the 'admin' role</p>
        @else
        <p>You do not have the 'admin' role</p>
        @endrole
</x-app-layout>
