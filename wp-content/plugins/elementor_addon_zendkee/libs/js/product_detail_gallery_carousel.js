jQuery(document).ready(function () {
    var total_perview = 4;

    jQuery(".woocommerce-product-gallery.woocommerce-product-gallery--with-images").addClass("swiper-container");
    jQuery(".woocommerce-product-gallery__wrapper").after('<div class="swiper-pagination"></div>');
    jQuery(".woocommerce-product-gallery .flex-control-nav.flex-control-thumbs").addClass("swiper-wrapper").find("li").addClass("swiper-slide");
    jQuery(".woocommerce-product-gallery.woocommerce-product-gallery--with-images").after('<div class="swiper-button-next"></div>');
    jQuery(".woocommerce-product-gallery.woocommerce-product-gallery--with-images").after('<div class="swiper-button-prev"></div>');
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: total_perview,
        // 分页器圆点
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        // 左右按钮
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
            disabledClass: '1'
        },
    });

    jQuery('.flex-control-nav.flex-control-thumbs').css('overflow', 'initial');

    var swipercontainer = jQuery(".swiper-container");
    swipercontainer.find(".swiper-wrapper").unbind("touchend");
    jQuery(".product .swiper-button-next").click(function () {
        let swiperindex = jQuery(".flex-active").index(".swiper-wrapper .swiper-slide img");
        let swiperlength = swipercontainer.find(".swiper-slide img").length;
        if (swiperindex < swiperlength) {
            swipercontainer.find(".swiper-wrapper img").eq(swiperindex + 1).click();
        }
    });
    jQuery(".product .swiper-button-prev").click(function () {
        let swiperindex = jQuery(".flex-active").index(".swiper-wrapper .swiper-slide img");
        if (swiperindex > 0) {
            swipercontainer.find(".swiper-wrapper img").eq(swiperindex - 1).click();
        }
    });



    /* 计算详情页轮播图左右切换按钮的相对底部的位移 */
    function caculate_thumbnail_nav() {
        //外层/大图宽度
        swiper_width = jQuery(".woocommerce-product-gallery.swiper-container").width();
        //外层的margin-bottom
        swiper_height_margin_bottom = jQuery("div.woocommerce-product-gallery.swiper-container").css("margin-bottom");
        //缩略图的高度/宽度
        thumbnail_height = swiper_width / total_perview;
        //切换按钮的高度
        button_nav_height = jQuery(".swiper-button-prev").height();
        //切换按钮距离底部的位移
        to_bottom = (thumbnail_height - button_nav_height) / 2 + parseInt(swiper_height_margin_bottom);

        jQuery(".swiper-button-prev,.swiper-button-next").css("bottom", to_bottom);
    }

    caculate_thumbnail_nav();

    jQuery(window).resize(function () {
        caculate_thumbnail_nav();
    });



});


