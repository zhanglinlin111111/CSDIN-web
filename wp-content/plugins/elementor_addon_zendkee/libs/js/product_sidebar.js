jQuery(document).ready(function ($) {
    timeout = 300;
    /**
     * **** 等条件成熟，执行操作 ****
     * callback:要执行的操作
     * timeout:检测间隔，单位：毫秒
     * verifier:检查条件。符合条件(函数返回true)，则运行callback
     * */
    if (typeof zendkee_delay_run != "function") {
        function zendkee_delay_run(callback, timeout, verifier) {
            var interval = window.setInterval(function () {
                if (typeof (eval(verifier)) == "function" && eval(verifier + "();")) {
                    if (typeof (eval(callback)) == "function") {
                        eval(callback + "();");
                    }
                    clearInterval(interval);
                }

            }, timeout, verifier);
        }
    }

    function test_product_gallery() {
        return jQuery(".nav-menu-sidebar .elementor-item .sub-arrow").length > 0;
    }

    function construct_silder() {
        // 阻止跳转
        function returnfalse(event) {
            return false;
        };

        var sidebar = jQuery(".nav-menu-sidebar");
        var eleitem = sidebar.find(".elementor-item");
        var elesubitem = sidebar.find(".elementor-sub-item");
        var eleitemsub = eleitem.find(".sub-arrow");
        var elesubitemsub = elesubitem.find(".sub-arrow");

        // 阻止主菜单的点击事件
        sidebar.find(".sm-vertical").unbind("click");

        // 阻止展开按照的跳转
        eleitemsub.on("click", "", returnfalse);
        elesubitemsub.on("click", "", returnfalse);

        // 阻止自动展开全部子菜单
        sidebar.find(".current-menu-ancestor").find(".has-submenu").siblings().slideDown();
        sidebar.find(".sub-menu").find(".has-submenu").each(function (i) {
            jQuery(this).siblings().addClass("divnone");
        });

        sidebar.find(".sub-menu").find(".has-submenu:not(.elementor-item-active)").siblings().slideUp();
        sidebar.find(".elementor-item-active").siblings().slideDown();
        sidebar.find(".elementor-item-active").siblings().removeClass("divnone");
        sidebar.find(".elementor-item-active").parent().parent().removeClass("divnone");
        sidebar.find(".elementor-item-active").parent().parent().parent().parent().removeClass("divnone");

        // 一级展开事件
        eleitemsub.each(function (i) {
            if (jQuery(this).parent().siblings().is(":visible")) {
                jQuery(this).find(".fa").addClass("izhuang");
            };

            jQuery(this).click(function () {
                if (jQuery(this).find(".fa").hasClass("izhuang")) {
                    jQuery(this).find(".fa").removeClass("izhuang");
                } else {
                    jQuery(this).find(".fa").addClass("izhuang");
                };
                jQuery(this).parent().siblings().removeClass("divnone");
                jQuery(this).parent().siblings().slideToggle();
                jQuery(this).parent().parent().siblings().find(".sub-menu").slideUp();
                jQuery(this).parent().parent().siblings().find(".fa").removeClass("izhuang");
            });
        });

        // 二级展开事件
        elesubitemsub.each(function (i) {
            if (!(jQuery(this).parent().siblings().is(":hidden"))) {
                jQuery(this).find(".fa").addClass("izhuang");
            };

            jQuery(this).click(function () {
                if (jQuery(this).find(".fa").hasClass("izhuang")) {
                    jQuery(this).find(".fa").removeClass("izhuang");
                } else {
                    jQuery(this).find(".fa").addClass("izhuang");
                };
                jQuery(this).parent().siblings().removeClass("divnone");
                jQuery(this).parent().siblings().slideToggle();

                jQuery(this).parent().parent().siblings().find(".sub-menu").slideUp();
                jQuery(this).parent().parent().siblings().find(".fa").removeClass("izhuang");
            });
        });
    }
    /* add a go back button on elementor editing page */
    jQuery(document).ready(function () {
        zendkee_delay_run('construct_silder', timeout, 'test_product_gallery');
    });

});