<?php
get_header();

$term = get_queried_object(); // Get the current category term

// Initialize variables to store filter values
$selected_color = '';

// Check if form has been submitted
if (isset($_GET['filter_submitted'])) {
    // Retrieve and sanitize filter values
    $selected_color = sanitize_text_field($_GET['color']);
}

// Define filter arguments based on user input
$filter_args = array(
    'post_type' => 'custom-product',
    'tax_query' => array(
        array(
            'taxonomy' => 'product-category',
            'field' => 'id',
            'terms' => $term->term_id,
        ),
    ),
);

// Add color filter if selected
if (!empty($selected_color)) {
    $filter_args['meta_query'][] = array(
        'key' => '_custom_product_color',
        'value' => $selected_color,
        'compare' => '=', // Use '=' to match the selected color
    );
}

$custom_product_query = new WP_Query($filter_args);

?>

<h1><?php echo "Category Name - ".$term->name; ?></h1>

<!-- Filter Form -->
<form method="GET">
    <!-- Add a hidden field to indicate form submission -->
    <input type="hidden" name="filter_submitted" value="1">

    <!-- Color Filter -->
    <label for="color">Filter by Color:</label>
    <select name="color" id="color">
        <option value="">All Colors</option>
        <?php
        // Generate color options dynamically
        foreach ($custom_product_colors as $color_key => $color_label) {
            $selected = ($selected_color === $color_key) ? 'selected' : '';
            echo '<option value="' . esc_attr($color_key) . '" ' . $selected . '>' . esc_html($color_label) . '</option>';
        }
        ?>
    </select>

    <button type="submit">Apply Filters</button>
</form>

<?php
if ($custom_product_query->have_posts()) :
    while ($custom_product_query->have_posts()) :
        $custom_product_query->the_post();
        // Get product details
        $product_title = get_the_title();
        $product_link = get_permalink(); 

        ?>
        <div class="product-entry">
            <h2>* <a href="<?php echo $product_link; ?>"><?php echo $product_title; ?></a></h2>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
else :
    echo 'No products found in this category.';
endif;

?>
