<?php
namespace SktThemes;

if ( ! class_exists( '\SktThemes\PageTemplatesDirectory' ) ) {

	class PageTemplatesDirectory {

		/**
		 * @var PageTemplatesDirectory
		 */

		protected static $instance = null;

		/**
		 * The version of this library
		 * @var string
		 */
		public static $version = '1.0.0';

		/**
		 * Holds the module slug.
		 *
		 * @since   1.0.0
		 * @access  protected
		 * @var     string $slug The module slug.
		 */
		protected $slug = 'templates-directory';

		protected $source_url;

		/**
		 * Defines the library behaviour
		 */
		protected function init() {
			add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
			add_action( 'rest_api_init', array( $this, 'register_endpoints_gutenberg' ) );
			
			//Add dashboard menu page.
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 100 );
			//Add rewrite endpoint.
			add_action( 'init', array( $this, 'demo_listing_register' ) );
			//Add template redirect.
			add_action( 'template_redirect', array( $this, 'demo_listing' ) );
			//Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_template_dir_scripts' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'gutenberg_enqueue_template_dir_scripts' ) );
			
			// Get the full-width pages feature
			add_action( 'init', array( $this, 'load_full_width_page_templates' ), 11 );
			// Remove the blank template from the page template selector
			// Filter to add fetched.
			add_filter( 'template_directory_templates_list', array( $this, 'filter_templates' ), 99 );
			
			add_filter( 'gutenberg_template_directory_templates_list', array( $this, 'gutenberg_filter_templates' ), 99 );
		}

		/**
		 * Enqueue the scripts for the dashboard page of the
		 */
		public function enqueue_template_dir_scripts() {
			$templatepage = isset($_REQUEST['page']) ? $_REQUEST['page'] :'';
			if ( $templatepage === 'skt_template_directory') {
				if ( $templatepage === 'skt_template_directory') {
					$plugin_slug = 'sktb';
				}  
				$script_handle = $this->slug . '-script';
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'updates' );
				wp_register_script( $script_handle, plugin_dir_url( $this->get_dir() ) . $this->slug . '/js/script.js', array( 'jquery' ), $this::$version );
				wp_localize_script( $script_handle, 'importer_endpoint',
					array(
						'url'                 => $this->get_endpoint_url( '/import_elementor' ),
						'plugin_slug'         => $plugin_slug,
						'fetch_templates_url' => $this->get_endpoint_url( '/fetch_templates' ),
						'nonce'               => wp_create_nonce( 'wp_rest' ),
					) );
				wp_enqueue_script( $script_handle );
				wp_enqueue_style( $this->slug . '-style', plugin_dir_url( $this->get_dir() ) . $this->slug . '/css/admin.css', array(), $this::$version );
			}
		}
		
		public function gutenberg_enqueue_template_dir_scripts() {
			$templatepage = isset($_REQUEST['page']) ? $_REQUEST['page'] :'';
			if ( $templatepage === 'skt_template_gutenberg') {
				if ( $templatepage === 'skt_template_gutenberg') {
					$plugin_slug = 'sktb';
				}  
				$script_handle = $this->slug . '-script';
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'updates' );
				wp_register_script( $script_handle, plugin_dir_url( $this->get_dir() ) . $this->slug . '/js/script-gutenberg.js', array( 'jquery' ), $this::$version );
				wp_localize_script( $script_handle, 'importer_gutenberg_endpoint',
					array(
						'url'                 => $this->get_endpoint_url( '/import_gutenberg' ),
						'plugin_slug'         => $plugin_slug,
						'fetch_templates_url' => $this->get_endpoint_url( '/fetch_templates' ),
						'nonce'               => wp_create_nonce( 'wp_rest' ),
					) );
				wp_enqueue_script( $script_handle );
				wp_enqueue_style( $this->slug . '-style', plugin_dir_url( $this->get_dir() ) . $this->slug . '/css/admin.css', array(), $this::$version );
			}
		}		

		/**
		 *
		 *
		 * @param string $path
		 *
		 * @return string
		 */
		public function get_endpoint_url( $path = '' ) {
			return rest_url( $this->slug . $path );
		}

		/**
		 * Register Rest endpoint for requests.
		 */
		public function register_endpoints() {
			register_rest_route( $this->slug, '/import_elementor', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'import_elementor' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );
			register_rest_route( $this->slug, '/fetch_templates', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'fetch_templates' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );
		}
		
		
		public function register_endpoints_gutenberg() {
			register_rest_route( $this->slug, '/import_gutenberg', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'import_gutenberg' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );
			register_rest_route( $this->slug, '/fetch_templates', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'fetch_templates' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );
		}		

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function fetch_templates( \WP_REST_Request $request ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			$params = $request->get_params();
		}

		public function filter_templates( $templates ) {
			$current_screen = get_current_screen();
			if ( $current_screen->id === 'skt-templates_page_skt_template_directory' ) {
				$fetched = get_option( 'sktb_synced_templates' );
			} else {
				$fetched = get_option( 'sizzify_synced_templates' );
			}
			if ( empty( $fetched ) ) {
				return $templates;
			}
			if ( ! is_array( $fetched ) ) {
				return $templates;
			}
			$new_templates = array_merge( $templates, $fetched['templates'] );

			return $new_templates;
		}
		
		
		public function gutenberg_filter_templates( $templates ) {
			$current_screen = get_current_screen();
			if ( $current_screen->id === 'skt-templates_page_skt_template_gutenberg' ) {
				$fetched = get_option( 'sktb_synced_templates' );
			} else {
				$fetched = get_option( 'sizzify_synced_templates' );
			}
			if ( empty( $fetched ) ) {
				return $templates;
			}
			if ( ! is_array( $fetched ) ) {
				return $templates;
			}
			$new_templates = array_merge( $templates, $fetched['templates'] );

			return $new_templates;
		}		
		
		
		public function gutenberg_templates_list() {
			$defaults_if_empty = array(
				'title'            => __( 'A new SKT Templates', 'skt-templates' ),
				'description'      => __( 'Awesome SKT Templates', 'skt-templates' ),
				'import_file'      => '',
				'required_plugins' => array( 'skt-blocks' => array( 'title' => __( 'SKT Blocks – Gutenberg based Page Builder', 'skt-templates' ) ) ),
			);
			
			$gutenberg_templates_list = array(
			'sktsalon-gutenberg'              => array(
					'title'       => __( 'GB SKT Salon', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/barber-shop-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-salon/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-salon/skt-salon.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-salon/skt-salon.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, hairdressers, beauty salons, massage parlours, day spas, medical business, beauty centre, beauty treatment, barber, beauty center, beauty salon, hair salon, haircut, hairdresser, makeup, manicure, massage, salon, shop, spa, wellness, salon, Salon, GB SKT Salon' ),
			),			
			'sktayurveda-gutenberg'              => array(
					'title'       => __( 'GB SKT Ayurveda', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/ayurvedic-medicine-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-ayurveda/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-ayurveda/skt-ayurveda.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-ayurveda/skt-ayurveda.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, ayurveda, beauty, coaching, Unani, fitness, gym, health, meditation, mind, pilates, spa, sport, timetable, wellness, yoga, yoga studio, alternative medicine center, wellness, spa salon, body treatment clinic, healing, massage, herbalism, herbal, rasayanas and homoeopathy, GB SKT Ayurveda' ),
			),
			'sktinsurance-gutenberg'              => array(
					'title'       => __( 'GB SKT Insurance', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/insurance-agency-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-insurance/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-insurance/skt-insurance.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-insurance/skt-insurance.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, small business house, business, car insurance, corporate websites, finance, health insurance, insurance, insurance agency, insurance company, insurance theme, life insurance, insurance broker, investment, GB SKT Insurance' ),
			),
			'sktskincare-gutenberg'              => array(
					'title'       => __( 'GB SKT Skin Care', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skin-clinic-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-skincare/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-skincare/skt-skin-care.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-skincare/skt-skin-care.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, beauty parlour, day spa, health care, make up, massage, nail, physiotherapy, salon, skincare, wellness, barber, barber shop, beauty, beauty center, beauty salon, hair salon, hairdresser, hairstylist, makeup, makeup artist, tattoo, handmade soap, healthcare products, organic shop, wellness, therapy, treatment, GB SKT Skin Care' ),
			),			
			'sktsandwich-gutenberg'              => array(
					'title'       => __( 'GB SKT Sandwich', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/fast-food-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-sandwich/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-sandwich/skt-sandwich.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-sandwich/skt-sandwich.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, bakery, burgers, cheeseburger, fast food, meal, menu, pizza, restaurant, salad, sandwich, bar, bistro, cafeteria, food, food shop, online food, Online Pizza, pizza builder, pizza restaurant, pizza shop, pizzeria, beverages, GB SKT Sandwich' ),
			),			
			'sktplants-gutenberg'              => array(
					'title'       => __( 'GB SKT Plants', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/plant-store-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-plants/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-plants/skt-plants.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-plants/skt-plants.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, garden shop, gardener, gardener shop, gardening, gardening website, landscape gardener, nursery shop, plant flowers, plant nursery, plant shop, plants, plants store, plants theme, plant, garden, farm, flower, garden plants, houseplants, GB SKT Plants' ),
			),				
			'sktdoctor-gutenberg'              => array(
					'title'       => __( 'GB SKT Doctor', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/medical-clinic-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-doctor/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-doctor/skt-doctor.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-doctor/skt-doctor.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, hospital, clinic, healthcare, pharmacy, medical store, science, lab, drug, medication, veterinary, maternity, healer, general physician, therapist, orthopaedic, eye clinic, pathology, dermatologist, gynecologist, ambulance, physiotherapy, health consultant, pediatrician, psychologist, dentist, nurse, weight loss, health care centre, GB SKT Doctor, doctor, surgery, surgen' ),
			),	
			'gbrenovate-gutenberg'              => array(
					'title'       => __( 'GB Renovate', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/home-improvement-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/gb-renovate'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/gb-renovate/gb-renovate.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gb-renovate/gb-renovate.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, renovate, interior designs, designs, kitchen appliances, Whole Home Makeovers, Crowdsourcing Platform, Furniture Re-Upholsterer, E-decorating Service, Home Window Dresser, Resale Sites, Home Accessories Decorator, Designer Rooms, Home Decor Services, Makers And Manufacturers, home decor, interior construction, home decorating, decoration, decor, furnishing articles, interior equipment, internal design, interior set-up, interior fit-out, remodeling, overhaul, improvement, reconstruction, betterment, modernization, redo, new look, refashion, redecoration, repair, revamp, restore, rehabilitation, retreading, refitting, renovation, retouch, GB Renovate' ),
			),				
			'gbextreme-gutenberg'              => array(
					'title'       => __( 'GB Extreme', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/adventure-tours-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/gb-extreme/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/gb-extreme/gb-extreme.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gb-extreme/gb-extreme.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, adventure, mountain, biking, hiking, extreme sports, tours, travel, exploit, escapade, event, stunt, quest, happening, trip, venture, GB Extreme' ),
			),			
			'gbbarter-gutenberg'              => array(
					'title'       => __( 'GB Barter', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-shopping-ecommerce-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/gb-barter/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/barter/barter.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gb-barter/gb-barter.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, barter, eCommerce, WooCommerce, shop, shopping, sales, selling, online store, digital payment, PayPal, storefront, b2b, b2c, GB Barter' ),
				),
				'gbposterity-gutenberg'              => array(
					'title'       => __( 'GB Posterity', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-creative-agency-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/posterity/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/posterity/posterity.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gb-posterity/gb-posterity.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, posterity, multipurpose, pet, dogs, chocolate, food, recipe, corporate, construction, real estate, charity, trust, car, automobile, hair, industry, factory, consulting, office, accounting, computers, cafe, fitness, gym, architect, interior, GB Posterity, posterity' ),
				),	
				'gbposteritydark-gutenberg'              => array(
					'title'       => __( 'GB Posterity Dark', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-creative-agency-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/posterity-dark/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/posterity-dark/posterity-dark.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gb-posterity-dark/gb-posterity-dark.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, posterity, posteriydark, dark, multipurpose, pet, dogs, chocolate, food, recipe, corporate, construction, real estate, charity, trust, car, automobile, hair, industry, factory, consulting, office, accounting, computers, cafe, fitness, gym, architect, interior, posteriy dark, GB Posterity Dark' ),
				),		
				'gbnature-gutenberg'              => array(
					'title'       => __( 'GB Nature', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/gutenberg-wordpress-theme/'),
					'demo_url'    => esc_url('https://sktperfectdemo.com/themepack/gbnature/'),
					'screenshot'  => esc_url('https://www.themes21.net/themedemos/gbnature/free-gbnature.jpg'),
					'import_file' => esc_url('https://www.themes21.net/themedemos/gbnature/gb-nature.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, atmosphere, environmental, climate, nature, world, ecology, science, surrounding, natural world, surround, locality, neighborhood, psychology, scenery, sphere, scene, nature, spot, mother nature, wildlife, ecosystem, work, area, place, god gift, globe, environmental organizations, non profit, NGO, charity, donations, clean, fresh, good looking, greenery, green color, house, landscape, creation, flora, locus, air, planet, healing, circumambience, GB Nature' ),
				),
				'gbhotel-gutenberg'              => array(
					'title'       => __( 'GB Hotel', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/gutenberg-wordpress-theme/'),
					'demo_url'    => esc_url('https://sktperfectdemo.com/themepack/gbhotel/'),
					'screenshot'  => esc_url('https://www.themes21.net/themedemos/gbhotel/gb-hotel.jpg'),
					'import_file' => esc_url('https://www.themes21.net/themedemos/gbhotel/gb-hotel.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, Motels, accommodation, Motel accommodation, Hostels, backpackers , Apartments, Bed & Breakfasts, Holiday Homes, Homestays, Holiday Parks, Campgrounds, Farmstays, Luxury Lodges, Boutiques, Lodges, houses, pavilions, stays, gatehouse, hall, club, reside, rent rooms, inhabits, cottage, retreat, main building, clubhouse, hostelry, stays, lodging, pubs, traveler, service, hospices, room, hoteles, guests, facilities, hotel staff, location, hospitality, hotel management, catering, hostelries, roadhouses, bars, resort, canal, innkeeper, hotel accommodation, reservations, hotel business, place, in hotels, settlements, schools, establishments, institutions, properties, farmhouses, GB Hotel' ),
				),
				'gbcharity-gutenberg'              => array(
					'title'       => __( 'GB Charity', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/gutenberg-wordpress-theme/'),
					'demo_url'    => esc_url('https://sktperfectdemo.com/themepack/gbcharity/'),
					'screenshot'  => esc_url('https://www.themes21.net/themedemos/gbcharity/gb-charity.jpg'),
					'import_file' => esc_url('https://www.themes21.net/themedemos/gbcharity/gb-charity.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, kindness, kindliness, compassion, feeling, goodwill, generosity, gentleness, charitableness, tolerance, mercy, humanitarianism, understanding, kindliness, liberality,nurture, relief, generosity, help, leniency, allowance, kindliness, favor, selflessness, unselfishness, love, kindheartedness, support, tenderness, goodness, donation, charitable foundation, offering, indulgence, kindliness, fund, assistance, benefaction, contribution, generosity, brotherly love, caring, clemency, concern, pity, sympathy, benignity, empathy, welfare, charities, gift, aid, help, grace, GB Charity, charity' ),
				),
				'gbfitness-gutenberg'              => array(
					'title'       => __( 'GB Fitness', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/gutenberg-wordpress-theme/'),
					'demo_url'    => esc_url('https://sktperfectdemo.com/themepack/gbfitness/'),
					'screenshot'  => esc_url('https://www.themes21.net/themedemos/gbfitness/gb-fitness.jpg'),
					'import_file' => esc_url('https://www.themes21.net/themedemos/gbfitness/gb-fitness.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, health, fitness, coach, well-being, good physical condition, healthiness, fitness, physical fitness, haleness, good trim, good shape, fine fettle, good kilter, robustness, strength, vigour, soundness, discipline, yoga, meditation, reiki, healing, weight loss, pilates, stretching, relaxation, workout, mental, gymnasium, theater, action, arena, gymnastics, exercise, health club, fitness room, health spa, work out, weight room, working out, sports hall, welfare centre, fitness club, wellness area, workout room, spa, high school, sport club, athletic club, fitness studio, health farm, establishment, gym membership, junior high, sports club, health-care centre, exercise room, training room, fitness suite, health centre, beauty centre, my gym, country club, fite, gym class, medical clinic, med centre, free clinic, medical facilities, dispensary, health posts, healing center, health care facility, medical station, health care establishment, health establishment, medical establishment, centre de santé, medical centres, medical, hospital, polyclinic, healthcare facilities, treatment centre, medical institutions, health care institution, health units, GB Fitness' ),
				),
				'gbconstruction-gutenberg'              => array(
					'title'       => __( 'GB Construction', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/gutenberg-wordpress-theme/'),
					'demo_url'    => esc_url('https://sktperfectdemo.com/themepack/gbconstruction/'),
					'screenshot'  => esc_url('https://www.themes21.net/themedemos/gbconstruction/gb-construction.jpg'),
					'import_file' => esc_url('https://www.themes21.net/themedemos/gbconstruction/gb-construction.json'),
					'keywords'    => __( ' Gutenberg, gutenberg, inventor, originator, founder, maker, mastermind, engineer, builder, planner, designer, patron, originator, initiator, entrepreneur, deviser, author, director, manufacturer, designers, artificer, artist, person, agent, innovator, constructor, architecture, draftsman, planner, designer, progenitor, director, producer, planner, craftsmen, peacemaker, agent, artisan, producer, maker, generator, fabricator, craftsperson, structure, design, organizer, architectural, pioneer, founding father, author, brains, originators, instigators, implementer, contractor, contriver, real estate developer, building contractor, design engineer, property developer, brick layer, land developer, establisher, handyman, maintenance, decor, laborer, land consulting, roofing, artist, portfolio, profile, roofttop, repair, real estate, colorful, adornments, cenery, surroundings, home decor, color scheme, embellishment, garnish, furnishings, interior decorations, interiors, set design, scenography, flourish, design, redecorating, decorative style, ornaments, environments, designs, interior construction, painting, trimming, interior decorating, decoration, emblazonry, home decorating, GB Construction' ),
				),
				);
				
				foreach ( $gutenberg_templates_list as $template => $properties ) {
				$gutenberg_templates_list[ $template ] = wp_parse_args( $properties, $defaults_if_empty );
			}

			return apply_filters( 'gutenberg_template_directory_templates_list', $gutenberg_templates_list );
		}

		/**
		 * The templates list.
		 *
		 * @return array
		 */
		public function templates_list() {
			$defaults_if_empty = array(
				'title'            => __( 'A new SKT Templates', 'skt-templates' ),
				'description'      => __( 'Awesome SKT Templates', 'skt-templates' ),
				'import_file'      => '',
				'required_plugins' => array( 'elementor' => array( 'title' => __( 'Elementor Page Builder', 'skt-templates' ) ) ),
			);
			$templates_list = array(
				'diwali-elementor'              => array(
					'title'       => __( 'Diwali', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/diwali/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/diwali/diwali.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/diwali/diwali.json'),
					'keywords'    => __( ' diwali, Christmas, Halloween, New Year, Thanksgiving, Valentine, Black Friday, Diwali, Festival, Holiday, Celebration, End of season, Shopping festival, Prime day sale, Big Billion Days Sale, Womens Day, Republic day, independence day, Friendship day, Holi, Mothers Day, Dussehra, Childrens Day, Fathers Day', 'skt-templates' ),
				),
				'cybermonday-elementor'              => array(
					'title'       => __( 'Cyber Monday', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/cybermonday/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/cybermonday/cybermonday.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/cybermonday/cybermonday.json'),
					'keywords'    => __( ' cybermonday, Cybermonday, Cyber Monday, cyber monday, Christmas, Halloween, New Year, Thanksgiving, Valentine, Black Friday, Diwali, Festival, Holiday, Celebration, End of season, Shopping festival, Prime day sale, Big Billion Days Sale, Womens Day, Republic day, independence day, Friendship day, Holi, Mothers Day, Dussehra, Childrens Day, Fathers Day', 'skt-templates' ),
				),
				'blackfriday-elementor'              => array(
					'title'       => __( 'Black Friday', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/blackfriday/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/blackfriday/blackfriday.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/blackfriday/blackfriday.json'),
					'keywords'    => __( ' blackfriday, Blackfriday, Black Friday, black friday, Christmas, Halloween, New Year, Thanksgiving, Valentine, Diwali, Festival, Holiday, Celebration, End of season, Shopping festival, Prime day sale, Big Billion Days Sale, Womens Day, Republic day, independence day, Friendship day, Holi, Mothers Day, Dussehra, Childrens Day, Fathers Day', 'skt-templates' ),
				),
				'halloween-elementor'              => array(
					'title'       => __( 'Halloween', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/halloween/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/halloween/halloween.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/halloween/halloween.json'),
					'keywords'    => __( ' Halloween, halloween, Christmas, Halloween, New Year, Thanksgiving, Valentine, Black Friday, Diwali, Festival, Holiday, Celebration, End of season, Shopping festival, Prime day sale, Big Billion Days Sale, Womens Day, Republic day, independence day, Friendship day, Holi, Mothers Day, Dussehra, Childrens Day, Fathers Day', 'skt-templates' ),
				),
				'sktuiux-elementor'              => array(
					'title'       => __( 'SKT UI UX', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-ui-ux/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-ui-ux/skt-ui-ux.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-ui-ux/skt-ui-ux.json'),
					'keywords'    => __( ' UX, UI, UIUX, web design, designers, developers, coding, user experience, user interface, multipurpose, creative agencies, marketing companies, SEO agency, SMO, photoshop, demo, marketing agency, startups, SKT UI UX, ux, ui, uiux', 'skt-templates' ),
				),		
				 'sktresort-elementor'              => array(
					'title'       => __( 'SKT Resort', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-resort/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-resort/skt-resort.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-resort/skt-resort.json'),
					'keywords'    => __( ' holiday, hostel, hotel, luxury, motel, rental, reservation, resort, room, tour, travel, vacation, royal motel, hospitality, SKT Resort', 'skt-templates' ),
				),	
				'sktkarate-elementor'              => array(
					'title'       => __( 'SKT Karate', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-karate/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-karate/skt-karate.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-karate/skt-karate.json'),
					'keywords'    => __( ' sports, coaches, trainers, sportsmen, runner companies, healthy lifestyle, health, fitness store, karate, Kung-Fu, boxing, krav maga, striking styles, standup styles, shoot fighting, Russian sambo, wrestling, grappling styles, SKT Karate', 'skt-templates' ),
				),					
				'sktspecialisthome-elementor'              => array(
					'title'       => __( 'Specialist Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-specialist/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-home.json'),
					'keywords'    => __( ' specialist, corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, information security, computer based, computer security, biosecurity, information technology, insurance, Specialist Home', 'skt-templates' ),
				),
				
				'sktspecialistabout-elementor'              => array(
					'title'       => __( 'Specialist About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-specialist/about-us/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-about.json'),
					'keywords'    => __( ' specialist, corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, information security, computer based, computer security, biosecurity, information technology, insurance, Specialist About, about', 'skt-templates' ),
				),
				
				'sktspecialistservices-elementor'              => array(
					'title'       => __( 'Specialist Services', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-specialist/services/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-services.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-services.json'),
					'keywords'    => __( ' specialist, corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, information security, computer based, computer security, biosecurity, information technology, insurance, Specialist Services, services', 'skt-templates' ),
				),	
				
				'sktspecialistcontact-elementor'              => array(
					'title'       => __( 'Specialist Contact', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-specialist/contact-us/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-specialist/specialist-contact.json'),
					'keywords'    => __( ' specialist, corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, information security, computer based, computer security, biosecurity, information technology, insurance, Specialist Contact, contact', 'skt-templates' ),
				),															

				'sktecology-elementor'              => array(
					'title'       => __( 'SKT Ecology', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-ecology/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-ecology/skt-ecology.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-ecology/skt-ecology.json'),
					'keywords'    => __( ' ecology, eco products, natural, cultivation Website, organic farming, ecosystem service, welfare organizations, forest ecosystem, non profit organizations, NGO, carbon storage, ecological services, renewable energy, eco friendly, solar, recycle, reusable, conservation, agriculture, agro, SKT Ecology', 'skt-templates' ),
				),			
			
				'palmhealinglite-elementor'              => array(
					'title'       => __( 'Palm Healing Lite', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/palm-healing/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/palm-healing/palm-healing.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/palm-healing/palm-healing.json'),
					'keywords'    => __( ' reiki, healing touch, donation, emotional issues, stress relief, radiation, chemotherapy, treatments, palm healing, crown chakra, third eye chakra, throat chakra, heart chakra, solar plexus, sacral chakra, root chakra, exercise, health club, fitness room, health spa, fitness, yoga studio, teaching yoga, acupuncture, ayurveda, yogis, homeopathy, physical exercise, philosophy, body,spiritual discipline, ascetic, Palm Healing Lite, palm', 'skt-templates' ),
				),				
					
				'sktwomanhome-elementor'              => array(
					'title'       => __( 'Woman Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-woman/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-home.json'),
					'keywords'    => __( ' woman, therapists, coaches, speakers, mentors, entrepreneurs, feminine, feminist, feminism, women empowerment, therapists, young woman, entrepreneurs, lifestyle, parenting challenges, relationships, therapy, appointment, individual therapy, training, healing trust, family counseling, Woman Home', 'skt-templates' ),
				),					
				'sktwomanabout-elementor'              => array(
					'title'       => __( 'Woman About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-woman/about-me/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-about.json'),
					'keywords'    => __( ' woman, therapists, coaches, speakers, mentors, entrepreneurs, feminine, feminist, feminism, women empowerment, therapists, young woman, entrepreneurs, lifestyle, parenting challenges, relationships, therapy, appointment, individual therapy, training, healing trust, family counseling, Woman About, about', 'skt-templates' ),
				),					
				'sktwomanservices-elementor'              => array(
					'title'       => __( 'Woman Services', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-woman/services/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-services.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-services.json'),
					'keywords'    => __( ' woman, therapists, coaches, speakers, mentors, entrepreneurs, feminine, feminist, feminism, women empowerment, therapists, young woman, entrepreneurs, lifestyle, parenting challenges, relationships, therapy, appointment, individual therapy, training, healing trust, family counseling, Woman Services, services', 'skt-templates' ),
				),				
				'sktwomancontact-elementor'              => array(
					'title'       => __( 'Woman Contacts', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-woman/contacts/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-woman/skt-woman-contact.json'),
					'keywords'    => __( ' woman, therapists, coaches, speakers, mentors, entrepreneurs, feminine, feminist, feminism, women empowerment, therapists, young woman, entrepreneurs, lifestyle, parenting challenges, relationships, therapy, appointment, individual therapy, training, healing trust, family counseling, Woman Contacts, contacts', 'skt-templates' ),
				),													
				'taxihome-elementor'              => array(
					'title'       => __( 'Taxi Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/taxi/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/taxi/taxi-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/taxi/taxi-home.json'),
					'keywords'    => __( ' taxi, airport pickup, car rental, party bus, vehicle companies, transportation industry, travel agencies, motor rental, ola, uber, taxi, vehicle lease, two wheeler hire, Taxi Home', 'skt-templates' ),
				),	 					
				'taxiabout-elementor'              => array(
					'title'       => __( 'Taxi About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/taxi/about/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/taxi/taxi-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/taxi/taxi-about.json'),
					'keywords'    => __( ' taxi, airport pickup, car rental, party bus, vehicle companies, transportation industry, travel agencies, motor rental, ola, uber, taxi, vehicle lease, two wheeler hire, Taxi About, about', 'skt-templates' ),
				),					
				'taxiservices-elementor'              => array(
					'title'       => __( 'Taxi Services', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/taxi/services/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/taxi/taxi-services.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/taxi/taxi-services.json'),
					'keywords'    => __( ' taxi, airport pickup, car rental, party bus, vehicle companies, transportation industry, travel agencies, motor rental, ola, uber, taxi, vehicle lease, two wheeler hire, Taxi Services, services', 'skt-templates' ),
				),					
				'taxireviews-elementor'              => array(
					'title'       => __( 'Taxi Reviews', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/taxi/reviews/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/taxi/taxi-reviews.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/taxi/taxi-reviews.json'),
					'keywords'    => __( ' taxi, airport pickup, car rental, party bus, vehicle companies, transportation industry, travel agencies, motor rental, ola, uber, taxi, vehicle lease, two wheeler hire, Taxi Reviews, reviews, Reviews', 'skt-templates' ),
				),
				
				'taxicontacts-elementor'              => array(
					'title'       => __( 'Taxi Contact', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/taxi/contacts/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/taxi/taxi-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/taxi/taxi-contact.json'),
					'keywords'    => __( ' taxi, airport pickup, car rental, party bus, vehicle companies, transportation industry, travel agencies, motor rental, ola, uber, taxi, vehicle lease, two wheeler hire, Taxi Contact, contact', 'skt-templates' ),
				),											
				'sktwildlife-elementor'              => array(
					'title'       => __( 'SKT Wildlife', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-wildlife/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-wildlife/skt-wildlife.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-wildlife/skt-wildlife.json'),
					'keywords'    => __( ' wildlife, wild, open shelter, jungle safari, veterinary clinic, zoo, pet shop, aquarium, green landscaping, nature conservation, environment, forest, farm produce, bio produce, animal husbandry, agriculture, Ayurveda, medicines, organic products, NGOs, resorts, travel trip and tourism, photographers, wildlife enthusiasts, animal lovers, SKT Wildlife, Wildlife', 'skt-templates' ),
				),					
				'posterity-elementor'              => array(
					'title'       => __( 'Posterity', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-creative-agency-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/posterity/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/posterity/posterity.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/posterity/posterity.json'),
					'keywords'    => __( ' posterity, multipurpose, pet, dogs, chocolate, food, recipe, corporate, construction, real estate, charity, trust, car, automobile, hair, industry, factory, consulting, office, accounting, computers, cafe, fitness, gym, architect, interior, Posterity' ),
				),														
				'handyman-elementor'              => array(
					'title'       => __( 'Handy Man', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/handy/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/handy/handyman.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/handy/handyman.json'),
					'keywords'    => __( ' help, helper, helpmate, home security system, hot tub, spa, lamp repair, handyman, landscaping, lawncare, lockset adjustment, maid service, molding installation, moving, paint removal, painting, patio stone installation, pest control, plumbing repair, porch, remodeling basement, remodeling bathroom, remodeling kitchen, roofing, safety modification, sealing driveway, senior living modification, septic system repair, shelf installation, shelving, skylight installation, soundproofing, sprinkler repair, sprinkler system installation, stain removal, staining furniture, stone work, storage area construction, storage area repair, swapping a toilet, swimming pool maintenance, tiling, trash removal, wall building, water purification, water softening, window cleaning, welding, window installation, window repair, window screen, duty, work, waste removal, welder, repair, adjustment, improvment, overhaul, reconstruction, rehabilitation, maintenance, welding service, alteration, remaking, resetting, Handy Man, handy', 'skt-templates' ),
				),
				'sktminimal-elementor'              => array(
					'title'       => __( 'Minimal', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/minimal/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/minimal/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/minimal/skt-minimal.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/minimal/skt-minimal.json'),
					'keywords'    => __( ' minimal, minimalistic, eCommerce, store, shop, furniture, chair, ceramic materials, baked clay, equipment, tools, apparatus, utensils, electronic devices, home decor, lighting, gear, makeovers, decorating, decoration, consumer goods, crockery, stoneware, art, earthenware, ceramic, clay ware, ware, ceramic ware, potteries, Minimal', 'skt-templates' ),
				),												
				'weddingcardshome-elementor'              => array(
					'title'       => __( 'Wedding Cards Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/wedding-cards/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/wedding-cards/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-home.json'),
					'keywords'    => __( ' wedding, invitation cards, wedding cards, birthday cards, celebration, solicitation, invite, wedding invitation, wedding planner, bride, groom, marriage, announcement, events, wedding venue, romantic cards, greeting cards, rustic card, floral canopy, customized cards, Wedding Cards Home, Cards, card', 'skt-templates' ),
				),	
				'weddingcardsabout-elementor'              => array(
					'title'       => __( 'Wedding Cards About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/wedding-cards/about/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/wedding-cards/about/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-about.json'),
					'keywords'    => __( ' wedding, invitation cards, wedding cards, birthday cards, celebration, solicitation, invite, wedding invitation, wedding planner, bride, groom, marriage, announcement, events, wedding venue, romantic cards, greeting cards, rustic card, floral canopy, customized cards, Wedding Cards About, about', 'skt-templates' ),
				),	
				'weddingcards-elementor'              => array(
					'title'       => __( 'Wedding Cards', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards.json'),
					'keywords'    => __( ' wedding, invitation cards, wedding cards, birthday cards, celebration, solicitation, invite, wedding invitation, wedding planner, bride, groom, marriage, announcement, events, wedding venue, romantic cards, greeting cards, rustic card, floral canopy, customized cards, Wedding Cards, card, cards', 'skt-templates' ),
				),		
				'weddingcardscontact-elementor'              => array(
					'title'       => __( 'Wedding Cards Contact', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/wedding-cards/contact/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/wedding-cards/contact/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/wedding-cards/wedding-cards-contact.json'),
					'keywords'    => __( ' wedding, invitation cards, wedding cards, birthday cards, celebration, solicitation, invite, wedding invitation, wedding planner, bride, groom, marriage, announcement, events, wedding venue, romantic cards, greeting cards, rustic card, floral canopy, customized cards, Wedding Cards Contact', 'skt-templates' ),
				),											
				'mechanichome-elementor'              => array(
					'title'       => __( 'Mechanic Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/mechanic/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/mechanic/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mechanic/mechanic-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mechanic/mechanic-home.json'),
					'keywords'    => __( ' repair, mechanic, auto repair, services, maintenance, wheels alignment, engine service, autocar, car wash, auto painting, tires shop, auto care, auto mechanic, serviceman, tire dealers, car garage, mechanic workshops, car service, inspection, truck owner, automobile garages, mechanic shop, Mechanic Home', 'skt-templates' ),
				),
				'mechanicabout-elementor'              => array(
					'title'       => __( 'Mechanic About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/mechanic/about/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/mechanic/about/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mechanic/mechanic-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mechanic/mechanic-about.json'),
					'keywords'    => __( ' repair, mechanic, auto repair, services, maintenance, wheels alignment, engine service, autocar, car wash, auto painting, tires shop, auto care, auto mechanic, serviceman, tire dealers, car garage, mechanic workshops, car service, inspection, truck owner, automobile garages, mechanic shop, Mechanic About, about', 'skt-templates' ),
				),
				'mechanicservices-elementor'              => array(
					'title'       => __( 'Mechanic Services', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/mechanic/services/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/mechanic/services/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mechanic/mechanic-services.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mechanic/mechanic-services.json'),
					'keywords'    => __( ' repair, mechanic, auto repair, services, maintenance, wheels alignment, engine service, autocar, car wash, auto painting, tires shop, auto care, auto mechanic, serviceman, tire dealers, car garage, mechanic workshops, car service, inspection, truck owner, automobile garages, mechanic shop, Mechanic Services, services', 'skt-templates' ),
				),
				'mechaniccontact-elementor'              => array(
					'title'       => __( 'Mechanic Contact', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/mechanic/contact/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/mechanic/contact/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mechanic/mechanic-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mechanic/mechanic-contact.json'),
					'keywords'    => __( ' repair, mechanic, auto repair, services, maintenance, wheels alignment, engine service, autocar, car wash, auto painting, tires shop, auto care, auto mechanic, serviceman, tire dealers, car garage, mechanic workshops, car service, inspection, truck owner, automobile garages, mechanic shop, Mechanic Contact, contact', 'skt-templates' ),
				),															
				'extremesportshome-elementor'              => array(
					'title'       => __( 'Extreme Sports Home', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/extreme-sports/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/extreme-sports/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-home.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-home.json'),
					'keywords'    => __( ' sports, adventure, hiking, skiing, mountaineering, river rafting, trekking, fun, surfing, climbing, exciting, adrenalin rush, camping, Extreme Sports Home, extreme', 'skt-templates' ),
				),	
				'extremesportsabout-elementor'              => array(
					'title'       => __( 'Extreme Sports About', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/extreme-sports/about/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/extreme-sports/about/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-about.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-about.json'),
					'keywords'    => __( ' sports, adventure, hiking, skiing, mountaineering, river rafting, trekking, fun, surfing, climbing, exciting, adrenalin rush, camping, Extreme Sports About, extreme, about', 'skt-templates' ),
				),	
				'extremesportsactivities-elementor'              => array(
					'title'       => __( 'Extreme Sports Activities', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/extreme-sports/activities/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/extreme-sports/activities/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-activities.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-activities.json'),
					'keywords'    => __( ' sports, adventure, hiking, skiing, mountaineering, river rafting, trekking, fun, surfing, climbing, exciting, adrenalin rush, camping, Extreme Sports Activities, Extreme, activities, extreme', 'skt-templates' ),
				),	
				'extremesportscontact-elementor'              => array(
					'title'       => __( 'Extreme Sports Contact', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/extreme-sports/contact/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/extreme-sports/contact/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-contact.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/extreme-sports/extreme-sports-contact.json'),
					'keywords'    => __( ' sports, adventure, hiking, skiing, mountaineering, river rafting, trekking, fun, surfing, climbing, exciting, adrenalin rush, camping', 'skt-templates' ),
				),													
				'sktmosque-elementor'              => array(
					'title'       => __( 'SKT Mosque', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://demosktthemes.com/free/mosque/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/mosque/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mosque/skt-mosque.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mosque/skt-mosque.json'),
					'keywords'    => __( ' mosque, masjid, prayers, offerings, donation, charity, NGO, kids learning centre, meditation, nonprofit, fundraising, foster home, caretaker, Humanitarian, food trust, welfare schemes, volunteer programs, relief fund, church, disaster, peace, amnesty, blood donation camps, child education, Extreme Sports Contact, contact, extreme', 'skt-templates' ),
				),		
				'posteritydark-elementor'              => array(
					'title'       => __( 'Posterity Dark', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-creative-agency-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/posterity-dark/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/posterity-dark/posterity-dark.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/posterity-dark/posterity-dark.json'),
					'keywords'    => __( ' posterity, posteriydark, dark, multipurpose, pet, dogs, chocolate, food, recipe, corporate, construction, real estate, charity, trust, car, automobile, hair, industry, factory, consulting, office, accounting, computers, cafe, fitness, gym, architect, interior, Posterity Dark, posterity dark' ),
				),
				'saturnwp-elementor'              => array(
					'title'       => __( 'SaturnWP', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/saturnwp/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/saturnwp/saturnwp.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/saturnwp/saturnwp.json'),
					'keywords'    => __( ' digital-agency, agency, online, digital, consulting, corporate, business, small business, b2b, b2c, financial, investment, portfolio, management, discussion, advice, solicitor, lawyer, attorney, legal, help, SEO, SMO, social, SaturnWP, saturn, wp', 'skt-templates' ),
				),	
				'sktgreen-elementor'              => array(
					'title'       => __( 'SKT Green', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-green-earth-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-green/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/natureone/free-natureone.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/natureone/free-natureone.json'),
					'keywords'    => __( ' nature, green, conservation, solar, eco-friendly, renewable, biofuel electricity, recycle, natural resource, pollution free, water heating, sun, power, geothermal, hydro, wind energy, environment, earth, farm, agriculture, SKT Green', 'skt-templates' ),
				),				
				'comingsoonone-elementor'              => array(
					'title'       => __( 'Coming Soon One', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-one.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-one.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon One, coming, soon, comingsoon', 'skt-templates' ),
				),	
				'comingsoontwo-elementor'              => array(
					'title'       => __( 'Coming Soon Two', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-2/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-two.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-two.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Two, coming, soon, comingsoon', 'skt-templates' ),
				),
				'comingsoonthree-elementor'              => array(
					'title'       => __( 'Coming Soon Three', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-3/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-three.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-three.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Three, coming, soon, comingsoon', 'skt-templates' ),
				),
				'comingsoonfour-elementor'              => array(
					'title'       => __( 'Coming Soon Four', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-4/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-four.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-four.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Four, coming, soon, comingsoon', 'skt-templates' ),
				),	
				'comingsoonfive-elementor'              => array(
					'title'       => __( 'Coming Soon Five', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-5/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-five.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-five.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Five, coming, soon, comingsoon', 'skt-templates' ),
				),	
				'comingsoonsix-elementor'              => array(
					'title'       => __( 'Coming Soon Six', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-6/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-six.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-six.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Six, coming, soon, comingsoon', 'skt-templates' ),
				),	
				'comingsoonseven-elementor'              => array(
					'title'       => __( 'Coming Soon Seven', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-7/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-seven.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-seven.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Seven, seven, coming, soon, comingsoon', 'skt-templates' ),
				),
				'comingsooneight-elementor'              => array(
					'title'       => __( 'Coming Soon Eight', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/coming-soon-wordpress-theme'),					
					'demo_url'    => esc_url('https://sktperfectdemo.com/demos/coming-soon/template-8/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-eight.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/coming-soon/coming-soon-eight.json'),
					'keywords'    => __( ' coming soon, coming soon page, under construction, holding page, maintenance mode, upcoming, arriving, getting ready, countdown, timer, Coming Soon Eight, coming, soon, comingsoon', 'skt-templates' ),
				),																										
				'blendit-elementor'              => array(
					'title'       => __( 'Blendit', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/blendit/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/blendit/blendit.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/blendit/blendit.json'),
					'keywords'    => __( ' blendit, design agency, creative agency, branding company, design studio, development company, marketing agency, marketing services, promotional services, interior design, digital studio, corporate, consultancy, information technology services, IT services, maintenance services, duty, office, group of people, firm, instrument, business, agent, representation, department, office, authority, corporation, government agency, service provider, IT service providers, repairing services, Blendit' ),
				),	
				'nightclub-elementor'              => array(
					'title'       => __( 'Night Club', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/nightclub/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/nightclub/nightclub.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/nightclub/nightclub.json'),
					'keywords'    => __( ' nightclub, parties, events, night clubs, music club, dancing club and night life, parties, music, dance, bands, disco, jockey, Trance and House Event, DJ, Trance Party, Musicians,Disc Jockey,groups, music, video gallery, Concert, cafes, restaurants, cafes, bars, bistros, bakeries, pubs, cafeteria, pizzerias, Night Club, party, nightlife, night life' ),
				),			
				'painting-elementor'              => array(
					'title'       => __( 'Painting', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/painting/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/painting/painting.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/painting/painting.json'),
					'keywords'    => __( ' painting, art galleries, paintings, artwork, artist, exhibitions, white washing, rustproofing, wall papering, residential painting, face paint, startups, manufacturing factory, paint less dent removal, varnishes, lacquers, stains, putties, paint thinners, Graffiti business, spray painters, Graffiti Removal, Workshop, Drywall Contracting Company, wallpaper fixers, Polishing Services , Retailing Store, Interior Decoration, Art Studio, Materials, scraper, Triangular-load, wholesale Distribution company, Painting' ),
				),			
				'finance-elementor'              => array(
					'title'       => __( 'Finance', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/finance/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/finance/finance.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/finance/finance.json'),
					'keywords'    => __( ' finance, fund, funding, subsidize, back, support, financing, funds, money, bankroll, sponsor, underwrite, financial, capital, capitalize, endow, cash, funded, resources, assets, wherewithal, stake, investment, economics, wealth, maintain, guarantee, backing, cover, financed, finances, fiscal, means, promote, business, financial affairs, for financing, financially, patronize, for the financing, grubstake, revenue, for funding, financial resources, budget, help, money matters, term loan, assist, treasury, sponsored, banking, dough, regarding the financing, commerce, terms of financing, pay for, backed, keep, income, securities, provide funding, reserves, sponsoring, capitalized, fiscally, financial matters, provide financing, subsidy, financial means, sponsorship, grant, loan, as regards the financing, covered, financial backing, covering, fundraising, money management, lending, aid, invest, exchequer, sustain, bread, set up, advance, meet, provide, property, funding, internal revenue service, offset, foot the bill, invest in, support financially, nourish, stock, financials, Finance' ),
				),	
				'airconditioner-elementor'              => array(
					'title'       => __( 'Air Conditioner', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/airconditioner/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/airconditioner/airconditioner.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/airconditioner/airconditioner.json'),
					'keywords'    => __( ' airconditioner, heating and cooling services, AC Contractor, Air filtration, ventilating service provider, Air handler business, HVAC repairing center, factory, electronic gadgets, Air Condition, Air Conditioner, cooler, HVAC technician, HVAC Specialist, automobiles, Solar heating technician, Refrigeration Specialist, Equipment specialist, Mechanical Administrator, HVAC products, Solar Thermal, Chimney Repairs, Installation services, HVAC test tools, testing contractor, Air Conditioner, air, ac, AC' ),
				),		
				'therapist-elementor'              => array(
					'title'       => __( 'Therapist', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/therapist/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/therapist/therapist.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/therapist/therapist.json'),
					'keywords'    => __( ' therapist, analyst, healer, psychologist, psychotherapist, therapy, counselor, internist, taking care, medical doctor, psychiatry, doctor of psychology, headshrinker, general practitioner, sychiatric, nursemaid, grief counselor, chiropractor, medicine, medical,life coach, clinician, counselor, psychical, nurse, Therapist, massager, massage' ),
				),		
				'startup-elementor'              => array(
					'title'       => __( 'Startup', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/startup/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/startup/startup.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/startup/startup.json'),
					'keywords'    => __( ' startup, small business, startup business, attractive, appealing, impressive, responsive, visually appealing, white color theme, corporate, businesses, multipurpose, ecommerce, consulting, black and white, white design, lightweighted template, clean, whitespaces, portfolio, profilt, resume, CV, Startup' ),
				),		
				'webake-elementor'              => array(
					'title'       => __( 'We Bake', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/webake/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/webake/webake.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/webake/webake.json'),
					'keywords'    => __( ' webake, cooking, baking, toast, bakers, cookies, roasting, fry, heat, microwave, oven-bake, barbecue, juice, pastry, food, cake, cuisine, snacks, cupcakes, muffins, pastries, pastry, cream, We Bake, bake' ),
				),	
				'medical-elementor'              => array(
					'title'       => __( 'Medical', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/medical/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/medical/medical.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/medical/medical.json'),
					'keywords'    => __( ' medical, health, remedial, doctor, treatment, medicine, medic, healing, medical exam, test, checking, physician, medical examination, medicinal, healthcare, Medical, remedies, surgery, surgen' ),
				),	
				'nuptials-elementor'              => array(
					'title'       => __( 'Nuptials', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/nuptials/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/nuptials/nuptials.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/nuptials/nuptials.json'),
					'keywords'    => __( ' nuptials, marriage, marry, joining, nuptials, match, marital, bridal, rite, wedlock, marital, union, wedlock, marriage ceremony, rite, wedding party, matrimony, bride and groom, coupling, alliance, occasion, meeting, gathering, wedding, bride, groom, invite, family, celebration, sangeet, reception, haldi, act, business, function, marriage, banquet, celebration, parties, entertainment, barbecue, bash, social, reception, engagement, bride and groom, matrimonial, coupling, merging, fun, social, nuptials, marry, anniversary, commitment ceremony, ceremony, wedding receptions, combinations, unions, intermarriage, wedlock, consortium, coupling, holy matrimony, mating, monogamy, pledging, match, spousal, Nuptials' ),
				),		
				'gymmaster-elementor'              => array(
					'title'       => __( 'Gym Master', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/gym-master/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/gym-master/gym-master.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/gym-master/gym-master.json'),
					'keywords'    => __( ' gymmaster, health, fitness, coach, well-being, good physical condition, healthiness, fitness, physical fitness, haleness, good trim, good shape, fine fettle, good kilter, robustness, strength, vigour, soundness, discipline, yoga, meditation, reiki, healing, weight loss, pilates, stretching, relaxation, workout, mental, Gym Master, gym' ),
				),		
				'massagecenter-elementor'              => array(
					'title'       => __( 'Massage Center', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/massage-center/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/massage-center/massage-center.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/massage-center/massage-center.json'),
					'keywords'    => __( ' massagecenter, spa, saloon, salon, meditative practice, yogic, ideology, health, pilates, meditative exercise, yogism, setting-up exercises, relaxation, stretching, stretch, yodel, hot yoga chick, body-bending exercise, yogeeism, exercising, mystery, kundalini, yoga class, yogi, relaxing, yoga classes, eastern discipline, church, philosophical ascetic practice, type of exercise, health system, practice, workout, yoga teachers, physical education, fitness, yoga studio, teaching yoga, acupuncture, ayurveda, yogis, reiki, homeopathy, physical exercise, philosophy, body, exercise, spiritual discipline, ascetic discipline, hinduism, theism, mental exercises, fitness, shape, strength, fun, inspiration, pleasure, refreshment, rubbing, doctor, body massage, eye massage, facial, acupressure, massage therapist, reinstatement, curative, therapeutic, heal, remedial, soothing, medicinal, curing, restorative, sanative, alterative, ointment, get better, recovery, repair, remedy, be cured, cured, get well, overhaul, treat, healed, recover, health-giving, tonic, remedial, assuage, recuperation, recovery, return, salutary, getting well, rehabilitation, pharmaceutical, drug, remedial, calming, reconcile, improvement, organism, health, easing, relieve, treatment, recovery, aid, peace, convalescent, Massage Center' ),
				),		
				'horoscope-elementor'              => array(
					'title'       => __( 'Horoscope', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/horoscope/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/horoscope/horoscope.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/horoscope/horoscope.json'),
					'keywords'    => __( ' horoscope, figure, star sign, zodiac, symbolization, representation, astromancy, astrology, sign, birth chart, fortune cookie, stars, crystal gazing, constellations, prognostication, fortunate, omen, luck, horoscope, zodiac sign, capricorn, sign, horoscope, indicator, estimate, forecast, fortune telling, prediction, foretelling, projection, circumstance, predestination, future predict, fate, fortune, future, fate, spire, turret, bridge, price consultation, consultancy, guess, signal, astromancy, symbolization, birthstone, gem, astrological chart, astrophysicist, astrophysics, accessory, ring, trinket, zodiacal signs, non profit, NGO, charity, Horoscope' ),
				),		
				'kitchendesign-elementor'              => array(
					'title'       => __( 'Kitchen Design', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/kitchen-design/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/kitchen-design/kitchen-design.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/kitchen-design/kitchen-design.json'),
					'keywords'    => __( ' kitchendesign, interior designs, designs, kitchen appliances, Whole Home Makeovers, Crowdsourcing Platform, Furniture Re-Upholsterer, E-decorating Service, Home Window Dresser, Resale Sites, Home Accessories Decorator, Designer Rooms, Eco-Friendly Home Decor Services, Makers And Manufacturers, home decor, interior construction, home decorating, decoration, decor, furnishing articles, interior equipment, internal design, interior set-up, interior fit-out, remodeling, overhaul, improvement, reconstruction, betterment, modernization, redo, new look, refashion, redecoration, repair, revamp, restore, rehabilitation, retreading, refitting, renovation, retouch, Kitchen Design, design' ),
				),	
				'windowsanddoors-elementor'              => array(
					'title'       => __( 'Windows And Doors', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/windowsanddoors/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/windowsanddoors/windowsanddoors.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/windowsanddoors/windowsanddoors.json'),
					'keywords'    => __( ' windowsanddoors, window installations, Doors fixer, handyman, repair services, remodeling, window and door cleaning services, manufacturers, Aluminum Door manufacturing, Repair Business, UPVC Window, Suppliers, home improvement industry, strategic consultancy, local businesses, Sliding Windows installer, Windows And Doors, windows, doors, door' ),
				),																																	
				'sanitization-elementor'              => array(
					'title'       => __( 'Sanitization', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/sanitization/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/sanitization/sanitization.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/sanitization/sanitization.json'),
					'keywords'    => __( ' wash, health, fitness, stress, relief, disinfectant, depersonalization, refining, remediation, clean-up, dry cleaner, purifying, refinement, impersonalizing, clean, cleanse, wipe, sponge, scrub, mop, rinse, scour, swab, hose down, sanitize, sanitization, disinfect, disinfection, cleaning, decontaminate, antiseptic, sanitary, janitor, lean, freshen, purify, deodorize, deodrant, depurate, depollute, hygiene, residue, sterilise, sterilize, napkin, Sanitization', 'skt-templates' ),
				),						
				'pinminimal-elementor'              => array(
					'title'       => __( 'Pin Minimal', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://www.pinnaclethemes.net/themedemos/pin-minimal-free/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/app/minimal.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/app/minimal.json'),
					'keywords'    => __( ' minimal, minimalistic, white, flat, material, simple, clean, natural, Pin Minimal, Minimal', 'skt-templates' ),
				),															
				'cctv-elementor'              => array(
					'title'       => __( 'CCTV', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/cctv/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/cctv/cctv.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/cctv/cctv.json'),
					'keywords'    => __( ' home automation requirements, home protection systems, residential protection, commercial protection, CCTV security systems, individuals security, Security guards, watching crime, CCTV Cameras, Crime Check, safety equipment stores, spy camera espionage, surveillance systems bureaus, bar-code scanner manufacturers, anti theft equipment, biometric system companies, parking management system, video door phone sellers, stun gun, dome cameras, IP camera, Bullet IR Night Vision Camera, Special purpose cameras, vision cameras, Dome IR Night vision cameras, CCTV, camera', 'skt-templates' ),
				),					
				'pvcpipes-elementor'              => array(
					'title'       => __( 'PVC Pipes', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/pvcpipes/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/pvcpipes/pvcpipes.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/pvcpipes/pvcpipes.json'),
					'keywords'    => __( ' fittings, PVC Pipes, maintenance, pipe cutter, office equipment, tap, maintenance, renovation, plumbing, electrician companies, home repair business, remodeling, plumbing firms, renovation, carpentry, construction businesses, building parts, bathroom accessories, plumbing parts, water pipes, showers, tools, kitchen hardware, bath equipment', 'skt-templates' ),
				),					
				'hometheater-elementor'              => array(
					'title'       => __( 'Home Theater', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/hometheatre/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/hometheatre/home-theater.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/hometheatre/home-theater.json'),
					'keywords'    => __( ' hometheater, home theatre, sound equipments, equipment, tools, apparatus, devices, electronic devices, home decor, lighting, gear, devices, mobile phones, Home appliances, gadgets, Makeovers, decorating, decoration, remodel, refashion, mechanism, add-ons, consumer goods, Washing Machine, Bluetooth speakers, Audio Systems, manufacturer, music entertainment business, DSLR camera, electronics products, Home Theater', 'skt-templates' ),
				),					
				'flowershop-elementor'              => array(
					'title'       => __( 'Flowershop', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/flowershop/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/flowershop/flowershop.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/flowershop/flowershop.json'),
					'keywords'    => __( ' flowershop, market, shop, store, flower shop, food shop, snacks shop, bakery store, artificial flower shop, cake shop, grocery store, shopping, foodstuff, goods, groceries, food market, grocer, foodstore, mall, food retailing, supermarket, mart, greengrocery, edibles, emporium, food product, corner shop, storefront, greengrocer, trade, mart, delicatessen, groceteria, comestible, place, department store, superette, tent, convenience store, provision, vegetable store, eatables, provision shop, victuals, boutique, trade, markets, purchase, supermarket, stock market, bazaars, sales, bazaar, sells, exchanges, businesses, convenience store, auctions, trading, deal, bargain, merchandise, commerce, stock exchange, Flowershop', 'skt-templates' ),
				),					
				'municipality-elementor'              => array(
					'title'       => __( 'Municipality', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/municipality/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/municipality/municipality.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/municipality/municipality.json'),
					'keywords'    => __( ' municipality, community, urban community, urban area, foundation, establishment, local government, city government, town government, policy, municipal government, municipal executive, municipal elections, Municipal law, municipal reform, welfare, district, village, city, town, metropolis, burg, province, non profit organization, NGO, governmental organizations, political jurisdictions, community resources, administrative agency, city club, food inspection, transportation, fire departments, Municipality', 'skt-templates' ),
				),																		
				'summercamp-elementor'              => array(
					'title'       => __( 'Summer Camp', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/summercamp/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/summercamp/summer-camp.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/summercamp/summer-camp.json'),
					'keywords'    => __( ' summercamp, traveling, trek, happy movement, expedition, cruise, backpack, visit, trips, tour, vacationing, voyage, roaming, action, go, roll, move, journey, saffari, touring, journey, trip, go abroad, peregrinate, riding, journey, motion, movement, change, taking a trip, stay, holidaying, spring break, vacation, furlough, summer vacation, vacancies, vacation time, tour, travel, vacay, breaks, offseason, vacationing, resort, summer vacation, breakdown, weekends, recesses, rests, package, outings, staying, summer holiday, summer break, summer recess, major holidays, high holidays, big holiday, great holiday, long vacations, break, summering, long vacation, summer recreation, holiday, summer activities, summer enjoyment, summer entertainment, summer fun, summer gaiety, summer joviality, summer joy, summer merriment, summer pleasure, summer relax, summer relaxation, summer rest, summertime, entertainment, summertime fun, summertime joy, summertime pleasure, holiday period, summer leisure activities, summer camps, great festivals, large parties, summertime, major festivals, major feasts, spend the summer, festivals, main festivals, spring, midsummer, estate, Summer Camp', 'skt-templates' ),
				),							
				'association-elementor'              => array(
					'title'       => __( 'Association', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/association/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/association/association.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/association/association.json'),
					'keywords'    => __( ' association, kindness, kindliness, compassion, feeling, goodwill, generosity, gentleness, charitableness, tolerance, mercy, humanitarianism, understanding, kindliness, liberality,nurture, relief, generosity, help, leniency, allowance, kindliness, favor, selflessness, unselfishness, love, kindheartedness, support, tenderness, goodness, donation, charitable foundation, offering, indulgence, kindliness, fund, assistance, benefaction, contribution, generosity, brotherly love, caring, clemency, concern, pity, sympathy, benignity, empathy, welfare, charities, gift, aid, help, grace, Association', 'skt-templates' ),
				),																							
				'aquarium-elementor'              => array(
					'title'       => __( 'Aquarium', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/aquarium/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/aquarium/aquarium.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/aquarium/aquarium.json'),
					'keywords'    => __( ' aquarium shops, decoration fish dealers, aquarium accessories service providers, public aquariums, fishbowl, aquatic museum, marine exhibit, vivarium, Aquarius, fishery, aquarium park, goldfish, aquapark, menagerie, pond, fish pond, dolphinarium, fish tanks, goldfish bowl, seaquarium, seaworld, Aquarium', 'skt-templates' ),
				),									
				'swimmingpool-elementor'              => array(
					'title'       => __( 'Swimming Pool', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/swimming-pool/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/swimming-pool/swimming-pool.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/swimming-pool/swimming-pool.json'),
					'keywords'    => __( ' swimmingpool, waterfront, seaside, seashore, coastal region, seaboard, foreshore, Swimming pools , Wellness, Vacation Rentals, Tour Guide, Welcome Center, Watersports Rentals, Travel, Consultant, massage services, facial treatments, Transportation, VIP Services, Personalized services, Car rental, restaurants and local event, Swimming Pool', 'skt-templates' ),
				),								
				'eventplanners-elementor'              => array(
					'title'       => __( 'Event Planners', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/event-planners/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/event-planners/event-planners.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/event-planners/event-planners.json'),
					'keywords'    => __( ' event planners, act, business, function, marriage, banquet, celebration, parties, entertainment, barbecue, bash, social, reception, engagement, birthday, speaker session, Networking sessions, Conferences, seminar, half-day event, Workshops, classes, VIP experiences, Sponsorships,Trade shows, expos, Awards and competitions, Festivals and parties, event marketing, B2C, B2B marketing, meetups, wordcamps, education and training, Event Planners', 'skt-templates' ),
				),									
				'schooluniform-elementor'              => array(
					'title'       => __( 'School Uniform', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/school-uniform/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/school-uniform/school-uniform.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/school-uniform/school-uniform.json'),
					'keywords'    => __( ' uniform, clothing, fashion, apparel stores, luxurious undergarments, boutique, clothing, garments, dress, attire, wardrobe, outfit, apparel, nightgown, fashion boutique, appearance, looks, boutique, girlie, cloth store, fashion store, feminine, clothes, custom tailoring, alteration, handmade, cloths repair, clothier, fashion, custom wear, uniform, retail, store, wholesaler, shop, fashion industry, clothing repair centers, tailoring service companies, tailor house owner, stylist, fashion designer, model, professional tailor, or an online store manager, cutting approaches, stitching methods, boutique rules, bridal collections, formal dress collections, A-line designers, unstitch fabric, School Uniform', 'skt-templates' ),
				),						
				'tailor-elementor'              => array(
					'title'       => __( 'Tailor', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/tailor/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/tailor/tailor.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/tailor/tailor.json'),
					'keywords'    => __( ' tailor, clothing, fashion, apparel stores, luxurious undergarments, boutique, clothing, garments, dress, attire, wardrobe, outfit, apparel, nightgown, fashion boutique, appearance, looks, boutique, girlie, cloth store, fashion store, feminine, clothes, custom tailoring, alteration, handmade, cloths repair, clothier, fashion, custom wear, uniform, retail, store, wholesaler, shop, fashion industry, clothing repair centers, tailoring service companies, tailor house owner, stylist, fashion designer, model, professional tailor, or an online store manager, cutting approaches, stitching methods, boutique rules, bridal collections, formal dress collections, A-line designers, unstitch fabric, Tailor', 'skt-templates' ),
				),					
				'tatto-elementor'              => array(
					'title'       => __( 'Tattoo', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/tatto/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/tatto/tatto.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/tatto/tatto.json'),
					'keywords'    => __( ' tatto, body art, tattoo making, body art, tattoo lettering, body piercing, art, artist, creativity, tattoo shop, tattoo studio, tattoo parlous, salon, makeup artist, Tattoo', 'skt-templates' ),
				),										
				'mountainbiking-elementor'              => array(
					'title'       => __( 'Mountain Biking', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/mountain-biking/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mountain-biking/mountain-biking.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mountain-biking/mountain-biking.json'),
					'keywords'    => __( ' mountainbiking, traveling, trek, happy movement, expedition, cruise, backpack, visit, trips, tour, vacationing, voyage, roaming, action, go, roll, move, journey, saffari, touring, journey, trip, go abroad, peregrinate, riding, journey, motion, movement, change, taking a trip, stay, holidaying, spring break, vacation, furlough, summer vacation, vacancies, vacation time, tour, travel, vacay, breaks, offseason, vacationing, resort, summer vacation, breakdown, weekends, recesses, rests, package, outings, staying, summer holiday, summer break, summer recess, major holidays, high holidays, big holiday, great holiday, long vacations, break, summering, long vacation, summer recreation, holiday, summer activities, summer enjoyment, summer entertainment, summer fun, summer gaiety, summer joviality, summer joy, summer merriment, summer pleasure, summer relax, summer relaxation, summer rest, summertime, entertainment, summertime fun, summertime joy, summertime pleasure, holiday period, summer leisure activities, summer camps, great festivals, large parties, summertime, major festivals, major feasts, spend the summer, festivals, main festivals, spring, midsummer, estate, Mountain Biking', 'skt-templates' ),
				),								
				'repairman-elementor'              => array(
					'title'       => __( 'Repairman', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/repairman/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/repairman/repairman.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/repairman/repairman.json'),
					'keywords'    => __( ' repairman, window installations, Doors fixer, handyman, repair services, remodeling, window and door cleaning services, manufacturers, Aluminum Door manufacturing, Repair Business, UPVC Window, Suppliers, home improvement industry, strategic consultancy, local businesses, Sliding Windows installer, Repairman', 'skt-templates' ),
				),					
				'lights-elementor'              => array(
					'title'       => __( 'Lights', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/lights/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/lights/lights.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/lights/lights.json'),
					'keywords'    => __( ' lights, lighting company, lighting shop, led lights, led shop, interior accessories, decor items, handmade, ceramics items, chandelier stores, light bulbs retailers, fixtures shops, lamp posts, lighting accessories, designer lamp studio, Lights' ),
				),				
				'moviemaker-elementor'              => array(
					'title'       => __( 'Movie Maker', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/moviemaker/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/moviemaker/moviemaker.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/moviemaker/moviemaker.json'),
					'keywords'    => __( ' moviemaker, film producer, stage director, cinematographer, movie director, moviemaker, head master, headteacher, filmmaking, directing, producer, film, moviegoer, cinema, headmaster, stage performer, stage manager, head, directorial, videographer, artistic director, movies, manager, editor, director, director-level, film-makers, casting director, camera operator, pictures, stock footage, achiever, line producer, implementor, scenario writer, superintendent, film fan, theater director, cameraman, movie fan, moving pictures, camera guy, filmmakers, realizer, photographer, cinematographers, camera man, movie theater, president, cinematography, video, moviemakers, film buff, cinema operator, theatrical producer, film set, executive, ribbons, actor, actress, model, modelling, cast, crew, photographer, makeup, artist, makeup artist, hair styler, Movie Maker, movie' ),
				),				
				'sktvideography-elementor'              => array(
					'title'       => __( 'SKT Videography', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-videographer-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/skt-videography/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/skt-videography/skt-videography.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/skt-videography/skt-videography.json'),
					'keywords'    => __( ' videography, wedding, engagement, nuptials, matrimony, ring, ceremony, ritual, vows, anniversary, celebration, photography, rites, union, big day, knot, aisle, wive, husband, wife, esposo, esposa, hitched, plunged, gatherings, events, video, reels, youtube, film, SKT Videography' ),
				),				
				'bicycleshop-elementor'              => array(
					'title'       => __( 'Bicycleshop', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-cycling-club-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/bicycleshop/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/bicycleshop/bicycleshop.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/bicycleshop/bicycleshop.json'),
					'keywords'    => __( ' bicycleshop, woocommerce, ecommerce, shop, store, sales, shopping, commerce, Bicycleshop, cycle store' ),
				),			
				'barter-elementor'              => array(
					'title'       => __( 'Barter', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-shopping-ecommerce-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/barter/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/barter/barter.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/barter/barter.json'),
					'keywords'    => __( ' barter, eCommerce, WooCommerce, shop, shopping, sales, selling, online store, digital payment, PayPal, storefront, b2b, b2c, Barter' ),
				),
				'software-elementor'              => array(
					'title'       => __( 'Software', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-software-wordpress-theme'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/software/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/software/free-software.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/software/free-software.json'),
					'keywords'    => __( ' software, program, freeware, application, operating system, laptop, computer, courseware, productivity, file management, Software' ),
				),
				'bathware-elementor'              => array(
					'title'       => __( 'Bathware', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/bathware/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/bathware/bathware.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/bathware/bathware.json'),
					'keywords'    => __( ' bathware, bathroom fittings, bathroom stores, bathroom accessories, superior bathroom service providers, fashionable rest room designers, units, basins, tap, faucet, washbasin, baths, showers, tiles, bathroom, building interior design, furniture, shower screens, freestanding, bathroom vanity, marble, home improvement firms, Bathware', 'skt-templates' ),
				),
				'zym-elementor'              => array(
					'title'       => __( 'Zym', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/zym/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/zym/zym.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/zym/zym.json'),
					'keywords'    => __( ' zym, fitness, yoga, gym, crossfit, studio, health, wellness, wellbeing, care, giving, nursing, body, bodybuilding, sports, athletes, boxing, martial, karate, judo, taekwondo, personal trainer, guide, coach, life skills, Zym', 'skt-templates' ),
				),
				'petcare-elementor'              => array(
					'title'       => __( 'Pet Care', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/pet-care/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/pet-care/pet-care.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/pet-care/pet-care.json'),
					'keywords'    => __( ' pet-care, pets, animals, cats, dogs, vets, veterinary, caring, nursing, peta, charity, donation, fundraiser, pet, horse, equestrian, care, orphan, orphanage, clinic, dog walking, dog grooming, boarding, retreat, pet sitters, Pet Care, pet', 'skt-templates' ),
				),
				'bony-elementor'              => array(
					'title'       => __( 'Bony', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/bony/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/bony/bony.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/bony/bony.json'),
					'keywords'    => __( ' bony, orthopaedic, chriropractor, orthodontist, physiotherapy, therapy, clinic, doctor, nurse, nursing, care, caring, osteopathy, arthritis, body, pain, spine, bone, joint, knee, walk, low, back, posture, Bony', 'skt-templates' ),
				),
				'lawzo-elementor'              => array(
					'title'       => __( 'Lawzo', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/lawzo/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/lawzo/lawzo.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/lawzo/lawzo.json'),
					'keywords'    => __( ' lawzo, lawyer, attorney, justice, law, solicitor, general, legal, consultation, advice, help, discussion, corporate, advocate, associate, divorce, civil, lawsuit, barrister, counsel, counsellor, canonist, firm, Lawzo', 'skt-templates' ),
				),
				'launch-elementor'              => array(
					'title'       => __( 'Launch', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/launch/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/launch/launch.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/launch/launch.json'),
					'keywords'    => __( ' launch, folio, leaf sheet, side, recto verso, signature, surface, piece of paper, sheet of paper, flyleaf paper, eBook, book, journal, author, reading, sample, e-book, paperback, hardcover, Launch', 'skt-templates' ),
				),
				'shudh-elementor'              => array(
					'title'       => __( 'Shudh', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/shudh/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/shudh/shudh.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/shudh/shudh.json'),
					'keywords'    => __( ' shudh, minimal, minimalism, minimalistic, clean, tidy, art, slight, tiny, little, limited, small, less, least, nominal, minimum, basal, token, lowest, Shudh', 'skt-templates' ),
				),
				'resume-elementor'              => array(
					'title'       => __( 'Resume', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/resume/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/resume/resume.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/resume/resume.json'),
					'keywords'    => __( ' resume, job, cv, curiculum vitae, online, portfolio, profile, digital, hired, hiring, seeker, candidate, interview, exam, experience, solutions, problems, skills, highlights, life, philosophy, manpower, template, format, word, document, Resume', 'skt-templates' ),
				),
				'fitt-elementor'              => array(
					'title'       => __( 'Fitt', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/fitt/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/fitt/fitt.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/fitt/fitt.json'),
					'keywords'    => __( ' fitt, fitness, yoga, gym, crossfit, studio, health, wellness, wellbeing, care, giving, nursing, body, bodybuilding, sports, athletes, boxing, martial, karate, judo, taekwondo, personal trainer, guide, coach, life skills, Fitt', 'skt-templates' ),
				),
				'theart-elementor'              => array(
					'title'       => __( 'The Art', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/theart/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/theart/theart.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/theart/theart.json'),
					'keywords'    => __( ' theart, Crafts and Handmade Goods, beauty, Advertising, makeover, Graphic Artist, Tattoo Designs, Calligraphy Studio, artist, Art Dealer, Airbrush Artist, Antique, The Art', 'skt-templates' ),
				),
				'photodock-elementor'              => array(
					'title'       => __( 'Photodock', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/photodock/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/photodock/photodock.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/photodock/photodock.json'),
					'keywords'    => __( ' photodock, portfolio, creative, report, document, paper, information, details, essay, sketch, figure, portrait, painting, image, descriptive, study, description, depiction, source, account, biography, draft, picture, registry, book, profile, record, communication, register, mark, post, report, file, mark, certificate, journalism, papers, contract, note, catalog, form, text, instructions, Photodock', 'skt-templates' ),
				),
				'cats-elementor'              => array(
					'title'       => __( 'Cats', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/cats/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/cats/cats.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/cats/cats.json'),
					'keywords'    => __( ' cat, pets, animals, cats, dogs, vets, veterinary, caring, nursing, peta, charity, donation, fundraiser, pet, horse, equestrian, care, orphan, orphanage, clinic, dog walking, dog grooming, boarding, retreat, pet sitters, Cats', 'skt-templates' ),
				),
				'events-elementor'              => array(
					'title'       => __( 'Events', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/events/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/events/events.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/events/events.json'),
					'keywords'    => __( ' events, event, management, celebration, ceremony, appearance, holiday, occasion, situation, affair, function, proceeding, meeting, lunch, dinner, meetup, game, match, tournament, bout, contest, result, aftermath, happening, party, DJ, dance, Events', 'skt-templates' ),
				),
				'beautycuts-elementor'              => array(
					'title'       => __( 'Beauty Cuts', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/beautycuts/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/beautycuts/beautycuts.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/beautycuts/beautycuts.json'),
					'keywords'    => __( ' beautycuts, beautiful, artistry, hair,cut, hairscut, hairstyle, wig, elegance, good looks, grace, refinement, style, bloom, exquisiteness, fairness, fascination, glamor, loveliness, polish, Beauty Cuts', 'skt-templates' ),
				),
				'library-elementor'              => array(
					'title'       => __( 'Library', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/library/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/library/library.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/library/library.json'),
					'keywords'    => __( ' library, librarian, book, room, bibliotheca, reference, media, center, excellence, ebook, elearning, learn, magazine, fiction, album, essay, edition, brochure, copy, booklet, pamphlet, paper, paperback, kindle, writing, write, novel, atlas, manual, textbook, bestseller, encyclopedia, opus, periodical, portfolio, reprint, preprint, thesaurus, scroll, record, diary, notebook, notepad, bill, Library, lab', 'skt-templates' ),
				),
				'tutor-elementor'              => array(
					'title'       => __( 'Tutor', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/tutor/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/tutor/tutor.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/tutor/tutor.json'),
					'keywords'    => __( ' educate, tutor, learning, guide, guidance, coach, help, advice, counselling, counsel, lecturer, instruct, instruction, discipline, disciple, direct, mentor, private, tutorial, professor, preceptor, teach, teaching, student, class, classroom, e learning, ebook, student, Tutor, teacher', 'skt-templates' ),
				),
				'welder-elementor'              => array(
					'title'       => __( 'Welder', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/welder/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/welder/welder.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/welder/welder.json'),
					'keywords'    => __( ' welder, repair, adjustment, improvment, overhaul, reconstruction, rehabilitation, maintenance, welding service, alteration, remaking, resetting, Welder, welding', 'skt-templates' ),
				),
				'legalexpert-elementor'              => array(
					'title'       => __( 'Legal Expert', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/legalexpert/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/legalexpert/legalexpert.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/legalexpert/legalexpert.json'),
					'keywords'    => __( ' lawyer, attorney, justice, law, solicitor, general, legal, consultation, advice, help, discussion, corporate, advocate, associate, divorce, civil, lawsuit, barrister, counsel, counsellor, canonist, firm, Legal Expert, legal', 'skt-templates' ),
				),
				'dairyfarm-elementor'              => array(
					'title'       => __( 'Dairy Farm', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/dairyfarm/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/dairyfarm/dairyfarm.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/dairyfarm/dairyfarm.json'),
					'keywords'    => __( ' bakery, cultivate, raise, grow, farmer, farmhouse, work, agriculture, breeding, cattle, nature, natural, culture, farmland, raising, simple, clean, garden, dairy products, dairy farm, cream, plantation, cheese factory, estate, cowshed, cattle farm, Dairy Farm, Farms, farm, farming', 'skt-templates' ),
				),
				'tea-elementor'              => array(
					'title'       => __( 'Tea', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/tea/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/tea/tea.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/tea/tea.json'),
					'keywords'    => __( ' tea house, cafe, teashop, tearooms, dining, cafeteria, establishment , luncheonette, small restaurant, tea parlor, lunchroom, tea parlour, restaurant, pavilion tea, drink, teatime, brunch, beverage, party, meal, snacks, chocolate, sweet, latte, food, cafe, espresso, decaf, mocha, coffeehouse, diner, burnt umber, joe, brew, caffeine, tawny, cup of coffee, sepia, coffee bean, bean, chestnut, coffee berry, cafés, cappuccino, hazel, cafeteria, deep brown, beverage, beer, coffees, hot, slang, rink, water, high tea, white coffee, coffee shop, coffee bar, restaurant, snack bar, refreshment, relief, stress free, coffee lounge, fresh pot, milk, cafe bar, coffee milk, cheesecake factory, bistro, brasserie, meal, eatery, Tea', 'skt-templates' ),
				),
				'grocery-elementor'              => array(
					'title'       => __( 'Grocery', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/grocery/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/grocery/grocery.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/grocery/grocery.json'),
					'keywords'    => __( ' grocery, kirana, store, ecommerce, woocommerce, online, supermarket, market, groceries, food, shopping, buy, discount, coupons, online, basket, cart, groceries, mall, Grocery', 'skt-templates' ),
				),
				'herbal-elementor'              => array(
					'title'       => __( 'Herbal', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/herbal/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/herbal/herbal.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/herbal/herbal.json'),
					'keywords'    => __( ' nourishment, victuals, nutrient, nutriment, foodstuffs, goodness, beneficial, nurtures, edibles, eatables, vitamins, minerals, food products, chow, feed, grub, food items, mends, nutritional value, nutrimental, wholesomenes, nourishment, diet, groceries, conditioners, nutriments, drugs, solids, agri-foodstuffs, homemade, good stuff, herbs, plant, vegetable, plant-based, herb tea, vegetable, crop, grassy, botanical, floral, herbal medicine, medicinal herbs, grass, verdant, herbaceous, grass up, botanic, medicinal plants, weed, herbage, plant origin, flavorer, herbarium, mossy, grasses, flavourer, garden, vegetative, Phyto therapeutic, fruity, vegetal, planting, medicinal herb, herbal remedies, vegetable origin, flavoring, seasoner, herbal medicinal products, harvest, cultivate, agriculture, agriculture products, Ayurveda, unani, ayurvedic, ayus, acupuncture, homeopathy, naturopathy, yoga, reiki, meditation, chiropractic, allopathic, homeopathic, metaphysical concept, healing method, therapeutic touch, relaxation technique, spiritual healing, medical treatment, artificial insemination, anaesthesia, aromatherapy, Artificial Feeding, acupressure, analgesia, Herbal', 'skt-templates' ),
				),
				'nutristore-elementor'              => array(
					'title'       => __( 'Nutristore', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/nutristore/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/nutristore/nutristore.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/nutristore/nutristore.json'),
					'keywords'    => __( ' nutristore, nourishment, victuals, nutrient, nutriment, foodstuffs, goodness, beneficial, nurtures, edibles, eatables, vitamins, minerals, food products, chow, feed, grub, food items, mends, nutritional value, nutrimental, wholesomenes, fuel, meat, feeding, keep, nourishment, finger food, nosh, comestible, cuisine, diet, groceries, conditioners, nutriments, drugs, solids, agri-foodstuffs, health, fitness, herbal, Nutristore', 'skt-templates' ),
				),
				'shopzee-elementor'              => array(
					'title'       => __( 'Shopzee', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/shopzee/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/shopzee/shopzee.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/shopzee/shopzee.json'),
					'keywords'    => __( ' eye wear, specs, goggles, bifocals, shades, exhibition, vision, opera glasses, online store, handcrafted glasses, sun glasses, online optical business, fashion, fancy frames, dealers, lenskart, online eyeglasses, eyewear manufacturers, Traditional Ecommerce Business, B2C, B2B, C2B, C2C, D2C, Business to consumer, Business to business, Consumer to business, Consumer to consumer, Direct to consumer, Wholesaling, Dropshipping, Subscription service, distributing services, delivery services, shippings services, millennial generation, discount shop, discount store, convenience store, corner store, disposals store, grocery store, retail store, thrift store, store detective, liquor store, app store, jewelry store, shoe store, hobby store, cold store, backing store, store card, multiple store, lays store on, army navy store, gun store, cigar store, e store, music store, convenience store, drug store, general store, variety store, dime store, hobby shop, buffer store, big-box store, second-hand store, building supply store, anchor store, computer store, country store, mens store, army-navy store, bags, handbags, ecommerce, e-commerce, shopping, coupon, Shopzee, shopzee', 'skt-templates' ),
				),
				'beach-elementor'              => array(
					'title'       => __( 'Beach', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/beachresort/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/beachresort/beachresort.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/beachresort/beachresort.json'),
					'keywords'    => __( ' waterfront, seaside, seashore, coastal region, seaboard, foreshore, Swimming pools , Wellness, Vacation Rentals, Tour Guide, Welcome Center, Watersports Rentals, Travel, Consultant, massage services, facial treatments, Transportation, VIP Services, Personalized services, Car rental, restaurants and local event, Beach, beach', 'skt-templates' ),
				),
				'activist-lite-elementor'              => array(
					'title'       => __( 'Activism', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-activism-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/activist/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/activist/free-activist.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/activist/free-activist.json'),
					'keywords'    => __( ' ngo, non profit, citizen, old age, senior living, kids, children, red cross, wwf, social, human rights, activists, donation, fundraiser, donate, help, campaign, activism, Activism', 'skt-templates' ),
				),									
				'fundraiser-elementor'              => array(
					'title'       => __( 'Fundraiser', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/fundraising-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/fundraiser/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/fundraiser/fundraiser.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/fundraiser/fundraiser.json'),
					'keywords'    => __( ' charity, fundraiser, church, donation, donate, fund, trust, association, foundation, cause, aid, welfare, relief, funding, handouts, gifts, presents, largesse, lease, donations, contributions, grants, endowments, ngo, non profit, organization, non-profit, voluntary, humanitarian, humanity, social, generosity, generous, philanthropy, scholarships, subsidies, subsidy, Fundraiser', 'skt-templates' ),
				),																					
				'charityt-elementor'              => array(
					'title'       => __( 'Charity', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-charity/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/charity/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/charity/free-charity.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/charity/free-charity.json'),
					'keywords'    => __( ' charity, fundraiser, church, donation, donate, fund, trust, association, foundation, cause, aid, welfare, relief, funding, handouts, gifts, presents, largesse, lease, donations, contributions, grants, endowments, ngo, non profit, organization, non-profit, voluntary, humanitarian, humanity, social, generosity, generous, philanthropy, scholarships, subsidies, subsidy, Charity', 'skt-templates' ),
				),					
				'mydog-elementor'              => array(
					'title'       => __( 'My Dog', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-pet-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/mydog/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/mydog/free-mydog.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/mydog/free-mydog.json'),
					'keywords'    => __( ' pet, dog, veterinary, animal, husbandry, livestock, aquarium, cat, fish, mammal, bat, horse, equestrian, friend, My Dog', 'skt-templates' ),
				),
				'film-elementor'              => array(
					'title'       => __( 'FilmMaker', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-video-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/film/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/film/free-filmmaker.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/film/free-filmmaker.json'),
					'keywords'    => __( ' wedding, engagement, nuptials, matrimony, ring, ceremony, ritual, vows, anniversary, celebration, videography, photography, rites, union, big day, knot, aisle, wive, husband, wife, esposo, esposa, hitched, plunged, gatherings, events, video, reels, youtube, film, FilmMaker, filmmaker', 'skt-templates' ),
				),
				'martial-arts-lite-elementor'              => array(
					'title'       => __( 'Martial Arts', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-martial-arts-wordpress-theme/'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/martial-arts/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/martial-arts/free-martial-arts.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/martial-arts/free-martial-arts.json'),
					'keywords'    => __( ' kungfu, fitness, sportsman, running, sports, trainer, yoga, meditation, running, crossfit, taekwondo, karate, boxing, kickboxing, yoga, Martial Arts, martialarts', 'skt-templates' ),
				),
				'babysitter-lite-elementor'              => array(
					'title'       => __( 'BabySitter', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-kids-store-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/baby/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/baby/free-babysitter.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/baby/free-babbysitter.json'),
					'keywords'    => __( ' kids, chools, nursery, kids fashion store, kindergarten, daycare, baby care, nursery, nanny, grandma, babysitting, nursing, toddler, BabySitter, babysitter', 'skt-templates' ),
				),
				'winery-lite-elementor'              => array(
					'title'       => __( 'Winery', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-liquor-store-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/winery/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/winery/free-winery.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/winery/free-winery.json'),
					'keywords'    => __( ' wine, champagne, alcohol, beverage, drink, liquor, spirits, booze, cocktail, beer, nectar, honey, brewery, Winery', 'skt-templates' ),
				),
				'industrial-lite-elementor'              => array(
					'title'       => __( 'Industrial', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-industrial-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/industrial/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/industrial/free-industrial.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/industrial/free-industrial.json'),
					'keywords'    => __( ' industry, factory, manufacturing, production, worker, construction, fabrication, welder, smithy, automation, machine, mechanized, mechanic, business, commerce, trade, union, Industrial', 'skt-templates' ),
				),
				'free-coffee-elementor'              => array(
					'title'       => __( 'Coffee', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-coffee/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/cuppa/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/cuppa/free-coffee.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/cuppa/free-coffee.json'),
					'keywords'    => __( ' coffee, caffeine, tea, drink, milk, hot, brewery, cappuccino, espresso, brew, java, mocha, decaf, juice, shakes, Coffee', 'skt-templates' ),
				),
				'cutsnstyle-lite-elementor'              => array(
					'title'       => __( 'CutsnStyle', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/cutsnstyle-lite/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/haircut/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/haircut/free-haircut.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/haircut/free-haircut.json'),
					'keywords'    => __( ' salon, beauty, nails, manicure, pedicure, parlor, spa, hairdresser, barber, soap, glamour, fashion, grace, charm, looks, style, mud bath, oxygen therapy, aromatherapy, facial, foot, skin care, hair coloring, shampoo, razors, grooming, beard, cosmetology, CutsnStyle, hair cut, cutting saloon, saloon', 'skt-templates' ),
				),
				'buther-lite-elementor'              => array(
					'title'       => __( 'Butcher', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-meat-shop-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/butcher/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/butcher/free-butcher.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/butcher/free-butcher.json'),
					'keywords'    => __( ' butcher, meat, steakhouse, boner, mutton, chicken, fish, slaughter, Butcher', 'skt-templates' ),
				),
				'architect-lite-elementor'              => array(
					'title'       => __( 'Architect', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-architect-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/architect/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/architect/free-architect.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/architect/free-architect.json'),
					'keywords'    => __( ' architect, interior, construction, contractor, architecture, draughtsman, planner, builder, consultant, fabricator, creator, maker, engineer, mason, craftsman, erector, Architect', 'skt-templates' ),
				),
				'free-autocar-elementor'              => array(
					'title'       => __( 'Auto Car', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-car-rental-wordpress-theme/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/autocar/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/autocar/free-autocar.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/autocar/free-autocar.json'),
					'keywords'    => __( ' transport, lorry, truck, tow, bus, movers, packers, courier, garage, mechanic, car, automobile, Auto Car', 'skt-templates' ),
				),
				'movers-packers-lite-elementor'              => array(
					'title'       => __( 'Movers and Packers', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/movers-packers-lite/'),					
					'demo_url'    => esc_url('https://demosktthemes.com/free/movers-packers/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/movers-packers/free-movers-packers.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/movers-packers/free-movers-packers.json'),
					'keywords'    => __( ' transport, lorry, truck, tow, bus, movers, packers, courier, garage, mechanic, car, automobile, shifting, Movers and Packers', 'skt-templates' ),
				),
				'modeling-lite-elementor'              => array(
					'title'       => __( 'Modeling', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-lifestyle-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/modelling/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/modelling/free-modelling.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/modelling/free-modelling.json'),
					'keywords'    => __( ' model, fashion, style, glamour, mannequin, manikin, mannikin, manakin, clothing, photography, photograph, instagram, Modeling, modeling', 'skt-templates' ),
				),
				'exceptiona-lite-elementor'              => array(
					'title'       => __( 'Exceptiona', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-accounting-firm-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/exceptiona/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/exceptiona/exceptiona-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/exceptiona/exceptiona-lite.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Exceptiona', 'skt-templates' ),
				),
				'free-parallax-elementor'              => array(
					'title'       => __( 'Parallax Me', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt_parallax_me/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/parallax/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/parallax/free-parallax.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/parallax/free-parallax.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, Parallax Me, me, me time', 'skt-templates' ),
				),
				'free-build-elementor'              => array(
					'title'       => __( 'Build', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-build-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/build/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/build/free-build.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/build/free-build.json'),
					'keywords'    => __( ' construction, contractor, concrete, cement, fabricator, steel, roofing, flooring, industry, factory, manufacturing, production, worker, fabrication, welder, smithy, automation, machine, mechanized, mechanic, business, commerce, trade, union, Build' ),
				),
				'fitness-lite-elementor'              => array(
					'title'       => __( 'Fitness', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/fitness-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/sktfitness/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/sktfitness/free-sktfitness.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/sktfitness/free-sktfitness.json'),
					'keywords'    => __( ' fitness, trainer, gym, crossfit, health, strength, abs, six pack, wellness, meditation, reiki, mental, physical, bodybuilding, kickboxing, sports, running, kungfu, karate, taekwondo, yoga, Fitness, fit' ),
				),
				'restaurant-lite-elementor'              => array(
					'title'       => __( 'Restaurant', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/restaurant-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/restro/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/restro/free-restro.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/restro/free-restro.json'),
					'keywords'    => __( ' restaurant, bistro, eatery, food, joint, street café, café, coffee, burger, fast food, junk food, noodle, chinese, chef, cook, kitchen, cuisine, cooking, baking, bread, cake, chocolate, nourishment, diet, dishes, waiter, eatables, meal, Restaurant, restaurant, restro' ),
				),
				'flat-lite-elementor'              => array(
					'title'       => __( 'Flat', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-landing-page-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/flat/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/flat/free-flat.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/flat/free-flat.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, material design, Flat' ),
				),
				'juice-shakes-lite-elementor'              => array(
					'title'       => __( 'Juice and Shakes', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-smoothie-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/juice/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/juice/free-juice-shakes.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/juice/free-juice-shakes.json'),
					'keywords'    => __( ' coffee, caffeine, tea, drink, milk, hot, brewery, cappuccino, espresso, brew, java, mocha, decaf, juice, shakes, Juice and Shakes, cold, coldrink, cold drink' ),
				),				
				'organic-lite-elementor'              => array(
					'title'       => __( 'Organic', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-farming-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/organic/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/organic/free-organic.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/organic/free-organic.json'),
					'keywords'    => __( ' organic, farm fresh, vegetables, garden, nature, agriculture, agro food, spices, nutrition, herbal, greenery, environment, ecology, green, eco friendly, conservation, natural, gardening, landscaping, horticulture, Organic' ),
				),
				'bistro-lite-elementor'              => array(
					'title'       => __( 'Bistro', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-fast-food-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/bistro/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/bistro/free-bistro.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/bistro/free-bistro.json'),
					'keywords'    => __( ' restaurant, bistro, eatery, food, joint, street café, café, coffee, burger, fast food, junk food, noodle, chinese, chef, cook, kitchen, cuisine, cooking, baking, bread, cake, chocolate, nourishment, diet, dishes, waiter, eatables, meal, Bistro' ),
				),
				'yogi-lite-elementor'              => array(
					'title'       => __( 'Yogi', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/yogi-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/yogi/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/yogi/free-yogi.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/yogi/free-yogi.json'),
					'keywords'    => __( ' fitness, trainer, gym, crossfit, health, strength, abs, six pack, wellness, meditation, reiki, mental, physical, bodybuilding, kickboxing, sports, running, kungfu, karate, taekwondo, yoga, Yogi' ),
				),
				'free-design-agency-elementor'              => array(
					'title'       => __( 'Design Agency', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-design-agency/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/design/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/design/free-design-agency.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/design/free-design-agency.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, Design Agency, design agency' ),
				),
				'construction-lite-elementor'              => array(
					'title'       => __( 'Construction', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/construction-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/construction/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/construction/free-construction.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/construction/free-construction.json'),
					'keywords'    => __( ' construction, contractor, concrete, cement, fabricator, steel, roofing, flooring, industry, factory, manufacturing, production, worker, fabrication, welder, smithy, automation, machine, mechanized, mechanic, business, commerce, trade, union, Construction' ),
				),
				'toothy-lite-elementor'              => array(
					'title'       => __( 'Toothy', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-dentist-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/toothy/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/toothy/free-toothy.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/toothy/free-toothy.json'),
					'keywords'    => __( ' medical, dentist, hospital, ward, nurse, doctor, physician, health, mental, physical, dispensary, physiotheraphy, care, nursing, old age, senior living, dental, cardio, orthopaedic, bones, chiropractor, Toothy, teeth, dental' ),
				),
				'itconsultant-lite-elementor'              => array(
					'title'       => __( 'IT Consultant', 'skt-templates' ),
 					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/consultant-lite/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/it-consultant/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/it-consultant/free-itconsultant.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/it-consultant/free-itconsultant.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, IT Consultant, IT, it, consultant' ),
				),
				'free-onlinecoach-elementor'              => array(
					'title'       => __( 'Online Coach', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-coach-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/online-coach/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/online-coach/free-onlinecoach.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/online-coach/free-onlinecoach.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Online Coach, coach, coaching' ),
				),
				'free-sktpathway-elementor'              => array(
					'title'       => __( 'Pathway', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt_pathway/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/pathway/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/pathway/free-pathway.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/pathway/free-pathway.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Pathway' ),
				),
				'free-sktblack-elementor'              => array(
					'title'       => __( 'Black', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-black/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/black/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/black/free-black.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/black/free-black.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Black, dark' ),
				),
				'free-sktwhite-elementor'              => array(
					'title'       => __( 'White', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/skt-white/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/white/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/white/free-white.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/white/free-white.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, White, white, clean' ),
				),
				'interior-lite-elementor'              => array(
					'title'       => __( 'Interior', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-interior-wordpress-theme/'),	
					'demo_url'    => esc_url('https://demosktthemes.com/free/interior/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/interior/interior-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/interior/interior-lite.json'),
					'keywords'    => __( ' interior design, furnishing, cushions, flooring, roofing, house works, vase, flower, curtains, furniture, wallpaper, renovation, framing, modular, kitchen, wardrobe, cupboard, unit, TV, fridge, washing machine, home appliances, bedroom, sofa, couch, living room, Interior' ),
				),
				'free-simple-elementor'              => array(
					'title'       => __( 'Simple', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-simple-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/simple/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/simple/free-simple.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/simple/free-simple.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Simple, minimal' ),
				),
				'free-condimentum-elementor'              => array(
					'title'       => __( 'Condimentum', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-multipurpose-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/condimentum/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/condimentum/free-condimentum.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/condimentum/free-condimentum.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Condimentum' ),
				),
				'ele-makeup-lite-elementor'              => array(
					'title'       => __( 'Makeup', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-beauty-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/makeup/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/makeup/ele-makeup-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/makeup/ele-makeup-lite.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, attorney, Makeup, artist, makeup artist, makeover, make over' ),
				),
				'ele-attorney-lite-elementor'              => array(
					'title'       => __( 'Attorney', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-law-firm-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/attorney/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/attorney/ele-attorney.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/attorney/ele-attorney.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, attorney, Attorney' ),
				),
				'poultry-farm-lite-elementor'              => array(
					'title'       => __( 'Poultry Farm', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-poultry-farm-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/poultry-farm/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/poultry-farm/free-poultryfarm.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/poultry-farm/free-poultryfarm.json'),
					'keywords'    => __( ' organic, farm fresh, vegetables, garden, nature, agriculture, agro food, spices, nutrition, herbal, greenery, environment, ecology, green, eco friendly, conservation, natural, gardening, landscaping, horticulture, livestock, eggs, chicken, mutton, goat, sheep, Poultry Farm, poultry' ),
				),
				'ele-restaurant-lite-elementor'              => array(
					'title'       => __( 'Restaurant', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-food-blog-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/restaurant/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/restaurant/ele-restaurant-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/restaurant/ele-restaurant-lite.json'),
					'keywords'    => __( ' restaurant, bistro, eatery, food, joint, street café, café, coffee, burger, fast food, junk food, noodle, chinese, chef, cook, kitchen, cuisine, cooking, baking, bread, cake, chocolate, nourishment, diet, dishes, waiter, eatables, meal, Restaurant' ),
				),
				'ele-luxuryhotel-lite-elementor'              => array(
					'title'       => __( 'Luxury Hotel', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-hotel-booking-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/hotel/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/hotel/free-hotel.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/hotel/free-hotel.json'),
					'keywords'    => __( ' hotel, motel, oyo, resort, vacation, family, trip, travel, b&b, holiday, lodge, accommodation, inn, guest house, hostel, boarding, service apartment, auberge, boatel, pension, bed and breakfast, tavern, dump, lodging, hospitality, Luxury Hotel, luxury' ),
				),
				'ele-wedding-lite-elementor'              => array(
					'title'       => __( 'Wedding', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-wedding-planner-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/wedding/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/wedding/ele-wedding-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/wedding/ele-wedding-lite.json'),
					'keywords'    => __( ' wedding, engagement, nuptials, matrimony, ring, ceremony, ritual, vows, anniversary, celebration, videography, photography, rites, union, big day, knot, aisle, wive, husband, wife, esposo, esposa, hitched, plunged, Wedding, marriage, marry' ),
				),
				'ele-fitness-lite-elementor'              => array(
					'title'       => __( 'Fitness', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-workout-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/fitness/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/fitness/ele-fitness.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/fitness/ele-fitness.json'),
					'keywords'    => __( ' fitness, trainer, gym, crossfit, health, strength, abs, six pack, wellness, meditation, reiki, mental, physical, bodybuilding, kickboxing, sports, running, kungfu, karate, taekwondo, yoga, Fitness' ),
				),
				'ele-nature-lite-elementor'              => array(
					'title'       => __( 'Nature', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-green-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/nature/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/nature/ele-nature.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/nature/ele-nature.json'),
					'keywords'    => __( ' atmosphere, environmental, climate, nature, world, ecology, science, surrounding, natural world, surround, locality, neighborhood, psychology, scenery, sphere, scene, nature, spot, mother nature, wildlife, ecosystem, work, area, place, god gift, globe, environmental organizations, non profit, NGO, charity, donations, clean, fresh, good looking, greenery, green color, house, landscape, creation, flora, locus, air, planet, healing, circumambience, Nature, nature' ),
				),
				'ele-ebook-lite-elementor'              => array(
					'title'       => __( 'eBook', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-ebook-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/ebook/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/ebook/ele-book.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/ebook/ele-ebook.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, attorney, eBook, book, book store' ),
				),
				'ele-product-launch-lite-elementor'              => array(
					'title'       => __( 'Product Launch', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-mobile-app-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/app/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/app/ele-app.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/app/ele-app.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Product Launch' ),
				),
				'ele-spa-lite-elementor'              => array(
					'title'       => __( 'Spa', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-beauty-salon-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/spa/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/spa/ele-spa.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/spa/ele-spa.json'),
					'keywords'    => __( ' salon, beauty, nails, manicure, pedicure, parlor, spa, hairdresser, barber, soap, glamour, fashion, grace, charm, looks, style, mud bath, oxygen therapy, aromatherapy, facial, foot, skin care, hair coloring, shampoo, razors, grooming, beard, cosmetology, Spa, massage, body massage' ),
				),
				'ele-store-lite-elementor'              => array(
					'title'       => __( 'Store', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-wordpress-store-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/store/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/store/ele-store.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/store/ele-store.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, store, shop, Store' ),
				),
				'hightech-lite-elementor'              => array(
					'title'       => __( 'High Tech', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-computer-repair-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/hightech/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/hightech/hightech-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/hightech/hightech-lite.json'),
					'keywords'    => __( ' technology, computer, repair, laptop, mobile, phone, digital, online services, help, desktop, mac, windows, apple, iPhone, android, electronic, tablet, maintenance, software, antivirus, IT solutions, training, consulting, High Tech' ),
				),
				'junkremoval-lite-elementor'              => array(
					'title'       => __( 'Junk Removal', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-waste-management-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/junkremoval/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/junkremoval/junk-removal-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/junkremoval/junkremoval-lite.json'),
					'keywords'    => __( ' help, helper, helpmate, home security system, hot tub, spa, lamp repair, landscaping, lawncare, lockset adjustment, maid service, molding installation, moving, paint removal, painting, patio stone installation, pest control, plumbing repair, porch, remodeling basement, remodeling bathroom, remodeling kitchen, roofing, safety modification, sealing driveway, senior living modification, septic system repair, shelf installation, shelving, skylight installation, soundproofing, sprinkler repair, sprinkler system installation, stain removal, staining furniture, stone work, storage area construction, storage area repair, swapping a toilet, swimming pool maintenance, tiling, trash removal, wall building, water purification, water softening, window cleaning, welding, window installation, window repair, window screen, duty, work, waste removal, Junk Removal' ),
				),
				'pets-lite-elementor'              => array(
					'title'       => __( 'Pet', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-animal-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/pets/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/pets/ele-pets.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/pets/ele-pets.json'),
					'keywords'    => __( ' cat, dog, pets, animals, domestic animals, pet animals, caring, stress relief, favorites, pooches, beast, wildlife, fish, kittens, kitties, domesticated animals, cat food, pet animals, cuddling, quadruped, furry friend, cattle, companion animal, dears, darlings, wildcats, pet clinics, pet grooming, vets, veterinarians, Organic Treat Maker, Obedience Expert, Yard Cleaner, In-Home Cleaner, Animal Blogger, Animal Toy Maker, bed/Housing Designer, Tank Designer, Pet Travel Service Provider, , Pet Bandana, Maker Collar Designer, Cat Café Operator, Dog Manicurist, Unique Pet Store Owner, Dog Whisperer, Cat Toilet Trainer, Pet Bakery Owner, Dog Beer Brewer, YouTube Training Expert, Maker or Seller of Breath Mints for Dogs, Custom Pet Portrait Artist, Pet Photographer, Renter of Unusual Animals, Pet Sharing Service Operator, Fur Dying Specialist, Tag Engraver, Pet Clothing Designer, Luxury Boarding Service Operator, At-Home Boarding Service Provider, Dog Treat Truck Owner, Dog Sports Competition Organizer, Doggie Day Care Operator, Pet Pillow Creator, T-Shirt Designer, Dog Workout Coach, Pet Restaurateur, Homemade Pet Food Creator, Large Animal Sitter, Shelter Matching Expert, Pet Costume Designer, Pet Massage Therapists, Home Pet Monitoring Expert, Pet Health Expert, Pet Event Organizer, Pet Spa Operator, Brand Manager, Pet' ),
				),
				'ele-agency-lite-elementor'              => array(
					'title'       => __( 'Agency', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-marketing-agency-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/agency/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/agency/ele-agency.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/agency/ele-agency.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Agency' ),
				),
				'ele-yoga-lite-elementor'              => array(
					'title'       => __( 'Yoga', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-yoga-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/yoga/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/yoga/ele-yoga.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/yoga/ele-yoga.json'),
					'keywords'    => __( ' fitness, trainer, gym, crossfit, health, strength, abs, six pack, wellness, meditation, reiki, mental, physical, bodybuilding, kickboxing, sports, running, kungfu, karate, taekwondo, yoga, Yoga' ),
				),
				'localbusiness-lite-elementor'              => array(
					'title'       => __( 'Local Business', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-simple-business-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/localbusiness/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/localbusiness/localbusiness-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/localbusiness/localbusiness-lite.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, Local Business' ),
				),
				'free-fashion-elementor'              => array(
					'title'       => __( 'Fashion', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-fashion-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/fashion/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/fashion/free-fashion.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/fashion/free-fashion.json'),
					'keywords'    => __( ' corporate, business, consulting, agency, people, meeting, communal, working, workforce, office, accounting, lawyer, coaching, advocate, advice, suggestion, therapy, mental wellness, fashion, model, modelling, Fashion' ),
				),
				'free-chocolate-elementor'              => array(
					'title'       => __( 'Chocolate', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-chocolate-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/chocolate/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/chocolate/free-chocolate.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/chocolate/free-chocolate.json'),
					'keywords'    => __( ' coffee, caffeine, tea, drink, milk, hot, brewery, cappuccino, espresso, brew, java, mocha, decaf, juice, shakes, Chocolate' ),
				),
				'icecream-lite-elementor'              => array(
					'title'       => __( 'IceCream', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-icecream-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/icecream/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/icecream/icecream-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/icecream/icecream-lite.json'),
					'keywords'    => __( ' coffee, caffeine, tea, drink, milk, hot, brewery, cappuccino, espresso, brew, java, mocha, decaf, juice, shakes, ice cream, yogurt, IceCream' ),
				),
				'catering-lite-elementor'              => array(
					'title'       => __( 'Catering', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-catering-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/catering/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/catering/catering-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/catering/catering-lite.json'),
					'keywords'    => __( ' restaurant, bistro, eatery, food, joint, street café, café, coffee, burger, fast food, junk food, noodle, chinese, chef, cook, kitchen, cuisine, cooking, baking, bread, cake, chocolate, nourishment, diet, dishes, waiter, eatables, meal, Catering' ),
				),
				'plumbing-lite-elementor'              => array(
					'title'       => __( 'Plumbing', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-plumber-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/plumbing/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/plumbing/plumbing-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/plumbing/plumbing-lite.json'),
					'keywords'    => __( ' plumber, electrician, carpenter, craftsman, workshop, garage, painter, renovation, decoration, maid service, cleaning, mechanic, construction, installation, contractor, home remodeling, building, plastering, partitioning, celings, roofing, architecture, interior work, engineering, welding, refurbishment, spare parts, manufacturing, plumbing, fabrication, handyman, painting, production, worker, fabrication, welder, smithy, automation, machine, mechanized, Plumbing' ),
				),
				'recycle-lite-elementor'              => array(
					'title'       => __( 'Recycle', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-environmental-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/recycle/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/recycle/recycle-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/recycle/recycle-lite.json'),
					'keywords'    => __( ' organic, farm fresh, vegetables, garden, nature, agriculture, agro food, spices, nutrition, herbal, greenery, environment, ecology, green, eco friendly, conservation, natural, gardening, landscaping, horticulture, Recycle, recycle' ),
				),
				'pottery-lite-elementor'              => array(
					'title'       => __( 'Pottery', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-pottery-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/pottery/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/pottery/pottery-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/pottery/pottery-lite.json'),
					'keywords'    => __( ' interior design, furnishing, cushions, flooring, roofing, house works, vase, flower, curtains, furniture, wallpaper, renovation, framing, modular, kitchen, wardrobe, cupboard, unit, TV, fridge, washing machine, home appliances, bedroom, sofa, couch, living room, Pottery' ),
				),
				'actor-lite-elementor'              => array(
					'title'       => __( 'Actor', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('https://www.sktthemes.org/shop/free-celebrity-wordpress-theme/'),						
					'demo_url'    => esc_url('https://demosktthemes.com/free/actor/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/actor/actor-lite.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/actor/actor-lite.json'),
					'keywords'    => __( ' actor, movie, tv shows, actress, model, instagram, fan, following, shows, events, singing, dancing, birthdays, personal, online presence, resume, profile, portfolio, Actor' ),
				),
				'marketing-agency-elementor'              => array(
					'title'       => __( 'Marketing Agency', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/marketing-agency/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/marketing-agency/marketing-agency.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/marketing-agency/marketing-agency.json'),
					'keywords'    => __( ' marketing-agency, agency, online, digital, consulting, corporate, business, small business, b2b, b2c, financial, investment, portfolio, management, discussion, advice, solicitor, lawyer, attorney, legal, help, SEO, SMO, social, Marketing Agency', 'skt-templates' ),
				),
				'befit-elementor'              => array(
					'title'       => __( 'Befit', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/befit/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/befit/befit.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/befit/befit.json'),
					'keywords'    => __( ' befit, health, fitness, coach, well-being, good physical condition, healthiness, fitness, physical fitness, haleness, good trim, good shape, fine fettle, good kilter, robustness, strength, vigour, soundness, discipline, yoga, meditation, reiki, healing, weight loss, pilates, stretching, relaxation, workout, mental, gymnasium, theater, action, arena, gymnastics, exercise, health club, fitness room, health spa, work out, weight room, working out, sports hall, welfare centre, fitness club, wellness area, workout room, spa, high school, sport club, athletic club, fitness studio, health farm, establishment, gym membership, junior high, sports club, health-care centre, exercise room, training room, fitness suite, health centre, beauty centre, my gym, country club, fite, gym class, medical clinic, med centre, free clinic, medical facilities, dispensary, health posts, healing center, health care facility, medical station, health care establishment, health establishment, medical establishment, centre de santé, medical centres, medical, hospital, polyclinic, healthcare facilities, treatment centre, medical institutions, health care institution, health units, Befit', 'skt-templates' ),
				),
				'cybersecurity-elementor'              => array(
					'title'       => __( 'Cyber Security', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/cybersecurity/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/cybersecurity/cybersecurity.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/cybersecurity/cybersecurity.json'),
					'keywords'    => __( ' cybersecurity, safety, security, biosafety, information security, computer based, computer security, biosecurity, information technology, it security, safety-related, electronic security, ict security, internet safety, cyber-safety, security concerns, logical security, information safety, data security, security information, cyber security task force, security matters, safety issues, electronic information security, firewall, defending data, electronic systems, mobile devices, defending networks, defending servers, security, cyber risk, preventative methods, protection, cyber attack, safety, cyber crime, informatics, consulting, safety measures, Cyber Security', 'skt-templates' ),
				),
				'marathon-elementor'              => array(
					'title'       => __( 'Marathon', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/marathon/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/marathon/marathon.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/marathon/marathon.json'),
					'keywords'    => __( ' marathon, athletic, sporting, sportswomen, gymnastics, sportsmen, physical activity, enjoyment, games, disport, entertainment, physical exercise, working out, amusement, sport, fun, sports news, fitness, sporting events, workout, physical training, physical education, pastimes, plays, amusements, athletes, sportspeople, competitive game, sports wear, sporty, exercising, races, cycling, golf, hobbies, track pants, sports activities, outdoor sport, fairground, sporting activity, sports news, sports update, sporting events, gymnastic exercises, physical recreation, aerobics, workout, football, physical jerks, informal, active lifestyle, Marathon, marathon', 'skt-templates' ),
				),
				'healing-touch-elementor'              => array(
					'title'       => __( 'Healing Touch', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/healing-touch/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/healing-touch/healing-touch.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/healing-touch/healing-touch.json'),
					'keywords'    => __( ' healing touch, healingtouch, meditative practice, yogic, ideology, health, pilates, meditative exercise, yogism, setting-up exercises, relaxation, stretching, stretch, yodel, hot yoga chick, body-bending exercise, yogeeism, exercising, mystery, kundalini, yoga class, yogi, relaxing, yoga classes, eastern discipline, church, philosophical ascetic practice, type of exercise, health system, practice, workout, yoga teachers, physical education, fitness, yoga studio, teaching yoga, acupuncture, ayurveda, yogis, reiki, homeopathy, physical exercise, philosophy, body, exercise, spiritual discipline, ascetic discipline, hinduism, theism, mental exercises, fitness, shape, strength, fun, inspiration, pleasure, refreshment, rubbing, doctor, body massage, eye massage, facial, acupressure, massage therapist, reinstatement, curative, therapeutic, heal, remedial, soothing, medicinal, curing, restorative, sanative, alterative, ointment, get better, recovery, repair, remedy, be cured, cured, get well, overhaul, treat, healed, recover, health-giving, tonic, remedial, assuage, recuperation, recovery, return, salutary, getting well, rehabilitation, pharmaceutical, drug, remedial, calming, reconcile, improvement, organism, health, easing, relieve, treatment, recovery, aid, peace, convalescent, Healing Touch', 'skt-templates' ),
				),
				'secure-elementor'              => array(
					'title'       => __( 'Secure', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/secure/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/secure/secure.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/secure/secure.json'),
					'keywords'    => __( ' secure, safety, security, biosafety, information security, computer based, computer security, biosecurity, information technology, it security, safety-related, electronic security, ict security, internet safety, cyber-safety, security concerns, logical security, information safety, data security, security information, cyber security task force, security matters, safety issues, electronic information security, firewall, defending data, electronic systems, mobile devices, defending networks, defending servers, security, cyber risk, preventative methods, protection, cyber attack, safety, cyber crime, informatics, consulting, safety measures, insurance, protection, certainty, confidence, welfare, safeguards, safeguarding, protected, safety, spread, ensure security, defence, secured, preservation, protection, bodyguard, guard, Secure', 'skt-templates' ),
				),
				'homedecor-elementor'              => array(
					'title'       => __( 'Home Decor', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/homedecor/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/homedecor/homedecor.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/homedecor/homedecor.json'),
					'keywords'    => __( ' homedecor, interior designs, designs, kitchen appliances, Whole Home Makeovers, Crowdsourcing Platform, Furniture Re-Upholsterer, E-decorating Service, Home Window Dresser, Resale Sites, Home Accessories Decorator, Designer Rooms, Eco-Friendly Home Decor Services, Makers And Manufacturers, home decor, interior construction, home decorating, decoration, decor, furnishing articles, interior equipment, internal design, interior set-up, interior fit-out, remodeling, overhaul, improvement, reconstruction, betterment, modernization, redo, new look, refashion, redecoration, repair, revamp, restore, rehabilitation, retreading, refitting, renovation, retouch, Home Decor', 'skt-templates' ),
				),
				'laptop-repair-elementor'              => array(
					'title'       => __( 'Laptop Repair', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/laptop-repair/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/laptop-repair/laptop-repair.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/laptop-repair/laptop-repair.json'),
					'keywords'    => __( ' laptop-repair, laptoprepair, help, helper, helpmate, home security system, hot tub, spa, lamp repair, handyman, landscaping, lawncare, lockset adjustment, maid service, molding installation, moving, paint removal, painting, patio stone installation, pest control, plumbing repair, porch, remodeling basement, remodeling bathroom, remodeling kitchen, roofing, safety modification, sealing driveway, senior living modification, septic system repair, shelf installation, shelving, skylight installation, soundproofing, sprinkler repair, sprinkler system installation, stain removal, staining furniture, stone work, storage area construction, storage area repair, swapping a toilet, swimming pool maintenance, tiling, trash removal, wall building, water purification, water softening, window cleaning, welding, window installation, window repair, window screen, duty, work, waste removal, welder, repair, adjustment, improvment, overhaul, reconstruction, rehabilitation, maintenance, welding service, alteration, remaking, resetting, Laptop Repair', 'skt-templates' ),
				),
				'maintenance-services-elementor'              => array(
					'title'       => __( 'Maintenance Services', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/maintenance-services/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/maintenance-services/maintenance-services.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/maintenance-services/maintenance-services.json'),
					'keywords'    => __( ' maintenance services, support, sustenance, retention, servicing, service, maintained, allowance, repair, nourishment, preserving, control, updating, perpetuation, spread, care, storage, existence, care, keep a record, retirement, up keep, updated, superannuation, maintainance, pensionable, holding, industries, commercial, trade union, Clothing, Textiles, Petroleum, Chemicals, plastics, Computers, Transportation, Food Production, Metal Manufacturing, Wood, Leather, Paper, municipal, industrially, industrialist, metropolitan, employment, occupational, fabric, mechanical, trade, urbanized, heavy-duty, manufacturing, trades union, industry-wide, sectoral, mining, work-related, engineering industry, citywide, workplace, industrialization, gene industry, mining, capital goods industry, genetic engineering industry, manufacturing, electrical industries, electronics industry, electrical engineering industry, electrical industry, machine industry, mechanical design, construction of machines, manufacture of machinery, machinery building, vending machines, engineered, machine engineering, mechanical engineer, construction, earth-moving machinery, Maintenance Services', 'skt-templates' ),
				),
				'hvac-elementor'              => array(
					'title'       => __( 'Hvac', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/hvac/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/hvac/hvac.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/hvac/hvac.json'),
					'keywords'    => __( ' clean, wash, cleanse, wipe, sponge, scrub, mop, rinse, scour, swab, hose down, sanitize, sanitization, disinfect, disinfection, cleaning, decontaminate, antiseptic, sanitary, janitor, lean, wipe, freshen, purify, cleanse, deodorize, deodrant, depurate, wash, depollute, hygiene, residue, sterilise, sterilize, napkin, Hvac', 'skt-templates' ),
				),
				'teethy-elementor'              => array(
					'title'       => __( 'Teethy', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/teethy/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/teethy/teethy.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/teethy/teethy.json'),
					'keywords'    => __( ' teeth, tooth, mouth, denticulation, health, wellness, dental, fangs, toothed, gear, bite, jaws, power, clout, set of teeth, denture, fang, biting, toothing, toothache, prongs, tusk, braces, jaw bone, armed to the teeth, tooth arrangement, orthodontist, dental surgeon, tooth doctor, hygienist, dental practitioner, periodontist, dentistry, medical doctor, dental clinic, stomatologist, exodontist, odontologist, root canal, dental hygienist, dental practice, oral surgeon, dental work, medical officer, dental school, scientist, prosthodontics, appointment, pediatrician, dentist appointment, therapist, psychiatrist, teeth cleaning, toothache, dental records, dental office, dental care, dental implants, root canal treatment, Teethy, teethy', 'skt-templates' ),
				),
				'clean-elementor'              => array(
					'title'       => __( 'Clean', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/clean/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/clean/clean.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/clean/clean.json'),
					'keywords'    => __( ' neat, fair, easy, simple, one page, fresh, tidy, purify, minimum functionality, minimalist, good, straight forward, wholesome, good looking, trendy, Clean, clean', 'skt-templates' ),
				),
				'donation-elementor'              => array(
					'title'       => __( 'Donation', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/donation/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/donation/donation.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/donation/donation.json'),
					'keywords'    => __( ' contribution, fund, grant, gift, reward, prize, offering, endowment, charity, payment, handout, subsidy, favor, funding, giving, show, donate, allowance, award, arrangement, gratuity, lump, largesse, generosity, presentation, donating, aid, liberality, bestowal, provision, grant, bequest, boon, donorship, donate, pledge, subscription, donations, philanthropy, subsidies, help, beneficence, increase, relief, investment, delivery, show, assistance, supply, support, non-profit organization, NGOs, charity, perquisite, concession, endowment, boon, allotment, scholarship, Donation', 'skt-templates' ),
				),
				'laundry-elementor'              => array(
					'title'       => __( 'Laundry', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/laundry/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/laundry/laundry.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/laundry/laundry.json'),
					'keywords'    => __( ' laundry, ironing, shower, laundromat, washhouse, furnace, laundrette, clean, clothes, costume, attire, wear, garment, laundering, washer, laundress, cleanness, dirty linen, brush, dress, launderer, washing machine, washing powder, outfit, utility room, rinse, wash up, drycleaner, laundry soap, filthy clothes, lavage, soaking, bath, dry-cleaned, wash clothes, dirty laundry, washables, Laundry', 'skt-templates' ),
				),
				'funeral-elementor'              => array(
					'title'       => __( 'Funeral', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/funeral/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/funeral/funeral.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/funeral/funeral.json'),
					'keywords'    => __( ' funeral, entombment, inhumation, sepulture, cremation, memorial service, committal, funeral service, exequies, mortuary, mournful, memorial, funeral procession, funeral arrangements, last rites, funeral rites, rite, dismal, sad, mournful, funeral rite, burial service, grave, vigil, burried, ceremony, worry, concern, funeral parlor, sepulchral, mortician, ceremonial occasion, eulogy, being buried, mourners, wakes, observance, landfill, undertaker, underground, last offices, Funeral', 'skt-templates' ),
				),
				'yachtandcruise-elementor'              => array(
					'title'       => __( 'Yacht And Cruise', 'skt-templates' ),
					'description' => __( 'It downloads from our website sktthemes.org, once you do it you will get the exact preview like shown in the demo. Steps after downloading the theme: Upload it via appearance>themes>add new>upload theme zip file and activate the theme.', 'skt-templates' ),
					'theme_url'   => esc_url('#'),
					'demo_url'    => esc_url('https://demosktthemes.com/free/yachtandcruise/'),
					'screenshot'  => esc_url('https://demosktthemes.com/free/yachtandcruise/yachtandcruise.jpg'),
					'import_file' => esc_url('https://demosktthemes.com/free/yachtandcruise/yachtandcruise.json'),
					'keywords'    => __( ' sail, boat, sailing, cruiser, boating, ship, voyage, coast, sails, go sailing, ferry, cruise ship, navigate, sailed, traverse, travel, tour, drift, trip, boat trip, traveling, drive, trip, voyage, roam, journey, wander, explore, hiking, road, walking, roadshow, watershow, bookings, reservations, hotels, real estate, Yacht And Cruise', 'skt-templates' ),
				)  
			);

			foreach ( $templates_list as $template => $properties ) {
				$templates_list[ $template ] = wp_parse_args( $properties, $defaults_if_empty );
			}

			return apply_filters( 'template_directory_templates_list', $templates_list );
		}

		/**
		 * Register endpoint for themes page.
		 */
		public function demo_listing_register() {
			add_rewrite_endpoint( 'sktb_templates', EP_ROOT );
		}

		/**
		 * Return template preview in customizer.
		 *
		 * @return bool|string
		 */
		public function demo_listing() {
			$flag = get_query_var( 'sktb_templates', false );

			if ( $flag !== '' ) {
				return false;
			}
			if ( ! current_user_can( 'customize' ) ) {
				return false;
			}
			if ( ! is_customize_preview() ) {
				return false;
			}

			return $this->render_view( 'template-directory-render-template' );
		}

		/**
		 * Add the 'Template Directory' page to the dashboard menu.
		 */
		public function add_menu_page() {
			$products = apply_filters( 'sktb_template_dir_products', array() );
			foreach ( $products as $product ) {
				add_submenu_page(
					$product['parent_page_slug'], $product['directory_page_title'], __( 'Elementor Templates', 'skt-templates' ), 'manage_options', $product['page_slug'],
					array( $this, 'render_admin_page' )
				);
				
				add_submenu_page(
					$product['parent_page_slug'], $product['directory_page_title'], __( 'Gutenberg Templates', 'skt-templates' ), 'manage_options', $product['gutenberg_page_slug'],
					array( $this, 'gutenberg_render_admin_page' )
				);				
				
			}

		}

		/**
		 * Render the template directory admin page.
		 */
		public function render_admin_page() {
			$data = array(
				'templates_array' => $this->templates_list(),
			);
			echo $this->render_view( 'template-directory-page', $data );
		}
		
		public function gutenberg_render_admin_page() {
			$data = array(
				'templates_array' => $this->gutenberg_templates_list(),
			);
			echo $this->render_view( 'template-directory-page', $data );
		}		

		/**
		 * Utility method to call Elementor import routine.
		 *
		 * @param \WP_REST_Request $request the async request.
		 *
		 * @return string
		 */
		 
		public function import_elementor( \WP_REST_Request $request ) {
			if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
				return 'no-elementor';
			}

			$params        = $request->get_params();
			$template_name = $params['template_name'];
			$template_url  = $params['template_url'];

			require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
			require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );

			// Mime a supported document type.
			$elementor_plugin = \Elementor\Plugin::$instance;
			$elementor_plugin->documents->register_document_type( 'not-supported', \Elementor\Modules\Library\Documents\Page::get_class_full_name() );

			$template                   = download_url( esc_url( $template_url ) );
			$name                       = $template_name;
			$_FILES['file']['tmp_name'] = $template;
			$elementor                  = new \Elementor\TemplateLibrary\Source_Local;
			$elementor->import_template( $name, $template );

			$args = array(
				'post_type'        => 'elementor_library',
				'nopaging'         => true,
				'posts_per_page'   => '1',
				'orderby'          => 'date',
				'order'            => 'DESC',
				'suppress_filters' => true,
			);

			$query = new \WP_Query( $args );

			$last_template_added = $query->posts[0];
			//get template id
			$template_id = $last_template_added->ID;

			wp_reset_query();
			wp_reset_postdata();

			//page content
			$page_content = $last_template_added->post_content;
			//meta fields
			$elementor_data_meta      = get_post_meta( $template_id, '_elementor_data' );
			$elementor_ver_meta       = get_post_meta( $template_id, '_elementor_version' );
			$elementor_edit_mode_meta = get_post_meta( $template_id, '_elementor_edit_mode' );
			$elementor_css_meta       = get_post_meta( $template_id, '_elementor_css' );

			$elementor_metas = array(
				'_elementor_data'      => ! empty( $elementor_data_meta[0] ) ? wp_slash( $elementor_data_meta[0] ) : '',
				'_elementor_version'   => ! empty( $elementor_ver_meta[0] ) ? $elementor_ver_meta[0] : '',
				'_elementor_edit_mode' => ! empty( $elementor_edit_mode_meta[0] ) ? $elementor_edit_mode_meta[0] : '',
				'_elementor_css'       => $elementor_css_meta,
			);
			if($template_name=='Wedding Cards Home' || $template_name=='Wedding Cards About' || $template_name=='Wedding Cards' || $template_name=='Wedding Cards Contact' || $template_name=='Extreme Sports Home' || $template_name=='Extreme Sports About' || $template_name=='Extreme Sports Activities' || $template_name=='Extreme Sports Contact' || $template_name=='Mechanic Home' || $template_name=='Mechanic About' || $template_name=='Mechanic Services' || $template_name=='Mechanic Contact' || $template_name=='Taxi Home' || $template_name=='Taxi About' || $template_name=='Taxi Services' || $template_name=='Taxi Reviews' || $template_name=='Taxi Contact' || $template_name=='Woman Home' || $template_name=='Woman About' || $template_name=='Woman Services' || $template_name=='Woman Contacts' || $template_name=='Specialist Home' || $template_name=='Specialist About' || $template_name=='Specialist Services' || $template_name=='Specialist Contact' || $template_name=='Diwali' || $template_name=='Cyber Monday' || $template_name=='Black Friday' || $template_name=='Halloween'){
				$new_template_page = array(
				'post_type'     => 'page',
				'post_title'    => $template_name,
				'post_status'   => 'publish',
				'post_content'  => $page_content,
				'meta_input'    => $elementor_metas,
				'page_template' => apply_filters( 'template_directory_default_template', 'templates/builder-fullwidth.php' )
			);
				}
				else
				{
				$new_template_page = array(
				'post_type'     => 'page',
				'post_title'    => $template_name,
				'post_status'   => 'publish',
				'post_content'  => $page_content,
				'meta_input'    => $elementor_metas,
				'page_template' => apply_filters( 'template_directory_default_template', 'templates/builder-fullwidth-std.php' )
			);
				}

			$post_id = wp_insert_post( $new_template_page );
			$redirect_url = add_query_arg( array(
				'post'   => $post_id,
				'action' => 'elementor',
			), admin_url( 'post.php' ) );

			return ( $redirect_url );
		}

		/**
		 * Generate action button html.
		 *
		 * @param string $slug plugin slug.
		 *
		 * @return string
		 */
		public function get_button_html( $slug ) {
			$button = '';
			$state  = $this->check_plugin_state( $slug );
			if ( ! empty( $slug ) ) {
				switch ( $state ) {
					case 'install':
						$nonce  = wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'install-plugin',
									'from'   => 'import',
									'plugin' => $slug,
								),
								network_admin_url( 'update.php' )
							),
							'install-plugin_' . $slug
						);
						$button .= '<a data-slug="' . $slug . '" class="install-now sktb-install-plugin button button-primary" href="' . esc_url( $nonce ) . '" data-name="' . $slug . '" aria-label="Install ' . $slug . '">' . __( 'Install and activate', 'skt-templates' ) . '</a>';
						break;
					case 'activate':
						$plugin_link_suffix = $slug . '/' . $slug . '.php';
						$nonce              = add_query_arg(
							array(
								'action'   => 'activate',
								'plugin'   => rawurlencode( $plugin_link_suffix ),
								'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
							), network_admin_url( 'plugins.php' )
						);
						$button             .= '<a data-slug="' . $slug . '" class="activate-now button button-primary" href="' . esc_url( $nonce ) . '" aria-label="Activate ' . $slug . '">' . __( 'Activate', 'skt-templates' ) . '</a>';
						break;
				}// End switch().
			}// End if().
			return $button;
		}

		/**
		 * Getter method for the source url
		 * @return mixed
		 */
		public function get_source_url() {
			return $this->source_url;
		}

		/**
		 * Setting method for source url
		 *
		 * @param $url
		 */
		protected function set_source_url( $url ) {
			$this->source_url = $url;
		}

		/**
		 * Check plugin state.
		 *
		 * @param string $slug plugin slug.
		 *
		 * @return bool
		 */
		public function check_plugin_state( $slug ) {
			if ( file_exists( WP_CONTENT_DIR . '/plugins/' . $slug . '/' . $slug . '.php' ) || file_exists( WP_CONTENT_DIR . '/plugins/' . $slug . '/index.php' ) ) {
				require_once( ABSPATH . 'wp-admin' . '/includes/plugin.php' );
				$needs = ( is_plugin_active( $slug . '/' . $slug . '.php' ) ||
				           is_plugin_active( $slug . '/index.php' ) ) ?
					'deactivate' : 'activate';

				return $needs;
			} else {
				return 'install';
			}
		}

		/**
		 * If the composer library is present let's try to init.
		 */
		public function load_full_width_page_templates() {
			if ( class_exists( '\SktThemes\FullWidthTemplates' ) ) {
				\SktThemes\FullWidthTemplates::instance();
			}
		}

		/**
		 * By default the composer library "Full Width Page Templates" comes with two page templates: a blank one and a full
		 * width one with the header and footer inherited from the active theme.
		 * SKTB Template directory doesn't need the blonk one, so we are going to ditch it.
		 *
		 * @param array $list
		 *
		 * @return array
		 */
		public function filter_fwpt_templates_list( $list ) {
			unset( $list['templates/builder-fullwidth.php'] );

			return $list;
		}

		/**
		 * Utility method to render a view from module.
		 *
		 * @codeCoverageIgnore
		 *
		 * @since   1.0.0
		 * @access  protected
		 *
		 * @param   string $view_name The view name w/o the `-tpl.php` part.
		 * @param   array  $args      An array of arguments to be passed to the view.
		 *
		 * @return string
		 */
		protected function render_view( $view_name, $args = array() ) {
			ob_start();
			$file = $this->get_dir() . '/views/' . $view_name . '-tpl.php';
			if ( ! empty( $args ) ) {
				foreach ( $args as $sktb_rh_name => $sktb_rh_value ) {
					$$sktb_rh_name = $sktb_rh_value;
				}
			}
			if ( file_exists( $file ) ) {
				include $file;
			}

			return ob_get_clean();
		}

		/**
		 * Method to return path to child class in a Reflective Way.
		 *
		 * @since   1.0.0
		 * @access  protected
		 * @return string
		 */
		protected function get_dir() {
			return dirname( __FILE__ );
		}

		/**
		 * @static
		 * @since  1.0.0
		 * @access public
		 * @return PageTemplatesDirectory
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html( 'Cheatin&#8217; huh?'), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html( 'Cheatin&#8217; huh?'), '1.0.0' );
		}
	}
}