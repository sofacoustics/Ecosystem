<section>
	<header>
		<h2 class="text-lg font-medium text-gray-900">
			Profile Information
		</h2>

		<p class="mt-1 text-sm text-gray-600">
			Update your account's profile information and email address.
		</p>
	</header>

	<form id="send-verification" method="post" action="{{ route('verification.send') }}">
		@csrf
	</form>

	<form id="profile" method="post" action="{{ route('profile.process') }}" class="mt-6 space-y-6">
		@csrf
		@method('patch')

		<div>
			<x-input-label for="name" :value="__('Name')"/>
			@if($user->orcid_verified_at ==null)
				<x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />						
			@else
				<x-text-input disabled id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
			@endif 
			<x-input-error class="mt-2" :messages="$errors->get('name')" />
		</div>

		<div>
			<x-input-label for="email" :value="__('Email')" />
			<x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
			<x-input-error class="mt-2" :messages="$errors->get('email')" />

			@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
				<div>
					<p class="text-sm mt-2 text-gray-800">
						Your email address is unverified. Verify your email address to obtain write access.
						<x-button form="send-verification" method="post" class="inline" action="{{ route('verification.send') }}">Resend the verification email</x-button>
					</p>

					@if (session('status') === 'verification-link-sent')
						<p class="mt-2 font-medium text-sm text-green-600">
							A new verification link has been sent to your email address. If you don't receive the email within a few minutes, contact the Ecosystem team. 
						</p>
					@endif
				</div>
			@endif
			@if($user->hasVerifiedEmail())
				<p><small>Email address verified on {{ $user->email_verified_at }} (GMT).</small></p>
			@endif
		</div>

		<div>
			@if($user->orcid_verified_at ==null)
				<x-button form="profile" type="submit" name="action" value="orcidLink">Link with ORCID to obtain write access</x-button>
			@else
				<x-input-label for="orcid" :value="__('ORCID')" />
				<x-text-input disabled id="orcid" name="orcid" type="text" class="mt-1 block w-1/2" :value="old('orcid', $user->orcid)" autofocus autocomplete="orcid" />
				<x-button-alert form="profile" class="inline" type="submit" name="action" value="orcidUnlink">Unlink ORCID</x-button>
				<p><small>Linked with ORCID on {{ $user->orcid_verified_at }} (GMT).</small></p>
			@endif
			<x-input-error class="mt-2" :messages="$errors->get('orcid')" />
		</div>

		<div class="flex items-center gap-4">
			<x-button form="profile" type="submit" name="action" value="update">Update profile</x-button>

			@if (Session('error'))
				<p class="text-danger">{{ session('error') }}</p>
			@endif

			@if (session('status'))
				<p
					x-data="{ show: true }"
					x-show="show"
					x-transition
					x-init="setTimeout(() => show = false, 5000)"
					class="text-sm text-gray-600"
				>{{ session('status') }}</p>
			@endif
		</div>
	</form>

</section>
