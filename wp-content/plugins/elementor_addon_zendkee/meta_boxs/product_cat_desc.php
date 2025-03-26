<?php

if (!defined('Zendkee_Product_CATEGORY_DESC2')){
    define('Zendkee_Product_CATEGORY_DESC2','zendkee_product_cat_desc2');
}

//产品分类
add_action("product_cat_edit_form_fields", 'zendkee_edit_form_fields_meta', 10, 2);
function zendkee_edit_form_fields_meta($term, $taxonomy = '')
{
    $zendkee_meta_term = get_term_meta($term->term_id, Zendkee_Product_CATEGORY_DESC2, true);
    ?>
        <tr valign="top">
            <th scope="row"><?php _e('Description 2');?></th>
            <td>
                <?php
            //这是富文本编辑器
            wp_editor($zendkee_meta_term,Zendkee_Product_CATEGORY_DESC2 , array('textarea_rows'=>2));

            ?>
            </td>
        </tr>

        <?php
}

//产品分类meta数据保存
add_action('edited_terms', 'zendkee_save_extra_category_meta', 10, 2);
// save extra category extra fields callback function
function zendkee_save_extra_category_meta($term_id, $taxonomy)
{
    if(isset($_POST[Zendkee_Product_CATEGORY_DESC2])){
        update_term_meta($term_id, Zendkee_Product_CATEGORY_DESC2, $_POST[Zendkee_Product_CATEGORY_DESC2]);
    }
}