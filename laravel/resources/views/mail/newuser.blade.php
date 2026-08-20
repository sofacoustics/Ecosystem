<div>
	<x-mail.header :admin='true' />
	<p>The user {{ $user->name }} (id: {{ $user->id }}) using the email address {{ $user->email }} has been created and their ORCID ID has been verified.</p>
	<x-mail.footer :admin="$admin" />
</div>
