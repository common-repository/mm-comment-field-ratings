<?php

add_action('cmb2_render_radio_image', 'cmb2_radio_image_callback', 10, 5);
function cmb2_radio_image_callback($field, $escaped_value, $object_id, $object_type, $field_type_object) {
    echo $field_type_object->radio();
}
add_filter('cmb2_list_input_attributes', 'cmb2_radio_image_attributes', 10, 4);
function cmb2_radio_image_attributes($args, $defaults, $field, $cmb) {
    if ($field->args['type'] == 'radio_image' && isset($field->args['images'])) {
        foreach ($field->args['images'] as $field_id => $image) {
            if ($field_id == $args['value']) {
                $image = trailingslashit($field->args['images_path']) . $image;
                $args['label'] = '<img src="' . $image . '" alt="' . $args['value'] . '" title="' . $args['label'] . '" />';
            }
        }
    }
    return $args;
}
//add_action('admin_head', 'cmb2_radio_image');
//function cmb2_radio_image() {
//
//}