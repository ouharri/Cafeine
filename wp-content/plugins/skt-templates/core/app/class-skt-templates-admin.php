<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/app
 */

class Skt_Templates_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}
	
	
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Skt_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Skt_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		 
		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}
		
		
		
		if ( in_array( $screen->id, array( 'toplevel_page_skt_template_about' ), true ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/skt-templates-admin.css', array(), $this->version, 'all' );
		}
		if ( in_array( $screen->id, array( 'toplevel_page_skt_template_import' ), true ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/skt-templates-admin.css', array(), $this->version, 'all' );
		}

		do_action( 'sktb_admin_enqueue_styles' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Skt_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Skt_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}
 
		do_action( 'sktb_admin_enqueue_scripts' );
	}

	/**
	 * Add admin menu items for skt-templates.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function menu_pages() {
		add_menu_page(
			__( 'SKT Templates', 'skt-templates' ),
			__( 'SKT Templates', 'skt-templates' ),
			'manage_options',
			'skt_template_about',
			array(
				$this,
				'page_modules_render',
			),
			SKTB_URL . 'images/skt-template-icon.svg',
			'75'
		);
		add_submenu_page( 'skt_template_about', __( 'SKT Templates General Options', 'skt-templates' ), __( 'About Templates', 'skt-templates' ), 'manage_options', 'skt_template_about' );
		
		
		
add_menu_page(
			__( 'SKT Templates', 'skt-templates' ),
			__( 'SKT Templates', 'skt-templates' ),
			'manage_options',
			'skt_template_import',
			array(
				$this,
				'page_import_tempate',
			),
			'99'
		);
		add_submenu_page( 'skt_template_import', __( 'SKT Templates General Options', 'skt-templates' ), __( 'Import Templates', 'skt-templates' ), 'manage_options', 'skt_template_import' );		
		
		
	}

	/**
	 * Add the initial dashboard notice to guide the user to the OrbitFox admin page.
	 *
	 * @since   2.3.4
	 * @access  public
	 */
	public function visit_dashboard_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! get_user_meta( $user_id, 'skt_templates_ignore_visit_dashboard_notice' ) ) { ?>
			
            
            <div class="notice notice-info" style="position:relative;">
				<p>
				<?php
					/*
					 * translators: Go to url.
					 */
					echo sprintf( esc_attr__( 'You have activated SKT Templates plugin! Go to the %s to get started.', 'skt-templates' ), sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'skt_templates_ignore_visit_dashboard_notice', '0', admin_url( 'admin.php?page=skt_template_directory' ) ) ), esc_attr__( 'Template Directory', 'skt-templates' ) ) );
				?>
					</p>
				<a href="<?php echo esc_url( add_query_arg( 'skt_templates_ignore_visit_dashboard_notice', '0', admin_url( 'admin.php?page=skt_template_directory' ) ) ); ?>"
				   class="notice-dismiss" style="text-decoration: none;">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</a>
			</div>
            
            
			<?php
		}
	}

	/**
	 * Dismiss the initial dashboard notice.
	 *
	 * @since   2.3.4
	 * @access  public
	 */
	public function visit_dashboard_notice_dismiss() {
		global $current_user;
		$user_id = $current_user->ID;
		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( isset( $_GET['skt_templates_ignore_visit_dashboard_notice'] ) && '0' == $_GET['skt_templates_ignore_visit_dashboard_notice'] ) {
			add_user_meta( $user_id, 'skt_templates_ignore_visit_dashboard_notice', 'true', true );
			wp_safe_redirect( admin_url( 'admin.php?page=skt_template_directory' ) );
			exit;
		}
	}
	
	
	public function page_import_tempate() {
				$gbimport = isset($_POST['gbimport']) ? $_POST['gbimport']: '';
				if($gbimport=="import"){
				?>
				<style>
				body.toplevel_page_skt_template_import{display:block;}
				</style>
				<?php }  
				if($gbimport=="import"){
					$json_url = isset($_POST['json_url']) ? $_POST['json_url']: '';
					$template_name = isset($_POST['template_name']) ? $_POST['template_name']: '';

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $json_url);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_TIMEOUT, 60);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				   "Content-Type: application/json"
				));
				$content = curl_exec($curl);
				$response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);
	
				// Response
				if($response != 200){
				   return false;
				}
				$data = json_decode($content, true);
 
				$page_content = $data['original_content'];
				$insertId = $data['id'];
				
				$new_template_page = array(
					'post_type'     => 'page',
					'post_title'    => $template_name,
					'post_status'   => 'publish',
					'post_content'  => $page_content,
					'page_template' => apply_filters( 'template_directory_default_template', 'templates/builder-fullwidth-gb.php' )
				);
 
				$post_id = wp_insert_post( $new_template_page );
				$redirect_url = add_query_arg( array(
				'post'   => $post_id,
				'action' => 'edit',
			), admin_url( 'post.php' ) );

			echo("<script>document.location.href = '".$redirect_url."'</script>");
			exit;
				
			}
?>
		<?php
	}

	/**
	 * Calls the skt_templates_modules hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load_modules() {
		do_action( 'skt_templates_modules' );
	}

	/**
	 * Method to display modules page.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function page_modules_render() {
		$global_settings = new Skt_Templates_Global_Settings();

		$modules = $global_settings::$instance->module_objects;

		$rdh           = new Skt_Templates_Render_Helper();
		$panels        = '';
		$count_modules = 0;
		foreach ( $modules as $slug => $module ) {
			if ( $module->enable_module() ) {
				$module_options = $module->get_options();
				$options_fields = '';
				if ( ! empty( $module_options ) ) {
					foreach ( $module_options as $option ) {
						$options_fields .= $rdh->render_option( $option, $module );
					}

					$panels .= $rdh->get_partial(
						'module-panel',
						array(
							'slug'           => $slug,
							'name'           => $module->name,
							'active'         => $module->get_is_active(),
							'description'    => $module->description,
							'show'           => $module->show,
							'no_save'        => $module->no_save,
							'options_fields' => $options_fields,
						)
					);
				}
			}// End if().
		}// End foreach().

		$no_modules = false;
		$empty_tpl  = '';

		$data   = array(
			'panels'        => $panels,
		);
		$output = $rdh->get_view( 'modules', $data );
		echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}