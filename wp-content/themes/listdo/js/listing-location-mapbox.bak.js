(function ($) {
    "use strict";
    var map;
	var listingLocation = {
		init: function() {
			var self = this;
			setTimeout(function(){
				self.initializeMap();
			}, 100);
		},
		initializeMap: function() {
			var maps = [];
			if ( $('.listdo-location-field').length > 0 ) {
				var mapInstance = $('.listdo-location-field');
				var geocoder_e = mapInstance.find( '.geocoder' );
				var mapCanvas = mapInstance.find( '.listdo-location-field-map' ).attr('id');
				var latitude = mapInstance.find( '.geo_latitude' );
				var longitude = mapInstance.find( '.geo_longitude' );
				var latLng = [-79.4512, 43.6568];
				var zoom = 1;

				// If we have saved values, let's set the position and zoom level
				if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
					latLng = [longitude.val(), latitude.val()];
					zoom = 13;
				}

				mapboxgl.accessToken = listdo_maps_opts.mapbox_token;
				map = new mapboxgl.Map({
					container: mapCanvas,
					style: 'mapbox://styles/mapbox/streets-v11',
					center: latLng,
					marker: {
						color: 'orange'
					},
					zoom: zoom
				});
				var marker = new mapboxgl.Marker({
					draggable: true,
					color: 'orange'
				}).setLngLat(latLng).addTo(map).on('dragend', function() {
					var lngLat = marker.getLngLat();
					latitude.val( lngLat.lat );
					longitude.val( lngLat.lng );
				});
				

				var geocoder = new MapboxGeocoder({
					accessToken: mapboxgl.accessToken,
					placeholder: listdo_maps_opts.placeholder,
					marker: false,
					zoom: 13,
					mapboxgl: mapboxgl
				});
				map.addControl(geocoder);
				
				//geocoder_e.append(geocoder.onAdd(map));

				geocoder.on('result', function(place) {
					console.log(place);
				   	if ( ! place.result.geometry ) {
						return;
					}
					latitude.val( place.result.geometry.coordinates[1] );
					longitude.val( place.result.geometry.coordinates[0] );

					marker.remove();
					var newmarker = new mapboxgl.Marker({
						draggable: true,
						color: 'orange'
					}).setLngLat(place.result.geometry.coordinates).addTo(map);
					newmarker.on('dragend', function() {
						var lngLat = newmarker.getLngLat();
						latitude.val( lngLat.lat );
						longitude.val( lngLat.lng );
					});
				});
			}
		},
	}
	listingLocation.init();

	
})(jQuery);


