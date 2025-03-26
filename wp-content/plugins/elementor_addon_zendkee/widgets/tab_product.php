<?php
//namespace ElementorPro\Modules\Pricing\Widgets;
namespace Elementor;
//
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

//use ElementorPro\Base\Base_Widget;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Widget_tab_product extends Widget_Base
{

    public function get_name()
    {
        return 'tab-product';
    }

    public function get_title()
    {
        return __('Tab Product', 'elementor-pro');
    }

    public function get_icon()
    {
        return 'fa fa-file-text-o';
    }

    public function get_keywords()
    {
        return ['tab', 'product'];
    }

    public function get_categories()
    {
        return ['new-section'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_list',
            [
                'label' => __('Tabs', 'elementor-pro'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name',
            [
                'label' => __('Tab Name', 'elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => false,
                ],
            ]
        );

        $post_category_objects = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));


        $product_category_array = array();
        foreach ($post_category_objects as $item) {
            $product_category_array = array_merge($product_category_array, array(
                $item->slug => $item->name . ' ( ' . $item->count . ' )',
            ));
        }
//        $product_category_array = array('aaaaa','bbbbbb');

//        var_dump($product_category_array);

        $repeater->add_control(
            'product_cat',
            [
                'label' => __('Product Category', 'elementor-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => $product_category_array,
            ]
        );

        /*
         *
            ‘none‘ – No order (available since version 2.8).
            ‘ID‘ – Order by post id. Note the capitalization.
            ‘author‘ – Order by author.
            ‘title‘ – Order by title.
            ‘name‘ – Order by post name (post slug).
            ‘type‘ – Order by post type (available since version 4.0).
            ‘date‘ – Order by date.
            ‘modified‘ – Order by last modified date.
            ‘parent‘ – Order by post/page parent id.
            ‘rand‘ – Random order.
         * */
        $repeater->add_control(
            'orderby',
            [
                'label' => __('Order By', 'elementor-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'ID' => 'ID',
                    'name' => 'Name',
                    'title' => 'Title',
                    'price' => 'Price',
                    'date' => 'Publish Date',
                    'modified' => 'Last Modified',
                    'rand' => 'Random',
                    'menu_order title' => 'Custom Ordering + Name',
                ),
            ]
        );

        $repeater->add_control(
            'order',
            [
                'label' => __('Order', 'elementor-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => array('ASC'=>'ASC', 'DESC'=>'DESC'),
            ]
        );


        $repeater->add_control(
            'posts_per_page',
            [
                'label' => __('Products Per Page', 'elementor-pro'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 6,
            ]
        );


        $this->add_control(
            'tab_list',
            [
                'label' => __('Tab Items', 'elementor-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => __('First tab', 'elementor-pro'),
                    ],
                    [
                        'name' => __('Second tab', 'elementor-pro'),
                    ],
                    [
                        'name' => __('Third tab', 'elementor-pro'),
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'slidesPerView_mobile',
            [
                'label' => __( 'How Many Items To Preview In Mobile', 'elementor' ) ,
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 2,
            ]
        );

        $this->add_control(
            'slidesPerView_pc',
            [
                'label' => __( 'How Many Items To Preview In PC', 'elementor' ) ,
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 4,
            ]
        );


        $this->end_controls_section();


        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $slidesPerView_mobile = $settings['slidesPerView_mobile'];
        $slidesPerView_pc = $settings['slidesPerView_pc'];

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        \ZK_TEMPLATE::set_config(array('cache'=>false));//no caching
        $zk_template = \ZK_TEMPLATE::getInstance();

        $tabs_title = array();
        $tabs_content = array();
        foreach ((array)$settings['tab_list'] as $item){
            $tabs_title[] = $item['name'];
            $options = array(
                'orderby' => $item['orderby'],
                'order' => $item['order'],
                'posts_per_page' => $item['posts_per_page'],
                'paged' => $paged,
                'post_status' => 'publish',
                'post_type' => 'product',
            );
            $tabs_content[] = zk_get_product_by_cat($item['product_cat'] , $options);

        }

        echo $zk_template->render('tab_product.html' , ['tabs_content'=>$tabs_content , 'tabs_title'=>$tabs_title , 'slidesPerView_mobile' => $slidesPerView_mobile, 'slidesPerView_pc'=>$slidesPerView_pc]);


    }

    /**
     * Render Price List widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template()
    {


    }
}


\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_tab_product() );