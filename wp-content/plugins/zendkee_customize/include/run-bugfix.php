<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/11/12 0012
 * Time: 10:50
 */

//for Advanced CF7 DB
//解决时间不是根据timezone时区显示的问题
add_filter('vsz_cf7_modify_form_before_insert_data',function($contact_form){
    $contact_form->posted_data['submit_time'] = current_time('Y-m-d H:i:s');
    return $contact_form;
});