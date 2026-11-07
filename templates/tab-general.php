<?php
/**
 * Tab: General Settings
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$general = isset( $options['General'] ) ? $options['General'] : array();
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-admin-generic"></span> General Information</h2>
			<p>Basic corporate and identity details about your business.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<div class="wbs-grid-2">
			<div class="wbs-field-group">
				<label for="wbs_business_name">Business Name</label>
				<input type="text" id="wbs_business_name" name="General[Business_Name]" value="<?php echo esc_attr( $general['Business_Name'] ?? '' ); ?>" placeholder="e.g. Acme Corporation" />
			</div>

			<div class="wbs-field-group">
				<label for="wbs_alternate_name">Alternate Name / DBA</label>
				<input type="text" id="wbs_alternate_name" name="General[Alternate_Name]" value="<?php echo esc_attr( $general['Alternate_Name'] ?? '' ); ?>" placeholder="e.g. Acme Inc." />
			</div>
		</div>

		<div class="wbs-grid-2">
			<div class="wbs-field-group">
				<label for="wbs_business_tagline">Business Tagline</label>
				<input type="text" id="wbs_business_tagline" name="General[Business_Tagline]" value="<?php echo esc_attr( $general['Business_Tagline'] ?? '' ); ?>" placeholder="e.g. Innovating the digital tomorrow" />
			</div>

			<div class="wbs-field-group">
				<label for="wbs_business_type">Business Type</label>
				<select id="wbs_business_type" name="General[Business_Type]">
					<?php
					$types = array(
						'Corporation',
						'LLC',
						'Sole Proprietorship',
						'Partnership',
						'Non-Profit Organization',
						'Local Business',
						'Online Store / E-commerce',
						'Educational Institution',
						'Freelancer / Agency'
					);
					$current_type = $general['Business_Type'] ?? 'Corporation';
					foreach ( $types as $type ) {
						printf(
							'<option value="%1$s" %2$s>%1$s</option>',
							esc_attr( $type ),
							selected( $current_type, $type, false )
						);
					}
					?>
				</select>
			</div>
		</div>

		<div class="wbs-field-group">
			<label for="wbs_business_description">Business Description</label>
			<textarea id="wbs_business_description" name="General[Business_Description]" rows="4" placeholder="Comprehensive summary of what your business does..."><?php echo esc_textarea( $general['Business_Description'] ?? '' ); ?></textarea>
		</div>

		<div class="wbs-grid-3">
			<div class="wbs-field-group">
				<label for="wbs_website_url">Website URL</label>
				<input type="url" id="wbs_website_url" name="General[Website_URL]" value="<?php echo esc_url( $general['Website_URL'] ?? home_url() ); ?>" placeholder="https://example.com" />
			</div>

			<div class="wbs-field-group">
				<label for="wbs_founded_year">Founded Year</label>
				<input type="number" id="wbs_founded_year" name="General[Founded_Year]" min="1800" max="<?php echo esc_attr( date( 'Y' ) ); ?>" value="<?php echo esc_attr( $general['Founded_Year'] ?? '' ); ?>" placeholder="e.g. 2018" />
			</div>

			<div class="wbs-field-group">
				<label for="wbs_founder_name">Founder Name</label>
				<input type="text" id="wbs_founder_name" name="General[Founder_Name]" value="<?php echo esc_attr( $general['Founder_Name'] ?? '' ); ?>" placeholder="e.g. Jane Doe" />
			</div>
		</div>

		<div class="wbs-grid-3">
			<div class="wbs-field-group">
				<label for="wbs_number_of_employees">Number of Employees</label>
				<input type="number" id="wbs_number_of_employees" name="General[Number_of_Employees]" min="1" value="<?php echo esc_attr( $general['Number_of_Employees'] ?? '' ); ?>" placeholder="e.g. 25" />
			</div>

			<div class="wbs-field-group">
				<label for="wbs_currency">Currency</label>
				<select id="wbs_currency" name="General[Currency]">
					<?php
					$currencies = array(
						'USD' => 'USD - US Dollar ($)',
						'EUR' => 'EUR - Euro (€)',
						'GBP' => 'GBP - British Pound (£)',
						'INR' => 'INR - Indian Rupee (₹)',
						'CAD' => 'CAD - Canadian Dollar ($)',
						'AUD' => 'AUD - Australian Dollar ($)',
						'JPY' => 'JPY - Japanese Yen (¥)',
						'SGD' => 'SGD - Singapore Dollar ($)',
						'AED' => 'AED - UAE Dirham (د.إ)',
					);
					$current_currency = $general['Currency'] ?? 'USD';
					foreach ( $currencies as $code => $label ) {
						printf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $code ),
							selected( $current_currency, $code, false ),
							esc_html( $label )
						);
					}
					?>
				</select>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_primary_language">Primary Language</label>
				<input type="text" id="wbs_primary_language" name="General[Primary_Language]" value="<?php echo esc_attr( $general['Primary_Language'] ?? 'English' ); ?>" placeholder="e.g. English" />
			</div>
		</div>

		<div class="wbs-field-group">
			<label for="wbs_available_language">Available Languages</label>
			<input type="text" id="wbs_available_language" name="General[Available_Language]" value="<?php echo esc_attr( $general['Available_Language'] ?? '' ); ?>" placeholder="e.g. English, Spanish, French, German" />
			<span class="wbs-field-hint">Comma separated list of supported languages.</span>
		</div>

	</div>
</div>
