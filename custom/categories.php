<?php
/*
Template Name: Category-page
*/
get_header();
?>

<h1>Product Categories</h1>

<?php
// Get all terms (categories) for the custom taxonomy 'product-category'
$categories = get_terms(
    array(
        'taxonomy' => 'product-category', // Replace 'product-category' with your taxonomy name
        'hide_empty' => false, // Set to false to include empty categories
    )
);

if ($categories && !is_wp_error($categories)) {
    echo '<ul>';
    foreach ($categories as $category) {
        echo '<li> * <a href="' . get_term_link($category->term_id) . '">' . $category->name . '</a></li>';
    }
    echo '</ul>';
}
?>

