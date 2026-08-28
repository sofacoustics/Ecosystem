@hasrole('admin')
<div class="{{ $adminInfo }}">
	<p>Service Info for Admins!</p>
		<livewire:service-log-output :servicelog="$log"/>
</div>
@endhasrole
