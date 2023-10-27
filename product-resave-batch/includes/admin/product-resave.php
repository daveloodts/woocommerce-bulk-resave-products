<?php
/**
 * Product Resave HTML
 *
 * @since 1.0.0
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$redirect_url = add_query_arg( array( 'page' => 'product-resave-batch' ), admin_url('admin.php') );
?>

<div class="wrap blueparrot-product-resave-wrp">

	<h2><?php esc_html_e( 'Product Resave - Settings', 'product-resave-in-batches' ); ?></h2>

	<div class="metabox-holder">
		<div class="post-box-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox blueparrot-no-toggle">

					<div class="postbox-header">
						<h3 class="hndle">
							<span><?php esc_html_e( 'Product Resave', 'product-resave-in-batches' ); ?></span>
						</h3>
					</div>

					<div class="inside blueparrot-peoduct-resave-inr">

						<p><?php esc_html_e('Click the button below to start resaving products in batches:', 'product-resave-in-batches'); ?></p>

						<form id="blueparrot-product-resave-form" class="blueparrot-product-resave-form" method="post" action="">

							<span class="blueparrot-submit-wrp">
								<input type="submit" value="<?php esc_html_e('Start Resaving Products', 'product-resave-in-batches'); ?>" class="button button-secondary blueparrot-submit" />
							</span>

							<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'blueparrot-product-resave' ); ?>" />
							<input type="hidden" name="redirect_url" value="<?php echo esc_url( $redirect_url ); ?>" />

							<div class="blueparrot-product-update-result-wrp">
								<p><?php esc_html_e('To keep things running smoothly. The product save process runs in the background and may take a little while, so please be patient.', 'product-resave-in-batches'); ?></p>
								<p><?php esc_html_e('Product save process has been started.', 'product-resave-in-batches'); ?></p>
								<p style="color:red;"><?php esc_html_e('Kindly do not refresh the page or close the browser.', 'product-resave-in-batches'); ?></p>
							</div>
						</form><!-- end .blueparrot-peoduct-resave-inr -->
					</div><!-- end .inside -->
				</div><!-- end .postbox -->
			</div>
		</div><!-- end .post-box-container -->
	</div><!-- end .metabox-holder -->
</div><!-- end .wrap -->