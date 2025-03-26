<?php
if (!class_exists('CoolContentTimeline')) {

    class CoolContentTimeline
    {
        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions
            add_action('init', array($this, 'cool_ct_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'ctl_ct_ss'));
            add_filter( 'body_class', array($this, 'ctl_ct_body_class') );
         }

        public function cool_ct_shortcode()
        {
            add_shortcode('cool-content-timeline', array($this, 'cool_ct_view'));
        }
        public function ctl_ct_body_class( $c ) {
            global $post;
            if( isset($post->post_content) && has_shortcode( $post->post_content, 'cool-content-timeline' )  )
            {
                $c[] = 'cool-ct-page';
            }else if( isset($post->post_content) && has_shortcode( $post->post_content, 'cool-timeline' ) ) {
                $c[] = 'cool-timeline-page';
            }
            return $c;
        }

    
        public function cool_ct_view($atts, $content = null)
        {

            $design_cls = '';
            $attribute = shortcode_atts(array(
                'show-posts' => '',
                'order' => '',
                'post-type' => '',
                'category' => 0,
                'taxonomy' => '',
                'post-category' => '',
                'tags' => '',
                'layout' => 'default',
                'designs' => '',
                'items' => '',
                'skin' => '',
                'type' => '',
                'icons' => '',
                'animations' => '',
                'date-format'=>'',
                'story-content'=>'',
                'pagination' => 'default',
                'filters'=>'no',
                 'autoplay'=>'false',
                'start-on'=>0
            ), $atts);

           $layout=ctl_set_default_value($attribute['layout'],'default');
           $pagination=$attribute['pagination'];
           $type='';
              $tm_active_design='';
            if(isset($attribute['designs']))
              {
                $tm_active_design=$attribute['designs'];
              }else{
                 $tm_active_design='default';
              }
               //  Enqueue common required assets
           wp_enqueue_style('ctl_gfonts');
           wp_enqueue_style('ctl_default_fonts');
           wp_enqueue_script('ctl_prettyPhoto');
           wp_enqueue_style('ctl_pp_css');
           wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            clt_conditional_assets($layout,$type,$tm_active_design);

            if ( $layout == 'horizontal') {
                if ($tm_active_design) {
                    $design_cls = 'ht-' .$tm_active_design;
                    $design =$tm_active_design;
                } else {
                    $design_cls = 'ht-default';
                    $design = 'default';
                }
            } else if ( $layout == 'default' ||  $layout == 'one-side' ||  $layout=='compact') {
      
            if(($pagination=="ajax_load_more" || $attribute['filters']=="yes")){
               wp_localize_script( 'ctl-ajax-load-more', 'ct_load_more',
                 array( 'url' => admin_url( 'admin-ajax.php' ),'attribute'=>$attribute) );
               }

                if ($tm_active_design) {
                    $design_cls = 'main-' . $tm_active_design;
                    $design = $tm_active_design;
                } else {
                    $design_cls = 'main-default';
                    $design = 'default';
                }
            }
    
      $ctl_options_arr = get_option('cool_timeline_options');
      $active_design=$tm_active_design?$tm_active_design:'default';
      $wrp_cls = '';
      $wrapper_cls = '';
      $output = '';
      $last_year='';
      $ctl_html = '';
      $dates_li ='';
      $same_day_post='';
      $alternate=0;
      $ctl_title_text=ctl_set_default_value($ctl_options_arr['title_text'], 'Timeline');
       $ctl_html_no_cont = '';
        $args = array();
        $cat_timeline = array();
        $ctl_html_no_cont = '';
      $layout_wrp = '';
      $i=0;

      $old_animations=array("bounceInUp","bounceInDown","bounceInLeft","bounceInRight","slideInDown","slideInUp",
      "bounceIn","slideInLeft","slideInRight","shake","wobble","swing","jello","flip","fadein",
      "rotatein","zoomIn");
      // set stories animations
     
    if (isset($attribute['animations'])) {
        $ctl_animation=$attribute['animations'];
    }else{
      $ctl_animation ='fade-in';
         }
      if(in_array($ctl_animation,$old_animations)){
        $ctl_animation ='fade-in';
      }  

       
      $itcls='';
      if($active_design=="design-2" || $active_design=="design-3"  || $active_design=="design-4") {
          $items = ctl_set_default_value($attribute['items'],"3");
          $itcls='hori-items-'.$items;
      }else{
          $items ='0';
          $itcls='hori-items-1';
      }

 if ($layout == "one-side") {
        $layout_cls = 'one-sided';
        $layout_wrp = 'one-sided-wrapper';
    } 
    elseif ($layout == "compact"){
         $layout_cls = 'compact';
        $layout_wrp = 'compact-wrapper';
     } 
    else if ($layout == "horizontal") {
        $layout_cls = 'horizontal';
        $layout_wrp = 'ctl-horizontal-wrapper';
    }
    else {
        $layout_cls = '';
        $layout_wrp = 'both-sided-wrapper';
    }

      require('layouts/loop-content-timeline.php');

        if ($layout == "horizontal") {
            $timeline_id=uniqid();
              $output .='<! ========= Cool Timeline PRO '.CTLPV.' =========>';
            $timeline_wrp_id="ctl-horizontal-slider-".$timeline_id;
            $output .= '<div style="opacity:0" id="'.$timeline_wrp_id.'" class="cool-timeline-horizontal  '.$wrp_cls.' '.$design_cls.'" date-slider="ctl-h-slider-'.$timeline_id.'" data-nav="nav-slider-'.$timeline_id.'" data-start-on="'.esc_attr($attribute['start-on']).'" data-autoplay="'.esc_attr($attribute['autoplay']).'"  data-items="'.$items.'">
        <div class="timeline-wrapper '.$wrapper_cls.' '.$itcls.'" >';

            if($active_design=="design-4") {
                $output .= '<div  class="wrp-desgin-4">';
            }else{
                $output .= '<div class="clt_carousel_slider">';
                $output .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
                $output .= $dates_li;
                $output .= '</ul></div>';
            }

            $output .= '<div  class="clt_caru_slider ">';
            $output .= '<ul class="ctl_h_slides"  id="ctl-h-slider-'.$timeline_id.'">';
            $output .=$ctl_html;
            $output .= '</ul></div>';

            if($active_design=='design-4') {
                $output .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
                $output .= $dates_li;
                $output .= '</ul></div>';
            }

            $output .='</div></div>';


        }else {
            $timeline_id=uniqid();
            $main_wrp_id='content-'.$layout.'-'.$tm_active_design.'-'.rand(1,20);

            $main_wrp_cls=array();
                $main_wrp_cls[]="cool_timeline";
                $main_wrp_cls[]="cool-timeline-wrapper";
                $main_wrp_cls[]=$layout_wrp;
                $main_wrp_cls[]=$wrapper_cls;
                $main_wrp_cls[]=$design_cls;
                $main_wrp_cls=apply_filters('ctl_wrapper_clasess',$main_wrp_cls);   

              $output .='<! ========= Cool Timeline PRO '.CTLPV.' =========>';
            $output .= '
          <!-- Cool content timeline 
          ================================================== -->
            <div style="opacity:0;" data-showposts="'.$attribute['show-posts'].'" id="'.$main_wrp_id.'" class="'.implode(" ",$main_wrp_cls).'"  data-pagination="' . $enable_navigation . '"  data-pagination-position="' . $navigation_position . '">';
             
              if($attribute['filters']=="yes"){
                 $output.=ctl_categories_filters($post_taxonomy,$select_cat=$post_category,$type="content-tm" ,$layout);
              }

             $output .=ctl_main_title($ctl_options_arr,$ctl_title_text,$ttype='content_timeline');
            $output .= '<div class="cool-timeline ultimate-style ' . $layout_cls . ' ' . $wrp_cls . '">';

             $output .='<div style="display:none" class="filter-preloaders"><img src="'.CTP_PLUGIN_URL.'assets/images/clt-compact-preloader.gif"></div>';

            $output .= '<div data-animations="'.$ctl_animation.'" id="timeline-' . $timeline_id . '" class="timeline cooltimeline_cont ' . $cls_icons . '">';

            if($layout=="compact"){
                $compact_id="ctl-compact-pro-".rand(1,20);
                $output .='<div id="'.$compact_id.'" class="clt-compact-cont"><div class="center-line"></div>';
            }
        
            $output .= $ctl_html;
            if($layout=="compact"){
                $output .='</div>';
            }

          $output.='<div class="clearfix"></div>';
            $pagination_html="";
            if($pagination=="ajax_load_more"){
   
                if($ctl_loop->max_num_pages>1){
             $pagination_html.='<button data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i>'.__(' Loading','cool-timeline').'" data-max-num-pages="'.$ctl_loop->max_num_pages.'" data-timeline-type="'.$layout.'" class="ctl_load_more">'.__('Load More','cool-timeline').'</button>';
                 }
                }else{
             if ( $enable_pagination == "yes") {
                  if (function_exists('ctl_pro_pagination')) {
                     $pagination_html.= ctl_pro_pagination($ctl_loop->max_num_pages, "", $paged);
                  }
               }
              }  
             $output .='</div>';   
            $output .=$pagination_html.$ctl_html_no_cont;
            $output .= '</div> </div>  <!-- end
           ================================================== -->';

        }  
         return $output;
    }
  

     /*
      * Include this plugin's public JS & CSS files on posts.
      */

        function ctl_ct_ss()
        {   
            ctl_common_assets();
        }

    }
}
