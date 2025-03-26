<?php
/*
* 去除editor的访问权限
*/


//remove menu:Contact Form 7
remove_action('admin_menu', 'wpcf7_admin_menu', 9, 0);

//remove menu:Envato Elements
if(class_exists('\Envato_Elements\Plugin')){
    remove_action('admin_menu',array(\Envato_Elements\Plugin::get_instance(),'admin_menu'));
}


//普通用户不可访问翻译插件prisna translate的设置
add_action('admin_menu', function(){
    //去除普通用户的菜单
    remove_menu_page('prisna-translate-settings');
    
    //隐藏自定义翻译多余的设置项
    add_action('admin_head',function(){
        echo '<style type="text/css">';
        include ELEMENT_ADDON_ZENDKEE_PATH.'libs/css/hide_prisna_translate.css';
        echo '</style>';
        
        
        echo '<script type="text/javascript">';
        include ELEMENT_ADDON_ZENDKEE_PATH.'libs/js/hide_prisna_translate.js';
        
        echo '</script>';
    });
}, 9999);


//去除工具箱菜单
add_action('admin_menu', function(){
    remove_menu_page('tools.php');
}, 9000);



//去除Elementor Template菜单
add_action('admin_menu', function(){
    remove_menu_page('edit.php?post_type=elementor_library');
}, 9010);


//去除Yoast菜单
add_action('admin_menu', function(){
    remove_menu_page('wpseo_dashboard');
    remove_menu_page('wpseo_workouts');
}, 9020);



//去除Dashboard菜单
add_action('admin_menu', function(){
    remove_menu_page('index.php');
}, 9030);

//访问index.php（Dashboard页面，重定向到/wp-admin/edit.php）
add_action( 'admin_init', function(){
    global $pagenow;
    if ( 'index.php' == $pagenow || 'tools.php' == $pagenow ) {
        wp_redirect( sprintf('%sedit.php',admin_url()) );
        exit;
    }
} );


//隐藏多余的显示项
add_action('admin_head',function(){
    echo '<style type="text/css">';
    include ELEMENT_ADDON_ZENDKEE_PATH.'libs/css/editor.css';
    echo '</style>';
});


//去除顶端admin-bar的选项
add_action('admin_bar_menu',function($wp_admin_bar){
    //去除评论数
    $wp_admin_bar->remove_menu( 'comments' );
    
    //去除Yoast的小工具
    $wp_admin_bar->remove_menu( 'wpseo-menu' );
    
    return $wp_admin_bar;
} , 999);



//去掉profile页面的联系方式
add_filter( 'user_contactmethods', function($methods, $user){
    unset($methods['facebook']);
    unset($methods['instagram']);
    unset($methods['linkedin']);
    unset($methods['myspace']);
    unset($methods['pinterest']);
    unset($methods['soundcloud']);
    unset($methods['tumblr']);
    unset($methods['twitter']);
    unset($methods['youtube']);
    unset($methods['wikipedia']);
    return $methods;
} , 20 , 2 );



//去除Products菜单下的两个子菜单：Tags，Attributes
add_action('admin_menu', function(){
    global $submenu;
    
    if(isset($submenu["edit.php?post_type=product"])){
        foreach ($submenu["edit.php?post_type=product"] as $key => &$item){
            //Tags and Attributes
            if(isset($item[0]) && ($item[0]=='Tags' || $item[0]=='Attributes')){
                unset($submenu["edit.php?post_type=product"][$key]);
            }
        }
    }
}, 9040);




add_action('admin_menu', function(){
    //去除评论功能
    remove_menu_page('edit-comments.php');
    //去除友情链接
    remove_menu_page('link-manager.php');
    remove_menu_page('edit-tags.php?taxonomy=link_category');
    //去除Woocommerce Marking
    remove_menu_page('woocommerce-marketing');
    //去除Woocommerce 的Analyse
    remove_menu_page('wc-admin&path=/analytics/overview');
    
}, 8000);




