<?php
/**
 * Shows the `text` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/text-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $thepostid;
if ( is_admin() ) {
	wp_enqueue_script( 'jquery-highlight', get_template_directory_uri() . '/js/jquery.highlight.js', array( 'jquery' ), '5', true );
	wp_enqueue_script( 'leaflet', get_template_directory_uri() . '/js/leaflet/leaflet.js', array( 'jquery' ), '1.5.1', true );
	wp_enqueue_script( 'leaflet-GoogleMutant', get_template_directory_uri() . '/js/leaflet/Leaflet.GoogleMutant.js', array( 'jquery' ), '1.5.1', true );
	wp_enqueue_script( 'control-geocoder', get_template_directory_uri() . '/js/leaflet/Control.Geocoder.js', array( 'jquery' ), '1.5.1', true );
	wp_enqueue_script( 'esri-leaflet', get_template_directory_uri() . '/js/leaflet/esri-leaflet.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'esri-leaflet-geocoder', get_template_directory_uri() . '/js/leaflet/esri-leaflet-geocoder.js', array( 'jquery' ), '1.5.1', true );

	wp_enqueue_script( 'leaflet-markercluster', get_template_directory_uri() . '/js/leaflet/leaflet.markercluster.js', array( 'jquery' ), '1.5.1', true );
	wp_enqueue_script( 'leaflet-HtmlIcon', get_template_directory_uri() . '/js/leaflet/LeafletHtmlIcon.js', array( 'jquery' ), '1.5.1', true );
}
wp_register_script( 'listing-location', get_template_directory_uri() . '/js/listing-location.js', array('jquery'), '20141010', true );
wp_localize_script( 'listing-location', 'listing_opts', array(
	'geocoder_country' => listdo_get_config('listing_map_geocoder_country', ''),
	'latitude' => listdo_get_config('listing_map_latitude', ''),
	'longitude' => listdo_get_config('listing_map_longitude', ''),
	)
);
wp_enqueue_script( 'listing-location' );

$mapbox_token = '';
$mapbox_style = '';
$custom_style = '';
if ( listdo_get_config('listing_map_style_type', '') == 'mapbox' ) {
	$mapbox_token = listdo_get_config('listing_mapbox_token', '');
	$mapbox_style = listdo_get_config('listing_mapbox_style', '');
} else {
	$custom_style = listdo_get_config('listing_map_custom_style', '');
}
wp_localize_script( 'listing-location', 'listdo_listing_map_opts', array(
	'mapbox_token' => $mapbox_token,
	'mapbox_style' => $mapbox_style,
	'custom_style' => $custom_style,
));

if ( $thepostid ) {
	$job_id = $thepostid;
} else {
	$job_id = ! empty( $_REQUEST['job_id'] ) ? absint( $_REQUEST['job_id'] ) : 0;
}

$geo_latitude = get_post_meta( $job_id, 'geolocation_lat', true );
$geo_longitude = get_post_meta( $job_id, 'geolocation_long', true );

$location_friendly_name = 'job_location_friendly';
$location_friendly_value = get_post_meta( $job_id, '_job_location_friendly', true );
?>
<div class="listdo-location-field">
	<div class="row flex-middle-sm">
		<div class="col-md-6 col-xs-12">
			<fieldset>
				<label><?php esc_html_e( 'Friendly Location', 'listdo' ); ?></label>
				<div class="field">
					<input type="text" class="input-text" name="<?php echo esc_attr( $location_friendly_name ); ?>"<?php if ( isset( $field['autocomplete'] ) && false === $field['autocomplete'] ) { echo ' autocomplete="off"'; } ?> id="<?php echo esc_attr( $key ); ?>_friendly" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $location_friendly_value ); ?>" />
				</div>
			</fieldset>

			<?php if ( ! empty( $field['label'] ) ) : ?>
				<fieldset>
					<label><?php echo wp_kses_post($field['label']); ?></label>
				</fieldset>
			<?php endif; ?>
			
			<div class="listdo-location-field-inner">
				<input type="text" class="input-text input-location-field" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"<?php if ( isset( $field['autocomplete'] ) && false === $field['autocomplete'] ) { echo ' autocomplete="off"'; } ?> id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>" maxlength="<?php echo ! empty( $field['maxlength'] ) ? $field['maxlength'] : ''; ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> />

				<span class="find-me-location" title="<?php echo esc_attr__('Find Me', 'listdo'); ?>"><?php get_template_part( 'images/icon/location' ); ?></span>
				<span class="loading-me"></span>
				
			</div>
			<fieldset>
				<label><?php esc_html_e( 'Latitude', 'listdo' ); ?></label>
				<div class="field">
					<input class="geo_latitude" placeholder="<?php esc_attr_e('51.4980073', 'listdo'); ?>"  name="geo_latitude" value="<?php echo esc_attr( $geo_latitude); ?>" type="text">
				</div>
			</fieldset>
			<fieldset>
				<label><?php esc_html_e( 'Longitude', 'listdo' ); ?></label>
				<div class="field">
					<input class="geo_longitude" placeholder="<?php esc_attr_e('51.4980073', 'listdo'); ?>" name="geo_longitude" value="<?php echo esc_attr( $geo_longitude ); ?>" type="text">
				</div>
			</fieldset>
		</div>
		<div class="col-md-6 col-xs-12">
			<div id="listdo-location-field-map" class="listdo-location-field-map"></div>
		</div>
	</div>
</div>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo trim($field['description']); ?></small><?php endif; ?>