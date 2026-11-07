<?php
/**
 * Tab: Header and Footer Settings
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hf          = isset( $options['Header_And_Footer'] ) ? $options['Header_And_Footer'] : array();
$header      = $hf['Header'] ?? array();
$footer      = $hf['Footer'] ?? array();

// Navigation Menus for dynamic select
$nav_menus = wp_get_nav_menus();

/**
 * Helper to render dynamic nav menu select
 */
if ( ! function_exists( 'wbs_render_menu_dropdown' ) ) {
	function wbs_render_menu_dropdown( $name, $selected_val, $nav_menus ) {
		?>
		<select name="<?php echo esc_attr( $name ); ?>">
			<option value="">-- Choose Dynamic Nav Menu --</option>
			<?php if ( ! empty( $nav_menus ) ) : ?>
				<?php foreach ( $nav_menus as $menu_obj ) : ?>
					<option value="<?php echo esc_attr( $menu_obj->slug ); ?>" <?php selected( $selected_val, $menu_obj->slug ); ?>>
						<?php echo esc_html( $menu_obj->name ); ?> (<?php echo esc_html( $menu_obj->count ); ?> items)
					</option>
				<?php endforeach; ?>
			<?php else : ?>
				<option value="" disabled>No menus created yet in Appearance &gt; Menus</option>
			<?php endif; ?>
		</select>
		<?php
	}
}

$header_style = $header['Style'] ?? 'style_1';
$footer_style = $footer['Style'] ?? 'style_1';
$plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) );
?>

<!-- 1. HEADER SETTINGS -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-layout"></span> Header Configuration &amp; Style</h2>
			<p>Select header layout (active choice marked with dark border) and configure navigation elements.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<!-- Header Style Selector -->
		<div class="wbs-field-group">
			<label>Header Visual Style</label>
			<div class="wbs-style-selector-grid">
				<?php for ( $i = 1; $i <= 4; $i++ ) : 
					$style_val = 'style_' . $i;
					$img_url   = $plugin_dir_url . 'assets/images/header-style-' . $i . '.svg';
				?>
					<label class="wbs-style-card">
						<input type="radio" name="Header_And_Footer[Header][Style]" value="<?php echo esc_attr( $style_val ); ?>" <?php checked( $header_style, $style_val ); ?> />
						<div class="wbs-style-card-inner">
							<span class="wbs-checked-badge dashicons dashicons-yes"></span>
							<img src="<?php echo esc_url( $img_url ); ?>" alt="Header Style <?php echo $i; ?>" />
							<span class="wbs-style-label">Header Style <?php echo $i; ?></span>
						</div>
					</label>
				<?php endfor; ?>
			</div>
		</div>

		<div class="wbs-grid-3" style="margin-top: 20px;">
			<!-- Enable CTA switch -->
			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Enable Header CTA</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Header][Enabled_cta]" value="1" <?php checked( ! empty( $header['Enabled_cta'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<!-- Sticky Header switch -->
			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Sticky Header on Scroll</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Header][Sticky_header]" value="1" <?php checked( ! empty( $header['Sticky_header'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<!-- Dynamic Nav Menu -->
			<div class="wbs-field-group" style="margin-bottom: 0;">
				<label>Header Navigation Menu</label>
				<?php wbs_render_menu_dropdown( 'Header_And_Footer[Header][Select_menu]', $header['Select_menu'] ?? '', $nav_menus ); ?>
			</div>
		</div>

		<!-- Topbar Controls -->
		<h4 style="margin: 24px 0 12px 0; font-size: 14px; color: var(--wbs-dark);">Topbar Component Switches</h4>
		<div class="wbs-grid-3">
			<?php 
			$topbar = $header['Topbar'] ?? array();
			$topbar_items = array(
				'Enabled_Search'      => 'Search Bar',
				'Enabled_Social'      => 'Social Icons',
				'Enabled_Time'        => 'Business Hours / Time',
				'Enabled_Appointment' => 'Appointment Booking Button',
				'Enabled_Career'      => 'Career / Jobs Link',
			);
			foreach ( $topbar_items as $t_key => $t_label ) :
			?>
				<div class="wbs-switch-wrapper">
					<span class="wbs-switch-title"><?php echo esc_html( $t_label ); ?></span>
					<label class="wbs-switch">
						<input type="checkbox" name="Header_And_Footer[Header][Topbar][<?php echo esc_attr( $t_key ); ?>]" value="1" <?php checked( ! empty( $topbar[ $t_key ] ), true ); ?> />
						<span class="wbs-slider"></span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</div>

