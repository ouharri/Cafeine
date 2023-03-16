<?php
/**
 * The View for Rendering the Template Directory Main Dashboard Page.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    SktThemes
 * @subpackage SktThemes/PageTemplatesDirectory
 * @codeCoverageIgnore
 */
$preview_url = add_query_arg( 'sktb_templates', '', home_url() ); // Define query arg for custom endpoint.
$html = '';
if ( is_array( $templates_array ) ) { ?>
	<div class="sktb-template-dir wrap">
        <h2 class="wp-heading-inline sktb-first-heading"> <?php echo apply_filters( 'sktb_template_dir_page_title', __( 'SKT Templates Directory', 'skt-templates' ) ); ?></h2>
        	<?php
        	$mode = isset($_POST['mode']) ? $_POST['mode']: '';
        	$search = isset($_POST['search']) ? $_POST['search']: '';
			$url = $_SERVER['REQUEST_URI'];  
			$template_text = 'skt_template_directory';
			$get_admin_url = get_admin_url();
			?>
            <div class="sktb-form-area">
            <form method="post" id="sktb-searchform-template"> 
                    	<span> 
                        	<input type="hidden" name="mode" value="action">
                            <input type="text" name="search" placeholder="<?php if (strpos($url, $template_text)!==false){echo "Find Elementor Templates";}else{echo "Find Gutenberg Templates";} ?>" value="" required>
                            <input type="submit" id="searchsubmit" value="Find"> 
                    	</span>
                    </form>
            </div>
            <?php 
				$template_text_elementor = 'skt_template_directory';
				$template_text_gutenberg = 'skt_template_gutenberg';
			?>
            <div class="sktb-templates-button-area">
            	<div class="sktb-template-button-left"><a class="<?php if (strpos($url, $template_text_elementor)!==false){echo'activepage';}?>" href="<?php echo esc_url($get_admin_url)."admin.php?page=skt_template_directory"; ?>"><?php esc_attr_e('Elementor Templates','skt-templates');?></a></div>
                <div class="sktb-template-button-right"><a class="<?php if (strpos($url, $template_text_gutenberg)!==false){echo'activepage';}?>" href="<?php echo esc_url($get_admin_url)."admin.php?page=skt_template_gutenberg"; ?>"><?php esc_attr_e('Gutenberg Templates','skt-templates');?></a></div>
                <div class="clear"></div>
            </div>
        <?php
        	if($mode=="action"){
        		$get_admin_url = get_admin_url();
        ?>
        <div class="sktb-search-result">
        	<?php echo 'search result for <span>'.$search.'</span>'; ?>
        	<a href="<?php if (strpos($url, $template_text)!==false){echo esc_url($get_admin_url)."admin.php?page=skt_template_directory";}else{echo esc_url($get_admin_url)."admin.php?page=skt_template_gutenberg";} ?>"><img src="<?php echo esc_url( SKTB_URL ); ?>images/delete-search.png" title="" class="sktb-delete-search"/></a>
        </div>
    	<?php } ?>
        <div class="sktb-template-browser">
		<?php
		$search_found = false;
		foreach ( $templates_array as $template => $properties ) {
		if($mode=="action"){
		$keywords = $properties['keywords'];
		$title = $properties['title'];
		$pos = strpos($keywords, $search);
		if ($title==$search) { ?>
    		<div class="sktb-template">
					<?php if ( isset( $properties['has_badge'] ) ) { ?>
						<span class="badge"><?php echo esc_html( $properties['has_badge'] ); ?></span>
					<?php } ?>
					<div class="more-details sktb-preview-template"
						 data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
						 data-template-slug="<?php echo esc_attr( $template ); ?>">
						<span><?php echo __( 'More Details', 'skt-templates' ); ?></span></div>
					<div class="sktb-template-screenshot">
						<img src="<?php echo esc_url( $properties['screenshot'] ); ?>"
							 alt="<?php echo esc_html( $properties['title'] ); ?>">
					</div>
					<h2 class="template-name template-header"><?php echo esc_html( $properties['title'] ); ?></h2>
					<div class="sktb-template-actions">
						<?php if ( ! empty( $properties['demo_url'] ) ) { ?>
							<a class="button sktb-preview-template"
							   data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
							   data-template-slug="<?php echo esc_attr( $template ); ?>"><?php echo __( 'Preview', 'skt-templates' ); ?></a>
						<?php } ?>
					</div>
				</div>
		<?php  $search_found = true;	} elseif($pos == true ) {	?>
				<div class="sktb-template">
					<?php if ( isset( $properties['has_badge'] ) ) { ?>
						<span class="badge"><?php echo esc_html( $properties['has_badge'] ); ?></span>
					<?php } ?>
					<div class="more-details sktb-preview-template"
						 data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
						 data-template-slug="<?php echo esc_attr( $template ); ?>">
						<span><?php echo __( 'More Details', 'skt-templates' ); ?></span></div>
					<div class="sktb-template-screenshot">
						<img src="<?php echo esc_url( $properties['screenshot'] ); ?>"
							 alt="<?php echo esc_html( $properties['title'] ); ?>">
					</div>
					<h2 class="template-name template-header"><?php echo esc_html( $properties['title'] ); ?></h2>
					<div class="sktb-template-actions">
						<?php if ( ! empty( $properties['demo_url'] ) ) { ?>
							<a class="button sktb-preview-template"
							   data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
							   data-template-slug="<?php echo esc_attr( $template ); ?>"><?php echo __( 'Preview', 'skt-templates' ); ?></a>
						<?php } ?>
					</div>
				</div>
			<?php  $search_found = true; } }elseif($mode=="")  { ?>
				<div class="sktb-template">
					<?php if ( isset( $properties['has_badge'] ) ) { ?>
						<span class="badge"><?php echo esc_html( $properties['has_badge'] ); ?></span>
					<?php } ?>
					<div class="more-details sktb-preview-template"
						 data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
						 data-template-slug="<?php echo esc_attr( $template ); ?>">
						<span><?php echo __( 'More Details', 'skt-templates' ); ?></span></div>
					<div class="sktb-template-screenshot">
						<img src="<?php echo esc_url( $properties['screenshot'] ); ?>"
							 alt="<?php echo esc_html( $properties['title'] ); ?>">
					</div>
					<h2 class="template-name template-header"><?php echo esc_html( $properties['title'] ); ?></h2>
					<div class="sktb-template-actions">

						<?php if ( ! empty( $properties['demo_url'] ) ) { ?>
							<a class="button sktb-preview-template"
							   data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
							   data-template-slug="<?php echo esc_attr( $template ); ?>"><?php echo __( 'Preview', 'skt-templates' ); ?></a>
						<?php } ?>
					</div>
				</div>
		<?php	$search_found = true;}
		} 
		if (!$search_found) {
		    echo 'No records found';
		}
		?>
		</div>
	</div>
	<div class="wp-clearfix clearfix"></div>
<?php } // End if().
?>
<div class="sktb-template-preview theme-install-overlay wp-full-overlay expanded" style="display: none; ">
	<div class="wp-full-overlay-sidebar">
		<div class="wp-full-overlay-header">
			<button class="close-full-overlay"><span
						class="screen-reader-text"><?php esc_html_e( 'Close', 'skt-templates' ); ?></span></button>
			<div class="sktb-next-prev">
				<button class="previous-theme"><span
							class="screen-reader-text"><?php esc_html_e( 'Previous', 'skt-templates' ); ?></span></button>
				<button class="next-theme"><span
							class="screen-reader-text"><?php esc_html_e( 'Next', 'skt-templates' ); ?></span></button>
			</div>
            
            
            <?php if (strpos($url, $template_text)!==false){?>
				<span class="sktb-import-template button button-primary"><?php esc_html_e( 'Import', 'skt-templates' ); ?></span>
			<?php }else{
				?>
                <span class="sktb-import-template-gb button button-primary"><?php esc_html_e( 'Gutenberg Import', 'skt-templates' ); ?></span>
                <form method="post" action="<?php echo esc_url($get_admin_url).'admin.php?page=skt_template_import'; ?>" id="myForm">
                <input type="hidden"class="template_name" name="template_name" value="" />
                <input type="hidden"class="json_url" name="json_url" value="" />
                <input type="hidden" name="gbimport" id="selectId" value="import" />
                </form>
				<?php
      		} 
	  	?>
		</div>
		<div class="wp-full-overlay-sidebar-content">
			<?php
			foreach ( $templates_array as $template => $properties ) {
				$upsell = 'no';
				if ( isset( $properties['has_badge'] ) && ! isset( $properties['import_file'] ) ) {
					$upsell = 'yes';
					$properties['import_file'] = '';
				}
				?>
				<div class="install-theme-info sktb-theme-info <?php echo esc_attr( $template ); ?>"
					 data-demo-url="<?php echo esc_url( $properties['demo_url'] ); ?>"
                     <?php if( isset( $properties['import_file'] ) ) { ?>
					 data-template-file="<?php echo esc_url( $properties['import_file'] ); ?>"
					<?php  } ?>
                     data-template-title="<?php echo esc_html( $properties['title'] ); ?>"
					 data-upsell="<?php echo esc_attr( $upsell ) ?>">                     
					<h3 class="theme-name"><?php echo esc_html( $properties['title'] ); ?></h3>
					<div class="sktb-preview-wrap">
						<img class="theme-screenshot" src="<?php echo esc_url( $properties['screenshot'] ); ?>"
							 alt="<?php echo esc_html( $properties['title'] ); ?>">
						<?php if ( isset( $properties['has_badge'] ) ) { ?>
							<span class="badge"> <?php echo esc_html( $properties['has_badge'] ); ?></span>
						<?php } ?>
					</div>
					<div class="theme-details">
                    	<?php esc_html_e('Use this layout for your business or personal website. Just click on the import to start using this template for your next website. All the blocks as shown with images will appear on your edit area.', 'skt-templates');?>   
                        <p><a href="<?php echo esc_url('https://www.sktthemes.org/shop/all-themes/');?>" target="_blank"><?php esc_html_e('Buy All Themes', 'skt-templates'); ?></a><i><?php esc_html_e( ' (300+ templates) for just $99. Features include inner pages, page/post layouts, header/footer layouts, site layout, color/font options and 1 year unlimited support.', 'skt-templates' ); ?></i></p>
					</div>
					<?php
					if ( ! empty( $properties['required_plugins'] ) && is_array( $properties['required_plugins'] ) ) { ?>
						<div class="sktb-required-plugins">
							<p><?php esc_html_e( 'Required Plugins', 'skt-templates' ); ?></p>
							<?php
							foreach ( $properties['required_plugins'] as $plugin_slug => $details ) {
								if ( $this->check_plugin_state( $plugin_slug ) === 'install' ) {
									echo '<div class="sktb-installable plugin-card-' . esc_attr( $plugin_slug ) . '">';
									echo '<span class="dashicons dashicons-no-alt"></span>';
									echo $details['title'];
									echo $this->get_button_html( $plugin_slug );
									echo '</div>';
								} elseif ( $this->check_plugin_state( $plugin_slug ) === 'activate' ) {
									echo '<div class="sktb-activate plugin-card-' . esc_attr( $plugin_slug ) . '">';
									echo '<span class="dashicons dashicons-admin-plugins" style="color: #ffb227;"></span>';
									echo $details['title'];
									echo $this->get_button_html( $plugin_slug );
									echo '</div>';
								} else {
									echo '<div class="sktb-installed plugin-card-' . esc_attr( $plugin_slug ) . '">';
									echo '<span class="dashicons dashicons-yes" style="color: #34a85e"></span>';
									echo $details['title'];
									echo '</div>';
								}
							} ?>
						</div>
					<?php } ?>
				</div><!-- /.install-theme-info -->
			<?php } ?>
		</div>
		<div class="wp-full-overlay-footer">
			<button type="button" class="collapse-sidebar button" aria-expanded="true" aria-label="Collapse Sidebar">
				<span class="collapse-sidebar-arrow"></span>
				<span class="collapse-sidebar-label"><?php esc_html_e( 'Collapse', 'skt-templates' ); ?></span>
			</button>
			<div class="devices-wrapper">
				<div class="devices sktb-responsive-preview">
					<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
						<span class="screen-reader-text"><?php esc_html_e( 'Enter desktop preview mode', 'skt-templates' ); ?></span>
					</button>
					<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
						<span class="screen-reader-text"><?php esc_html_e( 'Enter tablet preview mode', 'skt-templates' ); ?></span>
					</button>
					<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
						<span class="screen-reader-text"><?php esc_html_e( 'Enter mobile preview mode', 'skt-templates' ); ?></span>
					</button>
				</div>
			</div>

		</div>
	</div>
	<div class="wp-full-overlay-main sktb-main-preview">
		<iframe src="" title="Preview" class="sktb-template-frame"></iframe>
	</div>
</div>