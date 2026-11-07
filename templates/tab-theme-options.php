<?php
/**
 * Tab: Theme Options (Sidebar, Preloader, Floating Action Buttons & Schema)
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hf        = isset( $options['Header_And_Footer'] ) ? $options['Header_And_Footer'] : array();
$sidebar   = $options['Theme_Options']['Sidebar'] ?? $hf['Sidebar'] ?? array();
$preloader = $options['Theme_Options']['Preloader'] ?? $hf['Preloader'] ?? array();
$floating  = $options['Theme_Options']['Floating_btn'] ?? $hf['Floating_btn'] ?? array();
?>

<!-- 1. SIDEBAR & PRELOADER SETTINGS -->
<div class="wbs-grid-2">

	<!-- Sidebar Controls -->
	<div class="wbs-card">
		<div class="wbs-card-header">
			<div>
				<h2><span class="dashicons dashicons-schedule"></span> Sidebar Widgets</h2>
				<p>Enable/disable components in global blog sidebar.</p>
			</div>
		</div>
		<div class="wbs-card-body">
			<?php 
			$sidebar_switches = array(
				'Enabled'          => 'Enable Sidebar',
				'Search_box'       => 'Search Widget',
				'Contact_box'      => 'Contact Card Widget',
				'Newsletter_box'   => 'Newsletter Subscription Box',
				'Blog_categories'  => 'Categories Widget',
				'Blog_tags'        => 'Tags Cloud Widget',
				'Recent_posts_box' => 'Recent Articles List',
				'Share_post'       => 'Social Sharing Widget',
			);
			foreach ( $sidebar_switches as $s_key => $s_title ) :
			?>
				<div class="wbs-switch-wrapper" style="margin-bottom: 8px;">
					<span class="wbs-switch-title"><?php echo esc_html( $s_title ); ?></span>
					<label class="wbs-switch">
						<input type="checkbox" name="Header_And_Footer[Sidebar][<?php echo esc_attr( $s_key ); ?>]" value="1" <?php checked( ! empty( $sidebar[ $s_key ] ), true ); ?> />
						<span class="wbs-slider"></span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- Preloader Controls -->
	<div class="wbs-card">
		<div class="wbs-card-header">
			<div>
				<h2><span class="dashicons dashicons-update"></span> Page Preloader</h2>
				<p>Smooth loading animation before page renders.</p>
			</div>
		</div>
		<div class="wbs-card-body">
			<div class="wbs-switch-wrapper" style="margin-bottom: 16px;">
				<span class="wbs-switch-title">Enable Preloader</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Preloader][Enabled]" value="1" <?php checked( ! empty( $preloader['Enabled'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<div class="wbs-field-group">
				<label>Preloader Animation Style</label>
				<select name="Header_And_Footer[Preloader][Style]">
					<?php 
					$p_styles = array( 
						'spinner' => 'Classic Circular Spinner', 
						'pulse'   => 'Pulse Brand Logo', 
						'bar'     => 'Top Loading Progress Bar', 
						'minimal' => 'Minimal Fade' 
					);
					$cur_p_style = $preloader['Style'] ?? 'spinner';
					foreach ( $p_styles as $pk => $pv ) {
						printf( '<option value="%s" %s>%s</option>', esc_attr( $pk ), selected( $cur_p_style, $pk, false ), esc_html( $pv ) );
					}
					?>
				</select>
			</div>

			<div class="wbs-field-group">
				<label>Loading Text</label>
				<input type="text" name="Header_And_Footer[Preloader][Loading_text]" value="<?php echo esc_attr( $preloader['Loading_text'] ?? 'Loading...' ); ?>" />
			</div>

			<div class="wbs-field-group">
				<label>Minimum Timeout (ms)</label>
				<input type="number" name="Header_And_Footer[Preloader][Minimum_time_out]" min="0" step="50" value="<?php echo esc_attr( $preloader['Minimum_time_out'] ?? '500' ); ?>" />
			</div>

			<div class="wbs-field-group">
				<label>Exclude Page IDs</label>
				<input type="text" name="Header_And_Footer[Preloader][Exclude_page_id]" value="<?php echo esc_attr( $preloader['Exclude_page_id'] ?? '' ); ?>" placeholder="e.g. 12, 45, 99" />
				<span class="wbs-field-hint">Comma separated IDs of pages to disable preloader.</span>
			</div>
		</div>
	</div>

</div>

<!-- 2. FLOATING ACTION BUTTON SETTINGS (ALL 7 TRIGGERS) -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-admin-comments"></span> Floating Quick Contact &amp; Actions Widget</h2>
			<p>Configure all 7 floating triggers: Contact Modal, WhatsApp, Call, Message/SMS, Email, Google Maps, and Back-to-Top.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<!-- General Display & Positioning -->
		<div class="wbs-grid-3">
			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Desktop Enabled</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Floating_btn][desktop_enabled]" value="1" <?php checked( ! empty( $floating['desktop_enabled'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Mobile Enabled</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Floating_btn][mobile_enabled]" value="1" <?php checked( ! empty( $floating['mobile_enabled'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Tooltip Labels Enabled</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Floating_btn][tooltip_enabled]" value="1" <?php checked( ! empty( $floating['tooltip_enabled'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>
		</div>

		<div class="wbs-grid-2" style="margin-top: 14px;">
			<div class="wbs-field-group">
				<label>Horizontal Position</label>
				<select name="Header_And_Footer[Floating_btn][h_position]">
					<option value="right" <?php selected( $floating['h_position'] ?? 'right', 'right' ); ?>>Right Side</option>
					<option value="left" <?php selected( $floating['h_position'] ?? '', 'left' ); ?>>Left Side</option>
				</select>
			</div>

			<div class="wbs-field-group">
				<label>Vertical Position</label>
				<select name="Header_And_Footer[Floating_btn][v_position]">
					<option value="bottom" <?php selected( $floating['v_position'] ?? 'bottom', 'bottom' ); ?>>Bottom</option>
					<option value="top" <?php selected( $floating['v_position'] ?? '', 'top' ); ?>>Top</option>
				</select>
			</div>
		</div>

		<!-- All 7 Floating Action Sub-Components -->
		<h4 style="margin: 24px 0 14px 0; font-size: 14px; font-weight: 700; color: var(--wbs-dark);">Configured Triggers &amp; Channels (7 Actions)</h4>
		<div class="wbs-grid-3">

			<!-- 1. Contact Modal -->
			<?php 
			$f_modal = $floating['Contact Modal'] ?? array(); 
			$modal_enabled = ! empty( $f_modal['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Contact Modal</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Contact Modal][Enabled]" value="1" <?php checked( $modal_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Modal ID</label>
						<input type="text" name="Header_And_Footer[Floating_btn][Contact Modal][Modal_id]" value="<?php echo esc_attr( $f_modal['Modal_id'] ?? '' ); ?>" placeholder="e.g. #contact-modal or modal-popup" />
						<span class="wbs-field-hint">DOM ID of the contact popup modal to trigger.</span>
					</div>
					<div class="wbs-field-group">
						<label>Exclude Page IDs</label>
						<input type="text" name="Header_And_Footer[Floating_btn][Exclude_page_id]" value="<?php echo esc_attr( $f_modal['Exclude_page_id'] ?? '' ); ?>" placeholder="e.g. 12, 45, 99" />
						<span class="wbs-field-hint">Comma separated IDs of pages to disable preloader.</span>
					</div>
				</div>
			</div>

			<!-- 2. WhatsApp -->
			<?php 
			$f_wa = $floating['Whatsapp'] ?? array(); 
			$wa_enabled = ! empty( $f_wa['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">WhatsApp Chat</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Whatsapp][Enabled]" value="1" <?php checked( $wa_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group">
						<label>WhatsApp Number</label>
						<input type="tel" name="Header_And_Footer[Floating_btn][Whatsapp][Number]" value="<?php echo esc_attr( $f_wa['Number'] ?? '' ); ?>" placeholder="+14155552671" />
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Default Message</label>
						<input type="text" name="Header_And_Footer[Floating_btn][Whatsapp][Message]" value="<?php echo esc_attr( $f_wa['Message'] ?? 'Hello, I need assistance.' ); ?>" />
					</div>
				</div>
			</div>

			<!-- 5. Email Inquiry -->
			<?php 
			$f_email = $floating['Email'] ?? array(); 
			$email_enabled = ! empty( $f_email['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Email Inquiry</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Email][Enabled]" value="1" <?php checked( $email_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group">
						<label>Email Address</label>
						<input type="email" name="Header_And_Footer[Floating_btn][Email][Address]" value="<?php echo esc_attr( $f_email['Address'] ?? '' ); ?>" placeholder="support@company.com" />
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Default Subject</label>
						<input type="text" name="Header_And_Footer[Floating_btn][Email][Subject]" value="<?php echo esc_attr( $f_email['Subject'] ?? 'Hello, I need assistance.' ); ?>" />
					</div>
				</div>
			</div>

			<!-- 3. Direct Call -->
			<?php 
			$f_call = $floating['Call'] ?? array(); 
			$call_enabled = ! empty( $f_call['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Direct Call Button</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Call][Enabled]" value="1" <?php checked( $call_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Direct Phone Number</label>
						<input type="tel" name="Header_And_Footer[Floating_btn][Call][Number]" value="<?php echo esc_attr( $f_call['Number'] ?? '' ); ?>" placeholder="+18005550199" />
						<span class="wbs-field-hint">Triggers immediate tel: link for phone dialers.</span>
					</div>
				</div>
			</div>

			<!-- 4. Direct SMS / Message -->
			<?php 
			$f_msg = $floating['Message'] ?? array(); 
			$msg_enabled = ! empty( $f_msg['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">SMS / Text Message</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Message][Enabled]" value="1" <?php checked( $msg_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>SMS / Message Number</label>
						<input type="tel" name="Header_And_Footer[Floating_btn][Message][Number]" value="<?php echo esc_attr( $f_msg['Number'] ?? '' ); ?>" placeholder="+15551234567" />
						<span class="wbs-field-hint">Triggers native sms: messaging app link.</span>
					</div>
				</div>
			</div>

			<!-- 4. Direct SMS / Message -->
			<?php 
			$f_msg = $floating['Live_Chat_Button'] ?? array(); 
			$msg_enabled = ! empty( $f_msg['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Live Chat Button</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Live_Chat_Button][Enabled]" value="1" <?php checked( $msg_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Live Chat Button Title</label>
						<input type="text" name="Header_And_Footer[Floating_btn][Live_Chat_Button][Title]" value="<?php echo esc_attr( $f_msg['Title'] ?? '' ); ?>" placeholder="Chat with us!" />
					</div>
				</div>
			</div>

			<!-- 6. Google Maps -->
			<?php 
			$f_maps = $floating['Google_maps'] ?? array(); 
			$maps_enabled = ! empty( $f_maps['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Google Maps Direction</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Google_maps][Enabled]" value="1" <?php checked( $maps_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Google Maps Link</label>
						<input type="url" name="Header_And_Footer[Floating_btn][Google_maps][Link]" value="<?php echo esc_url( $f_maps['Link'] ?? '' ); ?>" placeholder="https://maps.google.com/?q=..." />
						<span class="wbs-field-hint">Opens Google Maps / Apple Maps navigation link.</span>
					</div>
				</div>
			</div>

			<!-- 7. Back to Top -->
			<?php 
			$f_top = $floating['Back_to_top'] ?? array(); 
			$top_enabled = ! empty( $f_top['Enabled'] );
			?>
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Back to Top Button</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Header_And_Footer[Floating_btn][Back_to_top][Enabled]" value="1" <?php checked( $top_enabled, true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Scroll Trigger Distance (px)</label>
						<input type="number" name="Header_And_Footer[Floating_btn][Back_to_top][scroll_distance]" min="50" step="50" value="<?php echo esc_attr( $f_top['scroll_distance'] ?? '300' ); ?>" />
						<span class="wbs-field-hint">Scroll position in pixels before button appears.</span>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>
