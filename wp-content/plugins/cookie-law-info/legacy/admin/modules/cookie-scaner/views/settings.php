<?php
/**
 * Scanner default view
 *
 * @package Cookie_Law_Info_Cookie_Scaner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
	.wt-cli-admin-notice-wrapper {
		display: flex;
		justify-content: space-between;
		padding: 5px 10px;
		align-items: center;
		flex-wrap:wrap;
	}
	.wt-cli-admin-notice-wrapper ul {
		list-style: disc;
		padding-left: 15px;
	}
	.wt-cli-notice-actions {
		padding: 10px 0;
	}
	.wt-cli-cookie-scan-bar {
		display: flex;
		flex-wrap: wrap;
		margin: 15px 0;
	}
	.wt-cli-ckyes-form-email-verify h4 {
		font-size: 15px;
		margin: 0 0 10px 0;
	}
	.wt-cli-ckyes-form-email-verify > div {
		margin-top: 15px;
	}
	.wt-cli-ckyes-account-widget-container > span {
		margin-right: 5px;
	}
	.wt-cli-ckyes-account-widget-container {
		display: flex;
		align-items:center;
	}
	span.wt-cli-ckyes-status-icon {
		width: 15px;
		height: 15px;
	}
	.wt-cli-cookie-scan-container .wt-cli-callout:before {
		top: 30px;
		left: 20px;
	}
	.wt-scan-status-info {
		margin: 20px 0;
		width:100%;
	}
	.wt-scan-status-info-item {
		margin-bottom: 5px;
	}
	.wt-cli-cookie-scanner-actions {
		width: 100%;
		display: flex;
		justify-content: flex-end;
		margin-bottom:15px;
	}
	.wt-cli-cookie-scan-container {
		width: 100%;
	}
	ul.wt-cli-cookie-scan-feature-list li {
		list-style: none;
	}
	ul.wt-cli-cookie-scan-feature-list li:before {
		content: '✓';
		margin-right: 10px;
		color: #64b450;
		font-weight:600;
	}
	.wt-cli-cookie-scan-features p {
		font-weight: 600;
	}
	.wt-cli-scan-status-container {
		width:100%;
	}
	.wt-cli-scan-status-container .spinner {
		float: left;
		background-size: 15px 15px;
		width: 15px;
		height: 15px;
		margin-top: 2px;
		margin-left: 0;
	}
	#wt-cli-ckyes-email-resend-link {
		cursor: pointer;
	}
	#wt-cli-ckyes-modal-settings-preview {
		width: 687px;
		padding-top: 30px;
	}
	#wt-cli-ckyes-modal-settings-preview .wt-cli-modal-body {
		text-align: center;
		border: 1px solid #f1f1f1;
	}
	.wt-cli-cookie-scan-results-container {
		background: #fff;
		border-radius: 3px;
		width:100%;
	}
	.wt-cli-scan-results-body {
		padding: 15px;
	}
	.wt-cli-scan-result-header {
		padding: 5px 15px;
		border-bottom: 1px solid #efeeee;
	}
	ul.wt-cli-scan-result-summary-list {
	display: flex;
}
.wt-cli-scan-result-summary-list li {
	background-color: #eff7ed;
	color: #000;
	margin: 0px 15px 0px 0px;
	padding: 5px 14px;
	border-width: 2px;
	border-style: solid;
	border-color: transparent;
	border-radius: 4px;
}
.wt-cli-scan-result-actions {
	display: flex;
	align-items: center;
	justify-content: flex-end;
}
table.wt-cli-table td,table.wt-cli-table th {
	font-size: 13px;
	line-height: 1.5em;
}
table.wt-cli-table th {
	font-size: 1.05em;
	text-align: left;
	padding: 15px 15px;
	
}
table.wt-cli-table td {
	border-top: 1px solid #E6E6E6;
	padding: 15px 15px;
}
table.wt-cli-table {
	border: 1px solid #E6E6E6;
	border-spacing: 0;
	width: 100%;
	clear: both;
	margin: 0;
	
}
.wt-cli-cookie-scan-bar .wt-cli-callout.wt-cli-callout-success {
	background: #fff;
	border-radius: 3px;
}
.wt-cli-scan-result-summary {
	justify-content: space-between;
	display: flex;
}
.wt-cli-scan-result-import-section p {
	font-weight: 500;
}
.wt-cli-scan-result-import-section {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 10px 0 15px 0;
}
.wt-cli-scan-result-actions .button-primary {
	color: #0071a1;
	border-color: #0071a1;
	background: #f3f5f6;
}
.wt-cli-cookie-scan-notice {
	width:100%;
}
.wt-cli-cookie-scan-features-section .wt-cli-callout.wt-cli-callout-success {
	background: #eff7ed;
}
.wt-cli-inline-notice {
	padding: 15px 0;
}
.wt-cli-inline-notice.wt-cli-inline-notice-error {
	color: #ff0000;
}
.wt-cli-inline-notice.wt-cli-inline-notice-success {
	color: #3e9429;
}
</style>
<div class="wrap">
	<h2><?php echo esc_html__( 'Cookie scanner', 'cookie-law-info' ); ?></h2> 
	<?php do_action( 'wt_cli_before_cookie_scanner_header' ); ?>
	<div class="wt-cli-cookie-scan-bar">
		<div class="wt-cli-cookie-scan-notice">
			<?php do_action( 'wt_cli_cookie_scanner_body' ); ?>
		</div>
	</div>
</div>
