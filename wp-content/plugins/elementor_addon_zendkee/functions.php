<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/13 0013
 * Time: 14:35
 */



/** 扩展功能 start **/
final class Elementor_Zendkee_Extension {

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.6';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Elementor_Zendkee_Extension The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Elementor_Zendkee_Extension An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {

        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );




    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n() {

        load_plugin_textdomain( 'elementor-test-extension' );

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }


        /* 在elements 列表注册控件组 */
        add_action( 'elementor/elements/categories_registered', function ( $elements_manager ) {

            $elements_manager->add_category(
                'new-section',
                [
                    'title' => __( '新控件', 'elementor' ),
                    'icon' => 'fa fa-plug',
                ]
            );

        } );


        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );



        //load settings
        if(is_admin()){
            require_once (__DIR__.'/settings.php');
        }


        //zendkee:add tags
//        $this->init_tags();
        add_action('elementor/dynamic_tags/register_tags' , [$this , 'init_tags']);


        //zendkee:meta box for product
        $this->init_metas();


    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets() {

        //这里写上要加载的模块
        $Widget = array(
            'tab_product',
            'product_desc1',
            'product_desc2',
            'product_desc3',
            'product_desc4',
            'product_desc5',
            'product_cat_desc',
//            'image_carousel' => 'Image_Carousel_Zendkee',
//            'image' => 'Image_Zendkee',
//            'image_box' => 'Image_Box_Zendkee',
//            'image-gallery' => 'Image_Gallery_Zendkee',
        );

        foreach ($Widget as $widget_file_name ){
            $widget_file = __DIR__ . '/widgets/'.$widget_file_name.'.php';
            if(file_exists($widget_file)){
                require_once( $widget_file );
            }
        }




        //load js and css
        if (!is_admin()) {
//            wp_enqueue_style('nprogress_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/nprogress/nprogress.css');
//            wp_enqueue_script('nprogress_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/nprogress/nprogress.js', array('jquery'), '1.0', true);
//            wp_enqueue_script('ismobile_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/isMobile.min.js', [] , '1.1.1', false);



            //引入css
            wp_enqueue_style('element_addon_zendkee_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/frontend.css');
            //引入js

//            add_action('wp_footer',function(){
//                wp_enqueue_script('element_addon_zendkee_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/frontend.js', array('jquery'), '1.0', false);
//
//            } , 1000000000000);

            add_action('wp_enqueue_scripts',function (){
                wp_enqueue_script('element_addon_zendkee_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/frontend.js', array('jquery'), '1.0', true);

            } , 100);

        }


        if(is_admin()){
//            wp_enqueue_script('image_loaded_js', 'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js', array('jquery'), '1.0', false);
            wp_enqueue_script('element_addon_zendkee_backend_settings_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/backend_widgets.js', array('jquery'), '1.0', false);
            wp_enqueue_style('element_addon_zendkee_backend_settings_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/backend_widgets.css');

            $data = array(
                'admin_url' => admin_url(),
            );
        
            // Localize the script with the data
            wp_localize_script('element_addon_zendkee_backend_settings_js', 'zendkee_elementor_addon_js_object', $data);
        }



    }


    public function init_tags(){
        $tags = array(
            'product_desc1' => 'Zendkee_Product_DESC1',
            'product_desc2' => 'Zendkee_Product_DESC2',
            'product_desc3' => 'Zendkee_Product_DESC3',
            'product_desc4' => 'Zendkee_Product_DESC4',
            'product_desc5' => 'Zendkee_Product_DESC5',
            'product_cat_desc' => 'Zendkee_Product_Category_DESC2',
        );


        foreach ($tags as $tag_file_name => $tag_class_name){
            $tag_file = __DIR__ . '/tags/'.$tag_file_name.'.php';

            /** @var \Elementor\Core\DynamicTags\Manager $module */
            $module = \Elementor\Plugin::$instance->dynamic_tags;

            if(file_exists($tag_file)){
                require_once( $tag_file );

                $className = 'ElementorPro\Modules\Woocommerce\Tags\\'.$tag_class_name;

                if(class_exists($className)){
                    $module->register_tag( $className );
                }
            }

        }

    }


    public function init_metas(){
        $metas = array(
            'product_desc1',
            'product_desc2',
            'product_desc3',
            'product_desc4',
            'product_desc5',
            'product_cat_desc',
        );


        foreach ($metas as $meta_file_name){
            $tag_file = __DIR__ . '/meta_boxs/'.$meta_file_name.'.php';

            if(file_exists($tag_file)){
                require_once( $tag_file );
            }

        }



    }





    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_controls() {

        // Include Control files
//		require_once( __DIR__ . '/controls/test-control.php' );

        // Register control
//		\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

    }

}





