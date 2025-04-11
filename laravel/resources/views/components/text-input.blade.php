@props(['disabled' => false])

@if($disabled)
	<input disabled style="background-color:#e5e7e9; color:#909497;" {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} >
@else 
	<input {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} >
@endif