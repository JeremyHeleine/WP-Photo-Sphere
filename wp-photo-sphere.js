/*
Copyright 2013 Jérémy Heleine

This file is part of WP Photo Sphere.

WP Photo Sphere is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

WP Photo Sphere is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Photo Sphere.  If not, see <http://www.gnu.org/licenses/>.
*/

jQuery(function($) {
	$(document).ready(function() {
			// For each WP Photo Sphere link
			$('.wpps_container a').each(function(){
					var a = $(this);
					// href[0]: image URL
					// href[1]: parameters
					var href = a.attr('href').split('?');
					// params[0]: viewer height
					// params[1]: hide link?
					// params[2]: loading image URL
					// params[3]: autoload?
					var params = href[1].split('&');

					// Autoload or click event
					a.attr('href', href[0]);
					if (params[3].split('=')[1] == 'true')
						wpps_load(a, params[0].split('=')[1], (params[1].split('=')[1] == '1'), params[2].split('=')[1]);
					else
						a.click(function(){wpps_load(a, params[0].split('=')[1], (params[1].split('=')[1] == '1'), params[2].split('=')[1]); return false;});
				});
		});

	// Load panorama
	function wpps_load(a, height, hide, loading) {
		// Future container of the panorama and image URL
		var div = a.parent().children('div');
		var image = a.attr('href');

		// Hide link?
		if (hide)
			a.remove();
		else
			a.off('click').click(function(){return false;});

		// Loading image and creation of the Photosphere object (panorama)
		var load = $('<img />').attr('src', loading).attr('alt', 'Wait...').appendTo(div);
		load.css({'position': 'absolute', 'top': '50%', 'left': '50%', 'margin-top': -(load.height() / 2) + 'px', 'margin-left': -(load.width() / 2) + 'px'});
		new Photosphere(image).loadPhotosphere(div.height(height)[0]);
	}
});
