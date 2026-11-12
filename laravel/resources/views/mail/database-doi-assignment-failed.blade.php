<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>There was an error whilst trying to assing a DOI to the database <x-mail.database :database="$database" :admin="$admin" /> as requested by <x-mail.actor :actor="$actor" />. The user has been informed that there was a problem and that we will get in touch.</p>
	@else
		<p>For some reason no DOI could be assigned to your database <x-mail.database :database="$database" />. The site admins have been informed and will contact you to fix the problem.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
