<?php get_header(); ?>

<?php truethemes_before_main_hook();// action hook, see truethemes_framework/global/hooks.php ?>

<?php get_template_part('theme-template-part-tools','childtheme'); ?>

<div class="main-area">
        <div class="main-holder">
            <div id="content" class="content_full_width">
                <?php 
                    if(have_posts()) : 
                        while(have_posts()) : the_post(); 
                            the_content(); 
                            truethemes_link_pages(); 
                        endwhile; 
                    endif; ?>
            </div><!-- end content -->
        </div><!-- end main-holder -->
</div><!-- main-area -->

<?php get_footer(); ?>