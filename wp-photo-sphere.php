<?php
/*
 * WP Photo Sphere v3.4.2
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

/*
Plugin Name: WP Photo Sphere
Plugin URI: http://jeremyheleine.me
Description: A filter that displays 360×180 degree panoramas. Please read the readme file for instructions.
Version: 3.4.1
Author: Jérémy Heleine
Author URI: http://jeremyheleine.me
Text Domain: wp-photo-sphere
Domain Path: /lang/
License: MIT
*/

// Current version number
if (!defined('WP_PHOTO_SPHERE_VERSION'))
	define('WP_PHOTO_SPHERE_VERSION', '3.4.1');

function wpps_activation() {
	update_option('wpps_version', WP_PHOTO_SPHERE_VERSION);

	$default_settings = array(
		'style' => 'margin: 10px auto;',
		'style_a' => 'padding: 5px; background-color: #3D3D3D; color: #FFFFFF;',
		'class_a' => '',
		'text' => 'WP Photo Sphere (%title%)',
		'autoload' => 0,
		'width' => '560px',
		'max_width' => '100%',
		'height' => '315px',
		'hide_link' => 0,
		'anim_speed' => '2rpm',
		'vertical_anim_speed' => '2rpm',
		'vertical_anim_target' => 0,
		'navbar' => 1,
		'min_fov' => 30,
		'max_fov' => 90,
		'zoom_level' => 0,
		'long' => 0,
		'lat' => 0,
		'tilt_up_max' => 90,
		'tilt_down_max' => 90,
		'min_long' => 0,
		'max_long' => 360,
		'reverse_anim' => 1,
		'xmp' => 1
	);

	$settings = get_option('wpps_settings');
	if ($settings === false)
		$settings = array();

	update_option('wpps_settings', array_merge($default_settings, $settings));
}
register_activation_hook(__FILE__, 'wpps_activation');

function wpps_check_version() {
	if (WP_PHOTO_SPHERE_VERSION !== get_option('wpps_version'))
		wpps_activation();
}
add_action('plugins_loaded', 'wpps_check_version');

function wpps_deactivation() {
	delete_option('wpps_settings');
}
register_deactivation_hook(__FILE__, 'wpps_deactivation');

function wpps_register_scripts() {
	wp_register_script('wpps-three', plugin_dir_url(__FILE__) . 'lib/three.min.js', array(), '3.3', true);
	wp_register_script('wpps-psv', plugin_dir_url(__FILE__) . 'lib/photo-sphere-viewer.min.js', array('wpps-three'), '2.4.1', true);
	wp_register_script('wp-photo-sphere', plugin_dir_url(__FILE__) . 'wp-photo-sphere.js', array('jquery', 'wpps-psv'), '3.4.1', true);
}
add_action('plugins_loaded', 'wpps_register_scripts');

