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
                error_log ("G11nOptionChange: " . get_option('g11n_editor_choice'));
                if(strcmp(get_option('g11n_editor_choice','Yes'),'No')==0){
                    return (true);
                } else {
                    return (false);
                }
        }


	public function G11nStartSession() {

            if (! is_admin()) {

                if ( WP_TMY_G11N_DEBUG ) {
        	    error_log("In G11nStartSession");
                }
    		if(!session_id()) {
        	    session_start();
    		}
                if ( WP_TMY_G11N_DEBUG ) {
        	    error_log("In G11nStartSession id=" . session_id());
                }
    		if (isset($_SESSION['g11n_language'])) {
                    if ( WP_TMY_G11N_DEBUG ) {
        		error_log("Starting session, session lang=" . $_SESSION['g11n_language']);
                    }
                    if (strcmp('Yes', get_option('g11n_using_google_tookit','Yes')) === 0) {
                        $_SESSION['g11n_language'] = get_option('g11n_default_lang');
                    }
    		}
    		if (!isset($_SESSION['g11n_language'])) {
                    $_SESSION['g11n_language'] = get_option('g11n_default_lang');
                    if ( WP_TMY_G11N_DEBUG ) {
        		error_log("Starting session, id=" . session_id() . ",lang is not set, set as: " . get_option('g11n_default_lang'));
                    }
    		}
                if ( WP_TMY_G11N_DEBUG ) {
    		    error_log("Starting session, id=" . session_id() . "session lang=" . $_SESSION['g11n_language'] );
                }
            }
	}

	public function G11nEndSession() {
    		session_destroy ();
	}

	public function g11n_setcookie() {

            if (! is_admin()) {
	    	$lang_var = filter_input(INPUT_GET, 'g11n_tmy_lang', FILTER_SANITIZE_SPECIAL_CHARS);
                if ( WP_TMY_G11N_DEBUG ) {
	    	    error_log("In g11n_setcookie , lang_var " . $lang_var);
                }
    		if (!empty($lang_var)) {
        		setcookie('g11n_language', $lang_var, strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
        		     error_log("In g11n_setcookie SET COOKIE from query string - " . $lang_var);
                        }
    		} else {
        		setcookie('g11n_language', get_option('g11n_default_lang'), strtotime('+1 day'));
                        if ( WP_TMY_G11N_DEBUG ) {
        		     error_log("In g11n_setcookie SET COOKIE from wp language option - " .  get_option('g11n_default_lang'));
                        }
    		}
            }
	}

