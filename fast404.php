<?php

/**
 * Plugin Name: Fast 404
 * Version:     1.0
 * Description: Prevent WordPress from delivering full Page-Not-Found errors when the browser is not expecting a full HTML page. Saves bandwidth and improvements performance.
 * Licence:     GPLv2 or later
 * Author:      Ayesh Karunaratne
 * Author URI:  https://ayesh.me/open-source
 */

declare(strict_types=1);

namespace PHPWatch\WP_Fast404;

use function defined;
use function http_response_code;
use function preg_match;
use function strpos;

/**
 * Evaluate the conditions for a fast-404
 *
 * If the regex is not provided, we need to immediately exit. Next, the $accept_header is checked for a simple string
 * match for the allowed mime. It is not configurable as of yet to prevent user-errors, and a regex is not used for
 * performance. If both these conditions are met, the main expression is evaluated, and if it matches, tries to exclude
 * if an exclude expression is configured.
 *
 * @param string $request_uri
 * @param string $accept_header
 * @param array $config
 * @return bool
 */
function evaluate(string $request_uri, string $accept_header, array $config): bool {
    return
        $config['regex']
        && strpos($accept_header, $config['allow_mime']) === false
        && preg_match($config['regex'], $request_uri)
        && !(isset($config['exclude_regex']) && preg_match($config['exclude_regex'], $request_uri));
}

/**
 * The main entry-point of the plugin. It decouples the request-uri and accept header values. WordPress functions such
 * as wp_die() are not used to make sure the request is terminated as quickly as possible.
 */
function run(): void {
    $config = config();
    if (!evaluate($_SERVER['REQUEST_URI'] ?? '', $_SERVER['HTTP_ACCEPT'] ?? '', $config)) {
        return;
    }

    http_response_code(404);
    die($config['error_message']);
}

/**
 * Returns an array of configuration options for the plugin. They can be configured by setting the relevant PHP
 * constants in the global namespace in a `wp-config.php` file. See the readme.txt file for more information.
 *
 * @return array
 */
function config(): array {
    return [
        'error_message' => defined('FAST404_ERROR_MESSAGE') ? \FAST404_ERROR_MESSAGE : 'Not found',
        'regex' => defined('FAST404_REGEX')
            ? \FAST404_REGEX
            : '/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i',
        'exclude_regex' => defined('FAST404_EXCLUDE_REGEX') ? \FAST404_EXCLUDE_REGEX : null,
        'allow_mime' => 'text/html',
    ];
}

run();
