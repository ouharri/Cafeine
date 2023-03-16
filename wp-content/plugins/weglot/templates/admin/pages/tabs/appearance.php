<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;
use WeglotWP\Helpers\Helper_Flag_Type;

$options_available = [
	'type_flags'      => [
		'key'         => 'type_flags',
		'label'       => __( 'Type of flags', 'weglot' ),
		'description' => '',
	],
	'is_fullname'     => [
		'key'         => 'is_fullname',
		'label'       => __( 'Is fullname', 'weglot' ),
		'description' => __( "Check if you want the name of the language. Don't check if you want the language code.", 'weglot' ),
	],
	'with_name'       => [
		'key'         => 'with_name',
		'label'       => __( 'With name', 'weglot' ),
		'description' => __( 'Check if you want to display the name of languages.', 'weglot' ),
	],
	'is_dropdown'     => [
		'key'         => 'is_dropdown',
		'label'       => __( 'Is dropdown', 'weglot' ),
		'description' => __( 'Check if you want the button to be a dropdown box.', 'weglot' ),
	],
	'with_flags'      => [
		'key'         => 'with_flags',
		'label'       => __( 'With flags', 'weglot' ),
		'description' => __( 'Check if you want flags in the language button.', 'weglot' ),
	],
	'override_css'    => [
		'key'         => 'override_css',
		'label'       => __( 'Override CSS', 'weglot' ),
		'description' => __( "Don't change it unless you want a specific style for your button.", 'weglot' ),
	],
	'flag_css'        => [
		'key' => 'flag_css',
	],
	'switcher_editor' => [
		'key'         => 'switcher_editor',
		'label'       => __( 'Custom position?', 'weglot' ),
		'description' => __( 'You can place the button anywhere in your site using our switcher editor in your Weglot Dashboard', 'weglot' ),
	],
];

