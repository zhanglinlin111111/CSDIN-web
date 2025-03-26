/*
* for widgets only
* */


/** 开启管理员权限 **/

/** 暂时关闭此功能 **
var imgs = [];
jQuery(document).ready($ => {
    //开启管理员权限
    if (elementor.config.user.restrictions) elementor.config.user.restrictions = [];
    // 如果不是管理员，引入js与css隐藏多余的功能
    if (!elementor.config.user.is_administrator) {
        var e = document.createElement("link"); e.setAttribute("rel", "stylesheet");
        e.setAttribute("href", "/wp-content/plugins/elementor_addon_zendkee/libs/css/elementor-permission.css");
        document.body.appendChild(e);
        var t = document.createElement("script");
        t.setAttribute("src", "/wp-content/plugins/elementor_addon_zendkee/libs/js/elementor-permission.js");
        document.body.appendChild(t);
    }
});
 ****/



timeout = 300;

/**
 * **** 等条件成熟，执行操作 ****
 * callback:要执行的操作
 * timeout:检测间隔，单位：毫秒
 * verifier:检查条件。符合条件(函数返回true)，则运行callback
* */
if(typeof zendkee_delay_run != "function"){
    function zendkee_delay_run(callback , timeout , verifier){
        var interval = window.setInterval(function(){
            if( typeof(eval(verifier)) == "function" && eval(verifier+"();")){
                if(typeof(eval(callback)) == "function"){
                    eval(callback+"();");
                }
                clearInterval(interval);
            }

        },timeout , verifier);
    }
}



function test_navigator(){
    return jQuery("#elementor-panel-footer #elementor-panel-footer-tools").length > 0;
}


function add_go_back_button() {
    let admin_url = zendkee_elementor_addon_js_object.admin_url;
    jQuery("#elementor-panel-footer #elementor-panel-footer-tools").prepend(`<div id='elementor-panel-footer-goback' ><a class='eicon-close'  data-tooltip='Go Back' title='Go Back' href='${admin_url}'></a></div>`);
    // jQuery("#elementor-panel-footer-goback a").tooltip('go back');
}

/* add a go back button on elementor editing page */
jQuery(document).ready(function () {
    zendkee_delay_run('add_go_back_button' , timeout , 'test_navigator');
});






