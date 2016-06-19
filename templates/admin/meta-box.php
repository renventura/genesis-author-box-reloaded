<?php
/**
 *	Markup for the Author Box settings meta box
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<table class="form-table">

	<tbody>

		<?php do_action( 'genesis_author_box_reloaded_settings_metabox_start' ); ?>

		<tr valign="top">
			<th scope="row"><?php _e( 'License Key', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'License Key', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="genesis_author_box_reloaded_license_key"></label>
						<input type="text" class="large-text" id="genesis_author_box_reloaded_license_key" value="<?php esc_attr_e( $license_key ); ?>" style="width:70%;" />
						<?php if ( $license_status == 'valid' ) : ?>
							<span id="genesis_author_box_reloaded_deactivate_license" data-gabr-license-action="deactivate_license" class="genesis-author-box-reloaded-license-button button-secondary" style="display:none;"><?php _e( 'Deactivate License', 'genesis-author-box-reloaded' ); ?></span>
						<?php else: ?>
							<span id="genesis_author_box_reloaded_activate_license" data-gabr-license-action="activate_license" class="genesis-author-box-reloaded-license-button button-primary" style="display:none;"><?php _e( 'Activate License', 'genesis-author-box-reloaded' ); ?></span>
						<?php endif; ?>
						<img src="<?php echo admin_url( '/images/loading.gif' ); ?>" id="genesis_author_box_reloaded_license_ajax_spinner" alt="spinner" style="display:none;">
						<?php wp_nonce_field( 'genesis_author_box_reloaded_license_action', 'genesis_author_box_reloaded_license_action' ); ?>
					</p>

					<?php if ( genesis_author_box_reloaded_get_license_transient() ) : ?>

						<?php $data = genesis_author_box_reloaded_get_license_data(); ?>

						<div id="license-info" class="license-info <?php echo $data['site_status']; ?>">
							<p style="text-decoration:underline;font-weight:bold;"><?php _e( 'License Information', 'genesis-author-box-reloaded' ); ?></p>
							<p><?php _e( 'Status:', 'genesis-author-box-reloaded' ); ?> <span id="license-status"><strong><?php echo $data['site_status']; ?></strong></span></p>
							<p><?php _e( 'Expiration:', 'genesis-author-box-reloaded' ); ?> <span id="license-expiration"><strong><?php echo date( 'F j, Y', strtotime( $data['license_exp_date'] ) ); ?></strong></span></p>
						</div>

					<?php endif; ?>

					<div id="genesis_author_box_reloaded_license_action_response"></div>

				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Enable Author Box', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Enable Author Box', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_enable]">
							<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_enable]" value="1" <?php checked( $enabled, 1 ); ?> />
							<?php _e( 'Check to enable the author box', 'genesis-author-box-reloaded' ); ?>
						</label>
					</p>
					
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Position', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Position', 'genesis-author-box-reloaded' ); ?></legend>

					<?php foreach( $output_hooks as $hook => $label ) : ?>

						<p>
							<label for='<?php echo GENESIS_SETTINGS_FIELD . "[genesis_author_box_reloaded_position_{$hook}"; ?>'>
								<input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_position]" id='<?php echo GENESIS_SETTINGS_FIELD . "[genesis_author_box_reloaded_position_{$hook}"; ?>' value="<?php echo $hook; ?>" <?php checked( $position, $hook ); ?> />
								<?php echo $label; ?>
							</label>
						</p>

					<?php endforeach; ?>

					<span class="description"><?php _e( 'This is where the author box will be displayed.', 'genesis-author-box-reloaded' ); ?></span>

				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Post Types', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Post Types', 'genesis-author-box-reloaded' ); ?></legend>

					<?php foreach( get_post_types( array( 'public' => true ) ) as $type ) : ?>

						<?php if ( in_array( $type, $excluded_post_types ) ) continue; ?>

						<p>
							<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_post_types][<?php echo $type; ?>]">
								<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_post_types][<?php echo $type; ?>]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_post_types][<?php echo $type; ?>]" value="1" <?php checked( intval( isset( $post_types[$type] ) ? $post_types[$type] : '' ), 1 ); ?> />
								<?php echo ucwords( $type ); ?>
							</label>
						</p>

					<?php endforeach; ?>

					<span class="description"><?php _e( 'Which post types should display the author box?', 'genesis-author-box-reloaded' ); ?></span>

				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Social Links', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Social Links', 'genesis-author-box-reloaded' ); ?></legend>

					<?php foreach( $links as $link => $label ) : ?>

						<p>
							<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_social_links][<?php echo $link; ?>]">
								<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_social_links][<?php echo $link; ?>]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_social_links][<?php echo $link; ?>]" value="1" <?php checked( isset( $enabled_links[$link] ) ? 1 : 0, 1 ); ?> />
								<?php echo $label; ?>
							</label>
						</p>

					<?php endforeach; ?>

					<span class="description"><?php _e( 'Social links displayed in the author box. The author\'s profile links are set under the Contact Info section of the profile editor.', 'genesis-author-box-reloaded' ); ?></span>

				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Display Website?', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Display Website?', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_website]">
							<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_website]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_website]" value="1" <?php checked( $display_website, 1 ); ?> />
							<?php _e( 'Check to link to an author\'s website', 'genesis-author-box-reloaded' ); ?>
						</label>
					</p>
					
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Display RSS Link?', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Display RSS Link?', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_rss_link]">
							<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_rss_link]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_rss_link]" value="1" <?php checked( $display_website, 1 ); ?> />
							<?php _e( 'Check to link to an author\'s RSS feed', 'genesis-author-box-reloaded' ); ?>
						</label>
					</p>
					
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Display Gravatar?', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Display Gravatar?', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_gravatar]">
							<input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_gravatar]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_display_gravatar]" value="1" <?php checked( $avatar_enabled, 1 ); ?> />
							<?php _e( 'Check to display an author\'s Gravatar', 'genesis-author-box-reloaded' ); ?>
						</label>
					</p>
					
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Title Text', 'genesis-author-box-reloaded' ); ?></th>
			<td>
				<fieldset>
					
					<legend class="screen-reader-text"><?php _e( 'Title Text', 'genesis-author-box-reloaded' ); ?></legend>

					<p>
						<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_title_text]"></label>
						<input type="text" class="large-text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_title_text]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[genesis_author_box_reloaded_title_text]" value="<?php echo $title_text; ?>" />
					</p>

					<span class="description"><?php _e( 'i.e. "About the Author", "About John Smith", etc. You can use the following tags: <strong>{full_name}</strong>, and <strong>{display_name}</strong>.', 'genesis-author-box-reloaded' ); ?></span>

				</fieldset>
			</td>
		</tr>

		<?php do_action( 'genesis_author_box_reloaded_settings_metabox_end' ); ?>

	</tbody>

</table>

<style>
	#genesis_author_box_reloaded_license_action_response {
		background: #f1f1f1;
		display: none;
		margin:  10px 0 0;
		padding: 20px;
	}
	.license-info {
		border: 1px solid #ddd;
		border-left: 5px solid #F39C12;
		margin: 20px 0 0;
		padding: 20px;
	}
	.license-info.valid {
		border-left: 5px solid #17BC9C;
	}
	.license-info.expired {
		border-left: 5px solid #E74C3C;
	}
</style>

<script>
	jQuery(document).ready(function($) {

		$('.genesis-author-box-reloaded-license-button').show().click(function(e) {

			var	$this = $(this),
				spinner = $('#genesis_author_box_reloaded_license_ajax_spinner'),
				response_container = $('#genesis_author_box_reloaded_license_action_response');

			// Bail if already running
			if ( $this.hasClass('disabled') ) {
				return;
			}

			spinner.show();
			$this.attr('disabled', 'disabled').addClass('disabled');

			// Data
			gabr_license_data = {
				action: 'genesis_author_box_reloaded_license_action',
				nonce: $('#genesis_author_box_reloaded_license_action').val(),
				license_key: $('#genesis_author_box_reloaded_license_key').val(),
				action_type: $this.data('gabr-license-action')
			};

			// AJAX request
			$.post( ajaxurl, gabr_license_data, function(response){
				
				console.log(response);

				if ( response.status == 'success' ) {

					var status_icon = 'yes';

					$this.hide();

					if ( gabr_license_data.action_type == 'activate_license' ) {

						$('#genesis_author_box_reloaded_deactivate_license').show();
						
					} else {

						$('#genesis_author_box_reloaded_activate_license').show();
						$('#license-info').hide();

					}

				} else {

					var status_icon = 'no';
					$this.removeAttr('disabled').removeClass('disabled');

				}

				spinner.hide();

				// Display the response message
				response_container.html('<span class="dashicons dashicons-' + status_icon + '"></span>' + response.message).slideDown('400', function() {
					
					// Hide message after timeout
					setTimeout(function(){
						response_container.fadeOut();
					}, 3000);

				});

			});

			return false;

		});

	});
</script>