<?php

$rand_id = rand(123, 9878787);
$default_fields = apuslistdo_get_default_field_types();

$available_fields = apuslistdo_all_types_available_fields();
$required_types = apuslistdo_all_types_required_fields();

$custom_all_fields_saved_data = apuslistdo_get_custom_fields_data();

?>
<div class="custom-fields-wrapper clearfix">
                
    <div class="apuslistdo-custom-field-form" id="apuslistdo-custom-field-form-<?php echo esc_attr($rand_id); ?>">
        <div class="box-wrapper">
            <h3 class="title"><?php echo esc_html('List of Fields', 'apus-listdo'); ?></h3>
            <ul id="foo<?php echo esc_attr($rand_id); ?>" class="block__list block__list_words"> 
                <?php

                $count_node = 1000;
                $output = '';
                $all_fields_name_count = 0;
                $disabled_fields = array();

                if (is_array($custom_all_fields_saved_data) && sizeof($custom_all_fields_saved_data) > 0) {
                    $field_names_counter = 0;
                    $types = apuslistdo_get_all_field_types();
                    
                    foreach ($custom_all_fields_saved_data as $key => $custom_field_saved_data) {
                        $all_fields_name_count++;
                        
                        $li_rand_id = rand(454, 999999);

                        $output .= '<li class="custom-field-class-' . $li_rand_id . '">';

                        $fieldtype = $custom_field_saved_data['type'];

                        $delete = true;
                        if ( isset($required_types[$fieldtype]) ) {
                            $count_node ++;
                            $dfield_values = $required_types[$fieldtype];
                            $delete = false;
                            $field_values = wp_parse_args( $custom_field_saved_data, $dfield_values);
                            if ( in_array( $fieldtype, array('job_title') ) ) {
                                $output .= apply_filters('apuslistdo_custom_field_available_simple_html', $fieldtype, $count_node, $field_values);
                            } else {
                                $output .= apply_filters('apuslistdo_custom_field_available_'.$fieldtype.'_html', $fieldtype, $count_node, $field_values);
                            }
                        } elseif ( isset($available_fields[$fieldtype]) ) {
                            $count_node ++;
                            $dfield_values = $available_fields[$fieldtype];

                            $field_values = wp_parse_args( $custom_field_saved_data, $dfield_values);
                            $dtypes = apply_filters( 'apuslistdo_list_simple_type', array('job_title', 'job_hours', 'job_email', 'job_tagline', 'job_location', 'job_email', 'job_website', 'job_phone', 'job_video', 'job_date', 'job_start_date', 'job_finish_date', 'job_socials', 'job_price_from', 'job_price_to', 'job_price_range', 'job_hours', 'job_menu_prices', 'job_tags', 'job_regions', 'job_categories' ) );
                            if ( in_array( $fieldtype, $dtypes) ) {
                                $output .= apply_filters('apuslistdo_custom_field_available_simple_html', $fieldtype, $count_node, $field_values);
                            } elseif ( in_array( $fieldtype, array('job_category', 'job_amenities', 'job_type') ) ) {
                                $output .= apply_filters('apuslistdo_custom_field_available_tax_html', $fieldtype, $count_node, $field_values);
                            } elseif ( in_array($fieldtype, array( 'job_logo', 'job_cover_image' ) )) {
                                $output .= apply_filters('apuslistdo_custom_field_available_file_html', $fieldtype, $count_node, $field_values);
                            } elseif ( in_array($fieldtype, array( 'job_gallery', 'job_gallery_images') )) {
                                $output .= apply_filters('apuslistdo_custom_field_available_files_html', $fieldtype, $count_node, $field_values);
                            } else {
                                $output .= apply_filters('apuslistdo_custom_field_available_'.$fieldtype.'_html', $fieldtype, $count_node, $field_values);
                            }
                            $disabled_fields[] = $fieldtype;
                        } elseif ( in_array($fieldtype, $types) ) {
                            $count_node ++;
                            if ( in_array( $fieldtype, array('text', 'textarea', 'wp-editor', 'number', 'url', 'email', 'checkbox') ) ) {
                                $output .= apply_filters('apuslistdo_custom_field_text_html', $fieldtype, $count_node, $custom_field_saved_data);
                            } elseif ( in_array( $fieldtype, array('select', 'multiselect', 'radio') ) ) {
                                $output .= apply_filters('apuslistdo_custom_field_opts_html', $fieldtype, $count_node, $custom_field_saved_data);
                            } else {
                                $output .= apply_filters('apuslistdo_custom_field_'.$fieldtype.'_html', $fieldtype, $count_node, $custom_field_saved_data);
                            }
                        }

                        $output .= apply_filters('apuslistdo_custom_field_actions_html', $li_rand_id, $count_node, $fieldtype, $delete);
                        $output .= '</li>';
                    }
                } else {
                    foreach ($required_types as $key => $field_values) {
                        $count_node ++;
                        $li_rand_id = rand(454, 999999);
                        $output .= '<li class="custom-field-class-' . $li_rand_id . '">';
                        $output .= apply_filters('apuslistdo_custom_field_available_simple_html', $key, $count_node, $field_values);

                        $output .= apply_filters('apuslistdo_custom_field_actions_html', $li_rand_id, $count_node, $key, false);
                        $output .= '</li>';
                    }
                }
                echo force_balance_tags($output);
                ?>
            </ul>

            <button type="submit" class="button button-primary" name="updateListingType"><?php esc_html_e('Update', 'apus-listdo'); ?></button>

            <div class="input-field-types">
                <h3><?php esc_html_e('Create a custom field', 'apus-listdo'); ?></h3>
                <div class="input-field-types-wrapper">
                    <select name="field-types" class="apuslistdo-field-types">
                        <?php foreach ($default_fields as $group) { ?>
                            <optgroup label="<?php echo esc_attr($group['title']); ?>">
                                <?php foreach ($group['fields'] as $value => $label) { ?>
                                    <option value="<?php echo esc_attr($value); ?>"><?php echo $label; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                    <button type="button" class="button btn-add-field" data-randid="<?php echo esc_attr($rand_id); ?>"><?php esc_html_e('Create', 'apus-listdo'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="apuslistdo-form-field-list apuslistdo-list">
        <h3 class="title"><?php esc_html_e('Available Fields', 'apus-listdo'); ?></h3>
        <?php if ( !empty($available_fields) ) { ?>
            <ul>
                <?php foreach ($available_fields as $key => $field) { ?>
                    <li class="<?php echo esc_attr($key); ?> <?php echo esc_attr(in_array($key, $disabled_fields) ? 'disabled' : ''); ?>">
                        <a class="apuslistdo-custom-field-add-available-field" data-fieldtype="<?php echo esc_attr($key); ?>" data-randid="<?php echo esc_attr($rand_id); ?>" href="javascript:void(0);" data-fieldlabel="<?php echo esc_attr($field['label']); ?>">
                            <span class="icon-wrapper">
                                <i class="fas fa-plus"></i>
                            </span>
                            <?php echo esc_html($field['label']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <div class="clearfix" style="clear: both;"></div>
</div>

<script>
    var global_custom_field_counter = <?php echo intval($all_fields_name_count); ?>;
    jQuery(document).ready(function ($) {
        $('#foo<?php echo esc_attr($rand_id); ?>').sortable({
            group: "words",
            animation: 150,
            handle: ".field-intro",
            cancel: ".form-group-wrapper"
        });
    });
</script>