if (!function_exists('zk_unique_id')) {
    function zk_unique_id($mix)
    {
        return md5(json_encode(array_merge($mix , array('paged'=>''))));
    }
}


if (!function_exists('zk_get_product_by_cat')) {
    function zk_get_product_by_cat($cat_slug, $options = array())
    {
//        global $wp_query, $post, $woocommerce, $query_string;

        $default = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 6,
            'paged' => 1,
            'post_status' => 'publish',
            'post_type' => 'product',
        );
        $options = array_merge($default, $options);

        $args = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',//此处参数指定为产品目录
                    'field' => 'slug',//调用依据为产品目录id
                    'terms' => $cat_slug,
                ),
            ),
            'posts_per_page' => $options['posts_per_page'],//一共需要调用的文章数量
            'paged' => $options['paged'], //分页
            'post_status' => $options['post_status'],//调用的文章为已经发布
            'post_type' => $options['post_type'],//调用的类型为产品（product）
            'no_found_rows' => 1,
            'orderby' => $options['orderby'],
            'order' => $options['order'],//文章排序为时间正排序
//            'meta_query' => array()//还可以使用post meta进行查询，这个和wordpress循环中使用一样
        );
//        var_dump($args);
        //以上为循环的参数

        //doc see : https://developer.wordpress.org/reference/classes/wp_query/
        $query = new WP_Query($args);//建立循环查询
//        echo "<pre>";
//        var_dump($query);
//        echo "</pre>";
        //开始循环
        $products = array();
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();

                $id = get_the_ID();
                $products[] = array(
                    'id' => $id,
                    'title' => get_the_title($id),
                    'url' => get_permalink($id),
                    'image' => get_the_post_thumbnail_url($id),
                    'image_html' => get_the_post_thumbnail($id),
                    'except' => get_the_excerpt($id),
                    'regular_price' => get_post_meta($id, '_regular_price', true),
                    'sale_price' => get_post_meta($id, '_sale_price', true),
                    'symbol' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '',
                );

            endwhile;  //结束循环
            wp_reset_query();//清除循环
        endif;

        $id = zk_unique_id($args);

        $pagination_html = pagination($args, $options['posts_per_page'], $options['paged'], array('wrap_before' => sprintf('<div class="pagination_ajax" data-for="%s">' , $id), 'show' => false));


        return array('id' => $id, 'products' => $products, 'pagination' => $pagination_html);
    }
}


//if(!function_exists('zk_pagination_meta')){
//    function zk_pagination_meta($query_string , $posts_per_page){
//        $query_string = array_merge($query_string , array(
//            'showposts' => -1,
//            'posts_per_page' => -1,
//        ) );
//        $posts = query_posts($query_string);
//        $total_posts = count($posts);
//        wp_reset_query();
//        return $total_posts;
//    }
//}
//
//
////未完待续
//if(!function_exists('zk_pagination')){
//    function zk_pagination($query_string , $posts_per_page , $page){
//        $total = zk_pagination_meta($query_string , $posts_per_page);
//
//        $page = (int)$page < 1 ? 1 : $page;
//        $prev = $page > 1 ? ($page-1) : 1;
//        $next = $page <= ($total - 1) ? ($page+1) : $total;
//
////        get_per
//
//        return sprintf('<div class="pagination"><a data-url="" data-page="" class="prev"></a> <span class="current-page">%s</span> <a data-url="%s" data-page="" class="next"></a></div>'  );
//    }
//}


if (!function_exists('pagination')) {
    function pagination($query_string, $posts_per_page, $paged, $args = array())
    {
        /*
        return format:
        wrap_before + ( link_before + LINK + link_class + NAME +  link_after + delimiter ) * N + wrap_after
        */
        $default = array(
            'delimiter' => '',
            'wrap_before' => '<div class="pagination">',
            'wrap_after' => '</div>',
            'link_before' => '',
            'link_after' => '',
            'link_class' => '',
            'current_before' => '<span>',
            'current_after' => '</span>',
            'current_class' => 'active',
            'show' => true,
        );

        $args = array_merge($default, $args);

        $output = '';
        $output .= $args['wrap_before'];

        $pagination_metadata = pagination_metadata($query_string, $posts_per_page, $paged);
        end($pagination_metadata);
        $last_key = key($pagination_metadata);
        foreach ($pagination_metadata as $key => $value) {
            if ($value['url']) {
                $output .= sprintf("%s<a href=\"%s\" class=\"%s\">%s</a>%s", $args['link_before'], $value['url'], $args['link_class'], $value['name'], $args['link_after']);
                if ($key != $last_key) {//not the last item
                    $output .= $args['delimiter'];
                }
            } else {//current page
                $output .= sprintf("%s%s%s", preg_replace("/<(\w+)([^>]*?)>/", "<$1$2 class=\"" . $args['current_class'] . "\">", $args['current_before']), $value['name'], $args['current_after']);
                if ($key != $last_key) {//not the last item
                    $output .= $args['delimiter'];
                }
            }
        }
        $output .= $args['wrap_after'];
        if ($args['show']) {
            echo $output;
        } else {
            return $output;
        }
    }
}


