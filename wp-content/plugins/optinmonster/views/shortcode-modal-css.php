<style type="text/css">
	.om-modal-open-monsterlink #wp-link-wrap {
		visibility: hidden;
	}

	.optin-monster-modal-inline .optin-monster-modal-monsterlink-item {
		display: none !important;
	}

	.optin-monster-modal-monsterlink .optin-monster-modal-inline-item {
		display: none !important;
	}

	.optin-monster-insert-campaign-button .optin-monster-menu-icon {
		font-size:16px;
		margin-top:-2px;
		background-repeat: no-repeat;
		background-position: center;
		background-size: 18px auto;
	}

	#optin-monster-modal-wrap {
		display: none;
		background-color: #fff;
		-webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
		box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
		width: 578px;
		height: 285px;
		overflow: hidden;
		margin-left: -250px;
		margin-top: -125px;
		position: fixed;
		top: 50%;
		left: 50%;
		z-index: 100205;
		-webkit-transition: height 0.2s, margin-top 0.2s;
		transition: height 0.2s, margin-top 0.2s;
	}

	#optin-monster-modal-backdrop {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		min-height: 360px;
		background: #000;
		opacity: 0.7;
		filter: alpha(opacity=70);
		z-index: 100200;
	}

	#optin-monster-modal {
		position: relative;
		height: 100%;
	}

	#optin-monster-modal-title {
		background: #fcfcfc;
		border-bottom: 1px solid #dfdfdf;
		height: 36px;
		font-size: 18px;
		font-weight: 600;
		line-height: 36px;
		padding: 0 36px 0 16px;
		top: 0;
		right: 0;
		left: 0;
	}

	#optin-monster-modal-close {
		color: #666;
		padding: 0;
		position: absolute;
		top: 0;
		right: 0;
		width: 36px;
		height: 36px;
		text-align: center;
		background: none;
		border: none;
		cursor: pointer;
	}

	#optin-monster-modal-close:before {
		font: normal 20px/36px 'dashicons';
		vertical-align: top;
		speak: none;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		width: 36px;
		height: 36px;
		content: '\f158';
	}

	#optin-monster-modal-close:hover,
	#optin-monster-modal-close:focus {
		color: #2ea2cc;
	}

	#optin-monster-modal-close:focus {
		outline: none;
		-webkit-box-shadow: 0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
		box-shadow: 0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
	}

	#optin-monster-modal-inner {
		padding: 0 16px 50px;
	}

	#optin-monster-modal-search-toggle:after {
		display: inline-block;
		font: normal 20px/1 'dashicons';
		vertical-align: top;
		speak: none;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		content: '\f140';
	}

	.optin-monster-modal-notice {
		background-color: #d9edf7;
		border: 1px solid #bce8f1;
		color: #31708f;
		padding: 10px;
		margin: 0;
	}

	#optin-monster-modal #optin-monster-modal-options {
		display: flex;
		flex-direction: column;
		justify-content: center;
		height: 200px;
	}

	#optin-monster-modal #optin-monster-modal-options p {
		margin: 0 0 20px;
	}

	#optin-monster-modal #optin-monster-modal-options #om-monsterlink-upgrade {
		font-size: 17px;
		text-align: center;
	}

	#optin-monster-modal #optin-monster-modal-options .optin-monster-modal-inline {
		display: inline-block;
		margin: 0;
		padding: 0 20px 0 0;
	}

	#optin-monster-modal-select-inline-campaign,
	#optin-monster-modal-select-campaign {
		margin-bottom: 20px;
		width: 100%;
		max-width: 100%;
	}

	#optin-monster-modal .submitbox {
		padding: 8px 16px;
		background: #fcfcfc;
		border-top: 1px solid #dfdfdf;
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
	}

	#optin-monster-modal-cancel {
		line-height: 25px;
		float: left;
	}

	#optin-monster-modal-update {
		line-height: 23px;
		float: right;
	}

	#optin-monster-modal-submit,
	#optin-monster-modal-submit-inline {
		float: right;
		margin-bottom: 0;
	}

	@media screen and ( max-width: 782px ) {
		#optin-monster-modal-wrap {
			height: 280px;
			margin-top: -140px;
		}

		#optin-monster-modal-inner {
			padding: 0 16px 60px;
		}

		#optin-monster-modal-cancel {
			line-height: 32px;
		}
	}

	@media screen and ( max-width: 520px ) {
		#optin-monster-modal-wrap {
			width: auto;
			margin-left: 0;
			left: 10px;
			right: 10px;
			max-width: 578px;
		}
	}

	@media screen and ( max-height: 520px ) {
		#optin-monster-modal-wrap {
			-webkit-transition: none;
			transition: none;
		}
	}

	@media screen and ( max-height: 290px ) {
		#optin-monster-modal-wrap {
			height: auto;
			margin-top: 0;
			top: 10px;
			bottom: 10px;
		}

		#optin-monster-modal-inner {
			overflow: auto;
			height: -webkit-calc(100% - 92px);
			height: calc(100% - 92px);
			padding-bottom: 2px;
		}
	}

	#wp-link-wrap.wp-core-ui {
	    height: 555px;
	}

	#om-link-campaign {
		margin-top: 5px;
		width: 70%;
	}

	.mce-container .wp-media-buttons-icon.optin-monster-menu-icon svg {
		height: 20px;
		color: #595959;
	}

	.om-monsterlink-upgrade {
		color: #646970;
		margin: 0 0 0 10px;
		font-size: 12px;
	}
</style>
