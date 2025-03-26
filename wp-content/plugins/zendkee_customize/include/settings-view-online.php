<?php
if (!defined('ABSPATH')) {
    exit();
}



?>


<button class="layui-btn layui-btn-danger online_wp" lay-submit="" lay-filter="online">执行操作</button>

<form class="form" id="form-online">
    <div class="layui-collapse" lay-accordion>

        <div class="layui-colla-item">

            <?php
            if(!defined('WP_HOME') or !defined('WP_SITEURL')){
                printf('<div class="layui-bg-red replace_notice">网站未定义"WP_HOME"和"WP_SITEURL"，URL替换后，新网址可能打不开</div>');
            }
            ?>




            <h2 class="layui-colla-title">URL替换</h2>
            <div class="layui-colla-content layui-show">

                <div class="layui-form-item">
                    <label class="layui-form-label">替换URL</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="replace_url" value="1" lay-skin="switch"
                               lay-text="ON|OFF" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">旧URL</label>
                    <label for=""></label>
                    <div class="layui-input-inline w30">
                        <input type="text" name="old_url" data-msg="旧URL" class="url_to_check layui-input" value="" />
                    </div>
                    <div class="layui-input-inline">
                        <select name="scheme_old" lay-filter="scheme_old">
                            <option value="">请选择协议</option>
                            <option value="http">HTTP</option>
                            <option value="https">HTTPS</option>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-primary guess_old_url" lay-submit="" lay-filter="">猜一猜</button>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">新URL</label>
                    <div class="layui-input-inline w30">
                        <input type="text" name="new_url" data-msg="新URL" class="url_to_check layui-input" value="" />
                    </div>
                    <div class="layui-input-inline">
                        <select name="scheme_new" lay-filter="scheme_new">
                            <option value="">请选择协议</option>
                            <option value="http">HTTP</option>
                            <option value="https">HTTPS</option>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-primary get_current_url" lay-submit="" lay-filter="">当前URL</button>
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-">
                        <button class="layui-btn layui-btn-primary check_url" lay-submit="" lay-filter="">使用建议的URL</button>
                    </div>
                </div>
            </div>
        </div>



    </div>

</form>
<button class="layui-btn layui-btn-danger online_wp" lay-submit="" lay-filter="online">执行操作</button>



