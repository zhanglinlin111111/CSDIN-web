
/* Zendkee Customize Frontend Form JS Start */

jQuery(document).ready(function(){
    //float form
    var closeBtn = document.querySelector("#leo-side-contact-form .closeBtn");
    var sideBtn = document.getElementById("coly-menu-float");
    var leoSideForm = document.getElementById("leo-side-contact-form");
    if (sideBtn) {
        sideBtn.addEventListener("click", toggleSideForm);
    }
    if (closeBtn) {
        closeBtn.addEventListener("click", toggleSideForm);
    }

    function toggleSideForm(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        } else {
            e.cancelBubble = true;
        }
        var leoSideFormClassList = leoSideForm.classList;
        if (leoSideFormClassList.contains("active")) {
            leoSideFormClassList.remove("active");
        } else if (!leoSideFormClassList.contains("active")) {
            leoSideFormClassList.add("active");
        }
    }

    jQuery("input[name=url]").val(location.href);
    document.addEventListener(
        "wpcf7mailsent",
        function (e) {
            let default_thanks_url = "/thanks/";
            var formRedirect = jQuery(e.target).find("a.redirect");
            let formThanksUrl = '';
            if (formRedirect.length == 0) {
                formThanksUrl = default_thanks_url;
            } else {
                formThanksUrl = formRedirect.data("to");
            }
            if (formThanksUrl) {
                window.location = formThanksUrl;
            }
        },
        false
    );


    //scroll to top
    jQuery(".coly-top-float").on("click", function (e) {
        jQuery("html,body").animate({
            scrollTop: "0",
        });
    });



    var frank_timer = null;
    jQuery(document).on("scroll", function (e) {
        frank_timer = setTimeout(function () {
            if (jQuery(document).scrollTop() >= jQuery(window).outerHeight() / 2) {
                jQuery(".coly-top-float").addClass('show');
            } else {
                jQuery(".coly-top-float").removeClass('show');
            }
        }, 100);
    });
    jQuery(".coly-top-float").on("click", function (e) {
        jQuery("html,body").animate({ "scrollTop": "0" });
    });

});

/* Zendkee Customize Frontend Form JS End */




