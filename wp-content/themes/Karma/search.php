<?php get_header(); ?>

<?php 
	truethemes_before_main_hook();// action hook, see truethemes_framework/global/hooks.php 

	$ka_results_title = get_option('ka_results_title');
	$ka_results_fallback = get_option('ka_results_fallback');
	$ka_404message = get_option('ka_404message');
	$ka_404sitemap = get_option('ka_404sitemap');
	$ka_searchbar = get_option('ka_searchbar');
	$ka_crumbs = get_option('ka_crumbs');
?>
<div class="main-area search-main-area">
<div class="tools">
<div class="holder">
<div class="frame">

<?php truethemes_before_article_title_hook();// action hook, see truethemes_framework/global/hooks.php ?>

<h1><?php echo $ka_results_title; ?></h1>
<?php if ($ka_searchbar == "true"){get_template_part('searchform','childtheme');} else {} ?>
<?php if ($ka_crumbs == "true"){ $bc = new simple_breadcrumb;} else {} ?>

<?php truethemes_after_searchform_hook();// action hook, see truethemes_framework/global/hooks.php ?>

</div><!-- end frame -->
</div><!-- end holder -->
</div><!-- end tools -->






<div class="main-holder">


<div id="content">
<h2 class="search-title">Search Results for "<?php the_search_query(); ?>"</h2><br />
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<ul class="search-list">
<li><strong><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></strong><br />
<?php
ob_start();
the_content();
$old_content = ob_get_clean();
$new_content = strip_tags($old_content);
echo substr($new_content,0,300).'...';
?>
</li>
</ul>

<?php endwhile; else: ?>
<?php echo $ka_results_fallback; ?>
<?php endif; ?>

<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
</div><!-- end content -->



<div id="sidebar" class="right_sidebar">
<?php dynamic_sidebar("Search Results Sidebar"); ?>
</div><!-- end sidebar -->
</div><!-- end main-holder -->
</div><!-- main-area -->



<?php get_footer(); ?>