=== WP Photo Sphere ===
Contributors: Jeremy Heleine
Tags: Google, Android, Photo Sphere, photos, panoramas, 360-degree, equirectangular
Requires at least: 3.1
Tested up to: 4.1
Stable tag: 3.0
License: MIT
License URI: http://opensource.org/licenses/MIT

A filter that displays 360-degree panoramas taken with Photo Sphere.

== Description ==

WP Photo Sphere is a filter that allows you to display 360×180 degree panoramas. With WP Photo Sphere, your visitors will be able
to navigate through your panoramas without install any plugin.

WP Photo Sphere is based on the JavaScript library [Photo Sphere Viewer](http://jeremyheleine.me/#photo-sphere-viewer).

This plugin allows you to display equirectangular panoramas, taken with a classic camera or with Photo Sphere on Android and iOS.

If you want to contact me for any reason, feel free to email me at jeremy.heleine@gmail.com or contact me on:

* Twitter: http://twitter.com/JeremyHeleine
* Google+: https://plus.google.com/+JérémyHeleine
* Facebook: https://www.facebook.com/jeremy.heleine

WP Photo Sphere is available in English, French, Spanish and, thanks to [Pedro Mendonça](https://github.com/pedro-mendonca), in Portuguese.

== Installation ==

1. Upload the `wp-photo-sphere` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to add a panorama? =

Use the `Add a panorama` button to upload or choose a panorama to insert into your post.

= How to change the dimensions? =

By default, the dimensions are 560 x 315 pixels but you can change that in the options page (in the Settings menu).

You can also choose different dimensions for each panorama using the width and height attributes.
For example: `[sphere 42 width="200" height="400"]` or `[sphere 42 width="50%" height="300"]`.

A maximum width can also be given with the attribute max_width. Its default value can be changed in the options page.

= How to display the navigation bar? =

The navigation bar allows users to zoom, animate the panorama or view it in fullscreen. To display it, just use the `navbar` attribute
with the value `yes`: `[sphere 42 navbar="yes"]`.

You can choose to display it (or not) on all of your panoramas in the options page. If you display it on all of your panoramas and
want to deactivate it on one particular panorama, use the `navbar` attribute with the value `no`.

= Can I change the automatic animation? =

By default, panoramas are automatically animated after 2000 milliseconds, but you can change this with the
anim_after attribute. You can also deactivate the animation with the value -1. For example:
`[sphere 42 anim_after="5000"]` or `[sphere 42 anim_after="-1"]`.

You can set the animation speed with the anim_speed attribute. It accepts six units: revolutions per minute/second
(rpm/rps), degrees per minute/second (dpm/dps) or radians per minute/second (rad per minute/second). The default
speed can be set in the options page.
Example: `[sphere 42 anim_speed="10rpm"]`.

= Is it possible to autoload the panorama? =

Since the version 1.1, you can specify, for each panorama, a special attribute: `autoload`. If you use this attribute,
the panorama will start automatically after the page has loaded. This attribute doesn't require any value, for
example: `[sphere 42 autoload]` or `[sphere 42 width="300" height="150" autoload]`.

== Screenshots ==

1. Options page
2. WP Photo Sphere link
3. Panorama

== Changelog ==

= 3.0 =
* Equirectangular panoramas compatibility (not only Photo Sphere)
* Navigation bar
* XMP data attribute (to read them or not)
* Portuguese language available

= 2.4 =
* You can set your own animation speed

= 2.3.1 =
* Bug fixed

= 2.3 =
* Panoramas are now mobile compatible
* Spanish language available

= 2.2 =
* "Add a panorama" button now compatible with the visual editor

= 2.1 =
* Maximum width can be given
* Autoload after 1 second

= 2.0 =
* New library
* New "Add a panorama" button
* New anim_after attribute
* Scipts loaded only if necessary

= 1.1.1 =
* Nothing new, just an error in the readme file

= 1.1 =
* Autoload attribute

= 1.0 =
* First official release

== Upgrade Notice ==

= 3.0 =
* Equirectangular panoramas compatibility (not only Photo Sphere)
* Navigation bar
* XMP data attribute (to read them or not)
* Portuguese language available

= 2.4 =
* You can set your own animation speed

= 2.3.1 =
* Bug fixed

= 2.3 =
* Panoramas are now mobile compatible
* Spanish language available

= 2.2 =
* "Add a panorama" button now compatible with the visual editor

= 2.1 =
* Maximum width can be given
* Autoload after 1 second

= 2.0 =
* New library used: Photo Sphere Viewer
* New "Add a panorama" button
* New anim_after attribute
* Scripts are now loaded only if necessary

= 1.1.1 =
* There was an error in the readme file...

= 1.1 =
* Autoload attribute

= 1.0 =
* First version
