<?php
if (!defined('ABSPATH')) {
    exit();
}

$enable_ip_rule = zendkee_get_option('enable_ip_rule');
$ip_rule = zendkee_get_option('ip_rule');
$ip_white_list = zendkee_get_option('ip_white_list') ? zendkee_get_option('ip_white_list') : array();
$ip_black_list = zendkee_get_option('ip_black_list') ? zendkee_get_option('ip_black_list') : array();
$enable_ivc_debug_mode = zendkee_get_option('enable_ivc_debug_mode');


?>
<form class="form" id="form-ip-view-control">
    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>

    <div class="layui-form-item">
        <label class="layui-form-label">启用IP规则</label>
        <div class="layui-input-block">
            <input type="checkbox" name="enable_ip_rule" value="1" lay-text="ON|OFF" lay-skin="switch" <?php echo zendkee_is_set($enable_ip_rule, '1') ? 'checked' : ''; ?>>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">主规则</label>
        <div class="layui-input-block">
            <input type="radio" name="ip_rule" value="only_white_list" title="Only White List" <?php echo zendkee_is_set($ip_rule, 'only_white_list') ? 'checked' : ''; ?>>
            <input type="radio" name="ip_rule" value="only_black_list" title="Only Black List" <?php echo zendkee_is_set($ip_rule, 'only_black_list') ? 'checked' : ''; ?>>
            <input type="radio" name="ip_rule" value="white_list" title="White List" <?php echo zendkee_is_set($ip_rule, 'white_list') ? 'checked' : ''; ?>>
            <input type="radio" name="ip_rule" value="black_list" title="Black List" <?php echo zendkee_is_set($ip_rule, 'black_list') ? 'checked' : ''; ?>>
        </div>

        <div class="layui-input-block">
            <blockquote class="layui-elem-quote layui-quote-nm">
                Only White List: 只允许白名单IP访问网站；再配合Region View Control使用,再做区域的判断（交集）<br>
                Only Black List: 只拦截黑名单IP访问网站；再配合Region View Control使用,再做区域的判断（交集）<br>
                White List: 允许白名单IP访问网站；配合Region View Control使用，当拦截的区域中，出现白名单IP，放行该IP（并集）<br>
                Black List: 拦截黑名单IP访问网站；配合Region View Control使用，当允许的区域中，出现黑名单IP，拦截该IP（并集）
            </blockquote>
        </div>
    </div>


    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">白名单IP列表</label>
        <div class="layui-input-block">
            <textarea name="ip_white_list" placeholder="输入IP地址，每行一个IP" class="layui-textarea" rows="10"><?php echo implode("\n", $ip_white_list); ?></textarea>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">黑名单IP列表</label>
        <div class="layui-input-block">
            <textarea name="ip_black_list" placeholder="输入IP地址，每行一个IP" class="layui-textarea" rows="10"><?php echo implode("\n", $ip_black_list); ?></textarea>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">Enable Debug Mode: </label>
        <div class="layui-input-block">
            <input type="checkbox" id="enable_ivc_debug_mode" name="enable_ivc_debug_mode" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($enable_ivc_debug_mode, '1') ? 'checked' : ''; ?>>
        </div>
    </div>

    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>
</form>