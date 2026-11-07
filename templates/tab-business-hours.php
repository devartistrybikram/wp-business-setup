<?php
/**
 * Tab: Business Hours Settings
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hours = isset( $options['Business_Hours'] ) ? $options['Business_Hours'] : array();

$days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-clock"></span> Operating &amp; Business Hours</h2>
			<p>Set daily operating schedules for physical locations and customer service.</p>
		</div>
	</div>
	<div class="wbs-card-body" style="padding: 0;">
		<?php foreach ( $days as $day ) : 
			$day_data = $hours[ $day ] ?? array();
			$enabled  = ! empty( $day_data['Enabled'] );
			$open     = $day_data['open'] ?? '09:00';
			$close    = $day_data['close'] ?? '18:00';
		?>
			<div class="wbs-hour-row">
				<div class="wbs-hour-day">
					<?php echo esc_html( $day ); ?>
				</div>

				<div style="width: 140px;">
					<label class="wbs-switch">
						<input type="checkbox" name="Business_Hours[<?php echo esc_attr( $day ); ?>][Enabled]" value="1" <?php checked( $enabled, true ); ?> />
						<span class="wbs-slider"></span>
					</label>
					<span style="font-size: 12px; margin-left: 8px; color: <?php echo $enabled ? '#10b981' : '#94a3b8'; ?>; font-weight: 600;">
						<?php echo $enabled ? 'Open' : 'Closed'; ?>
					</span>
				</div>

				<div class="wbs-hour-inputs">
					<span>From</span>
					<input type="time" name="Business_Hours[<?php echo esc_attr( $day ); ?>][open]" value="<?php echo esc_attr( $open ); ?>" />
					<span>To</span>
					<input type="time" name="Business_Hours[<?php echo esc_attr( $day ); ?>][close]" value="<?php echo esc_attr( $close ); ?>" />
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
