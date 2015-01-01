# WP Photo Sphere

WP Photo Sphere is a filter that allows you to display 360×180 degree panoramas. With WP Photo Sphere, your visitors will be able
to navigate through your panoramas without install any plugin.

WP Photo Sphere is based on the JavaScript library [Photo Sphere Viewer](http://jeremyheleine.me/#photo-sphere-viewer).

This plugin allows you to display equirectangular panoramas, taken with a classic camera or with Photo Sphere on Android and iOS.

WP Photo Sphere is available in English, French, Spanish and, thanks to [Pedro Mendonça](https://github.com/pedro-mendonca), in Portuguese.

## How to install it

Like any other plugin, copy the `wp-photo-sphere` folder to the `/wp-content/plugins/` directory. Then, activate the plugin through the Plugins menu in WordPress

## How to use it

Use the `Add a panorama` button to upload or choose a panorama to insert into your post.

By default, the dimensions are 560 x 315 pixels but you can change that in the options page (in the Settings menu).

You can also choose different dimensions for each panorama using the attributes `width` and `height`. For example: `[sphere 42 width="200" height="400"]` or `[sphere 42 width="50%" height="300"]`.

A maximum width can also be given with the attribute `max_width`, for example: `[sphere 42 width="700" max_width="80%"]`. Its default value can be changed in the options page.

The navigation bar allows users to zoom, animate the panorama or view it in fullscreen. To display it, just use the `navbar` attribute
with the value `yes`: `[sphere 42 navbar="yes"]`.

You can choose to display it (or not) on all of your panoramas in the options page. If you display it on all of your panoramas and
want to deactivate it on one particular panorama, use the `navbar` attribute with the value `no`.

By default, panoramas are automatically animated after 2000 milliseconds, but you can change this with the `anim_after` attribute. You can also deactivate the animation with the value `-1`. For example: `[sphere 42 anim_after="5000"]` or `[sphere 42 anim_after="-1"]`.

You can set the animation speed with the `anim_speed` attribute. It accepts six units: revolutions per minute/second (rpm/rps), degrees per minute/second (dpm/dps) or radians per minute/second (rad per minute/second). The default speed can be set in the options page. Example: `[sphere 42 anim_speed="10rpm"]`.

With the `autoload` attribute, a panorama will start automatically after the page has loaded. For example: `[sphere 42 autoload]` or `[sphere 42 width="300" height="150" autoload]`.

## License

This plugin is available under the MIT license.
