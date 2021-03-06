<?php

defined( 'ABSPATH' ) || exit;

/**
 * Require deprecated classes.
 */
require_once __DIR__ . '/DeprecatedClassTrait.php';
require_once __DIR__ . '/Engine/Optimization/QueryString/Remove.php';
require_once __DIR__ . '/Engine/Optimization/QueryString/RemoveSubscriber.php';

/**
 * Class aliases.
 */
class_alias( '\WP_Rocket\Engine\Admin\Beacon\ServiceProvider', '\WP_Rocket\ServiceProvider\Beacon' );
class_alias( '\WP_Rocket\Engine\HealthCheck\CacheDirSizeCheck', '\WP_Rocket\Subscriber\Tools\Cache_Dir_Size_Check_Subscriber' );
class_alias( '\WP_Rocket\Engine\HealthCheck\HealthCheck', '\WP_Rocket\Engine\Admin\HealthCheck' );
class_alias( '\WP_Rocket\Engine\Optimization\ServiceProvider', '\WP_Rocket\ServiceProvider\Optimization_Subscribers' );
class_alias( '\WP_Rocket\ThirdParty\Plugins\Smush', '\WP_Rocket\Subscriber\Third_Party\Plugins\Smush_Subscriber' );

/**
 * Generate the content of advanced-cache.php file.
 *
 * @since 3.6 deprecated
 * @since 3.5.5 Uses rocket_get_constant() for constants.
 * @since 2.1   Add filter rocket_advanced_cache_file.
 * @since 2.0.3
 *
 * @return  string  $buffer The content of avanced-cache.php file
 */
function get_rocket_advanced_cache_file() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
    _deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\Engine\Cache\AdvancedCache::get_advanced_cache_content()' );

	$buffer  = "<?php\n";
	$buffer .= "defined( 'ABSPATH' ) || exit;\n\n";

	// Add a constant to be sure this is our file.
	$buffer .= "define( 'WP_ROCKET_ADVANCED_CACHE', true );\n\n";

	$buffer .= "if ( ! defined( 'WP_ROCKET_CONFIG_PATH' ) ) {\n";
	$buffer .= "\tdefine( 'WP_ROCKET_CONFIG_PATH',       WP_CONTENT_DIR . '/wp-rocket-config/' );\n";
	$buffer .= "}\n\n";

	// Include the Mobile Detect class if we have to create a different caching file for mobile.
	if ( is_rocket_generate_caching_mobile_files() ) {
		$vendor_path = rocket_get_constant( 'WP_ROCKET_VENDORS_PATH' );

		$buffer .= "if ( file_exists( '" . $vendor_path . "classes/class-rocket-mobile-detect.php' ) && ! class_exists( 'Rocket_Mobile_Detect' ) ) {\n";
		$buffer .= "\tinclude_once '" . $vendor_path . "classes/class-rocket-mobile-detect.php';\n";
		$buffer .= "}\n\n";
	}

	// Register a class autoloader and include the process file.
	$buffer .= "if ( version_compare( phpversion(), '" . rocket_get_constant( 'WP_ROCKET_PHP_VERSION' ) . "' ) >= 0 ) {\n\n";

	// Class autoloader.
	$autoloader = rocket_direct_filesystem()->get_contents( rocket_get_constant( 'WP_ROCKET_INC_PATH' ) . 'process-autoloader.php' );

	if ( $autoloader ) {
		$autoloader = preg_replace( '@^<\?php\s*@', '', $autoloader );
		$autoloader = str_replace( [ "\n", "\n\t\n" ], [ "\n\t", "\n\n" ], trim( $autoloader ) );
		$autoloader = str_replace( 'WP_ROCKET_PATH', "'" . rocket_get_constant( 'WP_ROCKET_PATH' ) . "'", $autoloader );

		$buffer .= "\t$autoloader\n\n";
	}

	// Initialize the Cache class and process.
	$buffer .= "\t" . 'if ( ! class_exists( \'\WP_Rocket\Buffer\Cache\' ) ) {
		if ( ! defined( \'DONOTROCKETOPTIMIZE\' ) ) {
			define( \'DONOTROCKETOPTIMIZE\', true ); // WPCS: prefix ok.
		}
		return;
	}

	$rocket_config_class = new \WP_Rocket\Buffer\Config(
		[
			\'config_dir_path\' => \'' . rocket_get_constant( 'WP_ROCKET_CONFIG_PATH' ) . '\',
		]
	);

	( new \WP_Rocket\Buffer\Cache(
		new \WP_Rocket\Buffer\Tests(
			$rocket_config_class
		),
		$rocket_config_class,
		[
			\'cache_dir_path\' => \'' . rocket_get_constant( 'WP_ROCKET_CACHE_PATH' ) . '\',
		]
	) )->maybe_init_process();' . "\n";
	$buffer .= "} else {\n";
	// Add a constant to provent include issue.
	$buffer .= "\tdefine( 'WP_ROCKET_ADVANCED_CACHE_PROBLEM', true );\n";
	$buffer .= "}\n";

	/**
	 * Filter the content of advanced-cache.php file.
	 *
	 * @since 2.1
	 *
	 * @param string $buffer The content that will be printed in advanced-cache.php.
	*/
	return (string) apply_filters( 'rocket_advanced_cache_file', $buffer );
}

