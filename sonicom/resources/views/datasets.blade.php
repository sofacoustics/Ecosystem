<h1> Datasets </h1>

<ul>
@if (isset($datasets->data))
    @foreach ($datasets->data as $dataset)
    <li> {{ $dataset->descriptiveMetadata->title }} </li>
    @endforeach
@else
    <p>There are not datasets in this workspace!</p>    
@endif
</ul>