<?php
/**
 * Tab: Analytics & Tracking Codes
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$analytics = isset( $options['Analytics'] ) ? $options['Analytics'] : array();
$enabled   = ! empty( $analytics['Enabled'] );
?>

<!-- Master Switch Card -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-chart-line"></span> Analytics, Tracking &amp; Webmaster Services</h2>
			<p>Manage tracking IDs, search console verifications, and custom injection snippets for Head, Body, and Footer.</p>
		</div>
		<div>
			<label class="wbs-switch">
				<input type="checkbox" name="Analytics[Enabled]" value="1" <?php checked( $enabled, true ); ?> />
				<span class="wbs-slider"></span>
			</label>
		</div>
	</div>
</div>

<!-- Webmaster & Search Engine Verifications -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-admin-site-alt3"></span> Webmaster Verification Codes</h2>
			<p>Domain ownership meta verification codes for major search engines and security authorities (Paste verification codes only; script tags are prohibited).</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-3">

			<div class="wbs-field-group">
				<label for="wbs_google_verify">Google Search Console Verification</label>
				<input type="text" id="wbs_google_verify" name="Analytics[Google_Site_Verification]" value="<?php echo esc_attr( $analytics['Google_Site_Verification'] ?? '' ); ?>" placeholder="e.g. 4zX7_abc123DEF456..." />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="google-site-verification"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_bing_verify">Bing Webmaster Tools Verification</label>
				<input type="text" id="wbs_bing_verify" name="Analytics[Bing_Verification_ID]" value="<?php echo esc_attr( $analytics['Bing_Verification_ID'] ?? '' ); ?>" placeholder="e.g. 32-character hexadecimal key" />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="msvalidate.01"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_indexnow">IndexNow API Key</label>
				<input type="text" id="wbs_indexnow" name="Analytics[IndexNow_API_Key]" value="<?php echo esc_attr( $analytics['IndexNow_API_Key'] ?? '' ); ?>" placeholder="e.g. 84f475b8719f4a08..." />
				<span class="wbs-field-hint">Used for instant automated indexing to Bing, Yandex &amp; Seznam.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_pinterest_verify">Pinterest Domain Verification</label>
				<input type="text" id="wbs_pinterest_verify" name="Analytics[Pinterest_Tag_ID]" value="<?php echo esc_attr( $analytics['Pinterest_Tag_ID'] ?? '' ); ?>" placeholder="e.g. 1a2b3c4d5e6f..." />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="p:domain_verify"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_fb_verify">Facebook Domain Verification</label>
				<input type="text" id="wbs_fb_verify" name="Analytics[Facebook_Domain_Verification]" value="<?php echo esc_attr( $analytics['Facebook_Domain_Verification'] ?? '' ); ?>" placeholder="e.g. 15-character verification hash" />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="facebook-domain-verification"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_ahrefs_verify">Ahrefs Site Verification</label>
				<input type="text" id="wbs_ahrefs_verify" name="Analytics[Ahrefs_Site_Verification]" value="<?php echo esc_attr( $analytics['Ahrefs_Site_Verification'] ?? '' ); ?>" placeholder="e.g. ahrefs-site-verification_..." />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="ahrefs-site-verification"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_norton_verify">Norton Safe Web Verification</label>
				<input type="text" id="wbs_norton_verify" name="Analytics[Norton_Safe_Web]" value="<?php echo esc_attr( $analytics['Norton_Safe_Web'] ?? '' ); ?>" placeholder="e.g. norton-safeweb-site-verification" />
				<span class="wbs-field-hint">Verifies security status on Norton Safe Web directory.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_wot_verify">Web Of Trust (WOT) Verification</label>
				<input type="text" id="wbs_wot_verify" name="Analytics[Web_Of_Trust]" value="<?php echo esc_attr( $analytics['Web_Of_Trust'] ?? '' ); ?>" placeholder="e.g. wot-verification-code" />
				<span class="wbs-field-hint">Outputs <code>&lt;meta name="wot-verification"&gt;</code> tag.</span>
			</div>

			<div class="wbs-field-group">
				<label for="wbs_ga4_secret">Measurement Protocol Secret</label>
				<input type="text" id="wbs_ga4_secret" name="Analytics[Measurement_Protocol_Secret]" value="<?php echo esc_attr( $analytics['Measurement_Protocol_Secret'] ?? '' ); ?>" placeholder="API Secret for GA4 Server Events" />
				<span class="wbs-field-hint">Used for server-to-server measurement events.</span>
			</div>

		</div>
	</div>
</div>

<!-- Tracking & Analytics Platform IDs -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-analytics"></span> Analytics &amp; Advertising Pixels</h2>
			<p>Official tracking tags are generated and injected automatically from your clean IDs.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-3">

			<!-- GTM -->
			<div class="wbs-field-group">
				<label for="wbs_gtm_id">Google Tag Manager (GTM) Container ID</label>
				<input type="text" id="wbs_gtm_id" name="Analytics[GTM_Container_ID]" value="<?php echo esc_attr( $analytics['GTM_Container_ID'] ?? '' ); ?>" placeholder="GTM-XXXXXXX" pattern="^GTM-[A-Za-z0-9]+$" />
				<span class="wbs-field-hint">Injects official GTM script into <code>&lt;head&gt;</code> and noscript into <code>&lt;body&gt;</code>.</span>
			</div>

			<!-- GA4 -->
			<div class="wbs-field-group">
				<label for="wbs_ga4_id">Google Analytics 4 (GA4) Measurement ID</label>
				<input type="text" id="wbs_ga4_id" name="Analytics[GA4_Measurement_ID]" value="<?php echo esc_attr( $analytics['GA4_Measurement_ID'] ?? '' ); ?>" placeholder="G-XXXXXXXXXX" pattern="^G-[A-Za-z0-9]+$" />
				<span class="wbs-field-hint">Injects official gtag.js library and pageview tracker.</span>
			</div>

			<!-- Meta Pixel -->
			<div class="wbs-field-group">
				<label for="wbs_meta_pixel">Meta / Facebook Pixel ID</label>
				<input type="text" id="wbs_meta_pixel" name="Analytics[Meta_Pixel_ID]" value="<?php echo esc_attr( $analytics['Meta_Pixel_ID'] ?? '' ); ?>" placeholder="e.g. 123456789012345" />
				<span class="wbs-field-hint">Injects official Facebook Pixel base code &amp; PageView trigger.</span>
			</div>

			<!-- Microsoft Clarity -->
			<div class="wbs-field-group">
				<label for="wbs_clarity">Microsoft Clarity Project ID</label>
				<input type="text" id="wbs_clarity" name="Analytics[Clarity_Project_ID]" value="<?php echo esc_attr( $analytics['Clarity_Project_ID'] ?? '' ); ?>" placeholder="e.g. a1b2c3d4e5" />
				<span class="wbs-field-hint">Powers session recordings, click heatmaps &amp; user insights.</span>
			</div>

			<!-- Bing Tags -->
			<div class="wbs-field-group">
				<label for="wbs_bing_tags">Bing Ads UET Tag ID</label>
				<input type="text" id="wbs_bing_tags" name="Analytics[Bing_Tags_ID]" value="<?php echo esc_attr( $analytics['Bing_Tags_ID'] ?? '' ); ?>" placeholder="e.g. 12345678" />
				<span class="wbs-field-hint">Microsoft Advertising Universal Event Tracking (UET).</span>
			</div>

			<!-- LinkedIn -->
			<div class="wbs-field-group">
				<label for="wbs_linkedin_partner">LinkedIn Insight Partner ID</label>
				<input type="text" id="wbs_linkedin_partner" name="Analytics[LinkedIn_Partner_ID]" value="<?php echo esc_attr( $analytics['LinkedIn_Partner_ID'] ?? '' ); ?>" placeholder="e.g. 1234567" />
				<span class="wbs-field-hint">LinkedIn Campaign Manager website audience tracking.</span>
			</div>

			<!-- Twitter / X -->
			<div class="wbs-field-group">
				<label for="wbs_twitter_pixel">Twitter / X Pixel ID</label>
				<input type="text" id="wbs_twitter_pixel" name="Analytics[Twitter_Pixel_ID]" value="<?php echo esc_attr( $analytics['Twitter_Pixel_ID'] ?? '' ); ?>" placeholder="e.g. tw-xxxx-xxxx" />
				<span class="wbs-field-hint">Twitter Ads conversion pixel tracking.</span>
			</div>

			<!-- Matomo URL -->
			<div class="wbs-field-group">
				<label for="wbs_matomo_url">Matomo Analytics Server URL</label>
				<input type="url" id="wbs_matomo_url" name="Analytics[Matomo_URL]" value="<?php echo esc_url( $analytics['Matomo_URL'] ?? '' ); ?>" placeholder="https://analytics.yourdomain.com/" />
				<span class="wbs-field-hint">Self-hosted or cloud Matomo base tracking URL.</span>
			</div>

			<!-- Matomo Site ID -->
			<div class="wbs-field-group">
				<label for="wbs_matomo_site_id">Matomo Site ID</label>
				<input type="text" id="wbs_matomo_site_id" name="Analytics[Matomo_Site_ID]" value="<?php echo esc_attr( $analytics['Matomo_Site_ID'] ?? '' ); ?>" placeholder="e.g. 1" />
				<span class="wbs-field-hint">The numerical or alphanumeric site ID in Matomo.</span>
			</div>

		</div>
	</div>
</div>

<!-- Three Custom Code Editors (HEAD, BODY, FOOTER) with Strict Validation -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-editor-code"></span> Custom Tracking Code Editors (HEAD, BODY, FOOTER)</h2>
			<p>Direct code injection zones with anti-tamper security verification. PHP tags, eval, document.cookie, and malicious injection vectors are strictly blocked.</p>
		</div>
		<div>
			<span style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
				<span class="dashicons dashicons-shield" style="font-size: 14px; width: 14px; height: 14px; vertical-align: middle;"></span> Safe Code Verification Active
			</span>
		</div>
	</div>
	<div class="wbs-card-body">

		<!-- 1. HEAD Tracking Code -->
		<div class="wbs-field-group" style="margin-bottom: 24px;">
			<div class="wbs-code-editor-header">
				<label for="wbs_head_tracking">
					<span class="dashicons dashicons-arrow-up-alt2"></span> 
					<strong>HEAD Tracking Code</strong> &mdash; Injected into <code>&lt;head&gt;</code> via <code>wp_head</code>
				</label>
				<span class="wbs-code-lang-tag">HTML / Script</span>
			</div>
			<div class="wbs-code-editor-wrap">
				<textarea id="wbs_head_tracking" name="Analytics[Head_Tracking_Code]" rows="7" class="wbs-code-editor" placeholder="&lt;!-- Example: Custom tracking snippet or meta tag --&gt;&#10;&lt;script&gt;&#10;  // Safe tracking logic only&#10;&lt;/script&gt;"><?php echo esc_textarea( $analytics['Head_Tracking_Code'] ?? '' ); ?></textarea>
			</div>
			<span class="wbs-field-hint">Inserted immediately before the closing <code>&lt;/head&gt;</code> tag. Suitable for analytics base libraries, preconnects, and meta verification tags.</span>
		</div>

		<!-- 2. BODY Tracking Code -->
		<div class="wbs-field-group" style="margin-bottom: 24px;">
			<div class="wbs-code-editor-header">
				<label for="wbs_body_tracking">
					<span class="dashicons dashicons-editor-insertmore"></span> 
					<strong>BODY Tracking Code</strong> &mdash; Injected into opening <code>&lt;body&gt;</code> via <code>wp_body_open</code>
				</label>
				<span class="wbs-code-lang-tag">HTML / Noscript</span>
			</div>
			<div class="wbs-code-editor-wrap">
				<textarea id="wbs_body_tracking" name="Analytics[Body_Tracking_Code]" rows="6" class="wbs-code-editor" placeholder="&lt;!-- Example: Google Tag Manager (noscript) or Meta Pixel (noscript) fallback --&gt;&#10;&lt;noscript&gt;&#10;  &lt;iframe src=&quot;https://www.googletagmanager.com/ns.html?id=GTM-XXXX&quot; height=&quot;0&quot; width=&quot;0&quot; style=&quot;display:none;visibility:hidden&quot;&gt;&lt;/iframe&gt;&#10;&lt;/noscript&gt;"><?php echo esc_textarea( $analytics['Body_Tracking_Code'] ?? '' ); ?></textarea>
			</div>
			<span class="wbs-field-hint">Inserted immediately after opening <code>&lt;body&gt;</code> tag. Suitable for noscript fallbacks and accessibility markers.</span>
		</div>

		<!-- 3. FOOTER Tracking Code -->
		<div class="wbs-field-group">
			<div class="wbs-code-editor-header">
				<label for="wbs_footer_tracking">
					<span class="dashicons dashicons-arrow-down-alt2"></span> 
					<strong>FOOTER Tracking Code</strong> &mdash; Injected before <code>&lt;/body&gt;</code> via <code>wp_footer</code>
				</label>
				<span class="wbs-code-lang-tag">HTML / Script</span>
			</div>
			<div class="wbs-code-editor-wrap">
				<textarea id="wbs_footer_tracking" name="Analytics[Footer_Tracking_Code]" rows="7" class="wbs-code-editor" placeholder="&lt;!-- Example: Heatmaps, live chat widgets, or conversion tracking triggers --&gt;&#10;&lt;script&gt;&#10;  // Safe footer script&#10;&lt;/script&gt;"><?php echo esc_textarea( $analytics['Footer_Tracking_Code'] ?? '' ); ?></textarea>
			</div>
			<span class="wbs-field-hint">Inserted directly above closing <code>&lt;/body&gt;</code> tag. Ideal for non-critical scripts, conversion pixels, and live chat widgets to maintain fast page load.</span>
		</div>

		<!-- Security Rules Banner -->
		<div class="wbs-security-banner">
			<h4><span class="dashicons dashicons-shield-alt"></span> Data Validation &amp; Anti-Tamper Security Rules</h4>
			<ul>
				<li><strong>No PHP Execution:</strong> Server-side PHP tags (<code>&lt;?php</code>, <code>&lt;?=</code>) will immediately cause validation to fail and abort saving.</li>
				<li><strong>No Insecure Functions:</strong> Dangerous vectors including <code>eval()</code>, <code>Function()</code>, <code>document.cookie</code> theft, and <code>window.location</code> redirection hijacks are blocked.</li>
				<li><strong>No Event Injection:</strong> Inline event handlers (<code>onerror=</code>, <code>onload=</code>, <code>onclick=</code>) on arbitrary tags are rejected.</li>
				<li><strong>Clean ID Sanitization:</strong> All platform ID fields above are automatically stripped of HTML and script tags.</li>
			</ul>
		</div>

	</div>
</div>
