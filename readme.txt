=== Fast 404 ===
Contributors: ayeshrajans
Tags: performance, 404, page-not-found
Requires at least: 3.9.2
Tested up to: 6.3
Stable tag: 1.2
Requires PHP: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevents WordPress from delivering full Page-Not-Found errors when the browser is not expecting a full HTML page. Saves bandwidth and improves performance.

== Description ==

Fast 404 is a low foot-print plugin that quickly inspects an incoming HTTP request, and terminates the request as soon as possible if the request is for a non-existing resource. If the browser is expecting an HTML page (indicated by the `Accept` HTTP header), this plugin will not intercept it. For all other requests, this plugin will terminate it immediately, saving server resources and bandwidth.

When a user browser requests a resource (such as a `jpg` image, or a `.woff2` font file), the web server sends this resource if it is available in the requested location. If the file does not exist, the request is forwarded to WordPress to handle. Unless you are using a plugin that dynamically generates these files, these file-not-found requests trigger a full WordPress Page-Not-Found error page. This plugin inspects such incoming requests, and if the browser indicates that it is looking for a resource other than an HTML page, this plugin terminates the request as soon as possible to prevent WordPress from serving this request which would be a waste of resources and bandwidth. This plugin carefully makes sure that the short-circuited 404 pages (which just shows "Not Found" on a blank page) is only returned to browser asset requests, and not for end users who expect an HTML page.

By default, all HTTP requests to `js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2` extensions will be fast 404'd. You can configure the extensions and even configure an exclusion pattern to prevent this plugin from intercepting certain requests.

This plugin is the WordPress port of [PHPWatch/Fast404](https://github.com/PHPWatch/Fast404) package.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. You are all set!


== Frequently Asked Questions ==

= How to configure the error message? =

This plugin ensures that the error message is not shown to end users who request URLs from the browser address bar. It is configured as "Not Found" by default.

However, you can override this message by adding a PHP constant to your `wp-config.php` file. Anywhere in this file, put this:


`define('FAST404_ERROR_MESSAGE', 'My new error message');`

= How to configure file types? =

By default, `js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2` extensions are terminated early.

You need to define a PHP constant in `wp-config.php` file to override this. The value of the constant MUST be a valid regular expression matched against the request URI.


`define('FAST404_REGEX', '/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i')`

= How can I log requests? =

You cannot. The whole point of this plugin is to save server resources when the request cannot be served. It takes the first opportunity to terminate the request, and it might be early in the page request-cycle that any of the logging functionality is even available yet.

= What screams "I'm insecure"? =

"http://"

== Changelog ==

= 1.0 =
* Initial release.

= 1.0.1 =
* Updates the WordPress core version this plugin was tested against.
* Minor text improvements.

= 1.2 =
* Same version as 1.0.1, but with a consistent version number bump.
