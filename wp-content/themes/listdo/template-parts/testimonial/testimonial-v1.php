<?php
   $job = get_post_meta( get_the_ID(), 'apus_testimonial_job', true );
?>
<div class="testimonials-body testimonials-v1">
   <div class="testimonials-profile">
   		<div class="top-inner">
         <div class="description"><?php echo listdo_substring(get_the_excerpt(),45,'.') ?></div>
        
         <div class="info">
         	<?php if (!empty($link)) { ?>
	          <h3 class="name-client"><a class="text-theme" href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h3>
	        <?php } else { ?>
	          <h3 class="name-client text-theme"><?php the_title(); ?></h3>
	        <?php } ?>
	        <span class="job"> <span class="space">-</span> <?php echo esc_html($job); ?></span>
	    </div>
      </div>
   </div> 
</div>