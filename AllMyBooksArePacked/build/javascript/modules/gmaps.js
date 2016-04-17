console.info('gmaps module loaded')

define(['async!//maps.google.com/maps/api/js?sensor=false!callback'], function() {
	var init = function() {
		console.info('gmaps.init');

		var mapCanvas = $('#map-canvas');

		var location = {
			lon: mapCanvas.data('lon'),
			lat: mapCanvas.data('lat')
		}

		var zoom = mapCanvas.data('zoom');

		if(zoom==undefined)	zoom = 17;

		// GOOGLE MAPS
		var mapDiv = document.getElementById('map-canvas');
		var myLatlng = new google.maps.LatLng(location.lat,location.lon);
		var mapOptions = {
			zoom: zoom,
			center: myLatlng,
			navigationControl: false,
			mapTypeControl: false,
			scaleControl: false,
			draggable: false,
			scrollwheel: false,
			streetViewControl: false,
			disableDefaultUI: true
		};

		var map = new google.maps.Map(mapDiv, mapOptions);

		markerOptions = {
      position: myLatlng,
      map: map
	  }

		var marker = new google.maps.Marker(markerOptions);
	}

	// RETURN
	return {
		'init': init
	}
});