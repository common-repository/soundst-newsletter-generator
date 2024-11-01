<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo get_option('ng_head_html');?>
</head>
<body>
<?php
$args = array (
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => '5'
);
$dated_posts = new WP_Query( $args );
?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tbody>
	<tr>
		<td align="center">
			<?php 
			$mbg = get_option('ng_mbg');
			$bgc = get_option('ng_bgc');
			$bgimg_url = get_option('ng_bgimg_url');
			
			//main blocks
			$body_up = get_option('ng_body_up_html');
			$header_html = get_option('ng_header_html');
			$left_s = get_option('ng_left_s_html'); 
			$above_posts = ' Text/HTML to appear above posts (optional)';
			$post_layout = get_option('ng_layout_html');
			$below_posts = ' Text/HTML to appear below posts (optional)';
			$right_s = get_option('ng_right_s_html');
			$footer_html = get_option('ng_footer_html');
			$body_low = get_option('ng_body_low_html');
					
			//tags to change
			$link_color = get_option('ng_link_color');
			
			$h1_font_family = get_option('ng_h1_font_family');
			$h1_font_size = get_option('ng_h1_font_size');
			$h1_color = get_option('ng_h1_text_color');
			$h1_link_color = get_option('ng_h1_link_color');

			$h2_font_family = get_option('ng_h2_font_family');
			$h2_font_size = get_option('ng_h2_font_size');
			$h2_color = get_option('ng_h2_text_color');
			$h2_link_color = get_option('ng_h2_link_color');
			
			$h3_font_family = get_option('ng_h3_font_family');
			$h3_font_size = get_option('ng_h3_font_size');
			$h3_color = get_option('ng_h3_text_color');
			$h3_link_color = get_option('ng_h3_link_color');
			
			function add_link_color($subject, $parent_tag, $color) {
				preg_match_all('/<' . $parent_tag . '.*>(?P<inner_html>.*)<\/' . $parent_tag . '>/siU', $subject, $matches);
			
				foreach ($matches['inner_html'] as $html) {
					if (!empty($color)) {
					$updated_html = preg_replace('/(<a\s.*)style.*>/siU', '$1 style="color:'. $color .';">', $html);
			
					$subject = preg_replace('%' . $html . '%', $updated_html, $subject);
					}
				}
				return $subject;
			}
			
			// changing tags
			$body_up = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $body_up);
			$body_up = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $body_up);
			$body_up = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $body_up);
			$body_up = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $body_up);
			$body_up = add_link_color($body_up, 'h1', $h1_link_color);
			$body_up = add_link_color($body_up, 'h2', $h2_link_color);
			$body_up = add_link_color($body_up, 'h3', $h3_link_color);
			
			$header_html = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $header_html);
			$header_html = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $header_html);						
			$header_html = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $header_html);
			$header_html = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $header_html);
			$header_html = add_link_color($header_html, 'h1', $h1_link_color);
			$header_html = add_link_color($header_html, 'h2', $h2_link_color);
			$header_html = add_link_color($header_html, 'h3', $h3_link_color);
			
			$left_s = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $left_s);
			$left_s = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $left_s);			
			$left_s = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $left_s);
			$left_s = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $left_s);
			$left_s = add_link_color($left_s, 'h1', $h1_link_color);
			$left_s = add_link_color($left_s, 'h2', $h2_link_color);
			$left_s = add_link_color($left_s, 'h3', $h3_link_color);
			
			$above_posts = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $above_posts);
			$above_posts = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $above_posts);
			$above_posts = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $above_posts);
			$above_posts = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $above_posts);
			$above_posts = add_link_color($above_posts, 'h1', $h1_link_color);
			$above_posts = add_link_color($above_posts, 'h2', $h2_link_color);
			$above_posts = add_link_color($above_posts, 'h3', $h3_link_color);
						
			$below_posts = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $below_posts);
			$below_posts = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $below_posts);
			$below_posts = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $below_posts);
			$below_posts = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $below_posts);
			$below_posts = add_link_color($below_posts, 'h1', $h1_link_color);
			$below_posts = add_link_color($below_posts, 'h2', $h2_link_color);
			$below_posts = add_link_color($below_posts, 'h3', $h3_link_color);
			
			$right_s = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $right_s);
			$right_s = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $right_s);			
			$right_s = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $right_s);
			$right_s = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $right_s);						
			$right_s = add_link_color($right_s, 'h1', $h1_link_color);
			$right_s = add_link_color($right_s, 'h2', $h2_link_color);
			$right_s = add_link_color($right_s, 'h3', $h3_link_color);
			
			$footer_html = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $footer_html);
			$footer_html = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $footer_html);			
			$footer_html = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $footer_html);
			$footer_html = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $footer_html);
			$footer_html = add_link_color($footer_html, 'h1', $h1_link_color);
			$footer_html = add_link_color($footer_html, 'h2', $h2_link_color);
			$footer_html = add_link_color($footer_html, 'h3', $h3_link_color);
			
			$body_low = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $body_low);
			$body_low = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $body_low);			
			$body_low = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $body_low);
			$body_low = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $body_low);			
			$body_low = add_link_color($body_low, 'h1', $h1_link_color);
			$body_low = add_link_color($body_low, 'h2', $h2_link_color);
			$body_low = add_link_color($body_low, 'h3', $h3_link_color);
			?>
			<table border="0" style="background:<?php if($mbg == '0') {echo $bgc;} else {?>url('<?php echo $bgimg_url;?>')<?php }?>; font-family:<?php echo get_option('ng_font_family');?>; font-size:<?php echo get_option('ng_font_size')?>px; color:<?php echo get_option('ng_text_color');?>" width="" cellpadding="<?php echo get_option('ng_pbmb')?>" cellspacing="0" >
				<tr>
					<?php $header_type = get_option('ng_header_type');
					$header_img_url = get_option('ng_header_img_url')?>
					<td colspan="3"><div id="header"><?php if ($header_type == "0"){?>
							<img src="<?php echo $header_img_url;?>" alt="" style="width:100%; height:auto;"/> <?php } 
							else {echo $header_html;}?>
						</div>
					</td>
				</tr>
				<tr>
					<?php $ls_width = get_option('ng_ls_width');?>
					<td valign="top" <?php if (!empty($ls_width)) {?> width="<?php echo $ls_width?>px"<?php } else {?>style="display:none"<?php }?>>
					<?php echo $left_s;?>
					</td>
					<?php $content_width = get_option('ng_content_width');?>
					<td valign="top" <?php if (!empty($content_width)) {?> width="<?php echo $content_width?>px"<?php }?>>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td colspan="3" <?php if (empty($body_up)) {?>style="display:none"<?php }?>><?php echo $body_up;?></td>
								</tr>
								<tr>
								<td><?php								
								if ($dated_posts->have_posts()):
								
								$post_ff = get_option('ng_post_font');
								$post_fs = get_option('ng_post_size');
								$post_fc = get_option('ng_post_color');
								$thumb_size =  get_option('ng_thumb_width');
							
								//customizing excerpt and read more text								
								function ng_excerpt_more($more) {
									return '';
								}
								
								 
								// standard tags
								$post_layout = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'. $link_color .';">', $post_layout);
								$post_layout = preg_replace('/(<h1\b[^><]*)>/i', '$1 style="color:'. $h1_color .';font-size:'. $h1_font_size .'px;font-family:'. $h1_font_family .';margin: 0;">', $post_layout);
								$post_layout = preg_replace('/(<h2\b[^><]*)>/i', '$1 style="color:'. $h2_color .';font-size:'. $h2_font_size .'px;font-family:'. $h2_font_family .';margin: 0;">', $post_layout);
								$post_layout = preg_replace('/(<h3\b[^><]*)>/i', '$1 style="color:'. $h3_color .';font-size:'. $h3_font_size .'px;font-family:'. $h3_font_family .';margin: 0;">', $post_layout);
								$post_layout = add_link_color($post_layout, 'h1', $h1_link_color);
								$post_layout = add_link_color($post_layout, 'h2', $h2_link_color);
								$post_layout = add_link_color($post_layout, 'h3', $h3_link_color);
								
								add_filter('excerpt_more', 'ng_excerpt_more');?>
								
									<div class="posts" style="font-family:<?php echo $post_ff;?>;font-size:<?php echo $post_fs;?>px;color:<?php echo $post_fc;?>;">
									<?php while ($dated_posts->have_posts()):$dated_posts->the_post();	?>
									<div class="post" style="display:inline-block;width:100%; padding-bottom:<?php echo get_option('ng_posts_padding')?>px;">
										<?php 																					 
										//parsing tags
										$tmp_post_layout = $post_layout;
										$tmp_post_layout = str_replace('<title>', '<div style ="font-size: ' . get_option('ng_title_size') .'px; color:' . get_option('ng_title_color') .'">'. get_the_title() .'</div>', $tmp_post_layout);
										$tmp_post_layout = str_replace('<date>', '<span style ="font-size: ' . get_option('ng_date_size') .'px; color:' . get_option('ng_date_color') .'">'. get_the_date() .'&nbsp</span>', $tmp_post_layout);
										$tmp_post_layout = str_replace('<author>', '<span style ="font-size: ' . get_option('ng_author_size') .'px; color:' . get_option('ng_author_color') .'">'. get_the_author() .'&nbsp</span>', $tmp_post_layout);
										
										if(has_post_thumbnail()) {
											$tmp_post_layout = str_replace('<thumbnail>', '<div style ="width: ' . get_option('ng_thumb_width') .'px; float:' . (get_option('ng_thumb_align') == '0' ? 'left' : 'right') .';padding:' . (get_option('ng_thumb_align') == '0' ? '0 ' . get_option('ng_thumb_pad'). 'px ' . get_option('ng_thumb_pad') .'px 0' : '0 0' . get_option('ng_thumb_pad'). 'px ' . get_option('ng_thumb_pad') .'px') .'"><a href="'.get_permalink() .'" style="outline: 0;">'. get_the_post_thumbnail(get_the_ID(),array($thumb_size,10000)) .'</a></div>', $tmp_post_layout);
										} else {
											$tmp_post_layout = str_replace('<thumbnail>','', $tmp_post_layout);
										}
										$tmp_readmore = str_replace('<readmore>', '&nbsp<a href="'.get_permalink() .'" style ="font-size: ' . get_option('ng_rm_size') .'px; color:' . get_option('ng_rm_color') .'">' . get_option('ng_rm_text') .'</a>', $tmp_post_layout);
										$tmp_post_layout = str_replace('<excerpt>', '<div style ="font-family: ' . get_option('ng_post_font') .'; font-size: ' . get_option('ng_post_size') .'px; color:' . get_option('ng_post_color') .'">'. get_the_excerpt(), $tmp_readmore .'</div>', $tmp_post_layout);									
										echo $tmp_post_layout;?>				
										</div>
									<? endwhile;?>
									</div>
								<?php endif;
								remove_filter('excerpt_more', 'ng_excerpt_more');
								?></td>
								</tr>
								<tr>
									<td colspan="3" <?php if (empty($body_low)) {?>style="display:none"<?php }?>><?php echo $body_low;?></td>
								</tr>
							</tbody>							
						</table>
					</td>
					<?php $rs_width = get_option('ng_rs_width');?>
					<td valign="top" <?php if (!empty($rs_width)) {?> width="<?php echo $rs_width?>px"<?php } else {?>style="display:none"<?php }?>><?php echo $right_s;?></td>
				</tr>
				<tr>
					<?php $footer_type = get_option('ng_footer_type');
					$footer_img_url = get_option('ng_footer_img_url')?>
					<td colspan="3"><div id="footer"><?php if ($footer_type == "0"){?>
							<img src="<?php echo $footer_img_url;?>" alt="" style="width:100%; height:auto;"/> <?php } 
							else {echo $footer_html;}?>
						</div>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
</tbody>
</table>
</body>
</html>