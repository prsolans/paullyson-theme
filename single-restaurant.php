<?php

get_header();

$format = get_post_format();

$terms = get_the_terms($post->ID, 'location');

if (!empty($terms)) {
    foreach ($terms AS $term) {
        if ($term->parent == 0) {
            $location = $term->name;
        }
    }
}

$foursquareInfo = get_foursquare_data(get_the_title(), $location);
$yelpInfo = get_yelp_data(get_the_title(), $location);

$fsRating = 0;
$fsCount = 0;
$yelpRating = 0;
$yelpCount = 0;
$ourRating = 0;

?>

    <div class="wrapper section medium-padding">

        <div class="section-inner">

            <div class="content fleft">

                <?php if (have_posts()) : while (have_posts()) :
                the_post(); ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="post-header">

                        <div itemscope itemtype="http://schema.org/Restaurant" style="margin-bottom: 5px;">

                            <h2 class="post-title entry-title" itemprop="name"><a href="<?php the_permalink(); ?>"
                                                                                  rel="bookmark"
                                                                                  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

                                <span style="display: none;" itemprop="worstRating">0</span>
                                <span style="display: none;" itemprop='bestRating'>10</span>
                                <?php

                                if (get_overall_restaurant_ratings(get_the_ID()) != false) {

                                    echo "<div class='one-half''>";
                                    $ratings = get_overall_restaurant_ratings(get_the_ID());
                                    echo "<div class='paullyson-score " . $ratings['class'] . " fl' itemprop='ratingValue'>" . $ratings['overallScore'] . "</div><div class='fl'><b>Paullyson Score</b><br/>based on " . $ratings['count'] . " ratings.</div>";
                                    echo "</div>";

                                    $ourRating = $ratings['overallScore'];
                                }

                                ?>
                                <div class="one-half">
                                <?php
                                $weightedScore = get_restaurant_metascore($ourRating, $foursquareInfo['rating'], $foursquareInfo['ratingSignals'], $yelpInfo['rating'], $yelpInfo['review_count']);
                                echo "<div class='metascore-button " . $weightedScore['class'] . " fl'>" . $weightedScore['score'] . "</div><div class='fl'><b>Meta Score</b><br/>based on " . $weightedScore['externalReviews'] . " reviews.</div>";

                                ?></span>

                        </div>
                        <div class="clearfix">&nbsp;</div>
                                <hr/>


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
                            echo "<img class='fs-image' src='" . $foursquareInfo['image0'] . "'/>";
                            echo "<img class='fs-image' src='" . $foursquareInfo['image1'] . "'/>";
                            echo "<img class='fs-image' src='" . $foursquareInfo['image2'] . "'/>";

                            echo '<div class="clear"></div><div>';

                            if (isset($foursquareInfo['streetAddress0'])) {
                                echo "<span itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'>";
                                echo "<span itemprop='streetAddress'>";
                                echo $foursquareInfo['streetAddress0'];
                                echo "</span></span>";
                            }
                            if (isset($foursquareInfo['streetAddress1'])) {
                                echo "<br/>" . $foursquareInfo['streetAddress1'];
                            }
                            if (isset($foursquareInfo['url'])) {
                                echo "<br/><a href='" . $foursquareInfo['url'] . "' target='_blank' itemprop='url'>Website</a>";
                            }
                            if (isset($foursquareInfo['reservations'])) {
                                echo "<br/><a href='" . $foursquareInfo['reservations'] . "' target='_blank' itemprop='acceptsReservations'>Reservations</a>";
                            }

                            echo "</div>";

                            // END Restaurant Info

                            if (isset($foursquareInfo['lat'])):
                                ?>
                                <div class="acf-map">
                                    <div class="marker" data-lat="<?php echo $foursquareInfo['lat']; ?>"
                                         data-lng="<?php echo $foursquareInfo['lng']; ?>"><?php the_title(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php

                            if (isset($foursquareInfo['rating'])) {
                                $fsRating = $foursquareInfo['rating'];
                                $fsCount = $foursquareInfo['ratingSignals'];
                                echo "<h4>Additional online ratings:</h4>";
                                echo "<br/>Foursquare: " . $fsRating . " (" . $fsCount . " ratings)";
                            }
                            if (isset($yelpInfo['rating'])) {
                                $yelpRating = $yelpInfo['rating'];
                                $yelpCount = $yelpInfo['review_count'];
                                echo "<br/>Yelp: " . $yelpRating * 2 . " (" . $yelpCount . " ratings)";
                            }
                            if (isset($ratings['overallScore'])) {
                                $ourRating = $ratings['overallScore'];
                                echo "<br/>Our Score:" . $ratings['overallScore'];
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