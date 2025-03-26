<?php
if (!defined('ABSPATH')) {
    exit();
}

global $language_code_list;

$disable_builtin_size = zendkee_get_option('disable_builtin_size');
$disable_image_size = zendkee_get_option('disable_image_size');
$image_hide_title = zendkee_get_option('image_hide_title');
$enable_webp = zendkee_get_option('enable_webp');
$enable_debug = zendkee_get_option('enable_debug');
$remove_menu_id_class = zendkee_get_option('remove_menu_id_class');

$enable_custom_font = zendkee_get_option('enable_custom_font');
$zk_font_name = zendkee_get_option('zk_font_name');
$zk_font_value = zendkee_get_option('zk_font_value');


$misc_ga = zendkee_get_option('misc_ga');
$misc_gsc = zendkee_get_option('misc_gsc');
$misc_gtm_header = zendkee_get_option('misc_gtm_header');
$misc_gtm_footer = zendkee_get_option('misc_gtm_footer');

$enable_page_code = zendkee_get_option("enable_page_code");
$misc_page_code_pageid = zendkee_get_option("misc_page_code_pageid");
$misc_gsc_page['thanks'] = zendkee_get_option("misc_gsc_page[thanks]");
$misc_ga_page['thanks'] = zendkee_get_option("misc_ga_page[thanks]");
$misc_gtm_header_page['thanks'] = zendkee_get_option("misc_gtm_header_page[thanks]");
$misc_gtm_footer_page['thanks'] = zendkee_get_option("misc_gtm_footer_page[thanks]");


$product_detail_contact_form = zendkee_get_option('product_detail_contact_form');
$product_detail_form_priority = zendkee_get_option('product_detail_form_priority');
$enable_product_list_contact_form = zendkee_get_option('enable_product_list_contact_form');
$product_list_contact_form = zendkee_get_option('product_list_contact_form');
$enable_footer_contact_form = zendkee_get_option('enable_footer_contact_form');
$footer_contact_form = zendkee_get_option('footer_contact_form');
$jumpto = zendkee_get_option('jumpto');
//默认打开防止重复提交
$enhance_form = zendkee_get_option('enhance_form'); // === false ? '1' : zendkee_get_option('enhance_form');
// $enhance_select = zendkee_get_option('enhance_select'); // === false ? '1' : zendkee_get_option('enhance_select');
$form_select_button_bg_color = zendkee_get_option('form_select_button_bg_color') ? zendkee_get_option('form_select_button_bg_color') : '#000000';
$form_submit_button_bg_color = zendkee_get_option('form_submit_button_bg_color') ? zendkee_get_option('form_submit_button_bg_color') : '#000000';
$form_button_radius_size = zendkee_get_option('form_button_radius_size') ? zendkee_get_option('form_button_radius_size') : 0;


$jumpto_position = zendkee_get_option('jumpto_position') == 'b2c' ? 'b2c' : 'b2b';
$jumpto_text = zendkee_get_option('jumpto_text');
$float_contact_form = zendkee_get_option('float_contact_form');
$quote_text = zendkee_get_option('quote_text');
//$float_button_base_color = zendkee_get_option('float_button_base_color');
$float_button_bg_color = zendkee_get_option('float_button_bg_color');
$scroll_top = zendkee_get_option('scroll_top');
$enable_float_form = zendkee_get_option('enable_float_form');

$enable_frontend_js = zendkee_get_option('enable_frontend_js');
$frontend_js = zendkee_get_option('frontend_js');
$enable_backend_js = zendkee_get_option('enable_backend_js');
$backend_js = zendkee_get_option('backend_js');
$enable_frontend_css = zendkee_get_option('enable_frontend_css');
$frontend_css_position = zendkee_get_option('frontend_css_position') == 'header' ? 'header' : 'footer';
$frontend_css = zendkee_get_option('frontend_css');
$enable_backend_css = zendkee_get_option('enable_backend_css');
$backend_css = zendkee_get_option('backend_css');


