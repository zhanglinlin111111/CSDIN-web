<?php
if (!defined('ABSPATH')) {
    exit();
}
$ze_hide_product_main_description = zendkee_get_option('ze_hide_product_main_description');
$ze_redirect_to_elementor_editor = zendkee_get_option('ze_redirect_to_elementor_editor');
$ze_progress_bar_color = zendkee_get_option('ze_progress_bar_color');
$ze_product_list_menu_dropdown = zendkee_get_option('ze_product_list_menu_dropdown');
$ze_product_detail_gallery_carousel = zendkee_get_option('ze_product_detail_gallery_carousel');
$display_product_archive_short_desc = zendkee_get_option('display_product_archive_short_desc');
$customize_avatar = zendkee_get_option('customize_avatar');
$compatible_everest_admin_theme = zendkee_get_option('compatible_everest_admin_theme');
$ze_product_detail_inquiry_shortcode_enable = zendkee_get_option('ze_product_detail_inquiry_shortcode_enable');
$ze_product_detail_inquiry_shortcode = zendkee_get_option('ze_product_detail_inquiry_shortcode');


?>


<form class="form" id="form-base">

    <button class="layui-btn" lay-submit="" lay-filter="base">保存</button>

    <div class="layui-form-item">
        <label class="layui-form-label">全选</label>
        <div class="layui-input-block">
            <input type="checkbox" name="base_select_all" lay-filter="base_select_all" value="1" lay-skin="switch" lay-text="Select All|Select None">
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">隐藏产品编辑后台主编辑器</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="ze_hide_product_main_description" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($ze_hide_product_main_description, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">后台首页登录，默认进入首页的elementor编辑器</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="ze_redirect_to_elementor_editor" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($ze_redirect_to_elementor_editor, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">进度条颜色</label>
            <div class="layui-input-block">
                <div id="ze_progress_bar_color_selector"></div>
                <input type="hidden" name="ze_progress_bar_color" autocomplete="off" class="layui-input" value="<?php zk_e($ze_progress_bar_color); ?>">
            </div>
        </div>


        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">产品列表页侧边栏菜单下拉样式</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="ze_product_list_menu_dropdown" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($ze_product_list_menu_dropdown, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">产品详情页图库轮播</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="ze_product_detail_gallery_carousel" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($ze_product_detail_gallery_carousel, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">产品详情页-加购物车按钮-加入询盘按钮</label>
                <div class="layui-input-block w30">
                    启用： <input type="checkbox" name="ze_product_detail_inquiry_shortcode_enable" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($ze_product_detail_inquiry_shortcode_enable, '1') ? 'checked' : ''; ?>>
                    <br>
                    <input type="text" name="ze_product_detail_inquiry_shortcode" autocomplete="off" class="layui-input" value="<?php zk_e($ze_product_detail_inquiry_shortcode); ?>">
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">产品分类页显示产品短描述</label>
                <div class="layui-input-block">
                    <select name="display_product_archive_short_desc">
                        <option value="0" <?php echo zendkee_is_set($display_product_archive_short_desc, '0') ? 'selected' : ''; ?>>不显示</option>
                        <option value="display_product_archive_short_desc_before_product_title" <?php echo zendkee_is_set($display_product_archive_short_desc, 'display_product_archive_short_desc_before_product_title') ? 'selected' : ''; ?>>短描述位于产品名上方</option>
                        <option value="display_product_archive_short_desc_after_product_title" <?php echo zendkee_is_set($display_product_archive_short_desc, 'display_product_archive_short_desc_after_product_title') ? 'selected' : ''; ?>>短描述位于产品名下方</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">使用自定义Avata头像</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="customize_avatar" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($customize_avatar, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">适配主题Everest Admin Theme</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="compatible_everest_admin_theme" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($compatible_everest_admin_theme, '1') ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>

    </div>


    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>

</form>