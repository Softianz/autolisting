<?php
global $post;

$layout_version = listdo_get_listing_archive_version();
$columns = listdo_get_listing_item_columns();
$bcol_md = 12/$columns;
$bcol_sm = ($columns >= 2) ? 6 : 12;
$item_stype = listdo_get_listing_item_style();
$sidebar_position = listdo_get_archive_layout();
$clear = $columns;

$class = 'lg-clear-'.$columns.' md-clear-'.$clear.' col-lg-'.(12/$columns).' col-md-'.$bcol_md.' col-sm-'.$bcol_sm.' col-xs-12';
if ( $item_stype == 'list' || $item_stype == 'list-v2' || $item_stype == 'list-v3' ) {
	$class = 'col-md-12 col-sm-12';
}
?>
<div class="<?php echo esc_attr($class); ?>	">
	<?php get_template_part( 'job_manager/loop/'.$item_stype ); ?>
</div>