jQuery(document).ready(function ($) {
    $("#adminmenuwrap").append('' +
        '<div class="ehaitech_support"><a class="ehaitech_logo" href="https://www.ehaitech.com/" target="_blank"></a></div>'
        + '');
});

// 左边菜单收缩
window.onload = function () {
    jQuery('#adminmenu>li.wp-has-submenu a.menu-top').unbind("click");

    jQuery('#adminmenu>li.wp-has-submenu a.menu-top').click(function () {
        // alert("Comeon");
        var $this = jQuery(this);
        // $this.addClass("opensub");
        if ($this.parent().hasClass('opensub')) {
            $this.parent().removeClass('opensub');
            $this.next('.wp-submenu').slideUp(300);
        } else {
            jQuery('#adminmenu>li.wp-has-submenu').removeClass('opensub');
            $this.parent().addClass('opensub');
            jQuery('#adminmenu>li.wp-has-submenu .wp-submenu').slideUp(0);
            $this.next('.wp-submenu').slideDown(300);
        }
        return false;
    });
};
