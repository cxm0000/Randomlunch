

function getLocation()
{
	console.log("Starting geolocation query");
	if (navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(showPosition);
	}
	else{
		consolo.log("Geolocation is not supported by this browser.");
	}
}

function showPosition(position)
{
	console.log(position);
	var input = document.getElementById("input_query");
	if (input && position.len != 0) {
		input.value = position.coords.latitude + ',' + position.coords.longitude;
	}
}