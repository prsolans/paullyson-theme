<?php
/*
Template Name: Restaurants (All)
*/

/**
 * Created by PhpStorm.
 * User: prsolans
 * Date: 12/11/14
 * Time: 4:48 PM
 */
?>

<?php get_header(); ?>

    <div class="wrapper section medium-padding">

        <div class="content section-inner" style="width: 85%">

            <?php
            display_page_title();
            display_page_block_copy();
            ?>
            <div class='two-thirds-left'>

                <?php
                display_ratings_table('restaurant');
                ?>

                <?php if(get_the_title() == 'Food'): ?>
                <p>NOTE: While an establishment can be rated as both a Bar and Restaurant, the tables depict only
                    ratings
                    for that specific category, and as such, ratings for the same place may vary by category. Full
                    ratings
                    are available on the individual page for that location.</p>
                <?php endif; ?>

            </div>
            <?php display_rating_sidebar('restaurant'); ?>


            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div>
    <!-- /wrapper -->

<?php get_footer(); ?>