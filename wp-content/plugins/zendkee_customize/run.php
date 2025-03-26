<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2019/3/18
 * Time: 18:09
 */

if (!defined('ABSPATH')) {
    exit();
}



/*****************       IP Access Control Start             ******************/
if (zendkee_get_option('enable_ip_rule')) {
    include(__DIR__ . '/include/run-ip-access-control.php');
}
/*****************       IP Access Control End             ******************/


/*****************       Region Access Control Start             ******************/
if (zendkee_get_option('enable_region_rule')) {
    include(__DIR__ . '/include/run-region-access-control.php');
}
/*****************       Region Access Control End             ******************/


/*****************       Small Feature Start             ******************/
require_once(__DIR__ . '/include/run-small-feature.php');
/*****************       Small Feature End             ******************/


/*****************       Optimize Start             ******************/
require_once(__DIR__ . '/include/run-optimize.php');
/*****************       Optimize End             ******************/


/*****************       Misc Start             ******************/
require_once(__DIR__ . '/include/run-misc.php');
/*****************       Misc End             ******************/


/*****************       SEO Start             ******************/
require_once(__DIR__ . '/include/run-seo.php');
/*****************       SEO End             ******************/

/*****************       Init Start             ******************/
require_once(__DIR__ . '/include/run-init.php');
/*****************       Init End             ******************/


/*****************       BUG FIX Start             ******************/
require_once(__DIR__ . '/include/run-bugfix.php');
/*****************       BUG FIX End             ******************/
