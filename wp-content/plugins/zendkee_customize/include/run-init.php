<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/19 0019
 * Time: 10:35
 */


if (!defined('ABSPATH')) {
    exit();
}



//删除GA/GTM/GSC代码
if(zendkee_get_option('clear_google_code')){
    delete_option('google_ga_code');
    delete_option('google_header_gtm_code');
    delete_option('google_footer_gtm_code');

    zendkee_update_option('misc_gsc', '');
    zendkee_update_option('misc_ga', '');
    zendkee_update_option('misc_gtm_header', '');
    zendkee_update_option('misc_gtm_footer', '');

    //做完后清除init状态

    zendkee_update_option('clear_google_code' , 0);
    zendkee_save_option();
}



//清空smtp设置
if(zendkee_get_option('clear_smtp_settings')){
    delete_option('wp_mail_smtp');
    zendkee_update_option('clear_smtp_settings' , 0);
    zendkee_save_option();
}



//清空询盘记录
if(zendkee_get_option('clear_inquiry_log')){
    global $wpdb;
    //Contact Form CFDB7
    //table:wp_db7_forms
    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}db7_forms;");

    //Advanced CF7 DB
    //table:wp_cf7_vdata_entry
    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}cf7_vdata_entry;");

    //WPForms | VestaThemes.com
    //table:wp_wpforms_entry_fields
    //table:wp_wpforms_entries
    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}wpforms_entry_fields;");
    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}wpforms_entries;");


    zendkee_update_option('clear_inquiry_log' , 0);
    zendkee_save_option();
}

// 清空Mail设置（询盘发件人、收件人）
if(zendkee_get_option('clear_mail_settings')){
    //Contact Form 7
    //get all contact form 7 post id
    $cf7_ids = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type='wpcf7_contact_form'",ARRAY_A);
    foreach ($cf7_ids as $id_arr){
        $_mail = get_post_meta($id_arr['ID'] , '_mail' , true);
        $_mail_2 = get_post_meta($id_arr['ID'] , '_mail_2' , true);

        if(isset($_mail['subject'])){
            $_mail['subject']='';
        }
        if(isset($_mail['sender'])){
            $_mail['sender']='';
        }
        if(isset($_mail['recipient'])){
            $_mail['recipient']='';
        }

        if(isset($_mail_2['subject'])){
            $_mail_2['subject']='';
        }
        if(isset($_mail_2['sender'])){
            $_mail_2['sender']='';
        }
        if(isset($_mail_2['recipient'])){
            $_mail_2['recipient']='';
        }


        update_post_meta($id_arr['ID'] , '_mail' , $_mail);
        update_post_meta($id_arr['ID'] , '_mail_2' , $_mail_2);

    }

    zendkee_update_option('clear_mail_settings' , 0);
    zendkee_save_option();
}




//清空SEO优化记录
if(zendkee_get_option('clear_seo_log')){
    global $wpdb;

    /**  Yoast SEO start **/
    //table cols:
    //wp_yoast_indexable.title
    //wp_yoast_indexable.description
    $wpdb->query("UPDATE wp_yoast_indexable SET title='',description='';");



    //terms
    delete_option('wpseo_taxonomy_meta');




    //detail page
    //table:wp_postmeta
    /**
    _yoast_wpseo_focuskw
    _yoast_wpseo_title
    _yoast_wpseo_metadesc
    _yoast_wpseo_linkdex
    _yoast_wpseo_content_score
    _yoast_wpseo_is_cornerstone
    _yoast_wpseo_meta-robots-adv
    _yoast_wpseo_opengraph-title
    _yoast_wpseo_opengraph-description
    _yoast_wpseo_opengraph-image
    _yoast_wpseo_opengraph-image-id
    _yoast_wpseo_twitter-title
    _yoast_wpseo_twitter-description
    _yoast_wpseo_twitter-image
    _yoast_wpseo_twitter-image-id
     */

    $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_yoast_wpseo_%';");

    /**  Yoast SEO end **/


    /** All In One SEO Pack Start **/

    //table:wp_postmeta
    /**
    _aioseop_keywords
    _aioseop_description
    _aioseop_title
    _aioseop_custom_link
    _aioseop_sitemap_exclude
    _aioseop_disable
    _aioseop_disable_analytics
    _aioseop_noindex
    _aioseop_nofollow
    _aioseop_sitemap_priority
    _aioseop_sitemap_frequency
     */

    //table:wp_termmeta
    /**
    _aioseop_keywords
    _aioseop_description
    _aioseop_title
    _aioseop_custom_link
    _aioseop_disable
    _aioseop_disable_analytics
    _aioseop_noindex
    _aioseop_nofollow
    _aioseop_sitemap_exclude
    _aioseop_sitemap_priority
    _aioseop_sitemap_frequency
     */

    $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_aioseop_%';");
    $wpdb->query("DELETE FROM {$wpdb->prefix}termmeta WHERE meta_key LIKE '_aioseop_%';");

    /** All In One SEO Pack End **/



    zendkee_update_option('clear_seo_log' , 0);
    zendkee_save_option();
}



