<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/public
 * @author     Yu Shao <yu.shao.gm@gmail.com>
 */
class TMY_G11n_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $translator;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $translator ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->translator = $translator;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TMY_G11n_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TMY_G11n_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmy-g11n-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TMY_G11n_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TMY_G11n_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmy-g11n-public.js', array( 'jquery' ), $this->version, false );

	}

        public function g11n_option_editor_change($in) {
                if ( WP_TMY_G11N_DEBUG ) {
                    error_log ("G11nOptionChange: " . esc_attr(get_option('g11n_editor_choice')));
                }
                if(strcmp(get_option('g11n_editor_choice','Yes'),'No')==0){
                    return (true);
                } else {
                    return (false);
                }
        }


	public function g11n_create_rewrite_rule() {

            //error_log("in g11n_create_rewrite_rule: " . json_encode($_SERVER));
	        //if ( strpos( $_SERVER['REQUEST_URI'], '/language/' ) === FALSE ) {
                //   $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] . '/language/Japanese/';
                //}
                //$_SERVER['REQUEST_URI'] = preg_replace("/\/lang\/.*/",'',$_SERVER['REQUEST_URI']);
                //$_SERVER['REDIRECT_URL'] = preg_replace("/\/lang\/.*/",'',$_SERVER['REDIRECT_URL']);
                //error_log("in g11n_create_rewrite_rule: URI " . $_SERVER['REQUEST_URI']);
                //error_log("in g11n_create_rewrite_rule: REDIRECT_URL" . $_SERVER['REDIRECT_URL']);

            //global $wp;

	    //add_rewrite_tag( '%language%', '([^&]+)' );
            //add_rewrite_rule( '^lang/([^/+a]+)[/]?$', 'index.php?g11n_tmy_lang=$matches[1]', 'top' );
            //add_rewrite_rule( '^language/([^/]*)/?', 'index.php?g11n_tmy_lang=$matches[1]','top' );
            //add_rewrite_rule( '^language/([^/]+)/(.*)', 'index.php?g11n_tmy_lang=$matches[1]','top' );

            //add_rewrite_endpoint( 'lang', EP_ALL );
            //add_rewrite_rule('^lang/([^/]+)/(.*)',
            //                 '$2?g11n_tmy_lang=$1',
            //                 'top'
            //);
            //flush_rewrite_rules();

            //$wp_rewrite->flush_rules();

        }

        function tmy_rewrite_permalink_links( $permalink ) {

            $site_url = get_site_url();
            $all_configed_langs = get_option('g11n_additional_lang'); /* array format ((English -> en), ...) */
            $lang_code = $all_configed_langs[$this->translator->get_preferred_language()];
            $lang_code = strtolower(str_replace('_', '-', $lang_code));

            $lang_path = explode('/', str_replace($site_url, '', $permalink))[1];
            $lang_path = str_replace('-', '_', $lang_path);

            if (! array_search(strtolower($lang_path), array_map('strtolower',$all_configed_langs))) {
                $ret = str_replace($site_url, $site_url . '/' . esc_attr($lang_code), $permalink);
	        return $ret;
            }
            return $permalink;
        }
        function rewrite_tag_permalink_post_link( $permalink, $post, $leavename ) {

            $site_url = get_site_url();
            $all_configed_langs = get_option('g11n_additional_lang'); /* array format ((English -> en), ...) */
            $lang_code = $all_configed_langs[$this->translator->get_preferred_language()];
            $lang_code = strtolower(str_replace('_', '-', $lang_code));
            $lang_path = explode('/', str_replace($site_url, '', $permalink))[1];
            $lang_path = str_replace('-', '_', $lang_path);
            if (! array_search(strtolower($lang_path), array_map('strtolower',$all_configed_langs))) {
                $ret = str_replace($site_url, $site_url . '/' . esc_attr($lang_code), $permalink);
	        return $ret;
            }
            return $permalink;
        }



	public function G11nStartSession() {

            if (! is_admin()) {

                if ( WP_TMY_G11N_DEBUG ) {
        	    error_log("In G11nStartSession");
                }

                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
    		}
                if ( WP_TMY_G11N_DEBUG ) {
        	    error_log("In G11nStartSession id=" . esc_attr(session_id()));
                }

    		if (isset($_SESSION['g11n_language'])) {
                    if ( WP_TMY_G11N_DEBUG ) {
        		error_log("Starting session, session lang=" . tmy_g11n_lang_sanitize($_SESSION['g11n_language']));
                    }
                    if (strcmp('Yes', get_option('g11n_using_google_tookit','Yes')) === 0) {
                        $_SESSION['g11n_language'] = get_option('g11n_default_lang');
                    }
    		}
    		if (!isset($_SESSION['g11n_language'])) {
                    $_SESSION['g11n_language'] = get_option('g11n_default_lang');
                    if ( WP_TMY_G11N_DEBUG ) {
        		error_log("Starting session, id=" . esc_attr(session_id()) . ",lang is not set, set as: " . esc_attr(get_option('g11n_default_lang')));
                    }
    		}

                $lang_var = tmy_g11n_lang_sanitize(filter_input(INPUT_GET, 'g11n_tmy_lang', FILTER_SANITIZE_SPECIAL_CHARS));

                $all_configed_langs = get_option('g11n_additional_lang'); /* array format ((English -> en), ...) */
                $lang_var_code_from_query = filter_input(INPUT_GET, 'g11n_tmy_lang_code', FILTER_SANITIZE_SPECIAL_CHARS);
                $lang_var_code_from_query = str_replace('-', '_', $lang_var_code_from_query);

                if (!empty($lang_var_code_from_query)) {
                    $lang_var = array_search(strtolower($lang_var_code_from_query), array_map('strtolower',$all_configed_langs));
                }

                if (!empty($lang_var)) {
                    $_SESSION['g11n_language'] = $lang_var;
                }

                if ( WP_TMY_G11N_DEBUG ) {
    		    error_log("Starting session, id=" . esc_attr(session_id()) . "session lang=" . tmy_g11n_lang_sanitize($_SESSION['g11n_language']) );
                }
            }
	}

	public function G11nEndSession() {
    		session_destroy ();
	}

	public function g11n_setcookie() {

            if (! is_admin()) {
	    	$lang_var = tmy_g11n_lang_sanitize(filter_input(INPUT_GET, 'g11n_tmy_lang', FILTER_SANITIZE_SPECIAL_CHARS));
                if ( WP_TMY_G11N_DEBUG ) {
	    	    error_log("In g11n_setcookie , lang_var " . esc_attr($lang_var));
                }
    		if (!empty($lang_var)) {
        		setcookie('g11n_language', $lang_var, strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
        		     error_log("In g11n_setcookie SET COOKIE from query string - " . esc_attr($lang_var));
                        }
    		} else {
        		setcookie('g11n_language', get_option('g11n_default_lang'), strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
        		     error_log("In g11n_setcookie SET COOKIE from wp language option - " .  esc_attr(get_option('g11n_default_lang')));
                        }
    		}
            }
	}

