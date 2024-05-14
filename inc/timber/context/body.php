<?php

function add_custom_body_classes($classes)
{
    // Define the custom classes you want to add
    $custom_classes = array(
        'antialiased', 'tracking-tight', 'font-inter', 'text-base-100',
    );

    // Merge the custom classes with the existing classes array
    $classes = array_merge($classes, $custom_classes);

    return $classes; // Return the modified array of classes
}

add_filter('body_class', 'add_custom_body_classes');

// add_filter('timber/context', function ($context) {
//     // Assuming get_body_class() returns an array of body classes
//     // This will now set $context['body'] to a string of body classes
//     $context['body_class'] = add_custom_body_classes(get_body_class());
//     return $context;
// });
