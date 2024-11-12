<!-- RADAR dataset component -->
<div class="divide-emerald-100 divide-y divide-solid border border-2 border-emerald-200">
    <!-- https://arjunamrutiya.medium.com/mastering-laravel-blade-components-a-step-by-step-guide-9aff578621b8 -->
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
    <p>{{ $slot }}</p>

    <x-radar.div>
        <p>Creators:</p>
        @foreach ($dataset->descriptiveMetadata->creators->creator as $creator)
            <p>creatorName: {{ $creator['creatorName'] }}</p>
            {{-- @if(!is_null($creator['creatorAffiliation'])) --}}
            @if(array_key_exists('creatorAffiliation', $creator) && !is_null($creator['creatorAffiliation']))
                <p>creatorAffiliation: {{ $creator['creatorAffiliation']['value'] }}</p>
            @else
                <p>No affiliation</pj
            @endif
        @endforeach
    </x-radar.div>
    <x-radar.div>
        <p>Subject Areas:</p>
        @foreach ($dataset->descriptiveMetadata->subjectAreas->subjectArea as $subjectArea)
            <p>controlledSubjectAreaName: {{ $subjectArea['controlledSubjectAreaName'] }}</p>
            @if(!empty($subjectArea['additionalSubjectAreaName']))
                <p>additionalSubjectAreaName: {{ $subjectArea['additionalSubjectAreaName']}}</p>
            @endif
        @endforeach
    </x-radar.div>
    <x-radar.div>
        <p>Resource: {{ $dataset->descriptiveMetadata->resource->value }}</p>
    </x-radar.div>
</div>
