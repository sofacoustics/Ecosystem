<div id="imagecontainer">
	<p>
        <x-button type="button" onclick="zoomin()">Zoom In</x-button>
        <x-button type="button" onclick="zoomout()">Zoom Out</x-button>
        <x-button type="button" onclick="cw()">Rotate Clockwise</x-button>
				<x-button type="button" onclick="acw()">Rotate Anticlockwise</x-button>
				<x-button type="button" onclick="resetbutton()">Reset View</x-button>
  </p>
	<img id="image" class="p-2 " style="border: 1px solid #ccc; max-width:500px; position: relative;" onClick="reset(this)" src="{{ $datafile->asset() }}" />
</div>

<script>
	function cw() 
	{
		var myImg = document.getElementById("image");
		console.log(myImg.width);
		console.log(myImg.height);
		myImg.style.transform = "rotate(90deg)"; 
		x = myImg.width/2 - myImg.height/2;
		y = myImg.height/2 - myImg.width/2;
		myImg.style.top = x + "px";
		myImg.style.left = y + "px";
	}
	
	function acw() 
	{
		var myImg = document.getElementById("image");
		myImg.style.transform = "rotate(270deg)"; 
		x = myImg.width/2 - myImg.height/2;
		y = myImg.height/2 - myImg.width/2;
		myImg.style.top = x + "px";
		myImg.style.left = y + "px";
	}

 	function reset(myImg)
	{
		myImg.style.transform = "rotate(0deg)"; 
		myImg.style.top = "0px";
		myImg.style.left = "0px";
	}
	
 	function resetbutton()
	{
		var myImg = document.getElementById("image");
		myImg.style.transform = "rotate(0deg)"; 
		myImg.style.top = "0px";
		myImg.style.left = "0px";
	}
	
	function zoomin()
	{
		var myImg = document.getElementById("image");
		var currWidth = myImg.clientWidth;
		if(currWidth == 500)
		{
			alert("Maximum zoom-in level reached.");
		} 
		else
		{
			myImg.style.width = (currWidth + 50) + "px";
		} 
	}
	
	function zoomout()
	{
		var myImg = document.getElementById("image");
		var currWidth = myImg.clientWidth;
		if(currWidth == 50)
		{
			alert("Maximum zoom-out level reached.");
		} 
		else
		{
			myImg.style.width = (currWidth - 50) + "px";
		}
	}
</script>