/**
 * This warning is displayed when the advanced-cache.php file isn't writeable
 *
 * @since 3.6 deprecated
 * @since 2.0
 */
function rocket_warning_advanced_cache_permissions() {
    _deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\Engine\Cache\AdvancedCache::notice_permissions()' );

	$advanced_cache_file = WP_CONTENT_DIR . '/advanced-cache.php';

	if ( current_user_can( 'rocket_manage_options' )
		&& ! rocket_direct_filesystem()->is_writable( $advanced_cache_file )
		&& ( ! defined( 'WP_ROCKET_ADVANCED_CACHE' ) || ! WP_ROCKET_ADVANCED_CACHE )
		&& rocket_valid_key() ) {

		$boxes = get_user_meta( get_current_user_id(), 'rocket_boxes', true );

		if ( in_array( __FUNCTION__, (array) $boxes, true ) ) {
			return;
		}

		$message = rocket_notice_writing_permissions( basename( WP_CONTENT_DIR ) . '/advanced-cache.php' );

		rocket_notice_html(
			[
				'status'           => 'error',
				'dismissible'      => '',
				'message'          => $message,
				'dismiss_button'   => __FUNCTION__,
				'readonly_content' => get_rocket_advanced_cache_file(),
			]
		);
	}
}

/**
 * This warning is displayed when the advanced-cache.php file isn't ours
 *
 * @since 3.6 Deprecated
 * @since 2.2
 */
