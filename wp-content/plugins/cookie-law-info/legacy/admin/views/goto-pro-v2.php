<?php $assets_path = CLI_PLUGIN_URL . 'images/'; ?>
<style>
/************/

.text-center {
	text-align: center !important;
}

.mx-auto {
	margin-right: auto !important;
	margin-left: auto !important;
}

.my-0 {
	margin-top: 0 !important;
	margin-bottom: 0 !important;
}

.d-flex {
	display: flex !important;
}

.v-center {
	align-items: center !important;
}

.wt-white-wrapper {
	background-color: #fff;
	padding: 17px;
}

.px-10 {
	padding-left: 10px;
	padding-right: 10px;
}

.text-right {
	text-align: right !important;
}

.my-35 {
	margin-top: 35px;
	margin-bottom: 35px;
}

.wt-bg {
	padding: 10px;
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'bg.svg' );
	?>
	);
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;
}

h3.wt-sidebar-title {
	font-style: normal;
	font-weight: bold;
	font-size: 20px;
	line-height: 26px;
	text-align: center;
	color: #000000;
	margin-bottom: 20px;
}

.wt-primary-btn {
	background: #2D9FFF;
	border-radius: 7px;
	font-style: normal;
	font-weight: 600;
	font-size: 14px;
	line-height: 19px;
	padding: 10px 15px;
	color: #FFFFFF;
	display: inline-block;
	text-align: center;
	margin: 0 auto 30px auto;
}

.wt-secondary-btn {
	background: #FFFFFF;
	border: 2px solid #2D9FFF;
	border-radius: 13px;
	font-style: normal;
	font-weight: 700;
	font-size: 15px;
	line-height: 17px;
	color: #2D9FFF;
	padding: 12px 15px 12px 56px;
	display: inline-block;
}

.wt-primary-btn.crown-icon, .wt-secondary-btn.crown-icon {
	position: relative;
	padding-left: 52px;
	text-decoration: none;
	transition: all .2s ease;
}

.wt-primary-btn:hover,
.wt-secondary-btn:hover,
.wt-primary-btn:focus,
.wt-secondary-btn:focus {
	outline: none;
	text-decoration: none;
	transition: all .2s ease;
	transform: translateY(2px);
	box-shadow: none;
	opacity: .8;
	
}
.wt-primary-btn:hover {
	color: #fff;
}
.wt-secondary-btn:hover {
	color: #2D9FFF;
}
.wt-primary-btn.crown-icon:before,
.wt-secondary-btn.crown-icon:before {
	position: absolute;
	content: '';
	height: 19px;
	width: 23px;
	background-repeat: no-repeat;
	background-position: center;
	background-size: contain;
	left: 15px;
}

.wt-primary-btn.crown-icon:before {
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'white-crown.svg' );
	?>
	);
}

.wt-secondary-btn.crown-icon:before {
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'blue-crown.svg' );
	?>
	);

}

.wt-moneyback-support-wrapper {
	background: #E2F2FF;
	border-radius: 13px;
	padding: 17px 12px;
}

.wt-moneyback-support-wrapper>div {
	padding: 6px;
	width: 50%;
}

.wt-moneyback-support-wrapper img {
	filter: drop-shadow(0px 11px 21px rgba(34, 112, 177, 0.26));
	width: 36px;
	height: auto;
	margin-right: 10px;
}

.wt-moneyback-support-wrapper p {
	font-style: normal;
	font-weight: 600;
	font-size: 12px;
	line-height: 13px;
	color: #000000;
	margin: 0;
}

ul.wt-gdprpro-features {
	margin: 0;
	padding: 30px 25px;
}

.wt-gdprpro-features li {
	font-style: normal;
	font-weight: 400;
	font-size: 13px;
	line-height: 19px;
	color: #000000;
	list-style: none;
	margin: 0 0 30px 0;
	padding-left: 42px;
	position: relative;
}

.wt-gdprpro-features li b {
	font-weight: bold;
}

