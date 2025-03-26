<?php

//contact form 7 DB 增加editor的阅读权限
add_filter( 'user_has_cap', function($allcaps, $caps, $args, $current_user){

    //当前的用户使editor
    if(in_array('editor' , $current_user->roles)){
        $allcaps['cfdb7_access'] = true;
    }

    return $allcaps;

} , 10 , 4);



//add_action('admin_menu', function(){
//
//    CLASS ZENDKEE_SurStudioPluginTranslatorRevolutionDropDownAdmin extends SurStudioPluginTranslatorRevolutionDropDownAdmin{
//        public function _render_client_form(){
//            $form = new SurStudioPluginTranslatorRevolutionDropDownAdminForm();
//
//            ob_start();
//            include_once(ELEMENT_ADDON_ZENDKEE_TEMPLATE.'client_form.tpl');
//            $content = ob_get_clean();
//
//            echo $form->render(array(
//                'type' => 'html',
//                'content' => $content,
//                'meta_tag_rules' => self::_gen_meta_tag_rules_for_tabs()
//            ));
//        }
//    }
//
//
//
//    $current_user = wp_get_current_user();
//
//    //当前的用户使editor
//    if(in_array('editor' , $current_user->roles)){
//
//        $main_handle = SurStudioPluginTranslatorRevolutionDropDownConfig::getAdminHandle();
//
//        $capability = 'edit_pages';
//
//
//        add_menu_page('Ehai Translation', 'Ehai Translation', $capability , $main_handle, array('ZENDKEE_SurStudioPluginTranslatorRevolutionDropDownAdmin', '_render_client_form'), 'dashicons-translation');
//
//        SurStudioPluginTranslatorRevolutionDropDownAdminForm::save();
//    }
//
//});







/*
 * 翻译服务
	苏尔工作室
	谷歌

 *
 * */