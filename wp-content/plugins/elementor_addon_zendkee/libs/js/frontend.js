/* 获取URL参数
 * 使用方法：location.getParameter('参数名')
  * */
(function (window, undefined) {
    var ArrayForEach = Array.prototype.forEach || function (fn, sc) {
        var a = this, l = a.length | 0, i;
        for (i = 0; i < l; i += 1) fn.call(sc, a[i], i, a);
    };
    var ObjectGetOwnPropertyNames = Object.getOwnPropertyNames || function (o) {
        var a = [], p;
        for (p in o) if (o.hasOwnProperty(p)) a.push(p);
        return a;
    };
    var location = window.location, params = {};
    ArrayForEach.call(location.search.substr(1).split("&"), function (slice, index) {
        var p = slice.split("="), name = decodeURIComponent(p[0]), value = decodeURIComponent(p[1]);
        params.hasOwnProperty(name) ? params[name].push(value) : params[name] = [value];
    });

    function getParameter(name) {
        return params.hasOwnProperty(name) ? params[name][0] : null;
    }

    function getParameterValues(name) {
        return params.hasOwnProperty(name) ? params[name] : [];
    }

    function getParameterNames() {
        return ObjectGetOwnPropertyNames(params);
    }

    function getParameterMap() {
        return params;
    }

    location.getParameter = getParameter;
    location.getParameterValues = getParameterValues;
    location.getParameterNames = getParameterNames;
    location.getParameterMap = getParameterMap;
})(window);




/* swiper start */

jQuery(document).ready(function ($) {
    let item = jQuery(".tabGroup>li>div");
    item.each(function () {
        let itemId = `#${this.id}`;
        mySwiper(itemId);
    });

    function mySwiper(a) {
        var mySwiper1 = new Swiper(a, {
            slidesPerView: slidesPerView_mobile,
            pagination: {
                el: ".swiper-p1",
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            observer: true,
            observeParents: true,
            breakpoints: {
                900: {
                    slidesPerView: slidesPerView_pc,
                    spaceBetween: 30,
                },
            },
        });
    }

    let navGroup = jQuery(".navGroup h2");
    let tabGroup = jQuery(".tabGroup li");
    for (let i = 0; i < navGroup.length; i++) {
        navGroup[i].onclick = function () {
            for (let k = 0; k < tabGroup.length; k++) {
                tabGroup[k].style.display = "none";
                jQuery(navGroup[k]).removeClass("active");
            }
            tabGroup[i].style.display = "block";
            jQuery(navGroup[i]).addClass("active");
        };
    }
});
/* swiper end */








/** 图片大小提示 start **/
function display_image_size(){
    // jQuery('.elementor-image img,.elementor-image-box-img img').each(function(){
    jQuery('body section img').each(function(index , ele){


        //实际图片大小
        // var width = jQuery(this).width();
        // var height = jQuery(this).height();

        //原始图片大小
        if(ele.naturalWidth && ele.naturalHeight){
            var width = ele.naturalWidth;
            var height = ele.naturalHeight;

            info = "图片宽度:" + width + "px, 图片高度:" + height + "px";

            // 如果没有图片信息则添加信息sapn
            if (jQuery(this).next('span.imgs-wrapper').length <= 0) {
                jQuery(this).parent().addClass('image_info_parent').append('<span class="imgs-wrapper">' + info + '</span>');
            } else {
                // 如果有图片信息则替换span内容
                jQuery(this).next('span.imgs-wrapper').text(info);
            }
        }



    });
}

//只在element的后台preview页显示图像大小
if (location.getParameter('elementor-preview') !== null) {
    //定时检查
    // jQuery(document).ready(function () {
    //     var tries = 0;
    //     var interval = window.setInterval(function(){
    //
    //         tries++;
    //         if(tries >= 10){clearInterval(interval);}
    //         display_image_size();
    //
    //     },3000);
    //
    // });


    // 窗口变化时调用，重新获取图片
    // jQuery(window).resize(function () {
    //     display_image_size();
    // });

    //鼠标放上去检查
    jQuery(document).on("mouseover",'body section img',function () {

        var width = 0;
        var height = 0;

        width = jQuery(this).attr('width');
        height = jQuery(this).attr('height');

        if(!width || !height ){
            width = jQuery(this)[0].naturalWidth;
            height = jQuery(this)[0].naturalHeight;
        }


        //原始图片大小
        if(width && height){
            // var width = jQuery(this)[0].naturalWidth;
            // var height = jQuery(this)[0].naturalHeight;

            info = "图片宽度:" + width + "px, 图片高度:" + height + "px";

            // 如果没有图片信息则添加信息sapn
            if (jQuery(this).next('span.imgs-wrapper').length <= 0) {
                jQuery(this).parent().addClass('image_info_parent').append('<span class="imgs-wrapper">' + info + '</span>');
            } else {
                // 如果有图片信息则替换span内容
                jQuery(this).next('span.imgs-wrapper').text(info);
            }
        }


    });


}



/** 图片大小提示 end **/





/** elementor-pro popup:click_button start **/
// jQuery(document).ready(function ($) {
//     // $(".elementor-popup-modal").css("display","none");
//
//     $(document).on("click",".coly-menu",function (e) {
//         e.preventDefault();
//         console.log("click");
//         $(".elementor-popup-modal").css({"display":"block","visibility":"visible"});
//     })
// });
/** elementor-pro popup:click_button end **/