/*
* for settings only
* */
jQuery(document).ready(function ($) {
    layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element'], function () {
        // var laypage = layui.laypage ;//分页
        var layer = layui.layer;//弹层
        var table = layui.table;//表格
        var form = layui.form;//表单


        // var laydate = layui.laydate ;//日期
        // var carousel = layui.carousel ;//轮播
        // var upload = layui.upload ;//上传
        var element = layui.element ; //元素操作 等等...

        //监听tab切换
        //获取hash来切换选项卡，假设当前地址的hash为lay-id对应的值
        var layid = location.hash.replace(/^#zendkee_elementor_tabs=/, '');
        element.tabChange('zendkee_elementor_tabs', layid); //假设当前地址为：http://a.com#test1=222，那么选项卡会自动切换到“发送消息”这一项

        //监听Tab切换，以改变地址hash值
        element.on('tab(zendkee_elementor_tabs)', function () {
            location.hash = 'zendkee_elementor_tabs=' + this.getAttribute('lay-id');
        });




        //监听optimize的全选/反选
        layui.use(['form', 'jquery'], function () {
            var form = layui.form;
            var $ = layui.jquery;
            //点击全选, 勾选
            form.on('switch(base_select_all)', function (data) {
                var child = $(".optimize.child input[type='checkbox']");
                child.each(function (index, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });




        //进度条颜色选择器
        layui.use('colorpicker', function(){
            var colorpicker = layui.colorpicker;
            //渲染
            colorpicker.render({
                elem: '#ze_progress_bar_color_selector'  //绑定元素
                ,done: function(color){
                    jQuery('input[name=ze_progress_bar_color]').val(color);
                }
                ,color: jQuery('input[name=ze_progress_bar_color]').val() //默认颜色
                ,predefine: true
                ,colors:['#29d'] //预定义颜色
            });

        });


    });



    $(document).on('submit', '#form-base', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_elementor_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_elementor_base&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });




});

