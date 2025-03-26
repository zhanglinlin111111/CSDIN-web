<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 11:26
 */



//禁用F12
if (zendkee_get_option('disable_f12')) {
    add_action('wp_footer', function () {
        echo '<script>
document.onkeydown = function () {
        if (window.event && window.event.keyCode == 123) {
          event.keyCode = 0;
          event.returnValue = false;
        }
      };
      </script>';

    });
}

//禁用右键
if (zendkee_get_option('disable_rightclick')) {
    add_action('wp_footer', function () {
        echo '<script>
document.oncontextmenu = function (event) {
        if (window.event) {
          event = window.event;
        }
        try {
          var the = event.srcElement;
          if (
            !(
              (the.tagName == "INPUT" && the.type.toLowerCase() == "text") ||
              the.tagName == "TEXTAREA"
            )
          ) {
            return false;
          }
          return true;
        } catch (e) {
          return false;
        }
      };
      </script>';
    });
}


//禁用中文浏览器
if (zendkee_get_option('disable_lang_cn')) {
    add_action('init', function () {
        if (zendkee_frontend()) {//not wp-admin, not login user , not login page
            if (zendkee_is_chinese_browser()) {
                status_header(504);
                exit;
            }
        }
    });
}



//关闭搜索
if(zendkee_get_option('disable_search')){
    if(!is_admin()){
        add_action( 'parse_query', function($query, $error = true){
            if ( is_search() ) {
                $query->is_search = false;
                $query->query_vars['s'] = false;
                $query->query['s'] = false;
                if ( $error == true ){
                    $query->is_404 = true;
                }
            }
        } );
        add_filter( 'get_search_form', function($s){
            return null;
        } );
    }
}


//关闭作者页
if(zendkee_get_option('disable_author_page')) {
	add_filter('author_link', function($link, $author_id, $author_nicename){
		return '#author';
	}, 20, 3);

    add_action('template_redirect', function () {
        global $wp_query;
        if ( is_author() ) {
            // Redirect to homepage, set status to 301 permenant redirect.
            // Function defaults to 302 temporary redirect.
            wp_redirect(get_option('home'), 301);
            exit;
        }
    });
}