//分页
if (!function_exists('pagination_metadata')) {
    function pagination_metadata($query_string, $posts_per_page, $paged)
    {
        $query_string = array_merge($query_string, array(
            'showposts' => -1,
            'posts_per_page' => -1,
        ));

        $posts = query_posts($query_string);
        $total_posts = count($posts);
        if (empty($paged)) $paged = 1;
        $prev = $paged - 1;
        $next = $paged + 1;
        $range = 8; // 如果你想展示更多分页链接，修改它！
        $showitems = ($range * 2) + 1;
        $pages = ceil($total_posts / $posts_per_page);
        $page_data = array();
        if (1 != $pages) {
            if ($paged > 2 && $paged + $range + 1 > $pages && $showitems < $pages) {
                $page_data[] = array(
                    'name' => 'First',
                    'url' => get_pagenum_link(1),
                );
            }
            if ($paged > 1 && $showitems < $pages) {
                $page_data[] = array(
                    'name' => 'Prev',
                    'url' => get_pagenum_link($prev),
                );
            }
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    if ($paged == $i) {
                        $page_data[] = array(
                            'name' => $i,
                            'url' => '',
                        );
                    } else {
                        $page_data[] = array(
                            'name' => $i,
                            'url' => get_pagenum_link($i),
                        );
                    }
                }
            }
            if ($paged < $pages && $showitems < $pages) {
                $page_data[] = array(
                    'name' => 'Next',
                    'url' => get_pagenum_link($next),
                );
            }
            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                $page_data[] = array(
                    'name' => 'Last',
                    'url' => get_pagenum_link($pages),
                );
            }
        }
        wp_reset_query();
        return $page_data;
    }
}


if (!class_exists('ZK_TEMPLATE')) {
    class ZK_TEMPLATE
    {
        //私有属性，用于保存实例
        private static $instance;
        private $template_content;
        private $template_dir;
        private static $options = array();
        private $twig;


        private function __construct()
        {
            if (!defined('TWIG_AUTOLOAD_FILE')) {
                echo "'TWIG_AUTOLOAD_FILE' need to defined";
                return;
            }

            if (!defined('ELEMENT_ADDON_ZENDKEE_TEMPLATE')) {
                echo "'ELEMENT_ADDON_ZENDKEE_TEMPLATE' need to defined";
                return;
            }

            if (!file_exists(TWIG_CACHE_DIR)) {
                @mkdir(TWIG_CACHE_DIR);
                if (!file_exists(TWIG_CACHE_DIR)) {
                    echo "'TWIG_CACHE_DIR' can not create.";
                    return;
                }
            }


            require_once TWIG_AUTOLOAD_FILE;
            $this->template_dir = rtrim(ELEMENT_ADDON_ZENDKEE_TEMPLATE, '/') . '/';


            self::set_config(
                array_merge(array(
                    'cache' => TWIG_CACHE_DIR,
                ), self::$options)
            );

            $this->load();
        }

        //公有方法，用于获取实例
        public static function getInstance()
        {
            //判断实例有无创建，没有的话创建实例并返回，有的话直接返回
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // 克隆方法私有化，防止复制实例
        private function __clone()
        {
        }

        public static function set_config($options)
        {
            self::$options = array_merge(self::$options, $options);
        }

        private function load()
        {
            if (file_exists($this->template_dir) && is_readable($this->template_dir)) {
                $loader = new \Twig\Loader\FilesystemLoader($this->template_dir);
                $this->twig = new \Twig\Environment($loader, self::$options);
            } else {
                return false;
            }
        }

        public function render($template_name, $values)
        {
            $template_name = ltrim($template_name, '/');
            return $this->twig->render($template_name, $values);

        }

    }
}



if(!function_exists('zendkee_image_support_mobile_src')){
    function zendkee_image_support_mobile_src($html , $mobile_url){
        return preg_replace(array(
            "/<img\s(.*?)src=([\'\"])(.+?)\\2(.*?)\/>/",//replace url
            "/<img\s(.*?)class=([\'\"])(.+?)\\2(.*?)\/>/",//add class
            "/srcset=([\'\"]).*?\\1/",//remove attr srcset
        ), array(
            '<img src="'.ELEMENT_ADDON_ZENDKEE_URL.'libs/image/placeholder.png" $1data-pc-image-url="$3" data-mobile-image-url="'.$mobile_url.'"$4/>',
            '<img $1class="$3 img-zendkee-elementor-image"$4/>',
            '',
        ) , $html);
    }
}



//*****************************************************************************************************

//调试输出
if(!function_exists('dp')){
    function dp($s){
        echo '<pre>';
        var_dump($s);
        echo '</pre>';
    }
}

//配置类
if(!class_exists('ZENDKEE_OPTION')){

    class ZENDKEE_OPTION
    {
        //私有属性，用于保存实例
        private static $instance;
        protected $option_name = 'zendkee_customize';

        //构造方法私有化，防止外部创建实例
        private function __construct()
        {
            $this->options = (array)get_option($this->option_name);
        }

        //公有方法，用于获取实例
        public static function getInstance()
        {
            //判断实例有无创建，没有的话创建实例并返回，有的话直接返回
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        //克隆方法私有化，防止复制实例
        private function __clone()
        {
        }


        private $options;

        public function get($name)
        {
            if (!empty($this->options)) {
                foreach ((array)$this->options as $key => $option) {
                    if ($key === $name) {
                        return $option;
                    }
                }
            }

            return false;
        }

        public function update($name, $value)
        {
            if ($this->options == false) {
                $this->options = array();
            }

            $this->options = array_merge($this->options, array($name => $value));

            return true;
        }

        public function reset()
        {
            $this->options = array();
            return true;
        }

        public function save()
        {
            return update_option($this->option_name, $this->options);
        }

        public function remove($name){
            if (!empty($this->options)) {
                foreach ((array)$this->options as $key => $option) {
                    if ($key === $name) {
                        unset($this->options[$key]);
                        return array($key , $option);
                    }
                }
            }
            return false;
        }
    }
}


//获得配置
if(!function_exists('zendkee_get_option')){
    function zendkee_get_option($name)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->get($name);
    }
}


//更新配置
if(!function_exists('zendkee_update_option')){
    function zendkee_update_option($name, $value)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->update($name, $value);
    }
}


