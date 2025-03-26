<?php
/* default settings start */

function zendkee_seo_set_value(array $seo_value , array $seo_default , $site_type = 'tob'){
    foreach ($seo_value as $key => $value){
        if ($value === null or $value === false ){
            $seo_value[$key] = $seo_default[$site_type][$key];
        }
    }

    return $seo_value;
}


$seo_default = array(
    'tob' => array(
        'seo_page_title' => '%%title%% %%parent_title%% %%sep%% %%sitename%%',
        'seo_page_description' => 'View our solutions in %%industry_name%% and learn more about product application.',

        'seo_post_title' => '%%title%% %%sep%% %%sitename%%',
        'seo_post_description' => '%%date%%. View our blog about %%title%% and learn more industry insights and guidelines.',

        'seo_product_title' => 'Wholesale %%title%% %%sep%% %%sitename%%',
        'seo_product_description' => 'Premium %%title%% is availble at %%sitename%%. Click for info about %%title%%.',

        'seo_product_cat_title' => 'Wholesale %%term_title%% %%sep%% %%sitename%%',
        'seo_product_cat_description' => 'Premium %%term_title%% is availble at %%sitename%%. Click for info about %%term_title%%.',
    ),

    'toc' => array(
        'seo_page_title' => '%%title%% %%sep%% %%sitename%%',
        'seo_page_description' => '',

        'seo_post_title' => '%%title%% %%sep%% %%sitename%%',
        'seo_post_description' => '%%date%%. View our blog about %%title%% and learn about latest information and notice.
    ',

        'seo_product_title' => 'Best %%title%% for Sale %%sep%% %%sitename%%',
        'seo_product_description' => '%%sitename%% provides you with the most suitable %%title%% according to your preferences. Click for info about %%title%%.',

        'seo_product_cat_title' => 'Best %%term_title%% for Sale %%sep%% %%sitename%%',
        'seo_product_cat_description' => '%%sitename%% provides you with the most suitable %%product_type%% according to your preferences. Click for info about %%product_type%%.',
    )

);


$seo_default_js = array(
    'tob' => array(
        'home' => array(
            'title' => '%%hot_product%% Supplier/Manufacturer %%sep%% %%sitename%%',
            'description' => '%%sitename%% is a reliable %%hot_product%% supplier with quality products and timely customer service.'
        ),
        'about' => array(
            'title' => 'About %%sitename%% %%sep%% %%sitename%%',
            'description' => '%%sitename%% is leading company in the industry. View more information about %%sitename%% here.'
        ),
        'contact' => array(
            'title' => 'Contact Us %%sep%% %%sitename%%',
            'description' => 'You can contact us if you need any further support. Find out more about %%sitename%% here.'
        ),
        'service' => array(
            'title' => '%%industry_name%% Solutions %%sep%% %%sitename%%',
            'description' => 'View our solutions in %%industry_name%% and learn more about product application.'
        ),
        'solution' => array(
            'title' => '%%industry_name%% Solutions %%sep%% %%sitename%%',
            'description' => 'View our solutions in %%industry_name%% and learn more about product application.'
        ),
        'vr' => array(
            'title' => 'Visit %%sitename%% In VR %%sep%% %%sitename%%',
            'description' => 'Explore the VR experience with 360° imagery to visit %%sitename%% online. Click here to gain an immersive experience and in-deep understanding of %%sitename%%.'
        ),
    ),
    'toc' => array(
        'home' => array(
            'title' => 'High-Quality %%hot_product%% %%sep%% %%sitename%%',
            'description' => '%%sitename%% provides you with a variety of %%hot_product%% with various functions to meet the needs of different customers. Learn more about our quality products and 24/7 service.'
        ),
        'about' => array(
            'title' => 'About %%sitename%% %%sep%% %%sitename%%',
            'description' => '%%sitename%% is leading company in the industry. View more information about %%sitename%% here.'
        ),
        'contact' => array(
            'title' => 'Contact Us %%sep%% %%sitename%%',
            'description' => 'You can contact us if you need any further support. Find out more about %%sitename%% here.'
        ),
        'service' => array(
            'title' => '产品 Support Service %%sep%% %%sitename%%',
            'description' => 'More details about pre-sales and after-sales services are here for you. %%sitename%% provides you with professional help and support for 产品.'
        ),
        'solution' => array(
            'title' => '',
            'description' => ''
        ),
        'vr' => array(
            'title' => 'Visit %%sitename%% In VR %%sep%% %%sitename%%',
            'description' => 'Explore the VR experience with 360° imagery to visit %%sitename%% online. Click here to gain an immersive experience and in-deep understanding of %%sitename%%.'
        ),
    )
);


/* default settings end */


?><script type="text/javascript">
    var seo_template = <?php echo json_encode($seo_default_js);?>;
    // console.log(seo_template);
    // console.log(seo_template.tob);
</script>