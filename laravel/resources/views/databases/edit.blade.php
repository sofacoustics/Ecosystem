<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Database
        </h2>
    </x-slot>
    <h1>Title: {{ $database->title }}</h1>
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
            <legend>SONICOM Database</legend>
        <label for="title">Title
            <input type="text" name="title" id="title" value="{{ $database->title }}">
        </label><br>
        <label for="description_id">Description
            <input type="text" name="description" id="description_id" value="{{ $database->description }}">
        </label>
        </fieldset>
        <fieldset>
            <legend>RADAR Dataset</legend>
        {{-- 'title' will be used by RadardatasetpureData as well --}}
        <label for="productionYear">Production Year
            <input type="text" name="productionYear" id="productionYear" value="{{ $database->radardataset?->productionYear }}">
        </label><br/>
        <label for="resource">Resource Type
            {{-- use resource[value] *not* resource['value'] --}}
            <input type="text" name="resource[value]" id="resourcevalue" value="{{ $database->radardataset?->resource->value }}">
            <input type="text" name="resource[resourceType] id="resourcetype" value="{{ $database->radardataset?->resource->resourceType }}">
        </label><br/>
        <label>Rights
            <input type="text" name="rights[controlledRights]" value" value="{{ $database->radardataset?->rights->controlledRights}}">
            <input type="text" name="rights[additionalRights] value="{{ $database->radardataset?->rights->additionalRights}}">
        </label><br/>
        <label>Subject Areas
            <input type="text" name="subjectAreas[subjectArea][0][controlledSubjectAreaName]" value="{{ $database->radardataset?->subjectAreas->subjectArea[0]['controlledSubjectAreaName'] }}">
            <input type="text" name="subjectAreas[subjectArea][0][additionalSubjectAreaName]" value="{{ $database->radardataset?->subjectAreas->subjectArea[0]['additionalSubjectAreaName'] }}">
        </label><br/>
        <label>Publishers
            <input type="text" name="publishers[publisher][0][value]" value="{{ $database->radardataset?->publishers->publisher[0]['value'] }}">
        </label><br/>
        <label>Creators
            <input type="text" name="creators[creator][0][creatorName]" value="{{ $database->radardataset?->creators->creator[0]['creatorName'] }}">
        </label><br/>
        </fieldset>
        <input type="submit" name="submit" value="Submit">
    </form>
    @else
        <p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
    @endcan
    @guest
        <p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
    @endguest


    {{ $database }}
</x-app-layout>
