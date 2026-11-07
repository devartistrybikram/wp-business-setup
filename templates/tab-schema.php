<?php
/**
 * Tab: Schema & Structured Data (JSON-LD)
 *
 * @package WP_Business_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$schema = isset( $options['Schema'] ) ? $options['Schema'] : array();

// Fetch all published pages for schema multi-selection
$pages = get_pages( array( 'post_status' => 'publish' ) );

/**
 * Render multiselect dropdown for pages
 * Stores page ID / titles
 */
function wbs_render_schema_page_multiselect( $field_name, $selected_pages, $pages ) {
	// Ensure $selected_pages is an array
	if ( ! is_array( $selected_pages ) ) {
		$selected_pages = ! empty( $selected_pages ) ? explode( ',', (string) $selected_pages ) : array();
	}
	$selected_pages = array_map( 'strval', $selected_pages );
	?>
	<div class="wbs-multiselect-box">
		<?php if ( ! empty( $pages ) ) : ?>
			<?php foreach ( $pages as $p ) : 
				$page_id = (string) $p->ID;
				$is_checked = in_array( $page_id, $selected_pages, true ) || in_array( $p->post_title, $selected_pages, true );
			?>
				<label class="wbs-multiselect-option">
					<input type="checkbox" name="Schema[<?php echo esc_attr( $field_name ); ?>][]" value="<?php echo esc_attr( $page_id ); ?>" <?php checked( $is_checked, true ); ?> />
					<span><?php echo esc_html( $p->post_title ); ?> <small style="color:#94a3b8;">(ID: <?php echo esc_html( $page_id ); ?>)</small></span>
				</label>
			<?php endforeach; ?>
		<?php else : ?>
			<span style="font-size: 12px; color: #94a3b8;">No published pages found.</span>
		<?php endif; ?>
	</div>
	<span class="wbs-field-hint">Select one or multiple pages to bind this schema.</span>
	<?php
}
?>

<!-- Master Schema Switch -->
<div class="wbs-card">
	<div class="wbs-card-header">
		<div>
			<h2><span class="dashicons dashicons-code-standards"></span> Structured Data (JSON-LD Schema)</h2>
			<p>Configure automated Google Search rich results, entity graphs &amp; schema markup.</p>
		</div>
		<div>
			<label class="wbs-switch">
				<input type="checkbox" name="Schema[Enabled]" value="1" <?php checked( ! empty( $schema['Enabled'] ), true ); ?> />
				<span class="wbs-slider"></span>
			</label>
		</div>
	</div>
	<div class="wbs-card-body">

		<!-- Primary Schemas with Multiselect Pages -->
		<h3 style="margin: 0 0 16px 0; font-size: 15px; color: var(--wbs-dark);">Entity Schemas &amp; Target Page Assignment</h3>
		<div class="wbs-grid-2">

			<!-- Organization Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Organization Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Organization_schema]" value="1" <?php checked( ! empty( $schema['Organization_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Organization_schema_page', $schema['Organization_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Website Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Website Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Website_schema]" value="1" <?php checked( ! empty( $schema['Website_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Website_schema_page', $schema['Website_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- LocalBusiness Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">LocalBusiness Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Localbusines_schema]" value="1" <?php checked( ! empty( $schema['Localbusines_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Localbusines_schema_page', $schema['Localbusines_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- WebPage Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">WebPage Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Webpage_schema]" value="1" <?php checked( ! empty( $schema['Webpage_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Webpage_schema_page', $schema['Webpage_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Individual Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Individual Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Individual_schema]" value="1" <?php checked( ! empty( $schema['Individual_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Individual_schema_page', $schema['Individual_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Aggregate Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Aggregate Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Aggregate_schema]" value="1" <?php checked( ! empty( $schema['Aggregate_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Aggregate_schema_page', $schema['Aggregate_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Combined Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Combined Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Combined_schema]" value="1" <?php checked( ! empty( $schema['Combined_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Combined_schema_page', $schema['Combined_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Contact Page Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Contact Page Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Contact_page_schema]" value="1" <?php checked( ! empty( $schema['Contact_page_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Contact_page_schema_page', $schema['Contact_page_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Contact Point Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Contact Point Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Contact_point_schema]" value="1" <?php checked( ! empty( $schema['Contact_point_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Contact_point_schema_page', $schema['Contact_point_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- Creative Work Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Creative Work Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[Creative_work_schema]" value="1" <?php checked( ! empty( $schema['Creative_work_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'Creative_work_schema_page', $schema['Creative_work_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

			<!-- QA Page Schema -->
			<div class="wbs-card" style="background: var(--wbs-gray-50); margin-bottom: 0;">
				<div class="wbs-card-body" style="padding: 16px;">
					<div class="wbs-switch-wrapper" style="margin-bottom: 12px; background: #fff;">
						<span class="wbs-switch-title">Q&amp;A Page Schema</span>
						<label class="wbs-switch">
							<input type="checkbox" name="Schema[QApage_schema]" value="1" <?php checked( ! empty( $schema['QApage_schema'] ), true ); ?> />
							<span class="wbs-slider"></span>
						</label>
					</div>
					<div class="wbs-field-group" style="margin-bottom: 0;">
						<label>Target Pages (Multiselect)</label>
						<?php wbs_render_schema_page_multiselect( 'QApage_schema_page', $schema['QApage_schema_page'] ?? array(), $pages ); ?>
					</div>
				</div>
			</div>

		</div>

		<!-- Additional Schema Switches -->
		<h3 style="margin: 28px 0 16px 0; font-size: 15px; color: var(--wbs-dark);">Additional Schema Graph Toggles</h3>
		<div class="wbs-grid-3">
			<?php 
			$extra_schemas = array(
				'Breadcrumb_schema'    => 'BreadcrumbList Schema',
				'Brnad_schema'         => 'Brand Schema',
				'Service_schema'       => 'Service Schema',
				'Product_schema'       => 'Product Schema',
				'ArticleList_schema'   => 'Article List / Blog Schema',
				'Article_schema'       => 'Single Article Schema',
				'FAQ_schema'           => 'FAQPage Schema',
				'Collection_page_schema'=> 'CollectionPage Schema',
				'Job_posting_schema'   => 'JobPosting Schema',
				'How_To_Step_schema'   => 'HowTo / Step Schema',
				'Person_schema'        => 'Person Schema',
				'Profile_page_schema'  => 'ProfilePage Schema',
				'Offer_catalog_schema' => 'OfferCatalog Schema',
				'Video_schema'         => 'VideoObject Schema',
				'Image_schema'         => 'ImageObject Schema',
				'Offer_schema'         => 'Offer Schema',
				'course_schema'        => 'Course Schema',
			);

			foreach ( $extra_schemas as $s_k => $s_title ) :
			?>
				<div class="wbs-switch-wrapper">
					<span class="wbs-switch-title"><?php echo esc_html( $s_title ); ?></span>
					<label class="wbs-switch">
						<input type="checkbox" name="Schema[<?php echo esc_attr( $s_k ); ?>]" value="1" <?php checked( ! empty( $schema[ $s_k ] ), true ); ?> />
						<span class="wbs-slider"></span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="wbs-field-group" style="margin-top: 20px;">
			<label for="wbs_product_type">Default Product Type</label>
			<input type="text" id="wbs_product_type" name="Schema[Product_type]" value="<?php echo esc_attr( $schema['Product_type'] ?? 'IndividualProduct' ); ?>" placeholder="e.g. IndividualProduct, FinancialProduct, SoftwareApplication" />
		</div>

	</div>
</div>