$enable_outer_js = zendkee_get_option('enable_outer_js');
$outer_js_position = zendkee_get_option('outer_js_position') == 'header' ? 'header' : 'footer';
$outer_js_dependence = zendkee_get_option('outer_js_dependence');
$outer_js_dependence_other = zendkee_get_option('outer_js_dependence_other');
$outer_js = zendkee_get_option('outer_js');
$enable_outer_css = zendkee_get_option('enable_outer_css');
$outer_css = zendkee_get_option('outer_css');


$new_sizes = wp_get_registered_image_subsizes();



//获取所有已发布的page
$args = array(
    'posts_per_page' => -1, //一共需要调用的文章数量
    'paged' => 1, //分页
    'post_status' => 'publish', //调用的文章为已经发布
    'post_type' => 'page', //调用的类型为产品（product）
    'no_found_rows' => 1,
    'order' => "ASC", //文章排序为时间正排序
    'orderby' => 'title',
);

$get_posts = new WP_Query;
$misc_all_publish_pages_raw = $get_posts->query($args);
$misc_all_publish_pages = [];
foreach ($misc_all_publish_pages_raw as $page_obj) {
    $misc_all_publish_pages[] = [
        'id' => $page_obj->ID,
        'post_title' => $page_obj->post_title,
        'url' => get_permalink($page_obj->ID),
    ];
}


?>


