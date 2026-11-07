<?php
/**
 * Public Helper Functions and Frontend Output
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve all business setup options
 *
 * @return array
 */
function wbs_get_all_options() {
	$options = get_option( 'business_setup_options', array() );
	$defaults = wp_business_setup_get_default_schema();
	return wp_parse_args( $options, $defaults );
}

/**
 * Get a specific option or group of options
 *
 * @param string      $group   Group name (e.g., 'General', 'Branding', 'Contact', 'Social_Media')
 * @param string|null $key     Key name inside the group (e.g., 'Business_Name', 'Primary Logo')
 * @param mixed       $default Default fallback value
 * @return mixed
 */
function wbs_get_option( $group, $key = null, $default = null ) {
	$all = wbs_get_all_options();

	// Support Theme_Options lookup with fallback to Header_And_Footer
	if ( 'Theme_Options' === $group && ! isset( $all['Theme_Options'] ) && isset( $all['Header_And_Footer'] ) ) {
		if ( null !== $key && isset( $all['Header_And_Footer'][ $key ] ) ) {
			return $all['Header_And_Footer'][ $key ];
		}
	}

	if ( ! isset( $all[ $group ] ) ) {
		return $default;
	}
	if ( null === $key ) {
		return $all[ $group ];
	}
	return $all[ $group ][ $key ] ?? $default;
}

/**
 * Get Brand Logo URL
 *
 * @param string $type 'Primary Logo', 'Short Logo', 'White Logo', 'Black Logo'
 * @return string URL of logo
 */
function wbs_get_logo( $type = 'Primary Logo' ) {
	return wbs_get_option( 'Branding', $type, '' );
}

/**
 * Get Primary Phone Number
 *
 * @return string
 */
function wbs_get_primary_phone() {
	$phones = wbs_get_option( 'Contact', 'phone', array() );
	return ! empty( $phones[0] ) ? $phones[0] : '';
}

/**
 * Get Primary Email Address
 *
 * @return string
 */
function wbs_get_primary_email() {
	$emails = wbs_get_option( 'Contact', 'email', array() );
	return ! empty( $emails[0] ) ? $emails[0] : '';
}

/**
 * Get Primary Address as Formatted String
 *
 * @return string
 */
function wbs_get_primary_address_text() {
	$addresses = wbs_get_option( 'Contact', 'address', array() );
	if ( empty( $addresses[0] ) || ! is_array( $addresses[0] ) ) {
		return '';
	}
	$addr = $addresses[0];
	$parts = array_filter( array(
		$addr[0] ?? '',
		$addr[1] ?? '',
		$addr[2] ?? '',
		$addr[3] ?? '',
		$addr[5] ?? '',
		$addr[4] ?? '',
	) );
	return implode( ', ', $parts );
}

/**
 * Automatically output JSON-LD Structured Data in head if enabled
 */