//禁用Avada不常用组件
if(zendkee_get_option('clear_avada_elements')){

    //table:wp_option,key:fusion_builder_settings
    $fusion_builder_settings = is_array(get_option('fusion_builder_settings')) ? get_option('fusion_builder_settings') : array();
    $fusion_builder_settings['fusion_elements'] = isset($fusion_builder_settings['fusion_elements']) && is_array($fusion_builder_settings['fusion_elements']) && !empty($fusion_builder_settings['fusion_elements']) ? $fusion_builder_settings['fusion_elements'] : array();

    global $avada_exclude_elements;


    if(!empty($fusion_builder_settings['fusion_elements'])){

        $fusion_builder_settings['fusion_elements'] = array_diff($fusion_builder_settings['fusion_elements'] , $avada_exclude_elements);

        update_option('fusion_builder_settings',$fusion_builder_settings);
    }else{
        $all_elements = array(
            'fusion_alert',
            'fusion_tb_archives',
            'fusion_audio',
            'fusion_tb_author',
            'fusion_blog',
            'fusion_breadcrumbs',
            'fusion_button',
            'fusion_chart',
            'fusion_checklist',
            'fusion_code',
            'fusion_tb_comments',
            'contact-form-7',
            'fusion_tb_content',
            'fusion_content_boxes',
            'fusion_countdown',
            'fusion_counters_box',
            'fusion_counters_circle',
            'fusion_dropcap',
            'fusion_events',
            'fusion_faq',
            'fusion_tb_featured_slider',
            'fusion_flip_boxes',
            'fusion_fontawesome',
            'fusion_fusionslider',
            'fusion_gallery',
            'fusion_map',
            'fusion_highlight',
            'fusion_imageframe',
            'fusion_image_before_after',
            'fusion_images',
            'layerslider',
            'fusion_lightbox',
            'fusion_menu_anchor',
            'fusion_modal',
            'fusion_modal_text_link',
            'fusion_one_page_text_link',
            'fusion_tb_pagination',
            'fusion_person',
            'fusion_popover',
            'fusion_portfolio',
            'fusion_postslider',
            'fusion_pricing_table',
            'fusion_progress',
            'fusion_tb_project_details',
            'fusion_recent_posts',
            'fusion_tb_related',
            'fusion_search',
            'fusion_section_separator',
            'fusion_separator',
            'fusion_sharing',
            'fusion_slider',
            'rev_slider',
            'fusion_social_links',
            'fusion_soundcloud',
            'fusion_syntax_highlighter',
            'fusion_table',
            'fusion_tabs',
            'fusion_tagline_box',
            'fusion_testimonials',
            'fusion_text',
            'fusion_title',
            'fusion_accordion',
            'fusion_tooltip',
            'fusion_login',
            'fusion_lost_password',
            'fusion_register',
            'fusion_video',
            'fusion_vimeo',
            'fusion_widget',
            'fusion_widget_area',
            'fusion_products_slider',
            'fusion_featured_products_slider',
            'fusion_woo_shortcodes',
            'fusion_youtube',
        );

        $fusion_builder_settings['fusion_elements'] = array_diff($all_elements , $avada_exclude_elements);

        update_option('fusion_builder_settings',$fusion_builder_settings);
    }

    //scripts
    //fusion-chartjs


    zendkee_update_option('clear_avada_elements' , 0);
    zendkee_save_option();
}




/*清空网站配置*/
//清空title

