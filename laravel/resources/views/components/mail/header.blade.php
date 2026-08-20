@props([
	'admin' => false,
	'user' => null,
])
@if($admin)
	<p>Dear {{ config('app.name') }} Admins!</p>
@else
	<p>Dear @isset($user) {{ $user->name }} @endisset ,</p>
@endif
