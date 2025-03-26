<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

?>



    <div class="layui-container">
        <div class="layui-row">
            <div class="layui-col-md6">
                <form class="form" id="form-compatible">
                    <input type="hidden" name="compatible_action" value="import">
                <input type="submit" class="layui-btn" name="compatible_import" value="导入旧版数据">
                </form>
            </div>
            <div class="layui-col-md6">
                <form class="form" id="form-compatible">
                    <input type="hidden" name="compatible_action" value="remove">
                    <input type="submit" class="layui-btn layui-btn-danger" name="compatible_remove" value="删除旧版数据">

                </form>
            </div>
        </div>

    </div>



