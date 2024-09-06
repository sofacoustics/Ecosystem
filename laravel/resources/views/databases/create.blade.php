<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a database
        </h2>
    </x-slot>
        <p>TODO: implement database creation code/form</p>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    
        <form action="{{ route('databases.store') }}" method="POST">
            @csrf
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
            <label for="description">Description</label>
            <input type="text" name="description" id="description">
            {{--<input type="hidden" name="user_id" value="{{ Auth::id() }}">--}}
            <input type="submit" name="submit" value="Submit">
        </form>

    @guest
        <p>BUG: Please report that you got to this page. This should only be accessable by authenticated users.</p>
    @endguest
</x-app-layout>