function wbs_render_structured_data_in_head() {
	$schema_settings = wbs_get_option( 'Schema', null, array() );
	if ( empty( $schema_settings['Enabled'] ) ) {
		return;
	}

	$current_post_id = get_queried_object_id();
	$general = wbs_get_option( 'General', null, array() );
	$contact = wbs_get_option( 'Contact', null, array() );
	$social  = wbs_get_option( 'Social_Media', null, array() );
	$logo    = wbs_get_logo( 'Primary Logo' );

	$graph = array();

	// 1. Organization Schema
	if ( ! empty( $schema_settings['Organization_schema'] ) ) {
		$target_pages = (array) ( $schema_settings['Organization_schema_page'] ?? array() );
		if ( empty( $target_pages ) || is_front_page() || in_array( (string) $current_post_id, $target_pages, true ) ) {
			$same_as = array();
			if ( is_array( $social ) ) {
				foreach ( $social as $k => $v ) {
					if ( 'Other' !== $k && ! empty( $v ) && filter_var( $v, FILTER_VALIDATE_URL ) ) {
						$same_as[] = esc_url_raw( $v );
					}
				}
				if ( ! empty( $social['Other'] ) && is_array( $social['Other'] ) ) {
					foreach ( $social['Other'] as $item ) {
						if ( ! empty( $item['value'] ) && filter_var( $item['value'], FILTER_VALIDATE_URL ) ) {
							$same_as[] = esc_url_raw( $item['value'] );
						}
					}
				}
			}

			$org = array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Organization',
				'name'        => ! empty( $general['Business_Name'] ) ? $general['Business_Name'] : get_bloginfo( 'name' ),
				'url'         => ! empty( $general['Website_URL'] ) ? $general['Website_URL'] : home_url(),
				'description' => $general['Business_Description'] ?? '',
			);

			if ( ! empty( $logo ) ) {
				$org['logo'] = esc_url_raw( $logo );
			}
			if ( ! empty( $same_as ) ) {
				$org['sameAs'] = array_values( array_unique( $same_as ) );
			}

			$phones = (array) ( $contact['phone'] ?? array() );
			$emails = (array) ( $contact['email'] ?? array() );
			if ( ! empty( $phones[0] ) || ! empty( $emails[0] ) ) {
				$cp = array(
					'@type'       => 'ContactPoint',
					'contactType' => 'customer support',
				);
				if ( ! empty( $phones[0] ) ) {
					$cp['telephone'] = sanitize_text_field( $phones[0] );
				}
				if ( ! empty( $emails[0] ) ) {
					$cp['email'] = sanitize_email( $emails[0] );
				}
				$org['contactPoint'] = $cp;
			}

			$graph[] = $org;
		}
	}

	// 2. LocalBusiness Schema
	if ( ! empty( $schema_settings['Localbusines_schema'] ) ) {
		$target_pages = (array) ( $schema_settings['Localbusines_schema_page'] ?? array() );
		if ( empty( $target_pages ) || in_array( (string) $current_post_id, $target_pages, true ) ) {
			$addresses = (array) ( $contact['address'] ?? array() );
			$primary_addr = $addresses[0] ?? array();

			$local_business = array(
				'@context' => 'https://schema.org',
				'@type'    => 'LocalBusiness',
				'name'     => ! empty( $general['Business_Name'] ) ? $general['Business_Name'] : get_bloginfo( 'name' ),
				'image'    => $logo ? esc_url_raw( $logo ) : '',
				'url'      => ! empty( $general['Website_URL'] ) ? $general['Website_URL'] : home_url(),
				'telephone'=> wbs_get_primary_phone(),
			);

			if ( ! empty( $primary_addr[0] ) ) {
				$local_business['address'] = array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => trim( ( $primary_addr[0] ?? '' ) . ' ' . ( $primary_addr[1] ?? '' ) ),
					'addressLocality' => $primary_addr[2] ?? '',
					'addressRegion'   => $primary_addr[3] ?? '',
					'postalCode'      => $primary_addr[5] ?? '',
					'addressCountry'  => $primary_addr[4] ?? '',
				);
			}

			if ( ! empty( $primary_addr[6] ) && strpos( $primary_addr[6], ',' ) !== false ) {
				$coords = explode( ',', $primary_addr[6] );
				$local_business['geo'] = array(
					'@type'     => 'GeoCoordinates',
					'latitude'  => trim( $coords[0] ),
					'longitude' => trim( $coords[1] ),
				);
			}

			$graph[] = $local_business;
		}
	}

	// 3. Website Schema
	if ( ! empty( $schema_settings['Website_schema'] ) && is_front_page() ) {
		$graph[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url(),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
	}

	if ( ! empty( $graph ) ) {
		echo "\n<!-- Business Setup Structured Data -->\n";
		echo '<script type="application/ld+json">' . wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n<!-- /Business Setup Structured Data -->\n";
	}
}
add_action( 'wp_head', 'wbs_render_structured_data_in_head' );

/**
 * Output HEAD tracking codes, verification meta tags & custom scripts
 */
