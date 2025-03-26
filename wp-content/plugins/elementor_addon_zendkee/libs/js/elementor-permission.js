// jQuery(document).ready($ => {
//     $('iframe#elementor - preview - iframe')
// });

//加载elementor-permission.css
window.onload = function () {
    // 找到head
    var head = jQuery("#elementor-preview-iframe").contents().find('head');
    // 生成dom
    var new_element = document.createElement('link');
    // 设置
    new_element.setAttribute('rel', 'stylesheet');
    // 引入地址
    new_element.setAttribute('href', '/wp-content/plugins/elementor_addon_zendkee/libs/css/elementor-permission.css');
    // 添加dom
    jQuery(head).append(new_element);
};

// 找到dom进行隐藏
function hide() {
    //section的工具栏，左侧的增加按钮
    jQuery("#elementor-preview-iframe").contents().find("div.elementor-element-overlay > ul.elementor-editor-element-settings.elementor-editor-section-settings>li.elementor-editor-element-setting.elementor-editor-element-add").css('display', 'none');
    //列编辑器
    jQuery("#elementor-preview-iframe").contents().find("div.elementor-element-overlay > ul.elementor-editor-element-settings.elementor-editor-column-settings").css('display', 'none');

    //section的工具栏，右侧的删除按钮
    jQuery("#elementor-preview-iframe").contents().find("ul.elementor-editor-element-settings.elementor-editor-section-settings>li.elementor-editor-element-setting.elementor-editor-element-remove").css('display', 'none');

    //？
    jQuery("#elementor-preview-iframe").contents().find('.elementor-first-add').parents('ul.elementor-editor-element-settings.elementor-editor-section-settings').css('display', 'none');

    //add new section
    jQuery("#elementor-preview-iframe").contents().find('div#elementor-add-new-section').css('display', 'none');


    // jQuery("#elementor-preview-iframe").contents().find('.elementor-icon.eicon-plus').parents('section.elementor-element.elementor-element-edit-mode.elementor-section').css('display', 'none');///????误杀！！！！

    // jQuery("#elementor-preview-iframe").contents().find('.elementor-first-add').parents('section.elementor-element.elementor-element-edit-mode.elementor-section').css('display', 'none');///????误杀！！！！


}

// 点击按钮后，自动点击style
function getList() {
    var arr;
    var styles;
    // 延时调用
    var time = setTimeout(() => {
        // 找到所有编辑按钮
        arr = jQuery("#elementor-preview-iframe").contents().find('li.elementor-editor-element-setting.elementor-editor-element-edit');
        // 如果找到
        if (arr.length > 0) {
            // 进行dom隐藏
            hide();
            // 点击按钮
            var edPage = jQuery("#elementor-preview-iframe").contents().find('.elementor-document-handle');
            // 设置总按钮点击事件，每次点击后重新加载一次
            edPage.click(e => {
                getList();
            });
            // 给编辑按钮设置点击事件
            jQuery(arr).click(e => {
                // 延时点击，找到style，模拟点击
                setTimeout(() => {
                    styles = jQuery('div#elementor-panel-page-editor>.elementor-panel-navigation>.elementor-component-tab.elementor-panel-navigation-tab.elementor-tab-control-style');
                    jQuery(styles).click();


                }, 0);
            });
            // 清除定时器
            clearTimeout(time);
        } else {
            // 没有找到,再次执行
            getList();
        }
    }, 1000);
}

jQuery(document).ready($ => {
        getList();
        widget_hide_style();
    }
);

/*
* 点击每个小组件，都隐藏Style面板
* */
function widget_hide_style() {
    var interval = window.setInterval(function () {

        var widget_container = jQuery("#elementor-preview-iframe").contents().find(".elementor-widget-container");
        if (widget_container.length > 0) {

                jQuery("#elementor-preview-iframe").contents().on("click" , ".elementor-widget-container", function () {

                    jQuery("#elementor-panel-page-editor > div.elementor-panel-navigation > div.elementor-component-tab.elementor-panel-navigation-tab.elementor-tab-control-style").hide();

                });

            clearInterval(interval);
        }

    }, 300);


}