<?php
if (!defined('ABSPATH')) {
    exit();
}



/* get value start */
$remove_meta_og = zendkee_get_option('remove_meta_og');
$auto_complete_alt = zendkee_get_option('auto_complete_alt');
$add_site_title_to_alt = zendkee_get_option('add_site_title_to_alt');
$site_title = zendkee_get_option('site_title');
$hot_product = zendkee_get_option('hot_product');
$industry_name = zendkee_get_option('industry_name');
$site_type = zendkee_get_option('site_type') == 'toc' ? 'toc' : 'tob';




$special_page_seo_page = zendkee_get_option('special_page_seo_page');
$special_page_seo_title = zendkee_get_option('special_page_seo_title');
$special_page_seo_description = zendkee_get_option('special_page_seo_description');
/* get value end */


/* set default start */
//$seo_page_keywords = $seo_page_keywords ? $seo_page_keywords : $seo_page_keywords_default;


require_once(__DIR__.'/seo_template.php');

$seo_value = array(
    'seo_page_title' => zendkee_get_option('seo_page_title'),
    'seo_page_description' => zendkee_get_option('seo_page_description'),
    'seo_post_title' => zendkee_get_option('seo_post_title'),
    'seo_post_description' => zendkee_get_option('seo_post_description'),
    'seo_product_title' => zendkee_get_option('seo_product_title'),
    'seo_product_description' => zendkee_get_option('seo_product_description'),
    'seo_product_cat_title' => zendkee_get_option('seo_product_cat_title'),
    'seo_product_cat_description' => zendkee_get_option('seo_product_cat_description'),

);

$seo_value = zendkee_seo_set_value($seo_value , $seo_default , $site_type);

extract($seo_value);


if ($site_title == ""){
    $site_title = get_option('blogname');
}

?>


<form class="form" id="form-seo">
    <button class="layui-btn" lay-submit="" lay-filter="seo">保存</button>
    <a href="https://docs.google.com/document/d/1Q65aBcTOF0-HkJkguWXUQadKy88qLRjmx2lAav-ePyA/edit#bookmark=id.9rszowhi36p0" target="_blank" style="float:right;" ><i class="layui-icon layui-icon-help"></i>使用说明</a>


    <div class="layui-collapse" lay-accordion>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">基础设置</h2>
            <div class="layui-colla-content">
                <h3>去除meta og标签</h3>
                <div class="layui-form-item">
                    <label class="layui-form-label">去除og标签</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="remove_meta_og" value="1" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo zendkee_is_set($remove_meta_og, '1') ? 'checked' : ''; ?> >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">自动补充图片ALT标签</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="auto_complete_alt" value="1" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo zendkee_is_set($auto_complete_alt, '1') ? 'checked' : ''; ?> >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">图片ALT标签前面加上Site Title</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="add_site_title_to_alt" value="1" lay-skin="switch"
                               lay-text="ON|OFF" <?php echo zendkee_is_set($add_site_title_to_alt, '1') ? 'checked' : ''; ?> >
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">公司名称/站点名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="site_title"  placeholder="请输入公司名称/站点名称" autocomplete="off" class="layui-input" value="<?php echo $site_title; ?>">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">行业名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="industry_name"  placeholder="请输入行业名称" autocomplete="off" class="layui-input" value="<?php echo $industry_name; ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">主推产品</label>
                    <div class="layui-input-block">
                        <input type="text" name="hot_product"  placeholder="请输入主推产品" autocomplete="off" class="layui-input" value="<?php echo $hot_product; ?>">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">站点类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="site_type" value="tob"
                               title="B站" <?php echo zendkee_is_set($site_type, 'tob') ? 'checked' : ''; ?>>
                        <input type="radio" name="site_type" value="toc"
                               title="C站" <?php echo zendkee_is_set($site_type, 'toc') ? 'checked' : ''; ?>>
                    </div>
                </div>


            </div>
        </div>



        <div class="layui-colla-item">
            <h2 class="layui-colla-title">Page规则</h2>
            <div class="layui-colla-content">
                <div class="seo_available_tag">可用标签：
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分类名称">%%term_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="父级标题">%%parent_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%page%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="公司名称/站点名称">%%sitename%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点描述">%%sitedesc%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="主推产品">%%hot_product%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="行业名称">%%industry_name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="日期">%%date%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分隔符">%%sep%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点链接">%%BLOGLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="作者">%%name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章链接">%%POSTLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章类型">%%pt_plural%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="搜索词">%%searchphrase%%</span>

                </div>

