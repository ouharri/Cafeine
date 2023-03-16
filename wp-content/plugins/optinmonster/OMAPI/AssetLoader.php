<?php
/**
 * Asset Loader class.
 *
 * @since 2.0.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A special asset loader built specifically to enqueue
 * JS and CSS built by create-react-app
 *
 * @author Justin Sternberg <jsternberg@awesomemotive.com>
 */
class OMAPI_AssetLoader {

	/**
	 * The directory in which the assets can be found
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $directory;

	/**
	 * The asset file to load.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $manifestFile = 'manifest.json';

	/**
	 * The array of script handles.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $handles = array(
		'js'  => array(),
		'css' => array(),
	);

	/**
	 * The class constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory    The base directory to use.
	 * @param string $manifestFile The manifest file to use.
	 */
	public function __construct( $directory, $manifestFile = 'manifest.json' ) {
		$this->directory    = $directory;
		$this->manifestFile = $manifestFile;
	}

	/**
	 * Uses the built-in WP asset enqueuing to add the manifest assets.
	 *
	 * @since 2.0.0
	 *
	 * @param array $options Enqueue options.
	 * @return void
	 * @throws Exception If webpack assets not found.
	 */
	public function enqueue( array $options = array() ) {

		$defaults = array(
			'base_path' => $this->directory,
			'base_url'  => $this->directory,
			'version'   => time(),
		);

		$options = wp_parse_args( $options, $defaults );
		$assets  = $this->getAssetsList( $options['base_path'] );

		if ( empty( $assets ) ) {
			throw new \Exception( 'No webpack assets found.' );
		}

		foreach ( $assets as $assetPath ) {
			$isJs  = preg_match( '/\.js$/', $assetPath );
			$isCss = ! $isJs && preg_match( '/\.css$/', $assetPath );

			// Ignore source maps and images.
			if ( ! $isCss && ! $isJs ) {
				continue;
			}

			$handle = basename( $assetPath );
			$handle = 0 !== strpos( $handle, 'wp-om-' ) ? 'wp-om-' . $handle : $handle;
			$uri    = $this->getAssetUri( trim( $assetPath, '/' ), $options['base_url'] );

			if ( $isJs || $isCss ) {

				$should_enqueue = apply_filters( 'optin_monster_should_enqueue_asset', true, $handle, $uri, $isJs, $this );

				if ( ! $should_enqueue ) {
					continue;
				}

				if ( $isJs ) {
					$this->handles['js'][] = $handle;
					wp_enqueue_script( $handle, $uri, array(), $options['version'], true );
				} else {
					$this->handles['css'][] = $handle;
					wp_enqueue_style( $handle, $uri, array(), $options['version'] );
				}
			}
		}
	}

	/**
	 * Localize data for the enqueued script(s).
	 *
	 * @since  2.0.0
	 *
	 * @param  array $args Array of data to send to JS.
	 *
	 * @return OMAPI_AssetLoader
	 */
	public function localize( $args ) {
		foreach ( $this->handles['js'] as $handle ) {
			OMAPI_Utils::add_inline_script( $handle, 'omWpApi', $args );

			if ( isset( $args['omStaticDataKey'] ) ) {
				OMAPI_Utils::add_inline_script( $handle, 'omStaticDataKey', $args['omStaticDataKey'] );
			}
			// We only need to output once.
			break;
		}

		return $this;
	}

	/**
	 * Attempts to load the asset manifest.
	 *
	 * @since 2.0.0
	 *
	 * @param string $assetPath The asset path.
	 * @return null|Array
	 */
	protected function loadAssetFile( $assetPath ) {
		static $resources = array();

		if ( isset( $resources[ $assetPath ] ) ) {
			return $resources[ $assetPath ];
		}

		$url      = filter_var( $assetPath, FILTER_VALIDATE_URL );
		$contents = '';

		if ( $url ) {
			$results  = wp_remote_get(
				$assetPath,
				array(
					'sslverify' => false,
				)
			);
			$contents = wp_remote_retrieve_body( $results );
		} elseif ( file_exists( $assetPath ) ) {
			ob_start();
			include_once $assetPath;
			$contents = ob_get_clean();
		}

		if ( empty( $contents ) ) {
			return null;
		}

		$resources[ $assetPath ] = json_decode( $contents, true );

		return $resources[ $assetPath ];
	}

	/**
	 * Finds the asset manifest in the given directory.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $directory The directory to append to the manifest file.
	 * @return array              The assets themselves or an array of parsed assets.
	 */
	public function getAssetsList( $directory ) {
		$directory = trailingslashit( $directory );

		$assets = $this->loadAssetFile( $directory . $this->manifestFile );

		return ! empty( $assets ) ? array_values( $assets ) : array();
	}

	/**
	 * Infer a base web URL for a file system path.
	 *
	 * @since 2.0.0
	 *
	 * @param string $path Filesystem path for which to return a URL.
	 * @return string|null
	 */
	protected function inferBaseUrl( $path ) {

		if ( strpos( $path, get_stylesheet_directory() ) === 0 ) {
			return get_theme_file_uri( substr( $path, strlen( get_stylesheet_directory() ) ) );
		}
		if ( strpos( $path, get_template_directory() ) === 0 ) {
			return get_theme_file_uri( substr( $path, strlen( get_template_directory() ) ) );
		}
		// Any path not known to exist within a theme is treated as a plugin path.
		$pluginPath = plugin_dir_path( __FILE__ );
		if ( strpos( $path, $pluginPath ) === 0 ) {
			return plugin_dir_url( __FILE__ ) . substr( $path, strlen( $pluginPath ) );
		}
		return '';

	}

	/**
	 * Return web URIs or convert relative filesystem paths to absolute paths.
	 *
	 * @since 2.0.0
	 *
	 * @param string $assetPath A relative filesystem path or full resource URI.
	 * @param string $baseUrl   A base URL to prepend to relative bundle URIs.
	 * @return string
	 */
	public function getAssetUri( $assetPath, $baseUrl ) {

		if ( strpos( $assetPath, '://' ) !== false ) {
			return $assetPath;
		}

		return trailingslashit( $baseUrl ) . $assetPath;
	}
}
