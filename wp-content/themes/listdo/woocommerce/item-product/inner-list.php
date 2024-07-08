<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-block product-block-list" data-product-id="<?php echo esc_attr($product_id); ?>">
	<div class="box-list-2">
		<div class="row flex-middle">
			<div class="col-xs-6">
				<div class="inner">
				    <figure class="image">
				        <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="product-image">
				            <?php
				                /**
				                * woocommerce_before_shop_loop_item_title hook
				                *
				                * @hooked woocommerce_show_product_loop_sale_flash - 10
				                * @hooked woocommerce_template_loop_product_thumbnail - 10
				                */
				                remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
				                do_action( 'woocommerce_before_shop_loop_item_title' );
				            ?>
				        </a>
				        <?php do_action('listdo_woocommerce_before_shop_loop_item'); ?>
				    </figure>
				</div>    
			</div> 
			<div class="col-xs-6">   
			    <div class="wrapper-info">
				    <div class="caption-list">
			         	<h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			         	<?php
				            /**
				            * woocommerce_after_shop_loop_item_title hook
				            *
				            * @hooked woocommerce_template_loop_rating - 5
				            * @hooked woocommerce_template_loop_price - 10
				            */
				            remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
				            do_action( 'woocommerce_after_shop_loop_item_title');
				        ?>
		        		<div class="product-excerpt hidden-sm hidden-xs">
		            		<?php woocommerce_template_single_excerpt(); ?>
		            	</div>
				    </div>
				</div>  
				<div class="caption-buttons">
			        <?php
		                // Availability
			        	$availability      = $product->get_availability();
		                $availability_html = empty( $availability['availability'] ) ? '' : '<div class="avaibility-wrapper">'.esc_html__('Avaibility:', 'listdo').' <span class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</span></div>';
		                echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
		            ?>
			        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		    	</div>
		    </div>      
	    </div>
	</div>
</div>