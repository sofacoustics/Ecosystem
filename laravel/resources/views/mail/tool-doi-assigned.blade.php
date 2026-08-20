<div>
	<x-mail.header :admin="$admin" :user="$tool->user" />
	@if($admin)
		<p>The DOI {{ $tool->doi }} was assigned to the tool <x-mail.tool :tool="$tool" :admin="$admin" /> by <x-mail.actor :actor="$actor" />.</p>
	@else
		<p>A DOI {{ $tool->doi }} has been assigned to your tool <x-mail.tool :tool="$tool" />. 
		You may now reference this tool in your publications using this DOI. However, please note that until you have 'persistently published' 
		this tool, the DOI is only reserved for you and not yet persistent!</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
