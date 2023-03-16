<style type="text/css">
	.omapi-constant-contact #wpcontent {
		padding: 0;
	}
	.omapi-constant-contact #wpbody {
		background: #fff;
		padding-left: 40px;
		padding-right: 40px;
	}

	.notice {
		display: none;
	}

	.om-cc-wrap {
		max-width: 970px;
	}

	.om-cc-wrap h1 {
		/*color: #1a5285;*/
		font-size: 30px;
		margin: 0 0 15px 0;
	}

	.om-cc-wrap h2 {
		/*color: #1a5285;*/
		font-size: 26px;
		margin: 0 0 15px 0;
		text-align: left;
	}

	.om-cc-wrap p {
		font-size: 16px;
		font-weight: 300;
		color: #333;
		margin: 1.2em 0;
	}

	.om-cc-wrap ul,
	.om-cc-wrap ol {
		margin: 1.6em 2.5em 2em;
		line-height: 1.5;
		font-size: 16px;
		font-weight: 300;
	}

	.om-cc-wrap ul {
		list-style: disc;
	}

	.om-cc-wrap li {
		margin-bottom: 0.8em;
	}

	.om-cc-wrap hr {
		margin: 2.2em 0;
	}

	.om-cc-wrap .logo {
		float: right;
		margin-top: 0.8em;
		border: 1px solid #ddd;
	}

	.om-cc-wrap .reasons {
		margin: 2.2em 400px 2.2em 2em;
	}

	.om-cc-wrap .reasons li {
		margin-bottom: 1.4em;
	}

	.om-cc-wrap .steps {
		clear: both;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		    flex-wrap: wrap;
	}

	.om-cc-wrap .step {
		-webkit-box-flex: 1;
		    -ms-flex-positive: 1;
		        flex-grow: 1;
		-ms-flex-negative: 1;
		    flex-shrink: 1;
		margin-bottom: 1.4em;
		padding: 0 1em 0 0;
		-ms-flex-preferred-size: 50%;
		    flex-basis: 50%;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}

	.om-cc-wrap .step a {
		-webkit-box-shadow: rgba(0, 35, 60, 0.1) 3px 7px 13px 0px;
		box-shadow: rgba(0, 35, 60, 0.1) 3px 7px 13px 0px;
		border: 1px solid #efefef;
		display: block;
	}

	.om-cc-wrap .step a:hover {
		border-color: #d9d9d9;
	}

	.om-cc-wrap .step img {
		max-width: 100%;
		height: auto;
		display: block;
	}

	.om-cc-wrap .dashicons-yes {
		color: #19BE19;
		font-size: 26px;
	}

	.om-cc-wrap .button {
		background-color: #0078C3;
		border: 1px solid #005990;
		border-radius: 4px;
		color: #fff;
		font-size: 16px;
		font-weight: 600;
		height: auto;
		line-height: 1;
		margin-bottom: 10px;
		padding: 14px 30px;
		text-align: center;
	}

	.om-cc-wrap .button:hover,
	.om-cc-wrap .button:focus {
		background-color: #005990;
		color: #fff
	}

	@media only screen and (max-width: 767px) {
		.om-cc-wrap h1 {
			font-size: 26px;
		}

		.om-cc-wrap h2 {
			font-size: 22px;
		}

		.om-cc-wrap p {
			font-size: 14px;
		}

		.om-cc-wrap ul,
		.om-cc-wrap ol {
			font-size: 14px;
		}

		.om-cc-wrap .logo {
			width: 120px;
		}

		.om-cc-wrap .reasons {
			margin-right: 150px;
		}
	}
