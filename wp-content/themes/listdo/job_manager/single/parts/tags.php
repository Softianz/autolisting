<?php
global $post;
$terms = get_the_terms($post->ID, 'job_listing_tag');

if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ):
?>
	<div id="listing-tags" class="listing-tags widget">
		<h2 class="widget-title">
			<span><?php esc_html_e('Tags', 'listdo'); ?></span>
		</h2>
		
		<div class="box-inner">
			<ul class="listing-tag-list">
				<?php foreach ( $terms as $term ) { ?>
					<li>
						<a href="<?php echo esc_url(get_term_link($term->term_id, 'job_listing_tag')); ?>">
							<span class="tag-title"><?php echo trim($term->name); ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php endif; ?>