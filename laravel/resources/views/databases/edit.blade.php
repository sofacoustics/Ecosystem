<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database:  {{ $database->title }}
        </h2>
        {{ $database->description }}
    </x-slot>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('update', $database)
    <form action="{{ route('databases.update', $database->id) }}" method="POST">
        @csrf
        @method('PUT')
        <fieldset>
            <legend>SONICOM fields</legend>
        <label for="title">Title
            <input type="text" name="title" id="title" value="{{ $database->title }}">
        </label><br>
        <label for="description_id">Description
            <input type="text" name="description" id="description_id" value="{{ $database->description }}">
        </label>
        </fieldset>

        <input type="submit" name="submit" value="Submit">
        <a type="submit" href="{{url()->previous()}}" class="btn btn-default">Cancel</a>
    </form>
    {{--
    <fieldset>
        <legend>RADAR fields</legend>
        @auth
            <livewire:radar.dataset :database="$database" />
        @else
            <x-radar.dataset :dataset="$database->radardataset">
                A radar.div component with a dataset parameter
            </x-radar.dataset>
        @endauth
    </fieldset>
    --}}

    {{--
        <fieldset>
            <legend>RADAR Dataset</legend>
        <label for="productionYear">Production Year
            <input type="text" name="productionYear" id="productionYear" value="{{ $database->radardataset?->descriptiveMetadata->productionYear }}">
        </label><br/>
        <label for="resource">Resource Type
            <input type="text" name="resource[value]" id="resourcevalue" value="{{ $database->radardataset?->descriptiveMetadata->resource->value }}">
            <input type="text" name="resource[resourceType] id="resourcetype" value="{{ $database->radardataset?->descriptiveMetadata->resource->resourceType }}">
        </label><br/>
        <label>Rights
            <input type="text" name="rights[controlledRights]" value" value="{{ $database->radardataset?->descriptiveMetadata->rights->controlledRights}}">
            <input type="text" name="rights[additionalRights] value="{{ $database->radardataset?->descriptiveMetadata->rights->additionalRights}}">
        </label><br/>
        <label>Subject Areas
            <input type="text" name="subjectAreas[subjectArea][0]->controlledSubjectAreaName" value="{{ $database->radardataset?->descriptiveMetadata->subjectAreas->subjectArea[0]->controlledSubjectAreaName }}">
            <input type="text" name="subjectAreas[subjectArea][0]->additionalSubjectAreaName" value="{{ $database->radardataset?->descriptiveMetadata->subjectAreas->subjectArea[0]->additionalSubjectAreaName }}">
        </label><br/>
        <label>Publishers
            <input type="text" name="publishers[publisher][0]->value" value="{{ $database->radardataset?->descriptiveMetadata->publishers->publisher[0]->value }}">
        </label><br/>
        <label>Creators
            <input type="text" name="creators[creator][0]->creatorName" value="{{ $database->radardataset?->descriptiveMetadata->creators->creator[0]->creatorName }}">
        </label><br/>
        </fieldset>
    --}}
    @else
        <p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
    @endcan
    @guest
        <p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
    @endguest


</x-app-layout>
