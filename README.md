# WP Business Setup Plugin

A complete, production-ready WordPress business identity and setup management plugin. Built according to modern WordPress standards and custom schema specifications.

## 🚀 Features & Implementation Overview

1. **WordPress Menu Integration (After Dashboard)**:
   - Uses the active **Site Name** (`get_bloginfo('name')`) as the page title and menu label.
   - Positioned at index `3`, placing it immediately after the **Dashboard** (position `2`).
2. **Dedicated Storage in `wp_options`**:
   - Stores all grouped configuration under the single option key `business_setup_options`.
   - Fully compatible with WordPress export, import, caching, and multisite.
3. **Appropriate Form Input Controls**:
   - Strict input types used: `email`, `tel`, `url`, `number`, `time`, and `textarea`.
   - Modern iOS-style toggle switches for all "Enable" / boolean settings.
4. **Dynamic "Other" Key-Value Fields**:
   - In **Social Media** and **Legal Codes**, users can click **"+ Add Other"** to dynamically specify custom labels and URLs/values.
5. **Contact Fields with 10-Row Limit & Mandatory Rule**:
   - Dynamic repeaters for **Phone Numbers**, **Email Addresses**, and **Addresses**.
   - Maximum limit of 10 rows per contact type with live counter.
   - Enforces the rule: **At least one field is mandatory** (delete button is disabled on the last remaining item; backend validation prevents empty state).
   - Structured Address format: `[address line 1, address line 2, city, state, country, zipcode, geo-coordination]`.
6. **WordPress Media Selector for Branding**:
   - Native `wp.media` file picker integration for: Primary Logo, Short Logo, White Logo, Black Logo, Banner Image, and Banner File.
   - Includes real-time image previews and remove buttons.
7. **Dynamic Theme Menus**:
   - Header and Footer menu dropdowns automatically query `wp_get_nav_menus()` to list all created WordPress navigation menus.
8. **Visual Style Selector with Dark Border**:
   - Header & Footer layout options are displayed with high-resolution SVG layout previews.
   - The selected style card is highlighted with a prominent **dark border** (`3px solid #0f172a`) and checkmark badge.
9. **Complete Floating Action Buttons Suite (All 7 Triggers)**:
   - **General Controls**: Desktop switch, Mobile switch, Tooltip labels switch, Horizontal & Vertical position selectors.
   - **Contact Modal**: Enable switch + `Modal_id` input (DOM element selector).
   - **WhatsApp**: Enable switch + `Number` (`tel`) + pre-filled `Message`.
   - **Call**: Enable switch + direct phone `Number` (`tel`).
   - **Message / SMS**: Enable switch + direct `Number` (`tel`).
   - **Email**: Enable switch + `Address` (`email`) + pre-filled `Subject`.
   - **Google Maps**: Enable switch + map directions `Link` (`url`).
   - **Back to Top**: Enable switch + scroll distance trigger `scroll_distance` (`number`).
10. **Schema Multiselect for Pages**:
   - Schema page assignment selectors (`..._schema_page`) provide a multiselect box containing all published pages.
   - Stores page IDs/titles in the schema configuration.
11. **Analytical & Webmaster Tracking Suite**:
    - **Platform Verifications & Pixels**: Google Search Console, GTM, GA4, Bing Webmaster, Bing UET Tags, Microsoft Clarity, Pinterest Tag, Meta / Facebook Pixel, Facebook Domain Verification, IndexNow API, LinkedIn Insight, Twitter/X Pixel, Norton Safe Web, Web Of Trust (WOT), Ahrefs, GA4 Measurement Secret, and Matomo.
    - **Three Code Editors**:
      - `HEAD Tracking Code`: Injected into `<head>` via `wp_head`.
      - `BODY Tracking Code`: Injected immediately after opening `<body>` via `wp_body_open`.
      - `FOOTER Tracking Code`: Injected before closing `</body>` via `wp_footer`.
    - **Strict Anti-Tamper & Security Validation**:
      - All inputs undergo rigorous security checks prior to saving.
      - Server-side PHP script tags (`<?php`, `<?=`) are strictly blocked.
      - Dangerous JavaScript vectors (`eval()`, `Function()`, `document.cookie` harvesting, `window.location` redirect hijacks, `onerror=`/`onload=` event handlers) are blocked and rejected with an admin warning notice.
      - Platform ID fields strictly forbid HTML/script tags.
