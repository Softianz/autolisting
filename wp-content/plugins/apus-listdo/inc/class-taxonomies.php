<?php
/**
 * template loader
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ApusListdo_Taxonomies {
	
	public static function init() {
		$taxs = array( 'job_listing_category', 'job_listing_amenity', 'job_listing_region', 'job_listing_type' );
		foreach ($taxs as $tax) {
			add_filter( "manage_edit-{$tax}_columns", array( __CLASS__, 'tax_columns' ) );
			add_filter( "manage_{$tax}_custom_column", array( __CLASS__, 'tax_column' ), 10, 3 );
			add_action( "{$tax}_add_form_fields", array( __CLASS__, 'add_fields_form' ) );
			add_action( "{$tax}_edit_form_fields", array( __CLASS__, 'edit_fields_form' ), 10, 2 );
		}

		add_action( 'create_term', array( __CLASS__, 'save' )  );
		add_action( 'edit_term', array( __CLASS__, 'save' ) );
	}

	public static function add_fields_form($taxonomy) {
		?>

		<?php if ( $taxonomy == 'job_listing_amenity' ) { ?>
			<div class="form-field">
				<label><?php esc_html_e( 'Select Categories', 'apus-listdo' ); ?></label>
				<?php self::categories_field(); ?>
			</div>
		<?php } ?>

		<div class="form-field">
			<label><?php esc_html_e( 'Icon Type', 'apus-listdo' ); ?></label>
			<?php self::icon_type_field(); ?>
		</div>
		<div class="form-field icon-type-wrapper icon-type-font">
			<label><?php esc_html_e( 'Icon Font', 'apus-listdo' ); ?></label>
			<?php self::icon_font_field(); ?>
		</div>
		<div class="form-field icon-type-wrapper icon-type-image">
			<label><?php esc_html_e( 'Icon Image', 'apus-listdo' ); ?></label>
			<?php self::icon_image_field(); ?>
		</div>
		<?php if ( $taxonomy == 'job_listing_category' ) { ?>
			<div class="form-field">
				<label><?php esc_html_e( 'Color', 'apus-listdo' ); ?></label>
				<?php self::color_field(); ?>
			</div>
		<?php } ?>
		<?php
	}

	public static function edit_fields_form( $term, $taxonomy ) {
		$icon_type_value = get_term_meta( $term->term_id, 'apus_icon_type', true );
		$icon_font_value = get_term_meta( $term->term_id, 'apus_icon_font', true );
		$icon_image_value = get_term_meta( $term->term_id, 'apus_icon_image', true );
		
		?>
		
		<?php if ( $taxonomy == 'job_listing_amenity' ) {
			$category_value = get_term_meta( $term->term_id, 'apus_category_parent', true );
		?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Select Categories', 'apus-listdo' ); ?></label></th>
				<td>
					<?php self::categories_field($category_value); ?>
				</td>
			</tr>
		<?php } ?>

		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Type', 'apus-listdo' ); ?></label></th>
			<td>
				<?php self::icon_type_field($icon_type_value); ?>
			</td>
		</tr>
		<tr class="form-field icon-type-wrapper icon-type-font">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Font', 'apus-listdo' ); ?></label></th>
			<td>
				<?php self::icon_font_field($icon_font_value); ?>
			</td>
		</tr>
		<tr class="form-field icon-type-wrapper icon-type-image">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Image', 'apus-listdo' ); ?></label></th>
			<td>
				<?php self::icon_image_field($icon_image_value); ?>
			</td>
		</tr>
		<?php if ( $taxonomy == 'job_listing_category' ) {
			$color_value = get_term_meta( $term->term_id, 'apus_color', true );
		?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'apus-listdo' ); ?></label></th>
				<td>
					<?php self::color_field($color_value); ?>
				</td>
			</tr>
		<?php } ?>
		<?php
	}

	public static function categories_field( $val = '' ) {
		$args = array(
			'post_type' => 'job_listing_category',
			'posts_per_page' => -1,
			'post_status' =>'publish'
		);
		$terms = get_terms('job_listing_category', array(
    		'hide_empty' => 0,
		));
		?>
		<?php
		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach ($terms as $term) {
				$checked = '';
				if ( !is_array($val) ) {
					if ( $term->slug == $val ) {
						$checked = 'checked="checked"';
					}
				} elseif ( in_array($term->slug, $val) ) {
					$checked = 'checked="checked"';
				}
			?>
				<label>
					<input name="apus_category_parent[]" type="checkbox" value="<?php echo $term->slug; ?>" <?php echo trim($checked); ?>> <?php echo $term->name; ?>
				</label>
			<?php } ?>
		<?php } ?>
		<p><?php esc_html_e( 'Choose on what listing categories should this term be available. Set to empty for all.', 'apus-listdo' ); ?></p>
		<?php
	}

	public static function icon_type_field( $val = '' ) {
		?>
		<label>
			<input name="apus_icon_type" type="radio" value="font" <?php echo trim(empty($val) || $val == 'font' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Icon Font', 'apus-listdo'); ?>
		</label>
		<label>
			<input name="apus_icon_type" type="radio" value="image" <?php echo trim($val == 'image' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Icon Image', 'apus-listdo'); ?>
		</label>
		<?php
	}

	public static function icon_font_field( $val = '' ) {
		?>
		<input id="apus_tax_icon_font" name="apus_icon_font" type="text" value="<?php echo esc_attr($val); ?>">
		<?php
	}

	public static function icon_image_field( $val = '' ) {
		$avatar_url = '';
		if ( !empty($val) ) {
			$avatar_url = wp_get_attachment_image_src($val, 'full');
		}
		?>
		<div class="icon-image-wrapper">
			<div class="screenshot-user avatar-screenshot">
	            <?php if ( !empty($avatar_url[0]) ) { ?>
	                <img src="<?php echo esc_url($avatar_url[0]); ?>" alt="<?php esc_attr_e( 'Image', 'apus-listdo' ); ?>" />
	            <?php } ?>
	        </div>
	        <input class="widefat upload_image" name="apus_icon_image" type="hidden" value="<?php echo esc_attr($val); ?>" />
	        <div class="upload_image_action">
	            <input type="button" class="button radius-3x btn btn-theme user-add-image" value="<?php esc_attr_e( 'Add Icon Image', 'apus-listdo' ); ?>">
	            <input type="button" class="button radius-3x btn btn-theme-second user-remove-image" value="<?php esc_attr_e( 'Remove Icon Image', 'apus-listdo' ); ?>">
	        </div>
        </div>
		<?php
	}

	public static function color_field( $val = '' ) {
		?>
		<input id="apus_tax_color_input" name="apus_color" type="text" value="<?php echo esc_attr($val); ?>">
		<?php
	}

	public static function save( $term_id ) {
	    update_term_meta( $term_id, 'apus_icon_type', isset( $_POST['apus_icon_type'] ) ? $_POST['apus_icon_type'] : 'font' );
	    update_term_meta( $term_id, 'apus_icon_font', isset( $_POST['apus_icon_font'] ) ? $_POST['apus_icon_font'] : '' );
	    update_term_meta( $term_id, 'apus_icon_image', isset( $_POST['apus_icon_image'] ) ? $_POST['apus_icon_image'] : '' );

	    if ( isset( $_POST['apus_color'] ) ) {
	    	update_term_meta( $term_id, 'apus_color', $_POST['apus_color'] );
	    }

	    $old_value  = get_term_meta( $term_id, 'apus_category_parent', true );
    	$new_value = isset( $_POST['apus_category_parent'] ) ? $_POST['apus_category_parent'] : '';

		if ( $old_value && '' === $new_value ) {
	        delete_term_meta( $term_id, 'apus_category_parent' );
		} elseif ( $old_value !== $new_value ) {
	        update_term_meta( $term_id, 'apus_category_parent', $new_value );
	    }
	}

	public static function tax_columns( $columns ) {
		$new_columns = array();
		foreach ($columns as $key => $value) {
			if ( $key == 'name' ) {
				$new_columns['icon'] = esc_html__( 'Icon', 'apus-listdo' );
			}
			$new_columns[$key] = $value;
		}
		return $new_columns;
	}

	public static function tax_column( $columns, $column, $id ) {
		if ( $column == 'icon' ) {
			$icon_type_value = get_term_meta( $id, 'apus_icon_type', true );
			$icon_font_value = get_term_meta( $id, 'apus_icon_font', true );
			$icon_image_value = get_term_meta( $id, 'apus_icon_image', true );
			if ( $icon_type_value == 'font' && !empty($icon_font_value) ) {
				$columns .= '<i class="'.esc_attr($icon_font_value).'"></i>';
			} elseif ( $icon_type_value == 'image' && !empty($icon_image_value) ) {
				$image_url = wp_get_attachment_image_src($icon_image_value, 'full');
				if ( !empty($image_url[0]) ) {
					$columns .= '<img src="'.esc_url($image_url[0]).'" alt="'.esc_attr__( 'icon', 'apus-listdo' ).'" />';
				}
			}
		}
		return $columns;
	}
}
ApusListdo_Taxonomies::init();