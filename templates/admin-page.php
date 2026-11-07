<?php
/**
 * Master Admin Page Template for Business Setup
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_name = get_bloginfo( 'name' );
?>

<div class="wrap wbs-wrap">

	<?php if ( isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) : ?>
		<div class="notice notice-success is-dismissible" style="margin-top: 15px; margin-bottom: 20px;">
			<p><strong>Settings Saved Successfully.</strong> Your business configuration has been updated in the options table.</p>
		</div>
	<?php endif; ?>

	<?php 
	$validation_errors = get_transient( 'wbs_validation_errors_' . get_current_user_id() );
	if ( ! empty( $validation_errors ) && is_array( $validation_errors ) ) :
		delete_transient( 'wbs_validation_errors_' . get_current_user_id() );
	?>
		<div class="notice notice-error is-dismissible" style="margin-top: 15px; margin-bottom: 20px;">
			<p><strong>Security &amp; Validation Notice:</strong> Some inputs failed verification and were blocked from saving:</p>
			<ul style="list-style: disc; margin-left: 20px;">
				<?php foreach ( $validation_errors as $err ) : ?>
					<li><?php echo esc_html( $err ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="wbs-header">
		<div>
			<h1>
				<span class="dashicons dashicons-building"></span>
				<?php echo esc_html( $site_name ); ?> &mdash; Business Setup
			</h1>
			<p>Configure centralized business identity, branding, contacts, hours, social links, headers, footers &amp; schema markup.</p>
		</div>
		<div>
			<button type="submit" form="wbs-main-form" class="wbs-save-btn">
				<span class="dashicons dashicons-saved"></span> Save All Changes
			</button>
		</div>
	</div>

	<!-- Tabs Navigation -->
	<nav class="wbs-tabs-nav" aria-label="Business Setup Tabs">
		<button type="button" class="wbs-tab-btn active" data-tab="general">
			<span class="dashicons dashicons-admin-generic"></span> General
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="branding">
			<span class="dashicons dashicons-format-image"></span> Branding
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="contact">
			<span class="dashicons dashicons-phone"></span> Contact
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="social-media">
			<span class="dashicons dashicons-share"></span> Social Media
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="business-hours">
			<span class="dashicons dashicons-clock"></span> Business Hours
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="legal-documents">
			<span class="dashicons dashicons-media-document"></span> Legal Documents
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="legal-codes">
			<span class="dashicons dashicons-id-alt"></span> Legal Codes
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="header-footer">
			<span class="dashicons dashicons-layout"></span> Header &amp; Footer
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="theme-options">
			<span class="dashicons dashicons-admin-appearance"></span> Theme Options
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="analytics">
			<span class="dashicons dashicons-chart-line"></span> Analytics
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="uses-shortcodes">
			<span class="dashicons dashicons-shortcode"></span> Uses &amp; Shortcodes
		</button>
		<button type="button" class="wbs-tab-btn" data-tab="settings">
			<span class="dashicons dashicons-admin-settings"></span> Settings
		</button>
	</nav>

	<!-- Main Settings Form -->
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="wbs-main-form">
		<input type="hidden" name="action" value="wbs_save_options" />
		<?php wp_nonce_field( 'wbs_save_options_nonce', 'wbs_nonce' ); ?>

		<!-- Tab 1: General -->
		<div class="wbs-tab-pane active" id="tab-general">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-general.php'; ?>
		</div>

		<!-- Tab 2: Branding -->
		<div class="wbs-tab-pane" id="tab-branding">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-branding.php'; ?>
		</div>

		<!-- Tab 3: Contact -->
		<div class="wbs-tab-pane" id="tab-contact">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-contact.php'; ?>
		</div>

		<!-- Tab 4: Social Media -->
		<div class="wbs-tab-pane" id="tab-social-media">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-social-media.php'; ?>
		</div>

		<!-- Tab 5: Business Hours -->
		<div class="wbs-tab-pane" id="tab-business-hours">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-business-hours.php'; ?>
		</div>

		<!-- Tab 6: Legal Documents -->
		<div class="wbs-tab-pane" id="tab-legal-documents">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-legal-documents.php'; ?>
		</div>

		<!-- Tab 7: Legal Codes -->
		<div class="wbs-tab-pane" id="tab-legal-codes">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-legal-codes.php'; ?>
		</div>

		<!-- Tab 8: Header and Footer -->
		<div class="wbs-tab-pane" id="tab-header-footer">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-header-footer.php'; ?>
		</div>

		<!-- Tab 9: Theme Options -->
		<div class="wbs-tab-pane" id="tab-theme-options">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-theme-options.php'; ?>
		</div>

		<!-- Tab 10: Analytics -->
		<div class="wbs-tab-pane" id="tab-analytics">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-analytics.php'; ?>
		</div>

		<!-- Tab 11: Uses & Shortcodes -->
		<div class="wbs-tab-pane" id="tab-uses-shortcodes">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-uses-shortcodes.php'; ?>
		</div>

		<!-- Tab 12: Settings -->
		<div class="wbs-tab-pane" id="tab-settings">
			<?php include WP_BUSINESS_SETUP_DIR . 'templates/tab-settings.php'; ?>
		</div>

		<!-- Sticky Bottom Action Bar -->
		<div class="wbs-bottom-bar">
			<span>Changes are saved directly into the WordPress options table.</span>
			<button type="submit" class="wbs-save-btn">
				<span class="dashicons dashicons-saved"></span> Save All Changes
			</button>
		</div>

	</form>

</div>