<!--                <div class="layui-form-item">-->
<!--                    <label class="layui-form-label">Keywords</label>-->
<!--                    <div class="layui-input-block">-->
<!--                        <input type="text" name="seo_page_keywords" required  lay-verify="required" placeholder="请输入关键词规则" autocomplete="off" class="layui-input" value="--><?php //echo $seo_page_keywords; ?><!--">-->
<!--                    </div>-->
<!--                </div>-->

                <div class="layui-form-item">
                    <label class="layui-form-label">Title</label>
                    <div class="layui-input-block">
                        <input type="text" name="seo_page_title" required  lay-verify="required" placeholder="请输入标题规则" autocomplete="off" class="layui-input" value="<?php echo $seo_page_title; ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Description</label>
                    <div class="layui-input-block">
                        <textarea name="seo_page_description" placeholder="请输入描述规则" class="layui-textarea"><?php echo $seo_page_description; ?></textarea>

                    </div>
                </div>
            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">Post规则</h2>
            <div class="layui-colla-content">
                <div class="seo_available_tag">可用标签：
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分类名称">%%term_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="父级标题">%%parent_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%page%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="公司名称/站点名称">%%sitename%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点描述">%%sitedesc%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="主推产品">%%hot_product%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="行业名称">%%industry_name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="日期">%%date%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分隔符">%%sep%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点链接">%%BLOGLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="作者">%%name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章链接">%%POSTLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章类型">%%pt_plural%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="搜索词">%%searchphrase%%</span>

                </div>

<!--                <div class="layui-form-item">-->
<!--                    <label class="layui-form-label">Keywords</label>-->
<!--                    <div class="layui-input-block">-->
<!--                        <input type="text" name="seo_post_keywords" required  lay-verify="required" placeholder="请输入关键词规则" autocomplete="off" class="layui-input" value="--><?php //echo $seo_post_keywords; ?><!--">-->
<!--                    </div>-->
<!--                </div>-->

                <div class="layui-form-item">
                    <label class="layui-form-label">Title</label>
                    <div class="layui-input-block">
                        <input type="text" name="seo_post_title" required  lay-verify="required" placeholder="请输入标题规则" autocomplete="off" class="layui-input" value="<?php echo $seo_post_title; ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Description</label>
                    <div class="layui-input-block">
                        <textarea name="seo_post_description" placeholder="请输入描述规则" class="layui-textarea"><?php echo $seo_post_description; ?></textarea>

                    </div>
                </div>
            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">Product规则</h2>
            <div class="layui-colla-content">
                <div class="seo_available_tag">可用标签：
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分类名称">%%term_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="父级标题">%%parent_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%page%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="公司名称/站点名称">%%sitename%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点描述">%%sitedesc%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="主推产品">%%hot_product%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="行业名称">%%industry_name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="日期">%%date%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分隔符">%%sep%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点链接">%%BLOGLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="作者">%%name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章链接">%%POSTLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章类型">%%pt_plural%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="搜索词">%%searchphrase%%</span>

                </div>

