<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    TMY_G11n
 * @subpackage TMY_G11n/includes
 * @author     Yu Shao <yu.shao.gm@gmail.com>
 */
class TMY_G11n_Translator {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      TMY_G11n_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $translation_server;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the TMY_G11n_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function rest_get_translation_server( $rest_url ) {

		$ch = curl_init();
		curl_reset($ch);
               
                if (strpos($rest_url,'version') !== false) {
                    $accept_fmt="application/vnd.zanata.Version+json";
                } else {
                    $accept_fmt="application/json";
                }

		curl_setopt($ch, CURLOPT_URL, $rest_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-Auth-User: ' . get_option('g11n_server_user'),
			'X-Auth-Token: ' . get_option('g11n_server_token'),
			'Accept: ' . $accept_fmt

		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$output = curl_exec($ch);
		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$translation_server_log_messages = "Response Code: " . $http_response_code;
		if(curl_errno($ch)){
			$translation_server_log_messages .= ' Error: ' . curl_error($ch) . ' Code: ' . curl_errno($ch);
		}

		$payload = json_decode($output);
		curl_close($ch);
			    
		$return_array = array('payload' => $payload,
				 'server_msg' => $translation_server_log_messages,
				 'http_code' => $http_response_code
				);
		curl_reset($ch);
		return $return_array;

	}

	public function sync_translation_from_server( $post_id, $name_prefex, $language_name ) {

		//$name_prefex = "WordpressG11nAret-" . $wp_query->post->post_type . "-";
		//This part of the code will get translation directly from Translation server.
		//
		//$output = g11n_get_translation_server_rest($postid, $name_prefex, $language_name);
		//$payload = json_decode($output);
		//if (isset($payload->textFlowTargets[0]->content)) {
		//    $translation = $payload->textFlowTargets[0]->content;
		//} else {
		//    $translation = "NO TRANSLATION";
		//}

		$ch = curl_init();
		curl_reset($ch);

		$rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest/projects/p/" .
			    get_option('g11n_server_project') . "/iterations/i/" .
			    get_option('g11n_server_version') . "/r/";
		$rest_url .= $name_prefex . $postid . "/translations/" . $language_name . "?ext=gettext&ext=comment";

		curl_setopt($ch, CURLOPT_URL, $rest_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-Auth-User: ' . get_option('g11n_server_user'),
		    'X-Auth-Token: ' . get_option('g11n_server_token'),
		    'Accept: application/json'
		    ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch);     
		error_log ("Finding Translation from Server URL: " . $rest_url);
		error_log ("Response Code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE));
		error_log ("Post id: " . $postid);

		if(curl_errno($ch)){
		    error_log ('Request Error:' . curl_error($ch));
		}
		curl_close($ch);
		return $output;

	}

	public function push_contents_to_translation_server( $file_name, $contents_array ) {

                if ((strcmp('', get_option('g11n_server_user','')) !== 0) && 
                    (strcmp('', get_option('g11n_server_token','')) !== 0)) {

		    $ch = curl_init();
		    $payload_contents_array = array();
		    foreach ($contents_array as &$con) {
		        $con_id = md5($con);
		        array_push($payload_contents_array, array("extensions" => array(array("object-type" => "pot-entry-header",
				                                        "references" => array(),
				                                        "flags" => array(),
				                                        "context" => "")),
				                        "lang" => "en-US",
				                        "id" => "$con_id",
				                        "plural" => false,
				                        "content" => "$con"
				                       ));
		    }
		    $payload = array("name" => "$file_name",
			     "contentType" => "application/x-gettext",
			 "lang" => "en-US",
			     "extensions" => array(array("object-type" => "po-header",
				                   "entries" => array(),
				                   "comment" => "Globalization Wordpress plugin")),
			     "textFlows" => $payload_contents_array
			    );

		    $payload_string = json_encode($payload);
		    $rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest/projects/p/" . 
			    get_option('g11n_server_project') . "/iterations/i/" . 
			    get_option('g11n_server_version') . "/r/";
		    $rest_url .= $file_name;
		    $rest_url .= "?ext=gettext";
		    curl_reset($ch);
		    curl_setopt($ch, CURLOPT_URL, $rest_url);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		        'X-Auth-User: ' . get_option('g11n_server_user'),
		        'X-Auth-Token: ' . get_option('g11n_server_token'),
		        'Content-Type: application/json' 
		        ));
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);

		    $output = curl_exec($ch);      

		    $return_msg = "Sent for translation " . $rest_url;
		    //$return_msg .= "\npayload : " . $payload_string;
		    $return_msg .= " server return: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);

		    if(curl_errno($ch)){
		        error_log ('Request Error:' . curl_error($ch));
		    }
		    $return_msg .= "  output : " . $output;
		    $return_msg .= "  id : " . get_the_ID();
    
		    error_log("Server Push: " . $return_msg);
		    update_post_meta( get_the_ID(), 'translation_push_status', $return_msg);

