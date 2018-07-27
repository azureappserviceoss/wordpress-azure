<?php

/**

 * The main template file.

 * @package mahjong-org.azurewebsites.net

 * @subpackage mahjong

 * @since Mahjong-ca 1.0

 */



get_header(); ?>



 <div class="bannerbox">

  <div class="wrap">

   <div class="p1"><img src="<?php bloginfo( 'template_url' ); ?>/images/banner1.jpg" /></div>

   <div class="p2"><img src="<?php bloginfo( 'template_url' ); ?>/images/banner1.jpg" /></div>

  </div>

 <!--bannerbox end-->

 </div>



 <div class="home_main clearfix">

  <div class="wrap">

    <div class="left_focus">

        <h2 class="ttbox">Pictures</h2>

        <div id="slider" class="nivoSlider">

          <?php

    $arr = array('meta_key' => '_thumbnail_id',

                'showposts' => 5,        // 显示5个特色图像

                'posts_per_page' => 5,   // 显示5个特色图像

                'orderby' => 'date',     // 按发布时间先后顺序获取特色图像，可选：'title'、'rand'、'comment_count'等

                'ignore_sticky_posts' => 1,

                'order' => 'DESC');



    $slideshow = new WP_Query($arr);

    if ($slideshow->have_posts()) {

        $postCount = 0;

        while ($slideshow->have_posts()) {

            $slideshow->the_post();

?>

        <a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>">

        <?php

            // 获取特色图像的地址

            $timthumb_src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'thumbnail' ); // thumbnail代表缩略图，这个值也可以是medium, large，full

            echo '<img border="0" alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . $timthumb_src[0] . '" /> ';

        ?>

        </a>

<?php

        } // endwhile

        wp_reset_postdata();

    } // endif

?>

        </div>

    <!--left_focus end-->

    </div>

    <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/jquery.nivo.slider.pack.js"></script>

	<script type="text/javascript">

		$(window).load(function() {

			$('#slider').nivoSlider();

		});

	</script>

    <div class="mid_news">

     <h2><span class="more"><a href="/en/category/news">More>></a></span> <span class="tt font_yahei">News</span></h2>

     <ul class="news_list">

     <?php $posts = get_posts( "category=1&numberposts=6" ); ?>

	 <?php if( $posts ) : ?>

         <?php

             $title_index = 0;

             foreach( $posts as $post ) : setup_postdata( $post );

         ?>

         <li>

             <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">

                 <span class="text">

				 <?php

                     the_title();

                 ?>

                 </span>

                 <?php

                     if ($title_index == 0) echo "<span class='icon_new'> New</span>";

                 ?>

             </a>



             <span class="time"><?php the_time('m-d') ?></span>

         </li>

	     <?php

                $title_index++;

            endforeach;

         ?>

	 <?php endif; ?>

     </ul>

    <!--mid_news end-->

    </div>

    <div class="rightbox">

     <div class="mahjong_info"><strong>CANADA MCR SPORTS ASSOCIATION</strong> is the official membership organization for Mahjong Players and Supporters in Canada. It has been formed to promote the study ... <a href="/about">more>></a></div>

     <div class="guide_btns">

      <ul>

       <!--<li class="li1"><a href="/en/about/">About</a></li>-->

       <li class="li1"><a href="http://www.mahjong-org.azurewebsites.net/game/index.php?lang=en" target="_blank" style="font-size: 16px;">Online Game</a></li>

       <li class="li2"><a href="/en/contact/">Contact</a></li>

       <li class="li3"><a href="/en/category/album/">Album</a></li>

       <li class="li4"><a href="/en/mjgz060510.pdf" target="_blank">Enroll</a></li>

      </ul>

     </div>

    <!--rightbox end-->

    </div>

  <!--wrap end-->

  </div>

 <!--home_main end-->

 </div>



<?php get_footer(); ?>

