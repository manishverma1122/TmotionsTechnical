<?php 

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        // Get product details
        $product_image = get_the_post_thumbnail();
        $product_title = get_the_title();
        $product_description = get_the_content();
        $product_price = get_post_meta(get_the_ID(), '_custom_product_price', true); // Use the correct meta key
        $product_color = get_post_meta(get_the_ID(), '_custom_product_color', true); // Use the correct meta key
        $product_categories = get_the_terms(get_the_ID(), 'product-category');

        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1>Product Information</h1>
                <?php if ($product_image) : ?>
                    <div class="product-image" style="width:500px !important;">
                        <?php echo $product_image; ?>
                    </div>
                <?php endif; ?>
                <h1 class="entry-title"><?php echo $product_title; ?></h1>
            </header>

            <div class="entry-content">
                <?php if ($product_description) : ?>
                    <div class="product-description">
                        <?php echo $product_description; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product_categories)) : ?>
                    <div class="product-categories">
                        <strong>Categories:</strong>
                        <?php
                        $category_names = array();
                        foreach ($product_categories as $category) {
                            $category_names[] = '<a href="' . esc_url(get_term_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo implode(', ', $category_names);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ($product_price) : ?>
                    <div class="product-price">
                        <strong>Price:</strong> <?php echo esc_html($product_price); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product_color)) : ?>
                    <div class="product-color">
                        <strong>Color:</strong>
                        <?php
                        if (is_array($product_color)) {
                            echo esc_html(implode(', ', $product_color));
                        } else {
                            echo esc_html($product_color);
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <?php
    endwhile;
endif;

