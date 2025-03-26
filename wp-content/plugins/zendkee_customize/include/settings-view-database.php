<?php
if (!defined('ABSPATH')) {
    exit();
}
$clean_revision = zendkee_get_option('clean_revision');
$reserve_revisions = (int)zendkee_get_option('reserve_revisions') >= 0 ? (int)zendkee_get_option('reserve_revisions') : 20;


?>


<form class="form" id="form-database">

    <button class="layui-btn" lay-submit="" lay-filter="database">执行</button>

    <div class="layui-form-item">
        <label class="layui-form-label">全选</label>
        <div class="layui-input-block">
            <input type="checkbox" name="optimize_select_all" lay-filter="optimize_select_all" value="1" lay-skin="switch"
                   lay-text="Select All|Select None">
        </div>

        <div class="optimize child">
            <div class="layui-form-item">
                <label class="layui-form-label">清理历史版本数据</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="clean_revision" value="1" lay-skin="switch"
                           lay-text="ON|OFF" <?php echo zendkee_is_set($clean_revision, '1') ? 'checked' : ''; ?> >
                </div>

                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux">保留历史版本数</div>
                    <input type="number" name="reserve_revisions" value="<?php echo $reserve_revisions?>" />
                </div>
            </div>

        </div>

    </div>


    <button class="layui-btn" lay-submit="" lay-filter="database">执行</button>

</form>