.wt-gdprpro-features li:before {
	content: '';
	position: absolute;
	height: 17px;
	width: 20px;
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'list-icon.svg' );
	?>
	);
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
	left: 0;
}

.wt-gdprpro-features li:last-child {
	margin-bottom: 0;
}

.wt-link {
	font-style: normal;
	font-weight: 600;
	font-size: 14px;
	line-height: 18px;
	text-decoration: underline;
	margin-top: 20px;
	color: #1093F2;
	display: inline-block;
	margin-bottom: 35px;
	cursor: pointer;
}

.wt-link:hover, .wt-link:focus {
	text-decoration: none;

}

.wt-free-pro-table {
	overflow-x: auto;
	border-radius: 7px;
	border: 0.5px solid #d3d3d3;
}

.wt-free-pro-table table {
	width: 100%;
	min-width: 400px;
	border-collapse: collapse;
}

.wt-free-pro-table table th {
	font-style: normal;
	font-weight: bold;
	font-size: 15px;
	line-height: 28px;
	color: #000000;
	padding: 15px;
	text-align: center;
}

.wt-free-pro-table table th {
	border-right: 0.5px solid #d3d3d3;
	border-bottom: 0.5px solid #d3d3d3;
	border-left: none;
	border-top: none;
}

.wt-free-pro-table table tr th:first-child {
	background-color: #F8F9FA;
}

.wt-free-pro-table table tr th:last-child {
	border-right: none;
}

.wt-free-pro-table table td {
	border-right: 0.5px solid #d3d3d3;
	border-bottom: 0.5px solid #d3d3d3;
	border-left: none;
	border-top: none;
	padding: 20px 40px;
	font-style: normal;
	font-weight: 600;
	font-size: 14px;
	line-height: 20px;
	color: #3A3A3A;
	text-align: center;
}

.wt-free-pro-table table td p.light {
	margin: 10px 0 0 0;
	font-style: normal;
	font-weight: 300;
	font-size: 14px;
	line-height: 20px;
}

.wt-free-pro-table table tr td:first-child {
	text-align: left;
	background-color: #F8F9FA;
}

.wt-free-pro-table table tr td:last-child {
	border-right: none;
}

.wt-free-pro-table table tr:last-child td {
	border-bottom: none;
}

.wt-free-pro-table table a {
	font-style: normal;
	font-weight: 600;
	font-size: 14px;
	line-height: 24px;
	text-decoration: underline;
	color: #2DB3FF;
}

.wt-free-pro-table table a:hover {
	text-decoration: none;
}

.wt-free-pro-table .wt-cli-badge {
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
	height: 20px;
	width: 20px;
	display: inline-block;
}

.wt-cli-badge.wt-cli-success {
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'tick.svg' );
	?>
	);
}

.wt-cli-badge.wt-cli-error {
	background-image: url(
	<?php
	echo esc_url( $assets_path . 'cross.svg' );
	?>
	);
}

.wt-colored-wrapper {
	background-color: #F5FAFF;
	display: inline-block;
	border-radius: 0 0 13px 13px;
}

</style>