if(zendkee_get_option('clear_settings')){
    //Settings - Site Title
    update_option('blogname','');

    //Settings - Tagline
    update_option('blogdescription','');

    //Settings - Timezone
    update_option('timezone_string','Asia/Shanghai');

    //Settings - Update Services
    update_option('ping_sites','');


    //Settings - Discussion
    update_option('default_pingback_flag','');
    update_option('default_ping_status','closed');
    update_option('default_comment_status','closed');
    update_option('require_name_email','1');
    update_option('comment_registration','1');
    update_option('close_comments_for_old_posts','1');
    update_option('comments_notify','');
    update_option('moderation_notify','');
    update_option('comment_moderation','1');
    update_option('comment_whitelist','1');
    update_option('show_avatars','');

    //Settings - Permalink
    update_option('permalink_structure','/%category%/%postname%/');


    //Contact Form 7
    $wpcf7 = get_option('wpcf7');
    if($wpcf7){
        $wpcf7['recaptcha'] = '';
        update_option('wpcf7',$wpcf7);
    }



    zendkee_update_option('clear_settings' , 0);
    zendkee_save_option();
}



//禁用所有图像尺寸
//已经在保存值的时候处理


/* 扫描图片 start */
if(!function_exists('recursion_scan')){
    function recursion_scan($path){
        $found_files = array();
        $file_or_dir = scandir($path);
        foreach ($file_or_dir as $key => $value) {
            if($value == '.' or $value == '..'){
                continue;
            }
            $file = $path . DIRECTORY_SEPARATOR . $value;

            if(is_dir($file)){
                $found_files = array_merge($found_files , recursion_scan($file));
            }elseif(is_file($file)){
                $found_files[] = $file;
            }
        }
        return $found_files;
    }
}


if(!function_exists('is_image_file')){
    function is_image_file($filename){
        if(preg_match("~.+?\.(jpe?g|png|gif|bmp|webp)$~i" , $filename )){
            return true;
        }else {
            return false;
        }
    }
}


if(!function_exists('is_image_file_by_extension')){
    function is_image_file_by_extension($filename){
        $suffix = strtolower (trim(strrchr($filename,'.'),'.'));
        if(in_array($suffix, array('jpg','jpeg','png','gif','webp','bmp'))){
            return true;
        }else{
            return false;
        }
    }
}



//add_action('wp_ajax_nopriv_scan_images', 'scan_images'); // for not logged in users
add_action('wp_ajax_scan_images', 'scan_images');
if(!function_exists('scan_images')){
    function scan_images($par)
    {
        if ($_REQUEST['action'] == 'scan_images') {
            $upload_dir = wp_upload_dir();
            $base_upload_dir = $upload_dir['basedir'];

            $images = array();
            $files = recursion_scan($base_upload_dir);
            foreach ($files as $file){
                if(is_image_file_by_extension($file)){
                    $images[] = str_replace(ABSPATH , '' , $file);
//                $images[] = substr ($file,$length);
                }
            }
//        var_dump($files);
            var_dump($images);
        }
    }
}



//add_action('wp_ajax_nopriv_scan_db', 'scan_db'); // for not logged in users
add_action('wp_ajax_scan_db', 'scan_db');
if(!function_exists('scan_db')){
    function scan_db($par)
    {
        if ($_REQUEST['action'] == 'scan_db') {

//        $new_sizes = wp_get_registered_image_subsizes();
//        var_dump($new_sizes);

//        global $wpdb;
//        $postids = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type='attachment' order by ID", ARRAY_A);
//
//        $metas = $wpdb->get_results("SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='_wp_attachment_image_alt' ORDER BY post_id", ARRAY_A);
//
//
//        $image = array();
//        foreach ($postids as $id) {
//            $image[$id['ID']]['alt'] = '';
//            $image[$id['ID']]['url'] = wp_get_attachment_image_url($id['ID'], 'full');
//            $image[$id['ID']]['new_alt'] = get_image_alt_by_url($image[$id['ID']]['url']);
//            $post_ancestors = get_post_ancestors($id['ID']);
//            $image_attached_post = get_post($post_ancestors[0], ARRAY_A);
//            $image[$id['ID']]['post_title'] = $image_attached_post['post_title'];
//            $image[$id['ID']]['post_url'] = get_the_permalink($post_ancestors[0]);
//            for ($i = 0; $i < count($metas);) {
//                if ($metas[$i]['post_id'] == $id['ID']) {
//                    $image[$id['ID']]['alt'] = $metas[$i]['meta_value'];
//                    array_splice($metas, $i, 1);
//                    continue;
//                }
//                $i++;
//            }
//        }
//
//
//        var_dump($image);
        }
    }
}

/* 扫描图片 end */
