<?php
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

/*
Plugin Name: WP Photo Sphere
Plugin URI: http://projects.jeremyheleine.com/wp-photo-sphere
Description: A filter that displays 360-degree panoramas taken with Photo Sphere. Read the readme file for instructions.
Version: 1.0
Author: Jérémy Heleine
Author URI: http://www.jeremyheleine.com
License: GPL3
*/

function wpps_activation() {
	add_option('wpps_settings', array('style' => 'margin: 10px auto 10px auto;', 'style_a' => 'padding: 5px; background-color: #3D3D3D; color: #FFFFFF;', 'text' => 'WP Photo Sphere (%title%)', 'width' => 560, 'height' => 315, 'hide_link' => 0));
}

function wpps_deactivation() {
	delete_option('wpps_settings');
}

function wpps_enqueue_scripts() {
	wp_enqueue_script('wpps-three', plugin_dir_url(__FILE__) . 'lib/three.min.js', array(), '1.0', true);
	wp_enqueue_script('wpps-sphere', plugin_dir_url(__FILE__) . 'lib/sphere.js', array(), '1.0', true);
	wp_enqueue_script('wp-photo-sphere', plugin_dir_url(__FILE__) . 'wp-photo-sphere.js', array('jquery'), '1.0', true);
}

function wpps_lang() {
	load_plugin_textdomain('wp-photo-sphere', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

function wpps_replace_tags($content) {
	$results = array();
	$n = preg_match_all('#\[sphere ([0-9]+)(?: width="([0-9]+)" height="([0-9]+)")?\]#', $content, $results, PREG_SET_ORDER);
	if ($n !== false && $n > 0) {
		$settings = get_option('wpps_settings');
		foreach ($results as $result) {
			$image = wp_get_attachment_url($result[1]);
			$text = str_replace('%title%', get_the_title($result[1]), $settings['text']);
			$width = (isset($result[2])) ? intval($result[2]) : $settings['width'];
			$height = (isset($result[3])) ? intval($result[3]) : $settings['height'];
			$style = $settings['style'] . ' width: ' . $width . 'px;';
			$content = str_replace($result[0], '<div class="wpps_container" style="' . $style . '"><a href="' . $image . '?height=' . $height . '&amp;hide=' . $settings['hide_link'] . '&amp;load=' . plugin_dir_url(__FILE__) . 'load.gif" style="display: block; ' . $settings['style_a'] . '">' . $text . '</a><div style="position: relative;"></div></div>', $content);
		}
	}

	return $content;
}

function wpps_create_menu() {
	add_options_page('WP Photo Sphere', 'WP Photo Sphere', 'manage_options', __FILE__, 'wpps_options_page');
	add_action('admin_init', 'wpps_register_settings');
}

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
					<th><label for="wpps_settings_height"><?php _e('Default height', 'wp-photo-sphere'); ?></label></th>
					<td><input type="text" id="wpps_settings_height" name="wpps_settings[height]" size="5" value="<?php echo $settings['height']; ?>" /></td>
				</tr>

				<tr valign="top">
					<th><label for="wpps_settings_hide_link"><?php _e('Hide link', 'wp-photo-sphere'); ?></label></th>
					<td><input type="checkbox" id="wpps_settings_hide_link" name="wpps_settings[hide_link]" value="1"<?php checked((1 == $settings['hide_link'])); ?> /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function wpps_style_for_textarea($style) {
	return trim(preg_replace('#;\s*#', ';' . "\n", $style));
}

function wpps_sanitize_style($style) {
	return trim(preg_replace('#;\s*#', '; ', str_replace('"', '\'', $style)));
}

function wpps_sanitize_settings($values) {
	$values['style'] = wpps_sanitize_style($values['style']);
	$values['style_a'] = wpps_sanitize_style($values['style_a']);
	$values['width'] = intval($values['width']);
	$values['height'] = intval($values['height']);
	$values['hide_link'] = ($values['hide_link']) ? 1 : 0;
	return $values;
}

register_activation_hook(__FILE__, 'wpps_activation');
register_deactivation_hook(__FILE__, 'wpps_deactivation');
add_action('wp_enqueue_scripts', 'wpps_enqueue_scripts');
add_filter('the_content', 'wpps_replace_tags');
add_action('admin_menu', 'wpps_create_menu');
add_action('plugins_loaded', 'wpps_lang');
?>