</style>
<div class="wrap omapi-page om-cc-wrap">
	<h1><?php esc_html_e( 'Grow Your Website with OptinMonster + Email Marketing', 'optin-monster-api' ); ?></h1>
	<p><?php esc_html_e( 'Wondering if email marketing is really worth your time?', 'optin-monster-api' ); ?></p>
	<p><?php echo wp_kses( __( 'Email is hands-down the most effective way to nurture leads and turn them into customers, with a return on investment (ROI) of <strong>$44 back for every $1 spent</strong> according to the Direct Marketing Association.', 'optin-monster-api' ), array( 'strong' => array() ) ); ?></p>
	<p><?php esc_html_e( 'Here are 3 big reasons why every smart business in the world has an email list:', 'optin-monster-api' ); ?></p>
	<a href="<?php echo esc_url( $data['signup_url'] ); ?>" target="_blank" rel="noopener noreferrer">
		<img width="350" class="logo" src="<?php echo esc_url( $data['images_url'] .'constant-contact-OM.png' ); ?>" alt="<?php esc_attr_e( 'OptinMonster with Constant Contact - Try us free', 'optin-monster-api' ); ?>"/>
	</a>
	<ol class="reasons">
		<li><?php echo wp_kses( __( '<strong>Email is still #1</strong> - At least 91% of consumers check their email on a daily basis. You get direct access to your subscribers, without having to play by social media&#39;s rules and algorithms.', 'optin-monster-api' ), array( 'strong' => array() ) ); ?></li>
		<li><?php echo wp_kses( __( '<strong>You own your email list</strong> - Unlike with social media, your list is your property and no one can revoke your access to it.', 'optin-monster-api' ), array( 'strong' => array() ) ); ?></li>
		<li><?php echo wp_kses( __( '<strong>Email converts</strong> - People who buy products marketed through email spend 138% more than those who don&#39;t receive email offers.', 'optin-monster-api' ), array( 'strong' => array() ) ); ?></li>
	</ol>
	<p><?php esc_html_e( 'That&#39;s why it&#39;s crucial to start collecting email addresses and building your list as soon as possible.', 'optin-monster-api' ); ?></p>
	<p>
		<?php
		printf(
			wp_kses(
				/* translators: %s - WPBeginners.com Guide to Email Lists URL. */
				__( 'For more details, see this guide on <a href="%s" target="_blank" rel="noopener noreferrer">why building your email list is so important</a>.', 'optin-monster-api' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			'https://optinmonster.com/beginners-guide-to-email-marketing/'
		);
		?>
	</p>
	<hr/>
	<h2><?php esc_html_e( 'You&#39;ve Already Started - Here&#39;s the Next Step (It&#39;s Easy)', 'optin-monster-api' ); ?></h2>
	<p><?php esc_html_e( 'Here are the 3 things you need to build an email list:', 'optin-monster-api' ); ?></p>
	<ol>
		<li><?php esc_html_e( 'A Website or Blog', 'optin-monster-api' ); ?> <span class="dashicons dashicons-yes"></span></li>
		<?php // TODO: update the following line ?>
		<li><?php esc_html_e( 'High-Converting Form Builder', 'optin-monster-api' ); ?> <span class="dashicons dashicons-yes"></span></li>
		<li><strong><?php esc_html_e( 'The Best Email Marketing Service', 'optin-monster-api' ); ?></strong></li>
	</ol>
	<p><?php esc_html_e( 'With a powerful email marketing service like Constant Contact, you can instantly send out mass notifications and beautifully designed newsletters to engage your subscribers.', 'optin-monster-api' ); ?></p>
	<p>
		<a href="<?php echo esc_url( $data['signup_url'] ); ?>" class="button" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e( 'Get Started with Constant Contact for Free', 'optin-monster-api' ); ?>
		</a>
	</p>
	<p><?php esc_html_e( 'OptinMonster plugin makes it fast and easy to capture all kinds of visitor information right from your WordPress site - even if you don&#39;t have a Constant Contact account.', 'optin-monster-api' ); ?></p>
	<p><?php esc_html_e( 'But when you combine OptinMonster with Constant Contact, you can nurture your contacts and engage with them even after they leave your website. When you use Constant Contact + OptinMonster together, you can:', 'optin-monster-api' ); ?></p>
	<ul>
		<li><?php esc_html_e( 'Seamlessly add new contacts to your email list', 'optin-monster-api' ); ?></li>
		<li><?php esc_html_e( 'Create and send professional email newsletters', 'optin-monster-api' ); ?></li>
		<li><?php esc_html_e( 'Get expert marketing and support', 'optin-monster-api' ); ?></li>
	</ul>
	<p>
		<a href="<?php echo esc_url( $data['signup_url'] ); ?>" target="_blank" rel="noopener noreferrer">
			<strong><?php esc_html_e( 'Try Constant Contact Today', 'optin-monster-api' ); ?></strong>
		</a>
	</p>
	<hr/>
	<h2><?php esc_html_e( 'OptinMonster Makes List Building Easy', 'optin-monster-api' ); ?></h2>
	<p><?php esc_html_e( 'When creating OptinMonster, our goal was to make a conversion optimization tool that was both EASY and POWERFUL.', 'optin-monster-api' ); ?></p>
	<p><?php esc_html_e( 'Here&#39;s how it works.', 'optin-monster-api' ); ?></p>
	<div class="steps">
		<div class="step1 step">
			<a href="<?php echo esc_url( $data['images_url'] . 'om-step-1.png' ); ?>"><img src="<?php echo esc_url( $data['images_url'] . 'om-step-1-sm.png' ); ?>"></a>
			<p><?php esc_html_e( '1. Select a design from our beautiful, high-converting template library.', 'optin-monster-api' ); ?></p>
		</div>
		<div class="step2 step">
			<a href="<?php echo esc_url( $data['images_url'] . 'om-step-2.png' ); ?>"><img src="<?php echo esc_url( $data['images_url'] . 'om-step-2-sm.png' ); ?>"></a>
			<p><?php esc_html_e( '2. Drag and drop elements to completely customize the look and feel of your campaign.', 'optin-monster-api' ); ?></p>
		</div>
		<div class="step3 step">
			<a href="<?php echo esc_url( $data['images_url'] . 'om-step-3.png' ); ?>"><img src="<?php echo esc_url( $data['images_url'] . 'om-step-3-sm.png' ); ?>"></a>
			<p><?php esc_html_e( '3. Connect your Constant Contact email list.', 'optin-monster-api' ); ?></p>
		</div>
		<div class="step4 step">
			<a href="<?php echo esc_url( $data['images_url'] . 'om-step-4.png' ); ?>"><img src="<?php echo esc_url( $data['images_url'] . 'om-step-4-sm.png' ); ?>"></a>
			<p><?php esc_html_e( '4. Sync your campaign to your WordPress site, then hit Go Live.', 'optin-monster-api' ); ?></p>
		</div>
	</div>
	<p><?php esc_html_e( 'It doesn&#39;t matter what kind of business you run, what kind of website you have, or what industry you are in - you need to start building your email list today.', 'optin-monster-api' ); ?></p>
	<p><?php esc_html_e( 'With Constant Contact + OptinMonster, growing your list is easy.', 'optin-monster-api' ); ?></p>
	<p>
		<a href="<?php echo esc_url( $data['signup_url'] ); ?>" target="_blank" rel="noopener noreferrer">
			<strong><?php esc_html_e( 'Try Constant Contact Today', 'optin-monster-api' ); ?></strong>
		</a>
	</p>
</div>