//重置配置
if(!function_exists('zendkee_reset_option')){
    function zendkee_reset_option()
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->reset();
    }
}


//保存配置
if(!function_exists('zendkee_save_option')){
    function zendkee_save_option()
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->save();
    }
}


//删除配置
if(!function_exists('zendkee_remove_option')){
    function zendkee_remove_option($name)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->remove($name);
    }
}

//检查键值是否存在
if(!function_exists('zendkee_is_set')){
    function zendkee_is_set($haystack, $needle)
    {
        if (is_string($haystack)) {
            if ($haystack == $needle) {
                return true;
            } else {
                return false;
            }
        } elseif (is_array($haystack)) {
            return in_array($needle, $haystack);
        }
    }
}


//获得所有wordpress菜单
if(!function_exists('zendkee_get_all_wordpress_menus')){
    function zendkee_get_all_wordpress_menus($hide_empty = true)
    {
        $hide_empty = $hide_empty === true ? true : false;
        return get_terms('nav_menu', array('hide_empty' => $hide_empty));
    }
}


//检测是否中文浏览器
if(!function_exists('zendkee_is_chinese_browser')){
    function zendkee_is_chinese_browser()
    {
        return preg_match("~zh-CN|zh~i", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }
}


//判断是否前台
if(!function_exists('zendkee_frontend')){
    function zendkee_frontend()
    {
        //不是后台、用户没登录、不是登录页
        if (!is_admin() && !is_user_logged_in() && !preg_match("~^/wp-login\.php~", $_SERVER['REQUEST_URI'])) {
            return true;
        } else {
            return false;
        }
    }
}


//返回值转义字符（将特殊字符转换为 HTML 实体）
if(!function_exists('zk_r')){
    function zk_r($value)
    {
        return htmlspecialchars($value);
    }
}

if(!function_exists('zk_e')){
    //输出值转义字符（将特殊字符转换为 HTML 实体）
    function zk_e($value)
    {
        echo htmlspecialchars($value);
    }
}






//获取激活的插件
if(!function_exists('zendkee_get_active_plugins')){
    function zendkee_get_active_plugins()
    {
        $the_plugs = get_option('active_plugins');
        $plugin_slugs = array();
        foreach ($the_plugs as $key => $value) {
            $string = explode('/', $value); // Folder name will be displayed
            $plugin_slugs[] = $string[0];
        }
        return $plugin_slugs;
    }
}





//判断字符串是否JSON格式{
if(!function_exists('isJson')){
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

