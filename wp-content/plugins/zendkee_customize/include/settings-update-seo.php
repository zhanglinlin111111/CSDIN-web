<?php

add_action('wp_ajax_zendkee_seo', 'zendkee_seo');
if(!function_exists('zendkee_seo')){
    function zendkee_seo($par)
    {
        if ($_REQUEST['action'] == 'zendkee_seo') {
            $reload_action = array('action'=>'');

            $remove_meta_og = $_REQUEST['remove_meta_og'];
            $auto_complete_alt = $_REQUEST['auto_complete_alt'];
            $add_site_title_to_alt = $_REQUEST['add_site_title_to_alt'];
            $site_title = $_REQUEST['site_title'];
            $site_type = $_REQUEST['site_type'];
            $hot_product = $_REQUEST['hot_product'];
            $industry_name = $_REQUEST['industry_name'];

            $seo_page_title = $_REQUEST['seo_page_title'];
            $seo_page_description = $_REQUEST['seo_page_description'];

            $seo_post_title = $_REQUEST['seo_post_title'];
            $seo_post_description = $_REQUEST['seo_post_description'];

            $seo_product_title = $_REQUEST['seo_product_title'];
            $seo_product_description = $_REQUEST['seo_product_description'];

            $seo_product_cat_title = $_REQUEST['seo_product_cat_title'];
            $seo_product_cat_description = $_REQUEST['seo_product_cat_description'];


            $special_page_seo_page = array_unique($_REQUEST['special_page_seo_page']);
            $special_page_seo_title = $_REQUEST['special_page_seo_title'];
            $special_page_seo_description = $_REQUEST['special_page_seo_description'];

//            var_dump($special_page_seo_page);
//            var_dump($special_page_seo_title);
//            var_dump($special_page_seo_description);

            foreach ($special_page_seo_page as $key => $page_id){
                if(empty($page_id)){
//                    unset($special_page_seo_page_item);
                    unset($special_page_seo_page[$key]);
                    unset($special_page_seo_title[$key]);
                    unset($special_page_seo_description[$key]);
                }
            }

//            var_dump($special_page_seo_page);
//            var_dump($special_page_seo_title);
//            var_dump($special_page_seo_description);

            //reset config
            $old_site_type = zendkee_get_option('site_type');
            if($old_site_type != '' && $site_type != $old_site_type){
                $seo_value = array(
                    'seo_page_title' => null,
                    'seo_page_description' => null,
                    'seo_post_title' => null,
                    'seo_post_description' => null,
                    'seo_product_title' => null,
                    'seo_product_description' => null,
                    'seo_product_cat_title' => null,
                    'seo_product_cat_description' => null,
                );

                extract($seo_value,EXTR_OVERWRITE);
                $reload_action = array('action'=>'reload');
            }




            zendkee_update_option('remove_meta_og', $remove_meta_og);
            zendkee_update_option('auto_complete_alt', $auto_complete_alt);
            zendkee_update_option('add_site_title_to_alt', $add_site_title_to_alt);
            zendkee_update_option('site_title', $site_title);
            zendkee_update_option('site_type', $site_type);
            zendkee_update_option('hot_product', $hot_product);
            zendkee_update_option('industry_name', $industry_name);

            zendkee_update_option('seo_page_title', $seo_page_title);
            zendkee_update_option('seo_page_description', $seo_page_description);

            zendkee_update_option('seo_post_title', $seo_post_title);
            zendkee_update_option('seo_post_description', $seo_post_description);

            zendkee_update_option('seo_product_title', $seo_product_title);
            zendkee_update_option('seo_product_description', $seo_product_description);

            zendkee_update_option('seo_product_cat_title', $seo_product_cat_title);
            zendkee_update_option('seo_product_cat_description', $seo_product_cat_description);

            zendkee_update_option('special_page_seo_page', $special_page_seo_page);
            zendkee_update_option('special_page_seo_title', $special_page_seo_title);
            zendkee_update_option('special_page_seo_description', $special_page_seo_description);



            if($site_title){
                update_option('blogname' , $site_title);
            }



            /* 设置Yoast */
            $wpseo_titles = get_option('wpseo_titles');
            if($wpseo_titles){
                $wpseo_titles['title-page'] = $seo_page_title;
                $wpseo_titles['metadesc-page'] = $seo_page_description;

                $wpseo_titles['title-post'] = $seo_post_title;
                $wpseo_titles['metadesc-post'] = $seo_post_description;

                $wpseo_titles['title-product'] = $seo_product_title;
                $wpseo_titles['metadesc-product'] = $seo_product_description;

                $wpseo_titles['title-tax-product_cat'] = $seo_product_cat_title;
                $wpseo_titles['metadesc-tax-product_cat'] = $seo_product_cat_description;

                update_option('wpseo_titles' , $wpseo_titles);
            }

            if(!empty($special_page_seo_page)){
                foreach ($special_page_seo_page as $key => $page_id){
                    update_post_meta($page_id , '_yoast_wpseo_title' , $special_page_seo_title[$key]);
                    update_post_meta($page_id , '_yoast_wpseo_metadesc' , $special_page_seo_description[$key]);
                }
            }



            if (zendkee_save_option()) {
                echo json_encode(array_merge(array('status' => 'ok', 'info' => 'Update Success') , $reload_action));
            } else {
                echo json_encode(array_merge(array('status' => 'fail', 'info' => 'Nothing To Do') , $reload_action));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}


