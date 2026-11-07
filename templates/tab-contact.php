<?php
/**
 * Tab: Contact Settings
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact = isset( $options['Contact'] ) ? $options['Contact'] : array();

// Ensure arrays have at least 1 element
$phones    = ! empty( $contact['phone'] ) && is_array( $contact['phone'] ) ? array_values( $contact['phone'] ) : array( '' );
$emails    = ! empty( $contact['email'] ) && is_array( $contact['email'] ) ? array_values( $contact['email'] ) : array( '' );
$addresses = ! empty( $contact['address'] ) && is_array( $contact['address'] ) ? array_values( $contact['address'] ) : array( array( '', '', '', '', '', '', '' ) );
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-phone"></span> Direct Communication Channels</h2>
			<p>Direct instant messaging &amp; customer inquiry channels.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-2">
			<div class="wbs-field-group">
				<label for="wbs_contact_sms">SMS / Text Support Number</label>
				<input type="tel" id="wbs_contact_sms" name="Contact[sms]" value="<?php echo esc_attr( $contact['sms'] ?? '' ); ?>" placeholder="+1 (555) 123-4567" />
				<span class="wbs-field-hint">Phone number formatted for SMS / short message inquiries.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_contact_whatsapp">WhatsApp Support Number</label>
				<input type="tel" id="wbs_contact_whatsapp" name="Contact[whatsapp]" value="<?php echo esc_attr( $contact['whatsapp'] ?? '' ); ?>" placeholder="+1 (555) 987-6543" />
				<span class="wbs-field-hint">Direct WhatsApp line with country code (e.g. +14155552671).</span>
			</div>
		</div>
	</div>
</div>

<div class="wbs-grid-2">
	<!-- Phone Numbers Repeater -->
	<div class="wbs-card">
		<div class="wbs-card-header">
			<div>
				<h2><span class="dashicons dashicons-phone"></span> Phone Numbers</h2>
				<p>Support, sales, and desk lines (Max 10, Minimum 1 required).</p>
			</div>
		</div>
		<div class="wbs-card-body">
			<div class="wbs-repeater-container wbs-simple-repeater" id="wbs-phone-repeater">
				<div class="wbs-repeater-header">
					<h3>Registered Numbers</h3>
					<span class="wbs-repeater-counter"><span class="wbs-count-current"><?php echo count( $phones ); ?></span> / 10</span>
				</div>

				<div class="wbs-repeater-rows">
					<?php foreach ( $phones as $phone_val ) : ?>
						<div class="wbs-repeater-row">
							<span class="dashicons dashicons-menu wbs-row-drag"></span>
							<input type="tel" name="Contact[phone][]" value="<?php echo esc_attr( $phone_val ); ?>" placeholder="+1 (555) 000-0000" />
							<button type="button" class="wbs-btn-delete-row dashicons dashicons-trash" <?php echo count( $phones ) <= 1 ? 'disabled title="At least one field is required"' : ''; ?>></button>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="wbs-btn-add-row wbs-btn-add-phone" <?php echo count( $phones ) >= 10 ? 'disabled' : ''; ?>>
					<span class="dashicons dashicons-plus-alt2"></span> Add More
				</button>
			</div>
		</div>
	</div>

	<!-- Email Addresses Repeater -->
	<div class="wbs-card">
		<div class="wbs-card-header">
			<div>
				<h2><span class="dashicons dashicons-email-alt"></span> Email Addresses</h2>
				<p>General, support, and billing inboxes (Max 10, Minimum 1 required).</p>
			</div>
		</div>
		<div class="wbs-card-body">
			<div class="wbs-repeater-container wbs-simple-repeater" id="wbs-email-repeater">
				<div class="wbs-repeater-header">
					<h3>Registered Emails</h3>
					<span class="wbs-repeater-counter"><span class="wbs-count-current"><?php echo count( $emails ); ?></span> / 10</span>
				</div>

				<div class="wbs-repeater-rows">
					<?php foreach ( $emails as $email_val ) : ?>
						<div class="wbs-repeater-row">
							<span class="dashicons dashicons-menu wbs-row-drag"></span>
							<input type="email" name="Contact[email][]" value="<?php echo esc_attr( $email_val ); ?>" placeholder="support@company.com" />
							<button type="button" class="wbs-btn-delete-row dashicons dashicons-trash" <?php echo count( $emails ) <= 1 ? 'disabled title="At least one field is required"' : ''; ?>></button>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="wbs-btn-add-row wbs-btn-add-email" <?php echo count( $emails ) >= 10 ? 'disabled' : ''; ?>>
					<span class="dashicons dashicons-plus-alt2"></span> Add More
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Physical Locations / Addresses Repeater -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-location"></span> Business Physical Addresses</h2>
			<p>Headquarters, branches &amp; warehouse locations. Format: [Address 1, Address 2, City, State, Country, Zipcode, Geo-coordination] (Max 10, Minimum 1 required).</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-repeater-container" id="wbs-address-repeater">
			<div class="wbs-repeater-header">
				<h3>Physical Branches &amp; Offices</h3>
				<span class="wbs-repeater-counter"><span class="wbs-count-current"><?php echo count( $addresses ); ?></span> / 10</span>
			</div>

			<div class="wbs-address-cards">
				<?php foreach ( $addresses as $idx => $addr ) : 
					$a0 = $addr[0] ?? '';
					$a1 = $addr[1] ?? '';
					$a2 = $addr[2] ?? '';
					$a3 = $addr[3] ?? '';
					$a4 = $addr[4] ?? '';
					$a5 = $addr[5] ?? '';
					$a6 = $addr[6] ?? '';
				?>
					<div class="wbs-address-card" data-index="<?php echo esc_attr( $idx ); ?>">
						<div class="wbs-address-header">
							<span>Address #<span class="wbs-address-index"><?php echo esc_html( $idx + 1 ); ?></span></span>
							<button type="button" class="wbs-btn-delete-address wbs-btn-delete-row dashicons dashicons-trash" title="Remove Address" <?php echo count( $addresses ) <= 1 ? 'disabled' : ''; ?>></button>
						</div>

						<div class="wbs-grid-2">
							<div class="wbs-field-group">
								<label>Address Line 1</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][0]" value="<?php echo esc_attr( $a0 ); ?>" placeholder="Street Address, P.O. Box" />
							</div>
							<div class="wbs-field-group">
								<label>Address Line 2</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][1]" value="<?php echo esc_attr( $a1 ); ?>" placeholder="Apt, Suite, Unit, Building, Floor" />
							</div>
						</div>

						<div class="wbs-grid-4" style="margin-top: 10px;">
							<div class="wbs-field-group">
								<label>City</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][2]" value="<?php echo esc_attr( $a2 ); ?>" placeholder="City" />
							</div>
							<div class="wbs-field-group">
								<label>State / Province</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][3]" value="<?php echo esc_attr( $a3 ); ?>" placeholder="State / Province" />
							</div>
							<div class="wbs-field-group">
								<label>Country</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][4]" value="<?php echo esc_attr( $a4 ); ?>" placeholder="Country" />
							</div>
							<div class="wbs-field-group">
								<label>Zipcode</label>
								<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][5]" value="<?php echo esc_attr( $a5 ); ?>" placeholder="Zip / Postal Code" />
							</div>
						</div>

						<div class="wbs-field-group" style="margin-top: 10px;">
							<label>Geo-Coordination (Latitude, Longitude)</label>
							<input type="text" name="Contact[address][<?php echo esc_attr( $idx ); ?>][6]" value="<?php echo esc_attr( $a6 ); ?>" placeholder="e.g. 40.7128,-74.0060" />
							<span class="wbs-field-hint">Used for LocalBusiness schema and maps navigation.</span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" class="wbs-btn-add-row wbs-btn-add-address" <?php echo count( $addresses ) >= 10 ? 'disabled' : ''; ?>>
				<span class="dashicons dashicons-plus-alt2"></span> Add More Address
			</button>
		</div>
	</div>
</div>
