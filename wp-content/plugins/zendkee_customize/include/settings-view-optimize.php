<?php
if (!defined('ABSPATH')) {
    exit();
}
$disable_emoji = zendkee_get_option('disable_emoji');
$disable_embeds = zendkee_get_option('disable_embeds');
$disable_feed = zendkee_get_option('disable_feed');
$disable_offline_editor = zendkee_get_option('disable_offline_editor');
$disable_index_rel_link = zendkee_get_option('disable_index_rel_link');
$disable_prev_next = zendkee_get_option('disable_prev_next');
$disable_related_post = zendkee_get_option('disable_related_post');
$disable_feature_publish = zendkee_get_option('disable_feature_publish');
$disable_generator = zendkee_get_option('disable_generator');
$disable_shortlink = zendkee_get_option('disable_shortlink');
$disable_comment_style = zendkee_get_option('disable_comment_style');
$disable_language_package = zendkee_get_option('disable_language_package');
$disable_language_package_backend = zendkee_get_option('disable_language_package_backend');
$cdn_js_libs = zendkee_get_option('cdn_js_libs');
$cdn_js_libs_type = zendkee_get_option('cdn_js_libs_type');
$cdn_css_libs = zendkee_get_option('cdn_css_libs');
$remove_js_css_version = zendkee_get_option('remove_js_css_version');
$disable_rss = zendkee_get_option('disable_rss');
$disable_pingbacks = zendkee_get_option('disable_pingbacks');
$disable_xml_rpc = zendkee_get_option('disable_xml_rpc');
$disable_google_font = zendkee_get_option('disable_google_font');
$disable_about_wordpress_icon = zendkee_get_option('disable_about_wordpress_icon');
$disable_dashboard_widget = zendkee_get_option('disable_dashboard_widget');
$disable_woocommerce_admin = zendkee_get_option('disable_woocommerce_admin');
$disable_avada_faq = zendkee_get_option('disable_avada_faq');
$disable_avada_portfolio = zendkee_get_option('disable_avada_portfolio');
$reduce_shop_page_dynamic_url = zendkee_get_option('reduce_shop_page_dynamic_url');
$disable_wp_json_and_rest_api = zendkee_get_option('disable_wp_json_and_rest_api');
$disable_notice = zendkee_get_option('disable_notice');
$disable_update_notice = zendkee_get_option('disable_update_notice');
$optimize_b2b = zendkee_get_option('optimize_b2b');
$remove_donate = zendkee_get_option('remove_donate');




$page_preload = zendkee_get_option('page_preload');
$local_avatar = zendkee_get_option('local_avatar');


?>


