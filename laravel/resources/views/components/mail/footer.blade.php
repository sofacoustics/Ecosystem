@props([
	'admin' => false,
])
<div>
	@if($admin)
		<p>The {{ config('app.name') }} Mail System</p>
	@else
		<p>The {{ config('app.name') }} Team!</p>
	@endif
</div>