12. **Import & Export JSON**:
    - **Export**: One-click download of the complete setup configuration as a `.json` file.
    - **Import**: Upload and parse any valid JSON file with safety confirmation and automated options update.
    - **Reset to Defaults**: One-click reset to factory defaults.
13. **Automated Frontend Injections**:
    - Automatically emits Google-compliant `application/ld+json` for `Organization`, `LocalBusiness`, and `WebSite` schemas.
    - Automatically injects official library tags for GA4, GTM, Clarity, Meta Pixel, Matomo, and Webmaster verification meta tags when IDs are provided.

---

## 📂 Plugin Structure

```text
wp-business-setup/
├── wp-business-setup.php          # Main bootstrap plugin file
├── README.md                      # Documentation
├── includes/
│   ├── class-business-setup.php   # Admin controller, anti-hack security verifier, AJAX endpoints
│   ├── default-schema.php         # Default JSON data schema matching specifications
│   └── helper-functions.php       # Template functions, frontend code injection & Schema JSON-LD
├── templates/
│   ├── admin-page.php             # Master container, header banner, tab navigation
│   ├── tab-general.php            # General business details
│   ├── tab-branding.php           # Media library uploaders for logos & banners
│   ├── tab-contact.php            # Repeaters for phone, email, multi-field address
│   ├── tab-social-media.php       # Social media URLs + dynamic "Add Other" links
│   ├── tab-business-hours.php     # Daily schedule toggles + open/close time pickers
│   ├── tab-legal-documents.php    # Legal pages & policy URLs
│   ├── tab-legal-codes.php        # Corporate IDs, tax codes + dynamic "Add Other"
│   ├── tab-header-footer.php      # Header & Footer styles, dynamic menus, topbars
│   ├── tab-theme-options.php      # Sidebar widgets, Preloader, Floating Action Buttons & Schema
│   ├── tab-analytics.php          # Analytics IDs, Webmaster tools, and HEAD/BODY/FOOTER code editors
│   ├── tab-uses-shortcodes.php   # Shortcode directory, developer guide & system boundaries
│   └── tab-settings.php          # JSON export, JSON import, reset to defaults
└── assets/
    ├── css/
    │   └── admin-style.css        # Responsive styling, switches, dark-bordered cards
    ├── js/
    │   └── admin-script.js        # Repeaters, tabs, media uploader, import/export
    └── images/
        ├── header-style-1.svg     # Header preview mockups
        ├── header-style-2.svg
        ├── header-style-3.svg
        ├── header-style-4.svg
        ├── footer-style-1.svg     # Footer preview mockups
        ├── footer-style-2.svg
        ├── footer-style-3.svg
        └── footer-style-4.svg
```

---

## 💻 Developer Usage in Theme Templates

Any theme or third-party plugin can retrieve saved business settings using the provided template functions:

```php
// 1. Retrieve the entire configuration array:
$business = wbs_get_all_options();

// 2. Retrieve a specific group or key:
$business_name = wbs_get_option( 'General', 'Business_Name' );
$tagline       = wbs_get_option( 'General', 'Business_Tagline' );

// 3. Retrieve branding logo URL:
$primary_logo  = wbs_get_logo( 'Primary Logo' );
$white_logo    = wbs_get_logo( 'White Logo' );

// 4. Retrieve primary phone and email:
$phone = wbs_get_primary_phone();
$email = wbs_get_primary_email();

// 5. Retrieve formatted physical address:
$address_text = wbs_get_primary_address_text();
```