function wbs_output_head_tracking() {
	$analytics = wbs_get_option( 'Analytics', null, array() );
	if ( empty( $analytics['Enabled'] ) ) {
		return;
	}

	echo "\n<!-- Business Setup Analytics: HEAD -->\n";

	// Verification Meta Tags
	$verifications = array(
		'Google_Site_Verification'     => 'google-site-verification',
		'Bing_Verification_ID'         => 'msvalidate.01',
		'Pinterest_Tag_ID'             => 'p:domain_verify',
		'Facebook_Domain_Verification' => 'facebook-domain-verification',
		'Ahrefs_Site_Verification'     => 'ahrefs-site-verification',
		'Norton_Safe_Web'              => 'norton-safeweb-site-verification',
		'Web_Of_Trust'                 => 'wot-verification',
	);

	foreach ( $verifications as $opt_key => $meta_name ) {
		if ( ! empty( $analytics[ $opt_key ] ) ) {
			printf(
				'<meta name="%1$s" content="%2$s" />' . "\n",
				esc_attr( $meta_name ),
				esc_attr( $analytics[ $opt_key ] )
			);
		}
	}

	// GTM Head Snippet
	if ( ! empty( $analytics['GTM_Container_ID'] ) ) {
		$gtm_id = preg_replace( '/[^A-Za-z0-9\-]/', '', $analytics['GTM_Container_ID'] );
		?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
<!-- End Google Tag Manager -->
		<?php
	}

	// GA4 Measurement Snippet
	if ( ! empty( $analytics['GA4_Measurement_ID'] ) ) {
		$ga4_id = preg_replace( '/[^A-Za-z0-9\-]/', '', $analytics['GA4_Measurement_ID'] );
		?>
<!-- Google Analytics 4 (GA4) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga4_id ); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo esc_js( $ga4_id ); ?>');
</script>
<!-- End Google Analytics 4 (GA4) -->
		<?php
	}

	// Microsoft Clarity Snippet
	if ( ! empty( $analytics['Clarity_Project_ID'] ) ) {
		$clarity_id = preg_replace( '/[^A-Za-z0-9]/', '', $analytics['Clarity_Project_ID'] );
		?>
<!-- Microsoft Clarity -->
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "<?php echo esc_js( $clarity_id ); ?>");
</script>
<!-- End Microsoft Clarity -->
		<?php
	}

	// Meta / Facebook Pixel Code
	if ( ! empty( $analytics['Meta_Pixel_ID'] ) ) {
		$meta_pixel = preg_replace( '/[^0-9]/', '', $analytics['Meta_Pixel_ID'] );
		?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js( $meta_pixel ); ?>');
fbq('track', 'PageView');
</script>
<!-- End Meta Pixel Code -->
		<?php
	}

	// Verified Custom HEAD Tracking Code
	if ( ! empty( $analytics['Head_Tracking_Code'] ) ) {
		echo $analytics['Head_Tracking_Code'] . "\n";
	}

	echo "<!-- /Business Setup Analytics: HEAD -->\n";
}
add_action( 'wp_head', 'wbs_output_head_tracking', 1 );

/**
 * Output BODY tracking codes (opening body tags & noscript fallbacks)
 */
