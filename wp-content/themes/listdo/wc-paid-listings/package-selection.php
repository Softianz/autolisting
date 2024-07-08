<?php if ( $packages || $user_packages ) :
	$checked = 1;
	?>
	<?php if ( $user_packages ) : ?>
		<div class="widget widget-your-packages">
			<h3 class="title"><?php esc_html_e( 'Your Packages', 'listdo' ); ?></h3>
			<ul class="job_packages">
				<?php foreach ( $user_packages as $key => $package ) :
					$package = wc_paid_listings_get_package( $package );
					?>
					<li class="user-job-package">
						<input type="radio" <?php checked( $checked, 1 ); ?> name="job_package" value="user-<?php echo esc_attr($key); ?>" id="user-package-<?php echo esc_attr($package->get_id()); ?>" />
						<label for="user-package-<?php echo esc_attr($package->get_id()); ?>"><?php echo trim($package->get_title()); ?></label><br/>
						<?php
							if ( $package->get_limit() ) {
								printf( _n( '%s job posted out of %d', '%s jobs posted out of %d', $package->get_count(), 'listdo' ), $package->get_count(), $package->get_limit() );
							} else {
								printf( _n( '%s job posted', '%s jobs posted', $package->get_count(), 'listdo' ), $package->get_count() );
							}

							if ( $package->get_duration() ) {
								printf(  ', ' . _n( 'listed for %s day', 'listed for %s days', $package->get_duration(), 'listdo' ), $package->get_duration() );
							}

							$checked = 0;
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			
			<button class="button btn btn-danger" type="submit">
				<?php esc_html_e('Add Listing', 'listdo') ?>
			</button>
		</div>
	<?php endif; ?>

	<?php if ( $packages ) : ?>
		<div class="widget widget-packages widget-subwoo">
			<div class="row">
				<?php foreach ( $packages as $key => $package ) :
					$product = wc_get_product( $package );
					if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
						continue;
					}
					?>

					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="subwoo-inner <?php echo esc_attr( $product->is_featured()?'highlight_product':'' ); ?>">
							<div class="header-sub">
								<div class="inner-sub">
									<?php
	                                $icon_class = get_post_meta($product->get_id(), '_listings_icon_class', true);
	                                if ( $icon_class ) {
	                                    ?>
	                                    <div class="icon-wrapper">
	                                        <span class="<?php echo esc_attr($icon_class); ?>"></span>
	                                    </div>
	                                    <?php
	                                }
	                                ?>
	                                <?php if($product->is_featured()){ ?>
                                        <div class="featured">
                                            <span class="bg-theme featured-inner"><?php echo esc_html__('Most Popular','listdo') ?></span>
                                        </div>
                                    <?php } ?>
									<h3 class="title"><?php echo trim($product->get_title()); ?></h3>
								</div>
							</div>
							<div class="price">
								<?php echo (!empty($product->get_price())) ? $product->get_price_html() : esc_html__('Free', 'listdo'); ?>
							</div>
							<div class="bottom-sub">
								<?php if( get_post_field('post_content', $product->get_id()) ) { ?>
	                            	<p><?php echo get_post_field('post_content', $product->get_id()); ?></p>
	                            <?php } ?>
								<?php if( get_post_field('post_excerpt', $product->get_id()) ) { ?>
	                            	<div class="short-des"><?php echo get_post_field('post_excerpt', $product->get_id()); ?></div>
	                            <?php } ?>
								<div class="button-action">
									<button class="button product_type_simple add_to_cart_button ajax_add_to_cart btn" type="submit" name="job_package" value="<?php echo esc_attr($product->get_id()); ?>" id="package-<?php echo esc_attr($product->get_id()); ?>">
										<?php esc_html_e('Get Started', 'listdo') ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php else : ?>

	<p><?php esc_html_e( 'No packages found', 'listdo' ); ?></p>

<?php endif; ?>
