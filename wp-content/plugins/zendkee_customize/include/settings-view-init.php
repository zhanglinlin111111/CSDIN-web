<?php
if (!defined('ABSPATH')) {
    exit();
}


$clear_google_code = zendkee_get_option('clear_google_code');
$clear_smtp_settings = zendkee_get_option('clear_smtp_settings');
$clear_mail_settings = zendkee_get_option('clear_mail_settings');
$clear_inquiry_log = zendkee_get_option('clear_inquiry_log');
$clear_seo_log = zendkee_get_option('clear_seo_log');
$clear_avada_elements = zendkee_get_option('clear_avada_elements');
$optimize_avada = zendkee_get_option('optimize_avada');
$disable_all_image_dimensions = zendkee_get_option('disable_all_image_dimensions');
$clear_settings = zendkee_get_option('clear_settings');


?>


<button class="layui-btn layui-btn-danger init_wp" lay-submit="" lay-filter="init">执行初始化操作</button>

<form class="form" id="form-init">
    <div class="layui-collapse" lay-accordion>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">数据库清理</h2>
            <div class="layui-colla-content layui-show">

                <label class="layui-form-label">全选</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="init_select_all" lay-filter="init_select_all" value="1"
                           lay-skin="switch"
                           lay-text="Select All|Select None">
                </div>

                <div class="init child">
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_google_code" lay-skin="primary"
                               lay-filter="clear_google_code" title="清空GSC/GA/GTM/GSC代码"
                               value="1" <?php echo(zendkee_is_set($clear_google_code, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_smtp_settings" lay-skin="primary"
                               lay-filter="clear_smtp_settings" title="清空SMTP设置（WP Mail SMTP）"
                               value="1" <?php echo(zendkee_is_set($clear_smtp_settings, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_mail_settings" lay-skin="primary"
                               lay-filter="clear_mail_settings" title="清空Mail设置（Contact Form 7）"
                               value="1" <?php echo(zendkee_is_set($clear_mail_settings, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_inquiry_log" lay-skin="primary"
                               lay-filter="clear_inquiry_log"
                               title="清空询盘历史记录（Contact Form CFDB7、Advanced CF7 DB、WPForms）"
                               value="1" <?php echo(zendkee_is_set($clear_inquiry_log, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_seo_log" lay-skin="primary" lay-filter="clear_seo_log"
                               title="清空SEO优化记录（Yoast SEO、All In One SEO Pack）"
                               value="1" <?php echo(zendkee_is_set($clear_seo_log, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_avada_elements" lay-skin="primary"
                               lay-filter="clear_avada_elements" title="禁用Avada不常用组件"
                               value="1" <?php echo(zendkee_is_set($clear_avada_elements, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="optimize_avada" lay-skin="primary"
                               lay-filter="clear_avada_elements" title="禁用Avada不常用功能"
                               value="1" <?php echo(zendkee_is_set($clear_avada_elements, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="disable_all_image_dimensions" lay-skin="primary"
                               lay-filter="disable_all_image_dimensions" title="禁用所有图像尺寸"
                               value="1" <?php echo(zendkee_is_set($disable_all_image_dimensions, 1) ? 'checked' : ''); ?> />
                    </div>
                    <div class="layui-input-block">
                        <input type="checkbox" name="clear_settings" lay-skin="primary"
                               lay-filter="clear_settings" title="清空网站配置"
                               value="1" <?php echo(zendkee_is_set($clear_settings, 1) ? 'checked' : ''); ?> />
                    </div>
                </div>


                <hr class="layui-bg-blue">


            </div>
        </div>


        <!--        <div class="layui-colla-item">-->
        <!--            <h2 class="layui-colla-title">图片清理</h2>-->
        <!--            <div class="layui-colla-content">-->
        <!---->
        <!---->
        <!---->
        <!--                <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="scan_images" id="scan_images">扫描文件</button>-->
        <!--                <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="scan_db" id="scan_db">扫描数据库</button>-->
        <!---->
        <!---->
        <!--            </div>-->
        <!--        </div>-->

    </div>

</form>
<button class="layui-btn layui-btn-danger init_wp" lay-submit="" lay-filter="init">执行初始化操作</button>