function wbs_output_body_tracking() {
	$analytics = wbs_get_option( 'Analytics', null, array() );
	if ( empty( $analytics['Enabled'] ) ) {
		return;
	}

	echo "\n<!-- Business Setup Analytics: BODY (Opening) -->\n";

	// GTM Body Noscript
	if ( ! empty( $analytics['GTM_Container_ID'] ) ) {
		$gtm_id = preg_replace( '/[^A-Za-z0-9\-]/', '', $analytics['GTM_Container_ID'] );
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	// Meta Pixel Noscript
	if ( ! empty( $analytics['Meta_Pixel_ID'] ) ) {
		$meta_pixel = preg_replace( '/[^0-9]/', '', $analytics['Meta_Pixel_ID'] );
		?>
<!-- Meta Pixel (noscript) -->
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo esc_attr( $meta_pixel ); ?>&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel (noscript) -->
		<?php
	}

	// Verified Custom BODY Tracking Code
	if ( ! empty( $analytics['Body_Tracking_Code'] ) ) {
		echo $analytics['Body_Tracking_Code'] . "\n";
	}

	echo "<!-- /Business Setup Analytics: BODY -->\n";
}
add_action( 'wp_body_open', 'wbs_output_body_tracking', 1 );

/**
 * Output FOOTER tracking codes (closing body scripts)
 */
function wbs_output_footer_tracking() {
	$analytics = wbs_get_option( 'Analytics', null, array() );
	if ( empty( $analytics['Enabled'] ) ) {
		return;
	}

	echo "\n<!-- Business Setup Analytics: FOOTER -->\n";

	// Matomo Tracking Code
	if ( ! empty( $analytics['Matomo_URL'] ) && ! empty( $analytics['Matomo_Site_ID'] ) ) {
		$matomo_url = trailingslashit( esc_url_raw( $analytics['Matomo_URL'] ) );
		$site_id    = preg_replace( '/[^A-Za-z0-9]/', '', $analytics['Matomo_Site_ID'] );
		?>
<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="<?php echo esc_url( $matomo_url ); ?>";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '<?php echo esc_js( $site_id ); ?>']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
		<?php
	}

	// LinkedIn Insight Tag
	if ( ! empty( $analytics['LinkedIn_Partner_ID'] ) ) {
		$li_id = preg_replace( '/[^0-9]/', '', $analytics['LinkedIn_Partner_ID'] );
		?>
<!-- LinkedIn Insight -->
<script type="text/javascript">
_linkedin_partner_id = "<?php echo esc_js( $li_id ); ?>";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<!-- End LinkedIn Insight -->
		<?php
	}

	// Twitter / X Pixel
	if ( ! empty( $analytics['Twitter_Pixel_ID'] ) ) {
		$tw_id = preg_replace( '/[^A-Za-z0-9\-]/', '', $analytics['Twitter_Pixel_ID'] );
		?>
<!-- Twitter conversion tracking -->
<script>
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
twq('config','<?php echo esc_js( $tw_id ); ?>');
</script>
<!-- End Twitter conversion tracking -->
		<?php
	}

	// Verified Custom FOOTER Tracking Code
	if ( ! empty( $analytics['Footer_Tracking_Code'] ) ) {
		echo $analytics['Footer_Tracking_Code'] . "\n";
	}

	echo "<!-- /Business Setup Analytics: FOOTER -->\n";
}
add_action( 'wp_footer', 'wbs_output_footer_tracking', 99 );

// =========================================================================
// WORDPRESS SHORTCODES FOR BUSINESS DATA
// =========================================================================

/**
 * Universal Shortcode: [business_data group="General" key="Business_Name" default=""]
 */
function wbs_shortcode_business_data( $atts ) {
	$atts = shortcode_atts(
		array(
			'group'   => 'General',
			'key'     => 'Business_Name',
			'default' => '',
		),
		$atts,
		'business_data'
	);

	$val = wbs_get_option( $atts['group'], $atts['key'], $atts['default'] );
	if ( is_array( $val ) ) {
		return esc_html( wp_json_encode( $val ) );
	}
	return esc_html( (string) $val );
}
add_shortcode( 'business_data', 'wbs_shortcode_business_data' );

/**
 * Shortcode: [business_name]
 */
function wbs_shortcode_business_name() {
	$name = wbs_get_option( 'General', 'Business_Name' );
	return esc_html( ! empty( $name ) ? $name : get_bloginfo( 'name' ) );
}
add_shortcode( 'business_name', 'wbs_shortcode_business_name' );

/**
 * Shortcode: [business_tagline]
 */
function wbs_shortcode_business_tagline() {
	$tagline = wbs_get_option( 'General', 'Business_Tagline' );
	return esc_html( ! empty( $tagline ) ? $tagline : get_bloginfo( 'description' ) );
}
add_shortcode( 'business_tagline', 'wbs_shortcode_business_tagline' );

/**
 * Shortcode: [business_description]
 */
function wbs_shortcode_business_description() {
	$desc = wbs_get_option( 'General', 'Business_Description', '' );
	return wp_kses_post( wpautop( $desc ) );
}
add_shortcode( 'business_description', 'wbs_shortcode_business_description' );

/**
 * Shortcode: [business_phone index="0" link="false" class=""]
 */
