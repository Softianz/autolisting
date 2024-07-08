<?php
if ( !function_exists ('listdo_custom_styles') ) {
	function listdo_custom_styles() {
		ob_start();	
		?>

			<?php
				$main_font = listdo_get_config('main_font');
				$main_font_family = isset($main_font['font-family']) ? $main_font['font-family'] : false;
				$main_font_size = isset($main_font['font-size']) ? $main_font['font-size'] : false;
				$main_font_weight = isset($main_font['font-weight']) ? $main_font['font-weight'] : false;
			?>
			<?php if ( $main_font_family ): ?>
				/* Main Font */
				.btn,
				body
				{
					font-family:  <?php echo '\'' . $main_font_family . '\','; ?> sans-serif;
				}
			<?php endif; ?>
			<?php if ( $main_font_size ): ?>
				/* Main Font Size */
				body
				{
					font-size: <?php echo esc_html($main_font_size); ?>;
				}
			<?php endif; ?>
			<?php if ( $main_font_weight ): ?>
				/* Main Font Weight */
				body
				{
					font-weight: <?php echo esc_html($main_font_weight); ?>;
				}
			<?php endif; ?>


			<?php
				$heading_font = listdo_get_config('heading_font');
				$heading_font_family = isset($heading_font['font-family']) ? $heading_font['font-family'] : false;
				$heading_font_weight = isset($heading_font['font-weight']) ? $heading_font['font-weight'] : false;
			?>
			<?php if ( $heading_font_family ): ?>
				/* Heading Font */
				h1, h2, h3, h4, h5, h6
				{
					font-family:  <?php echo '\'' . $heading_font_family . '\','; ?> sans-serif;
				}			
			<?php endif; ?>

			<?php if ( $heading_font_weight ): ?>
				/* Heading Font Weight */
				h1, h2, h3, h4, h5, h6
				{
					font-weight: <?php echo esc_html($heading_font_weight); ?>;
				}			
			<?php endif; ?>
			

			/* check main color */ 
			<?php if ( listdo_get_config('main_color') != "" ) : ?>
				/* seting border color main */
				.woocommerce #respond input#submit:hover, .woocommerce #respond input#submit:active, .woocommerce a.button:hover, .woocommerce a.button:active, .woocommerce button.button:hover, .woocommerce button.button:active, .woocommerce input.button:hover, .woocommerce input.button:active,
				.subwoo-inner .button-action .added_to_cart, .subwoo-inner .button-action .button,
				.subwoo-inner .button-action .added_to_cart.added_to_cart, .subwoo-inner .button-action .added_to_cart:hover, .subwoo-inner .button-action .added_to_cart:focus, .subwoo-inner .button-action .button.added_to_cart, .subwoo-inner .button-action .button:hover, .subwoo-inner .button-action .button:focus,
				.post-navigation .nav-links > :hover .meta-nav,
				.post-navigation .nav-links .meta-nav,
				.border-theme
				{
					border-color: <?php echo esc_html( listdo_get_config('main_color') ) ?>;
				}

				/* seting background main */
				form.cart .single_add_to_cart_button,form.cart .single_add_to_cart_button:hover,
				.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
				.woocommerce #respond input#submit:hover, .woocommerce #respond input#submit:active, .woocommerce a.button:hover, .woocommerce a.button:active, .woocommerce button.button:hover, .woocommerce button.button:active, .woocommerce input.button:hover, .woocommerce input.button:active,
				.apus-top-cart .mini-cart .count,.product-block .add-cart .added_to_cart, .product-block .add-cart .button,
				.product-block .add-cart .added_to_cart:focus, .product-block .add-cart .added_to_cart:hover, .product-block .add-cart .button:focus, .product-block .add-cart .button:hover,
				.subwoo-inner .button-action .added_to_cart.added_to_cart, .subwoo-inner .button-action .added_to_cart:hover, .subwoo-inner .button-action .added_to_cart:focus, .subwoo-inner .button-action .button.added_to_cart, .subwoo-inner .button-action .button:hover, .subwoo-inner .button-action .button:focus,
				.post-navigation .nav-links > :hover .meta-nav,
				#back-to-top:active, #back-to-top:hover,
				.bg-theme
				{
					background: <?php echo esc_html( listdo_get_config('main_color') ) ?>;
				}
				/* setting color*/
				.subwoo-inner .button-action .added_to_cart, .subwoo-inner .button-action .button,
				.post-navigation .nav-links .meta-nav,
				.header-top-job.style-white .entry-header a.apus-bookmark-added, .header-top-job.style-white .entry-header a:hover, .header-top-job.style-white .entry-header a:focus,
				.sidebar-detail-job .listing-day.current,
				.apus-single-listing .direction-map.active, .apus-single-listing .direction-map.active i, .apus-single-listing .direction-map:hover, .apus-single-listing .direction-map:hover i,
				.job_filters .job_tags label.active::before, .job_filters .job_amenities label.active::before,
				a:hover, a:focus{
					color: <?php echo esc_html( listdo_get_config('main_color') ) ?>;
				}

				.highlight,
				.text-theme{
					color: <?php echo esc_html( listdo_get_config('main_color') ) ?> !important;
				}
				.bg-theme
				{
					background: <?php echo esc_html( listdo_get_config('main_color') ) ?> !important;
				}

			<?php endif; ?>



			/* button for theme */
			<?php if ( listdo_get_config('button_color') != "" ) : ?>
				.btn-theme{
					background: <?php echo esc_html( listdo_get_config('button_color') ) ?>;
					border-color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
				}
				.btn-theme.btn-outline{
					border-color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
					color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
				}

				.btn-theme.btn-outline:focus,
				.btn-theme.btn-outline:hover{
					background-color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
					border-color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
				}
				.btn-theme.btn-inverse:hover,
				.btn-theme.btn-inverse:focus{
					border-color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
					color:<?php echo esc_html( listdo_get_config('button_color') ) ?>;
				}
			<?php endif; ?>

			<?php if ( listdo_get_config('button_hover_color') != "" ) : ?>
				.btn-theme:focus,
				.btn-theme:active,
				.btn-theme:hover{
					background: <?php echo esc_html( listdo_get_config('button_hover_color') ) ?>;
					border-color:<?php echo esc_html( listdo_get_config('button_hover_color') ) ?>;
				}

			<?php endif; ?>

	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}