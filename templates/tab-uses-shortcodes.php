<?php
/**
 * Tab: Uses & Shortcodes (Documentation, Shortcodes Reference & Technical Limitations)
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-book-alt"></span> Uses, Shortcodes &amp; Integration Guide</h2>
			<p>Explore all available WordPress shortcodes, PHP template functions, automated hooks, and system limitations.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<p style="margin-top: 0; font-size: 14px; color: var(--wbs-gray-700); line-height: 1.6;">
			Every single piece of data saved in this setup system is dynamically accessible across your WordPress website &mdash; inside <strong>Block Editor (Gutenberg)</strong>, <strong>Classic Editor</strong>, <strong>Page Builders (Elementor, Divi, Bricks)</strong>, <strong>Widgets</strong>, and custom <strong>PHP Theme Templates</strong>.
		</p>
	</div>
</div>

<!-- 1. SHORTCODES REFERENCE DIRECTORY -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-shortcode"></span> WordPress Shortcodes Reference</h2>
			<p>Insert dynamic business attributes anywhere on your site without hardcoding.</p>
		</div>
	</div>
	<div class="wbs-card-body" style="padding: 0;">

		<table class="wbs-docs-table" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
			<thead>
				<tr style="background: var(--wbs-gray-100); border-bottom: 2px solid var(--wbs-gray-200);">
					<th style="padding: 12px 16px; width: 22%;">Shortcode</th>
					<th style="padding: 12px 16px; width: 28%;">Attributes &amp; Options</th>
					<th style="padding: 12px 16px; width: 30%;">Output Description</th>
					<th style="padding: 12px 16px; width: 20%;">Example</th>
				</tr>
			</thead>
			<tbody>
				<!-- General -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_name]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">&mdash;</td>
					<td style="padding: 12px 16px;">Outputs registered business name (falls back to site title).</td>
					<td style="padding: 12px 16px;"><code>[business_name]</code></td>
				</tr>

				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_tagline]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">&mdash;</td>
					<td style="padding: 12px 16px;">Outputs business tagline or slogan.</td>
					<td style="padding: 12px 16px;"><code>[business_tagline]</code></td>
				</tr>

				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_description]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">&mdash;</td>
					<td style="padding: 12px 16px;">Outputs corporate business description with formatted paragraphs.</td>
					<td style="padding: 12px 16px;"><code>[business_description]</code></td>
				</tr>

				<!-- Universal -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200); background: #fdfefe;">
					<td style="padding: 12px 16px;"><code>[business_data]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>group="General|Contact|..."</code><br>
						<code>key="Founded_Year|Currency|..."</code><br>
						<code>default="..."</code>
					</td>
					<td style="padding: 12px 16px;">Universal dynamic retriever for any key across all configuration tabs.</td>
					<td style="padding: 12px 16px;"><code>[business_data group="General" key="Founded_Year"]</code></td>
				</tr>

				<!-- Contact: Phone & Email -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_phone]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>index="0"</code> (0 to 9)<br>
						<code>link="true|false"</code><br>
						<code>class="..."</code>
					</td>
					<td style="padding: 12px 16px;">Outputs registered phone number. Set <code>link="true"</code> to generate clickable <code>tel:</code> link.</td>
					<td style="padding: 12px 16px;"><code>[business_phone index="0" link="true"]</code></td>
				</tr>

				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_email]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>index="0"</code> (0 to 9)<br>
						<code>link="true|false"</code><br>
						<code>class="..."</code>
					</td>
					<td style="padding: 12px 16px;">Outputs obfuscated antispam email. Set <code>link="true"</code> to generate clickable <code>mailto:</code> link.</td>
					<td style="padding: 12px 16px;"><code>[business_email index="0" link="true"]</code></td>
				</tr>

				<!-- Contact: Address -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_address]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>index="0"</code> (0 to 9)<br>
						<code>format="inline|full|city|country|state|zipcode|line1|geo"</code>
					</td>
					<td style="padding: 12px 16px;">Outputs formatted address. <code>format="full"</code> generates semantic <code>&lt;address&gt;</code> HTML block.</td>
					<td style="padding: 12px 16px;"><code>[business_address index="0" format="full"]</code></td>
				</tr>

				<!-- WhatsApp -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_whatsapp]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>link="true|false"</code><br>
						<code>text="Chat on WhatsApp"</code><br>
						<code>class="..."</code>
					</td>
					<td style="padding: 12px 16px;">Generates direct WhatsApp click-to-chat button with prefilled customer assistance text.</td>
					<td style="padding: 12px 16px;"><code>[business_whatsapp text="Contact via WhatsApp"]</code></td>
				</tr>

				<!-- Branding -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_logo]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>type="Primary Logo|White Logo|Black Logo|Short Logo"</code><br>
						<code>output="tag|url"</code><br>
						<code>class="..."</code>
					</td>
					<td style="padding: 12px 16px;">Renders brand logo <code>&lt;img&gt;</code> tag with alt text, or returns direct raw URL with <code>output="url"</code>.</td>
					<td style="padding: 12px 16px;"><code>[business_logo type="White Logo" class="header-logo"]</code></td>
				</tr>

				<!-- Operating Hours -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_hours]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>day="all|today|Monday|..."</code><br>
						<code>format="table|inline"</code>
					</td>
					<td style="padding: 12px 16px;">Renders complete 7-day schedule table or outputs today's operating hours status.</td>
					<td style="padding: 12px 16px;"><code>[business_hours day="today"]</code><br><code>[business_hours format="table"]</code></td>
				</tr>

				<!-- Social Media -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_social]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>platform="Facebook|Instagram|Linkedin|..."</code><br>
						<code>link="true|false"</code><br>
						<code>text="..."</code>
					</td>
					<td style="padding: 12px 16px;">Outputs verified external social profile link with security attributes (<code>rel="noopener noreferrer"</code>).</td>
					<td style="padding: 12px 16px;"><code>[business_social platform="Linkedin" link="true"]</code></td>
				</tr>

				<!-- Legal Codes -->
				<tr style="border-bottom: 1px solid var(--wbs-gray-200);">
					<td style="padding: 12px 16px;"><code>[business_legal_code]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>code="GST|PAN|CIN|EIN|DUNS|UDYAM|..."</code>
					</td>
					<td style="padding: 12px 16px;">Displays tax identification or corporate incorporation number in invoices or footers.</td>
					<td style="padding: 12px 16px;"><code>[business_legal_code code="GST"]</code></td>
				</tr>

				<!-- Legal Documents -->
				<tr>
					<td style="padding: 12px 16px;"><code>[business_legal_doc]</code></td>
					<td style="padding: 12px 16px; color: var(--wbs-gray-600);">
						<code>doc="Privacy_Policy|Terms_And_Conditions|..."</code><br>
						<code>link="true|false"</code><br>
						<code>text="..."</code>
					</td>
					<td style="padding: 12px 16px;">Generates compliance hyperlinks to official legal terms or policy pages.</td>
					<td style="padding: 12px 16px;"><code>[business_legal_doc doc="Privacy_Policy" link="true"]</code></td>
				</tr>
			</tbody>
		</table>

	</div>
</div>

<!-- 2. PHP DEVELOPER TEMPLATE FUNCTIONS -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-editor-code"></span> PHP Developer Functions (Theme &amp; Plugin Integration)</h2>
			<p>Native PHP helper functions for template files such as <code>header.php</code>, <code>footer.php</code>, and <code>single.php</code>.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<div class="wbs-grid-2">
			<div>
				<h4 style="margin-top: 0; font-size: 13px; color: var(--wbs-dark);">Retrieving Specific Values</h4>
				<pre style="background: #0f172a; color: #38bdf8; padding: 14px; border-radius: 6px; font-size: 12px; line-height: 1.5; overflow-x: auto;">// 1. Get company name:
$name = wbs_get_option( 'General', 'Business_Name' );

// 2. Get primary brand logo URL:
$logo_url = wbs_get_logo( 'Primary Logo' );

// 3. Get primary contact details:
$phone   = wbs_get_primary_phone();
$email   = wbs_get_primary_email();
$address = wbs_get_primary_address_text();

// 4. Get social channel:
$linkedin = wbs_get_option( 'Social_Media', 'Linkedin' );</pre>
			</div>

			<div>
				<h4 style="margin-top: 0; font-size: 13px; color: var(--wbs-dark);">Complete Data Array Retrieval</h4>
				<pre style="background: #0f172a; color: #38bdf8; padding: 14px; border-radius: 6px; font-size: 12px; line-height: 1.5; overflow-x: auto;">// Retrieve entire multi-dimensional configuration:
$all_data = wbs_get_all_options();

// Access specific sub-arrays directly:
$hours     = $all_data['Business_Hours'];
$analytics = $all_data['Analytics'];
$floating  = $all_data['Theme_Options']['Floating_btn'];

// Check if a feature is enabled:
if ( ! empty( $analytics['Enabled'] ) ) {
    // Analytics is active
}</pre>
			</div>
		</div>

	</div>
</div>

<!-- 3. AUTOMATED HOOKS & EXECUTION PIPELINE -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-admin-plugins"></span> Automated Lifecycle Injection Hooks</h2>
			<p>Standard WordPress frontend hooks automatically powered by this plugin.</p>
		</div>
	</div>
	<div class="wbs-card-body">
		<div class="wbs-grid-3">

			<div style="background: var(--wbs-gray-50); padding: 16px; border: 1px solid var(--wbs-gray-200); border-radius: 6px;">
				<h4 style="margin: 0 0 8px 0; color: #0284c7; display: flex; align-items: center; gap: 6px;">
					<span class="dashicons dashicons-arrow-up-alt2"></span> <code>wp_head</code> (Priority 1)
				</h4>
				<ul style="margin: 0; padding-left: 18px; font-size: 12px; color: var(--wbs-gray-700); line-height: 1.6;">
					<li>Google Search Console verification meta tag</li>
					<li>Bing Webmaster Tools (<code>msvalidate.01</code>)</li>
					<li>Google Tag Manager (GTM) container script</li>
					<li>Google Analytics 4 (<code>gtag.js</code>) library</li>
					<li>Microsoft Clarity project tracking script</li>
					<li>Meta / Facebook Pixel base tracker</li>
					<li>Pinterest &amp; Ahrefs domain verifications</li>
					<li>Google Schema JSON-LD Entity Graph</li>
					<li>Custom Verified <strong>HEAD Tracking Code</strong></li>
				</ul>
			</div>

			<div style="background: var(--wbs-gray-50); padding: 16px; border: 1px solid var(--wbs-gray-200); border-radius: 6px;">
				<h4 style="margin: 0 0 8px 0; color: #0284c7; display: flex; align-items: center; gap: 6px;">
					<span class="dashicons dashicons-editor-insertmore"></span> <code>wp_body_open</code> (Priority 1)
				</h4>
				<ul style="margin: 0; padding-left: 18px; font-size: 12px; color: var(--wbs-gray-700); line-height: 1.6;">
					<li>Google Tag Manager <code>&lt;noscript&gt;&lt;iframe&gt;</code> fallback</li>
					<li>Meta / Facebook Pixel <code>&lt;noscript&gt;&lt;img&gt;</code> fallback</li>
					<li>Accessibility skip markers</li>
					<li>Custom Verified <strong>BODY Tracking Code</strong></li>
				</ul>
			</div>

			<div style="background: var(--wbs-gray-50); padding: 16px; border: 1px solid var(--wbs-gray-200); border-radius: 6px;">
				<h4 style="margin: 0 0 8px 0; color: #0284c7; display: flex; align-items: center; gap: 6px;">
					<span class="dashicons dashicons-arrow-down-alt2"></span> <code>wp_footer</code> (Priority 99)
				</h4>
				<ul style="margin: 0; padding-left: 18px; font-size: 12px; color: var(--wbs-gray-700); line-height: 1.6;">
					<li>Matomo Analytics JavaScript tracker snippet</li>
					<li>LinkedIn Insight Tag asynchronous loader</li>
					<li>Twitter / X conversion tracking pixel</li>
					<li>Custom Verified <strong>FOOTER Tracking Code</strong></li>
				</ul>
			</div>

		</div>
	</div>
</div>

<!-- 4. SYSTEM LIMITATIONS & TECHNICAL BOUNDARIES -->
<div class="wbs-card" style="border-left: 4px solid #f59e0b;">
	<div class="wbs-card-header" style="background: #fffbeb;">
		<div>
			<h2 style="color: #92400e;"><span class="dashicons dashicons-warning" style="color: #f59e0b;"></span> System Limitations &amp; Operational Boundaries</h2>
			<p style="color: #b45309;">Please review these architectural constraints and security rules before configuring production environments.</p>
		</div>
	</div>
	<div class="wbs-card-body">

		<div class="wbs-grid-2">
			<div>
				<h4 style="margin-top: 0; font-size: 13px; color: var(--wbs-dark);">1. Repeater Row Capacities</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					Dynamic repeaters for <strong>Phone Numbers</strong>, <strong>Email Inboxes</strong>, and <strong>Physical Addresses</strong> are capped at a maximum of <strong>10 entries</strong> each to maintain high performance. A <strong>minimum of 1 row</strong> is strictly required by the data contract (the delete button is disabled when only one entry remains).
				</p>

				<h4 style="font-size: 13px; color: var(--wbs-dark);">2. Security Validation in Analytics Code Editors</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					To protect against persistent Cross-Site Scripting (XSS) and server compromise, the custom code editors (HEAD, BODY, FOOTER) strictly block:
				</p>
				<ul style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5; padding-left: 18px;">
					<li>Server-side PHP tags (<code>&lt;?php</code>, <code>&lt;?=</code>)</li>
					<li>Dangerous functions (<code>eval()</code>, <code>Function()</code>)</li>
					<li>Cookie theft and redirection vectors (<code>document.cookie</code>, <code>window.location =</code>)</li>
					<li>Inline event handlers (<code>onerror=</code>, <code>onload=</code>, <code>onclick=</code>)</li>
				</ul>

				<h4 style="font-size: 13px; color: var(--wbs-dark);">3. Theme Menu Prerequisites</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					Header and Footer dynamic navigation dropdowns query registered menus via <code>wp_get_nav_menus()</code>. At least one navigation menu must be created under <strong>Appearance &gt; Menus</strong> for options to be selectable.
				</p>
			</div>

			<div>
				<h4 style="margin-top: 0; font-size: 13px; color: var(--wbs-dark);">4. WordPress Media Library Capabilities</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					All Branding image selectors require the active administrator account to possess the standard <code>upload_files</code> capability. Stored assets save the absolute URL directly into the WordPress options table.
				</p>

				<h4 style="font-size: 13px; color: var(--wbs-dark);">5. Schema Page Binding Requirements</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					Multiselect page assignment selectors query pages with status <code>publish</code>. Draft, private, or trashed pages cannot be bound to structured data graphs until published.
				</p>

				<h4 style="font-size: 13px; color: var(--wbs-dark);">6. Object Caching &amp; Full-Page Cache Notice</h4>
				<p style="font-size: 12px; color: var(--wbs-gray-600); line-height: 1.5;">
					Configuration is stored in the <code>wp_options</code> table under the key <code>business_setup_options</code> with autoload enabled. When using Redis, Memcached, or full-page caching plugins (e.g. WP Rocket, LiteSpeed, W3 Total Cache), purge your page cache after saving settings to ensure immediate frontend propagation.
				</p>
			</div>
		</div>

	</div>
</div>