function wbs_shortcode_business_phone( $atts ) {
	$atts = shortcode_atts(
		array(
			'index' => 0,
			'link'  => 'false',
			'class' => 'wbs-phone-link',
		),
		$atts,
		'business_phone'
	);

	$phones = wbs_get_option( 'Contact', 'phone', array() );
	$idx    = absint( $atts['index'] );
	$phone  = $phones[ $idx ] ?? ( $phones[0] ?? '' );

	if ( empty( $phone ) ) {
		return '';
	}

	if ( 'true' === $atts['link'] || '1' === $atts['link'] || true === $atts['link'] ) {
		$clean_tel = preg_replace( '/[^0-9\+]/', '', $phone );
		return sprintf(
			'<a href="tel:%s" class="%s">%s</a>',
			esc_attr( $clean_tel ),
			esc_attr( $atts['class'] ),
			esc_html( $phone )
		);
	}

	return esc_html( $phone );
}
add_shortcode( 'business_phone', 'wbs_shortcode_business_phone' );

/**
 * Shortcode: [business_email index="0" link="false" class=""]
 */
function wbs_shortcode_business_email( $atts ) {
	$atts = shortcode_atts(
		array(
			'index' => 0,
			'link'  => 'false',
			'class' => 'wbs-email-link',
		),
		$atts,
		'business_email'
	);

	$emails = wbs_get_option( 'Contact', 'email', array() );
	$idx    = absint( $atts['index'] );
	$email  = $emails[ $idx ] ?? ( $emails[0] ?? '' );

	if ( empty( $email ) ) {
		return '';
	}

	if ( 'true' === $atts['link'] || '1' === $atts['link'] || true === $atts['link'] ) {
		return sprintf(
			'<a href="mailto:%s" class="%s">%s</a>',
			esc_attr( antispambot( $email ) ),
			esc_attr( $atts['class'] ),
			esc_html( antispambot( $email ) )
		);
	}

	return esc_html( antispambot( $email ) );
}
add_shortcode( 'business_email', 'wbs_shortcode_business_email' );

/**
 * Shortcode: [business_address index="0" format="full|inline|line1|city|state|country|zipcode|geo"]
 */
function wbs_shortcode_business_address( $atts ) {
	$atts = shortcode_atts(
		array(
			'index'  => 0,
			'format' => 'inline',
		),
		$atts,
		'business_address'
	);

	$addresses = wbs_get_option( 'Contact', 'address', array() );
	$idx       = absint( $atts['index'] );
	$addr      = $addresses[ $idx ] ?? ( $addresses[0] ?? array() );

	if ( empty( $addr ) || ! is_array( $addr ) ) {
		return '';
	}

	$l1   = $addr[0] ?? '';
	$l2   = $addr[1] ?? '';
	$city = $addr[2] ?? '';
	$st   = $addr[3] ?? '';
	$ctry = $addr[4] ?? '';
	$zip  = $addr[5] ?? '';
	$geo  = $addr[6] ?? '';

	switch ( $atts['format'] ) {
		case 'line1':
			return esc_html( $l1 );
		case 'city':
			return esc_html( $city );
		case 'state':
			return esc_html( $st );
		case 'country':
			return esc_html( $ctry );
		case 'zipcode':
			return esc_html( $zip );
		case 'geo':
			return esc_html( $geo );
		case 'full':
			$html = '<address class="wbs-address-block">';
			if ( $l1 ) $html .= '<span class="wbs-addr-l1">' . esc_html( $l1 ) . '</span><br>';
			if ( $l2 ) $html .= '<span class="wbs-addr-l2">' . esc_html( $l2 ) . '</span><br>';
			$city_line = array_filter( array( $city, $st, $zip ) );
			if ( $city_line ) $html .= '<span class="wbs-addr-city">' . esc_html( implode( ', ', $city_line ) ) . '</span><br>';
			if ( $ctry ) $html .= '<span class="wbs-addr-country">' . esc_html( $ctry ) . '</span>';
			$html .= '</address>';
			return $html;
		case 'inline':
		default:
			$parts = array_filter( array( $l1, $l2, $city, $st, $zip, $ctry ) );
			return esc_html( implode( ', ', $parts ) );
	}
}
add_shortcode( 'business_address', 'wbs_shortcode_business_address' );

