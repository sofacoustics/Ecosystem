<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a dataset
        </h2>
    </x-slot>
        <p>TODO: implement dataset creation code/form</p>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    
        <form action="{{ route('dataset.store') }}" method="POST">
            @csrf
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
            {{--<input type="hidden" name="uploader_id" value="{{ Auth::id() }}">--}}
            <input type="submit" name="submit" value="Submit">
        </form>

    @guest
        <p>BUG: Please report that you got to this page. This should only be accessable by authenticated users.</p>
    @endguest
</x-app-layout>