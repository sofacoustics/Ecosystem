<div id="imagecontainer">
	<p>
        <x-button type="button" onclick="cw{{ $datafile->id }}()">Rotate Clockwise</x-button>
				<x-button type="button" onclick="acw{{ $datafile->id }}()">Rotate Anticlockwise</x-button>
				<x-button type="button" onclick="resetbutton{{ $datafile->id }}()">Reset View</x-button>
  </p>
	<a href="{{ $datafile->asset() }}" target="_blank">
		<img id="image{{ $datafile->id }}" class="p-2 " 
			style="border: 1px solid #ccc; max-width:500px; position: relative;" 
			onClick="reset(this)" 
			src="{{ $datafile->asset() }}" 
		/>
	</a>
</div>

<script>
	var Angle = 0;
	function cw{{ $datafile->id }}() 
	{
		var myImg = document.getElementById("image{{ $datafile->id }}");
		console.log(myImg.getBoundingClientRect());
		console.log(myImg.clientHeight);
		Angle = Angle + 90; 
		myImg.style.transform = "rotate(" + Angle + "deg)"; 
		rect = myImg.getBoundingClientRect();
		x = myImg.clientWidth/2 - rect.width/2;
		y = myImg.clientHeight/2 - rect.height/2;
		myImg.style.top = x + "px";
		myImg.style.left = y + "px";
	}
	
	function acw{{ $datafile->id }}() 
	{
		var myImg = document.getElementById("image{{ $datafile->id }}");
		Angle = Angle - 90;
		myImg.style.transform = "rotate(" + Angle + "deg)"; 
		rect = myImg.getBoundingClientRect();
		x = myImg.clientWidth/2 - rect.width/2;
		y = myImg.clientHeight/2 - rect.height/2;
		myImg.style.top = x + "px";
		myImg.style.left = y + "px";
	}

 	function reset(myImg)
	{
		myImg.style.transform = "rotate(0deg)";
		Angle = 0;
		myImg.style.top = "0px";
		myImg.style.left = "0px";
	}
	
 	function resetbutton{{ $datafile->id }}()
	{
		var myImg = document.getElementById("image{{ $datafile->id }}");
		Angle = 0;
		myImg.style.transform = "rotate(0deg)"; 
		myImg.style.top = "0px";
		myImg.style.left = "0px";
	}
	
</script>