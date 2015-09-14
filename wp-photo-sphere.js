/*
 * This file is part of WP Photo Sphere v3.4.2
 * http://jeremyheleine.me
 *
 * Copyright (c) 2013-2015 Jérémy Heleine
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
	/**
	 * WP Photo Sphere class
	 * @param panorama (string) Panorama URL or path (absolute or relative)
	 * @param link (HTMLElement) The WP Photo Sphere link
	 * @param params_str (string) The parameters: key=value, separated by '&'
	 **/

	var WPPhotoSphere = function(panorama, link, params_str) {
		/**
		 * Checks if a value exists in an array
		 * @param value (mixed) The searched value
		 * @param array (Array) The array
		 * @return (boolean) true if the value exists in the array, false otherwise
		 **/

		var inArray = function(value, array) {
			for (var i = 0, l = array.length; i < l; ++i) {
				if (array[i] == value)
					return true;
			}

			return false;
		}

		/**
		 * Parses the parameters
		 * @param params_str (string) The parameters: key=value, separated by '&'
		 * @return (object) An object representing the parameters
		 **/

		var parse = function(params_str) {
			var params = {};

			// Booleans
			var booleans = ['hide_link', 'autoload', 'navbar', 'reverse_anim', 'xmp'];

			// String to array
			var params_array = params_str.split('&');
			var param;

			// Array to object
			for (var i = 0, l = params_array.length; i < l; ++i) {
				param = params_array[i].split('=');

				// Boolean?
				params[param[0]] = (inArray(param[0], booleans)) ? (param[1] == '1') : param[1];
			}

			return params;
		}

		/**
		 * Loads the panorama
		 * @return (void)
		 **/

		var load = function() {
			// Container
			var container = link.parent().children('div').css('text-align', 'center');

			// Removes the link or simply its event
			if (params.hide_link)
				link.remove();

			else
				link.unbind('click', load);

			// Basic options
			var options = {
				panorama: panorama,
				container: container[0],
				navbar: params.navbar,
				min_fov: params.min_fov,
				max_fov: params.max_fov,
				zoom_level: params.zoom_level,
				anim_speed: params.anim_speed,
				vertical_anim_speed: params.vertical_anim_speed,
				vertical_anim_target: params.vertical_anim_target,
				default_position: {
					long: params.long  + 'deg',
					lat: params.lat + 'deg'
				},
				tilt_up_max: params.tilt_up_max + 'deg',
				tilt_down_max: params.tilt_down_max + 'deg',
				min_longitude: params.min_long + 'deg',
				max_longitude: params.max_long + 'deg',
				reverse_anim: params.reverse_anim,
				usexmpdata: params.xmp,
				size: {height: params.height}
			};

			// Animation delay
			if (params.anim_after != 'default')
				options.time_anim = (params.anim_after == '-1') ? false : parseInt(params.anim_after);

			// Cropped panorama
			var pano_size = {};
			var size_attrs = ['full_width', 'full_height', 'cropped_width', 'cropped_height', 'cropped_x', 'cropped_y'];
			var not_default_attrs = 0;

			for (var i = 0; i < 6; ++i) {
				var attr = size_attrs[i];

				if (params[attr] != 'default') {
					pano_size[attr] = params[attr];
					++not_default_attrs;
				}
			}

			if (!!not_default_attrs)
				options.pano_size = pano_size;

			// Object
			new PhotoSphereViewer(options);
		}

		// Parameters
		var params = parse(params_str);

		// Link event
		link.click(function(){return false;});
		link.click(load);

		// Autoload?
		if (params.autoload)
			setTimeout(load, 1000);
	}

	$(document).ready(function() {
			// For each WP Photo Sphere link
			$('.wpps_container a').each(function(){
					var a = $(this);

					// href[0]: image URL
					// href[1]: parameters
					var href = a.attr('href').split('?');

					// WP Photo Sphere object
					new WPPhotoSphere(href[0], a.attr('href', href[0]), href[1]);
				});
		});
});
