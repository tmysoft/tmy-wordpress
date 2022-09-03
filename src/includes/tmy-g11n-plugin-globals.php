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

    if (strcmp($lang,'')!==0) {
        $all_langs = get_option('g11n_additional_lang');
        $default_lang = get_option('g11n_default_lang');
        if (array_key_exists($lang, $all_langs)) {
            return $lang;
        } else {
            error_log("Warning tmy_g11n_language_escape, invalid:" . $lang . " reset to: " . $default_lang);
            return $default_lang;
        }
    }
}
