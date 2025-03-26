<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2021/3/9 0009
 * Time: 9:54
 */

if(!function_exists('zendkee_is_content_editor')){
    function zendkee_is_content_editor(){

        $user  = wp_get_current_user();
        $user_roles = $user->roles;

        //function in :wp-content/plugins/elementor-pro/modules/role-manager/module.php : get_role_manager_options()
        $restrictions = get_option( 'elementor_role-manager', [] );

//        var_dump($user_roles);
//        var_dump($restrictions);

        foreach ($user_roles as $role){
            if(key_exists($role, (array)$restrictions) && in_array('design',(array)$restrictions[$role])){
                return true;
            }
        }

        return false;
    }
}


if(is_admin() && zendkee_is_content_editor()){

    add_action('admin_print_footer_scripts',function(){
        echo '<style type="text/css" id="disable_edit_functions">';
        include_once (__DIR__.'/disable_edit_functions.css');
        echo '</style>';
    });

}