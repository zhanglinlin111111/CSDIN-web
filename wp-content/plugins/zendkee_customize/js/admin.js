jQuery(document).ready(function ($) {
    var special_page_seo_item_backup = null;

    // var seo_select = null;


    //获取光标位置
    (function($) {
        $.fn.getCursorPosition = function() {
            var input = this.get(0);
            if (!input) return; // No (input) element found
            if ('selectionStart' in input) {
                // Standard-compliant browsers
                return input.selectionStart;
            } else if (document.selection) {
                // IE
                input.focus();
                var sel = document.selection.createRange();
                var selLen = document.selection.createRange().text.length;
                sel.moveStart('character', -input.value.length);
                return sel.text.length - selLen;
            }
        }
    })(jQuery);

    //设置光标位置
    (function($) {
        $.fn.setCursorPosition = function(pos) {
            if (this.setSelectionRange) {
                this.setSelectionRange(pos, pos);
            } else if (this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                if(pos < 0) {
                    pos = $(this).val().length + pos;
                }
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        }
    })(jQuery);


    //ltrim函数
    String.prototype.ltrim = function (c) {
        if (!c) {
            c = ' ';
        }
        var reg = new RegExp('(^[' + c + ']*)', 'gi');
        return this.replace(reg, '');
    };

    //rtrim函数
    String.prototype.rtrim = function (c) {
        if (!c) {
            c = ' ';
        }
        var reg = new RegExp('([' + c + ']*$)', 'gi');
        return this.replace(reg, '');
    };








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
        var layid = location.hash.replace(/^#zendkee_custom_tabs=/, '');
        element.tabChange('zendkee_custom_tabs', layid); //假设当前地址为：http://a.com#test1=222，那么选项卡会自动切换到“发送消息”这一项

        //监听Tab切换，以改变地址hash值
        element.on('tab(zendkee_custom_tabs)', function () {
            location.hash = 'zendkee_custom_tabs=' + this.getAttribute('lay-id');
        });



        //
        // //监听layer的form表单下的事件
        // //监听杂项
        // form.on('switch(small_feature)', function(data){
        //     // console.log(data.elem); //得到checkbox原始DOM对象
        //     // console.log(data.elem.checked); //开关是否开启，true或者false
        //     // console.log(data.value); //开关value值，也可以通过data.elem.value得到
        //     // console.log(data.othis); //得到美化后的DOM对象
        //
        //     var field = data.value;
        //     var value = data.elem.checked;
        //
        //
        //     $.ajax({
        //         type: "POST",
        //         url: zendkee_customize_js_object.ajax_url,
        //         dataType: 'json',
        //         data: {
        //             action: 'zendkee_small_feature',
        //             field: field,
        //             value:value,
        //         },
        //         beforeSend: function () {
        //             layer.msg('Loading...', {icon: 4, time: 0});
        //         },
        //     }).done(function (msg) {
        //         if (msg.status == 'ok') {
        //             layer.msg(msg.info , {
        //                 icon: 1,
        //                 time: 3000 //2秒关闭（如果不配置，默认是3秒）
        //             }, function(){
        //                 //do something
        //             });
        //
        //         }else{
        //             layer.msg(msg.info , {
        //                 icon: 2,
        //                 time: 3000 //2秒关闭（如果不配置，默认是3秒）
        //             }, function(){
        //                 //do something
        //             });
        //         }
        //     });
        //
        //
        // });


        //监听optimize的全选/反选
        layui.use(['form', 'jquery'], function () {
            var form = layui.form;
            var $ = layui.jquery;
            //点击全选, 勾选
            form.on('switch(optimize_select_all)', function (data) {
                var child = $(".optimize.child input[type='checkbox']");
                child.each(function (index, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });


        //监听init的全选/反选
        layui.use(['form', 'jquery'], function () {
            var form = layui.form;
            var $ = layui.jquery;
            //点击全选, 勾选
            form.on('switch(init_select_all)', function (data) {
                var child = $(".init.child input[type='checkbox']");
                child.each(function (index, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });


        //监听misc的禁用图像尺寸 全选/全不选
        layui.use(['form', 'jquery'], function () {
            var form = layui.form;
            var $ = layui.jquery;
            //点击全选, 勾选
            form.on('switch(image_size_select_all)', function (data) {
                var child = $(".disable_image_size input[type='checkbox']");
                child.each(function (index, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });


        //misc中的浮动表单按钮和置顶按钮的颜色选择器
        layui.use('colorpicker', function(){
            var colorpicker = layui.colorpicker;
            //渲染
            //表单选择按钮背景颜色
            colorpicker.render({
                elem: '#form_select_button_bg_color_selector'  //绑定元素
                , done: function (color) {
                    jQuery('input[name=form_select_button_bg_color]').val(color);
                }
                // ,predefine: true
                , color: jQuery('input[name=form_select_button_bg_color]').val()
                , predefine: true
                , colors: ['#f00'] //预定义颜色
            });
            //表单提交按钮背景颜色
            colorpicker.render({
                elem: '#form_submit_button_bg_color_selector'  //绑定元素
                , done: function (color) {
                    jQuery('input[name=form_submit_button_bg_color]').val(color);
                }
                // ,predefine: true
                , color: jQuery('input[name=form_submit_button_bg_color]').val()
                , predefine: true
                , colors: ['#f00'] //预定义颜色
            });

            colorpicker.render({
                elem: '#float_button_base_color_selector'  //绑定元素
                ,done: function(color){
                    jQuery('input[name=float_button_base_color]').val(color);
                }
                ,color: jQuery('input[name=float_button_base_color]').val() //默认颜色
                ,predefine: true
                ,colors:['#555'] //预定义颜色
            });
            colorpicker.render({
                elem: '#float_button_bg_color_selector'  //绑定元素
                ,done: function(color){
                    jQuery('input[name=float_button_bg_color]').val(color);
                }
                // ,predefine: true
                ,color: jQuery('input[name=float_button_bg_color]').val()
                ,predefine: true
                ,colors:['#fff'] //预定义颜色
            });
        });




        //misc中，字体排序
        $(".fonts_list_container").sortable();
        //添加字体
        $(document).on("click",".add_font",function(e){
            e.preventDefault();
            $(".fonts_list_container").append($("#font-add-template").html());

        });
        //删除字体
        $(document).on("click",".remove_font",function(e){
            e.preventDefault();
            $(this).parents(".sortable").remove();
        });



        //监听Online中的URL协议选择
        //Online 检查url
        form.on('select(scheme_old)', function (data) {

            var old_url = $('input[name=old_url]').val();
            old_url = old_url.trim();
            if(old_url.match(/^\/\//)){
                old_url = data.value + ":" + old_url;
            }else if(old_url.match(/^https?:\/\//i)){
                old_url = old_url.replace(/^https?/i , data.value);
            }else{
                old_url = data.value + "://" + old_url;
            }

            $('input[name=old_url]').val(old_url)
        });

        form.on('select(scheme_new)', function (data) {

            var new_url = $('input[name=new_url]').val();
            new_url = new_url.trim();
            if(new_url.match(/^\/\//)){
                new_url = data.value + ":" + new_url;
            }else if(new_url.match(/^https?:\/\//i)){
                new_url = new_url.replace(/^https?/i , data.value);
            }else{
                new_url = data.value + "://" + new_url;
            }

            $('input[name=new_url]').val(new_url)
        });



        // //监听debug
        // form.on('switch(debug)', function(data){
        //     var enable_svc_debug_mode = data.elem.checked;
        //
        //     $.ajax({
        //         type: "POST",
        //         url: zendkee_customize_js_object.ajax_url,
        //         dataType: 'json',
        //         data: {
        //             action: 'zendkee_debug_mode',
        //             enable_svc_debug_mode: enable_svc_debug_mode,
        //         },
        //         beforeSend: function () {
        //             layer.msg('Loading...', {icon: 4, time: 0});
        //         },
        //     }).done(function (msg) {
        //         if (msg.status == 'ok') {
        //             layer.msg(msg.info , {
        //                 icon: 1,
        //                 time: 3000 //2秒关闭（如果不配置，默认是3秒）
        //             });
        //
        //         }else{
        //             layer.msg(msg.info , {
        //                 icon: 2,
        //                 time: 3000 //2秒关闭（如果不配置，默认是3秒）
        //             });
        //         }
        //     });
        // });



        //seo: special page change select
        form.on('select(special_page_seo_page)', function (data) {
            var $ = layui.jquery;

            var selected_data_title = $(data.elem).find("option:selected").data('title');
            var site_type = $('input[name=site_type]:checked').val();

            var search_template = null;

            var found = false;

            if(site_type == 'tob'){
                search_template = seo_template.tob;
            }else{
                search_template = seo_template.toc;
            }


            for (const [key, item] of Object.entries(search_template)) {
                reg = new RegExp(key,'i');
                if(selected_data_title.match(reg)){

                    $(data.elem).parents('.special_page_seo_item').find('.special_page_seo_title').val(item.title);
                    $(data.elem).parents('.special_page_seo_item').find('.special_page_seo_description').val(item.description);

                    found = true;
                    break;
                }
            }


            if(!found){
                $(data.elem).parents('.special_page_seo_item').find('.special_page_seo_title').val('');
                $(data.elem).parents('.special_page_seo_item').find('.special_page_seo_description').val('');
            }

        });


    });


    $(document).on('submit', '#form-region-view-control', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_region_view_control&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                if (msg.status == 'ok') {
                    location.reload();

                }

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });


    // $(document).on('click',".update_rule",function () {//update / delete rule
    //     event.preventDefault();
    //     var country_code=$(this).data('country_code');
    //     var status_code=$(this).parents("tr").find(".status_code").val();
    //     $.ajax({
    //         type:"POST",
    //         url: zendkee_customize_js_object.ajax_url,
    //         dataType:'json',
    //         data: {
    //             action:'zendkee_site_view_action',
    //             do:'update',
    //             country_code:country_code,
    //             status_code:status_code
    //         },
    //         beforeSend:function () {
    //             layer.msg('Loading...', {icon: 4 , time: 0});
    //         },
    //     }).done(function(msg){
    //         if(msg.status=='ok'){
    //             location.reload();
    //
    //         }
    //     });
    // });
    //


    //delete rule
    $(document).on('click', ".delete_rule", function () {//update / delete rule
        event.preventDefault();
        var country_code = $(this).data('country_code');
        var status_code = '';
        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: {
                action: 'zendkee_svc_delete_rule',
                country_code: country_code,
                // status_code:status_code
            },
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                location.reload();

            }
        });
        return false;
    });
    //
    //
    // $(document).on('change','.main_rule',function () {//update main rule
    //    event.preventDefault();
    //    var rule = $(this).val();
    //    $.ajax({
    //        type:"POST",
    //        url: zendkee_customize_js_object.ajax_url,
    //        dataType:'json',
    //        data: {
    //            action:'zendkee_site_view_action',
    //            do:'update_main_rule',
    //            rule:rule,
    //        },
    //        beforeSend:function () {
    //            layer.msg('Loading...', {icon: 4 , time: 0});
    //        },
    //    }).done(function(msg){
    //        if(msg.status=='ok'){
    //            location.reload();
    //
    //        }
    //    });
    // });
    //
    //
    // $(document).on('change','#enable_svc_debug_mode',function(){
    //     // event.preventDefault();
    //     if ($(this).is(":checked"))
    //     {
    //         var enable_svc_debug_mode = 1;
    //     }else{
    //         var enable_svc_debug_mode = 0;
    //     }
    //     // var enable_svc_debug_mode = $(this).val();
    //     $.ajax({
    //         type:"POST",
    //         url: zendkee_customize_js_object.ajax_url,
    //         dataType:'json',
    //         data: {
    //             action:'zendkee_debug_mode',
    //             enable_svc_debug_mode:enable_svc_debug_mode,
    //         },
    //         beforeSend:function () {
    //             layer.msg('Loading...', {icon: 4 , time: 0});
    //         },
    //     }).done(function(msg){
    //         if(msg.status=='ok'){
    //             location.reload();
    //
    //         }
    //     });
    // });


    $(document).on('submit', '#form-ip-view-control', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_ip_view_control&' + FORM.serialize(),
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


    $(document).on('submit', '#form-optimize', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_form_optimize&' + FORM.serialize(),
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


    $(document).on('submit', '#form-small-feature', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_small_feature&' + FORM.serialize(),
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


    $(document).on('submit', '#form-misc', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_misc&' + FORM.serialize(),
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




    //seo
    $(document).on('click','.add_special_page_seo_item' , function (e) {
        e.preventDefault();
        var item = null;
        console.log(special_page_seo_item_backup);

        if(special_page_seo_item_backup!=null){
            item = special_page_seo_item_backup.clone();
        }else{
            item = $('.special_page_seo_container > .special_page_seo_item:eq(0)').clone();
        }
        item.find('select.special_page_seo_page').val('');
        item.find('input.special_page_seo_title').val('');
        item.find('textarea.special_page_seo_description').val('');
        $('.special_page_seo_container').append(item);
        // item = null;
        layui.use(['form'], function () {
            layui.form.render('select');
        });

    });

    //seo delete item
    $(document).on('click' , '.remove_special_page_seo_item' , function(e){
        e.preventDefault();
        if($('.special_page_seo_item').length==1){
            special_page_seo_item_backup = $('.special_page_seo_item');
            console.log(special_page_seo_item_backup);
        }
        $(this).parents('.special_page_seo_item').remove();
    });

    //


    //seo submit
    $(document).on('submit', '#form-seo', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_seo&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                if(msg.action=='reload'){
                    location.reload();
                }

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });


    var tips;
    $('.seo_available_tag span.layui-btn').on({
        mouseenter:function(){
            var that = this;
            usage = $(this).data('usage');
            // console.log(usage);
            tips =layer.tips("<span style='color:#000;'>"+ usage +"</span>",that,{tips:[1,'#fff'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });




    //seo tag
    //点击过输入框中，记录输入框和对应位置
    var global_dom , global_x=0;
    $(document).on('blur' , '#form-seo .layui-input, #form-seo .layui-textarea' , function (e) {
        //获取输入框jq对象
        global_dom = $(this);
        //获取输入框光标位置
        global_x = $(this).getCursorPosition();

    });


    //点击seo可用变量标签的时候，将标签插入到文本框中
    $(document).on('click','.seo_available_tag span.layui-btn' , function (e) {

        if(global_dom){
            //找到当前标签和上一次的输入框的父辈
            global_dom_parent = global_dom.parents('.layui-colla-item');
            tag_parent = $(this).parents('.layui-colla-item');

            //判断父辈的位置是否一致。一致则可以插入标签到输入框中
            if(global_dom_parent.index() == tag_parent.index()){

                var target_str = global_dom.val();
                var tag_str = $(this).text();

                //拆分目标源文本
                var left_str = target_str.slice(0,global_x);
                var right_str = target_str.slice(global_x);

                if(target_str.length==0){
                    global_dom.val( tag_str );
                    //重新定位光标位置
                    global_x = tag_str.length;
                }else{
                    if(left_str.length==0){
                        global_dom.val( tag_str + " " + right_str.ltrim() );
                    }else if(right_str.length==0){
                        global_dom.val( left_str.rtrim() + " " + tag_str );
                    }else{
                        global_dom.val( left_str.rtrim() + " " + tag_str + " " + right_str.ltrim() );
                    }
                    //重新定位光标位置
                    global_x += tag_str.length+1;

                }

            }else{
                // console.log('not the same');
            }

        }

    });






    //Init操作确认
    $(document).on('click', '.init_wp' , function () {
        layer.open({
            type:0
            ,title: '确定执行勾选的初始化操作？'
            ,content: '注意：已经上线的网站慎用！'
            ,btn: ['确定执行', '取消']
            ,yes: function(index, layero){
                $('#form-init').submit();
            }
            ,btn2: function(index, layero){

            }
        });
    });

    //Init执行
    $(document).on('submit', '#form-init', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_init&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });

    //获取旧URL
    var guess_old_url = new Array();
    $(document).on('click','.guess_old_url', function (e) {
        e.preventDefault();
        url = guess_old_url.pop();

        var layerindex ;
        // console.log(url);
        if(url==undefined){
            $.ajax({
                url:ajaxurl,
                dataType:"json",
                data:"action=zendkee_guess_old_url",
                beforeSend:function(){
                    layerindex = layer.load(2);
                },
            }).done(function (response) {
                if(response.status=='ok'){
                    guess_old_url = response.urls;
                    url = guess_old_url.pop();
                    console.log(url);
                    $('input[name=old_url]').val(url);
                }
            }).always(function () {
                layer.close(layerindex);
            });
        }else{
            $('input[name=old_url]').val(url);
        }


    });




    //获取当前URL
    $(document).on('click','.get_current_url', function (e) {
        e.preventDefault();
        $('input[name=new_url]').val(window.location.origin);
    });

    //纠正URL
    $(document).on('click','.check_url', function (e) {
        e.preventDefault();
        $('.url_to_check').each(function(){
            url = $(this).val();
            url = url.trim();
            url = url.replace(/\/$/, "");
            $(this).val(url);
        });

        layer.msg("已经帮你检查输入的URL", {
            icon: 1,
        });


    });

    //Online操作确认
    $(document).on('click', '.online_wp' , function () {
        layer.open({
            type:0
            ,title: '确定执行URL替换操作？'
            ,content: '注意：已经上线的网站慎用！'
            ,btn: ['确定执行', '取消']
            ,yes: function(index, layero){
                $('#form-online').submit();
            }
            ,btn2: function(index, layero){

            }
        });
    });





    //Online执行
    $(document).on('submit', '#form-online', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_online&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                    time: 8000
                },function(){
                    // location.reload();
                }
                );
                

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });


    //数据库优化
    $(document).on('submit', '#form-database', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_database&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });



    //启用/关闭专业版
    $(document).on('click','.enable_pro,.disable_pro',function (e) {
        e.preventDefault();
        FORM = $('#form-pro');
        STATUS = $(this).data('status');
        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_pro&status=' + STATUS + "&" + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;
    });


    $(document).on('submit', '#form-compatible', function (e) {
        e.preventDefault();
        FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=zendkee_compatible&' + FORM.serialize(),
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });


    //reset
    $(document).on('click', '#reset', function () {
        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: {
                action: 'zendkee_reset',
            },
            beforeSend: function () {
                layer.msg('Loading...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();


            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;
    });



    /**
     * 生成谷歌翻译工具的短代码
     */
    $(document).on("click",".create_google_translate_shortcode" , function(e){
        e.preventDefault();
        // form.
        let translate_these = Array();
        $("input[name='translate_these[]']:checked").each(function(){
            // console.log($(this).val());
            translate_these.push($(this).val());
        });
        if(translate_these.length>0){
            let language_attr = translate_these.join(",");
            // console.log(language_attr);
            let google_shortcode = `[zk_google_translate languages="${language_attr}"]`;
            $("#google_translate_shortcode").val(google_shortcode);
        }else{
            // console.log("taishao");
            layer.msg("先选择一些语言吧！", {
                icon: 2,
            });
        }
        

        return false;
    });

    //scan images
    $(document).on('click', '#scan_images', function (e) {
        // e.preventDefault();
        // FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=scan_images',
            beforeSend: function () {
                layer.msg('Scanning...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });

    //scan database
    $(document).on('click', '#scan_db', function (e) {
        // e.preventDefault();
        // FORM = $(this);

        $.ajax({
            type: "POST",
            url: zendkee_customize_js_object.ajax_url,
            dataType: 'json',
            data: 'action=scan_db',
            beforeSend: function () {
                layer.msg('Scanning...', {icon: 4, time: 0});
            },
        }).done(function (msg) {
            if (msg.status == 'ok') {
                layer.msg(msg.info, {
                    icon: 1,
                });
                location.reload();

            } else {
                layer.msg(msg.info, {
                    icon: 2,
                });
            }
        });

        return false;

    });

    

});