/**
 * Shortcode: [business_logo type="Primary Logo|Short Logo|White Logo|Black Logo" output="tag|url" class=""]
 */
function wbs_shortcode_business_logo( $atts ) {
	$atts = shortcode_atts(
		array(
			'type'   => 'Primary Logo',
			'output' => 'tag',
			'class'  => 'wbs-brand-logo',
			'alt'    => '',
		),
		$atts,
		'business_logo'
	);

	$logo_url = wbs_get_logo( $atts['type'] );
	if ( empty( $logo_url ) ) {
		return '';
	}

	if ( 'url' === $atts['output'] ) {
		return esc_url( $logo_url );
	}

	$alt = ! empty( $atts['alt'] ) ? $atts['alt'] : wbs_shortcode_business_name();
	return sprintf(
		'<img src="%s" alt="%s" class="%s" />',
		esc_url( $logo_url ),
		esc_attr( $alt ),
		esc_attr( $atts['class'] )
	);
}
add_shortcode( 'business_logo', 'wbs_shortcode_business_logo' );

/**
 * Shortcode: [business_hours day="all|today|Monday|Sunday..." format="table|list|inline"]
 */
function wbs_shortcode_business_hours( $atts ) {
	$atts = shortcode_atts(
		array(
			'day'    => 'all',
			'format' => 'table',
		),
		$atts,
		'business_hours'
	);

	$hours = wbs_get_option( 'Business_Hours', null, array() );
	if ( empty( $hours ) || ! is_array( $hours ) ) {
		return '';
	}

	$days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );

	if ( 'today' === $atts['day'] ) {
		$today_name = date( 'l' );
		$day_data   = $hours[ $today_name ] ?? array();
		if ( empty( $day_data['Enabled'] ) ) {
			return esc_html( $today_name . ': Closed' );
		}
		return esc_html( $today_name . ': ' . ( $day_data['open'] ?? '' ) . ' - ' . ( $day_data['close'] ?? '' ) );
	}

	if ( in_array( ucfirst( $atts['day'] ), $days, true ) ) {
		$d = ucfirst( $atts['day'] );
		$day_data = $hours[ $d ] ?? array();
		if ( empty( $day_data['Enabled'] ) ) {
			return esc_html( $d . ': Closed' );
		}
		return esc_html( $d . ': ' . ( $day_data['open'] ?? '' ) . ' - ' . ( $day_data['close'] ?? '' ) );
	}

	if ( 'inline' === $atts['format'] ) {
		$items = array();
		foreach ( $days as $d ) {
			$info = $hours[ $d ] ?? array();
			$status = ! empty( $info['Enabled'] ) ? ( $info['open'] . ' - ' . $info['close'] ) : 'Closed';
			$items[] = $d . ': ' . $status;
		}
		return esc_html( implode( ' | ', $items ) );
	}

	// HTML Table format
	$out = '<table class="wbs-hours-table" style="width:100%; border-collapse:collapse;">';
	$out .= '<tbody>';
	foreach ( $days as $d ) {
		$info     = $hours[ $d ] ?? array();
		$is_open  = ! empty( $info['Enabled'] );
		$time_str = $is_open ? esc_html( ( $info['open'] ?? '' ) . ' - ' . ( $info['close'] ?? '' ) ) : '<span style="color:#ef4444;font-weight:600;">Closed</span>';

		$out .= '<tr style="border-bottom:1px solid #f1f5f9; padding:6px 0;">';
		$out .= '<td style="font-weight:600; padding:6px 8px;">' . esc_html( $d ) . '</td>';
		$out .= '<td style="text-align:right; padding:6px 8px;">' . $time_str . '</td>';
		$out .= '</tr>';
	}
	$out .= '</tbody></table>';

	return $out;
}
add_shortcode( 'business_hours', 'wbs_shortcode_business_hours' );

/**
 * Shortcode: [business_social platform="Facebook" link="true"]
 */
