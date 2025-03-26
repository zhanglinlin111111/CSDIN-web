<?php 
// default vars for later use
$display_year ='';
$ctl_story_lbl='';
$spy_ele = '';
$i = 0;
$row = 1;
$ctl_html_no_cont = '';
$story_styles='';

$active_design=$design;
$icons = ctl_set_default_value($attribute['icons'],'YES');

// checking timeline layout type and generating classes
if ($layout == "one-side") {
    $layout_cls = 'one-sided';
    $layout_wrp = 'one-sided-wrapper';
} elseif ($layout== "compact"){
             $layout_cls = 'compact';
            $layout_wrp = 'compact-wrapper';
            $compact_ele_pos=$attribute['compact-ele-pos'] ?$attribute['compact-ele-pos']:'main-date';
 }  else {
    $layout_cls = '';
    $layout_wrp = 'both-sided-wrapper';
}
  
// if load more enable just set current story number
 if($pagination=="ajax_load_more"){
      $i=$alternate+1;
    }

// Main Query 
$ctl_loop = new WP_Query(apply_filters( 'ctl_stories_query',$args));

if ($ctl_loop->have_posts()) {
    
    while ($ctl_loop->have_posts()) : $ctl_loop->the_post();
     global $post;
     $compact_year='';   $ctl_format_html = '';
     $slink_s='';
     $slink_e='';
     $post_id=get_the_ID();
     $story_format = get_post_meta($post_id, 'story_format', true);
     $img_cont_size = get_post_meta($post_id, 'img_cont_size', true);
     $ctl_story_date = get_post_meta($post_id, 'ctl_story_date', true);
     $ctl_story_date_lbl = get_post_meta($post_id, 'ctl_story_date_lbl', true);
     $custom_link = get_post_meta($post_id, 'story_custom_link', true);
     $posted_date=ctl_get_story_date($post_id,$date_formats);
     $container_cls=isset($img_cont_size)?$img_cont_size:"full";
   
     // generating dynamic style 
     $story_styles.=CooltimelineStyles::clt_v_story_styles($post_id);
   
    // creating dynamic read more link
    if($r_more=="yes"){
      if(isset($custom_link)&& !empty($custom_link)){
      $slink_s='<a target="_blank" title="'.esc_attr(get_the_title()).'" href="'.esc_url($custom_link).'">';
       $slink_e='</a>';
      }else{
      $slink_s='<a title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">';
       $slink_e='</a>';
          }
     }  

     // dynamic alternate class
        if ($i % 2 == 0) { 
            $even_odd = "even";
         } else {
            $even_odd = "odd";
        }
        
        // stories wrapper all classes
        $p_cls=array();
        $p_cls[]="timeline-post";
        $p_cls[]=esc_attr($even_odd);
        $p_cls[]=esc_attr($post_skin_cls);
        $p_cls[]=esc_attr($cls_icons);
        $p_cls[]='post-'.esc_attr($post->ID);
        if(isset($category_id)){
        $p_cls[]='story-cat-'.esc_attr($category_id);
         }
        $p_cls[]=$layout=="compact"?"timeline-mansory":'';
        $p_cls[]= esc_attr($design).'-meta';
        $p_cls=apply_filters('ctl_story_clasess',$p_cls);

         $category = get_the_terms( $post->ID, 'ctl-stories' );
          if(isset($category)&& is_array($category)){
         foreach ( $category as $cat){
            $category_id= $cat->term_id;
          }
        }
         $stop_ani='';
         if ($story_format == "video"){
         $stop_ani='stopanimation animated';
         }

        // loading content based upon layout type
         if($layout=="compact" &&  $based=="default"){
             require('content/story-compact-content.php');
         }else if($based=="custom"){
            require('content/story-custom-content.php');
         }else{
            require('content/story-default-content.php');
         }

        $i++;
        $post_content = '';

    endwhile;
    wp_reset_postdata();
    // adding styles if  ajax load more is enabled
    if($pagination=="ajax_load_more"){ 
    $ctl_html.='<style type="text/css">'.$story_styles.'</style>';
    }
  
} else {
    $ctl_html_no_cont .= '<div class="no-content"><h4>';
    //$ctl_html_no_cont.=$ctl_no_posts;
    $ctl_html_no_cont .= __('Sorry,You have not added any story yet', 'cool-timeline');
    $ctl_html_no_cont .= '</h4></div>';
}
$ctl_pagi='';

