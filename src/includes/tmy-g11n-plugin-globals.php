<?php

/**
 * The file that defines the global variable and functions
 *
 * @since      1.0.0
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/includes
 * @author     Yu Shao <yu.shao.gm@gmail.com>
 */

function tmy_g11n_lang_sanitize( $lang ) {

    $default_lang = get_option('g11n_default_lang', 'English');
    if (strcmp($lang,'')!==0) {
        $all_langs = get_option('g11n_additional_lang',array());
        if (array_key_exists($lang, $all_langs)) {
            return $lang;
        } else {
            error_log("Warning tmy_g11n_language_escape, invalid:" . $lang . " reset to: " . $default_lang);
            return $default_lang;
        }
    } else {
        return $lang;
    }
}


function tmy_g11n_html_kses_esc( $html ) {

    // '<div style="border:1px solid;background-color:#d7dbdd;color:#21618c;font-size:1rem;">';


    $allowed_html = array('span' => array('style' => array('border' => array())),
                          'div' => array('style' => array('border' => array(),
                                                          'background-color' => array(),
                                                          'color' => array())),
                          'a' => array('href' => array(array()),
                                       'target' => array()
                                      ),
                          'script' => array('src' => array(),
                                            'type' => array()),
                          'style' => array('type' => array()),
                          'div' => array('id' => array()),
                          'tr' => array(),
                          'table' => array(),
                          'button' => array('class' => array(),
                                            'style' => array(),
                                            'onclick' => array(),
                                            'aria-disabled' => array(),
                                            'type' => array()),
                          'td' => array(),
                          'br' => array(),
                          'b' => array(),
                          'img' => array('style' => array(),
                                         'src' => array(),
                                         'title'=> array(),
                                         'alt' => array()
                                              )
                               );
    return wp_kses($html,$allowed_html);

}

function tmy_g11n_available_post_types() {
 
    $post_types = get_post_types( array( 'public' => true ));
    unset($post_types['g11n_translation']); 
    return $post_types;

}

function tmy_g11n_is_valid_post_type($post_type) {
 
    $post_types = get_post_types( array( 'public' => true ));
    unset($post_types['g11n_translation']); 
    return array_key_exists($post_type,$post_types);

}

function tmy_g11n_available_post_type_options() {

    $ret_array = array();
 
    $post_types = get_post_types( array( 'public' => true ));
    unset($post_types['g11n_translation']); 

    foreach ( $post_types  as $post_type ) {
        $ret_array['g11n_l10n_props_' . $post_type] = 'g11n_l10n_props_' . $post_type;
    }
    $ret_array['g11n_l10n_props_blogname'] = 'g11n_l10n_props_blogname';
    $ret_array['g11n_l10n_props_blogdescription'] = 'g11n_l10n_props_blogdescription';
    return $ret_array;

}

function tmy_g11n_post_type_enabled($post_id, $post_title) {

    if ((strcmp($post_title,"blogname")===0) || (strcmp($post_title,"blogdescription")===0)) {
        $option_name = "g11n_l10n_props_" . $post_title;
    } else {
        $post_type = get_post_type($post_id);
        $option_name = "g11n_l10n_props_" . $post_type;
    }

    if (strcmp(get_option($option_name),"Yes")===0) {
        return true;
    } else {
        return false;
    }

}
