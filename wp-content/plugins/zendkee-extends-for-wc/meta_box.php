<?php



/**
 * 产品分类页，增加描述的编辑后台 start
 */
//产品分类-增加一个分类描述
add_action("product_cat_edit_form_fields", 'zendkee_edit_form_fields_meta_x2', 0, 2);
//文章分类-增加一个分类描述
add_action("category_edit_form_fields", 'zendkee_edit_form_fields_meta_x2', 0, 2);
if (!function_exists('zendkee_edit_form_fields_meta_x2')) {
    function zendkee_edit_form_fields_meta_x2($term, $taxonomy = '')
    {
        $zendkee_meta_term_x2 = get_term_meta($term->term_id, 'zendkee_meta_term_x2', true);
?>
        <tr valign="top">
            <th scope="row">Description x2 <br><br><br><span style="font-weight: normal;">Usage: [explain_product_desc2]</th>
            <td>
                <?php
                //这是富文本编辑器
                wp_editor($zendkee_meta_term_x2, 'zendkee_meta_term_x2', ['textarea_rows' => 10]);
                //这是普通的textarea
                // printf('<textarea name="zendkee_meta_term_x2" rows="5" cols="50"  class="large-text">%s</textarea>', $zendkee_meta_term_x2);
                ?>
            </td>
        </tr>
    <?php
    }
}


//产品分类meta数据保存
add_action('create_term', 'zendkee_save_extra_category_meta_x2', 10, 3);
add_action('edited_terms', 'zendkee_save_extra_category_meta_x2', 10, 2);

// save extra category extra fields callback function
function zendkee_save_extra_category_meta_x2($term_id, $taxonomy)
{
    if (isset($_POST['zendkee_meta_term_x2'])) {
        update_term_meta($term_id, 'zendkee_meta_term_x2', $_POST['zendkee_meta_term_x2']);
    }
}

/**
 * 产品分类页，增加描述的编辑后台 end
 */




/**
 * 增加产品分类页-顶部描述
 */

//产品分类
add_action("product_cat_edit_form_fields", 'zendkee_edit_form_fields_meta_top', 0, 2);
//文章分类
add_action("category_edit_form_fields", 'zendkee_edit_form_fields_meta_top', 0, 2);
if (!function_exists('zendkee_edit_form_fields_meta_top')) {
    function zendkee_edit_form_fields_meta_top($term, $taxonomy = '')
    {
        $zendkee_meta_term_top_desc = get_term_meta($term->term_id, 'zendkee_meta_term_top_desc', true);
        $zendkee_meta_term_top_image = get_term_meta($term->term_id, 'zendkee_meta_term_top_image', true);
        // $zendkee_meta_term_top_direction = get_term_meta($term->term_id, 'zendkee_meta_term_top_direction', true);
    ?>
        <!--<tr valign="top" style="border-top:1px solid #2271b1;">
            <th scope="row"><label for="zendkee_meta_term_top_direction">Top Position</label></th>
            <td>
                <?php
                // printf(
                //     '
                // <label><input type="radio" name="zendkee_meta_term_top_direction" value="left" %s>Image Left, Text Right</label>
                // <label><input type="radio" name="zendkee_meta_term_top_direction" value="right" %s>Image Right, Text Left</label>',
                //     checked($zendkee_meta_term_top_direction, 'left', false),
                //     checked($zendkee_meta_term_top_direction, 'right', false)
                // );
                ?>

            </td>
        </tr>-->
        <tr valign="top">
            <th scope="row">Top Description <br><br><br><span style="font-weight: normal;">Usage: [explain_top_desc]</span></th>
            <td>
                <?php
                //这是富文本编辑器
                wp_editor($zendkee_meta_term_top_desc, 'zendkee_meta_term_top_desc', ['textarea_rows' => 10]);

                ?>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="zendkee_meta_term_top_direction">Top Image</label><br><br><br><span style="font-weight: normal;">Usage: [explain_top_image]</span></th>
            <td>
                <?php
                printf('<input type="hidden" id="zendkee_meta_term_top_image" name="zendkee_meta_term_top_image" class="custom_media_url" value="%s">', esc_attr($zendkee_meta_term_top_image));
                if ($zendkee_meta_term_top_image) {
                    echo '<div id="zendkee_meta_term_top_image_preview" class="zendkee_meta_term_image_preview"><img src="' . esc_url($zendkee_meta_term_top_image) . '" style="max-width:200px; height:100%;"/><br><a href="#" class="zk_remove_image_button">移除图片</a></div>';
                } else {
                    echo '<div id="zendkee_meta_term_top_image_preview" class="zendkee_meta_term_image_preview"></div>';
                }
                ?>
                <button class="button zk_upload_image_button_1">Upload Image</button>
            </td>
        </tr>
    <?php
    }
}


//产品分类meta数据保存
add_action('create_term', 'zendkee_save_extra_category_meta_top', 10, 3);
add_action('edited_terms', 'zendkee_save_extra_category_meta_top', 10, 2);

// save extra category extra fields callback function
function zendkee_save_extra_category_meta_top($term_id, $taxonomy)
{
    if (isset($_POST['zendkee_meta_term_top_desc'])) {
        update_term_meta($term_id, 'zendkee_meta_term_top_desc', $_POST['zendkee_meta_term_top_desc']);
    }
    if (isset($_POST['zendkee_meta_term_top_image'])) {
        update_term_meta($term_id, 'zendkee_meta_term_top_image', esc_url($_POST['zendkee_meta_term_top_image']));
    }
    // if (isset($_POST['zendkee_meta_term_top_direction'])) {
    //     update_term_meta($term_id, 'zendkee_meta_term_top_direction', $_POST['zendkee_meta_term_top_direction']);
    // }
}


