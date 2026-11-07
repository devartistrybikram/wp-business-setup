<?php
/**
 * Tab: Settings (Import & Export JSON)
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-database-export"></span> Data Management &amp; Settings</h2>
			<p>Export all business configuration as JSON or import an existing configuration file.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<div class="wbs-grid-2">
			<!-- Export Box -->
			<div class="wbs-io-box">
				<h3><span class="dashicons dashicons-download"></span> Export Configuration</h3>
				<p>Download complete business setup data including branding, contacts, social media, operating hours, header/footer styles, and schema mappings as a standardized JSON file.</p>
				<button type="button" class="wbs-btn-action export" id="wbs-btn-export-json">
					<span class="dashicons dashicons-download"></span> Export Settings as JSON
				</button>
			</div>

			<!-- Import Box -->
			<div class="wbs-io-box">
				<h3><span class="dashicons dashicons-upload"></span> Import Configuration</h3>
				<p>Select a valid <code>.json</code> file previously exported from this system. Current configuration will be replaced with imported values.</p>
				<div style="margin-bottom: 14px;">
					<input type="file" id="wbs-import-file" accept=".json,application/json" />
				</div>
				<button type="button" class="wbs-btn-action import" id="wbs-btn-import-json">
					<span class="dashicons dashicons-upload"></span> Import JSON File
				</button>
			</div>
		</div>

		<!-- Reset Box -->
		<div class="wbs-io-box" style="margin-top: 10px; border-color: #fecdd3; background: #fff5f5;">
			<h3 style="color: #991b1b;"><span class="dashicons dashicons-warning"></span> Reset Configuration to Factory Defaults</h3>
			<p style="color: #7f1d1d;">Caution: This action will restore all tabs, contacts, and options to initial blank/default schema state. This action cannot be undone.</p>
			<button type="button" class="wbs-btn-action reset" id="wbs-btn-reset-defaults">
				<span class="dashicons dashicons-trash"></span> Reset All Settings to Defaults
			</button>
		</div>

		<!-- Developer API Reference -->
		<div style="margin-top: 24px; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
			<h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #0f172a;">Developer Integration Reference</h4>
			<p style="font-size: 12px; color: #64748b; margin-bottom: 8px;">All data is persistently stored inside the <code>wp_options</code> table under the option key <code>business_setup_options</code>. You can retrieve it in theme templates or plugins:</p>
			<pre style="background: #0f172a; color: #e2e8f0; padding: 12px; border-radius: 6px; font-size: 12px; overflow-x: auto; margin: 0;">// Retrieve all options:
$business = get_option( 'business_setup_options' );

// Retrieve specific helper value:
$phone = wbs_get_option( 'Contact', 'phone' );
$logo  = wbs_get_option( 'Branding', 'Primary Logo' );</pre>
		</div>

	</div>
</div>