?>
<style id="weglot-css-flag-css"></style>
<style id="weglot-css-inline"></style>
<?php if ( empty( $this->option_services->get_switchers_editor_button() ) ) { ?>
	<h3>
		<?php echo esc_html__( 'Language button design', 'weglot' ) . ' ' . esc_html__( '(Optional)', 'weglot' ); ?>
	</h3>
	<hr/>

	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label>
					<?php echo esc_html__( 'Button preview', 'weglot' ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<?php echo $this->button_services->get_html( 'weglot-preview' ); //phpcs:ignore ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['is_dropdown']['key'] ); ?>">
					<?php echo esc_html( $options_available['is_dropdown']['label'] ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<input
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][is_dropdown]', WEGLOT_SLUG ) ); ?>"
					id="<?php echo esc_attr( $options_available['is_dropdown']['key'] ); ?>"
					type="checkbox"
					<?php checked( $this->options[ $options_available['is_dropdown']['key'] ], 1 ); ?>
				>
				<p class="description"><?php echo esc_html( $options_available['is_dropdown']['description'] ); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['with_flags']['key'] ); ?>">
					<?php echo esc_html( $options_available['with_flags']['label'] ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<input
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][with_flags]', WEGLOT_SLUG ) ); ?>"
					id="<?php echo esc_attr( $options_available['with_flags']['key'] ); ?>"
					type="checkbox"
					<?php checked( $this->options[ $options_available['with_flags']['key'] ], 1 ); ?>
				>
				<p class="description"><?php echo esc_html( $options_available['with_flags']['description'] ); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['type_flags']['key'] ); ?>">
					<?php echo esc_html( $options_available['type_flags']['label'] ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<select
					class="wg-input-select"
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][flag_type]', WEGLOT_SLUG ) ); ?>"
					id="<?php echo esc_attr( $options_available['type_flags']['key'] ); ?>"
				>
					<option
						<?php selected( $this->options[ $options_available['type_flags']['key'] ], Helper_Flag_Type::RECTANGLE_MAT ); ?>
						data-value="<?php echo esc_attr( Helper_Flag_Type::get_flag_number_with_type( Helper_Flag_Type::RECTANGLE_MAT ) ); ?>"
						value="<?php echo esc_attr( Helper_Flag_Type::RECTANGLE_MAT ); ?>"
					>
						<?php esc_html_e( 'Rectangle mat', 'weglot' ); ?>
					</option>
					<option
						<?php selected( $this->options[ $options_available['type_flags']['key'] ], Helper_Flag_Type::SHINY ); ?>
						data-value="<?php echo esc_attr( Helper_Flag_Type::get_flag_number_with_type( Helper_Flag_Type::SHINY ) ); ?>"
						value="<?php echo esc_attr( Helper_Flag_Type::SHINY ); ?>"
					>
						<?php esc_html_e( 'Rectangle shiny', 'weglot' ); ?>
					</option>
					<option
						<?php selected( $this->options[ $options_available['type_flags']['key'] ], Helper_Flag_Type::SQUARE ); ?>
						data-value="<?php echo esc_attr( Helper_Flag_Type::get_flag_number_with_type( Helper_Flag_Type::SQUARE ) ); ?>"
						value="<?php echo esc_attr( Helper_Flag_Type::SQUARE ); ?>"
					>
						<?php esc_html_e( 'Square', 'weglot' ); ?>
					</option>
					<option
						<?php selected( $this->options[ $options_available['type_flags']['key'] ], Helper_Flag_Type::CIRCLE ); ?>
						data-value="<?php echo esc_attr( Helper_Flag_Type::get_flag_number_with_type( Helper_Flag_Type::CIRCLE ) ); ?>"
						value="<?php echo esc_attr( Helper_Flag_Type::CIRCLE ); ?>"
					>
						<?php esc_html_e( 'Circle', 'weglot' ); ?>
					</option>
				</select>
				<div class="flag-style-openclose"><?php esc_html_e( 'Change country flags', 'weglot' ); ?></div>
				<p id="custom_flag_tips">You are still using old flags. To use new SVG flags, make sure you are using
					the default flags (if you have some custom CSS related to background-position or background-image,
					remove it). Then save your settings and you will be using the flags</p>
				<div class="flag-style-wrapper" style="display:none;">
					<select class="flag-en-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose English flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'United Kingdom (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'United States', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Australia', 'weglot' ); ?></option>
						<option value=3><?php esc_html_e( 'Canada', 'weglot' ); ?></option>
						<option value=4><?php esc_html_e( 'New Zealand', 'weglot' ); ?></option>
						<option value=5><?php esc_html_e( 'Jamaica', 'weglot' ); ?></option>
						<option value=6><?php esc_html_e( 'Ireland', 'weglot' ); ?></option>
					</select>
					<select class="flag-es-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose Spanish flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'Spain (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'Mexico', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Argentina', 'weglot' ); ?></option>
						<option value=3><?php esc_html_e( 'Colombia', 'weglot' ); ?></option>
						<option value=4><?php esc_html_e( 'Peru', 'weglot' ); ?></option>
						<option value=5><?php esc_html_e( 'Bolivia', 'weglot' ); ?></option>
						<option value=6><?php esc_html_e( 'Uruguay', 'weglot' ); ?></option>
						<option value=7><?php esc_html_e( 'Venezuela', 'weglot' ); ?></option>
						<option value=8><?php esc_html_e( 'Chile', 'weglot' ); ?></option>
						<option value=9><?php esc_html_e( 'Ecuador', 'weglot' ); ?></option>
						<option value=10><?php esc_html_e( 'Guatemala', 'weglot' ); ?></option>
						<option value=11><?php esc_html_e( 'Cuba', 'weglot' ); ?></option>
						<option value=12><?php esc_html_e( 'Dominican Republic', 'weglot' ); ?></option>
						<option value=13><?php esc_html_e( 'Honduras', 'weglot' ); ?></option>
						<option value=14><?php esc_html_e( 'Paraguay', 'weglot' ); ?></option>
						<option value=15><?php esc_html_e( 'El Salvador', 'weglot' ); ?></option>
						<option value=16><?php esc_html_e( 'Nicaragua', 'weglot' ); ?></option>
						<option value=17><?php esc_html_e( 'Costa Rica', 'weglot' ); ?></option>
						<option value=18><?php esc_html_e( 'Puerto Rico', 'weglot' ); ?></option>
						<option value=19><?php esc_html_e( 'Panama', 'weglot' ); ?></option>
					</select>
					<select class="flag-fr-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose French flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'France (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'Belgium', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Canada', 'weglot' ); ?></option>
						<option value=3><?php esc_html_e( 'Switzerland', 'weglot' ); ?></option>
						<option value=4><?php esc_html_e( 'Luxemburg', 'weglot' ); ?></option>
					</select>
					<select class="flag-ar-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose Arabic flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'Saudi Arabia (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'Algeria', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Egypt', 'weglot' ); ?></option>
						<option value=3><?php esc_html_e( 'Iraq', 'weglot' ); ?></option>
						<option value=4><?php esc_html_e( 'Jordan', 'weglot' ); ?></option>
						<option value=5><?php esc_html_e( 'Kuwait', 'weglot' ); ?></option>
						<option value=6><?php esc_html_e( 'Lebanon', 'weglot' ); ?></option>
						<option value=7><?php esc_html_e( 'Libya', 'weglot' ); ?></option>
						<option value=8><?php esc_html_e( 'Morocco', 'weglot' ); ?></option>
						<option value=14><?php esc_html_e( 'Oman', 'weglot' ); ?></option>
						<option value=9><?php esc_html_e( 'Qatar', 'weglot' ); ?></option>
						<option value=10><?php esc_html_e( 'Syria', 'weglot' ); ?></option>
						<option value=11><?php esc_html_e( 'Tunisia', 'weglot' ); ?></option>
						<option value=12><?php esc_html_e( 'United Arab Emirates', 'weglot' ); ?></option>
						<option value=13><?php esc_html_e( 'Yemen', 'weglot' ); ?></option>
					</select>
					<select class="flag-zh-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose Simplified Chinese flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'China (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'Taiwan', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Hong Kong', 'weglot' ); ?></option>
					</select>
					<select class="flag-tw-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose Traditional Chinese flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'Taiwan (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'China', 'weglot' ); ?></option>
						<option value=2><?php esc_html_e( 'Hong Kong', 'weglot' ); ?></option>
					</select>
					<select class="flag-pt-type wg-input-select">
						<option value=0><?php esc_html_e( 'Choose Portuguese flag:', 'weglot' ); ?></option>
						<option value=0><?php esc_html_e( 'Portugal (default)', 'weglot' ); ?></option>
						<option value=1><?php esc_html_e( 'Brazil', 'weglot' ); ?></option>
					</select>
					<p><?php esc_html_e( 'If you want to use a different flag, just ask us.', 'weglot' ); ?></p>
				</div>
				<textarea id="flag_css"
						  name="<?php echo esc_attr( sprintf( '%s[%s]', WEGLOT_SLUG, $options_available['flag_css']['key'] ) ); ?>"
						  style="display:none;"><?php echo esc_attr( $this->options['flag_css'] ); ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['with_name']['key'] ); ?>">
					<?php echo esc_html( $options_available['with_name']['label'] ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<input
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][with_name]', WEGLOT_SLUG ) ); ?>"
					id="<?php echo esc_attr( $options_available['with_name']['key'] ); ?>"
					type="checkbox"
					<?php checked( $this->options[ $options_available['with_name']['key'] ], 1 ); ?>
				>
				<p class="description"><?php echo esc_html( $options_available['with_name']['description'] ); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['is_fullname']['key'] ); ?>">
					<?php echo esc_html( $options_available['is_fullname']['label'] ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<input
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][full_name]', WEGLOT_SLUG ) ); ?>"
					id="<?php echo esc_attr( $options_available['is_fullname']['key'] ); ?>"
					type="checkbox"
					<?php checked( $this->options[ $options_available['is_fullname']['key'] ], 1 ); ?>
				>
				<p class="description"><?php echo esc_html( $options_available['is_fullname']['description'] ); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $options_available['override_css']['key'] ); ?>">
					<?php echo esc_html( $options_available['override_css']['label'] ); ?>
				</label>
				<p class="sub-label"><?php echo esc_html( $options_available['override_css']['description'] ); ?></p>
			</td>
			</th>
			<td class="forminp forminp-text">
				<textarea
					class="wg-input-textarea"
					id="<?php echo esc_attr( $options_available['override_css']['key'] ); ?>"
					type="text"
					rows="10"
					cols="30"
					name="<?php echo esc_attr( sprintf( '%s[custom_settings][button_style][custom_css]', WEGLOT_SLUG ) ); ?>"
					placeholder=".country-selector {
  margin-bottom: 20px;
}"><?php echo $this->options[ $options_available['override_css']['key'] ]; //phpcs:ignore?></textarea>
		</tr>
		</tbody>
	</table>