public function g11n_add_floating_menu() {

            if(strcmp(get_option('g11n_switcher_floating'),"Yes")==0){
               echo '<div id="tmyfloatmenu" style="position:fixed;z-index:10001;bottom:3rem;left:3rem;"> <div style="border:1px solid;border-radius:5px;background-color:#d7dbdd;color:#21618c;z-index:10000;box-shadow: 0 0 15px 0 rgba(0,0,0,.4);padding:0.4rem 0.4rem;margin:0rem 0;right:1rem;font-size:1rem;">' . $this->translator->get_language_switcher() . '</div></div>';
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

	    	if (strcmp($post_type,"g11n_translation")===0) {

                    echo '<div style="border:1px solid #A8A7A7;padding: 10px;">';
                    $trans_info = $this->translator->get_translation_info($post_id);
                    if (isset($trans_info[0])) {
                        $original_id = $trans_info[0]->ID;
                        $original_title = $trans_info[0]->post_title;
                    }
		    $trans_lang = get_post_meta($post_id,'g11n_tmy_lang',true);

                    echo '<b>This is the ' . $trans_lang . ' translation page of <a href="' . 
                         esc_url( get_edit_post_link($original_id) ) . '">' . $original_title . 
                       ' (ID:' . $original_id . ')</a>';

		    if (strcmp($post_status,"publish")===0) {
		        echo ' Status: Live</b></br>';
		    } else {
		        echo ' Status: Not Published Yet</b></br>';
	       	    }
                    echo "</div>";

                } elseif ((strcmp($post_type,"post")===0) || (strcmp($post_type,"page")===0)) {

                    echo '<div style="border:1px solid #A8A7A7;padding: 10px;">';
    		    echo '<b>Translation Satus:</b><br><br>'; 

                    $all_langs = get_option('g11n_additional_lang');
                    $default_lang = get_option('g11n_default_lang');
                    unset($all_langs[$default_lang]);
                    
                    if (is_array($all_langs)) {
                        foreach( $all_langs as $value => $code) {
                            $translation_id = $this->translator->get_translation_id($post_id,$code,$post_type);
    		            //echo $code . ':' . $translation_id . '<br>'; 
			    if (isset($translation_id)) {
                                $translation_status = get_post_status($translation_id);
                                echo $value . '-' . $code . ' Translation page is at <a href="' . esc_url( get_edit_post_link($translation_id) ) . 
                                     '">ID ' . $translation_id . '</a>, status: ' . $translation_status . '</br>';
                            } else {
                                echo $value . '-' . $code . ' Not Started Yet </br>';
                            }

                         }
                    }

                    echo '<br>Click <button type="button" onclick="create_sync_translation(' . $post_id . ', \'' . $post_type . '\')">Start or Sync Translation</button> to send this page to translation server';
                    //echo '<br><input type="button" value="Start or Sync Translation" onclick="start_sync_translation('project')"><br>';
                    echo '<br>Visit <a href="' . get_home_url() . '/wp-admin/edit.php?post_type=g11n_translation' . '">G11n Translation Page</a> for all translations';
                    echo '<br>Or, visit <a href="' . get_home_url() . '/wp-admin/options-general.php?page=tmy-l10n-manager' . '">TMY Dashboard</a> for translation summary<br>';

                    if ((strcmp('', get_option('g11n_server_user','')) !== 0) && (strcmp('', get_option('g11n_server_token','')) !== 0)) {
    		        echo '<br>Latest status with Translation Server:<div id="g11n_push_status_text_id"><h5>'. 
			    get_post_meta(get_the_ID(),'translation_push_status',true) . '</h5></div>';
                    }
                    echo "</div>";
                    
                }
	}

	public function myprefix_edit_form_after_title($post) {
	    	//if (strcmp($post->post_type,"g11n_translation")===0) {
	        //        echo "This is a translation of ITranslation Status";
	//		if (strcmp($post->post_status,"pending")===0) {
	//			    echo '<button type="button"><h2>Translation Status: Live</h2></button>';
	//		} else {
	//		    echo '<button type="button"><h2>Translation Status: To be confirmed, set to Pending Review to publish translation.</h2></button>';
	//		}
	//	}
	}

	public function add_before_my_siderbar( $name ) 
	{
                global $locale;
                //error_log("in sidebar before: " . $locale);
                //$WP_Sys_Locale_Switcher = new WP_Locale_Switcher();
                //$success_switch = $WP_Sys_Locale_Switcher->switch_to_locale($locale);

		if(strcmp(get_option('g11n_switcher_sidebar'),"Yes")==0){
			echo '<div align="center">' . $this->translator->get_language_switcher(). '</div>';
		}

	}

	public function g11n_locale_filter($locale_in) {


                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_locale_filter: " . $locale_in . " admin: " . is_admin());
                }

                if (is_admin()) { return $locale_in; }

		$language_options = get_option('g11n_additional_lang', array());

		    /* if current locale code is not in the language list configured, return original locale to avoid dead lock */
		    //if (!array_key_exists('$locale_in', $language_options)) {
		    //    return $locale_in;
		    //}

		$pre_lang = $this->translator->get_preferred_language();
                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In g11n_locale_filter, preferred language: " . $pre_lang);
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
                            error_log("In g11n_locale_filter, change locale to: " . $s_locale . " template name = ". $template_name);
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
			'name' => __( 'G11n Plugin Translations' ),
			'singular_name' => __( 'G11n Plugin Translation' )
		      ),
		      'public' => true,
                      'show_in_rest' => true,
		      'has_archive' => true,
		    )
		);

	}

	public function g11n_post_published_notification( $ID, $post ) {

		/* post data structure ref: 
		 * https://codex.wordpress.org/Class_Reference/WP_Post 
		 */

		    if (strcmp($post->post_type, "g11n_translation") === 0) {
			error_log("SKIP Sending for translation POST type: " . $post->post_type);
			return;
		    }

		    if (strcmp($post->post_type, "product") === 0) {
			error_log("Publishing product id: " . $post->ID);
			error_log("Publishing product title: " . $post->post_title);
			error_log("Publishing product content: " . $post->post_content);
			error_log("Publishing product excerpt: " . $post->post_excerpt);
			return;
		    }

		    $json_file_name = "WordpressG11nAret-" . $post->post_type . "-" . $ID;

		    $content_title = $post->post_title;
		    //$content = $post->post_content;
		    //$contents_array = array($content_title,$content);

		    error_log("MYSQL" . var_export($post->post_content,true));
		    $tmp_array = preg_split('/(\n)/', $post->post_content,-1, PREG_SPLIT_DELIM_CAPTURE);
		    error_log("MYSQL" . var_export($tmp_array,true));
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
		    error_log("MYSQL" . var_export($contents_array,true));
		    //$this->translator->push_contents_to_translation_server($json_file_name, $contents_array);
                    // disable this August 2022

	}

	public function g11n_pre_option_blogname( $in ) {

	        global $wp_query;

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("g11n_pre_option_blogname:" . $in );
                }
                if (strcmp(get_option('g11n_l10n_props_blogname'),"Yes")==0) {

                    $title_post  = get_page_by_title('blogname',OBJECT,'post');
                    if (is_null($title_post)) {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogname',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID,
                                                            'post_title'    => 'blogname',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("g11n_pre_option_blogname post id:" . $new_post_id );
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
                    error_log("g11n_pre_option_blogdescription:" . $in );
                }
                if (strcmp(get_option('g11n_l10n_props_desc'),"Yes")==0) {

                    $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                    if (is_null($title_post)) {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogdescription',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID,
                                                            'post_title'    => 'blogdescription',
                                                            'post_content'  => $in,
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("g11n_pre_option_blogdescription post id:" . $new_post_id );
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
                            error_log("In g11n_the_posts_filter, post_id: " . $post->ID . " excerpt: " . $post->post_excerpt);
                        }
		        if ((strcmp($post->post_type, "product") === 0) && (! empty($post->post_excerpt))) {
                            if ( WP_TMY_G11N_DEBUG ) {
                                error_log("In g11n_the_posts_filter, excerpt post_id: " . $post->ID . " excerpt: " . $post->post_excerpt);
                            }
                            $post->post_excerpt="excert l10n";
                            $post->post_title="title l10n:". $post->post_title;
		        }
	            }
                    return $posts;
        }

	public function g11n_excerpt_filter($input) {

                    if ( ! is_admin() ) {
		        global $wp_query; 

		        $postid = $wp_query->post->ID;
		        $posttype = $wp_query->post->post_type;

		        error_log("In excerpt filter, post id =" . $postid);
		        error_log("In excerpt filter, post type =" . $posttype);
		        error_log("In excerpt filter, input =" . $input);



		        if (strcmp($posttype,"product")==0) {
			    #return $input;
			    return "Translation of excerpt : " . $input;
		        }
                    }

        }

	public function g11n_content_filter($input) {

                    if ( ! is_admin() ) {

		        global $wp_query; 

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter filter, session_id=" . session_id() . "session lang=" . $_SESSION['g11n_language'] );
		            error_log("In g11n_content_filter filter, session lang = [" . $_SESSION['g11n_language']. "]");
		            error_log("In g11n_content_filter filter, cookie lang = [" . $_COOKIE['g11n_language'] . "]");
		            error_log("In g11n_content_filter filter, browser lang = [" . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "]");
                        }

		        $postid = $wp_query->post->ID;
		        $posttype = $wp_query->post->post_type;

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter filter, postid, posttype:" . $postid . " " . $posttype );
                        }
		        if ((strcmp(get_option('g11n_l10n_props_posts'),"Yes")!=0) and 
			    (strcmp($posttype,"post")==0)) {
			    return $input;
		        }

		        if ((strcmp(get_option('g11n_l10n_props_pages'),"Yes")!=0) and 
			    (strcmp($posttype,"page")==0)) {
			    return $input;
		        }

		        if (strcmp($posttype,"product")==0) {
			    return $input;
			    //return "Translation of cts: " . $input;
		        }

		        $language_options = get_option('g11n_additional_lang');
		        $g11n_current_language = $_SESSION['g11n_language'];
		        $language_name = $language_options[$g11n_current_language];
		        //$translation_post_id = $this->translator->get_translation_id($postid,$language_name,$posttype);

		        $translation_post_id = $this->translator->get_translation_id($postid,$language_name,$posttype,false);

                        if ( WP_TMY_G11N_DEBUG ) {
		            error_log("In g11n_content_filter original post id = " . $postid . ".");
		            error_log("In g11n_content_filter language = " . $language_name . ".");
		            error_log("In g11n_content_filter type = " . $posttype . ".");
		            error_log("In g11n_content_filter translation_post_id = " . $translation_post_id . ".");
                        }

		        if(strcmp(get_option('g11n_switcher_post'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher();
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

                    if ( is_admin() ) {
                        return $title;
                    }
		    global $wp_query; 
		    //$postid = $wp_query->post->ID;
		    //error_log("G11N TITLE FILTER, session_id=" . session_id() . "session lang=" . $_SESSION['g11n_language'] );
		    //error_log("G11N TITLE FILTER, id = [" . $id . "]");
		    //error_log("G11N TITLE FILTER, postid = [" . $postid . "]");

                    //error_log("The Title filter, excerpt post_id: " . $wp_query->post->ID . " type: " . $wp_query->post->post_type);
                    //error_log("The Title filter, excerpt post_id: " . var_export($wp_query, true));

		    if (!isset($wp_query->post)) return $title;

		    $posttype = $wp_query->post->post_type;

		    if ((strcmp(get_option('g11n_l10n_props_posts'),"Yes")!=0) and 
			(strcmp($posttype,"post")==0)) {
			return $title;
		    }

		    if ((strcmp(get_option('g11n_l10n_props_pages'),"Yes")!=0) and 
			(strcmp($posttype,"page")==0)) {
			return $title;
		    }

		    if (strcmp($posttype,"product")==0) {
			#return $title;
			error_log("Translation of Title: " . $title);
			return "Translation of Title: " . $title;
		    }

		    $language_options = get_option('g11n_additional_lang');
		    //$g11n_current_language = $_SESSION['g11n_language'];
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
                        error_log("In g11n_wp_title_filter,output,show:" . $output . " " . $show);
                    }

                    if ( is_admin() ) {
                        return $output;
                    }
		    if (strcmp($show,'description')==0) {
                        if (strcmp(get_option('g11n_l10n_props_desc'),"Yes")==0){
			     $g11n_current_language = $_SESSION['g11n_language'];
			     $language_options = get_option('g11n_additional_lang');
			     $language_name = $language_options[$g11n_current_language];

                             $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                             if (! is_null($title_post)) {
                                 $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
			         if (isset($translation_post_id)) {
                                     if ( WP_TMY_G11N_DEBUG ) {
                                         error_log("In g11n_wp_title_filter,blogdescription translation id:" . $translation_post_id);
                                     }
			             $output = get_post_field("post_content", $translation_post_id);
			         }
		             }
                        }
			if(strcmp(get_option('g11n_switcher_tagline'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher();
			} else {
			    $switcher_html = "";
			}
		        return $output . $switcher_html;
                    }

		    if (strcmp($show,'name')==0) {
                        if (strcmp(get_option('g11n_l10n_props_blogname'),"Yes")==0){
			    $g11n_current_language = $_SESSION['g11n_language'];
			    $language_options = get_option('g11n_additional_lang');
			    $language_name = $language_options[$g11n_current_language];

                            $title_post  = get_page_by_title('blogname',OBJECT,'post');
                            if (! is_null($title_post)) {
                                $translation_post_id = $this->translator->get_translation_id($title_post->ID,$language_name,"post",false);
			        if (isset($translation_post_id)) {
                                    if ( WP_TMY_G11N_DEBUG ) {
                                        error_log("In g11n_wp_title_filter,blogname translation id:" . $translation_post_id);
                                    }
			            $output = get_post_field("post_content", $translation_post_id);
			        }
                            }
		        }
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_wp_title_filter, g11n_switcher_title,".get_option('g11n_switcher_title'));
                        }
			if(strcmp(get_option('g11n_switcher_title'),"Yes")==0){
			    $switcher_html = $this->translator->get_language_switcher();
			} else {
			    $switcher_html = "";
			}
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In g11n_wp_title_filter, output,".$output . $switcher_html);
                        }
		        return $output . $switcher_html;
		    } 

		    return $output;
	}


}