/**
 * 增加产品分类页-底部描述
 */


//产品分类
add_action("product_cat_edit_form_fields", 'zendkee_edit_form_fields_meta_bottom', 0, 2);
//文章分类
add_action("category_edit_form_fields", 'zendkee_edit_form_fields_meta_bottom', 0, 2);
if (!function_exists('zendkee_edit_form_fields_meta_bottom')) {
    function zendkee_edit_form_fields_meta_bottom($term, $taxonomy = '')
    {
        $zendkee_meta_term_bottom_desc = get_term_meta($term->term_id, 'zendkee_meta_term_bottom_desc', true);
        $zendkee_meta_term_bottom_image = get_term_meta($term->term_id, 'zendkee_meta_term_bottom_image', true);
        // $zendkee_meta_term_bottom_direction = get_term_meta($term->term_id, 'zendkee_meta_term_bottom_direction', true);
    ?>
        <!--<tr valign="top">
            <th scope="row"><label for="zendkee_meta_term_bottom_direction">Bottom Position</label></th>
            <td>
                <?php
                // printf(
                //     '
                // <label><input type="radio" name="zendkee_meta_term_bottom_direction" value="left" %s>Image Left, Text Right</label>
                // <label><input type="radio" name="zendkee_meta_term_bottom_direction" value="right" %s>Image Right, Text Left</label>',
                //     checked($zendkee_meta_term_bottom_direction, 'left', false),
                //     checked($zendkee_meta_term_bottom_direction, 'right', false)
                // );
                ?>

            </td>
        </tr>-->
        <tr valign="top">
            <th scope="row">Bottom Description <br><br><br><span style="font-weight: normal;">Usage: [explain_bottom_desc]</span></th>
            <td>
                <?php
                //这是富文本编辑器
                wp_editor($zendkee_meta_term_bottom_desc, 'zendkee_meta_term_bottom_desc', ['textarea_rows' => 10]);

                ?>
            </td>
        </tr>
        <tr valign="top" style="border-bottom:1px solid #2271b1;">
            <th scope="row"><label for="zendkee_meta_term_bottom_direction">Bottom Image</label> <br><br><br><span style="font-weight: normal;">Usage: [explain_bottom_image]</span></th>
            <td>
                <?php
                printf('<input type="hidden" id="zendkee_meta_term_bottom_image" name="zendkee_meta_term_bottom_image" class="custom_media_url" value="%s">', esc_attr($zendkee_meta_term_bottom_image));
                if ($zendkee_meta_term_bottom_image) {
                    echo '<div id="zendkee_meta_term_bottom_image_preview" class="zendkee_meta_term_image_preview"><img src="' . esc_url($zendkee_meta_term_bottom_image) . '" style="max-width:200px; height:100%;"/><br><a href="#" class="zk_remove_image_button">移除图片</a></div>';
                } else {
                    echo '<div id="zendkee_meta_term_bottom_image_preview" class="zendkee_meta_term_image_preview"></div>';
                }
                ?>
                <button class="button zk_upload_image_button_2">Upload Image</button>
            </td>
        </tr>
<?php
    }
}


//产品分类meta数据保存
add_action('create_term', 'zendkee_save_extra_category_meta_bottom', 10, 3);
add_action('edited_terms', 'zendkee_save_extra_category_meta_bottom', 10, 2);

// save extra category extra fields callback function
function zendkee_save_extra_category_meta_bottom($term_id, $taxonomy)
{
    if (isset($_POST['zendkee_meta_term_bottom_desc'])) {
        update_term_meta($term_id, 'zendkee_meta_term_bottom_desc', $_POST['zendkee_meta_term_bottom_desc']);
    }
    if (isset($_POST['zendkee_meta_term_bottom_image'])) {
        update_term_meta($term_id, 'zendkee_meta_term_bottom_image', esc_url($_POST['zendkee_meta_term_bottom_image']));
    }
    // if (isset($_POST['zendkee_meta_term_bottom_direction'])) {
    //     update_term_meta($term_id, 'zendkee_meta_term_bottom_direction', $_POST['zendkee_meta_term_bottom_direction']);
    // }
}








/**
 * 增加Widget支持
 */

//注册一个widget框，可以放置内容（可放置上面的widget）
add_action('widgets_init', function () {
    register_sidebar(array(
        'id' => 'product_cat_siderbar',
        'name' => '产品分类页-侧边栏',
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '<div class="zendkee-widget-title">',
        'after_title' => "</div>",
    ));
});



add_action('woocommerce_before_shop_loop', function () {
    if (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        // $category_id = $category->term_id;
        $zendkee_meta_term_top = get_term_meta($term->term_id, 'zendkee_meta_term_top', true);

        printf('<div class="product_cat_desc_top">%s</div>', $zendkee_meta_term_top);
    }
});

add_action('woocommerce_after_shop_loop', function () {
    if (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        // $category_id = $category->term_id;
        $zendkee_meta_term_bottom = get_term_meta($term->term_id, 'zendkee_meta_term_bottom', true);

        printf('<div class="product_cat_desc_bottom">%s</div>', $zendkee_meta_term_bottom);
    }
});