<form class="form" id="form-optimize">

    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>

    <div class="layui-form-item">
        <label class="layui-form-label">全选</label>
        <div class="layui-input-block">
            <input type="checkbox" name="optimize_select_all" lay-filter="optimize_select_all" value="1" lay-skin="switch"
                   lay-text="Select All|Select None">
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">禁用Emoji</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_emoji" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_emoji, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">禁用embeds</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_embeds" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_embeds, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用WP_JSON 和 REST API</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_wp_json_and_rest_api" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_wp_json_and_rest_api, '1') ? 'checked' : ''; ?> >
                </div>
            </div>


            <div class="layui-form-item">
                <label class="layui-form-label">移除feed</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_feed" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_feed, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">移除离线编辑器开放接口</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_offline_editor" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_offline_editor, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">去除本页唯一链接信息</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_index_rel_link" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_index_rel_link, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">清除前后文信息</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_prev_next" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_prev_next, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">移除相关联的文章链接</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_related_post" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_related_post, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">不检查未来的文章发布情况</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_feature_publish" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_feature_publish, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">移除WordPress版本</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_generator" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_generator, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">移除短链接</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_shortlink" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_shortlink, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">去除评论样式</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_comment_style" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_comment_style, '1') ? 'checked' : ''; ?> >
                </div>
            </div>


            <div class="layui-form-item">
                <label class="layui-form-label">前台不加载语言包</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_language_package" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_language_package, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">后台不加载语言包</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_language_package_backend" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_language_package_backend, '1') ? 'checked' : ''; ?> >
                </div>
                
            </div>




            <div class="layui-form-item">
                <label class="layui-form-label">CDN JS库</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="cdn_js_libs" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($cdn_js_libs, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">CDN JS库类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="cdn_js_libs_type" value="auto" title="自动（没有静态缓存的时候，自动判断）"> <br>
                    <input type="radio" name="cdn_js_libs_type" value="cdn.staticfile.org" title="cdn.staticfile.org（国内强悍一些）"> <span><a href="https://www.staticfile.org/" target="_blank">cdn.staticfile.org</a></span><br>
                    <input type="radio" name="cdn_js_libs_type" value="cdnjs.cloudflare.com" title="cdnjs.cloudflare.com（国外强悍一些）" checked> <span><a href="https://cdnjs.com/" target="_blank">cdnjs.cloudflare.com</a></span><br>

                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">CDN CSS库</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="cdn_css_libs" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($cdn_css_libs, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <!-- 功能不兼容Avada，禁用此功能
            <div class="layui-form-item">
                <label class="layui-form-label">页面预加载</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="page_preload" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($page_preload, '1') ? 'checked' : ''; ?> >
                    <p class="layui-bg-red">页面预加载功能和主题可能有兼容性问题，GA可能无法正确追踪页面，请谨慎打开和测试。详情参考 <a href="http://instantclick.io/">instantclick.io</a></p>
                </div>
            </div>
             -->

            <!-- 此功能没看到效果，禁用它
            <div class="layui-form-item">
                <label class="layui-form-label">本地化头像</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="local_avatar" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($local_avatar, '1') ? 'checked' : ''; ?> >
                    <p>使用一个默认的头像替换掉gravatar.com的头像</p>
                </div>
            </div>
             -->


            <div class="layui-form-item">
                <label class="layui-form-label">删除JS/CSS版本号</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="remove_js_css_version" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($remove_js_css_version, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用RSS源</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_rss" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_rss, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用自我Pingbacks</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_pingbacks" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_pingbacks, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">关闭XML-RPC</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_xml_rpc" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_xml_rpc, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用Google字体</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_google_font" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_google_font, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">去除后台WordPress信息</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_about_wordpress_icon" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_about_wordpress_icon, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">移除仪表盘(dashboard)页面加载的小工具</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_dashboard_widget" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_dashboard_widget, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用Woocommerce仪表盘和分析页面</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_woocommerce_admin" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_woocommerce_admin, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <?php if(class_exists( 'FusionCore_Plugin' )){ //Avada主题核心插件启用后才有这个选项 ?>
            <div class="layui-form-item">
                <label class="layui-form-label">禁用Avada的FAQ</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_avada_faq" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_avada_faq, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">禁用Avada的Portfolio</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_avada_portfolio" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_avada_portfolio, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <?php } ?>
            <?php if(class_exists( 'Avada_Woocommerce' )){ //Avada主题启用后才有这个选项 ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">减少Shop Page的动态链接（Avada主题适用）</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="reduce_shop_page_dynamic_url" value="1" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo zendkee_is_set($reduce_shop_page_dynamic_url, '1') ? 'checked' : ''; ?> >
                    </div>
                </div>
            <?php } ?>
            <div class="layui-form-item">
                <label class="layui-form-label">去除所有通知</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_notice" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_notice, '1') ? 'checked' : ''; ?> >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">禁止更新通知</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="disable_update_notice" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($disable_update_notice, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">B2B端优化</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="optimize_b2b" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($optimize_b2b, '1') ? 'checked' : ''; ?> >
                </div>
            </div>


            <div class="layui-form-item">
                <label class="layui-form-label">去除捐赠信息</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="remove_donate" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($remove_donate, '1') ? 'checked' : ''; ?> >
                </div>
            </div>

        </div>

    </div>


        <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>

</form>



<!--



-------------------------









====================================================
https://www.wpdaxue.com/speed-up-wordpress.html

移除某些WP自带的小工具
请注意：你可以根据自己的实际需要注释掉下面的某行或某些行：


function coolwp_remove_meta_widget() {
     unregister_widget('WP_Widget_Pages');
     unregister_widget('WP_Widget_Calendar');
     //unregister_widget('WP_Widget_Archives');
     unregister_widget('WP_Widget_Links');
     unregister_widget('WP_Widget_Meta');
    // unregister_widget('WP_Widget_Search');
      // unregister_widget('WP_Widget_Text');
     unregister_widget('WP_Widget_Categories');
     unregister_widget('WP_Widget_Recent_Posts');
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_RSS');
     unregister_widget('WP_Widget_Tag_Cloud');
     unregister_widget('WP_Nav_Menu_Widget');
    /*register my custom widget*/
    register_widget('WP_Widget_Meta_Mod');
}
add_action( 'widgets_init', 'coolwp_remove_meta_widget',11 );
上面是我根据某个项目的实际需要移除了若干小工具的代码，没被注释掉的会被移除。



custom preload : js,css,image,font
security:Content-Security-Policy
custom prefetch: for dns



@@@@@@@@@@@@@@
禁用头像


初始化数据。例如title，sub title




---->