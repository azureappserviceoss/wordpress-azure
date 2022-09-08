<?php
/**
 * Template Name: front-page
 */
?>
<?php
get_header();
?>
 <main>
    <div>
        <div class="bg"></div>
        <div class="logo"><?php the_post_thumbnail(); ?></div>
        <?php 
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
            endwhile;
        endif;
        ?>

</div>
</main>