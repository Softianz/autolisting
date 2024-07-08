<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'apus-woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );


$thumbs_pos = listdo_get_config('product_thumbs_position', 'thumbnails-bottom');
$number_product_thumbs = listdo_get_config('number_product_thumbs', 5);

?>
<div class="apus-woocommerce-product-gallery-wrapper">
    
	<div class="slick-carousel apus-woocommerce-product-gallery" data-carousel="slick" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="true" data-slickparent="true">
		<?php
		
		if ( has_post_thumbnail() ) {
			$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'listdo' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

		do_action( 'woocommerce_product_thumbnails' );
		?>
	</div>
</div>
<div class="wrapper-thumbs <?php echo esc_attr( ( count($product->get_gallery_image_ids()) > 0 )?'':'hidden' ); ?>">
	<div class="slick-carousel apus-woocommerce-product-gallery-thumbs <?php echo esc_attr($thumbs_pos == 'thumbnails-left' || $thumbs_pos == 'thumbnails-right' ? 'vertical' : ''); ?>" data-carousel="slick" data-items="<?php echo esc_attr($number_product_thumbs); ?>" data-smallmedium="<?php echo esc_attr($number_product_thumbs); ?>" data-extrasmall="3" data-smallest="3" data-pagination="false" data-nav="false" data-asnavfor=".apus-woocommerce-product-gallery" data-slidestoscroll="1" data-focusonselect="true" <?php echo trim($thumbs_pos == 'thumbnails-left' || $thumbs_pos == 'thumbnails-right' ? 'data-vertical="true"' : ''); ?>>
		<?php

		if ( has_post_thumbnail() ) {
			$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
			$html .= get_the_post_thumbnail( $post->ID, 'woocommerce_thumbnail' );
			$html .= '</div></div>';
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder"><div class="thumbs-inner">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'listdo' ) );
			$html .= '</div></div>';
		}

		echo apply_filters( 'listdo_woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

		
		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids && has_post_thumbnail() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
				$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
				
				$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
				$html .= wp_get_attachment_image( $attachment_id, 'woocommerce_thumbnail', false );
		 		$html .= '</div></div>';

				echo apply_filters( 'listdo_woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
			}
		}

		?>
	</div>
</div>
