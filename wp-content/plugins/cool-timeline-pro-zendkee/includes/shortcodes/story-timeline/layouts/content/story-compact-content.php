 <?php
  $hidden_year='';

  $post_date = explode('/', get_the_date($ctl_story_date));
  $post_year = (int)$post_date[$year_position];
 
 // hidden Stories Year for scrolling navigation
  if ($post_year != $display_year) {
    $display_year = $post_year;
    $ctle_year_lbl = sprintf('<span  class="ctl-timeline-date">%s</span>',$post_year);

    if($pagination=="ajax_load_more" && $last_year== $post_year){
      $hidden_year.='';
      }else{
      $hidden_year.= '<span data-cls="sc-nv-'.esc_attr($design).' '.esc_attr($wrp_cls).'" class="compact-year scrollable-section '.esc_attr($design).'-year"data-section-title="' . esc_attr($post_year) . '" id="year-'.esc_attr($post_year).'"></span>'; 
      }

  }      
// stories content wrapper start
$ctl_html .= '<!-- .timeline-post-start-->';
$ctl_html .= '<div data-alternate="'.$i.'" id="story-'.esc_attr($post_id).'" class="'.implode(" ",$p_cls).'">';
$ctl_html .= $hidden_year;

// story icon
  if ( $icons == "YES" ) {
      $icon=ctl_post_icon($post_id,$default_icon);
     $ctl_html .='<div   data-aos="'. $ctl_animation.'" class="timeline-icon icon-larger iconbg-turqoise icon-color-white '.esc_attr($design).'-icon">
                        <div class="icon-placeholder">'.$icon.'</div>
                        <div class="timeline-bar"></div>
                    </div>';
    }else {
      $ctl_html .= '<div  data-aos="'.$ctl_animation.'" class="timeline-icon icon-dot-full '.esc_attr($design).'-dot"><div class="timeline-bar"></div></div>';
     }
  
// story content wrapper
     $ctl_html .= '<div data-aos="'.$ctl_animation.'"  class="timeline-content  clearfix ' .esc_attr($even_odd) . '  ' . esc_attr($container_cls) .' '.esc_attr($design).'-content '.$stop_ani.'">';

     // display story title on the top if selected design between these designs
     if(in_array($active_design,array("design-2","default","design-4","design-5","design-6"))){

      // if date on the top of compact layout
          if($attribute['compact-ele-pos']=="main-date" ){
               $ctl_html .='<div class="content-title clt-meta-date">'.apply_filters('ctl_story_dates',$posted_date).'</div>';     
              }else{
                  $ctl_html .='<h2 class="content-title">'.$slink_s. get_the_title() .$slink_e.'</h2>';
              }
        }

 $ctl_html .= '<div class="ctl_info event-description ' . $container_cls. '">';
  
 // story dynamic media
 if ($story_format == "video") {
             $ctl_html .=clt_story_video($post_id);
         } elseif ($story_format == "slideshow") {  
              $ctl_html .=clt_story_slideshow($post_id,$attribute['type'],$ctl_options_arr);
         }else{
             $ctl_html .=clt_story_featured_img($post_id,$ctl_options_arr);
          }

$ctl_html .= '<div class="content-details">';
        
          // if date on the top of content in the compact layout
         if($attribute['compact-ele-pos']=="main-date" ){
            if($active_design=="design-3") {
                  $ctl_html .= '<div class="clt-compact-date">'.apply_filters('ctl_story_dates',$posted_date).'</div>';
                }
                // the title is below the date
             $ctl_html .='<h2 class="compact-content-title">'.$slink_s. get_the_title() .$slink_e.'</h2>';

         }else{
            // in all other design title is on the top
             if($active_design=="design-3" || $active_design=="design-6") {
                $ctl_html .='<h2 class="compact-content-title">'.$slink_s. get_the_title() .$slink_e.'</h2>';
                  }
                  
                if ($disable_months == "no") {
                         $ctl_html .= '<div class="clt-compact-date">'.apply_filters('ctl_story_dates',$posted_date).'</div>';
                        }
              }

// story full content 
  if ($story_content=="full") {
             $ctl_html .= apply_filters('the_content', $post->post_content);
            } else {
            $ctl_html .= "<p>" . apply_filters('ctl_story_excerpt',get_the_excerpt()). "</p>";
             }
         $ctl_html .='</div>';
         $ctl_html .= '</div>';
        $ctl_html .= '</div><!-- timeline content --></div>
        <!-- .timeline-post-end -->';

