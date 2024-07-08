<?php
   $job = get_post_meta( get_the_ID(), 'apus_testimonial_job', true );
?>
<div class="testimonials-body light text-center">
   <div class="testimonials-profile">
         <div class="testimonial-avatar">
            <?php if(the_post_thumbnail('widget')){ ?>
                  <?php the_post_thumbnail('widget'); ?>
            <?php } ?>
         </div>
         <div class="description"><?php echo get_the_excerpt(); ?></div>
   </div> 
</div>