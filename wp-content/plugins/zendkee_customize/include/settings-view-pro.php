<?php
if (!defined('ABSPATH')) {
    exit();
}

$enable_pro = zendkee_get_option('enable_pro');

?>


<form class="form" id="form-pro">


    <h2>专业版功能</h2>


    <?php if(!$enable_pro){ ?>
    <div class="layui-form-item">
        <label class="layui-form-label">专业版Key</label>
        <div class="layui-input-inline">
            <input type="text" name="pro_key" class="layui-input" value="" placeholder="易海最帅后端是谁"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <button class="layui-btn enable_pro" data-status="enable" lay-submit="" lay-filter="">启用专业版</button>
    </div>
    <?php } else{ ?>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <button class="layui-btn layui-btn-danger disable_pro" data-status="disable" lay-submit="" lay-filter="">关闭专业版</button>
        </div>
    <?php } ?>



</form>