function rocket_warning_advanced_cache_not_ours() {
    _deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\Engine\Cache\AdvancedCache::notice_content_not_ours()' );

	if ( ! ( 'plugins.php' === $GLOBALS['pagenow'] && isset( $_GET['activate'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		&& current_user_can( 'rocket_manage_options' )
		&& ! defined( 'WP_ROCKET_ADVANCED_CACHE' )
		&& ( defined( 'WP_CACHE' ) && WP_CACHE )
		&& get_rocket_option( 'version' ) === WP_ROCKET_VERSION
		&& rocket_valid_key() ) {

			$message = rocket_notice_writing_permissions( basename( WP_CONTENT_DIR ) . '/advanced-cache.php' );

			rocket_notice_html(
				[
					'status'      => 'error',
					'dismissible' => '',
					'message'     => $message,
				]
			);
	}
}

/**
 * Exclude fusion styles from cache busting to prevent cache dir issues
 *
 * @deprecated 3.6
 * @author Remy Perona
 *
 * @param array $excluded_files An array of excluded files.
 * @return array
 */
function rocket_exclude_avada_dynamic_css( $excluded_files ) {
    _deprecated_function( __FUNCTION__ . '()', '3.6' );

    $upload_dir = wp_upload_dir();

    $excluded_files[] = rocket_clean_exclude_file( $upload_dir['baseurl'] . '/fusion-styles/(.*)' );

    return $excluded_files;
}

/**
 * Excludes Uncode JS files from remove query strings
 *
 * @deprecated 3.6
 * @since 3.3.3
 * @author Remy Perona
 *
 * @param array $exclude_busting Array of CSS and JS filepaths to be excluded.
 * @return array
 */
function rocket_exclude_busting_uncode( $exclude_busting ) {
    _deprecated_function( __FUNCTION__ . '()', '3.6' );

    // CSS files.
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/css/style.css' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/css/uncode-icons.css' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/css/style-custom.css' );

    // JS files.
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/init.js' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/min/init.min.js' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/app.js' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/app.min.js' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/plugins.js' );
    $exclude_busting[] = rocket_clean_exclude_file( get_template_directory_uri() . '/library/js/plugins.min.js' );
    return $exclude_busting;
}

/**
 * Purge the cache when the beaver builder layout is updated to update the minified files content & URL
 *
 * @deprecated 3.6
 * @since 2.9 Also clear the cache busting folder
 * @since 2.8.6
 */
function rocket_beaver_builder_clean_domain() {
	_deprecated_function( __FUNCTION__ . '()', '3.6', 'WP_Rocket\ThirdParty\Plugins\PageBuilder\BeaverBuilder::purge_cache' );
	rocket_clean_minify();
	rocket_clean_domain();
}

/**
 * Returns paths used for cache busting
 *
 * @since 2.9
 * @deprecated 3.6
 * @author Remy Perona
 *
 * @param string $filename name of the cache busting file.
 * @param string $extension file extension.
 * @return array Array of paths used for cache busting
 */
function rocket_get_cache_busting_paths( $filename, $extension ) {
	_deprecated_function( __FUNCTION__ . '()', '3.6' );
	$blog_id                = get_current_blog_id();
	$cache_busting_path     = WP_ROCKET_CACHE_BUSTING_PATH . $blog_id;
	$filename               = rocket_realpath( rtrim( str_replace( [ ' ', '%20' ], '-', $filename ) ) );
	$cache_busting_filepath = $cache_busting_path . $filename;
	$cache_busting_url      = WP_ROCKET_CACHE_BUSTING_URL . $blog_id . $filename;

	switch ( $extension ) {
		case 'css':
			/** This filter is documented in inc/functions/minify.php */
			$cache_busting_url = apply_filters( 'rocket_css_url', $cache_busting_url );
			break;
		case 'js':
			/** This filter is documented in inc/functions/minify.php */
			$cache_busting_url = apply_filters( 'rocket_js_url', $cache_busting_url );
			break;
	}

	return [
		'bustingpath' => $cache_busting_path,
		'filepath'    => $cache_busting_filepath,
		'url'         => $cache_busting_url,
	];
}

/**
 * Caches SCCSS code & remove the default enqueued URL
 *
 * @since 2.9
 * @deprecated 3.6
 *
 * @author Remy Perona
 */
function rocket_cache_sccss() {
	_deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\ThirdParty\Plugins\SimpleCustomCss::cache_sccss()' );
	$sccss = rocket_get_cache_busting_paths( 'sccss.css', 'css' );

	if ( ! file_exists( $sccss['filepath'] ) ) {
		rocket_sccss_create_cache_file( $sccss['bustingpath'], $sccss['filepath'] );
	}

	if ( file_exists( $sccss['filepath'] ) ) {
		wp_enqueue_style( 'scss', $sccss['url'], '', filemtime( $sccss['filepath'] ) );
		remove_action( 'wp_enqueue_scripts', 'sccss_register_style', 99 );
	}
}

/**
 * Deletes & recreates cache for SCCSS code
 *
 * @since 2.9
 * @deprecated 3.6
 *
 * @author Remy Perona
 */
function rocket_delete_sccss_cache_file() {
	_deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\ThirdParty\Plugins\SimpleCustomCss::update_cache_file()' );
	$sccss = rocket_get_cache_busting_paths( 'sccss.css', 'css' );

	array_map( 'unlink', glob( $sccss['bustingpath'] . 'sccss*.css' ) );
	rocket_clean_domain();
	rocket_sccss_create_cache_file( $sccss['bustingpath'], $sccss['filepath'] );
}

/**
 * Returns the filename for SCSSS cache file
 *
 * @since 2.9
 * @deprecated 3.6
 *
 * @author Remy Perona
 *
 * @param string $filename filename.
 * @return string filename
 */
function rocket_sccss_cache_busting_filename( $filename ) {
	_deprecated_function( __FUNCTION__ . '()', '3.6' );
	if ( false !== strpos( $filename, 'sccss' ) ) {
		return preg_replace( '/(?:.*)(sccss(?:.*))/i', '$1', $filename );
	}

	return $filename;
}

/**
 * Creates the cache file for SCCSS code
 *
 * @since 2.9
 * @deprecated 3.6
 *
 * @author Remy Perona
 *
 * @param string $cache_busting_path Path to the cache busting directory.
 * @param string $cache_sccss_filepath Path to the sccss cache file.
 */
function rocket_sccss_create_cache_file( $cache_busting_path, $cache_sccss_filepath ) {
	_deprecated_function( __FUNCTION__ . '()', '3.6', '\WP_Rocket\ThirdParty\Plugins\SimpleCustomCss::create_cache_file()' );
	$options     = get_option( 'sccss_settings' );
	$raw_content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';
	$content     = wp_kses( $raw_content, [ '\'', '\"' ] );
	$content     = str_replace( '&gt;', '>', $content );

	if ( ! rocket_direct_filesystem()->is_dir( $cache_busting_path ) ) {
		rocket_mkdir_p( $cache_busting_path );
	}

	rocket_put_content( $cache_sccss_filepath, $content );
}

