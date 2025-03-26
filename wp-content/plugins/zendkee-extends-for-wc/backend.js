jQuery(document).ready(function ($) {
    var mediaUploader;

    // 上传图片按钮点击事件
    $('.zk_upload_image_button_1,.zk_upload_image_button_2').on("click",function (e) {
        e.preventDefault();

        var THAT = $(this);
        // var HANDLE = THAT;
        // console.log(THAT)
        // 如果mediaUploader存在，则打开媒体上传器
        // if (mediaUploader) {
        //     mediaUploader.open();
        //     HANDLE = THAT;
        //     return;
        // }

        // 创建一个媒体上传器
        mediaUploader = wp.media({
            title: '上传图片',
            button: {
                text: '选择图片'
            },
            multiple: false  // 设置为true以支持多图片上传
        });

        // 当用户选择了图片后的回调函数
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            // console.log(HANDLE)

            THAT.siblings('.custom_media_url').val(attachment.url);
            THAT.siblings(".zendkee_meta_term_image_preview").html('<img src="' + attachment.url + '" style="max-width:200px; height:100%;"/><br><a href="#" class="zk_remove_image_button">移除图片</a>');
        });

        // 打开媒体上传器
        mediaUploader.open();
    });

    // 移除图片按钮点击事件
    $(document).on('click', '.zk_remove_image_button', function (e) {
        e.preventDefault();
        var THATR = $(this);
        THATR.parents(".zendkee_meta_term_image_preview").html('').siblings('.custom_media_url').val('');
    });
});