		    curl_close($ch);
            }
	}

	public function check_translation_exist( $post_id, $locale_id, $post_type ) {

		global $wpdb;

		if ((strcmp($post_type,'post') === 0)||(strcmp($post_type,'page') === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
			   "  where exists ( ".
				  "select post_id ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            //"{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "{$wpdb->prefix}posts.post_status != 'trash' and ".
				            "meta1.post_id = meta2.post_id and ".
				            "meta1.meta_key = 'orig_post_id' and ".
				            "meta2.meta_key = 'g11n_tmy_lang'  and ".
				            "meta1.meta_value = " . $post_id . " and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}
		if ((strcmp($post_type,"blogname") === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
		       "  where exists ( ".
				  "select post_id ".
				    //"from {$wpdb->prefix}postmeta as meta2 ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            //"{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "meta1.post_id = meta2.post_id and ".
				    //"where meta1.post_id = meta2.post_id and ".
				            "meta1.meta_key = 'option_name' and ".
				            "meta2.meta_key = 'g11n_tmy_lang'  and ".
				            "meta1.meta_value = '" . $post_type . "' and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    //error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}
		if ((strcmp($post_type,"blogdescription") === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
		       "  where exists ( ".
				  "select post_id ".
				    //"from {$wpdb->prefix}postmeta as meta2 ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            //"{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "meta1.post_id = meta2.post_id and ".
				    //"where meta1.post_id = meta2.post_id and ".
				        "meta1.meta_key = 'option_name' and ".
				        "meta2.meta_key = 'g11n_tmy_lang'  and ".
				        "meta1.meta_value = '" . $post_type . "' and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    //error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}


		if (isset($result[0]->post_id)) {
		    //error_log("GET TRANS ID = " . $result[0]->post_id);
		    return ($result[0]->post_id);
		} else {
		    //error_log("GET TRANS ID = null");
		    return null;
		}

	}

	public function get_translation_id( $post_id, $locale_id, $post_type ) {

		global $wpdb;

		if ((strcmp($post_type,'post') === 0)||(strcmp($post_type,'page') === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
			   "  where exists ( ".
				  "select post_id ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            "{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "meta1.post_id = meta2.post_id and ".
				            "meta1.meta_key = 'orig_post_id' and ".
				            "meta2.meta_key = 'g11n_tmy_lang'  and ".
				            "meta1.meta_value = " . $post_id . " and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}
		if ((strcmp($post_type,"blogname") === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
		       "  where exists ( ".
				  "select post_id ".
				    //"from {$wpdb->prefix}postmeta as meta2 ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            "{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "meta1.post_id = meta2.post_id and ".
				    //"where meta1.post_id = meta2.post_id and ".
				            "meta1.meta_key = 'option_name' and ".
				            "meta2.meta_key = 'g11n_tmy_lang'  and ".
				            "meta1.meta_value = '" . $post_type . "' and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    //error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}
		if ((strcmp($post_type,"blogdescription") === 0)) {
		    $sql = "select post_id from {$wpdb->prefix}postmeta as meta1 ".
		       "  where exists ( ".
				  "select post_id ".
				    //"from {$wpdb->prefix}postmeta as meta2 ".
				      "from {$wpdb->prefix}postmeta as meta2, {$wpdb->prefix}posts ".
				      "where meta1.post_id = {$wpdb->prefix}posts.ID and ".
				            "{$wpdb->prefix}posts.post_status = 'publish' and ".
				            "meta1.post_id = meta2.post_id and ".
				    //"where meta1.post_id = meta2.post_id and ".
				            "meta1.meta_key = 'option_name' and ".
				            "meta2.meta_key = 'g11n_tmy_lang'  and ".
				            "meta1.meta_value = '" . $post_type . "' and ".
				            "meta2.meta_value = '" . $locale_id . "')";
		    //error_log("GET TRANS SQL = " . $sql);
		    $result = $wpdb->get_results($sql);
		}


		if (isset($result[0]->post_id)) {
		    //error_log("GET TRANS ID = " . $result[0]->post_id);
		    return ($result[0]->post_id);
		} else {
		    //error_log("GET TRANS ID = null");
		    return null;
		}

	}

	public function get_translation_info( $trans_id ) {
		global $wpdb;
		$sql = "select {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts where " .
       			//"{$wpdb->prefix}posts.post_status != \"trash\" and " .
       			"{$wpdb->prefix}postmeta.meta_key = 'orig_post_id' and {$wpdb->prefix}postmeta.meta_value = {$wpdb->prefix}posts.ID and " .
       			"{$wpdb->prefix}postmeta.post_id = " . $trans_id;

		error_log("GET POST SQL = " . $sql);
		$result = $wpdb->get_results($sql);
		return ($result);
	}


	public function get_language_switcher() {
	
		//$current_url = home_url();
		//$current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$current_url = $_SERVER['REQUEST_URI'];
		$query_variable_name = "g11n_tmy_lang";
		$g11n_current_language = $_SESSION['g11n_language'];


		$language_options = get_option('g11n_additional_lang', array());
		$language_switcher_html = '<span style="font-color:red; font-size: xx-small; font-family: sans-serif; display: inline-block;">';
		//$language_switcher_html = '';

		foreach( $language_options as $value => $code) {
		    //<img src="./flags/24/CN.png" alt="CN">

		    if (strcmp('Text', get_option('g11n_switcher_type','Text')) === 0) {
			$href_text_ht = $value;
			$href_text = $value;
		    }
		    if (strcmp('Flag', get_option('g11n_switcher_type','Text')) === 0) {
			$href_text_ht = '<img style="display: inline-block; border: #FF0000 1px outset" src="' . 
				                 plugins_url('flags/', __FILE__ ) . "24/" . 
				                 strtoupper($code) . '.png" title="'. 
				                 $value .'" alt="' . 
				                 strtoupper($code) . '">';
			$href_text = '<img style="display: inline-block" src="' . 
				                 plugins_url('flags/', __FILE__ ) . "24/" . 
				                 strtoupper($code) . '.png" title="'. 
				                 $value .'" alt="' . 
				                 strtoupper($code) . '">';
		    }
		    if (strcmp($value, $g11n_current_language) === 0) {
			$language_switcher_html .= '<a href=' . 
				                   add_query_arg($query_variable_name, $value, $current_url) . '><b> ' .
				                   $href_text_ht.'</b></a>';
		    } else {
			$language_switcher_html .= '<a href=' . 
				                   add_query_arg($query_variable_name, $value, $current_url) . '> ' .
				                   $href_text.'</a>';
		    }
		}
		$language_switcher_html .= "</span>";

		return $language_switcher_html;

        }

	public function get_preferred_language() {

                if(!session_id()) {
                        session_start();
                }

                if (isset($_SESSION['g11n_language'])) {
                        error_log("Starting session, session lang=" . $_SESSION['g11n_language']);
                }

                if (!isset($_SESSION['g11n_language'])) {
                        $_SESSION['g11n_language'] = get_option('g11n_default_lang');
                        error_log("Starting session, id=" . session_id() . ",lang is not set, set as: " . get_option('g11n_default_lang'));
                }


		if (isset($_SESSION['g11n_language'])) {
                   error_log("getting preferred language, session language set: " . $_SESSION['g11n_language']);
		} else {
                   error_log("getting preferred language, session language no set ");

                }
		$lang_var_from_query = filter_input(INPUT_GET, 'g11n_tmy_lang', FILTER_SANITIZE_SPECIAL_CHARS);
		//error_log("QUERY LANG = ". $lang_var_from_query);
		if (!empty($lang_var_from_query)) {
		   $_SESSION['g11n_language'] = $lang_var_from_query;
		   error_log("QUERY LANG 2 = ". $lang_var_from_query);
		   return $lang_var_from_query;
		}

		if ((isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) and (strcmp(get_option('g11n_site_lang_browser'),'Yes')===0)) {

		    $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		    $prefLocales = array();
		    foreach ($languages as $language) {
			$lang = explode(';q=', $language);
			// $lang == [language, weight], default weight = 1
			$prefLocales[$lang[0]] = isset($lang[1]) ? floatval($lang[1]) : 1;
		    }
		    arsort($prefLocales);

		    //$prefLocales = array_reduce(
		    //    explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
		    //    function ($res, $el) {
		    //        list($l, $q) = array_merge(explode(';q=', $el), [1]);
		    //        $res[$l] = (float) $q;
		    //        return $res;
		    //    }, []);
		    //arsort($prefLocales);

		    /* array format: ( [zh-CN] => 1 [zh] => 0.8 [en] => 0.6 [en-US] => 0.4) */

		    $all_configed_langs = get_option('g11n_additional_lang'); /* array format ((English -> en), ...) */

		    if (is_array($all_configed_langs)) {
			foreach( $prefLocales as $value => $pri) {
			    if (array_search($value, $all_configed_langs)) {
				$pref_lang = array_search($value, $all_configed_langs);
				break;
			    }
			    /* check after removing CN of zh-CN*/
			    $lang_code = preg_split("/-/", $value);
			    if (array_search($lang_code[0], $all_configed_langs)) {
				$pref_lang = array_search($lang_code[0], $all_configed_langs);
				break;
			    }
			}
		    }
		    if (isset($pref_lang)) { 
			$_SESSION['g11n_language'] = $pref_lang;
			return $pref_lang; 
		    }
		}

		if ((isset($_COOKIE['g11n_language'])) and (strcmp(get_option('g11n_site_lang_cookie'),'Yes')===0)) {
		   $_SESSION['g11n_language'] = $_COOKIE['g11n_language'];
		   return $_COOKIE['g11n_language'];
		}

		if (isset($_SESSION['g11n_language'])) {
		   return $_SESSION['g11n_language'];
		}

		$_SESSION['g11n_language'] = get_option('g11n_default_lang','English');
		return $_SESSION['g11n_language'];


	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