public function g11n_add_floating_menu() {

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
           if(strcmp(get_option('g11n_switcher_floating'),"Yes")==0){
               echo '<div id="tmyfloatmenu" style="position:fixed;z-index:10001;bottom:5rem;left:3rem;"> <div style="border:1px solid;border-radius:2px;background-color:#d7dbdd;color:#21618c;z-index:10000;box-shadow: 0 0 0px 0 rgba(0,0,0,.4);padding:0.1rem 0.4rem;margin:0rem 0;right:1rem;font-size:1rem;">' . tmy_g11n_html_kses_esc($this->translator->get_language_switcher('floating')) . '</div></div>';

              ?>
                <script>
                //Make the DIV element draggagle:
                dragElement(document.getElementById("tmyfloatmenu"));

                function dragElement(elmnt) {
                  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                  if (document.getElementById(elmnt.id + "header")) {
                    /* if present, the header is where you move the DIV from:*/
                    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
                  } else {
                    /* otherwise, move the DIV from anywhere inside the DIV:*/
                    elmnt.onmousedown = dragMouseDown;
                  }

                  function dragMouseDown(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // get the mouse cursor position at startup:
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    // call a function whenever the cursor moves:
                    document.onmousemove = elementDrag;
                  }

                  function elementDrag(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // calculate the new cursor position:
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    // set the element's new position:
                    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                  }

                  function closeDragElement() {
                    /* stop moving when mouse button is released:*/
                    document.onmouseup = null;
                    document.onmousemove = null;
                  }
                }
                </script>
              <?php
            }
        }
	public function g11n_widget_title($title, $instance, $id_base) {
                error_log("g11n_widget_title");
                //return "aaaaaaaaaa";
        }


	public function g11n_push_status_div() {
        /***********************************************/
        /* action for edit_form_after_editor obsoleted */
        /***********************************************/

                ?>
                <script>
                    function create_sync_translation(id, post_type) {

                        var r = confirm("This will create sync translation");
                        if (r == true) {
                            jQuery(document).ready(function($) {
                                    var data = {
                                            'action': 'tmy_create_sync_translation',
                                            'id': id,
                                            'post_type': post_type
                                    };
                                    $.ajax({
                                        type:    "POST",
                                        url:     ajaxurl,
                                        data:    data,
                                        success: function(response) {
                                            alert('Server Reply: ' + response);
                                        },
                                        error:   function(jqXHR, textStatus, errorThrown ) {
                                            alert("Error, status = " + jqXHR.status + ", " + "textStatus: " + textStatus + "ErrorThrown: " + errorThrown);
                                        }
                                    });
                                    return;
                            });
                        }
                    }
                </script>
                <?php

                $post_id = get_the_ID();
                $post_type = get_post_type($post_id);
                $post_status = get_post_status($post_id);

                $all_post_types = tmy_g11n_available_post_types();

	    	if (strcmp($post_type,"g11n_translation")===0) {

                    echo '<div style="border:1px solid #A8A7A7;padding: 10px;">';
                    $trans_info = $this->translator->get_translation_info($post_id);
                    if (isset($trans_info[0])) {
                        $original_id = $trans_info[0]->ID;
                        $original_title = $trans_info[0]->post_title;
                    }
		    $trans_lang = get_post_meta($post_id,'g11n_tmy_lang',true);

                    echo '<b>This is the ' . esc_attr($trans_lang) . ' translation page of <a href="' . 
                         esc_url( get_edit_post_link($original_id) ) . '">' . esc_attr($original_title) . 
                       ' (ID:' . esc_attr($original_id) . ')</a>';

		    if (strcmp($post_status,"publish")===0) {
		        echo ' Status: Live</b></br>';
		    } else {
		        echo ' Status: Not Published Yet</b></br>';
	       	    }
                    echo "</div>";

                } elseif (array_key_exists($post_type, $all_post_types)) {
                //} elseif ((strcmp($post_type,"post")===0) || (strcmp($post_type,"page")===0)) {

                    echo '<div style="border:1px solid #A8A7A7;padding: 10px;">';
    		    echo '<b>Translation Satus:</b><br><br>'; 

                    $all_langs = get_option('g11n_additional_lang');
                    $default_lang = get_option('g11n_default_lang');
                    unset($all_langs[$default_lang]);
                    
                    if (is_array($all_langs)) {
                        foreach( $all_langs as $value => $code) {
                            $translation_id = $this->translator->get_translation_id($post_id,$code,$post_type);
			    if (isset($translation_id)) {
                                $translation_status = get_post_status($translation_id);
                                echo esc_attr($value) . '-' . esc_attr($code) . ' Translation page is at <a href="' . esc_url( get_edit_post_link($translation_id) ) . 
                                     '">ID ' . esc_attr($translation_id) . '</a>, status: ' . esc_attr($translation_status) . '</br>';
                            } else {
                                echo esc_attr($value) . '-' . esc_attr($code) . ' Not Started Yet </br>';
                            }

                         }
                    }

                    echo '<br>Click <button type="button" onclick="create_sync_translation(' . esc_attr($post_id) . ', \'' . esc_attr($post_type) . '\')">Start or Sync Translation</button> to send this page to translation server';
                    echo '<br>Visit <a href="' . get_home_url() . '/wp-admin/edit.php?post_type=g11n_translation' . '">TMY Translations</a> page for all translations';
                    echo '<br>Or, visit <a href="' . get_home_url() . '/wp-admin/admin.php?page=tmy-g11n-dashboard-menu' . '">TMY Dashboard</a> for translation summary<br>';

                    if ((strcmp('', get_option('g11n_server_user','')) !== 0) && (strcmp('', get_option('g11n_server_token','')) !== 0)) {
    		        echo '<br>Latest status with Translation Server:<div id="g11n_push_status_text_id"><h5>'. 
			    esc_attr(get_post_meta(get_the_ID(),'translation_push_status',true)) . '</h5></div>';
                    }
                    echo "</div>";
                    
                }
	}

	public function myprefix_edit_form_after_title($post) {

	}

	public function add_before_dynamic_siderbar( $current_widget ) {
	    global $wp_registered_widgets;

	    // Only run on the Widgets admin screen, not the front-end
	    //if ( ! is_admin() )
	    //	return;

	    // Get all sidebars and their widgets
	    $sidebars_widgets = wp_get_sidebars_widgets();

	    // Optionally remove looping through Inactive Widgets
	    unset( $sidebars_widgets['wp_inactive_widgets'] );

	    // Get current sidebar ID
	    foreach( $sidebars_widgets as $sidebars => $widgets ){
		for( $i = 0; $i < count( $widgets ); $i++ ) {
			if ( $current_widget['id'] === $widgets[$i]) {
				$current_sidebar_id = $sidebars;
				break 2;
			}
		}
	    }

	    // Bail if sidebar not found (e.g. Inactive Widgets, which we unset earlier)
	    if ( ! isset( $current_sidebar_id ) )
		    return;

	    // Get first widget ID in the current sidebar
	    foreach( $sidebars_widgets[$current_sidebar_id] as $key => $value ) {
		$first_widget_id = $value;
		break;
	    }

	    // Bail if we're not about to show the first widget form
	    if ( $first_widget_id !== $current_widget['id'] )
		return;

	    // Now echo something awesome at the top of each sidebar!
	    if(strcmp(get_option('g11n_switcher_sidebar'),"Yes")==0){
		echo '<div align="center">' . tmy_g11n_html_kses_esc($this->translator->get_language_switcher('sidebar')). '</div>';
            }



        }
	public function add_before_my_siderbar( $name ) 
	{
                global $locale;
                //error_log("in sidebar before: " . $locale);
                //$WP_Sys_Locale_Switcher = new WP_Locale_Switcher();
                //$success_switch = $WP_Sys_Locale_Switcher->switch_to_locale($locale);
		if(strcmp(get_option('g11n_switcher_sidebar'),"Yes")==0){
	        //	echo '<div align="center">' . $this->translator->get_language_switcher('sidebar'). '</div>';
		}

	}

	public function g11n_locale_filter($locale_in) {


                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_locale_filter: " . esc_attr($locale_in) . " admin: " . is_admin());
                }

                if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return $locale_in; 
                }

		$language_options = get_option('g11n_additional_lang', array());

		    /* if current locale code is not in the language list configured, return original locale to avoid dead lock */
		    //if (!array_key_exists('$locale_in', $language_options)) {
		    //    return $locale_in;
		    //}

		$pre_lang = $this->translator->get_preferred_language();
                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_locale_filter, preferred language: " . esc_attr($pre_lang));
                }
		if (array_key_exists($pre_lang, $language_options)) {
			$s_locale = $language_options[$pre_lang];
		    } else {
			return $locale_in;
		}
		if (strcmp($s_locale,$locale_in != 0)) {
			remove_filter('locale',array($this, 'g11n_locale_filter'),10);
			global $locale;
			$locale = $s_locale;
			$template_name = get_template();
			if (is_textdomain_loaded($template_name)) {
			    unload_textdomain($template_name);
			    load_theme_textdomain($template_name);
			}
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_locale_filter, change locale to: " . esc_attr($s_locale) . " template name = ". esc_attr($template_name));
                        }

                        //$al = get_available_languages();
                        //error_log(print_r($al));
                        //error_log("locale now: " . locale_get_default());
 

                        //$WP_Sys_Locale_Switcher = new WP_Locale_Switcher();
                        //$success_switch = $WP_Sys_Locale_Switcher->switch_to_locale($s_locale);
                        //foreach ( $GLOBALS['wp_widget_factory']->widgets as $widget ) {
                        //    error_log("list of widget base: " . $widget->id_base."\n");
                        //    error_log("list of widget name: " .  $widget->name."\n");
                        //    //error_log(var_dump($widget));
                        //}
                        //error_log("switched to: ". $s_locale. " result: ". $success_switch);
                       
                        //error_log("locale now: " . locale_get_default());

			unload_textdomain('default');
                        //error_log("load_default_textdomain:".$s_locale);
			load_default_textdomain($s_locale);

                        update_option(
                            'widget_block',
                              array(
                                2              => array( 'content' => '<!-- wp:search /-->' ),
                                3              => array( 'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>' . __( 'Recent Posts' ) . '</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->' ),
                                4              => array( 'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>' . __( 'Recent Comments' ) . '</h2><!-- /wp:heading --><!-- wp:latest-comments {"displayAvatar":false,"displayDate":false,"displayExcerpt":false} /--></div><!-- /wp:group -->' ),
                                5              => array( 'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>' . __( 'Archives' ) . '</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->' ),
                                6              => array( 'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>' . __( 'Categories' ) . '</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->' ),
                                '_multiwidget' => 1,
                                   )
                        );

			add_filter('locale',array($this, 'g11n_locale_filter'),10);
			return $s_locale;
		} else {
			return $locale_in;
		}
		//  } else {
		//    return $locale_in;
		//  }
	}

	public function g11n_create_post_type_translation() {

		register_post_type( 'g11n_translation',
		    array(
		      'labels' => array(
			'name' => __( 'TMY Translations' ),
			'singular_name' => __( 'TMY Translation' )
		      ),
		      'public' => true,
		      'show_ui' => true,
		      'show_in_menu' => 'tmy-g11n-main-menu',
		      'menu_position' => '3',
		      //'show_in_menu' => 'admin.php?page=tmy-g11n-setup-menu',
		      //'show_in_menu' => 'edit.php?post_type=g11n_translation',
                      'show_in_rest' => true,
                      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
                      'capabilities' => array(
                          'create_posts' => 'do_not_allow', 
                       // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                       ),
                      'map_meta_cap' => true, 
		      'has_archive' => true,
		    )
		);

	}

	public function g11n_post_saved_notification( $ID, $post ) {

            if ( WP_TMY_G11N_DEBUG ) {
                error_log("In g11n_post_saved_notification, " . esc_attr($ID));
                error_log("In g11n_post_saved_notification, " . esc_attr(json_encode($post)));
            }

            //do_action('do_meta_boxes', null, 'normal', $post);
            //do_meta_boxes( null, 'normal', $post);
            //do_meta_boxes( $screen, $context, $object )

        }

	public function g11n_post_published_notification( $ID, $post ) {

		/* post data structure ref: 
		 * https://codex.wordpress.org/Class_Reference/WP_Post 
		 */

		    if (strcmp($post->post_type, "g11n_translation") === 0) {
			error_log("SKIP Sending for translation POST type: " . esc_attr($post->post_type));
			return;
		    }

		    if (strcmp($post->post_type, "product") === 0) {
                        if ( WP_TMY_G11N_DEBUG ) {
			    error_log("Publishing product id: " . esc_attr($post->ID));
                        }
			return;
		    }

		    $json_file_name = "WordpressG11nAret-" . $post->post_type . "-" . $ID;

		    $content_title = $post->post_title;
		    //$content = $post->post_content;
		    //$contents_array = array($content_title,$content);

                    if ( WP_TMY_G11N_DEBUG ) {
		        error_log("MYSQL" . esc_attr(var_export($post->post_content,true)));
                    }
		    $tmp_array = preg_split('/(\n)/', $post->post_content,-1, PREG_SPLIT_DELIM_CAPTURE);
		    //error_log("MYSQL" . var_export($tmp_array,true));
		    $contents_array = array();
		    array_push($contents_array, $content_title);
		    $paragraph = "";
		    foreach ($tmp_array as $line) {
			$paragraph .= $line;
			if (strlen($paragraph) > get_option('g11n_server_trunksize',900)) {
			    array_push($contents_array, $paragraph);
			    $paragraph = "";
			}
		    }
		    if (strlen($paragraph)>0) array_push($contents_array, $paragraph);
		    //error_log("MYSQL" . var_export($contents_array,true));
		    //$this->translator->push_contents_to_translation_server($json_file_name, $contents_array);
                    // disable this August 2022

	}

	public function g11n_pre_get_option_blogdescription( $output, $show ) {

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("IN g11n_pre_get_option_blogdescription: " . esc_attr($output) . "." . esc_attr($show) . ".");
                }

		remove_filter('pre_option_blogdescription',array($this, 'g11n_pre_get_option_blogdescription'),10);
                $output = get_option('blogdescription');
		add_filter('pre_option_blogdescription',array($this, 'g11n_pre_get_option_blogdescription'), 10, 2);

                if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return $output;
                }

                if (strcmp(get_option('g11n_l10n_props_blogdescription'),"Yes")==0){
                     $g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
                     $language_options = get_option('g11n_additional_lang');
                     $language_name = $language_options[$g11n_current_language];

                     $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                     if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                         $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
                         if (isset($translation_post_id)) {
                             if ( WP_TMY_G11N_DEBUG ) {
                                 error_log("In g11n_pre_get_option_blogdescription, translation id:" . esc_attr($translation_post_id));
                             }
                             $output = get_post_field("post_content", $translation_post_id);
                         }
                     }
                }
                if(strcmp(get_option('g11n_switcher_tagline'),"Yes")==0){
                    $switcher_html = $this->translator->get_language_switcher('description');
                    //$switcher_html = "";
                } else {
                    $switcher_html = "";
                }
                return $output . $switcher_html;


        }
	public function g11n_pre_get_option_blogname( $output, $show ) {

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("IN g11n_pre_get_option_blogname: " . esc_attr($output) . "." . esc_attr($show) . ".");
                }


		remove_filter('pre_option_blogname',array($this, 'g11n_pre_get_option_blogname'),10);
		remove_filter('bloginfo',array($this, 'g11n_wp_title_filter'),10);

                $output = get_option('blogname');

		add_filter('bloginfo',array($this, 'g11n_wp_title_filter'), 10, 2);
		add_filter('pre_option_blogname',array($this, 'g11n_pre_get_option_blogname'), 10, 2);

                if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                    return $output;
                }
                if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
                    return $output;
                }

                if (strcmp(get_option('g11n_l10n_props_blogname'),"Yes")==0){
                    $g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
                    $language_options = get_option('g11n_additional_lang');
                    $language_name = $language_options[$g11n_current_language];

                    $title_post  = get_page_by_title('blogname',OBJECT,'post');

                    if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                        $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
                        if (isset($translation_post_id)) {
                            if ( WP_TMY_G11N_DEBUG ) {
                                error_log("In g11n_pre_get_option_blogname, translation id:" . esc_attr($translation_post_id));
                            }
                            $output = get_post_field("post_content", $translation_post_id);
                        }
                    }
                }
                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_pre_get_option_blogname, g11n_switcher_title,".esc_attr(get_option('g11n_switcher_title')));
                }
                if(strcmp(get_option('g11n_switcher_title'),"Yes")==0){
                    $switcher_html = $this->translator->get_language_switcher('blogname');
                    //$switcher_html = "";
                } else {
                    $switcher_html = "";
                }
                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_pre_get_option_blogname, output:".esc_attr($output) . esc_attr($switcher_html));
                }
                return $output . $switcher_html;

        }

	public function g11n_pre_option_blogname( $in ) {

	        global $wp_query;

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("g11n_pre_option_blogname:" . esc_attr($in) );
                }
                if (strcmp(get_option('g11n_l10n_props_blogname'),"Yes")==0) {

                    $title_post  = get_page_by_title('blogname',OBJECT,'post');
                    if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID,
                                                            'post_title'    => 'blogname',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogname',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("g11n_pre_option_blogname post id:" . esc_attr($new_post_id) );
                    }
                }
		//error_log("PRE UPDATE BLOGNAME: " . $in);
		//$json_file_name = "WordpressG11nAret-blogname-0";
		//$contents_array = array($in);
		//$this->translator->push_contents_to_translation_server($json_file_name, $contents_array);

		return $in;
	}
	public function g11n_pre_option_blogdescription( $in ) {

	        global $wp_query;

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("g11n_pre_option_blogdescription:" . esc_attr($in) );
                }
                if (strcmp(get_option('g11n_l10n_props_blogdescription'),"Yes")==0) {

                    $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                    if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID,
                                                            'post_title'    => 'blogdescription',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogdescription',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("g11n_pre_option_blogdescription post id:" . esc_attr($new_post_id) );
                    }
                }
		 //   error_log("PRE UPDATE blogdescription: " . $in);
		 //   $json_file_name = "WordpressG11nAret-blogdescription-0";
		 //   $contents_array = array($in);
		 //   $this->translator->push_contents_to_translation_server($json_file_name, $contents_array);

	        return $in;
	}
	
	public function g11n_the_posts_filter($posts, $query = false) {

                    if( is_search() ){
                    }

	            foreach ( $posts as $post ) {
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_the_posts_filter, post_id: " . esc_attr($post->ID) . " excerpt: " . esc_attr($post->post_excerpt));
                        }
                        if ( tmy_g11n_is_valid_post_type($post->post_type) && (! empty($post->post_excerpt))) {
		        //if ((strcmp($post->post_type, "product") === 0) && (! empty($post->post_excerpt))) {

		            $g11n_current_language = $this->translator->get_preferred_language();

                            //$g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
                            $language_options = get_option('g11n_additional_lang');
                            $language_name = $language_options[$g11n_current_language];
		            $translation_post_id = $this->translator->get_translation_id($post->ID,
                                                                                         $language_name,
                                                                                         $post->post_type,
                                                                                         false);
                            if ( WP_TMY_G11N_DEBUG ) {
                                error_log("In g11n_the_posts_filter, excerpt post_id: " . esc_attr($post->ID) . " language: " . esc_attr($language_name));
                                error_log("In g11n_the_posts_filter, translation_id:  " . esc_attr($translation_post_id));
                                error_log("In g11n_the_posts_filter, SESSION:  " . esc_attr($_SESSION['g11n_language']));
                            }
		            if (isset($translation_post_id)) {
                                $post->post_excerpt=get_the_excerpt($translation_post_id);
                            }
                            //$post->post_title="title l10n:". $post->post_title;
		        }
	            }
                    return $posts;
        }

	public function g11n_excerpt_filter($input) {

                    //if (! ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST )) {
		        global $wp_query; 

		        $postid = $wp_query->post->ID;
		        $posttype = $wp_query->post->post_type;

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In excerpt filter, post id =" . esc_attr($postid));
		            error_log("In excerpt filter, post type =" . esc_attr($posttype));
		            error_log("In excerpt filter, input =" . esc_attr($input));
                        }

		        if (strcmp($posttype,"product")==0) {
			    #return $input;
			    return "Translation of excerpt : " . $input;
		        }
                    //}

        }

	public function g11n_content_filter($input) {

                    if (! ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST )) {

		        global $wp_query; 

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter filter, session_id=" . esc_attr(session_id()) . "session lang=" . tmy_g11n_lang_sanitize($_SESSION['g11n_language']) );
		            error_log("In g11n_content_filter filter, session lang = [" . tmy_g11n_lang_sanitize($_SESSION['g11n_language']). "]");
		            error_log("In g11n_content_filter filter, cookie lang = [" . tmy_g11n_lang_sanitize($_COOKIE['g11n_language']) . "]");
		            error_log("In g11n_content_filter filter, browser lang = [" . sanitize_textarea_field($_SERVER['HTTP_ACCEPT_LANGUAGE']) . "]");
                        }

		        $postid = $wp_query->post->ID;
		        $posttype = $wp_query->post->post_type;

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter filter, postid, posttype:" . esc_attr($postid) . " " . esc_attr($posttype) );
                        }

                        if (! tmy_g11n_post_type_enabled($postid, $wp_query->post->post_title, $posttype)) {
			    return $input;
                        }

		        //if ((strcmp(get_option('g11n_l10n_props_posts'),"Yes")!=0) and 
			//    (strcmp($posttype,"post")==0)) {
			//    return $input;
		        //}

		        //if ((strcmp(get_option('g11n_l10n_props_pages'),"Yes")!=0) and 
			//    (strcmp($posttype,"page")==0)) {
			//    return $input;
		        //}

		        //if (strcmp($posttype,"product")==0) {
			//    return $input;
			//    //return "Translation of cts: " . $input;
		        //}

		        $language_options = get_option('g11n_additional_lang');
		        $g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
		        $language_name = $language_options[$g11n_current_language];
		        //$translation_post_id = $this->translator->get_translation_id($postid,$language_name,$posttype);

		        $translation_post_id = $this->translator->get_translation_id($postid,$language_name,$posttype,false);

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter original post id = " . esc_attr($postid) . ".");
		            error_log("In g11n_content_filter language = " . esc_attr($language_name) . ".");
		            error_log("In g11n_content_filter type = " . esc_attr($posttype) . ".");
		            error_log("In g11n_content_filter translation_post_id = " . esc_attr($translation_post_id) . ".");
                        }

		        if(strcmp(get_option('g11n_switcher_post'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher('content');
		        } else {
			        $switcher_html = "";
		        }
    
		        if (isset($translation_post_id)) {
			    return wpautop(get_post_field("post_content", $translation_post_id)) . "<br>" . $switcher_html;
		        } else {
			    return $input . "<br>" . $switcher_html;
		        }
                    }
	}

	public function g11n_title_filter( $title, $id ) {
    

                    if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("g11n_title_filter: " . esc_attr($title));
                        }
                        return $title;
                    }
		    global $wp_query; 
		    //$postid = $wp_query->post->ID;
		    //error_log("G11N TITLE FILTER, id = [" . $id . "]");
		    //error_log("G11N TITLE FILTER, postid = [" . $postid . "]");

                    //error_log("The Title filter, excerpt post_id: " . $wp_query->post->ID . " type: " . $wp_query->post->post_type);
                    //error_log("The Title filter, excerpt post_id: " . var_export($wp_query, true));

		    if (!isset($wp_query->post)) return $title;

		    $posttype = $wp_query->post->post_type;

                    if (! tmy_g11n_post_type_enabled($wp_query->post->ID, $title, $posttype)) {
			return $title;
                    }
		    //if ((strcmp(get_option('g11n_l10n_props_posts'),"Yes")!=0) and 
	            //		(strcmp($posttype,"post")==0)) {
	            //		return $title;
		    //}

		    //if ((strcmp(get_option('g11n_l10n_props_pages'),"Yes")!=0) and 
		    //	(strcmp($posttype,"page")==0)) {
		    //	return $title;
		    //}

		    //if (strcmp($posttype,"product")==0) {
		    //	#return $title;
			//error_log("Translation of Title: " . $title);
		    //	return "Translation of Title: " . $title;
		    //   }

		    $language_options = get_option('g11n_additional_lang');
		    //$g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
		    $g11n_current_language = $this->translator->get_preferred_language();
		    $language_name = $language_options[$g11n_current_language];
		    $translation_post_id = $this->translator->get_translation_id($id,$language_name,$wp_query->post->post_type,false);

		    if (isset($translation_post_id)) {
			return get_post_field("post_title", $translation_post_id);
		    } else {
			return $title;
		    }

	}

	public function g11n_wp_title_filter( $output, $show ) {

                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("In g11n_wp_title_filter starting with, output: " . esc_attr($output) . ",show: " . esc_attr($show));
                    }

		    if (strcmp($show,'description')==0) {

		        remove_filter('pre_option_blogdescription',array($this, 'g11n_pre_get_option_blogdescription'),10);
		        remove_filter('bloginfo',array($this, 'g11n_wp_title_filter'),10);
                        $output = get_option('blogdescription');
		        add_filter('bloginfo',array($this, 'g11n_wp_title_filter'), 10, 2);
		        add_filter('pre_option_blogdescription',array($this, 'g11n_pre_get_option_blogdescription'), 10, 2);
                        if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                            return $output;
                        }

                        if (strcmp(get_option('g11n_l10n_props_blogdescription'),"Yes")==0){
			     $g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
			     $language_options = get_option('g11n_additional_lang');
			     $language_name = $language_options[$g11n_current_language];

                             $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                             if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                                 $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
			         if (isset($translation_post_id)) {
                                     if ( WP_TMY_G11N_DEBUG ) {
                                         error_log("In g11n_wp_title_filter,blogdescription translation id:" . esc_attr($translation_post_id));
                                     }
			             $output = get_post_field("post_content", $translation_post_id);
			         }
		             }
                        }
			if(strcmp(get_option('g11n_switcher_tagline'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher('description');
			} else {
			    $switcher_html = "";
			}
		        return $output . $switcher_html;
                    }

		    if (strcmp($show,'name')==0) {

		        remove_filter('pre_option_blogname',array($this, 'g11n_pre_get_option_blogname'),10);
		        remove_filter('bloginfo',array($this, 'g11n_wp_title_filter'),10);

                        $output = get_option('blogname');

		        add_filter('bloginfo',array($this, 'g11n_wp_title_filter'), 10, 2);
		        add_filter('pre_option_blogname',array($this, 'g11n_pre_get_option_blogname'), 10, 2);

                        if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                            return $output;
                        }

                        if (strcmp(get_option('g11n_l10n_props_blogname'),"Yes")==0){
			    $g11n_current_language = tmy_g11n_lang_sanitize($_SESSION['g11n_language']);
			    $language_options = get_option('g11n_additional_lang');
			    $language_name = $language_options[$g11n_current_language];

                            $title_post  = get_page_by_title('blogname',OBJECT,'post');
                            if (! is_null($title_post) && (strcmp($title_post->post_status,'trash')!==0)) {
                                $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
			        if (isset($translation_post_id)) {
                                    if ( WP_TMY_G11N_DEBUG ) {
                                        error_log("In g11n_wp_title_filter,blogname translation id:" . esc_attr($translation_post_id));
                                    }
			            $output = get_post_field("post_content", $translation_post_id);
			        }
                            }
		        }
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_wp_title_filter, g11n_switcher_title,".esc_attr(get_option('g11n_switcher_title')));
                        }
			if(strcmp(get_option('g11n_switcher_title'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher('blogname');
			} else {
			    $switcher_html = "";
			}
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_wp_title_filter, output,".esc_attr($output));
                        }
		        return $output . $switcher_html;
		    } 

		    return $output;
	}

	public function tmy_g11n_blocks_init() {

            wp_enqueue_script(
              'tmy-lang-block',
              plugin_dir_url(__DIR__) . 'includes/tmy-block-language-switcher.js',
              array('wp-blocks','wp-editor','wp-server-side-render'),
              true
            );

            $return = register_block_type('tmy/tmy-chooser-box', array(
                    'render_callback' => array($this,'tmy_lang_switcher_block_dynamic_render_cb')
            ));

        }

        function tmy_g11n_site_url ( $url, $path ) {

           //error_log("SITE URL ".$url . " path: " . $path);
           return $url;
        }

        function tmy_lang_switcher_block_dynamic_render_cb ( $att ) {

            $html = '<div>' . tmy_g11n_html_kses_esc($this->translator->get_language_switcher('block')) . '</div>';
            return $html;
        }

	public function tmy_g11n_template_redirect() {

            session_start();

            if (! is_admin()) {
                if ( WP_TMY_G11N_DEBUG ) {
                    if (isset($_SESSION)) {
                        error_log("In tmy_g11n_template_redirect, ". sanitize_textarea_field(json_encode($_SESSION)));
                    } else {
                        error_log("In tmy_g11n_template_redirect, _SESSION is not set");
                    }
                    error_log("In tmy_g11n_template_redirect, session id ".esc_attr(session_id()));
                }
            }

            if (! is_admin()) {
                $lang_var = tmy_g11n_lang_sanitize(filter_input(INPUT_GET, 'g11n_tmy_lang', FILTER_SANITIZE_SPECIAL_CHARS));


                $all_configed_langs = get_option('g11n_additional_lang'); /* array format ((English -> en), ...) */
                $lang_var_code_from_query = filter_input(INPUT_GET, 'g11n_tmy_lang_code', FILTER_SANITIZE_SPECIAL_CHARS);
                $lang_var_code_from_query = str_replace('-', '_', $lang_var_code_from_query);

                if (!empty($lang_var_code_from_query)) {
                    $lang_var = array_search(strtolower($lang_var_code_from_query), array_map('strtolower',$all_configed_langs));
                }



                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_setcookie , lang_var " . esc_attr($lang_var));
                }
                if (!empty($lang_var)) {
                        setcookie('g11n_language', $lang_var, strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
                             error_log("In g11n_setcookie SET COOKIE from query string - " . esc_attr($lang_var));
                        }
                } else {
                        setcookie('g11n_language', get_option('g11n_default_lang'), strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
                             error_log("In g11n_setcookie SET COOKIE from wp language option - " . esc_attr(get_option('g11n_default_lang')));
                        }
                }
            }

        }
	public function tmy_g11n_html_head_handler() {

            //<link rel="alternate" hreflang="de" href="https://de.example.com/index.html" />
            //<link rel="alternate" href="https://example.com/country-selector" hreflang="x-default" />

            $all_langs = get_option('g11n_additional_lang');
            $default_lang = get_option('g11n_default_lang');
            //unset($all_langs[$default_lang]);
            global $wp;
            $site_url = get_site_url();

            if (is_array($all_langs)) {
                foreach( $all_langs as $value => $code) {
                    $lang_code = strtolower($code);
                    $lang_code = str_replace('_', '-', $lang_code);
                    $current_url = home_url( $wp->request );
                    $current_url = str_replace($site_url, $site_url . '/' . esc_attr($lang_code), $current_url);
                    $current_url = $current_url . '/';
                    echo '<link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' .
                    esc_url($current_url) . '" />' . "\n";
                }
            }
            $current_url = home_url( $wp->request );
            echo '<link rel="alternate" href="' . esc_url($current_url) . '" hreflang="x-default" />' . "\n";
        }
        public function tmy_translation_get_taxonomy_filter( $wp_term, $taxonomy ) {

            error_log("In tmy_translation_get_taxonomy_filter: " . json_encode($wp_term));

            if ( ! is_admin() ) {

                //if (! tmy_g11n_post_type_enabled($wp_term->term_id, "", "taxonomy")) {
                //    return $wp_term;
                //}

                $all_configed_langs = get_option('g11n_additional_lang');
                $lang_code = $all_configed_langs[$this->translator->get_preferred_language()];
                //$translation_id = $this->translator->get_translation_id($wp_term->term_id, $lang_code, "taxonomy", false);
                $translation_id = $this->translator->get_translation_id($wp_term->term_id, $lang_code, $taxonomy, false);

                if (isset($translation_id)) {
                    $wp_term->name = get_post_field("post_title", $translation_id);
                }
            }
            return $wp_term;
        }



}
