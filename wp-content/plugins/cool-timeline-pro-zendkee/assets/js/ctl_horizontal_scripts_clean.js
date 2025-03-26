jQuery("document").ready(function(t) {
    t(".cool-timeline-horizontal").find("a[class^='ctl_prettyPhoto']").prettyPhoto({
        social_tools: !1
    }), t(".cool-timeline-horizontal").find("a[rel^='ctl_prettyPhoto']").prettyPhoto({
        social_tools: !1
    }), t(".cool-timeline-horizontal.ht-design-6,.cool-timeline-horizontal.ht-design-5").each(function(e) {
        var i = "#" + t(this).attr("date-slider"),
            o = "#" + t(this).attr("data-nav"),
            s = t(this).attr("data-rtl"),
            a = "true" == t(this).attr("data-autoplay"),
            l = parseInt(t(this).attr("data-start-on")),
            r = "true" === s;
        t(i).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            rtl: r,
           asNavFor: o,
            arrows: !1,
            dots: !1,
          //  autoplay: a,
            infinite: !1,
            initialSlide: l,
            adaptiveHeight: !0,
            responsive: [{
                breakpoint: 768,
                settings: {
                    centerPadding: "10px",
                    slidesToShow: 1
                }
            }, {
                breakpoint: 480,
                settings: {
                    centerPadding: "10px",
                    slidesToShow: 1
                }
            }]
        }), t(o).slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: i,
            dots: !1,
            infinite: !1,
            centerMode: !0,
            rtl: r,
            autoplay: a,
            nextArrow: '<button type="button" class="ctl-slick-next "><i class="fa fa fa-arrow-circle-o-right"></i></button>',
            prevArrow: '<button type="button" class="ctl-slick-prev"><i class="fa fa fa-arrow-circle-o-left"></i></button>',
            focusOnSelect: !0,
            adaptiveHeight: !0,
            initialSlide: l,
            responsive: [{
                breakpoint: 768,
                settings: {
                    arrows: !0,
                    centerPadding: "10px",
                    slidesToShow: 1
                }
            }, {
                breakpoint: 480,
                settings: {
                    arrows: !0,
                    centerPadding: "10px",
                    slidesToShow: 1
                }
            }]
        }).on("beforeChange init", function(e, i, o) {
            for (var s = 0; s < i.$slides.length; s++) {
                var a = t(i.$slides[s]);
                if (a.hasClass("slick-current")) {
                    a.addClass("pi"), a.prevAll().addClass("pi"), a.nextAll().removeClass("pi");
                    break
                }
            }
        }).on("afterChange", function(e, i, o) {
            for (var s = 0; s < i.$slides.length; s++) {
                var a = t(i.$slides[s]);
                if (a.hasClass("slick-current")) {
                    a.removeClass("pi"), a.nextAll().removeClass("pi");
                    break
                }
            }
        })
    })
});