<?php
/**
 * Marketing Card CSS for WooCommerce Marketing Page
 *
 * @since 2.2.0
 */
?>
<style>
	.components-card-om {
		background: #fff;
		border: 1px solid rgb(226, 228, 231);
		border-radius: 3px;
		box-sizing: border-box;
		margin-bottom: 24px;
		position: relative;
	}

	.components-card-om * {
		box-sizing: border-box;
	}

	.components-card-om-header {
		display: flex;
		-webkit-box-align: center;
		align-items: center;
		-webkit-box-pack: justify;
		justify-content: space-between;
		border-bottom: 1px solid rgb(226, 228, 231);
		border-top-left-radius: 3px;
		border-top-right-radius: 3px;
		padding: 16px 24px;
	}

	.components-card-om-header p {
		margin-right: 0;
		margin: 0px;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		font-weight: 400;
		font-size: 20px;
		line-height: 28px;
	}

	.components-card-om-body {
		border-bottom: 1px solid #e0e0e0;
		display: flex;
		align-items: center;
		padding: 18px 24px;
	}

	.components-card-om-body-icon {
		position: relative;
		overflow: hidden;
		width: 36px;
		height: 36px;
		flex-basis: 36px;
		min-width: 36px;
	}

	.components-card-om-body-icon svg {
		margin-top: 2px;
		width: 36px;
		height: auto;
	}

	.components-card-om-body-text-wrap {
		flex-wrap: wrap;
		display: flex;
		align-items: center;
		flex-grow: 2;
		min-width: 0;
		padding: 0 14px;
	}

	.components-card-om-body-text h4 {
		font-weight: 400;
		font-size: 16px;
		margin: 0 0 5px;
		color: #1e1e1e;
	}

	.components-card-om-body-text p {
		color: #757575;
		margin: 0;
		max-width: 550px;
	}

	.components-card-om-body-text p:hover {
		color: #1e1e1e;
	}

</style>
