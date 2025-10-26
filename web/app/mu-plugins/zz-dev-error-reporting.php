<?php
/**
 * Local dev: hide PHP 7.4 deprecation notices emitted by the WP 5.0 core.
 *
 * WordPress's wp_debug_mode() force-enables error_reporting(E_ALL) when
 * WP_DEBUG is on. Must-use plugins load after that, so this is the earliest
 * place we can override it. Real errors and warnings still show.
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);
