<!-- resources/views/components/th.blade.php -->
<th {{ $attributes->merge(['class' => 'border p-2']) }}>
	{{ $slot }}
</th>
