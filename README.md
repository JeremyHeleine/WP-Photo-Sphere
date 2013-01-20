# WP Photo Sphere

WP Photo Sphere is a plugin for WordPress, a filter that allows you to display 360-degree panorama taken with Photo Sphere, the camera mode brought by Android 4.2 Jelly Bean.

This plugin is based on the JavaScript library Photosphere written by Joe Simpson (kennydude) https://github.com/kennydude/photosphere

## How to install it

Like any other plugin, copy the `wp-photo-sphere` folder to the `/wp-content/plugins/` directory. Then, activate the plugin through the Plugins menu in WordPress

## How to use it

1. Upload your panorama like any file and copy its ID.
2. Insert the tag `[sphere id]` and replace `id` with the ID copied at step 1 (for example: `[sphere 42`]).

By default, the dimensions are 560 x 315 pixels but you can change that in the options page (in the Settings menu).

You can also choose different dimensions for each panorama using the attributes width and height. For example: `[sphere 42 width="200" height="400"]`.

## License

This plugin is available under the GNU GPL license.
