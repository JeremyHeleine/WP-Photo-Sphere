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
			$('.wpps_container a').each(function(){
					var a = $(this);
					var href = a.attr('href').split('?height=');
					href[1] = href[1].split('&hide=');
					href[1][1] = href[1][1].split('&load=');
					a.attr('href', href[0]).click(function(){wpps_load($(this), href[0], href[1][0], parseInt(href[1][1][0]), href[1][1][1]); return false;});
				});
		});

	function wpps_load(a, image, height, hide, load) {
		var div = a.parent().children('div');
		if (hide)
			a.remove();
		else
			a.off('click').click(function(){return false;});

		var load = $('<img />').attr('src', load).attr('alt', 'Wait...').appendTo(div);
		load.css({'position': 'absolute', 'top': '50%', 'left': '50%', 'margin-top': -(load.height() / 2) + 'px', 'margin-left': -(load.width() / 2) + 'px'});
		new Photosphere(image).loadPhotosphere(div.height(height)[0]);
	}
});
