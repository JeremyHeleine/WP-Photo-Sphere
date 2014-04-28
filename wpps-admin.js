/*
 * This file is part of WP Photo Sphere v2.2
 * http://jeremyheleine.com/#wp-photo-sphere
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
			wpps_resize_img();
			$('#insert-wpps-button').click(wpps_open_media_frame);
		});

	function wpps_resize_img() {
		var img = $('#insert-wpps-button img');
		var height = $('.wp-media-buttons-icon').height();
		var padding = (img.parent().height() - height) / 2;
		img.height(height).css('padding', padding+'px 0');
	}

	var wpps_open_media_frame = (function() {
			var media_frame = null;

			return function() {
					// Is the frame already created?
					if (media_frame == null) {
						var insert_pano = $('#insert-wpps-button span').text();
						var insert = insert_pano.substring(0, insert_pano.indexOf(' '));

						media_frame = wp.media({
								title: insert_pano,
								library: {type: 'image'},
								multiple: false,
								button: {text: insert}
							});

						media_frame.on('select', function() {
								var id = media_frame.state().get('selection').first().toJSON().id;
								wpps_insert_tag(id);
							});
					}

					media_frame.open();
					return false;
				};
		})();

	function wpps_insert_tag(id) {
		var tag = '[sphere '+id+']';
		wp.media.editor.insert(tag);
	}
});
