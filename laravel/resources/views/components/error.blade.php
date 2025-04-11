{{--
    A component to display an error message

    Parameters:

        attribute`  The attribute to display the error message for
--}}
<div>
        @error($attribute)
            <span class="text-red-500">{{ $message }}</span>
        @enderror
</div>
