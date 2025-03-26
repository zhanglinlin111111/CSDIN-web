<?php

add_action('set_current_user' , function () {
    $current_user = wp_get_current_user();
    //editor角色
    if (in_array('editor', $current_user->roles)) {

        //去除Editor的权限
        include __DIR__.'/drop_capacity_editor.php';

    }
});








//调整表单插件的菜单显示名称，调整Profile的显示顺序
add_action('admin_menu', function(){
    global $menu;

//  var_dump($menu);

    $media_key = '';
    $post_key = '';
    $elementor_db_key = '';
    $cf7db_key = '';
    $profile_key = '';

    $media_item = array();
    $post_item = array();
    $elementor_db_item = array();
    $cf7db_item = array();
    $profile_item = array();

    foreach ($menu as $priority => &$item){

        //获取Media
        if($item[2]=='upload.php'){
            $media_key = $priority;
            $media_item = $item;
            unset($menu[$priority]);
        }

        //获取Post
        if($item[2]=='edit.php'){
            $post_key = $priority;
            $post_item = $item;
            unset($menu[$priority]);
        }


        //修改Elementor DB的菜单显示名称
        if($item[2]=='edit.php?post_type=elementor_cf_db'){
//            $menu[$priority][0] = 'Forms';

            $item[0] = 'Forms';

//            die('fuck');
            $elementor_db_key = $priority;
            $elementor_db_item = $item;
            unset($menu[$priority]);
        }

        //修改Contact Form 7 DB的菜单显示名称
        if($item[2]=='cfdb7-list.php'){
//            $menu[$priority][0] = 'Float Forms';
            $item[0] = 'Float Forms';
            $cf7db_key = $priority;
            $cf7db_item = $item;
            unset($menu[$priority]);
        }

        //删除profile
        if($item[2]=='profile.php'){
            $profile_key = $priority;
            $profile_item = $item;
            unset($menu[$priority]);
        }


        //Shopify to Woo改成=> Shopify to me
        if($item[2]=='s2w-import-shopify-to-woocommerce'){
            $item[0] = 'Shopify to Me';
        }


        //WC Lucky Wheel改成=> Lucky Wheel
        if($item[2]=='woo-lucky-wheel'){
            $item[0] = 'Lucky Wheel';
        }

        
        //ParcelPanel改成=> Parcel
        if($item[2]=='pp-admin'){
            $item[0] = 'Parcel';
        }


        //WP Mail SMTP改成=> SMTP
        if($item[2]=='wp-mail-smtp'){
            $item[0] = 'SMTP';
        }


        //Woocommerce改成=> Commerce
        if($item[2]=='woocommerce'){
            $item[0] = 'Commerce';
        }
    }

    //调整Media的显示顺序
    if(!empty($media_item)){
        $menu += array(($media_key-5) => $media_item);
    }

    //调整Post的显示顺序
    if(!empty($post_item)){
        $menu += array(($post_key+5) => $post_item);
    }

    //调整Elementor DB的显示顺序
    if(!empty($elementor_db_item)){
        $menu += array((1000+$elementor_db_key) => $elementor_db_item);
    }

    //调整Contact Form 7 DB显示顺序
    if(!empty($cf7db_item)){
        $menu += array((2000+$cf7db_key) => $cf7db_item);
    }

    //调整Profile的显示顺序
    if(!empty($profile_item)){
        $menu += array((3000+$profile_key) => $profile_item);
    }


//    var_dump($menu);

},9050);




//将所有Post重命名为News
add_filter( "post_type_labels_post", function($labels){

    $labels->name="News";
    $labels->singular_name="News";
    $labels->add_new="Add New";
    $labels->add_new_item="Add News";
    $labels->edit_item="Edit News";
    $labels->new_item="New News";
    $labels->view_item="View News";
    $labels->view_items="View News";
    $labels->search_items="Search News";
    $labels->not_found="No news found.";
    $labels->not_found_in_trash="No news found in Trash.";
    $labels->all_items="All News";
    $labels->archives="News Archives";
    $labels->attributes="News Attributes";
    $labels->insert_into_item="Insert into news";
    $labels->uploaded_to_this_item="Uploaded to this news";
    $labels->featured_image="Featured image";
    $labels->set_featured_image="Set featured image";
    $labels->remove_featured_image="Remove featured image";
    $labels->use_featured_image="Use as featured image";
    $labels->filter_items_list="Filter news list";
    $labels->filter_by_date="Filter by date";
    $labels->items_list_navigation="News list navigation";
    $labels->items_list="News list";
    $labels->item_published="News published.";
    $labels->item_published_privately="News published privately.";
    $labels->item_reverted_to_draft="News reverted to draft.";
    $labels->item_scheduled="News scheduled.";
    $labels->item_updated="News updated.";
    $labels->menu_name="News";
    $labels->name_admin_bar="News";

//    echo '$labels';
//    var_dump($labels);


    return $labels;
} );