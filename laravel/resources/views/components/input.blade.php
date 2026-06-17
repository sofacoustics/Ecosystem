<!-- resources/views/components/input.blade.php -->

@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300']) !!}>

