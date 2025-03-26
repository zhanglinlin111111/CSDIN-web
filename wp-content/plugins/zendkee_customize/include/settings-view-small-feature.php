<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

$disable_f12 = zendkee_get_option('disable_f12');
$disable_rightclick = zendkee_get_option('disable_rightclick');
$disable_lang_cn = zendkee_get_option('disable_lang_cn');
$disable_search = zendkee_get_option('disable_search');
$disable_author_page = zendkee_get_option('disable_author_page');

?>

<form class="form" id="form-small-feature">
    <button class="layui-btn" lay-submit="" lay-filter="small-feature">保存</button>
    <div class="layui-form-item">
        <label class="layui-form-label">禁用F12</label>
        <div class="layui-input-block">
            <input type="checkbox" class="small-feature" name="disable_f12" lay-skin="switch" lay-text="ON|OFF" lay-filter="small_feature" value="1" <?php echo zendkee_is_set($disable_f12, '1') ? 'checked' : ''; ?> ><div class="layui-unselect layui-form-switch" lay-skin="_switch"></div>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">禁用右键</label>
        <div class="layui-input-block">
            <input type="checkbox" class="small-feature" name="disable_rightclick" lay-skin="switch" lay-text="ON|OFF" lay-filter="small_feature" value="1" <?php echo zendkee_is_set($disable_rightclick, '1') ? 'checked' : ''; ?> ><div class="layui-unselect layui-form-switch" lay-skin="_switch"></div>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">禁止中文浏览器访问</label>
        <div class="layui-input-block">
            <input type="checkbox" class="small-feature" name="disable_lang_cn" lay-skin="switch" lay-text="ON|OFF" lay-filter="small_feature" value="1" <?php echo zendkee_is_set($disable_lang_cn, '1') ? 'checked' : ''; ?> ><div class="layui-unselect layui-form-switch" lay-skin="_switch"></div>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">关闭搜索功能</label>
        <div class="layui-input-block">
            <input type="checkbox" class="small-feature" name="disable_search" lay-skin="switch" lay-text="ON|OFF" lay-filter="small_feature" value="1" <?php echo zendkee_is_set($disable_search, '1') ? 'checked' : ''; ?> ><div class="layui-unselect layui-form-switch" lay-skin="_switch"></div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关闭作者页</label>
        <div class="layui-input-block">
            <input type="checkbox" class="small-feature" name="disable_author_page" lay-skin="switch" lay-text="ON|OFF" lay-filter="small_feature" value="1" <?php echo zendkee_is_set($disable_author_page, '1') ? 'checked' : ''; ?> ><div class="layui-unselect layui-form-switch" lay-skin="_switch"></div>
        </div>
    </div>

    <button class="layui-btn" lay-submit="" lay-filter="small-feature">保存</button>

</form>

