<div class="post-header">
    <h2 class="post-title entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"
                                          title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

    <?php if (is_sticky()) { ?> <span class="sticky-post"><?php _e('Sticky post', 'baskerville'); ?></span> <?php } ?>
    <?php

    echo get_location();
    if (get_overall_restaurant_ratings(get_the_ID()) != false) {
        echo "<br/>Overall Score: <span itemprop='ratingValue'>" . get_overall_restaurant_ratings(get_the_ID()) . "</span>";
    } ?></div> <!-- /post-header -->

<?php if (has_post_thumbnail()) : ?>

    <div class="featured-media">

        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">

            <?php the_post_thumbnail('post-thumbnail'); ?>

        </a>

    </div> <!-- /featured-media -->

<?php endif; ?>

<div class="post-excerpt">

    <?php the_excerpt('100'); ?>

</div> <!-- /post-excerpt -->

<div class="post-meta">
 <span class="vcard author">

        <span class="fn"><a href="#">Posted by <?php the_author_meta('display_name'); ?></a></span>
    </span>
    <a class="post-date date updated" href="<?php the_permalink(); ?>"
       title="<?php the_title(); ?>"><?php the_time('F d, Y'); ?></a>



    <?php

    if (function_exists('zilla_likes')) zilla_likes();

    if (comments_open()) {
        comments_popup_link('0', '1', '%', 'post-comments');
    }

    edit_post_link();

    ?>

    <div class="clear"></div>

</div>

<div class="clear"></div>