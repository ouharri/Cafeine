<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;

$options_available = apply_filters(
	'weglot_tabs_admin_options_available', [
		'api_key_private' => [
			'key'         => 'api_key_private',
			'label'       => __( 'API Key', 'weglot' ),
			'description' => sprintf( esc_html__( 'Log in to %1$sWeglot%2$s to get your API key.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/register-wordpress">', '</a>' ),
		],
		'language_from'   => [
			'key'         => 'original_language',
			'label'       => __( 'Original language', 'weglot' ),
			'description' => 'What is the original (current) language of your website?',
		],
		'languages'       => [
			'key'         => 'destination_language',
			'label'       => __( 'Destination languages', 'weglot' ),
			'description' => sprintf( esc_html__( 'Choose languages you want to translate into. Supported languages can be found %1$shere%2$s.', 'weglot' ), '<a target="_blank" href="https://weglot.com/documentation/available-languages/">', '</a>' ),
		],
	]
);

$user_info = $this->user_api_services->get_user_info();
$plans     = $this->user_api_services->get_plans();

?>

<h3><?php esc_html_e( 'Main configuration', 'weglot' ); ?></h3>
<hr>
<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $options_available['api_key_private']['key'] ); ?>">
				<?php echo esc_html( $options_available['api_key_private']['label'] ); ?>
			</label>
			<p class="sub-label"><?php echo $options_available['api_key_private']['description']; //phpcs:ignore ?></p>
		</th>
		<td class="forminp forminp-text">
			<input
				name="<?php echo esc_attr( sprintf( '%s[%s]', WEGLOT_SLUG, $options_available['api_key_private']['key'] ) ); ?>"
				id="<?php echo esc_attr( $options_available['api_key_private']['key'] ); ?>"
				type="text"
				required
				placeholder="wg_XXXXXXXXXXXX"
				value="<?php echo esc_attr( $this->options[ $options_available['api_key_private']['key'] ] ); ?>"
			>
			<br>
			<?php
			if ( $this->options['has_first_settings'] ) {
				?>
				<p class="description"><?php echo esc_html_e( 'If you don\'t have an account, you can create one in 20 seconds !', 'weglot' ); ?></p>
				<?php
			}
			?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $options_available['language_from']['key'] ); ?>">
				<?php echo esc_html( $options_available['language_from']['label'] ); ?>
			</label>
			<p class="sub-label"><?php echo $options_available['language_from']['description']; //phpcs:ignore ?></p>
		</th>
		<td class="forminp forminp-text">
			<select
				class="weglot-select weglot-select-original"
				name="<?php echo esc_attr( sprintf( '%s[%s]', WEGLOT_SLUG, 'language_from' ) ); ?>"
				id="<?php echo esc_attr( $options_available['language_from']['key'] ); ?>"
			>
				<?php
				$wplang = 'en';
				if( ! empty(get_option( 'WPLANG' ))){
					$wplang                      = substr( get_option( 'WPLANG' ), 0, 2 );
				}
				$original_languages_possible = $this->language_services->get_languages_available( [ 'sort' => true ] );
				foreach ( $original_languages_possible as $language ) {
					if ( $language->getInternalCode() !== 'br' ) {
						?>
						<?php if ( $this->options['has_first_settings'] ) { ?>
							<option
								value="<?php echo esc_attr( $language->getInternalCode() ); ?>"
								<?php selected( $language->getInternalCode(), $wplang ); ?>
							>
								<?php esc_html_e( $language->getEnglishName(), 'weglot' ); //phpcs:ignore ?>
							</option>
							<?php
						} else { ?>
							<option
								value="<?php echo esc_attr( $language->getInternalCode() ); ?>"
								<?php selected( $language->getInternalCode(), $this->options[ $options_available['language_from']['key'] ] ); ?>
							>
								<?php esc_html_e( $language->getEnglishName(), 'weglot' ); //phpcs:ignore ?>
							</option>
						<?php }
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $options_available['languages']['key'] ); ?>">
				<?php echo esc_html( $options_available['languages']['label'] ); ?>
			</label>
			<p class="sub-label"><?php echo $options_available['languages']['description']; //phpcs:ignore ?></p>
		</th>

		<td class="forminp forminp-text">
			<select
				class="weglot-select weglot-select-destination"
				style="display:none"
				name="<?php echo esc_attr( sprintf( '%s[languages][][language_to]', WEGLOT_SLUG ) ); ?>"
				id="<?php echo esc_attr( $options_available['languages']['key'] ); ?>"
				multiple="true"
				required
			>
				<?php
				$languages             = $this->language_services->get_all_languages();
				$destination_languages = $this->language_services->get_destination_languages( true );
				foreach ( $destination_languages as $language ) :
					?>
					<option
						value="<?php echo esc_attr( $language->getInternalCode() ); ?>"
						selected="selected">
						<?php echo esc_html( $language->getLocalName() ); ?>
					</option>
				<?php endforeach; ?>

				<?php foreach ( $languages as $language ) : ?>
					<option
						value="<?php echo esc_attr( $language->getInternalCode() ); ?>"
						<?php selected( true, in_array( $language, $destination_languages, true ) ); ?>
					>
						<?php echo esc_html( $language->getLocalName() ); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<?php
			if ( $user_info && isset( $user_info['plan_id'] ) && $user_info['plan_id'] <= 1 ) {
				?>
				<p class="description">
					<?php // translators: 1 HTML Tag, 2 HTML Tag ?>
					<?php echo sprintf( esc_html__( 'On the free plan, you can choose one language and use a maximum of 2000 words. If you need more, please %1$supgrade your plan%2$s.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/billing/upgrade">', '</a>' ); ?>
				</p>
				<?php
			} elseif ( isset( $user_info['plan_id'] ) && in_array( $user_info['plan_id'], $plans['starter_free']['ids'] ) ) { //phpcs:ignore
				?>
				<p class="description">
					<?php // translators: 1 HTML Tag, 2 HTML Tag ?>
					<?php echo sprintf( esc_html__( 'On the Starter plan, you can choose one language. If you need more, please %1$supgrade your plan%2$s.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/billing/upgrade">', '</a>' ); ?>
				</p>
				<?php
			} elseif ( isset( $user_info['plan_id'] ) && in_array( $user_info['plan_id'], $plans['business']['ids'] ) ) { //phpcs:ignore
				?>
				<p class="description">
					<?php // translators: 1 HTML Tag, 2 HTML Tag ?>
					<?php echo sprintf( esc_html__( 'On the Business plan, you can choose 3 languages. If you need more, please %1$supgrade your plan%2$s.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/billing/upgrade">', '</a>' ); ?>
				</p>
				<?php
			} elseif ( isset( $user_info['plan_id'] ) && in_array( $user_info['plan_id'], $plans['pro']['ids'] ) ) { //phpcs:ignore
				?>
				<p class="description">
					<?php // translators: 1 HTML Tag, 2 HTML Tag ?>
					<?php echo sprintf( esc_html__( 'On the Pro plan, you can choose 5 languages. If you need more, please %1$supgrade your plan%2$s.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/billing/upgrade">', '</a>' ); ?>
				</p>
				<?php
			}
			?>
		</td>
	</tr>
	</tbody>
</table>


<?php if ( ! $this->options['has_first_settings'] && $this->options['show_box_first_settings'] ) : ?>
	<?php $this->option_services->set_option_by_key( 'show_box_first_settings', false ); ?>
	<div id="weglot-box-first-settings" class="weglot-box-overlay">
		<div class="weglot-box">
			<a class="weglot-btn-close"><?php esc_html_e( 'Close', 'weglot' ); ?></a>
			<h3 class="weglot-box--title"><?php esc_html_e( 'Well done! Your website is now multilingual.', 'weglot' ); ?></h3>
			<p class="weglot-box--text"><?php esc_html_e( 'Go on your website, there is a language switcher bottom right. Try it :)', 'weglot' ); ?></p>
			<a class="button button-primary" href="<?php echo esc_url( home_url() ); ?>" target="_blank">
				<?php esc_html_e( 'Go on my front page.', 'weglot' ); ?>
			</a>
			<p class="weglot-box--subtext"><?php esc_html_e( 'Next step, customize the language button as you want and manually edit your translations directly in your Weglot account.', 'weglot' ); ?></p>
		</div>
	</div>
	<?php
	if ( $this->options[ $options_available['languages']['key'] ] && count( $this->options[ $options_available['languages']['key'] ] ) > 0 ) :
		?>
		<iframe
			style="visibility:hidden;"
			src="<?php echo esc_url( sprintf( '%s/%s', home_url(), $this->options[ $options_available['languages']['key'] ][0]['language_to'] ) ); ?>/"
			width="1" height="1">
		</iframe>
	<?php endif; ?>
<?php endif; ?>
