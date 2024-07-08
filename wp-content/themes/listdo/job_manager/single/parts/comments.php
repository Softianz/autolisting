<?php
global $post;
if ( listdo_listing_review_enable($post->ID) ) : ?>
	
	<?php comments_template(); ?>
<?php endif; ?>