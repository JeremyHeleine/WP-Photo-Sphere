=== WP Photo Sphere ===
Contributors: Jeremy Heleine
Tags: Google, Android, Photo Sphere, photos, panoramas, 360-degree
Requires at least: 3.1
Tested up to: 3.5
Stable tag: 1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A filter that displays 360-degree panoramas taken with Photo Sphere.

== Description ==

WP Photo Sphere is a filter that allows you to display 360-degree panoramas taken with Photo Sphere, the camera mode
brought by Android 4.2 Jelly Bean. With WP Photo Sphere, your visitors will be able to navigate through your panoramas
without install any plugin.

WP Photo Sphere is based on Photosphere, a JavaScript library written by Joe Simpson (kennydude):
<https://github.com/kennydude/photosphere>

If you want to contact me for any reason, feel free to email me at jeremy.heleine@gmail.com or contact me on:

* Twitter: http://twitter.com/JeremyHeleine
* Google+: https://plus.google.com/u/0/117007151468316562782
* Facebook: http://www.facebook.com/jeremy.heleine

== Installation ==

1. Upload the `wp-photo-sphere` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to add a panorama? =

1. Upload your panorama like any file and copy its ID.
2. Insert the tag `[sphere id]` and replace 'id' with the ID copied at step 1 (for example: `[sphere 42]`).

= How to change the dimensions? =

By default, the dimensions are 560 x 315 pixels but you can change that in the options page (in the Settings menu).

You can also choose different dimensions for each panorama using the attributes width and height.
For example: `[sphere 42 width="200" height="400"]`.

= Is it possible to autoload the panorama? =

Since the version 1.1, you can specify, for each panorama, a special attribute: `autoload`. If you use this attribute,
the panorama will start automatically after the page has loaded. This attribute doesn't require any value, for
example: `[sphere 42 autoload]` or `[sphere 42 width="300" height="150" autoload]`.

== Screenshots ==

1. Options page
2. WP Photo Sphere link
3. Panorama

== Changelog ==

= 1.1.1 =
* Nothing new, just an error in the readme file

= 1.1 =
* Autoload attribute

= 1.0 =
* First official release

== Upgrade Notice ==

= 1.1.1 =
* There was an error in the readme file...

= 1.1 =
* Autoload attribute

= 1.0 =
* First version
