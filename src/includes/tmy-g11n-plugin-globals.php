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

    $default_lang = get_option('g11n_default_lang');
    if (strcmp($lang,'')!==0) {
        $all_langs = get_option('g11n_additional_lang');
        if (array_key_exists($lang, $all_langs)) {
            return $lang;
        } else {
            error_log("Warning tmy_g11n_language_escape, invalid:" . $lang . " reset to: " . $default_lang);
            return $default_lang;
        }
    } else {
        return $default_lang;
    }
}


function tmy_g11n_switcher_esc( $html ) {

    $allowed_html = array('span' => array('style' => array('display' => array())),
                                'a' => array('href' => array()),
                                'script' => array('src' => array(),
                                                  'type' => array()),
                                'style' => array('type' => array()),
                                'div' => array('id' => array()),
                                'img' => array('style' => array(),
                                               'src' => array(),
                                               'title'=> array(),
                                               'alt' => array()
                                              )
                               );
    return wp_kses($html,$allowed_html);

}