<!-- 2. FOOTER SETTINGS -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-admin-appearance"></span> Footer Configuration &amp; Style</h2>
			<p>Select footer layout (checked style highlighted with dark border) and configure menus and legal badges.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<!-- Footer Style Selector -->
		<div class="wbs-field-group">
			<label>Footer Visual Style</label>
			<div class="wbs-style-selector-grid">
				<?php for ( $j = 1; $j <= 4; $j++ ) : 
					$f_style_val = 'style_' . $j;
					$f_img_url   = $plugin_dir_url . 'assets/images/footer-style-' . $j . '.svg';
				?>
					<label class="wbs-style-card">
						<input type="radio" name="Header_And_Footer[Footer][Style]" value="<?php echo esc_attr( $f_style_val ); ?>" <?php checked( $footer_style, $f_style_val ); ?> />
						<div class="wbs-style-card-inner">
							<span class="wbs-checked-badge dashicons dashicons-yes"></span>
							<img src="<?php echo esc_url( $f_img_url ); ?>" alt="Footer Style <?php echo $j; ?>" />
							<span class="wbs-style-label">Footer Style <?php echo $j; ?></span>
						</div>
					</label>
				<?php endfor; ?>
			</div>
		</div>

		<div class="wbs-grid-3" style="margin-top: 20px;">
			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Footer Social Links</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Footer][Enabled_Social]" value="1" <?php checked( ! empty( $footer['Enabled_Social'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>

			<div class="wbs-switch-wrapper">
				<span class="wbs-switch-title">Display Legal Registration Codes</span>
				<label class="wbs-switch">
					<input type="checkbox" name="Header_And_Footer[Footer][Legal_Code][Enabled]" value="1" <?php checked( ! empty( $footer['Legal_Code']['Enabled'] ), true ); ?> />
					<span class="wbs-slider"></span>
				</label>
			</div>
		</div>

		<!-- Connect CTA -->
		<h4 style="margin: 24px 0 12px 0; font-size: 14px; color: var(--wbs-dark);">Footer Quick Connect CTA</h4>
		<div class="wbs-grid-3">
			<?php 
			$cta = $footer['Connect_cta'] ?? array();
			$cta_switches = array(
				'Call_enabled'    => 'Quick Call CTA',
				'Email_enabled'   => 'Quick Email CTA',
				'Address_enabled' => 'Quick Address / Map CTA',
			);
			foreach ( $cta_switches as $cta_k => $cta_l ) :
			?>
				<div class="wbs-switch-wrapper">
					<span class="wbs-switch-title"><?php echo esc_html( $cta_l ); ?></span>
					<label class="wbs-switch">
						<input type="checkbox" name="Header_And_Footer[Footer][Connect_cta][<?php echo esc_attr( $cta_k ); ?>]" value="1" <?php checked( ! empty( $cta[ $cta_k ] ), true ); ?> />
						<span class="wbs-slider"></span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Footer Menus 1 to 5 -->
		<h4 style="margin: 24px 0 12px 0; font-size: 14px; color: var(--wbs-dark);">Dynamic Footer Menus (Columns 1 to 5)</h4>
		<div class="wbs-grid-3">
			<?php 
			$f_menus = $footer['Menus'] ?? array();
			for ( $m = 1; $m <= 5; $m++ ) : 
				$m_key   = 'menu_' . $m;
				$m_data  = $f_menus[ $m_key ] ?? array();
				$m_title = $m_data['Menu_Title'] ?? '';
				$m_slug  = $m_data['Select_menu'] ?? '';
			?>
				<div class="wbs-card" style="margin-bottom: 0; background: var(--wbs-gray-50);">
					<div class="wbs-card-body" style="padding: 14px;">
						<div class="wbs-field-group">
							<label>Menu Column <?php echo $m; ?> Title</label>
							<input type="text" name="Header_And_Footer[Footer][Menus][<?php echo $m_key; ?>][Menu_Title]" value="<?php echo esc_attr( $m_title ); ?>" placeholder="e.g. Quick Links" />
						</div>
						<div class="wbs-field-group" style="margin-bottom: 0;">
							<label>Select Menu</label>
							<?php wbs_render_menu_dropdown( 'Header_And_Footer[Footer][Menus][' . $m_key . '][Select_menu]', $m_slug, $nav_menus ); ?>
						</div>
					</div>
				</div>
			<?php endfor; ?>
		</div>

		<!-- Bottom Copyright Bar -->
		<h4 style="margin: 24px 0 12px 0; font-size: 14px; color: var(--wbs-dark);">Footer Bottom Copyright Bar</h4>
		<?php $bottom = $footer['Bottom'] ?? array(); ?>
		<div class="wbs-switch-wrapper" style="margin-bottom: 16px;">
			<span class="wbs-switch-title">Enable Bottom Copyright Bar</span>
			<label class="wbs-switch">
				<input type="checkbox" name="Header_And_Footer[Footer][Bottom][Enabled]" value="1" <?php checked( ! empty( $bottom['Enabled'] ), true ); ?> />
				<span class="wbs-slider"></span>
			</label>
		</div>

		<div class="wbs-grid-2">
			<div class="wbs-field-group">
				<label>Copyright Text</label>
				<input type="text" name="Header_And_Footer[Footer][Bottom][Copyright_text]" value="<?php echo esc_attr( $bottom['Copyright_text'] ?? '© {year} ' . get_bloginfo( 'name' ) . '. All Rights Reserved.' ); ?>" />
				<span class="wbs-field-hint">Use {year} to automatically output current year.</span>
			</div>
			<div class="wbs-field-group">
				<label>Bottom Bar Menu</label>
				<?php wbs_render_menu_dropdown( 'Header_And_Footer[Footer][Bottom][Select_menu]', $bottom['Select_menu'] ?? '', $nav_menus ); ?>
			</div>
		</div>

		<div class="wbs-field-group">
			<label>Footer Description / Disclaimer</label>
			<textarea name="Header_And_Footer[Footer][Bottom][Footer_description]" rows="3" placeholder="Brief regulatory disclaimer or summary placed at bottom of page..."><?php echo esc_textarea( $bottom['Footer_description'] ?? '' ); ?></textarea>
		</div>

	</div>
</div>
