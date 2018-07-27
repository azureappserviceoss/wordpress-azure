<?php
/**
 * The loop that displays a single post.
 *
 * @package mahjong-org.azurewebsites.net
 * @subpackage mahjong
 * @since Mahjong-ca 1.0
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2 class="article_tt font_yahei"><?php the_title(); ?></h2>

					<div class="sub_opt">
                      <span><?php _e('类别'); ?>: <?php the_category(', ') ?></span>
					  <span>时间：<?php the_time(get_settings('date_format')); ?></span>
                      <span><?php the_tags('标签：', ' , ' , ''); ?></span>
					</div><!-- .sub_opt -->

					<div class="article_text">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( __( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>

					<div class="entry-utility">
						<?php edit_post_link( __( '[编辑文章]', 'mahjong' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-utility -->
                    
                    <!-- share Button BEGIN -->
<div id="sharebox" class="s_tools">
<span class="share_text">分享到：</span>

<a class="s_51" title="分享到 51 微博" href="javascript:void(0)" onclick="var d=document,w=window,f='http://t.51.ca',l=d.location,e=encodeURIComponent,p=''+e(l.href)+'&t='+e(d.title);if(!w.open(f+'/index.php?mod=share&code=share&url='+p)){l.href=f+'.new'+p;}"></a>

<a class="s_tsina" title="分享到新浪微博" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.sina.com.cn/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=',e(d.title),'&appkey=2924220432'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"></a>

<a class="s_fbook" href="javascript:window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(document.location.href)+'&amp;t='+encodeURIComponent(document.title));void(0)"></a>

<a class="s_twi" href="javascript:window.open('http://twitter.com/home?status='+encodeURIComponent(document.location.href)+' '+encodeURIComponent(document.title));void(0)"></a>

<a class="s_tqq" href="javascript:void(0)" onclick="postToWb();" class="tmblog"></a>
<script type="text/javascript">  
function postToWb(){  
var _t = encodeURI(document.title);//'${(activity.intro)!}'这是取得Action穿过来的值，如果想取当前标题改为document.title  
var _url = encodeURI(document.location);  
var _appkey = encodeURI("appkey");//你从腾讯获得的appkey  
var _pic = encodeURI('');//（列如：var _pic='图片url1|图片url2|图片url3....）  
var _site = '';//你的网站地址  
var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic;  
window.open( _u,'转播到腾讯微博', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );  
}  
</script>

<a class="s_qzone" title="分享到QQ空间" href="javascript:(function(){window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+ encodeURIComponent(location.href)+ '&title='+encodeURIComponent(document.title),'_blank');})()"></a>
</div>
<!-- share Button END -->
				</div><!-- #post-## -->


				<?php //去除评论功能 comments_template( '', ture ); ?>
                
<ul id="tags_related" class="clearfix">
<h3>相关文章</h3>
<?php
global $post;
$post_tags = wp_get_post_tags($post->ID);
if ($post_tags) {
  foreach ($post_tags as $tag) {
    // 获取标签列表
    $tag_list[] .= $tag->term_id;
  }

  // 随机获取标签列表中的一个标签
  $post_tag = $tag_list[ mt_rand(0, count($tag_list) - 1) ];

  // 该方法使用 query_posts() 函数来调用相关文章，以下是参数列表
  $args = array(
        'tag__in' => array($post_tag),
        'category__not_in' => array(NULL),  // 不包括的分类ID
        'post__not_in' => array($post->ID),
        'showposts' => 6,                           // 显示相关文章数量
        'caller_get_posts' => 1
    );
  query_posts($args);

  if (have_posts()) {
    while (have_posts()) {
      the_post(); update_post_caches($posts); ?>
    <li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php
    }
  }
  else {
    echo '<li class="center">* 暂无相关文章</li>';
  }
  wp_reset_query(); 
}
else {
  echo '<li class="center">* 暂无相关文章</li>';
}
?>
</ul><!-- #tags_related 相关文章 -->
                

<?php endwhile; // end of the loop. ?>
