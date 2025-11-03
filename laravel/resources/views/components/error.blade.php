{{--
	A component to display an error message

	Parameters:

		attribute`	The attribute to display the error message for

	Notes:

		J.W. 3.11.2025	

		The code where not $attribute is set works. I'm not sure that the $attribute code works though!
--}}
<div>
	@if(isset($attribute))
		@error($attribute)
			<span class="text-red-500">{{ $message }}</span>
		@enderror
	@else
			<span class="text-red-500">{{ $slot }}</span>
	@endif
</div>