function wpps_enqueue_admin_scripts() {
	if (floatval(get_bloginfo('version')) >= 3.5)
		wp_enqueue_script('wpps-admin', plugin_dir_url(__FILE__) . 'wpps-admin.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_media', 'wpps_enqueue_admin_scripts');

function wpps_add_pano_button() {
	if (floatval(get_bloginfo('version')) >= 3.5) {
		?>
		<a href="#" id="insert-wpps-button" class="button" title="<?php _e('Add a panorama', 'wp-photo-sphere'); ?>">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>wpps-button.png" alt="" style="margin: 0 2px; padding: 0; height: 100%; width: auto; vertical-align: top;" />
			<span><?php _e('Add a panorama', 'wp-photo-sphere'); ?></span>
		</a>
		<?php
	}
}
add_action('media_buttons', 'wpps_add_pano_button', 15);

function wpps_lang() {
	// i18n
	load_plugin_textdomain('wp-photo-sphere', false, dirname(plugin_basename(__FILE__)) . '/lang');
}
add_action('plugins_loaded', 'wpps_lang');

function wpps_shortcode_attributes($atts) {
	if (!empty($atts)) {
		$sizes = array('width', 'max_width');
		$numbers = array('height', 'anim_after', 'full_width', 'full_height', 'cropped_width', 'cropped_height');
		$floats = array('min_fov', 'max_fov', 'zoom_level', 'long', 'lat', 'vertical_anim_target', 'tilt_up_max', 'tilt_down_max', 'min_long', 'max_long', 'cropped_x', 'cropped_y');
		$booleans = array('navbar', 'reverse_anim', 'xmp');

		foreach ($atts as $att => $value) {
			// Unnamed attribute
			if (is_int($att)) {
				// ID
				if (is_numeric($value) && !isset($atts['id']))
					$atts['id'] = $value;

				// Boolean
				else
					$atts[$value] = 1;

				unset($atts[$att]);
			}

			// URL
			else if ($att == 'url' && !preg_match('#^https?://#', $value))
				$atts['url'] = 'http://' . $value;

			// Size
			else if (in_array($att, $sizes))
				$atts[$att] = wpps_sanitize_size($value);

			// Numbers
			else if (in_array($att, $numbers))
				$atts[$att] = intval($value);

			// Floating-point numbers
			else if (in_array($att, $floats))
				$atts[$att] = floatval($value);

			// Manual booleans
			else if (in_array($att, $booleans))
				$atts[$att] = intval(($value == 'yes'));
		}
	}

	return $atts;
}

function wpps_handle_shortcode($atts) {
	wp_enqueue_script('wp-photo-sphere');
	$settings = get_option('wpps_settings');

	// Attributes
	$atts = wpps_shortcode_attributes($atts);
	$atts = shortcode_atts(array(
		'id' => 0,
		'url' => '',
		'title' => '',
		'width' => $settings['width'],
		'max_width' => $settings['max_width'],
		'height' => intval($settings['height']),
		'autoload' => $settings['autoload'],
		'anim_after' => 'default',
		'anim_speed' => $settings['anim_speed'],
		'vertical_anim_speed' => $settings['vertical_anim_speed'],
		'vertical_anim_target' => $settings['vertical_anim_target'],
		'navbar' => $settings['navbar'],
		'min_fov' => $settings['min_fov'],
		'max_fov' => $settings['max_fov'],
		'zoom_level' => $settings['zoom_level'],
		'long' => $settings['long'],
		'lat' => $settings['lat'],
		'tilt_up_max' => $settings['tilt_up_max'],
		'tilt_down_max' => $settings['tilt_down_max'],
		'min_long' => $settings['min_long'],
		'max_long' => $settings['max_long'],
		'reverse_anim' => $settings['reverse_anim'],
		'xmp' => $settings['xmp'],
		'full_width' => 'default',
		'full_height' => 'default',
		'cropped_width' => 'default',
		'cropped_height' => 'default',
		'cropped_x' => 'default',
		'cropped_y' => 'default',
	), $atts);

	// URL and title
	$title = (!empty($atts['title'])) ? $atts['title'] : $settings['text'];

	if ($atts['id'] != 0) {
		$id = $atts['id'];
		$url = wp_get_attachment_url($id);
		$text = str_replace('%title%', get_the_title($id), $title);
	}

	else {
		$url = $atts['url'];
		$text = str_replace('%title%', '', $title);
	}

	// Style
	$style = $settings['style'] . ' width: ' . $atts['width'] . '; max-width: ' . $atts['max_width'] . ';';
	$class_a = (!empty($settings['class_a'])) ? ' class="' . $settings['class_a'] . '"' : '';

	// Display
	$output = '<div class="wpps_container" style="' . $style . '">';

	$params = implode('&amp;', array(
		'height=' . $atts['height'],
		'hide_link=' . $settings['hide_link'],
		'autoload=' . $atts['autoload'],
		'anim_after=' . $atts['anim_after'],
		'anim_speed=' . $atts['anim_speed'],
		'vertical_anim_speed=' . $atts['vertical_anim_speed'],
		'vertical_anim_target=' . $atts['vertical_anim_target'],
		'navbar=' . $atts['navbar'],
		'min_fov=' . $atts['min_fov'],
		'max_fov=' . $atts['max_fov'],
		'zoom_level=' . $atts['zoom_level'],
		'long=' . $atts['long'],
		'lat=' . $atts['lat'],
		'tilt_up_max=' . $atts['tilt_up_max'],
		'tilt_down_max=' . $atts['tilt_down_max'],
		'min_long=' . $atts['min_long'],
		'max_long=' . $atts['max_long'],
		'reverse_anim=' . $atts['reverse_anim'],
		'xmp=' . $atts['xmp'],
		'full_width=' . $atts['full_width'],
		'full_height=' . $atts['full_height'],
		'cropped_width=' . $atts['cropped_width'],
		'cropped_height=' . $atts['cropped_height'],
		'cropped_x=' . $atts['cropped_x'],
		'cropped_y=' . $atts['cropped_y']
	));

	$output .= '<a href="' . $url . '?' . $params . '" style="display: block; ' . $settings['style_a'] . '"' . $class_a . '>' . $text . '</a>';

	$output .= '<div style="position: relative; box-sizing: content-box;"></div>';
	$output .= '</div>';

	return $output;
}
add_shortcode('sphere', 'wpps_handle_shortcode');

function wpps_create_menu() {
	add_options_page('WP Photo Sphere', 'WP Photo Sphere', 'manage_options', __FILE__, 'wpps_options_page');
	add_action('admin_init', 'wpps_register_settings');
}
add_action('admin_menu', 'wpps_create_menu');

function wpps_register_settings() {
	register_setting('wpps_options', 'wpps_settings', 'wpps_sanitize_settings');
}

function wpps_options_page() {
	?>
	<div class="wrap">
		<h2>WP Photo Sphere</h2>

		<form method="post" action="options.php">
			<?php
			settings_fields('wpps_options');
			$settings = get_option('wpps_settings');

			$anim_speed = array();
			preg_match('#^([0-9-]+(?:\.[0-9]*)?)([a-z ]+)$#', $settings['anim_speed'], $anim_speed);

			$vertical_anim_speed = array();
			preg_match('#^([0-9-]+(?:\.[0-9]*)?)([a-z ]+)$#', $settings['vertical_anim_speed'], $vertical_anim_speed);
			?>
			<table class="form-table">
				<tr valign="top">
					<th><label for="wpps_settings_style"><?php _e('Style of the container', 'wp-photo-sphere'); ?></label></th>
					<td><textarea id="wpps_settings_style" name="wpps_settings[style]" cols="40" rows="5"><?php echo wpps_style_for_textarea($settings['style']); ?></textarea></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_style_a"><?php _e('Style of the link', 'wp-photo-sphere'); ?></label></th>
					<td><textarea id="wpps_settings_style_a" name="wpps_settings[style_a]" cols="40" rows="5"><?php echo wpps_style_for_textarea($settings['style_a']); ?></textarea></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_class_a"><?php _e('Class of the link', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_class_a" name="wpps_settings[class_a]" size="40" value="<?php echo $settings['class_a']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_text"><?php _e('Text of the link', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_text" name="wpps_settings[text]" size="40" value="<?php echo $settings['text']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th></th>
					<td><?php _e('Use the tag %title% to insert the panorama title', 'wp-photo-sphere'); ?></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_width"><?php _e('Default width', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_width" name="wpps_settings[width]" size="5" value="<?php echo $settings['width']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_max_width"><?php _e('Default maximum width', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_max_width" name="wpps_settings[max_width]" size="5" value="<?php echo $settings['max_width']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_anim_speed"><?php _e('Default animation speed', 'wp-photo-sphere'); ?></label></th>
					<td>
						<input type="text" id="wpp_settings_anim_speed" name="wpps_settings[anim_speed_value]" size="4" value="<?php echo $anim_speed[1]; ?>" />
						<select name="wpps_settings[anim_speed_unit]">
							<option value="rpm" <?php selected($anim_speed[2], 'rpm'); ?>><?php _e('Revolutions per minute', 'wp-photo-sphere'); ?></option>
							<option value="rps" <?php selected($anim_speed[2], 'rps'); ?>><?php _e('Revolutions per second', 'wp-photo-sphere'); ?></option>
							<option value="dpm" <?php selected($anim_speed[2], 'dpm'); ?>><?php _e('Degrees per minute', 'wp-photo-sphere'); ?></option>
							<option value="dps" <?php selected($anim_speed[2], 'dps'); ?>><?php _e('Degrees per second', 'wp-photo-sphere'); ?></option>
							<option value="rad per minute" <?php selected($anim_speed[2], 'rad per minute'); ?>><?php _e('Radians per minute', 'wp-photo-sphere'); ?></option>
							<option value="rad per second" <?php selected($anim_speed[2], 'rad per second'); ?>><?php _e('Radians per second', 'wp-photo-sphere'); ?></option>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_vertical_anim_speed"><?php _e('Default vertical animation speed', 'wp-photo-sphere'); ?></label></th>
					<td>
						<input type="text" id="wpp_settings_vertical_anim_speed" name="wpps_settings[vertical_anim_speed_value]" size="4" value="<?php echo $vertical_anim_speed[1]; ?>" />
						<select name="wpps_settings[vertical_anim_speed_unit]">
							<option value="rpm" <?php selected($vertical_anim_speed[2], 'rpm'); ?>><?php _e('Revolutions per minute', 'wp-photo-sphere'); ?></option>
							<option value="rps" <?php selected($vertical_anim_speed[2], 'rps'); ?>><?php _e('Revolutions per second', 'wp-photo-sphere'); ?></option>
							<option value="dpm" <?php selected($vertical_anim_speed[2], 'dpm'); ?>><?php _e('Degrees per minute', 'wp-photo-sphere'); ?></option>
							<option value="dps" <?php selected($vertical_anim_speed[2], 'dps'); ?>><?php _e('Degrees per second', 'wp-photo-sphere'); ?></option>
							<option value="rad per minute" <?php selected($vertical_anim_speed[2], 'rad per minute'); ?>><?php _e('Radians per minute', 'wp-photo-sphere'); ?></option>
							<option value="rad per second" <?php selected($vertical_anim_speed[2], 'rad per second'); ?>><?php _e('Radians per second', 'wp-photo-sphere'); ?></option>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_height"><?php _e('Default height', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_height" name="wpps_settings[height]" size="5" value="<?php echo $settings['height']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_autoload"><?php _e('Automatically load panoramas', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_autoload" name="wpps_settings[autoload]" value="1" <?php checked($settings['autoload'], 1); ?> /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_hide_link"><?php _e('Hide link', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_hide_link" name="wpps_settings[hide_link]" value="1" <?php checked($settings['hide_link'], 1); ?> /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_navbar"><?php _e('Display the navigation bar', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_navbar" name="wpps_settings[navbar]" value="1" <?php checked($settings['navbar'], 1); ?> /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_min_fov"><?php _e('Minimal field of view (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_min_fov" name="wpps_settings[min_fov]" value="<?php echo $settings['min_fov']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_max_fov"><?php _e('Maximal field of view (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_max_fov" name="wpps_settings[max_fov]" value="<?php echo $settings['max_fov']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_zoom_level"><?php _e('Default zoom level', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_zoom_level" name="wpps_settings[zoom_level]" value="<?php echo $settings['zoom_level']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_long"><?php _e('Default longitude (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_long" name="wpps_settings[long]" value="<?php echo $settings['long']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_lat"><?php _e('Default latitude (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_lat" name="wpps_settings[lat]" value="<?php echo $settings['lat']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_vertical_anim_target"><?php _e('Default vertical animation target (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_vertical_anim_target" name="wpps_settings[vertical_anim_target]" value="<?php echo $settings['vertical_anim_target']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_tilt_up_max"><?php _e('Maximal tilt up angle (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_tilt_up_max" name="wpps_settings[tilt_up_max]" value="<?php echo $settings['tilt_up_max']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_tilt_down_max"><?php _e('Maximal tilt down angle (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_tilt_down_max" name="wpps_settings[tilt_down_max]" value="<?php echo $settings['tilt_down_max']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_min_long"><?php _e('Minimal longitude (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_min_long" name="wpps_settings[min_long]" value="<?php echo $settings['min_long']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_max_long"><?php _e('Maximal longitude (in degrees)', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_max_long" name="wpps_settings[max_long]" value="<?php echo $settings['max_long']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_reverse_anim"><?php _e('Reverse animation', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_reverse_anim" name="wpps_settings[reverse_anim]" value="1" <?php checked($settings['reverse_anim'], 1); ?> /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_xmp"><?php _e('Read XMP data', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_xmp" name="wpps_settings[xmp]" value="1" <?php checked($settings['xmp'], 1); ?> /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function wpps_style_for_textarea($style) {
	// One declaration per line in the options page
	return trim(preg_replace('#;\s*#', ';' . "\n", $style));
}

function wpps_sanitize_style($style) {
	// Removes line breaks
	return trim(preg_replace('#;\s*#', '; ', str_replace('"', '\'', $style)));
}

function wpps_sanitize_size($size, $allowed_units = array('px', '%', 'cm', 'in')) {
	$value = intval($size);
	$unit = trim(str_replace($value, '', $size));

	if (!in_array($unit, $allowed_units))
		$unit = 'px';

	return $value . $unit;
}

function wpps_sanitize_speed($value, $unit) {
	if (!in_array($unit, array('rpm', 'rps', 'dpm', 'dps', 'rad per minute', 'rad per second')))
		$unit = 'rpm';

	return floatval($value) . $unit;
}

function wpps_sanitize_settings($values) {
	$values['style'] = wpps_sanitize_style($values['style']);
	$values['style_a'] = wpps_sanitize_style($values['style_a']);
	$values['class_a'] = trim($values['class_a']);
	$values['width'] = wpps_sanitize_size($values['width']);
	$values['max_width'] = wpps_sanitize_size($values['max_width']);
	$values['height'] = wpps_sanitize_size($values['height'], array('px'));
	$values['autoload'] = (!!$values['autoload']) ? 1 : 0;
	$values['hide_link'] = (!!$values['hide_link']) ? 1 : 0;
	$values['navbar'] = (!!$values['navbar']) ? 1 : 0;
	$values['min_fov'] = floatval($values['min_fov']);
	$values['max_fov'] = floatval($values['max_fov']);
	$values['zoom_level'] = max(0, min(intval($values['zoom_level']), 100));
	$values['long'] = floatval($values['long']);
	$values['lat'] = floatval($values['lat']);
	$values['vertical_anim_target'] = floatval($values['vertical_anim_target']);
	$values['tilt_up_max'] = floatval($values['tilt_up_max']);
	$values['tilt_down_max'] = floatval($values['tilt_down_max']);
	$values['min_long'] = floatval($values['min_long']);
	$values['max_long'] = floatval($values['max_long']);
	$values['reverse_anim'] = (!!$values['reverse_anim']) ? 1 : 0;
	$values['xmp'] = (!!$values['xmp']) ? 1 : 0;

	// Animation speed
	$values['anim_speed'] = wpps_sanitize_speed($values['anim_speed_value'], $values['anim_speed_unit']);
	$values['vertical_anim_speed'] = wpps_sanitize_speed($values['vertical_anim_speed_value'], $values['vertical_anim_speed_unit']);

	unset($values['anim_speed_value'], $values['anim_speed_unit'], $values['vertical_anim_speed_value'], $values['vertical_anim_speed_unit']);

	return $values;
}
?>
