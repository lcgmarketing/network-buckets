<?php
/*
Template Name: Homepage :: Full Width
*/
?>
<?php get_header(); ?>

<?php truethemes_before_main_hook();// action hook, see truethemes_framework/global/hooks.php ?>

<div class="main-area home-main-area">
    <div class="main-holder home-holder">
        <div class="content_full_width">
	        <?php 
				if(have_posts()) : 
					while(have_posts()) : the_post(); 
						the_content(); 
						truethemes_link_pages(); 
					endwhile; 
				endif; 
			?>
        </div><!-- end content -->
    </div><!-- end main-holder -->
</div><!-- main-area -->

<?php get_footer(); ?>