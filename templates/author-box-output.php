<?php
/**
 *	Output the author box
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'genesis_author_box_before_output' ); ?>

<section class="author-box author-box-reloaded" itemprop="author" itemscope="" itemtype="http://schema.org/Person">

	<?php do_action( 'genesis_author_box_output_start' ); ?>
	
	<?php if ( $avatar_enabled ) : ?>
		<img alt="avatar" src="<?php echo $gravatar_url; ?>" srcset="<?php echo $gravatar_url; ?>" class="gravatar avatar photo avatar-<?php echo apply_filters( 'genesis_author_box_reloaded_gravatar_output_size', 96 ); ?>" height="<?php echo apply_filters( 'genesis_author_box_reloaded_gravatar_output_size', 96 ); ?>" width="<?php echo apply_filters( 'genesis_author_box_reloaded_gravatar_output_size', 96 ); ?>">
	<?php endif; ?>

	<h4 class="author-box-title"><?php echo $title_text; ?></h4>

	<?php if ( $bio ) : ?>
		<div class="author-box-content" itemprop="description"><?php echo $bio; ?></div>
	<?php endif; ?>

	<?php if ( genesis_author_box_reloaded_author_has_profiles( $author_id ) ) : ?>

		<div class="author-box-icons">

			<?php foreach ( $links as $key => $val ) : ?>

				<?php
					// Validate the profile URL
					$valid_profile_url = genesis_author_box_reloaded_is_valid_url( get_the_author_meta( $key, $author_id ) );
				?>

				<?php if ( get_the_author_meta( $key, $author_id ) && $valid_profile_url ) : // Author's Social Links ?>

					<span class="author-box-icon">
						<a href="<?php echo $valid_profile_url; ?>" target="<?php echo apply_filters( 'genesis_author_box_reloaded_links_target', '_blank' ); ?>"><img src="<?php echo $icons[$key]; ?>" alt="<?php echo $key; ?>" width="<?php echo apply_filters( 'genesis_author_box_reloaded_icon_size', 40 ); ?>"></a>
					</span>

				<?php endif; ?>

			<?php endforeach; ?>

			<?php if ( $show_website && get_the_author_meta( 'user_url', $author_id ) ) : // Author's Website ?>

				<span class="author-box-icon">
					<a href="<?php echo get_the_author_meta( 'user_url', $author_id ); ?>" target="<?php echo apply_filters( 'genesis_author_box_reloaded_links_target', '_blank' ); ?>"><img src="<?php echo GENESIS_AUTHOR_BOX_PLUGIN_DIR_URL . 'assets/images/website.png'; ?>" alt="<?php _e( 'Website', 'genesis-author-box-reloaded' ); ?>" width="<?php echo apply_filters( 'genesis_author_box_reloaded_icon_size', 40 ); ?>"></a>
				</span>

			<?php endif; ?>

			<?php if ( $show_rss ) : // Author's RSS Feed ?>

				<span class="author-box-icon">
					<a href="<?php echo get_author_feed_link( $author_id ); ?>" target="<?php echo apply_filters( 'genesis_author_box_reloaded_links_target', '_blank' ); ?>"><img src="<?php echo GENESIS_AUTHOR_BOX_PLUGIN_DIR_URL . 'assets/images/rss.png'; ?>" alt="<?php _e( 'RSS', 'genesis-author-box-reloaded' ); ?>" width="<?php echo apply_filters( 'genesis_author_box_reloaded_icon_size', 40 ); ?>"></a>
				</span>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'genesis_author_box_output_end' ); ?>

</section>

<?php do_action( 'genesis_author_box_after_output' ); ?>