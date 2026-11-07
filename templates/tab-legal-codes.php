<?php
/**
 * Tab: Legal Codes & Business Registration Identifiers
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$codes = isset( $options['Legal_Codes'] ) ? $options['Legal_Codes'] : array();

$standard_codes = array(
	'LEGAL NAME'          => 'Registered Legal Entity Name',
	'TRADE NAME'          => 'Doing Business As (DBA) / Trade Name',
	'IDENTITY CODE'       => 'Company Corporate Identity Code',
	'REGISTRATION NUMBER' => 'Business Registration / Incorporation Number',
	'GST'                 => 'GST Identification Number (GSTIN)',
	'PAN'                 => 'Permanent Account Number (PAN)',
	'CIN'                 => 'Corporate Identification Number (CIN)',
	'UDYAM'               => 'MSME / Udyam Registration Number',
	'DUNS'                => 'Dun & Bradstreet Number (DUNS)',
	'EIN'                 => 'Federal Employer Identification Number (EIN)',
	'TAN'                 => 'Tax Deduction & Collection Account Number (TAN)',
	'IEC'                 => 'Import Export Code (IEC)',
	'FSSAI'               => 'Food Safety License Number (FSSAI)',
	'LEI'                 => 'Legal Entity Identifier (LEI)',
	'EORI'                => 'Economic Operators Registration and ID (EORI)',
	'ISO Code'            => 'ISO Quality Certification Standard Code',
);

$other_codes = array();
if ( ! empty( $codes['Other'] ) && is_array( $codes['Other'] ) ) {
	$other_codes = $codes['Other'];
} else {
	for ( $i = 1; $i <= 5; $i++ ) {
		if ( isset( $codes[ 'Other_' . $i ] ) && ! empty( $codes[ 'Other_' . $i ] ) ) {
			$other_codes[] = array(
				'label' => 'Other ' . $i,
				'value' => $codes[ 'Other_' . $i ],
			);
		}
	}
}
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-id-alt"></span> Regulatory &amp; Tax Registration Numbers</h2>
			<p>Official tax IDs, corporate numbers, and global compliance identifiers.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-2">
			<?php foreach ( $standard_codes as $code_key => $description ) : 
				$code_val = $codes[ $code_key ] ?? '';
			?>
				<div class="wbs-field-group">
					<label for="wbs_code_<?php echo esc_attr( sanitize_title( $code_key ) ); ?>"><?php echo esc_html( $code_key ); ?></label>
					<input type="text" id="wbs_code_<?php echo esc_attr( sanitize_title( $code_key ) ); ?>" name="Legal_Codes[<?php echo esc_attr( $code_key ); ?>]" value="<?php echo esc_attr( $code_val ); ?>" placeholder="<?php echo esc_attr( $description ); ?>" />
					<span class="wbs-field-hint"><?php echo esc_html( $description ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<!-- Dynamic Other Legal Codes -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-plus-alt2"></span> Custom / Other Registration Numbers</h2>
			<p>Click "Add Other" to dynamically declare jurisdiction-specific licenses, municipal permits, or certifications.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-repeater-container">
			<div id="wbs-other-Legal_Codes">
				<?php if ( ! empty( $other_codes ) ) : ?>
					<?php foreach ( $other_codes as $idx => $item ) : 
						$label = is_array( $item ) ? ( $item['label'] ?? '' ) : 'Other ' . ( $idx + 1 );
						$value = is_array( $item ) ? ( $item['value'] ?? '' ) : $item;
					?>
						<div class="wbs-other-row">
							<input type="text" name="Legal_Codes[Other][<?php echo esc_attr( $idx ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" placeholder="License / Identifier Name" class="wbs-other-label" />
							<input type="text" name="Legal_Codes[Other][<?php echo esc_attr( $idx ); ?>][value]" value="<?php echo esc_attr( $value ); ?>" placeholder="Identifier / Registration Value" class="wbs-other-value" />
							<button type="button" class="wbs-btn-delete-other wbs-btn-delete-row dashicons dashicons-trash" title="Remove"></button>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<button type="button" class="wbs-btn-add-row wbs-btn-add-other" data-target="Legal_Codes">
				<span class="dashicons dashicons-plus-alt2"></span> Add Other Legal Code
			</button>
		</div>
	</div>
</div>
