/**
 * WordPress Business Setup Admin JavaScript
 */
(function($) {
	'use strict';

	$(document).ready(function() {

		// ----------------------------------------------------
		// 1. TABS NAVIGATION & PERSISTENCE
		// ----------------------------------------------------
		var activeTabKey = 'wbs_active_tab';
		var storedTab = localStorage.getItem(activeTabKey);
		var hashTab = window.location.hash ? window.location.hash.replace('#', '') : null;
		var initialTab = hashTab || storedTab || 'general';

		function switchTab(tabId) {
			if (!$('#tab-' + tabId).length) {
				tabId = 'general';
			}
			$('.wbs-tab-btn').removeClass('active');
			$('.wbs-tab-pane').removeClass('active');

			$('[data-tab="' + tabId + '"]').addClass('active');
			$('#tab-' + tabId).addClass('active');

			localStorage.setItem(activeTabKey, tabId);
			if (history.replaceState) {
				history.replaceState(null, null, '#' + tabId);
			}
		}

		$('.wbs-tab-btn').on('click', function(e) {
			e.preventDefault();
			var targetTab = $(this).data('tab');
			switchTab(targetTab);
		});

		switchTab(initialTab);

		// ----------------------------------------------------
		// 2. WORDPRESS MEDIA UPLOADER (BRANDING)
		// ----------------------------------------------------
		$(document).on('click', '.wbs-btn-upload', function(e) {
			e.preventDefault();
			var $btn = $(this);
			var $container = $btn.closest('.wbs-media-field');
			var $input = $container.find('.wbs-media-input');
			var $preview = $container.find('.wbs-media-preview');

			var mediaFrame = wp.media({
				title: 'Select or Upload Branding Asset',
				button: { text: 'Use Asset' },
				multiple: false
			});

			mediaFrame.on('select', function() {
				var attachment = mediaFrame.state().get('selection').first().toJSON();
				$input.val(attachment.url);

				if (attachment.url.match(/\.(jpeg|jpg|gif|png|webp|svg)$/i)) {
					$preview.html('<img src="' + attachment.url + '" alt="Preview" />');
				} else {
					$preview.html('<div class="wbs-media-placeholder"><span class="dashicons dashicons-media-document"></span><span>' + attachment.filename + '</span></div>');
				}
				$container.find('.wbs-btn-remove').show();
			});

			mediaFrame.open();
		});

		$(document).on('click', '.wbs-btn-remove', function(e) {
			e.preventDefault();
			var $container = $(this).closest('.wbs-media-field');
			$container.find('.wbs-media-input').val('');
			$container.find('.wbs-media-preview').html('<div class="wbs-media-placeholder"><span class="dashicons dashicons-format-image"></span><span>No file selected</span></div>');
			$(this).hide();
		});

		// ----------------------------------------------------
		// 3. HEADER & FOOTER STYLE SELECTOR (DARK BORDER WHEN CHECKED)
		// ----------------------------------------------------
		$('.wbs-style-card input[type="radio"], .wbs-style-card input[type="checkbox"]').on('change', function() {
			var name = $(this).attr('name');
			$('input[name="' + name + '"]').closest('.wbs-style-card').removeClass('is-active');
			if ($(this).is(':checked')) {
				$(this).closest('.wbs-style-card').addClass('is-active');
			}
		});

		// Set initial active states for checked cards
		$('.wbs-style-card input:checked').each(function() {
			$(this).closest('.wbs-style-card').addClass('is-active');
		});

		// ----------------------------------------------------
		// 4. CONTACTS: PHONE & EMAIL REPEATERS (MAX 10, MIN 1)
		// ----------------------------------------------------
		function updateSimpleRepeaterState($wrapper) {
			var count = $wrapper.find('.wbs-repeater-row').length;
			$wrapper.find('.wbs-count-current').text(count);

			var $addBtn = $wrapper.find('.wbs-btn-add-row');
			if (count >= 10) {
				$addBtn.prop('disabled', true).text('Maximum Limit Reached (10)');
			} else {
				$addBtn.prop('disabled', false).html('<span class="dashicons dashicons-plus-alt2"></span> Add More');
			}

			// Rule: User can remove fields, but one is must (disable delete button if only 1 exists)
			var $deleteButtons = $wrapper.find('.wbs-btn-delete-row');
			if (count <= 1) {
				$deleteButtons.prop('disabled', true).attr('title', 'At least one field is required');
			} else {
				$deleteButtons.prop('disabled', false).removeAttr('title');
			}
		}

		// Initialize existing repeater counters
		$('.wbs-simple-repeater').each(function() {
			updateSimpleRepeaterState($(this));
		});

		// Add phone row
		$(document).on('click', '.wbs-btn-add-phone', function(e) {
			e.preventDefault();
			var $wrapper = $(this).closest('.wbs-simple-repeater');
			var count = $wrapper.find('.wbs-repeater-row').length;
			if (count >= 10) return;

			var newRow = '<div class="wbs-repeater-row">' +
				'<span class="dashicons dashicons-menu wbs-row-drag"></span>' +
				'<input type="tel" name="Contact[phone][]" placeholder="+1 (555) 000-0000" class="regular-text" />' +
				'<button type="button" class="wbs-btn-delete-row dashicons dashicons-trash" title="Remove"></button>' +
			'</div>';

			$wrapper.find('.wbs-repeater-rows').append(newRow);
			updateSimpleRepeaterState($wrapper);
		});

		// Add email row
		$(document).on('click', '.wbs-btn-add-email', function(e) {
			e.preventDefault();
			var $wrapper = $(this).closest('.wbs-simple-repeater');
			var count = $wrapper.find('.wbs-repeater-row').length;
			if (count >= 10) return;

			var newRow = '<div class="wbs-repeater-row">' +
				'<span class="dashicons dashicons-menu wbs-row-drag"></span>' +
				'<input type="email" name="Contact[email][]" placeholder="contact@example.com" class="regular-text" />' +
				'<button type="button" class="wbs-btn-delete-row dashicons dashicons-trash" title="Remove"></button>' +
			'</div>';

			$wrapper.find('.wbs-repeater-rows').append(newRow);
			updateSimpleRepeaterState($wrapper);
		});

		// Remove row with rule check
		$(document).on('click', '.wbs-simple-repeater .wbs-btn-delete-row', function(e) {
			e.preventDefault();
			var $wrapper = $(this).closest('.wbs-simple-repeater');
			var count = $wrapper.find('.wbs-repeater-row').length;
			if (count <= 1) {
				alert('At least one field is required.');
				return;
			}
			$(this).closest('.wbs-repeater-row').remove();
			updateSimpleRepeaterState($wrapper);
		});

		// ----------------------------------------------------
		// 5. CONTACTS: ADDRESS REPEATER (MAX 10, MIN 1)
		// ----------------------------------------------------
		function updateAddressRepeaterState() {
			var $wrapper = $('#wbs-address-repeater');
			var count = $wrapper.find('.wbs-address-card').length;
			$wrapper.find('.wbs-count-current').text(count);

			var $addBtn = $wrapper.find('.wbs-btn-add-address');
			if (count >= 10) {
				$addBtn.prop('disabled', true).text('Maximum Limit Reached (10)');
			} else {
				$addBtn.prop('disabled', false).html('<span class="dashicons dashicons-plus-alt2"></span> Add More Address');
			}

			// Rule: user can remove, but one address is must
			var $deleteButtons = $wrapper.find('.wbs-btn-delete-address');
			if (count <= 1) {
				$deleteButtons.prop('disabled', true).attr('title', 'At least one address is required');
			} else {
				$deleteButtons.prop('disabled', false).removeAttr('title');
			}

			// Update Address Numbers (e.g. Address #1, Address #2)
			$wrapper.find('.wbs-address-card').each(function(index) {
				$(this).find('.wbs-address-index').text(index + 1);
			});
		}

		updateAddressRepeaterState();

		$(document).on('click', '.wbs-btn-add-address', function(e) {
			e.preventDefault();
			var $wrapper = $('#wbs-address-repeater');
			var count = $wrapper.find('.wbs-address-card').length;
			if (count >= 10) return;

			var index = count;
			var newAddressCard = '<div class="wbs-address-card" data-index="' + index + '">' +
				'<div class="wbs-address-header">' +
					'<span>Address #<span class="wbs-address-index">' + (index + 1) + '</span></span>' +
					'<button type="button" class="wbs-btn-delete-address wbs-btn-delete-row dashicons dashicons-trash" title="Remove Address"></button>' +
				'</div>' +
				'<div class="wbs-grid-2">' +
					'<div class="wbs-field-group">' +
						'<label>Address Line 1</label>' +
						'<input type="text" name="Contact[address][' + index + '][0]" placeholder="Street Address, P.O. Box" />' +
					'</div>' +
					'<div class="wbs-field-group">' +
						'<label>Address Line 2</label>' +
						'<input type="text" name="Contact[address][' + index + '][1]" placeholder="Apt, Suite, Unit, Building, Floor" />' +
					'</div>' +
				'</div>' +
				'<div class="wbs-grid-4" style="margin-top: 10px;">' +
					'<div class="wbs-field-group">' +
						'<label>City</label>' +
						'<input type="text" name="Contact[address][' + index + '][2]" placeholder="City" />' +
					'</div>' +
					'<div class="wbs-field-group">' +
						'<label>State / Province</label>' +
						'<input type="text" name="Contact[address][' + index + '][3]" placeholder="State" />' +
					'</div>' +
					'<div class="wbs-field-group">' +
						'<label>Country</label>' +
						'<input type="text" name="Contact[address][' + index + '][4]" placeholder="Country" />' +
					'</div>' +
					'<div class="wbs-field-group">' +
						'<label>Zipcode</label>' +
						'<input type="text" name="Contact[address][' + index + '][5]" placeholder="Zip / Postal Code" />' +
					'</div>' +
				'</div>' +
				'<div class="wbs-field-group" style="margin-top: 10px;">' +
					'<label>Geo-Coordination (Lat, Long)</label>' +
					'<input type="text" name="Contact[address][' + index + '][6]" placeholder="e.g. 40.7128,-74.0060" />' +
				'</div>' +
			'</div>';

			$wrapper.find('.wbs-address-cards').append(newAddressCard);
			updateAddressRepeaterState();
		});

		$(document).on('click', '.wbs-btn-delete-address', function(e) {
			e.preventDefault();
			var $wrapper = $('#wbs-address-repeater');
			var count = $wrapper.find('.wbs-address-card').length;
			if (count <= 1) {
				alert('At least one address is required.');
				return;
			}
			$(this).closest('.wbs-address-card').remove();
			updateAddressRepeaterState();
		});

		// ----------------------------------------------------
		// 6. DYNAMIC "OTHER" FIELDS (SOCIAL MEDIA & LEGAL CODES)
		// ----------------------------------------------------
		$(document).on('click', '.wbs-btn-add-other', function(e) {
			e.preventDefault();
			var targetKey = $(this).data('target'); // 'Social_Media' or 'Legal_Codes'
			var $container = $('#wbs-other-' + targetKey);
			var index = $container.find('.wbs-other-row').length + 1;

			var placeholderValue = (targetKey === 'Social_Media') ? 'https://...' : 'Code / ID Value';
			var newOtherRow = '<div class="wbs-other-row">' +
				'<input type="text" name="' + targetKey + '[Other][' + index + '][label]" value="Other ' + index + '" placeholder="Custom Label" class="wbs-other-label" />' +
				'<input type="' + (targetKey === 'Social_Media' ? 'url' : 'text') + '" name="' + targetKey + '[Other][' + index + '][value]" value="" placeholder="' + placeholderValue + '" class="wbs-other-value" />' +
				'<button type="button" class="wbs-btn-delete-other wbs-btn-delete-row dashicons dashicons-trash" title="Remove"></button>' +
			'</div>';

			$container.append(newOtherRow);
		});

		$(document).on('click', '.wbs-btn-delete-other', function(e) {
			e.preventDefault();
			$(this).closest('.wbs-other-row').remove();
		});

		// ----------------------------------------------------
		// 7. IMPORT & EXPORT FEATURES (SETTINGS TAB)
		// ----------------------------------------------------
		// Export JSON
		$('#wbs-btn-export-json').on('click', function(e) {
			e.preventDefault();
			var formElement = document.getElementById('wbs-main-form');
			var formData = new FormData(formElement);
			formData.append('action', 'wbs_export_settings_json');

			var $btn = $(this);
			$btn.prop('disabled', true).text('Preparing Export...');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'wbs_export_settings_json',
					nonce: $('#wbs_nonce').val()
				},
				success: function(response) {
					$btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Export Settings as JSON');
					if (response.success && response.data) {
						var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(response.data, null, 2));
						var downloadAnchor = document.createElement('a');
						downloadAnchor.setAttribute("href", dataStr);
						var date = new Date().toISOString().slice(0, 10);
						downloadAnchor.setAttribute("download", "business-setup-settings-" + date + ".json");
						document.body.appendChild(downloadAnchor);
						downloadAnchor.click();
						downloadAnchor.remove();
					} else {
						alert('Export failed: ' + (response.data || 'Unknown error'));
					}
				},
				error: function() {
					$btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Export Settings as JSON');
					alert('Failed to connect to server for export.');
				}
			});
		});

		// Import JSON file reader
		$('#wbs-btn-import-json').on('click', function(e) {
			e.preventDefault();
			var fileInput = document.getElementById('wbs-import-file');
			if (!fileInput.files.length) {
				alert('Please choose a .json file to import.');
				return;
			}

			var file = fileInput.files[0];
			if (file.type !== 'application/json' && !file.name.endsWith('.json')) {
				alert('Please upload a valid JSON file.');
				return;
			}

			if (!confirm('Importing will overwrite current business setup configuration. Do you wish to continue?')) {
				return;
			}

			var reader = new FileReader();
			reader.onload = function(evt) {
				try {
					var parsedJson = JSON.parse(evt.target.result);
					// Send via AJAX to import
					$('#wbs-btn-import-json').prop('disabled', true).text('Importing...');
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'wbs_import_settings_json',
							nonce: $('#wbs_nonce').val(),
							json_data: JSON.stringify(parsedJson)
						},
						success: function(res) {
							if (res.success) {
								alert('Settings imported successfully! Reloading...');
								window.location.reload();
							} else {
								alert('Import failed: ' + (res.data || 'Invalid format'));
								$('#wbs-btn-import-json').prop('disabled', false).html('<span class="dashicons dashicons-upload"></span> Import JSON File');
							}
						},
						error: function() {
							alert('An error occurred during import.');
							$('#wbs-btn-import-json').prop('disabled', false).html('<span class="dashicons dashicons-upload"></span> Import JSON File');
						}
					});
				} catch (err) {
					alert('Invalid JSON file format: ' + err.message);
				}
			};
			reader.readAsText(file);
		});

		// Reset to Defaults
		$('#wbs-btn-reset-defaults').on('click', function(e) {
			e.preventDefault();
			if (confirm('Are you sure you want to reset all business setup options to defaults? All custom changes will be lost.')) {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'wbs_reset_defaults',
						nonce: $('#wbs_nonce').val()
					},
					success: function(res) {
						if (res.success) {
							alert('Settings successfully reset to defaults! Reloading...');
							window.location.reload();
						}
					}
				});
			}
		});

	});

})(jQuery);
