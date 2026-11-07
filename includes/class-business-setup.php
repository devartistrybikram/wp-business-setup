<?php
/**
 * Core Business Setup Controller
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Business_Setup_Admin {

	/**
	 * Hook suffix for plugin admin page
	 *
	 * @var string
	 */
	private $hook_suffix = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_post_wbs_save_options', array( $this, 'handle_save_options' ) );
		
		// AJAX Actions for Import, Export, Reset
		add_action( 'wp_ajax_wbs_export_settings_json', array( $this, 'ajax_export_json' ) );
		add_action( 'wp_ajax_wbs_import_settings_json', array( $this, 'ajax_import_json' ) );
		add_action( 'wp_ajax_wbs_reset_defaults', array( $this, 'ajax_reset_defaults' ) );
	}

	/**
	 * Register Admin Menu Page
	 * Requirement: Site name used as page level, placed right after Dashboard (position 3)
	 */
	public function register_admin_menu() {
		$site_name = get_bloginfo( 'name' );
		$page_title = $site_name . ' &mdash; Business Setup';
		$menu_title = ! empty( $site_name ) ? $site_name : 'Business Setup';

		// Position 3 places it immediately after Dashboard (which is at position 2)
		$this->hook_suffix = add_menu_page(
			$page_title,
			$menu_title,
			'manage_options',
			'business-setup',
			array( $this, 'render_admin_page' ),
			'dashicons-building',
			3
		);
	}

	/**
	 * Enqueue Styles and Scripts
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( $hook !== $this->hook_suffix && false === strpos( $hook, 'business-setup' ) ) {
			return;
		}

		// WordPress Media Uploader for Branding assets
		wp_enqueue_media();

		// Plugin Admin CSS
		wp_enqueue_style(
			'wbs-admin-style',
			WP_BUSINESS_SETUP_URL . 'assets/css/admin-style.css',
			array(),
			WP_BUSINESS_SETUP_VERSION
		);

		// Plugin Admin JS
		wp_enqueue_script(
			'wbs-admin-script',
			WP_BUSINESS_SETUP_URL . 'assets/js/admin-script.js',
			array( 'jquery' ),
			WP_BUSINESS_SETUP_VERSION,
			true
		);

		wp_localize_script(
			'wbs-admin-script',
			'wbsAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wbs_save_options_nonce' ),
			)
		);
	}

	/**
	 * Render the Settings Page
	 */
	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wp-business-setup' ) );
		}

		$options = wbs_get_all_options();
		include WP_BUSINESS_SETUP_DIR . 'templates/admin-page.php';
	}

	/**
	 * Handle POST form save
	 */
	public function handle_save_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'wp-business-setup' ) );
		}

		check_admin_referer( 'wbs_save_options_nonce', 'wbs_nonce' );

		$defaults = wp_business_setup_get_default_schema();
		$current  = wbs_get_all_options();

		$cleaned = array();

		// 1. General
		$general_in = isset( $_POST['General'] ) ? (array) $_POST['General'] : array();
		$cleaned['General'] = array(
			'Business_Name'        => sanitize_text_field( $general_in['Business_Name'] ?? '' ),
			'Alternate_Name'       => sanitize_text_field( $general_in['Alternate_Name'] ?? '' ),
			'Business_Tagline'     => sanitize_text_field( $general_in['Business_Tagline'] ?? '' ),
			'Business_Description' => sanitize_textarea_field( $general_in['Business_Description'] ?? '' ),
			'Business_Type'        => sanitize_text_field( $general_in['Business_Type'] ?? 'Corporation' ),
			'Website_URL'          => esc_url_raw( $general_in['Website_URL'] ?? '' ),
			'Founded_Year'         => sanitize_text_field( $general_in['Founded_Year'] ?? '' ),
			'Founder_Name'         => sanitize_text_field( $general_in['Founder_Name'] ?? '' ),
			'Number_of_Employees'  => sanitize_text_field( $general_in['Number_of_Employees'] ?? '' ),
			'Currency'             => sanitize_text_field( $general_in['Currency'] ?? 'USD' ),
			'Primary_Language'     => sanitize_text_field( $general_in['Primary_Language'] ?? 'English' ),
			'Available_Language'   => sanitize_text_field( $general_in['Available_Language'] ?? '' ),
		);

		// 2. Branding (file selector URLs)
		$branding_in = isset( $_POST['Branding'] ) ? (array) $_POST['Branding'] : array();
		$cleaned['Branding'] = array();
		foreach ( array( 'Primary Logo', 'Short Logo', 'White Logo', 'Black Logo', 'Banner Image', 'Banner File' ) as $b_key ) {
			$cleaned['Branding'][ $b_key ] = esc_url_raw( $branding_in[ $b_key ] ?? '' );
		}

		// 3. Contact (phone/email/address min 1, max 10)
		$contact_in = isset( $_POST['Contact'] ) ? (array) $_POST['Contact'] : array();
		
		// SMS & WhatsApp
		$sms      = sanitize_text_field( $contact_in['sms'] ?? '' );
		$whatsapp = sanitize_text_field( $contact_in['whatsapp'] ?? '' );

		// Phone numbers (max 10, min 1)
		$raw_phones = isset( $contact_in['phone'] ) && is_array( $contact_in['phone'] ) ? $contact_in['phone'] : array();
		$clean_phones = array();
		foreach ( $raw_phones as $p ) {
			$p_clean = sanitize_text_field( $p );
			if ( '' !== $p_clean ) {
				$clean_phones[] = $p_clean;
			}
			if ( count( $clean_phones ) >= 10 ) {
				break;
			}
		}
		if ( empty( $clean_phones ) ) {
			$clean_phones = array( '' ); // Rule: 1 field is must
		}

		// Email addresses (max 10, min 1)
		$raw_emails = isset( $contact_in['email'] ) && is_array( $contact_in['email'] ) ? $contact_in['email'] : array();
		$clean_emails = array();
		foreach ( $raw_emails as $e ) {
			$e_clean = sanitize_email( $e );
			if ( '' !== $e_clean ) {
				$clean_emails[] = $e_clean;
			}
			if ( count( $clean_emails ) >= 10 ) {
				break;
			}
		}
		if ( empty( $clean_emails ) ) {
			$clean_emails = array( '' ); // Rule: 1 field is must
		}

		// Addresses: [line 1, line 2, city, state, country, zipcode, geo] (max 10, min 1)
		$raw_addresses = isset( $contact_in['address'] ) && is_array( $contact_in['address'] ) ? $contact_in['address'] : array();
		$clean_addresses = array();
		foreach ( $raw_addresses as $addr ) {
			if ( is_array( $addr ) ) {
				$entry = array(
					sanitize_text_field( $addr[0] ?? '' ),
					sanitize_text_field( $addr[1] ?? '' ),
					sanitize_text_field( $addr[2] ?? '' ),
					sanitize_text_field( $addr[3] ?? '' ),
					sanitize_text_field( $addr[4] ?? '' ),
					sanitize_text_field( $addr[5] ?? '' ),
					sanitize_text_field( $addr[6] ?? '' ),
				);
				// If at least one subfield is filled or to preserve first row
				if ( array_filter( $entry ) || empty( $clean_addresses ) ) {
					$clean_addresses[] = $entry;
				}
			}
			if ( count( $clean_addresses ) >= 10 ) {
				break;
			}
		}
		if ( empty( $clean_addresses ) ) {
			$clean_addresses = array( array( '', '', '', '', '', '', '' ) ); // Rule: 1 row is must
		}

		$cleaned['Contact'] = array(
			'sms'      => $sms,
			'whatsapp' => $whatsapp,
			'phone'    => $clean_phones,
			'email'    => $clean_emails,
			'address'  => $clean_addresses,
		);

		// 4. Social Media + Other dynamic items
		$social_in = isset( $_POST['Social_Media'] ) ? (array) $_POST['Social_Media'] : array();
		$cleaned['Social_Media'] = array();
		foreach ( $defaults['Social_Media'] as $platform => $v ) {
			if ( 'Other' === $platform ) {
				continue;
			}
			$cleaned['Social_Media'][ $platform ] = esc_url_raw( $social_in[ $platform ] ?? '' );
		}
		// Dynamic Other items
		$other_social = array();
		if ( ! empty( $social_in['Other'] ) && is_array( $social_in['Other'] ) ) {
			foreach ( $social_in['Other'] as $item ) {
				if ( is_array( $item ) && ! empty( $item['label'] ) ) {
					$other_social[] = array(
						'label' => sanitize_text_field( $item['label'] ),
						'value' => esc_url_raw( $item['value'] ?? '' ),
					);
				}
			}
		}
		$cleaned['Social_Media']['Other'] = $other_social;

		// 5. Business Hours
		$hours_in = isset( $_POST['Business_Hours'] ) ? (array) $_POST['Business_Hours'] : array();
		$cleaned['Business_Hours'] = array();
		$days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
		foreach ( $days as $day ) {
			$cleaned['Business_Hours'][ $day ] = array(
				'Enabled' => ! empty( $hours_in[ $day ]['Enabled'] ),
				'open'    => sanitize_text_field( $hours_in[ $day ]['open'] ?? '09:00' ),
				'close'   => sanitize_text_field( $hours_in[ $day ]['close'] ?? '18:00' ),
			);
		}

		// 6. Legal Documents
		$legal_in = isset( $_POST['Legal_Documents'] ) ? (array) $_POST['Legal_Documents'] : array();
		$cleaned['Legal_Documents'] = array();
		foreach ( array_keys( $defaults['Legal_Documents'] ) as $doc_key ) {
			$cleaned['Legal_Documents'][ $doc_key ] = esc_url_raw( $legal_in[ $doc_key ] ?? '' );
		}

		// 7. Legal Codes + Other dynamic items
		$codes_in = isset( $_POST['Legal_Codes'] ) ? (array) $_POST['Legal_Codes'] : array();
		$cleaned['Legal_Codes'] = array();
		foreach ( array_keys( $defaults['Legal_Codes'] ) as $c_key ) {
			if ( 'Other' === $c_key ) {
				continue;
			}
			$cleaned['Legal_Codes'][ $c_key ] = sanitize_text_field( $codes_in[ $c_key ] ?? '' );
		}
		$other_codes = array();
		if ( ! empty( $codes_in['Other'] ) && is_array( $codes_in['Other'] ) ) {
			foreach ( $codes_in['Other'] as $item ) {
				if ( is_array( $item ) && ! empty( $item['label'] ) ) {
					$other_codes[] = array(
						'label' => sanitize_text_field( $item['label'] ),
						'value' => sanitize_text_field( $item['value'] ?? '' ),
					);
				}
			}
		}
		$cleaned['Legal_Codes']['Other'] = $other_codes;

		// 8. Header & Footer and Theme Options
		$hf_in            = isset( $_POST['Header_And_Footer'] ) ? (array) $_POST['Header_And_Footer'] : array();
		$theme_options_in = isset( $_POST['Theme_Options'] ) ? (array) $_POST['Theme_Options'] : array();

		$header_in    = $hf_in['Header'] ?? array();
		$footer_in    = $hf_in['Footer'] ?? array();
		$sidebar_in   = ! empty( $theme_options_in['Sidebar'] ) ? $theme_options_in['Sidebar'] : ( $hf_in['Sidebar'] ?? array() );
		$preloader_in = ! empty( $theme_options_in['Preloader'] ) ? $theme_options_in['Preloader'] : ( $hf_in['Preloader'] ?? array() );
		$floating_in  = ! empty( $theme_options_in['Floating_btn'] ) ? $theme_options_in['Floating_btn'] : ( $hf_in['Floating_btn'] ?? array() );

		$cleaned['Header_And_Footer'] = array(
			'Header' => array(
				'Style'         => sanitize_text_field( $header_in['Style'] ?? 'style_1' ),
				'Enabled_cta'   => ! empty( $header_in['Enabled_cta'] ),
				'Sticky_header' => ! empty( $header_in['Sticky_header'] ),
				'Select_menu'   => sanitize_text_field( $header_in['Select_menu'] ?? '' ),
				'Topbar'        => array(
					'Enabled_Search'      => ! empty( $header_in['Topbar']['Enabled_Search'] ),
					'Enabled_Social'      => ! empty( $header_in['Topbar']['Enabled_Social'] ),
					'Enabled_Time'        => ! empty( $header_in['Topbar']['Enabled_Time'] ),
					'Enabled_Appointment' => ! empty( $header_in['Topbar']['Enabled_Appointment'] ),
					'Enabled_Career'      => ! empty( $header_in['Topbar']['Enabled_Career'] ),
				),
			),
			'Footer' => array(
				'Style'          => sanitize_text_field( $footer_in['Style'] ?? 'style_1' ),
				'Enabled_Social' => ! empty( $footer_in['Enabled_Social'] ),
				'Connect_cta'    => array(
					'Call_enabled'    => ! empty( $footer_in['Connect_cta']['Call_enabled'] ),
					'Email_enabled'   => ! empty( $footer_in['Connect_cta']['Email_enabled'] ),
					'Address_enabled' => ! empty( $footer_in['Connect_cta']['Address_enabled'] ),
				),
				'Legal_Code'     => array(
					'Enabled' => ! empty( $footer_in['Legal_Code']['Enabled'] ),
				),
				'Menus'          => array(),
				'Bottom'         => array(
					'Enabled'            => ! empty( $footer_in['Bottom']['Enabled'] ),
					'Copyright_text'     => sanitize_text_field( $footer_in['Bottom']['Copyright_text'] ?? '' ),
					'Footer_description' => sanitize_textarea_field( $footer_in['Bottom']['Footer_description'] ?? '' ),
					'Select_menu'        => sanitize_text_field( $footer_in['Bottom']['Select_menu'] ?? '' ),
				),
			),
			'Sidebar' => array(
				'Enabled'          => ! empty( $sidebar_in['Enabled'] ),
				'Search_box'       => ! empty( $sidebar_in['Search_box'] ),
				'Contact_box'      => ! empty( $sidebar_in['Contact_box'] ),
				'Newsletter_box'   => ! empty( $sidebar_in['Newsletter_box'] ),
				'Blog_categories'  => ! empty( $sidebar_in['Blog_categories'] ),
				'Blog_tags'        => ! empty( $sidebar_in['Blog_tags'] ),
				'Recent_posts_box' => ! empty( $sidebar_in['Recent_posts_box'] ),
				'Share_post'       => ! empty( $sidebar_in['Share_post'] ),
			),
			'Preloader' => array(
				'Enabled'          => ! empty( $preloader_in['Enabled'] ),
				'Style'            => sanitize_text_field( $preloader_in['Style'] ?? 'spinner' ),
				'Loading_text'     => sanitize_text_field( $preloader_in['Loading_text'] ?? 'Loading...' ),
				'Minimum_time_out' => sanitize_text_field( $preloader_in['Minimum_time_out'] ?? '500' ),
				'Exclude_page_id'  => sanitize_text_field( $preloader_in['Exclude_page_id'] ?? '' ),
			),
			'Floating_btn' => array(
				'desktop_enabled' => ! empty( $floating_in['desktop_enabled'] ),
				'mobile_enabled'  => ! empty( $floating_in['mobile_enabled'] ),
				'h_position'      => sanitize_text_field( $floating_in['h_position'] ?? 'right' ),
				'v_position'      => sanitize_text_field( $floating_in['v_position'] ?? 'bottom' ),
				'tooltip_enabled' => ! empty( $floating_in['tooltip_enabled'] ),
				'Contact Modal'   => array(
					'Enabled'  => ! empty( $floating_in['Contact Modal']['Enabled'] ),
					'Modal_id' => sanitize_text_field( $floating_in['Contact Modal']['Modal_id'] ?? '' ),
					'Exclude_page_id' => sanitize_text_field( $floating_in['Contact Modal']['Exclude_page_id'] ?? '' ),
				),
				'Email'           => array(
					'Enabled' => ! empty( $floating_in['Email']['Enabled'] ),
					'Address' => sanitize_email( $floating_in['Email']['Address'] ?? '' ),
					'Subject' => sanitize_text_field( $floating_in['Email']['Subject'] ?? '' ),
				),
				'Whatsapp'        => array(
					'Enabled' => ! empty( $floating_in['Whatsapp']['Enabled'] ),
					'Number'  => sanitize_text_field( $floating_in['Whatsapp']['Number'] ?? '' ),
					'Message' => sanitize_text_field( $floating_in['Whatsapp']['Message'] ?? '' ),
				),
				'Call'            => array(
					'Enabled' => ! empty( $floating_in['Call']['Enabled'] ),
					'Number'  => sanitize_text_field( $floating_in['Call']['Number'] ?? '' ),
				),
				'Message'         => array(
					'Enabled' => ! empty( $floating_in['Message']['Enabled'] ),
					'Number'  => sanitize_text_field( $floating_in['Message']['Number'] ?? '' ),
				),
				'Live_Chat_Button'         => array(
					'Enabled' => ! empty( $floating_in['Live_Chat_Button']['Enabled'] ),
					'Title'  => sanitize_text_field( $floating_in['Live_Chat_Button']['Title'] ?? '' ),
				),
				'Google_maps'     => array(
					'Enabled' => ! empty( $floating_in['Google_maps']['Enabled'] ),
					'Link'    => esc_url_raw( $floating_in['Google_maps']['Link'] ?? '' ),
				),
				'Back_to_top'     => array(
					'Enabled'         => ! empty( $floating_in['Back_to_top']['Enabled'] ),
					'scroll_distance' => absint( $floating_in['Back_to_top']['scroll_distance'] ?? 300 ),
				),
			),
		);

		for ( $m = 1; $m <= 5; $m++ ) {
			$m_key = 'menu_' . $m;
			$cleaned['Header_And_Footer']['Footer']['Menus'][ $m_key ] = array(
				'Menu_Title'  => sanitize_text_field( $footer_in['Menus'][ $m_key ]['Menu_Title'] ?? '' ),
				'Select_menu' => sanitize_text_field( $footer_in['Menus'][ $m_key ]['Select_menu'] ?? '' ),
			);
		}

		// 8b. Theme Options (Synced with Sidebar, Preloader, Floating_btn)
		$cleaned['Theme_Options'] = array(
			'Sidebar'      => $cleaned['Header_And_Footer']['Sidebar'],
			'Preloader'    => $cleaned['Header_And_Footer']['Preloader'],
			'Floating_btn' => $cleaned['Header_And_Footer']['Floating_btn'],
		);

		// 10. Analytics & Tracking Codes (Strict Security Verification)
		$analytics_in      = isset( $_POST['Analytics'] ) ? (array) $_POST['Analytics'] : array();
		$current_analytics = $current['Analytics'] ?? array();
		$validation_errors = array();

		$cleaned['Analytics'] = array(
			'Enabled'                      => ! empty( $analytics_in['Enabled'] ),
			'Google_Site_Verification'     => self::verify_platform_id( $analytics_in['Google_Site_Verification'] ?? '', 'Google Site Verification', $validation_errors ),
			'GTM_Container_ID'             => self::verify_platform_id( $analytics_in['GTM_Container_ID'] ?? '', 'GTM Container ID', $validation_errors ),
			'GA4_Measurement_ID'           => self::verify_platform_id( $analytics_in['GA4_Measurement_ID'] ?? '', 'GA4 Measurement ID', $validation_errors ),
			'Bing_Verification_ID'         => self::verify_platform_id( $analytics_in['Bing_Verification_ID'] ?? '', 'Bing Verification ID', $validation_errors ),
			'Bing_Tags_ID'                 => self::verify_platform_id( $analytics_in['Bing_Tags_ID'] ?? '', 'Bing UET Tags ID', $validation_errors ),
			'Clarity_Project_ID'           => self::verify_platform_id( $analytics_in['Clarity_Project_ID'] ?? '', 'Clarity Project ID', $validation_errors ),
			'Pinterest_Tag_ID'             => self::verify_platform_id( $analytics_in['Pinterest_Tag_ID'] ?? '', 'Pinterest Tag ID', $validation_errors ),
			'Meta_Pixel_ID'                => self::verify_platform_id( $analytics_in['Meta_Pixel_ID'] ?? '', 'Meta Pixel ID', $validation_errors ),
			'Facebook_Domain_Verification' => self::verify_platform_id( $analytics_in['Facebook_Domain_Verification'] ?? '', 'Facebook Domain Verification', $validation_errors ),
			'IndexNow_API_Key'             => self::verify_platform_id( $analytics_in['IndexNow_API_Key'] ?? '', 'IndexNow API Key', $validation_errors ),
			'LinkedIn_Partner_ID'          => self::verify_platform_id( $analytics_in['LinkedIn_Partner_ID'] ?? '', 'LinkedIn Partner ID', $validation_errors ),
			'Twitter_Pixel_ID'             => self::verify_platform_id( $analytics_in['Twitter_Pixel_ID'] ?? '', 'Twitter Pixel ID', $validation_errors ),
			'Norton_Safe_Web'              => self::verify_platform_id( $analytics_in['Norton_Safe_Web'] ?? '', 'Norton Safe Web', $validation_errors ),
			'Web_Of_Trust'                 => self::verify_platform_id( $analytics_in['Web_Of_Trust'] ?? '', 'Web Of Trust', $validation_errors ),
			'Ahrefs_Site_Verification'     => self::verify_platform_id( $analytics_in['Ahrefs_Site_Verification'] ?? '', 'Ahrefs Site Verification', $validation_errors ),
			'Measurement_Protocol_Secret'  => self::verify_platform_id( $analytics_in['Measurement_Protocol_Secret'] ?? '', 'Measurement Protocol Secret', $validation_errors ),
			'Matomo_URL'                   => esc_url_raw( $analytics_in['Matomo_URL'] ?? '' ),
			'Matomo_Site_ID'               => self::verify_platform_id( $analytics_in['Matomo_Site_ID'] ?? '', 'Matomo Site ID', $validation_errors ),
		);

		// Verify HEAD, BODY, FOOTER custom tracking code editors
		$raw_head   = $analytics_in['Head_Tracking_Code'] ?? '';
		$raw_body   = $analytics_in['Body_Tracking_Code'] ?? '';
		$raw_footer = $analytics_in['Footer_Tracking_Code'] ?? '';

		$clean_head   = self::verify_and_sanitize_tracking_code( $raw_head, 'HEAD', $validation_errors );
		$clean_body   = self::verify_and_sanitize_tracking_code( $raw_body, 'BODY', $validation_errors );
		$clean_footer = self::verify_and_sanitize_tracking_code( $raw_footer, 'FOOTER', $validation_errors );

		// Preserve previous safe values if submission contained malicious/hackable payload
		$cleaned['Analytics']['Head_Tracking_Code']   = ( empty( $clean_head ) && ! empty( $raw_head ) ) ? ( $current_analytics['Head_Tracking_Code'] ?? '' ) : $clean_head;
		$cleaned['Analytics']['Body_Tracking_Code']   = ( empty( $clean_body ) && ! empty( $raw_body ) ) ? ( $current_analytics['Body_Tracking_Code'] ?? '' ) : $clean_body;
		$cleaned['Analytics']['Footer_Tracking_Code'] = ( empty( $clean_footer ) && ! empty( $raw_footer ) ) ? ( $current_analytics['Footer_Tracking_Code'] ?? '' ) : $clean_footer;

		if ( ! empty( $validation_errors ) ) {
			set_transient( 'wbs_validation_errors_' . get_current_user_id(), $validation_errors, 45 );
		}

		// 11. Settings tab placeholder
		$cleaned['Settings'] = '';

		// Save to wp_options table
		update_option( 'business_setup_options', $cleaned );

		wp_safe_redirect( admin_url( 'admin.php?page=business-setup&settings-updated=true' ) );
		exit;
	}

	/**
	 * AJAX handler for JSON Export
	 */
	public function ajax_export_json() {
		check_ajax_referer( 'wbs_save_options_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied.' );
		}

		$options = wbs_get_all_options();
		wp_send_json_success( $options );
	}

	/**
	 * AJAX handler for JSON Import
	 */
	public function ajax_import_json() {
		check_ajax_referer( 'wbs_save_options_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied.' );
		}

		$json_str = isset( $_POST['json_data'] ) ? wp_unslash( $_POST['json_data'] ) : '';
		$decoded  = json_decode( $json_str, true );

		if ( empty( $decoded ) || ! is_array( $decoded ) ) {
			wp_send_json_error( 'Invalid JSON data provided.' );
		}

		// Merge with schema defaults to ensure structure integrity
		$defaults = wp_business_setup_get_default_schema();
		$merged   = wp_parse_args( $decoded, $defaults );

		update_option( 'business_setup_options', $merged );
		wp_send_json_success( 'Settings imported successfully.' );
	}

	/**
	 * AJAX handler for Reset Defaults
	 */
	public function ajax_reset_defaults() {
		check_ajax_referer( 'wbs_save_options_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied.' );
		}

		$defaults = wp_business_setup_get_default_schema();
		update_option( 'business_setup_options', $defaults );
		wp_send_json_success( 'Reset to defaults completed.' );
	}

	/**
	 * Verify and sanitize tracking code snippets against malicious/hackable patterns
	 *
	 * @param string $code Raw code string.
	 * @param string $location Location indicator ('HEAD', 'BODY', or 'FOOTER').
	 * @param array  $errors Reference to errors collection.
	 * @return string Verified code or empty string on dangerous violation.
	 */
	public static function verify_and_sanitize_tracking_code( $code, $location, &$errors ) {
		$code = trim( (string) $code );
		if ( empty( $code ) ) {
			return '';
		}

		// 1. Block Server-Side Code Injection (PHP, ASP, etc.)
		if ( preg_match( '/<\?php|<\?=| language\s*=\s*["\']?php/i', $code ) ) {
			$errors[] = sprintf( 'Security Alert: Server-side PHP script execution is strictly prohibited in %s tracking code.', $location );
			return '';
		}

		// 2. Block High-Risk Remote Execution, XSS & Tampering Vectors
		$attack_patterns = array(
			'/\beval\s*\(/i'                                                => 'eval() dynamic code execution',
			'/\bFunction\s*\(/i'                                            => 'Function constructor execution',
			'/document\.cookie/i'                                           => 'document.cookie access (session hijacking exploit)',
			'/document\.domain/i'                                           => 'document.domain tampering',
			'/window\.location\s*=/i'                                       => 'window.location redirect hijack',
			'/<\s*(object|embed|applet|base|form|input|button|textarea)\b/i' => 'Forbidden sensitive HTML element',
			'/javascript\s*:/i'                                             => 'javascript: pseudo-protocol URL',
			'/data\s*:\s*text\/html/i'                                      => 'data:text/html payload injection',
			'/vbscript\s*:/i'                                               => 'vbscript: pseudo-protocol',
			'/\bon(error|load|click|mouseover|focus|blur|change|submit|keydown|keyup)\s*=/i' => 'Inline element event handler injection',
		);

		foreach ( $attack_patterns as $pattern => $description ) {
			if ( preg_match( $pattern, $code ) ) {
				$errors[] = sprintf( 'Security Verification Failed in %1$s Tracking Code: %2$s detected and blocked.', $location, $description );
				return '';
			}
		}

		return $code;
	}

	/**
	 * Verify platform ID (must not contain HTML/script injection)
	 *
	 * @param string $val Raw ID string.
	 * @param string $field_name Field name for error reporting.
	 * @param array  $errors Reference to errors collection.
	 * @return string Clean ID.
	 */
	public static function verify_platform_id( $val, $field_name, &$errors ) {
		$val = trim( (string) $val );
		if ( empty( $val ) ) {
			return '';
		}

		// Strictly reject any HTML, tags, or scripts
		if ( preg_match( '/<[^>]*>/', $val ) || strpos( $val, '<' ) !== false || strpos( $val, '>' ) !== false ) {
			$errors[] = sprintf( 'Field "%s" must be a clean alphanumeric ID without HTML or script tags.', $field_name );
			return sanitize_text_field( strip_tags( $val ) );
		}

		return sanitize_text_field( $val );
	}
}
