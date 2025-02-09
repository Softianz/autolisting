<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Walks through categories.
 *
 * @extends Walker
 * @package wp-job-manager
 * @since 1.0.0
 */
class Listdo_WP_Job_Manager_Category_Walker extends Walker {

	/**
	 * Tree type that the class handles.
	 *
	 * @var string
	 */
	public $tree_type = 'category';

	/**
	 * Database fields to use.
	 *
	 * @var array
	 */
	public $db_fields = array(
		'parent' => 'parent',
		'id'     => 'term_id',
		'slug'   => 'slug',
	);

	/**
	 * Start the list walker.
	 *
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $object Category data object.
	 * @param int    $depth Depth of category in reference to parents.
	 * @param array  $args
	 * @param int    $current_object_id
	 */
	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {

		if ( ! empty( $args['hierarchical'] ) ) {
			$pad = str_repeat( '&nbsp;', $depth * 3 );
		} else {
			$pad = '';
		}

		$cat_name = apply_filters( 'list_product_cats', $object->name, $object );

		$value = isset( $args['value'] ) && 'id' === $args['value'] ? $object->term_id : $object->slug;
		$classes = array('level-' . intval( $depth ));
		if ( !empty($args['taxonomy']) && $args['taxonomy'] == 'job_listing_type' ) {
			$type_slugs = get_term_meta( $object->term_id, 'apus_category_parent', true );
			if ( !empty($type_slugs) ) {
				foreach ($type_slugs as $slug) {
					$term = get_term_by('slug', $slug, 'job_listing_category');
					if ( $term ) {
						$classes[] = $term->slug;
					}
				}
			}
		}
		$output .= "\t<option class=\"" . implode(' ', $classes) . '" value="' . esc_attr( $value ) . '"';


		if ( isset( $args['selected'] ) && (
				$value == $args['selected'] // phpcs:ignore WordPress.PHP.StrictComparisons
				|| ( is_array( $args['selected'] ) && in_array( $value, $args['selected'] ) ) // phpcs:ignore WordPress.PHP.StrictInArray
			 )
		) {
			$output .= ' selected="selected"';
		}

		$output .= '>';

		$output .= $pad . esc_html( $cat_name );

		if ( ! empty( $args['show_count'] ) ) {
			$output .= '&nbsp;(' . intval( $object->count ) . ')';
		}

		$output .= "</option>\n";
	}
}