function wbs_shortcode_business_social( $atts ) {
	$atts = shortcode_atts(
		array(
			'platform' => 'Facebook',
			'link'     => 'true',
			'text'     => '',
			'class'    => 'wbs-social-link',
		),
		$atts,
		'business_social'
	);

	$social = wbs_get_option( 'Social_Media', null, array() );
	$url    = $social[ $atts['platform'] ] ?? '';

	if ( empty( $url ) ) {
		return '';
	}

	if ( 'true' === $atts['link'] || '1' === $atts['link'] || true === $atts['link'] ) {
		$label = ! empty( $atts['text'] ) ? $atts['text'] : $atts['platform'];
		return sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer" class="%s">%s</a>',
			esc_url( $url ),
			esc_attr( $atts['class'] ),
			esc_html( $label )
		);
	}

	return esc_url( $url );
}
add_shortcode( 'business_social', 'wbs_shortcode_business_social' );

/**
 * Shortcode: [business_legal_code code="GST|PAN|CIN|EIN..."]
 */
function wbs_shortcode_business_legal_code( $atts ) {
	$atts = shortcode_atts(
		array(
			'code' => 'GST',
		),
		$atts,
		'business_legal_code'
	);

	$codes = wbs_get_option( 'Legal_Codes', null, array() );
	$val   = $codes[ $atts['code'] ] ?? '';
	return esc_html( (string) $val );
}
add_shortcode( 'business_legal_code', 'wbs_shortcode_business_legal_code' );

/**
 * Shortcode: [business_legal_doc doc="Privacy_Policy|Terms_And_Conditions..." link="true" text=""]
 */
function wbs_shortcode_business_legal_doc( $atts ) {
	$atts = shortcode_atts(
		array(
			'doc'   => 'Privacy_Policy',
			'link'  => 'true',
			'text'  => '',
			'class' => 'wbs-legal-link',
		),
		$atts,
		'business_legal_doc'
	);

	$docs = wbs_get_option( 'Legal_Documents', null, array() );
	$url  = $docs[ $atts['doc'] ] ?? '';

	if ( empty( $url ) ) {
		return '';
	}

	if ( 'true' === $atts['link'] || '1' === $atts['link'] || true === $atts['link'] ) {
		$label = ! empty( $atts['text'] ) ? $atts['text'] : ucwords( str_replace( '_', ' ', $atts['doc'] ) );
		return sprintf(
			'<a href="%s" class="%s">%s</a>',
			esc_url( $url ),
			esc_attr( $atts['class'] ),
			esc_html( $label )
		);
	}

	return esc_url( $url );
}
add_shortcode( 'business_legal_doc', 'wbs_shortcode_business_legal_doc' );

/**
 * Shortcode: [business_whatsapp link="true" text="Chat on WhatsApp" class=""]
 */
function wbs_shortcode_business_whatsapp( $atts ) {
	$atts = shortcode_atts(
		array(
			'link'  => 'true',
			'text'  => 'Chat on WhatsApp',
			'class' => 'wbs-whatsapp-btn',
		),
		$atts,
		'business_whatsapp'
	);

	$contact_wa  = wbs_get_option( 'Contact', 'whatsapp', '' );
	$floating_wa = wbs_get_option( 'Theme_Options', 'Floating_btn' );
	$number      = ! empty( $contact_wa ) ? $contact_wa : ( $floating_wa['Whatsapp']['Number'] ?? '' );
	$message     = $floating_wa['Whatsapp']['Message'] ?? 'Hello, I need assistance.';

	if ( empty( $number ) ) {
		return '';
	}

	$clean_number = preg_replace( '/[^0-9]/', '', $number );
	$wa_url       = 'https://wa.me/' . $clean_number . '?text=' . rawurlencode( $message );

	if ( 'true' === $atts['link'] || '1' === $atts['link'] || true === $atts['link'] ) {
		return sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer" class="%s">%s</a>',
			esc_url( $wa_url ),
			esc_attr( $atts['class'] ),
			esc_html( $atts['text'] )
		);
	}

	return esc_url( $wa_url );
}
add_shortcode( 'business_whatsapp', 'wbs_shortcode_business_whatsapp' );
