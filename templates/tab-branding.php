<?php
/**
 * Tab: Branding Settings (WordPress Media Uploader for all fields)
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$branding = isset( $options['Branding'] ) ? $options['Branding'] : array();

$branding_fields = array(
	'Primary Logo' => array(
		'label' => 'Primary Brand Logo',
		'desc'  => 'Main full logo displayed across standard headers and documents.',
	),
	'Short Logo'   => array(
		'label' => 'Short / Icon Logo (Favicon / App Icon)',
		'desc'  => 'Compact mark, square icon, or symbol for mobile headers and bookmarks.',
	),
	'White Logo'   => array(
		'label' => 'White / Inverted Logo (For Dark Backdrops)',
		'desc'  => 'Light-colored logo variant used on dark navigation bars and footers.',
	),
	'Black Logo'   => array(
		'label' => 'Black / Monochrome Logo (For Light Backdrops)',
		'desc'  => 'High-contrast dark variant for clean, minimal monochrome displays.',
	),
	'Banner Image' => array(
		'label' => 'Social Share / Hero Banner Image',
		'desc'  => 'OpenGraph / Twitter card default banner image (recommended 1200x630px).',
	),
	'Banner File'  => array(
		'label' => 'Brand Vector Asset / PDF File',
		'desc'  => 'Vector source package, brand guidelines PDF, or press kit asset file.',
	),
);
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-format-image"></span> Branding &amp; Media Assets</h2>
			<p>Upload or select files directly from your WordPress Media Library.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-2">
			<?php foreach ( $branding_fields as $key => $meta ) : 
				$current_val = $branding[ $key ] ?? '';
				$is_image    = ! empty( $current_val ) && preg_match( '/\.(jpg|jpeg|png|gif|webp|svg)$/i', $current_val );
			?>
				<div class="wbs-field-group">
					<label><?php echo esc_html( $meta['label'] ); ?></label>
					<div class="wbs-media-field" data-field="<?php echo esc_attr( $key ); ?>">
						<div class="wbs-media-preview">
							<?php if ( ! empty( $current_val ) ) : ?>
								<?php if ( $is_image ) : ?>
									<img src="<?php echo esc_url( $current_val ); ?>" alt="<?php echo esc_attr( $key ); ?>" />
								<?php else : ?>
									<div class="wbs-media-placeholder">
										<span class="dashicons dashicons-media-document"></span>
										<span><?php echo esc_html( basename( $current_val ) ); ?></span>
									</div>
								<?php endif; ?>
							<?php else : ?>
								<div class="wbs-media-placeholder">
									<span class="dashicons dashicons-format-image"></span>
									<span>No media selected</span>
								</div>
							<?php endif; ?>
						</div>

						<input type="hidden" class="wbs-media-input" name="Branding[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $current_val ); ?>" />

						<div class="wbs-media-actions">
							<button type="button" class="wbs-btn-upload">
								<span class="dashicons dashicons-upload"></span> Select File
							</button>
							<button type="button" class="wbs-btn-remove" style="<?php echo empty( $current_val ) ? 'display:none;' : ''; ?>">
								Remove
							</button>
						</div>
					</div>
					<span class="wbs-field-hint"><?php echo esc_html( $meta['desc'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
