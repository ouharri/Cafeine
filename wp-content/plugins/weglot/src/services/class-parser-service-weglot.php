<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use WeglotWP\Helpers\Helper_API;
use Weglot\Client\Client;
use Weglot\Parser\Parser;
use Weglot\Parser\ConfigProvider\ServerConfigProvider;
use Weglot\Parser\ConfigProvider\ConfigProviderInterface;


/**
 * Parser abstraction
 *
 * @since 2.0
 */
class Parser_Service_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Regex_Checkers_Service_Weglot
	 */
	private $regex_checkers_services;
	/**
	 * @var Dom_Checkers_Service_Weglot
	 */
	private $dom_checkers_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services         = weglot_get_service( 'Option_Service_Weglot' );
		$this->dom_checkers_services   = weglot_get_service( 'Dom_Checkers_Service_Weglot' );
		$this->regex_checkers_services = weglot_get_service( 'Regex_Checkers_Service_Weglot' );
	}

	/**
	 * @return Client
	 * @throws Exception
	 * @since 3.0.0
	 */
	public function get_client() {
		$api_key            = $this->option_services->get_api_key( true );
		$version            = $this->option_services->get_version();
		$translation_engine = $this->option_services->get_translation_engine();
		if ( ! $translation_engine || empty( $translation_engine ) ) {
			$translation_engine = 2;
		}

		$client = new Client(
			$api_key,
			$translation_engine,
			$version,
			array(
				'host' => Helper_API::get_api_url(),
			)
		);
		$client->getHttpClient()->addHeader( 'weglot-integration: WordPress Plugin' );

		return $client;
	}

	/**
	 * @return Parser
	 * @throws Exception
	 * @since 2.0
	 * @version 2.2.2
	 */
	public function get_parser() {

		$exclude_blocks   = $this->option_services->get_exclude_blocks();
		$whitelist_blocks = apply_filters(
			'weglot_parser_whitelist',
			array()
		);
		$custom_switchers = $this->option_services->get_switchers_editor_button();
		$config           = apply_filters( 'weglot_parser_config_provider', new ServerConfigProvider() );
		if ( ! ( $config instanceof ConfigProviderInterface ) ) {
			$config = new ServerConfigProvider();
		}

		if ( method_exists( $config, 'loadFromServer' ) ) {
			$config->loadFromServer();
		}

		$client = $this->get_client();
		$parser = new Parser( $client, $config, $exclude_blocks, $custom_switchers, $whitelist_blocks );

		$parser->getDomCheckerProvider()->addCheckers( $this->dom_checkers_services->get_dom_checkers() );
		$parser->getRegexCheckerProvider()->addCheckers( $this->regex_checkers_services->get_regex_checkers() );
		$ignored_nodes = apply_filters( 'weglot_get_parser_ignored_nodes', $parser->getIgnoredNodesFormatter()->getIgnoredNodes() );
		$parser->getIgnoredNodesFormatter()->setIgnoredNodes( $ignored_nodes );

		return $parser;
	}
}