<!--                <div class="layui-form-item">-->
<!--                    <label class="layui-form-label">Keywords</label>-->
<!--                    <div class="layui-input-block">-->
<!--                        <input type="text" name="seo_product_keywords" required  lay-verify="required" placeholder="请输入关键词规则" autocomplete="off" class="layui-input" value="--><?php //echo $seo_product_keywords; ?><!--">-->
<!--                    </div>-->
<!--                </div>-->

                <div class="layui-form-item">
                    <label class="layui-form-label">Title</label>
                    <div class="layui-input-block">
                        <input type="text" name="seo_product_title" required  lay-verify="required" placeholder="请输入标题规则" autocomplete="off" class="layui-input" value="<?php echo $seo_product_title; ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Description</label>
                    <div class="layui-input-block">
                        <textarea name="seo_product_description" placeholder="请输入描述规则" class="layui-textarea"><?php echo $seo_product_description; ?></textarea>

                    </div>
                </div>
            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">Product Category规则</h2>
            <div class="layui-colla-content">
                <div class="seo_available_tag">可用标签：
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分类名称">%%term_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="父级标题">%%parent_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%page%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="公司名称/站点名称">%%sitename%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点描述">%%sitedesc%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="主推产品">%%hot_product%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="行业名称">%%industry_name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="日期">%%date%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分隔符">%%sep%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点链接">%%BLOGLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="作者">%%name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章链接">%%POSTLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章类型">%%pt_plural%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="搜索词">%%searchphrase%%</span>

                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Title</label>
                    <div class="layui-input-block">
                        <input type="text" name="seo_product_cat_title" required  lay-verify="required" placeholder="请输入标题规则" autocomplete="off" class="layui-input" value="<?php echo $seo_product_cat_title; ?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Description</label>
                    <div class="layui-input-block">
                        <textarea name="seo_product_cat_description" placeholder="请输入描述规则" class="layui-textarea"><?php echo $seo_product_cat_description; ?></textarea>

                    </div>
                </div>
            </div>
        </div>


        <div class="layui-colla-item">
            <h2 class="layui-colla-title">具体页面设置</h2>
            <div class="layui-colla-content">
                <div class="seo_available_tag">可用标签：
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分类名称">%%term_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="父级标题">%%parent_title%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="标题">%%page%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="公司名称/站点名称">%%sitename%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点描述">%%sitedesc%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="主推产品">%%hot_product%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="行业名称">%%industry_name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="日期">%%date%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="分隔符">%%sep%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="站点链接">%%BLOGLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="作者">%%name%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章链接">%%POSTLINK%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="文章类型">%%pt_plural%%</span>
                    <span class="layui-btn layui-btn-sm layui-btn-primary" data-usage="搜索词">%%searchphrase%%</span>

                </div>

                <div class="special_page_seo_container">

                    <?php
//                    var_dump($special_page_seo_page);
//                    var_dump($special_page_seo_title);
//                    var_dump($special_page_seo_description);
                    if(empty($special_page_seo_page)){
                        $special_page_seo_page = array('');
                    }
                        foreach ($special_page_seo_page as $key => $page_id){
                    ?>


                    <div class="special_page_seo_item">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><button type="button" class="layui-btn layui-btn-danger layui-btn-sm remove_special_page_seo_item"><i class="layui-icon">&#xe640;</i></button> 页面</label>
                            <div class="layui-input-block">
                                <select name="special_page_seo_page[]" class="special_page_seo_page" lay-filter="special_page_seo_page">
                                    <option value=""></option>
                                    <?php
//                                    $pages = get_pages(array('post_status'=>array('publish','draft')));
                                    $pages = get_pages(array('post_status'=>array('publish')));
                                    foreach($pages as $page){
                                        printf('<option %s value="%s" data-title="%s">(%s) %s</option>' , ($page_id==$page->ID ? 'selected="selected"' : '') ,$page->ID, addslashes($page->post_title) , $page->ID, $page->post_title);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">Title</label>
                            <div class="layui-input-block">
                                <input type="text" name="special_page_seo_title[]" placeholder="请输入标题规则" autocomplete="off" class="layui-input special_page_seo_title" value="<?php echo $special_page_seo_title[$key]; ?>">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">Description</label>
                            <div class="layui-input-block">
                                <textarea name="special_page_seo_description[]" placeholder="请输入描述规则" class="layui-textarea special_page_seo_description"><?php echo $special_page_seo_description[$key]; ?></textarea>

                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>

                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal add_special_page_seo_item">增加页面设置</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <button class="layui-btn" lay-submit="" lay-filter="seo">保存</button>


</form>


