<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $thepostid;
if ( $thepostid ) {
	$job_id = $thepostid;
} else {
	$job_id = ! empty( $_REQUEST['job_id'] ) ? absint( $_REQUEST['job_id'] ) : 0;
}

$items_data = get_post_meta( $job_id, '_job_menu_prices_data', true );

?>
<div class="menu-prices-field group-fields">
	<?php if ( is_admin() ) { ?>
		<h3 class="title"><?php echo esc_html( $field['label'] ) ; ?></h3>
	<?php } ?>
	<div class="menu-prices-field-wrapper">
		<?php if ( !empty($items_data) && is_array($items_data) ) {
			$i = 0; foreach ($items_data as $section) {
				?>
					<div class="menu-prices-section-item group-field-item">
						<div class="menu-prices-section-item-title group-field-item-title"><?php esc_html_e('Section', 'listdo'); ?> #<span><?php echo esc_html($i + 1); ?></span></div>
						<div class="group-field-item-content">
							<div class="job-manager-section-title">
								<h3><?php esc_html_e('Section Title', 'listdo'); ?></h3>
								<input type="text" class="input-text input-section-title" placeholder="<?php esc_attr_e('Title', 'listdo'); ?>" name="_job_menu_prices[<?php echo esc_attr($i); ?>][section_title]" value="<?php echo esc_attr(!empty($section['section_title']) ? $section['section_title'] : ''); ?>"/>
							</div>
							<h4><?php esc_html_e('Section Items', 'listdo'); ?></h4>
							<!-- loop here -->
							<div class="menu-prices-section-item-wrapper section-item-wrapper">
								<?php
								$titles_value = !empty($section['title']) ? $section['title'] : array();
								$items_prices = !empty($section['price']) ?  $section['price'] : array();
								$items_descriptions = !empty($section['description']) ? $section['description'] : array();
								$j = 0;
								foreach ($titles_value as $key => $title_value) {
									$price_value = !empty($items_prices[$key]) ? $items_prices[$key] : '';
									$description_value = !empty($items_descriptions[$key]) ? $items_descriptions[$key] : '';
								?>
									<div class="menu-prices-item group-field-item">
										<div class="group-field-item-title"><?php esc_html_e('Item', 'listdo'); ?> #<span><?php echo esc_html($j + 1); ?></span></div>
										<div class="group-field-item-content">
											<div class="row">
												<div class="col-sm-6">
													<div class="job-manager-title">
														<label><?php esc_html_e('Title', 'listdo'); ?></label>
														<input type="text" placeholder="<?php esc_attr_e('Title...', 'listdo'); ?>" class="input-text input-section-item-title" name="_job_menu_prices[<?php echo esc_attr($i); ?>][title][]" value="<?php echo esc_attr($title_value); ?>"/>
													</div>
													<div class="job-manager-price">
														<label><?php esc_html_e('Price', 'listdo'); ?></label>
														<input type="text" placeholder="<?php esc_attr_e('Price...', 'listdo'); ?>" class="input-text input-section-item-price" name="_job_menu_prices[<?php echo esc_attr($i); ?>][price][]" value="<?php echo esc_attr($price_value); ?>"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="job-manager-description">
														<label><?php esc_html_e('Description', 'listdo'); ?></label>
														<textarea placeholder="<?php esc_attr_e('Description...', 'listdo'); ?>" cols="20" rows="3" class="input-text input-section-item-description" name="_job_menu_prices[<?php echo esc_attr($i); ?>][description][]"><?php echo trim($description_value); ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php $j++; } ?>
							</div>
							<!-- end loop -->
							<div class="section-item-wrapper">
								<a class="add-new-menu-price button btn btn-success" href="javascript:void(0);"><?php esc_html_e( 'Add New', 'listdo' ); ?></a>
								<a class="remove-menu-price button btn btn-danger" href="javascript:void(0);"><?php esc_html_e( 'Remove', 'listdo' ); ?></a>
							</div>
						</div>
					</div>
				<?php
			$i++; }
		} else { ?>
				<div class="menu-prices-section-item group-field-item">
					<div class="menu-prices-section-item-title group-field-item-title"><?php esc_html_e('Section', 'listdo'); ?> #<span>1</span></div>
					<div class="group-field-item-content">
						<div class="job-manager-section-title">
							<h3><?php esc_html_e('Section Title', 'listdo'); ?></h3>
							<input type="text" class="input-text input-section-title" placeholder="<?php esc_attr_e('Title', 'listdo'); ?>" name="_job_menu_prices[0][section_title]" value=""/>
						</div>
						<h4><?php esc_html_e('Section Items', 'listdo'); ?></h4>
						<div class="menu-prices-section-item-wrapper section-item-wrapper">
							<div class="menu-prices-item group-field-item">
								<div class="group-field-item-title"><?php esc_html_e('Item', 'listdo'); ?> #<span>1</span></div>
								<div class="group-field-item-content">
									<div class="row">
										<div class="col-sm-6">
											<div class="job-manager-title">
												<label><?php esc_html_e('Title', 'listdo'); ?></label>
												<input type="text" placeholder="<?php esc_attr_e('Title...', 'listdo'); ?>" class="input-text input-section-item-title" name="_job_menu_prices[0][title][]" value=""/>
											</div>
											<div class="job-manager-price">
												<label><?php esc_html_e('Price', 'listdo'); ?></label>
												<input type="text" placeholder="<?php esc_attr_e('Price...', 'listdo'); ?>" class="input-text input-section-item-price" name="_job_menu_prices[0][price][]" value=""/>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="job-manager-description">
												<label><?php esc_html_e('Description', 'listdo'); ?></label>
												<textarea cols="20" rows="3" placeholder="<?php esc_attr_e('Description...', 'listdo'); ?>" class="input-text input-section-item-description" name="_job_menu_prices[0][description][]"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="add-new-menu-price button  btn btn-success" href="javascript:void(0);"><?php esc_html_e( 'Add New Menu', 'listdo' ); ?></a>
						<a class="remove-menu-price button  btn btn-danger" href="javascript:void(0);"><?php esc_html_e( 'Remove Menu', 'listdo' ); ?></a>
					</div>
				</div>
		<?php } ?>
	</div>
	<a class="add-new-section-menu-price btn-action button btn btn-success" href="javascript:void(0);"><?php esc_html_e( 'Add New Section', 'listdo' ); ?></a>
	<a class="remove-section-menu-price btn-action button btn btn-danger" href="javascript:void(0);"><?php esc_html_e( 'Remove Section', 'listdo' ); ?></a>
</div>