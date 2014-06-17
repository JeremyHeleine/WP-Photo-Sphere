/*
 * This file is part of WP Photo Sphere v2.3
 * http://jeremyheleine.me/#wp-photo-sphere
 *
 * Copyright (c) 2013,2014 Jérémy Heleine
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
					// params[2]: autoload?
					// params[3]: anim after x milliseconds
					var params = href[1].split('&');

					// Autoload or click event
					a.attr('href', href[0]).click(function(){wpps_load(a, wpps_attr(params[0]), (wpps_attr(params[1]) == '1'), wpps_attr(params[3])); return false;});
					if (wpps_attr(params[2]) == '1')
						setTimeout(function(){a.click();}, 1000);
				});
		});

	// Get the value of an attribute
	function wpps_attr(param) {
		return param.split('=')[1];
	}

	// Load panorama
	function wpps_load(a, height, hide, anim_after) {
		// Future container of the panorama and image URL
		var div = a.parent().children('div');
		var panorama = a.attr('href');

		// Hide link?
		if (hide)
			a.remove();
		else
			a.off('click').click(function(){return false;});

		// Creation of the PhotoSphereViewer object
		var viewer_params = {
				panorama: panorama,
				container: div.height(height).css({'text-align': 'center', 'line-height': height+'px'})[0]
			};
		if (anim_after != 'default')
			viewer_params.time_anim = anim_after;
		new PhotoSphereViewer(viewer_params);
	}
});