<form class="form" id="form-misc">
    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>


    <div class="layui-collapse" lay-accordion>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">菜单末尾增强功能</h2>
            <div class="layui-colla-content">
                <h3>加入谷歌翻译语言栏</h3>

                <p>请选择要加入语言栏的菜单</p>
                <?php
                $menu_to_add_language_bar = zendkee_get_option('menu_to_add_language_bar');
                $menus_objects = zendkee_get_all_wordpress_menus();

                if (count($menus_objects)) {
                    $menus_control = array('choose' => 'Choose');
                    foreach ($menus_objects as $each) {
                        printf('<div class="layui-input-block">
                <input type="checkbox" name="menu_to_add_language_bar[]" lay-skin="primary" lay-filter="menu_to_add_language_bar" title="%s" value="%s" %s />
                </div>', $each->name, $each->slug, (zendkee_is_set($menu_to_add_language_bar, $each->slug) ? 'checked' : ''));
                    }
                } else {
                    printf('没有菜单，请先<a href="/wp-admin/nav-menus.php">点这里</a>建立菜单');
                }


                ?>


                <hr class="layui-bg-blue">


                <p>选择要翻译的语言</p>
                <?php
                $translate_these = zendkee_get_option('translate_these');

                $total = count($language_code_list);
                $each_col = floor(count($language_code_list) / 4);

                $language_code_list_col1 = array_slice($language_code_list, 0, $each_col);
                $language_code_list_col2 = array_slice($language_code_list, $each_col * 1, $each_col);
                $language_code_list_col3 = array_slice($language_code_list, $each_col * 2, $each_col);
                $language_code_list_col4 = array_slice($language_code_list, $each_col * 3);


                $html = '<div class="layui-row">
                    <div class="layui-col-md3">';

                foreach ($language_code_list_col1 as $slug => $name) {
                    $html .= sprintf('<div>
                <input type="checkbox" name="translate_these[]" lay-skin="primary" lay-filter="translate_these" title="%s" value="%s" %s />
                </div>
                ', $name, $slug, (zendkee_is_set($translate_these, $slug) ? 'checked' : ''));
                }

                $html .= '</div>
                    <div class="layui-col-md3">';

                foreach ($language_code_list_col2 as $slug => $name) {
                    $html .= sprintf('<div>
                <input type="checkbox" name="translate_these[]" lay-skin="primary" lay-filter="translate_these" title="%s" value="%s" %s />
                </div>
                ', $name, $slug, (zendkee_is_set($translate_these, $slug) ? 'checked' : ''));
                }

                $html .= '</div>
                    <div class="layui-col-md3">';

                foreach ($language_code_list_col3 as $slug => $name) {
                    $html .= sprintf('<div>
                <input type="checkbox" name="translate_these[]" lay-skin="primary" lay-filter="translate_these" title="%s" value="%s" %s />
                </div>
                ', $name, $slug, (zendkee_is_set($translate_these, $slug) ? 'checked' : ''));
                }

                $html .= '</div>
                    <div class="layui-col-md3">';

                foreach ($language_code_list_col4 as $slug => $name) {
                    $html .= sprintf('<div>
                <input type="checkbox" name="translate_these[]" lay-skin="primary" lay-filter="translate_these" title="%s" value="%s" %s />
                </div>
                ', $name, $slug, (zendkee_is_set($translate_these, $slug) ? 'checked' : ''));
                }

                $html .= '</div>
                    </div>';


                echo $html;
                ?>

                <div class="layui-row">
                    <div class="layui-col-md1">
                        <button class="layui-btn layui-btn-normal create_google_translate_shortcode">生成短代码</button>
                    </div>
                    <div class="layui-col-md4">
                        <input type="text" class="layui-input" name="google_translate_shortcode" id="google_translate_shortcode" readonly>
                    </div>
                </div>


                <hr class="layui-bg-red">
                <h3>加入搜索框</h3>

                <p>请选择要加入搜索框的菜单</p>
                <?php
                $menu_to_add_search_bar = zendkee_get_option('menu_to_add_search_bar');
                $menus_objects = zendkee_get_all_wordpress_menus();

                if (count($menus_objects)) {
                    $menus_control = array('choose' => 'Choose');
                    foreach ($menus_objects as $each) {
                        printf('<div class="layui-input-block">
                <input type="checkbox" name="menu_to_add_search_bar[]" lay-skin="primary" lay-filter="menu_to_add_search_bar" title="%s" value="%s" %s />
                </div>', $each->name, $each->slug, (zendkee_is_set($menu_to_add_search_bar, $each->slug) ? 'checked' : ''));
                    }
                } else {
                    printf('没有菜单，请先<a href="/wp-admin/nav-menus.php">点这里</a>建立菜单');
                }


                ?>

            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">图像管理</h2>

            <div class="layui-colla-content">
                <h3>生成图像尺寸管理</h3>
                <div class="layui-form-item">
                    <label class="layui-form-label">禁止生成图像尺寸</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="disable_builtin_size" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($disable_builtin_size, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">全选/全不选</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="image_size_select_all" lay-filter="image_size_select_all" value="1" lay-skin="switch" lay-text="Select All|Select None">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">禁用以下图像尺寸</label>
                    <div class="layui-input-block disable_image_size">
                        <?php
                        foreach ($new_sizes as $name => $size) {
                            printf('<input type="checkbox" name="disable_image_size[]" lay-skin="primary" title="%s"  value="%s" %s />', ($name . ': ' . $size['width'] . ' * ' . $size['height']), $name, (zendkee_is_set($disable_image_size, $name) ? 'checked' : ''));
                        }
                        ?>

                    </div>
                </div>


                <hr class="layui-bg-blue">

                <h3>前台JS隐藏图片title</h3>
                <div class="layui-form-item">
                    <label class="layui-form-label">隐藏图片title</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="image_hide_title" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($image_hide_title, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <hr class="layui-bg-blue">

                <h3>支持webp格式图片</h3>
                <div class="layui-form-item">
                    <label class="layui-form-label">开启支持</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_webp" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($enable_webp, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>



            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">菜单高级设置</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">移除菜单的id与class</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="remove_menu_id_class" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($remove_menu_id_class, '1') ? 'checked' : ''; ?>>
                        <p class="layui-bg-red">请谨慎开启此项。开启后，前端菜单样式与效果可能不出现</p>
                    </div>
                </div>

            </div>
        </div>



        <div class="layui-colla-item">
            <h2 class="layui-colla-title">自定义字体</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用编辑器加入可选字体</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_custom_font" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($enable_custom_font, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">编辑器加入可选字体</label>
                    <button type="button" class="layui-btn layui-btn-normal layui-btn-sm add_font">添加字体</button>
                    <div class="layui-input-block">
                        <div class="fonts_list_container">
                            <?php
                            if ($zk_font_name) {
                                foreach ((array)$zk_font_name as $key => $font_name) {

                            ?>
                                    <div class="layui-form-item sortable">
                                        <div class="layui-input-inline w30">
                                            字体显示名字：<input type="text" name="zk_font_name[]" lay-skin="switch" value="<?php echo $font_name; ?>">
                                        </div>

                                        <div class="layui-input-inline w30">
                                            字体代码：<input type="text" name="zk_font_value[]" lay-skin="switch" value="<?php echo $zk_font_value[$key]; ?>">
                                        </div>

                                        <div class="layui-input-inline">
                                            <button type="button" class="layui-btn layui-btn-danger layui-btn-sm remove_font">删除</button>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>




        <div class="layui-colla-item">
            <h2 class="layui-colla-title">谷歌代码-全局</h2>
            <div class="layui-colla-content">


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GSC<br><br><br>
                        <span style="color:red;">(这段代码放置在&lt;head&gt;标签里面)</span></label>
                    <div class="layui-input-block">
                        <textarea name="misc_gsc" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_gsc); ?></textarea>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GA<br><br><br><span style="color:red;">(这段代码放置在&lt;head&gt;标签里面)</span></label>
                    <div class="layui-input-block">
                        <textarea name="misc_ga" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_ga); ?></textarea>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GTM Header<br><br><br><span style="color:red;">(这段代码放置在&lt;head&gt;标签里面</span></label>
                    <div class="layui-input-block">
                        <textarea name="misc_gtm_header" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_gtm_header); ?></textarea>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GTM Footer<br><br><br><span style="color:red;">(这段代码放在&lt;body&gt;标签后面（紧贴&lt;body&gt;标签）</span></label>
                    <div class="layui-input-block">
                        <textarea name="misc_gtm_footer" placeholder="This code will place immediately after the opening <body> tag
这段代码放在<body>标签后面（紧贴<body>标签）" class="layui-textarea" rows="10"><?php zk_e($misc_gtm_footer); ?></textarea>
                    </div>
                </div>




            </div>
        </div>



        <div class="layui-colla-item">
            <h2 class="layui-colla-title">谷歌代码-独立页</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用独立页代码</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_page_code" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_page_code, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">Thanks页的谷歌代码。代码位置不留空，则对应位置的代码会覆盖全局代码。</blockquote>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">选择Thanks页</label>
                    <div class="layui-input-block">
                        <select name="misc_page_code_pageid">
                            <option value=""></option>
                            <?php foreach ($misc_all_publish_pages as $page) {
                                printf(
                                    '<option value="%1$s" %4$s>%2$s &nbsp;&nbsp; | &nbsp;&nbsp; %3$s</option>',
                                    $page['id'],
                                    $page['post_title'],
                                    $page['url'],
                                    ($misc_page_code_pageid == $page['id'] ? 'selected' : '')
                                );
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GSC<br><br><br>
                        <span style="color:red;">(这段代码放置在&lt;head&gt;标签里面)<br></span>
                        <span>如果留空，则使用全局GSC代码。<br>Tips：填入一个空格，可使该独立页面不输出GSC代码。</span>
                    </label>
                    <div class="layui-input-block">
                        <textarea name="misc_gsc_page[thanks]" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_gsc_page['thanks']); ?></textarea>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GA<br><br><br>
                        <span style="color:red;">(这段代码放置在&lt;head&gt;标签里面)<br></span>
                        <span>如果留空，则使用全局GA代码。<br>Tips：填入一个空格，可使该独立页面不输出GA代码。</span>
                    </label>
                    <div class="layui-input-block">
                        <textarea name="misc_ga_page[thanks]" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_ga_page['thanks']); ?></textarea>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GTM Header<br><br><br>
                        <span style="color:red;">(这段代码放置在&lt;head&gt;标签里面)<br></span>
                        <span>如果留空，则使用全局GTM Header代码。<br>Tips：填入一个空格，可使该独立页面不输出GTM Header代码。</span>
                    </label>
                    <div class="layui-input-block">
                        <textarea name="misc_gtm_header_page[thanks]" placeholder="This code will place inside <head> tag
这段代码放置在<head>标签里面" class="layui-textarea" rows="10"><?php zk_e($misc_gtm_header_page['thanks']); ?></textarea>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">GTM Footer<br><br><br>
                        <span style="color:red;">(这段代码放在&lt;body&gt;标签后面（紧贴&lt;body&gt;标签）<br></span>
                        <span>如果留空，则使用全局GTM Footer代码。<br>Tips：填入一个空格，可使该独立页面不输出GTM Footer代码。</span>
                    </label>
                    <div class="layui-input-block">
                        <textarea name="misc_gtm_footer_page[thanks]" placeholder="This code will place immediately after the opening <body> tag
这段代码放在<body>标签后面（紧贴<body>标签）" class="layui-textarea" rows="10"><?php zk_e($misc_gtm_footer_page['thanks']); ?></textarea>
                    </div>
                </div>

            </div>
        </div>



        <div class="layui-colla-item">
            <h2 class="layui-colla-title">联系表单</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">增强表单体验</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enhance_form" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enhance_form, '1') ? 'checked' : ''; ?>>
                    </div>
                    <i><small>防止重复提交，检查输入数据，增强型下拉表单-适用于<a href="https://contactform7.com/listo/" target="_blank">Listo</a>的国家和货币</small></i>
                </div>



                <div class="layui-form-item">
                    <label class="layui-form-label">选择按钮背景颜色</label>
                    <div class="layui-input-block">
                        <div id="form_select_button_bg_color_selector"></div>
                        <input type="hidden" name="form_select_button_bg_color" autocomplete="off" class="layui-input" value="<?php zk_e($form_select_button_bg_color); ?>">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">提交按钮背景颜色</label>
                    <div class="layui-input-block">
                        <div id="form_submit_button_bg_color_selector"></div>
                        <input type="hidden" name="form_submit_button_bg_color" autocomplete="off" class="layui-input" value="<?php zk_e($form_submit_button_bg_color); ?>">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">按钮圆角大小</label>
                    <div class="layui-input-block">
                        <input type="number" name="form_button_radius_size" value="<?php echo $form_button_radius_size; ?>" /> 像素
                    </div>
                </div>




                <hr class="layui-bg-blue">


                <div class="layui-form-item">
                    <label class="layui-form-label">产品详情页表单-短代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="product_detail_contact_form" autocomplete="off" class="layui-input" value="<?php zk_e($product_detail_contact_form); ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">表单显示位置</label>
                    <div class="layui-input-block">
                        <select name="product_detail_form_priority">
                            <option value="">选择</option>
                            <option value="before_related_product" <?php echo zendkee_is_set($product_detail_form_priority, 'before_related_product') ? 'selected' : ''; ?>>
                                相关产品之上
                            </option>
                            <option value="after_related_product" <?php echo zendkee_is_set($product_detail_form_priority, 'after_related_product') ? 'selected' : ''; ?>>
                                相关产品之下
                            </option>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">显示跳转按钮</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="jumpto" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($jumpto, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">按钮位置</label>
                    <div class="layui-input-block">
                        <input type="radio" name="jumpto_position" value="b2b" title="B2B端模板" <?php echo zendkee_is_set($jumpto_position, 'b2b') ? 'checked' : ''; ?>>
                        <input type="radio" name="jumpto_position" value="b2c" title="B2C端模板" <?php echo zendkee_is_set($jumpto_position, 'b2c') ? 'checked' : ''; ?>>
                    </div>
                    <i><small>选择B2C端模板，产品没有价格的时候，不显示跳转按钮。需要修改wordpress模板。 站点上的产品如果是有价格与没价格混合，请调试好两种情况的样式。</small></i>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">跳转按钮名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="jumpto_text" autocomplete="off" class="layui-input" value="<?php zk_e($jumpto_text == '' ? 'Get A Quote' : $jumpto_text); ?>">
                    </div>
                </div>


                <hr class="layui-bg-blue">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用产品分类页表单</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_product_list_contact_form" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_product_list_contact_form, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">产品分类页表单-短代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="product_list_contact_form" autocomplete="off" class="layui-input" value="<?php zk_e($product_list_contact_form); ?>">
                    </div>
                </div>

                <hr class="layui-bg-blue">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用Footer表单</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_footer_contact_form" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_footer_contact_form, '1') ? 'checked' : ''; ?>>
                    </div>
                    <i><small>目前支持：Avada主题</small></i>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Footer表单-短代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="footer_contact_form" autocomplete="off" class="layui-input" value="<?php zk_e($footer_contact_form); ?>">
                    </div>
                </div>



            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">浮动栏</h2>
            <div class="layui-colla-content">


                <div class="layui-form-item">
                    <label class="layui-form-label">启用置顶按钮</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="scroll_top" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($scroll_top, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">启用浮动表单</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_float_form" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_float_form, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">浮动表单-短代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="float_contact_form" autocomplete="off" class="layui-input" value="<?php zk_e($float_contact_form); ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">浮动表单-按钮文字</label>
                    <div class="layui-input-block">
                        <input type="text" name="quote_text" autocomplete="off" class="layui-input" value="<?php zk_e($quote_text); ?>">
                    </div>
                </div>



                <div class="layui-form-item">
                    <label class="layui-form-label">背景颜色</label>
                    <div class="layui-input-block">
                        <div id="float_button_bg_color_selector"></div>
                        <input type="hidden" name="float_button_bg_color" autocomplete="off" class="layui-input" value="<?php zk_e($float_button_bg_color); ?>">
                    </div>
                </div>


            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">引入外部JS</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用外部JS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_outer_js" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_outer_js, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">引入位置</label>
                    <div class="layui-input-block">
                        <input type="radio" name="outer_js_position" value="header" title="头部" <?php echo zendkee_is_set($outer_js_position, 'header') ? 'checked' : ''; ?>>
                        <input type="radio" name="outer_js_position" value="footer" title="尾部" <?php echo zendkee_is_set($outer_js_position, 'footer') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">依赖</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="outer_js_dependence[]" title="jQuery" value="jquery" <?php echo zendkee_is_set($outer_js_dependence, 'jquery') ? 'checked' : ''; ?>>
                        <input type="text" name="outer_js_dependence_other" placeholder="请输入其他依赖的库，英文逗号隔开，全部小写。不正确的依赖，前台不会调用此js" autocomplete="off" class="layui-input" value="<?php zk_e($outer_js_dependence_other); ?>">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">外部JS</label>
                    <div class="layui-input-block">
                        <textarea name="outer_js" placeholder="每行一个js，注意顺序" class="layui-textarea" rows="6"><?php zk_e($outer_js); ?></textarea>
                    </div>
                </div>

            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">引入外部CSS</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用外部CSS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_outer_css" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_outer_css, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">外部css</label>
                    <div class="layui-input-block">
                        <textarea name="outer_css" placeholder="每行一个css，注意顺序" class="layui-textarea" rows="6"><?php zk_e($outer_css); ?></textarea>
                    </div>
                </div>

            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">全局JS</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">启用前台JS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_frontend_js" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_frontend_js, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">前台JS</label>
                    <div class="layui-input-block">
                        <textarea name="frontend_js" placeholder="这段代码放在前台页脚" class="layui-textarea" rows="20"><?php zk_e($frontend_js); ?></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">启用后台JS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_backend_js" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_backend_js, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">后台JS</label>
                    <div class="layui-input-block">
                        <textarea name="backend_js" placeholder="这段代码放在后台页脚" class="layui-textarea" rows="20"><?php zk_e($backend_js); ?></textarea>
                    </div>
                </div>


            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">全局CSS</h2>
            <div class="layui-colla-content">


                <div class="layui-form-item">
                    <label class="layui-form-label">启用前台CSS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_frontend_css" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_frontend_css, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">引入位置</label>
                    <div class="layui-input-block">
                        <input type="radio" name="frontend_css_position" value="header" title="头部" <?php echo zendkee_is_set($frontend_css_position, 'header') ? 'checked' : ''; ?>>
                        <input type="radio" name="frontend_css_position" value="footer" title="尾部" <?php echo zendkee_is_set($frontend_css_position, 'footer') ? 'checked' : ''; ?>>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">前台CSS</label>
                    <div class="layui-input-block">
                        <textarea name="frontend_css" placeholder="这段代码放在前台页脚" class="layui-textarea" rows="20"><?php zk_e($frontend_css); ?></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">启用后台CSS</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_backend_css" lay-skin="switch" lay-text="ON|OFF" value="1" <?php echo zendkee_is_set($enable_backend_css, '1') ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">后台CSS</label>
                    <div class="layui-input-block">
                        <textarea name="backend_css" placeholder="这段代码放在后台页脚" class="layui-textarea" rows="20"><?php zk_e($backend_css); ?></textarea>
                    </div>
                </div>


            </div>
        </div>

        <div class="layui-colla-item">
            <h2 class="layui-colla-title">系统调试模式</h2>
            <div class="layui-colla-content">

                <div class="layui-form-item">
                    <label class="layui-form-label">开启系统调试模式</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="enable_debug" value="1" lay-skin="switch" lay-text="ON|OFF" <?php echo zendkee_is_set($enable_debug, '1') ? 'checked' : ''; ?>>
                    </div>

                    <p>
                        <br>
                        前台调试需要加入参数debug=xxx
                        <br>
                        调试数据在页脚
                    </p>

                </div>

            </div>
        </div>
    </div>


    <button class="layui-btn" lay-submit="" lay-filter="misc">保存</button>


</form>


<script type="text/template" id="font-add-template">
    <div class="layui-form-item sortable">
        <div class="layui-input-inline w30">
            字体显示名字：<input type="text" name="zk_font_name[]" lay-skin="switch" value="">
        </div>

        <div class="layui-input-inline w30">
            字体代码：<input type="text" name="zk_font_value[]" lay-skin="switch" value="">
        </div>

        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-danger layui-btn-sm remove_font">删除</button>
        </div>
    </div>
</script>


<!--
拦截垃圾评论
// 垃圾评论拦截
class anti_spam {
    function anti_spam() {
            add_action('template_redirect', array($this, 'w_tb'), 1);
            add_action('init', array($this, 'gate'), 1);
            add_action('preprocess_comment', array($this, 'sink'), 1);
            add_action('wp_head',function (){
                echo '<style>
                    textarea.super-comment{
                    display: none !important;
                    }
                </style>';
            });
    }
    function w_tb() {
        if ( is_singular() ) {
            ob_start(function($s){
                return preg_replace("#textarea(.*?)id=(?:[\"'])comment(?:[\"'])(.*?)name=(?:[\"'])comment(?:[\"'])(.+)/textarea>#",
                    "textarea$1$2name=\"w\"$3/textarea><textarea name=\"comment\" cols=\"100%\" rows=\"4\" class=\"comment super-comment\"></textarea>",$s);
            });
        }
    }
    function gate() {
        if ( !empty($_POST['w']) && empty($_POST['comment']) ) {
            $_POST['comment'] = $_POST['w'];
        } else {
            $request = $_SERVER['REQUEST_URI'];
            $referer = isset($_SERVER['HTTP_REFERER'])         ? $_SERVER['HTTP_REFERER']         : '隐瞒';
            $IP      = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] . ' (透过代理)' : $_SERVER["REMOTE_ADDR"];
            $way     = isset($_POST['w'])                      ? '手动操作'                       : '未经评论表格';
            $spamcom = isset($_POST['comment'])                ? $_POST['comment']                : null;
            $_POST['spam_confirmed'] = "请求: ". $request. "\n来路: ". $referer. "\nIP: ". $IP. "\n方式: ". $way. "\n內容: ". $spamcom. "\n -- 记录成功 --";
        }
    }
    function sink( $comment ) {
        if ( !empty($_POST['spam_confirmed']) ) {
            if ( in_array( $comment['comment_type'], array('pingback', 'trackback') ) ) return $comment;
            //方法一: 直接挡掉, 將 die(); 前面两斜线刪除即可.


            //将此IP加入黑名单中
            $IP      = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
            $blacklist_keys = get_option('blacklist_keys');
            $blacklist_keys = explode("\n",$blacklist_keys);
            $blacklist_keys[] = $IP;
            $blacklist_keys = array_unique($blacklist_keys);

            update_option('blacklist_keys',implode("\n",$blacklist_keys));

            die();

            //方法二: 标记为 spam, 留在资料库检查是否误判.
            //add_filter('pre_comment_approved', create_function('', 'return "spam";'));
            //$comment['comment_content'] = "[ 小墙判断这是 Spam! ]\n". $_POST['spam_confirmed'];
        }
        return $comment;
    }
}
$anti_spam = new anti_spam();






jumpto button可以选择颜色







-->