<div class="wt-cli-sidebar" style="max-width: 365px;margin-top:45px">
	<div class="wt-white-wrapper">
		<div class="wt-bg">
		  <h3 class="wt-sidebar-title text-center"><?php echo esc_html( _e( 'Get access to advanced features for GDPR compliance', 'cookie-law-info' ) ); ?></h3>
		  <p class="text-center my-0"><a
			  href="https://www.webtoffee.com/product/gdpr-cookie-consent/?utm_source=free_plugin_sidebar&utm_medium=gdpr_basic&utm_campaign=GDPR&utm_content=<?php echo esc_attr( CLI_VERSION ); ?>"
			  class="wt-primary-btn crown-icon" target="_blank" style="text-transform:uppercase;"><?php echo esc_html( _e( 'Upgrade to premium', 'cookie-law-info' ) ); ?></a></p>

		</div>
		<div class="wt-moneyback-support-wrapper d-flex ">
		  <div class="wt-moneyback d-flex v-center">
			<img src="<?php echo esc_url( $assets_path . 'money-back.svg' ); ?>" alt="money back badge" height="36" width="36">
			<p><?php echo esc_html( __( '30 Day Money Back Guarantee', 'cookie-law-info' ) ); ?></p>
		  </div>
		  <div class="wt-support d-flex v-center">
			<img src="<?php echo esc_url( $assets_path . 'support.svg' ); ?>" alt="support badge" height="36" width="36">
			<p><?php echo esc_html( __( 'Fast and Priority Support', 'cookie-law-info' ) ); ?></p>
		  </div>
		</div>
		<ul class="wt-gdprpro-features">
		  <li><b><?php echo esc_html( __( 'Enhanced cookie scanning:', 'cookie-law-info' ) ); ?></b> <?php echo esc_html( __( 'Scan up to 2000 URLs in a go.', 'cookie-law-info' ) ); ?></li>
		  <li>
			<b><?php echo esc_html( __( 'Auto-block cookies from popular third-party services & plugins:', 'cookie-law-info' ) ); ?></b>                						  
						  <?php
							echo esc_html(
								__( 'Supports Google analytics, Facebook pixel, Google tag manager, Hotjar analytics, +20 more.', 'cookie-law-info' )
							);
							?>
			</li>
		  <li>
			<b><?php echo esc_html( __( 'Be consent proof ready:', 'cookie-law-info' ) ); ?></b>
						  <?php
							echo esc_html(
								__(
									'Keep a record of users who have given consent along with details such as cookie categories, user ID, time stamp, etc.',
									'cookie-law-info'
								)
							);
							?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Display cookie notice based on user location:', 'cookie-law-info' ) ); ?></b> <?php echo esc_html( __( 'Option to show cookie notice only to users from the EU.', 'cookie-law-info' ) ); ?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Show ‘Do not sell my personal information’ link only to users from california.', 'cookie-law-info' ) ); ?></b>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Multiple pre-built templates for cookie notice:', 'cookie-law-info' ) ); ?></b> 
						  <?php
							echo esc_html(
								__(
									'Choose from 26 pre-designed and customizable cookie notice templates.',
									'cookie-law-info'
								)
							);
							?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Live preview of cookie notice:', 'cookie-law-info' ) ); ?></b> <?php echo esc_html( __( 'Get live preview of cookie notice as and when you customize them.', 'cookie-law-info' ) ); ?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Disable ‘Powered by CookieYes’ branding:', 'cookie-law-info' ) ); ?></b> <?php echo esc_html( __( 'Remove CookieYes branding from cookie notices.', 'cookie-law-info' ) ); ?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Renew user consent:', 'cookie-law-info' ) ); ?></b> 
						  <?php
							echo esc_html(
								__(
									'Renew user consent when you update your privacy/cookie policy or when needed otherwise.',
									'cookie-law-info'
								)
							);
							?>
		  </li>
		  <li>

			<b><?php echo esc_html( __( 'Categorize personal data collecting cookies:', 'cookie-law-info' ) ); ?></b> 
						  <?php
							echo esc_html(
								__(
									'Categorize personal data collecting cookies for ‘Do not sell my personal information’ link.',
									'cookie-law-info'
								)
							);
							?>
		  </li>
		</ul>
		<p class="text-center my-0"><a
			href="https://www.webtoffee.com/product/gdpr-cookie-consent/?utm_source=free_plugin_sidebar&utm_medium=gdpr_basic&utm_campaign=GDPR&utm_content=<?php echo esc_attr( CLI_VERSION ); ?>"
			class="wt-secondary-btn crown-icon" target="_blank" style="text-transform:uppercase;"><?php echo esc_html( __( 'Upgrade to premium', 'cookie-law-info' ) ); ?></a></p>
		<p class="text-center my-0"> <a id="cky-table-comparison-link" class="wt-link"><?php echo esc_html( __( 'Compare Free and Premium', 'cookie-law-info' ) ); ?></a></p>
	  </div>

</div>
