<?php
/**
 * Tab: Social Media Settings
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$social = isset( $options['Social_Media'] ) ? $options['Social_Media'] : array();

$standard_platforms = array(
	'Facebook'      => 'https://facebook.com/yourbrand',
	'Instagram'     => 'https://instagram.com/yourbrand',
	'Linkedin'      => 'https://linkedin.com/company/yourbrand',
	'Youtube'       => 'https://youtube.com/@yourbrand',
	'Twitter'       => 'https://twitter.com/yourbrand',
	'Pinterest'     => 'https://pinterest.com/yourbrand',
	'Whatsapp'      => 'https://wa.me/14155552671',
	'Behance'       => 'https://behance.net/yourbrand',
	'Github'        => 'https://github.com/yourbrand',
	'Tiktok'        => 'https://tiktok.com/@yourbrand',
	'Threads'       => 'https://threads.net/@yourbrand',
	'Reddit'        => 'https://reddit.com/r/yourbrand',
	'Snapchat'      => 'https://snapchat.com/add/yourbrand',
	'Telegram'      => 'https://t.me/yourbrand',
	'Discord'       => 'https://discord.gg/yourbrand',
	'Twitch'        => 'https://twitch.tv/yourbrand',
	'Medium'        => 'https://medium.com/@yourbrand',
	'Substack'      => 'https://yourbrand.substack.com',
	'Quora'         => 'https://quora.com/profile/yourbrand',
	'Bluesky'       => 'https://bsky.app/profile/yourbrand.bsky.social',
	'Mastodon'      => 'https://mastodon.social/@yourbrand',
	'Tumblr'        => 'https://yourbrand.tumblr.com',
	'Weixin'        => 'Weixin ID / Link',
	'Line'          => 'https://line.me/ti/p/yourbrand',
	'Vk'            => 'https://vk.com/yourbrand',
	'Kakaotalk'     => 'KakaoTalk ID / Link',
	'Upwork'        => 'https://upwork.com/agencies/yourbrand',
	'Fiverr'        => 'https://fiverr.com/yourbrand',
	'Freelancer'    => 'https://freelancer.com/u/yourbrand',
	'Toptal'        => 'https://toptal.com/resume/yourbrand',
	'Contra'        => 'https://contra.com/yourbrand',
	'Peopleperhour' => 'https://peopleperhour.com/freelancer/yourbrand',
	'Guru'          => 'https://guru.com/freelancers/yourbrand',
	'99designs'     => 'https://99designs.com/profiles/yourbrand',
	'Malt'          => 'https://malt.com/profile/yourbrand',
	'Flexjobs'      => 'https://flexjobs.com/employer/yourbrand',
	'Codeable'      => 'https://codeable.io/experts/yourbrand',
	'Dribbble'      => 'https://dribbble.com/yourbrand',
	'Truelancer'    => 'https://truelancer.com/freelancer/yourbrand',
	'Worknhire'     => 'https://worknhire.com/user/yourbrand',
	'Bark'          => 'https://bark.com/en/company/yourbrand',
	'Codementor'    => 'https://codementor.io/@yourbrand',
);

// Collect "Other" items
$other_items = array();
if ( ! empty( $social['Other'] ) && is_array( $social['Other'] ) ) {
	$other_items = $social['Other'];
} else {
	// Backward compatibility with Other_1, Other_2, etc. from initial json
	for ( $i = 1; $i <= 6; $i++ ) {
		if ( isset( $social[ 'Other_' . $i ] ) && ! empty( $social[ 'Other_' . $i ] ) ) {
			$other_items[] = array(
				'label' => 'Other ' . $i,
				'value' => $social[ 'Other_' . $i ],
			);
		}
	}
}
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-share"></span> Social Media &amp; Marketplace Profiles</h2>
			<p>Official public social profiles, developer portfolios &amp; freelance marketplaces.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-3">
			<?php foreach ( $standard_platforms as $platform => $ph ) : 
				$val = $social[ $platform ] ?? '';
			?>
				<div class="wbs-field-group">
					<label for="wbs_social_<?php echo esc_attr( sanitize_title( $platform ) ); ?>"><?php echo esc_html( $platform ); ?></label>
					<input type="url" id="wbs_social_<?php echo esc_attr( sanitize_title( $platform ) ); ?>" name="Social_Media[<?php echo esc_attr( $platform ); ?>]" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>" />
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<!-- Dynamic Other Social Media Links -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-plus-alt2"></span> Custom / Other Social &amp; Web Links</h2>
			<p>Click "Add Other" to dynamically specify custom labels and profile URLs.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-repeater-container">
			<div id="wbs-other-Social_Media">
				<?php if ( ! empty( $other_items ) ) : ?>
					<?php foreach ( $other_items as $k => $item ) : 
						$label = is_array( $item ) ? ( $item['label'] ?? '' ) : 'Other ' . ( $k + 1 );
						$value = is_array( $item ) ? ( $item['value'] ?? '' ) : $item;
					?>
						<div class="wbs-other-row">
							<input type="text" name="Social_Media[Other][<?php echo esc_attr( $k ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" placeholder="Custom Label (e.g. Patreon)" class="wbs-other-label" />
							<input type="url" name="Social_Media[Other][<?php echo esc_attr( $k ); ?>][value]" value="<?php echo esc_attr( $value ); ?>" placeholder="https://..." class="wbs-other-value" />
							<button type="button" class="wbs-btn-delete-other wbs-btn-delete-row dashicons dashicons-trash" title="Remove"></button>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<button type="button" class="wbs-btn-add-row wbs-btn-add-other" data-target="Social_Media">
				<span class="dashicons dashicons-plus-alt2"></span> Add Other Social Link
			</button>
		</div>
	</div>
</div>
