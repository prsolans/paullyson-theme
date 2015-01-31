<?php

get_header();

$format = get_post_format();

?>

    <div class="wrapper section medium-padding">

        <div class="section-inner">

            <div class="content fleft">

                <?php if (have_posts()) : while (have_posts()) :
                the_post(); ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="post-header">

                        <div itemscope itemtype="http://schema.org/Restaurant">

                            <h2 class="post-title entry-title" itemprop="name"><a href="<?php the_permalink(); ?>"
                                                                                  rel="bookmark"
                                                                                  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <h3>

                            <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

                                <span style="display: none;" itemprop="worstRating">0</span>
                                <?php
                                if (get_overall_restaurant_ratings(get_the_ID()) != false) {
                                    $ratings = get_overall_restaurant_ratings(get_the_ID());
                                    echo "Overall Score: <span itemprop='ratingValue'>" . $ratings['overallScore'] . "</span>/<span itemprop='bestRating'>10</span>";
                                    echo "<br/>Ratings: <span itemprop='ratingCount'>" . $ratings['count'] . "</span>";
                                }
                                ?></span>
                                <hr/>

                            </h3>

                            <!-- /post-header -->

                            <?php
                            if (has_post_thumbnail()) : ?>

                                <div class="featured-media">

                                    <?php the_post_thumbnail('post-image'); ?>

                                    <?php if (!empty(get_post(get_post_thumbnail_id())->post_excerpt)) : ?>

                                        <div class="media-caption-container">

                                            <p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>

                                        </div>

                                    <?php endif; ?>

                                </div> <!-- /featured-media -->

                            <?php endif; ?>

                            <?php

                            display_restaurant_ratings_by_author('prs');
                            display_restaurant_ratings_by_author('allykc');

                            ?>

                            <div class="post-content">

                            </div>

                            <div class="clear"></div>

                            <?php the_content(); ?>
                        </div>

                        <div class="location-info-block">

                            <?php
                            // START Restaurant Info

                            $terms = get_the_terms($post->ID, 'location');
                            debug_to_console($terms);
                            if (!empty($terms)) {
                                foreach ($terms AS $term) {
                                    if ($term->parent == 0) {
                                        $location = $term->name;
                                    }
                                }
                            }

                            $venueInfo = get_foursquare_data(get_the_title(), $location);

                            get_yelp_data(get_the_title(), $location);

                            echo '<div class="clear"></div><div>';

                            if (isset($venueInfo['streetAddress0'])) {
                                echo "<span itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'>";
                                echo "<span itemprop='streetAddress'>";
                                echo $venueInfo['streetAddress0'];
                                echo "</span></span>";
                            }
                            if (isset($venueInfo['streetAddress1'])) {
                                echo "<br/>" . $venueInfo['streetAddress1'];
                            }
                            if (isset($venueInfo['url'])) {
                                echo "<br/><a href='" . $venueInfo['url'] . "' target='_blank' itemprop='url'>Website</a>";
                            }
                            if (isset($venueInfo['reservations'])) {
                                echo "<br/><a href='" . $venueInfo['reservations'] . "' target='_blank' itemprop='acceptsReservations'>Reservations</a>";
                            }

                            echo "</div>";

                            // END Restaurant Info

                            if (isset($venueInfo['lat'])):
                                ?>
                                <div class="acf-map">
                                    <div class="marker" data-lat="<?php echo $venueInfo['lat']; ?>"
                                         data-lng="<?php echo $venueInfo['lng']; ?>"><?php the_title(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <?php
                            if (isset($venueInfo['rating'])) {
                                echo "<h4>Additional online ratings:</h4>";
                                echo "<br/>Foursquare: " . $venueInfo['rating'] . " (" . $venueInfo['ratingSignals'] . " ratings)";
                            }
                            ?>

                        </div>

                        <?php wp_link_pages(); ?>

                        <div class="clear"></div>

                    </div>
                    <!-- /post-content -->

                    <div class="post-meta-container">

                        <div class="post-author">

                            <div class="post-author-content vcard author">

                                <h4 class="fn"><?php the_author_meta('display_name'); ?></h4>

                                <p><?php the_author_meta('description'); ?></p>

                                <?php
                                if (isset($_GET['author_name'])) :
                                    $curauth = get_userdatabylogin($author_name);
                                else :
                                    $curauth = get_userdata(intval($author));
                                endif;
                                ?>

                                <div class="author-links">

                                    <a class="author-link-posts" title="<?php _e('Author archive', 'baskerville'); ?>"
                                       href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php _e('Author archive', 'baskerville'); ?></a>

                                    <?php $author_url = get_the_author_meta('user_url');

                                    $author_url = preg_replace('#^https?://#', '', rtrim($author_url, '/'));

                                    if (!empty($author_url)) : ?>

                                        <a class="author-link-website"
                                           title="<?php _e('Author website', 'baskerville'); ?>"
                                           href="<?php the_author_meta('user_url'); ?>"><?php _e('Author website', 'baskerville'); ?></a>

                                    <?php endif;

                                    $author_mail = get_the_author_meta('email');

                                    $show_mail = get_the_author_meta('showemail');

                                    if (!empty($author_mail) && ($show_mail == "yes")) : ?>

                                        <a class="author-link-mail" title="<?php echo $author_mail; ?>"
                                           href="mailto:<?php echo $author_mail ?>"><?php echo $author_mail; ?></a>

                                    <?php endif;

                                    $author_twitter = get_the_author_meta('twitter');

                                    if (!empty($author_twitter)) : ?>

                                        <a class="author-link-twitter"
                                           title="<?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter', 'baskerville'); ?>"
                                           href="http://www.twitter.com/<?php echo $author_twitter ?>"><?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter', 'baskerville'); ?></a>

                                    <?php endif; ?>

                                </div>
                                <!-- /author-links -->

                            </div>
                            <!-- /post-author-content -->

                        </div>
                        <!-- /post-author -->

                        <div class="post-meta">

                            <p class="post-date date updated"><?php the_time(get_option('date_format')); ?></p>

                            <?php if (function_exists('zilla_likes')) zilla_likes(); ?>

                            <p class="post-categories"><?php the_category(', '); ?></p>

                            <?php if (has_tag()) : ?>

                                <p class="post-tags"><?php the_tags('', ', '); ?></p>

                            <?php endif; ?>

                            <div class="clear"></div>

                            <div class="post-nav">

                                <?php
                                $prev_post = get_previous_post();
                                if (!empty($prev_post)): ?>

                                    <a class="post-nav-prev" title="<?php _e('Previous post:', 'baskerville');
                                    echo ' ' . esc_attr(get_the_title($prev_post)); ?>"
                                       href="<?php echo get_permalink($prev_post->ID); ?>"><?php _e('Previous post', 'baskerville'); ?></a>

                                <?php endif; ?>

                                <?php
                                $next_post = get_next_post();
                                if (!empty($next_post)): ?>

                                    <a class="post-nav-next" title="<?php _e('Next post:', 'baskerville');
                                    echo ' ' . esc_attr(get_the_title($next_post)); ?>"
                                       href="<?php echo get_permalink($next_post->ID); ?>"><?php _e('Next post', 'baskerville'); ?></a>

                                <?php endif; ?>

                                <?php edit_post_link(__('Edit post', 'baskerville')); ?>

                                <div class="clear"></div>

                            </div>

                        </div>
                        <!-- /post-meta -->

                        <div class="clear"></div>

                    </div>
                    <!-- /post-meta-container -->

                    <?php comments_template('', true); ?>

                    <?php endwhile; else: ?>

                        <p><?php _e("We couldn't find any posts that matched your query. Please try again.", "baskerville"); ?></p>

                    <?php
                    endif; ?>

                </div>
                <!-- /post -->

            </div>
            <!-- /content -->

            <?php get_sidebar(); ?>

            <div class="clear"></div>

        </div>
        <!-- /section-inner -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>