(function ($) {
    "use strict";
    var map, CustomHtmlIcon, geocoder, marker;
	var listingLocation = {
		init: function() {
			var self = this;
			setTimeout(function(){
				self.initializeMap();
			}, 200);
		},
		initializeMap: function() {
			var self = this;
			var maps = [];
			if ( $('.listdo-location-field').length > 0 ) {
				var mapInstance = $('.listdo-location-field');
				var geocoder_e = mapInstance.find( '.geocoder' );
				var mapCanvas = mapInstance.find( '.listdo-location-field-map' ).attr('id');
				var location_address = mapInstance.find( '.input-location-field' );
				var latitude = mapInstance.find( '.geo_latitude' );
				var longitude = mapInstance.find( '.geo_longitude' );
				var latitude_default = 43.6568;
				var longitude_default = -79.4512;
				if ( listing_opts.geocoder_country ) {
					latitude_default = listing_opts.latitude;
				}
				if ( listing_opts.geocoder_country ) {
					longitude_default = listing_opts.longitude;
				}
				var latLng = [latitude_default, longitude_default];
				var zoom = 8;

				// If we have saved values, let's set the position and zoom level
				if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
					latLng = [latitude.val(), longitude.val()];
					zoom = 13;
				}

				var $window = $(window);

				if ( listing_opts.geocoder_country ) {
                    geocoder = new L.Control.Geocoder.Nominatim({
                        geocodingQueryParams: {countrycodes: listing_opts.geocoder_country}
                    });
                } else {
                    geocoder = new L.Control.Geocoder.Nominatim();
                }

				map = L.map('listdo-location-field-map', {
	                scrollWheelZoom: false,
	                center: latLng,
		            zoom: zoom,
		            zoomControl: true,
	            });

	            CustomHtmlIcon = L.HtmlIcon.extend({
	                options: {
	                    html: "<div class='map-popup'></div>",
	                    iconSize: [42, 42],
	                    iconAnchor: [22, 42],
	                    popupAnchor: [0, -42]
	                }
	            });

	            $window.on('pxg:refreshmap', function() {
                	map._onResize();
	            });

	            $window.on('pxg:simplerefreshmap', function() {
	                map._onResize();
	            });

	            if ( listdo_listing_map_opts.mapbox_token != '' ) {
	                var tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
	                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Mapbox</a> &copy;  <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
	                    maxZoom: 18,
	                    //detectRetina: true,
	                    id: listdo_listing_map_opts.mapbox_style,
	                    accessToken: listdo_listing_map_opts.mapbox_token
	                });
	            } else {
	                if ( listdo_listing_map_opts.custom_style != '' ) {
	                    try {
	                        var custom_style = $.parseJSON(listdo_listing_map_opts.custom_style);
	                        var tileLayer = L.gridLayer.googleMutant({
	                            type: 'roadmap',
	                            styles: custom_style
	                        });

	                    } catch(err) {
	                        var tileLayer = L.gridLayer.googleMutant({
	                            type: 'roadmap'
	                        });
	                    }
	                } else {
	                    var tileLayer = L.gridLayer.googleMutant({
	                        type: 'roadmap'
	                    });
	                }
	                $('#apus-listing-map').addClass('map--google');
	            }

	            map.addLayer(tileLayer);

	            var mapPinHTML = "<div class='map-popup map-popup-empty'><div class='icon-wrapper'><div class='icon-cat'><i class='fas fa-map-marker-alt'></i></div></div></div>";

	            marker = new L.marker(latLng, {
		            draggable: 'true',
		            icon: new CustomHtmlIcon({ html: mapPinHTML })
		        });

	            marker.on('dragend', function(event) {
		            var position = marker.getLatLng();
		            marker.setLatLng(position, {
		              	draggable: 'true'
		            }).bindPopup(position).update();

		            geocoder.reverse(position, map.options.crs.scale(map.getZoom()), function(results) {
		            	console.log(results[0].name);
		              	location_address.val(results[0].name);
		            });
		            latitude.val( position.lat );
					longitude.val( position.lng );
		        });

	            // search location
	            location_address.attr('autocomplete', 'off').after('<div id="leaflet-geocode-container"></div>');

	            $(document).on('keyup', '.input-location-field',function search(e) {
	            	var s = $(this).val(), $this = $(this);
	              	if ( s && s.length >= 2 ) {
	              		search_location_fc($this, s);
		            } else {
		            	$("#leaflet-geocode-container").html('').removeClass('active');
		            }
		        });

	            var search_location_fc = function($this, s) {
	            	$this.parent().addClass('loading');
	                geocoder.geocode(s, function(results) {
	                	var output_html = '';
                        for (var i = 0; i < results.length; i++) {
                            output_html += '<li class="result-item" data-latitude="'+results[i].center.lat+'" data-longitude="'+results[i].center.lng+'" ><i class="fa fa-map-marker" aria-hidden="true"></i> '+results[i].name+'</li>';
                        }
                        if ( output_html ) {
                            output_html = '<ul>'+ output_html +'</ul>';
                        }

                        $('#leaflet-geocode-container').html(output_html).addClass('active');

                        var highlight_texts = s.split(' ');

                        highlight_texts.forEach(function (item) {
                            $('#leaflet-geocode-container').highlight(item);
                        });

	                  	$this.parent().removeClass('loading');
	                });
	            }

		        $(document).on( "click", "#leaflet-geocode-container ul li", function(e) {
		            var newLatLng = new L.LatLng($(this).data('latitude'), $(this).data('longitude'));
		            location_address.val($(this).text());
		            marker.setLatLng(newLatLng).update(); 
		            map.panTo(newLatLng);

		            latitude.val($(this).data('latitude'));
		            longitude.val($(this).data('longitude'));
		            $("#leaflet-geocode-container").html('').removeClass('active');
		        });

		        latitude.change(function() {
		            var position = [parseInt(latitude.val()), parseInt(longitude.val())];
		            marker.setLatLng(position, {
		              draggable: 'true'
		            }).bindPopup(position).update();
		            map.panTo(position);
		        });

				longitude.change(function() {
		            var position = [parseInt(latitude.val()), parseInt(longitude.val())];
		            marker.setLatLng(position, {
		              draggable: 'true'
		            }).bindPopup(position).update();
		            map.panTo(position);
		        });

				// find me
				$(document).on('click', '.find-me-location', function() {
			        $(this).addClass('loading');
			        navigator.geolocation.getCurrentPosition(self.getLocation, self.getErrorLocation);
			    });

		        map.addLayer(marker).setView(marker.getLatLng(), zoom);
		        setTimeout(function() {
		        	map._onResize();
		        }, 100);
		        
			}
		},
		getLocation: function(position) {
			$('.geo_latitude').val(position.coords.latitude);
	        $('.geo_longitude').val(position.coords.longitude);
	        $('.input-location-field').val('Location');
	        
	        var position = [position.coords.latitude, position.coords.longitude];

	        marker.setLatLng(position, {
              	draggable: 'true'
            }).bindPopup(position).update();
	        map.panTo(position);

	        var geocodeService = L.esri.Geocoding.geocodeService();
	        geocodeService.reverse().latlng(position).run(function(error, result) {
		      	$('.input-location-field').val(result.address.Match_addr);
		    });

	        return $('.find-me-location').removeClass('loading');
		},
		getErrorLocation: function(position) {
	        return $('.find-me-location').removeClass('loading');
	    }
	}
	listingLocation.init();

    

})(jQuery);


