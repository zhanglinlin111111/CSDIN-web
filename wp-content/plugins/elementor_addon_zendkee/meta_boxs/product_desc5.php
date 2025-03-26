<?php


const Zendkee_Product_DESC5 = 'zendkee_product_desc5';


/** 页面引入自定义meta属性框 start **/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'zendkee_meta_tabs5',
        __('Product Desc 5', 'zendkee'),
        function(){

            $zendkee_meta_page = get_post_meta(get_the_ID(), Zendkee_Product_DESC5, true);
            //这是富文本编辑器
            wp_editor($zendkee_meta_page,Zendkee_Product_DESC5, array('textarea_rows'=>10));

        },
        array('product'),//screen
        'normal',
        'high'
    );
});



//post，page，product页的notice数据保存
add_action('save_post', function ($post_id) {
    if (isset($_POST[Zendkee_Product_DESC5])) {
        update_post_meta($post_id, Zendkee_Product_DESC5, $_POST[Zendkee_Product_DESC5]);
    }
}, 10, 1);