<?php

global $post;
$args = array( 'position' => 'top', 'animation' => 'true' );
?>
<div class="apus-social-share">
		<strong class="title"><?php echo esc_html__('Share This Post:', 'listdo'); ?></strong>
		<?php if ( listdo_get_config('facebook_share', 1) ): ?>
			<a class="bo-social-facebook"  data-placement="<?php echo esc_attr($args['position']); ?>" data-animation="<?php echo esc_attr($args['animation']); ?>"  data-original-title="<?php echo esc_attr__('Facebook', 'listdo'); ?>" href="" onclick="javascript: window.open('http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>'); return false;" target="_blank" title="<?php echo esc_attr__('Share on facebook', 'listdo'); ?>">
				<i class="fab fa-facebook-f"></i>
				<span class="hidden"><?php echo esc_html__('facebook','listdo') ?></span>
			</a>
 
		<?php endif; ?>
		<?php if ( listdo_get_config('twitter_share', 1) ): ?>
 
			<a class="bo-social-twitter" data-placement="<?php echo esc_attr($args['position']); ?>" data-animation="<?php echo esc_attr($args['animation']); ?>" data-original-title="<?php echo esc_attr__('Twitter', 'listdo'); ?>" href="" onclick="javascript: window.open('http://twitter.com/home?status=<?php echo urlencode(get_the_title()); ?> <?php the_permalink(); ?>'); return false;" target="_blank" title="<?php echo esc_attr__('Share on Twitter', 'listdo'); ?>">
				<i class="fab fa-twitter"></i>
				<span class="hidden"><?php echo esc_html__('twitter','listdo') ?></span>
			</a>
 
		<?php endif; ?>
		<?php if ( listdo_get_config('linkedin_share', 1) ): ?>
 
			<a class="bo-social-linkedin"  data-placement="<?php echo esc_attr($args['position']); ?>" data-animation="<?php echo esc_attr($args['animation']); ?>"  data-original-title="<?php echo esc_attr__('LinkedIn', 'listdo'); ?>" href="" onclick="javascript: window.open('http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>'); return false;" target="_blank" title="<?php echo esc_attr__('Share on LinkedIn', 'listdo'); ?>">
				<i class="fab fa-linkedin-in"></i>
				<span class="hidden"><?php echo esc_html__('linkedin','listdo') ?></span>
			</a>
 
		<?php endif; ?>
		<?php if ( listdo_get_config('google_share', 1) ): ?>
 
			<a class="bo-social-google" data-placement="<?php echo esc_attr($args['position']); ?>" data-animation="<?php echo esc_attr($args['animation']); ?>"  data-original-title="<?php echo esc_attr__('Google plus', 'listdo'); ?>" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
	'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" title="<?php echo esc_attr__('Share on Google plus', 'listdo'); ?>">
				<i class="fab fa-google-plus-g"></i>
				<span class="hidden"><?php echo esc_html__('google','listdo') ?></span>
			</a>
 
		<?php endif; ?>
		<?php if ( listdo_get_config('pinterest_share', 1) ): ?>
 
			<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
			<a class="bo-social-pinterest" data-placement="<?php echo esc_attr($args['position']); ?>" data-animation="<?php echo esc_attr($args['animation']); ?>"  data-original-title="<?php echo esc_attr__('Pinterest', 'listdo'); ?>" href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;description=<?php echo urlencode($post->post_title); ?>&amp;media=<?php echo urlencode($full_image[0]); ?>" target="_blank" title="<?php echo esc_attr__('Share on Pinterest', 'listdo'); ?>">
				<i class="fab fa-pinterest-p"></i>
				<span class="hidden"><?php echo esc_html__('pinterest','listdo') ?></span>
			</a>
 
		<?php endif; ?>
</div>	