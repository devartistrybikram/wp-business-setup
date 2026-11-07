<?php
/**
 * Tab: Legal Documents
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$legal_docs = isset( $options['Legal_Documents'] ) ? $options['Legal_Documents'] : array();

$doc_labels = array(
	'Terms_And_Conditions'        => 'Terms & Conditions URL',
	'Privacy_Policy'              => 'Privacy Policy URL',
	'Refund_An_ Cancellation'      => 'Refund & Cancellation Policy URL',
	'Cookie_Policy'               => 'Cookie Policy URL',
	'Shipping_And_Delivery Policy' => 'Shipping & Delivery Policy URL',
	'Return_Policy'               => 'Return Policy URL',
	'Accessibility_Statement'     => 'Accessibility Statement URL',
	'Data_Processing_Policy'      => 'Data Processing Agreement / GDPR URL',
	'Acceptable_Use_Policy'       => 'Acceptable Use Policy URL',
	'Copyright_Policy'            => 'Copyright & IP Policy URL',
	'Disclaimer'                  => 'Disclaimer / Legal Notice URL',
	'Sitemap'                     => 'XML Sitemap URL',
);

// Fetch published pages for quick selection
$all_pages = get_pages( array( 'post_status' => 'publish' ) );
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-media-document"></span> Legal Documents &amp; Policy Pages</h2>
			<p>Official links to regulatory compliance, consumer rights, and terms of service.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-2">
			<?php foreach ( $doc_labels as $key => $label ) : 
				$current_url = $legal_docs[ $key ] ?? '';
			?>
				<div class="wbs-field-group">
					<label for="wbs_doc_<?php echo esc_attr( sanitize_title( $key ) ); ?>"><?php echo esc_html( $label ); ?></label>
					<div style="display: flex; gap: 8px;">
						<input type="url" id="wbs_doc_<?php echo esc_attr( sanitize_title( $key ) ); ?>" name="Legal_Documents[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_url( $current_url ); ?>" placeholder="https://..." style="flex: 1;" />
						
						<?php if ( ! empty( $all_pages ) ) : ?>
							<select style="max-width: 140px;" onchange="if(this.value){ document.getElementById('wbs_doc_<?php echo esc_attr( sanitize_title( $key ) ); ?>').value = this.value; }">
								<option value="">Insert Page</option>
								<?php foreach ( $all_pages as $p ) : ?>
									<option value="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><?php echo esc_html( $p->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
