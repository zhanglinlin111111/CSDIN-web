;jQuery(document).ready(function ($) {
    //防止重复提交
    $('.wpcf7-form').on('submit', function () {
        $(this).find('.wpcf7-submit').attr('disabled', true);
    });

    $('.wpcf7').on('wpcf7submit', function (e) {
        $(this).find('.wpcf7-submit').removeAttr('disabled');
    });


    //格式复位
    $(document).on("focus", ".wpcf7-form .zendkee_form input", function (e) {
        input_name = $(this).attr("name");
        $(this).next(".input-incorrect").remove();
        $(this).next(".input-tip").remove();
        if (input_name != undefined && (input_name.toLowerCase().indexOf("phone") > -1)) {
            $(this).before("<div class='input-prompt'>Format:1 (234) 567-8910 , 81-12-3456-7891 , 02 1234 5678</div>");
        }
        if (input_name != undefined && (input_name.toLowerCase().indexOf("country") > -1)) {
            $(this).parent().find(".wpcf7-dropdown-btn").click();
        }
    });


    //格式检查
    $(document).on("blur", ".wpcf7-form .zendkee_form input", function (e) {
        e.preventDefault();

        input_name = $(this).attr("name");
        $(this).prev(".input-prompt").remove();

        input_val = $.trim($(this).val());

        if (input_val == "" && input_name != undefined && input_name.toLowerCase().indexOf("country") < 0) {
            $(this).after("<div class='input-tip'></div>");
            return false;
        }

        //email
        if (input_name != undefined && input_name.toLowerCase().indexOf("mail") > -1) {
            if (!$(this).val().match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/)) {
                // 电子邮件格式不正确 bla bla bla ...
                $(this).after("<div class='input-incorrect'></div>");
            }
        }

        //zip code
        if (input_name != undefined && input_name.toLowerCase().indexOf("zip") > -1) {
            if (!$(this).val().match(/^\d+$|^\d+(-\d+)?$|^\w+( +\w+)?$/)) {
                // Zip邮编格式不正确 bla bla bla ...
                $(this).after("<div class='input-incorrect'></div>");
            }
        }

        //Phone
        if (input_name != undefined && (input_name.toLowerCase().indexOf("phone") > -1 || input_name.toLowerCase().indexOf("tel") > -1)) {
            if (!$(this).val().match(/^\d ?\(\d+\) ?\d+-?\d+$|^(\d+\-)+\d+$|^(\d+ +)+\d+$|^\d+$/)) {
                // 电话格式不正确 bla bla bla ...
                $(this).after("<div class='input-incorrect'></div>");
            }
        }

    });


    /**增强下拉 */
    $(".zendkee_form .wpcf7-dropdown-btn").on("click", function (e) {
        e.preventDefault();
        current_dropdown = $(this).siblings(".wpcf7-dropdown-content");
        $(".wpcf7-dropdown-content").not(current_dropdown).removeClass("show");
        current_dropdown.toggleClass("show");

    });


    $(".zendkee_form .wpcf7-dropdown-content a").on("click", function (e) {
        e.preventDefault();
        value = $(this).data("value");
        $(this).parents(".wpcf7-dropdown-content").siblings(".wpcf7-dropdown-value").val(value);
        $(this).parents(".wpcf7-dropdown-content").toggleClass("show");
    });

    $(".zendkee_form .wpcf7-dropdown-filter").on("keyup", function (e) {
        e.preventDefault();
        var filter;
        filter = $(this).val().toUpperCase();

        $(this).parents(".wpcf7-dropdown-content").children("a").each(function (i, n) {
            txtLabel = $(this).text();
            txtValue = $(this).data("value");

            if (txtLabel.toUpperCase().indexOf(filter) > -1 || txtValue.toUpperCase().indexOf(filter) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});