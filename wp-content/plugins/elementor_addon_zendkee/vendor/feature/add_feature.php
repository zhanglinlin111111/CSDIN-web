<?php

// //添加新的字体
// function custum_fontfamily ( $initArray ) {
//     $initArray['font_formats'] = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆';Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";

//     return $initArray;
// }
// add_filter('tiny_mce_before_init', 'custum_fontfamily');




// //自定义字体大小
// if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
//     function wpex_mce_text_sizes( $initArray ){
//         $initArray['fontsize_formats'] = "8px 9px 10px 12px 13px 14px 16px 18px 20px 21px 24px 28px 32px 36px 48px 60px 72px 96px";
//         return $initArray;
//     }
// }
// add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );