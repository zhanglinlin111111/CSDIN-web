<?php
if (!defined('ABSPATH')) {
    exit();
}

global $status_code_list;
global $isocode_list;
global $isocode_cn_list;



//dp($status_code_list);
//dp($isocode_list);
//dp($isocode_cn_list);

$enable_region_rule = zendkee_get_option('enable_region_rule');
$svc_rule = zendkee_get_option('svc_rule');
$country_status_code = zendkee_get_option('country_status_code');
$enable_svc_debug_mode = zendkee_get_option('enable_svc_debug_mode');


//dp($svc_rule);

$html = '<form class="form" id="form-region-view-control">
    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>';

if (maybe_cache_plugin_install()) {
    $html .= sprintf('<div class="svc_notice">注意:你可能启用了一个缓存插件.则"Region View Control"这个功能可能不能正常工作。</div>');
}


$html .= sprintf('<div class="layui-form-item">
<label class="layui-form-label">启用Region规则</label>
<div class="layui-input-block">
    <input type="checkbox" name="enable_region_rule" value="1" lay-text="ON|OFF"
           lay-skin="switch" %s>
</div>
</div>', zendkee_is_set($enable_region_rule, '1') ? 'checked' : '');

//display main rule switcher
$html .= sprintf('        
        <div class="main">
        
        <div style="margin: 2em;">
        <i>
        使用说明： <br>
        1.先选择主规则 <br>
        2.再针对国家选择访问状态 <br>
        </i>
        </div>
        
        <h2>主规则: </h2><br>
        允许某些国家访问本站:<input name="svc_rule" type="radio" class="main_rule" value="Allow" %s ><br>
        禁止某些国家访问本站:<input name="svc_rule" type="radio"  class="main_rule" value="Disallow" %s >
        
        
        
        </div>
        
        
        
        ', ($svc_rule == 'Allow' ? 'checked' : ''), ($svc_rule == 'Disallow' ? 'checked' : ''));


//display selected rule
$rule_status_code = array_search($svc_rule, $status_code_list);
if (!empty($country_status_code)) {
    $selected_rule_html = '<table class="selected_rule layui-table">
            <thead>
                <tr>
                    <th>国家</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($country_status_code as $country_code => $status_code) {
        if($status_code == $rule_status_code){
            $selected_rule_html .= sprintf('<tr>
			<td>%s</td>
			<td>%s</td>
			<td><a href="#" class="delete_rule layui-btn layui-btn-sm layui-btn-danger" data-action="delete" data-country_code="%s">Delete</a></td>
		</tr>', $isocode_list[$country_code], $status_code_list[$status_code], $country_code);
        }


    }
    $selected_rule_html .= '	</tbody>
            </table>';
    $html .= sprintf('<div class="selected_rule_container"><h2>已启用规则:</h2><br> %s</div>', $selected_rule_html);
}


//display country code and status code list
$html .= sprintf('<div class="country_list_container">
        <h2>国家列表:</h2>
        <table class="country_list layui-table">
            <thead>
            <tr>
                <th>国家 (英文名)</th>
                <th>国家 (中文名)</th>
                <th>状态码</th>
            </tr>
            </thead>
            <tbody>');


//dp($status_code_list);
foreach ($isocode_list as $country_code => $country_name) {
    $status_code_html = '<option value=""></option>';

    foreach ($status_code_list as $code => $desc) {
//        dp($svc_rule);
//        dp($desc);
        if($svc_rule == $desc){
            $status_code_html .= sprintf('<option value="%s" %s>%s %s</option>', $code, (isset($country_status_code[$country_code]) && $country_status_code[$country_code] == $code ? 'selected' : ''), $desc, $code);

        }
    }
    $status_code_html = sprintf('<select name="country_status_code[%s]" class="country_status_code" lay-verify="">%s</select>', $country_code, $status_code_html);


//    $action_html = sprintf('<a href="#" class="update_rule" data-action="update" data-country_code="%s">UPDATE</a>', $country_code);


//    $html .= sprintf('<tr>
//            <td>%s <span class="layui-bg-red">%s</span></td>
//            <td>%s</td>
//            <td>%s</td>
//        </tr>', $country_name, (isset($country_status_code[$country_code]) ? $country_status_code[$country_code] : ''), $isocode_cn_list[$country_code], $status_code_html);

    $html .= sprintf('<tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
        </tr>', $country_name, $isocode_cn_list[$country_code], $status_code_html);
}

$html .= sprintf('</tbody>
        <tfoot>
        <tr>
            <th>国家 (英文名)</th>
            <th>国家 (中文名)</th>
            <th>状态码</th>
        </tr>
        </tfoot>
    </table></div>');


//debug mode
//$enable_svc_debug_mode = zendkee_get_option('enable_svc_debug_mode');


$html .= sprintf('
<div class="layui-form-item">
    <label class="layui-form-label">启用调试模式: </label>
    <div class="layui-input-block">
      <input type="checkbox" id="enable_svc_debug_mode" name="enable_svc_debug_mode" value="1" lay-skin="switch" lay-text="ON|OFF" lay-filter="debug" %s >
    </div>
  </div>', $enable_svc_debug_mode == 1 ? ' checked' : '');


$html .= '<button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>

</form>';


echo $html;