<?php } ?>

<h3>
	<?php echo esc_html_e( 'Language button position', 'weglot' ) . ' ' . esc_html__( '(Optional)', 'weglot' ); ?>
</h3>
<hr/>

<p><?php esc_html_e( 'Where will the language button be on my website? By default, bottom right.', 'weglot' ); ?></p>

<table class="form-table">
	<?php if ( isset( $this->options['is_menu'] ) && $this->options['is_menu'] ) : ?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="is_menu>">
					<?php echo esc_html__( 'In menu (Weglot translate V1) ?', 'weglot' ); ?>
				</label>
			</th>
			<td class="forminp forminp-text">
				<input
					name="is_menu"
					id="is_menu"
					type="checkbox"
					checked
					style="display:inline-block;"
				>
				<div class="notice notice-error is-dismissible"
					 style="display: inline-block; position: relative; width: 80%; vertical-align: middle;">
					<p>
						<?php
						// translators: 1 HTML Tag, 2 HTML Tag
						echo esc_html__( 'Warning, this feature will be depreciated. We strongly advise you to uncheck the option and use and use the functionality: "In menu".', 'weglot' );
						?>
					</p>
				</div>
			</td>
		</tr>
	<?php endif; ?>

	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'In menu?', 'weglot' ); ?></th>
		<td>
			<?php echo sprintf( esc_html__( 'You can place the button in a menu area. Go to %1$sAppearance → Menus%2$s and drag and drop the Weglot Translate Custom link where you want.', 'weglot' ), '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">', '</a>' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'As a widget?', 'weglot' ); ?></th>
		<td>
			<?php echo sprintf( esc_html__( 'You can place the button in a widget area. Go to %1$sAppearance → Widgets%2$s and drag and drop the Weglot Translate widget where you want.', 'weglot' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'With a shortcode?', 'weglot' ); ?></th>
		<td>
			<?php esc_html_e( 'You can use the Weglot shortcode [weglot_switcher] wherever you want to place the button.', 'weglot' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'In the source code?', 'weglot' ); ?></th>
		<td>
			<?php esc_html_e( 'You can add the code &lt;div id=&quot;weglot_here&quot;&gt;&lt;/div&gt; wherever you want in the source code of your HTML page. The button will appear at this place.', 'weglot' ); ?>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $options_available['switcher_editor']['key'] ); ?>">
				<?php echo esc_html( $options_available['switcher_editor']['label'] ); ?>
			</label>
			<p class="sub-label"><?php echo esc_html( $options_available['switcher_editor']['description'] ); ?></p>
		</th>
		<td class="forminp forminp-text">
			<a class="btn btn-soft"
			   href="https://dashboard.weglot.com/settings/language-switcher/editor?url=<?php echo esc_url( get_home_url() ); ?>"
			   target="_blank"><span
					class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Use switcher editor', 'weglot' ); ?>
			</a>
		</td>
	</tr>
</table>

<template id="li-button-tpl">
	<li class="wg-li {CLASSES} {CODE_LANGUAGE}" data-code-language="{CODE_LANGUAGE}">
		<a href="#">{LABEL_LANGUAGE}</a></li>
</template>
