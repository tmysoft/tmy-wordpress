<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/admin
 * @author     Yu Shao <yu.shao.gm@gmail.com>
 */

class TMY_G11n_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $translator ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->translator = $translator;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmy-g11n-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmy-g11n-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function tmy_plugin_register_settings() {

    		register_setting( 'tmy-g11n-settings-group', 'g11n_default_lang' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_additional_lang' );

    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_url' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_user' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_token' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_project' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_version' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_server_trunksize' );

    		register_setting( 'tmy-g11n-settings-group', 'g11n_l10n_props_blogname' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_l10n_props_desc' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_l10n_props_posts' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_l10n_props_pages' );
	
    		register_setting( 'tmy-g11n-settings-group', 'g11n_site_lang_cookie' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_site_lang_session' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_site_lang_query_string' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_site_lang_browser' );

    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_tagline' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_post' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_title' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_sidebar' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_floating' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_switcher_type' );

    		register_setting( 'tmy-g11n-settings-group', 'g11n_auto_pullpush_translation' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_resource_file_location' );
    		register_setting( 'tmy-g11n-settings-group', 'g11n_editor_choice' );
	
	}

	public function tmy_plugin_register_admin_menu() {

		add_options_page( 'TMY Setup', 
                          	  'TMY Setup', 
                            	  'manage_options', 
                          	  'my-unique-identifier', 
                          	  array( $this,
                                         'tmy_admin_options_page') );

        	add_options_page( 'TMY Dashboard',
                          	  'TMY Dashboard',
                          	  'manage_options',
                          	  'tmy-l10n-manager',
                          	  array( $this,
                                         'tmy_l10n_manager_page') );
	
        	add_options_page( 'TMY Diagnosis',
                          	  'TMY Diagnosis',
                          	  'manage_options',
                          	  'tmy-support-manager',
                          	  array( $this,
                                         'tmy_support_manager_page') );
	
               	add_meta_box( 'trans_status', 
		                  'Translation Status', 
		                  array( $this, 'tmy_translation_metabox_callback'), 
		                  array('post','page','g11n_translation'),
		                  'normal', // (normal, side, advanced)
		                  'default' // (default, low, high, core) 
                            );
	}

        public function tmy_translation_metabox_callback( $post ) {

	    //echo 'hey: ' . $post->ID;
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

                $post_id = $post->ID;
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
             
                    if (((strcmp($original_title,"blogname")===0)&&(strcmp(get_option('g11n_l10n_props_blogname'),"Yes")!==0)) ||
                        ((strcmp($original_title,"blogdescription")===0)&&(strcmp(get_option('g11n_l10n_props_desc'),"Yes")!==0)) ||
                        ((strcmp(get_post_type($original_id),"post")===0)&&(strcmp(get_option('g11n_l10n_props_posts'),"Yes")!==0)) ||
                        ((strcmp(get_post_type($original_id),"page")===0)&&(strcmp(get_option('g11n_l10n_props_pages'),"Yes")!==0))) {
		        echo ' Status: <button type="button" style="background-color:#C0C0C0;color:white;width:100px; height:25px;" >DISABLED</button> </b></br>';
                    } else {
                        if (strcmp($post_status,"publish")===0) {
		            //echo ' Status: <button type="button" class="button button-secondary">LIVE</button> </b></br>';
		            echo ' Status: <button type="button" style="background-color:#4CAF50;color:white;width:50px; height:25px;" >LIVE</button> </b></br>';
		        } else {
		            echo ' Status: <button type="button" style="background-color:#CD5C5C;color:white;width:100px; height:25px;" >Not LIVE</button></b></br>';
	       	        }
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

        public function tmy_admin_options_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		defined( 'ABSPATH' ) or die();
    		$tmy_g11n_dir = dirname( __FILE__ );

    		require_once "{$tmy_g11n_dir}/include/g11n-lang-list.php";

		?>		

		<div class="wrap"> <h1>Globalization Options</h1>
		<form method="post" action="options.php">
		
		<?php

    		settings_fields( 'tmy-g11n-settings-group' );
    		do_settings_sections( 'tmy-g11n-settings-group' );

		?>
                    <table class="form-table">
        		<tr valign="top">
        		<th scope="row">Default Language</th>

        		<td><select name="g11n_default_lang">
		<?php
        
          	if (!get_option('g11n_default_lang')) {
              		$sys_default_lang = "English";
          	} else {
              		$sys_default_lang = get_option('g11n_default_lang');
          	}

          	foreach ($complete_lang_list as $lang => $code) :
               		echo '<option value="' . $lang . '" ' . 
                             selected($sys_default_lang, $lang) . ' >' . 
                             $lang . '</option>';
          	endforeach;
		
		?>   
		    </select>
        		</td>
        		</tr>
        		<tr valign="top">
        		<th scope="row">All Enabled Languages </th><td>
		<?php



        	if (!get_option('g11n_additional_lang')) {
            		$all_configed_langs = array('English' => $complete_lang_list['English']);
        	} else {
            		$all_configed_langs = get_option('g11n_additional_lang');
        	}
		
		//$all_configed_langs = get_option('g11n_additional_lang');
		//if (strcmp(esc_attr($all_configed_langs),"")==0){
		//    $dlang = esc_attr(get_option('g11n_default_lang'));
		    //$all_configed_langs = array('$dlang' => $complete_lang_list['$dlang']);
		//    $all_configed_langs = array('English' => "en");
		//}

		if (is_array($all_configed_langs)) {
		    foreach( $all_configed_langs as $value => $code) {
		        echo $value. '('.$code.') <input type="checkbox" name="g11n_additional_lang['.$value.']" value="'.$code.'" checked/><br>';
		    }
		}
		
		?>

		------
        	<div id="g11n_new_languages"></div>
       		<select id="g11n_add_language">

		<?php
        
          	foreach ($complete_lang_list as $lang => $code) :
               		echo '<option value="'.$code.'">'.$lang.'</option>';
          	endforeach;
       

		?>
		</select>
		<button type="button" onclick="G11nmyFunction()">Add Language</button> Click Save To Keep The Changes

		<script>
		function G11nmyFunction() {

		    	var e = document.getElementById("g11n_add_language");
		    	var code = e.options[e.selectedIndex].value;
		    	var lang = e.options[e.selectedIndex].text;
		    	var div = document.getElementById('g11n_new_languages');
		    	//div.innerHTML = div.innerHTML + lang + "(" + code +") ";
		    	var text = document.createTextNode(lang + "(" + code +") ");
		    	var cb = document.createElement('input');
		    	cb.type = 'checkbox';
		    	cb.checked = true;
		    	cb.name = "g11n_additional_lang["+lang+"]";
		    	cb.value = code;
		    	div.appendChild(text);
		    	div.appendChild(cb);

		}
		</script>


	       	</td>
	        </tr>

	        <tr valign="top">
	        <th scope="row">Get Visitor Language Preference From</th>
	        <td>Cookie <input type="checkbox" name="g11n_site_lang_cookie" value="Yes" "<?php checked(esc_attr( get_option('g11n_site_lang_cookie')), "Yes"); ?> /><br>
            		Browser Language Preference <input type="checkbox" name="g11n_site_lang_browser" 
                                                           value="Yes" "<?php checked(esc_attr( get_option('g11n_site_lang_browser')), "Yes"); ?> />
        	</td>
        	</tr>

        	<tr valign="top">
        	<th scope="row">Translation Server(Optional)</th>
        	<td>URL <input type="text" name="g11n_server_url" value="<?php echo esc_attr( get_option('g11n_server_url') ); ?>" /><br>
            	User <input type="text" name="g11n_server_user" value="<?php echo esc_attr( get_option('g11n_server_user') ); ?>" />
            	Token <input type="text" name="g11n_server_token" value="<?php echo esc_attr( get_option('g11n_server_token') ); ?>" /> <br>
            	Project Name <input type="text" id="g11n_server_project" name="g11n_server_project" value="<?php echo esc_attr( get_option('g11n_server_project') ); ?>" />
            	<!-- <button onclick="g11ncreateproject('project')">Create Project on Translation Server</button> -->
                <input type="button" value="Create Project on Translation Server" onclick="g11ncreateproject('project')"><br>
            	Version <input type="text" id="g11n_server_version" name="g11n_server_version" value="<?php echo esc_attr( get_option('g11n_server_version') ); ?>" />
                <input type="button" value="Create Version on Translation Server" onclick="g11ncreateproject('version')"><br>
            	Trunk Size <input type="text" name="g11n_server_trunksize" value="<?php echo esc_attr( get_option('g11n_server_trunksize',900) ); ?>" />
        	</td>

		<script>
		function g11ncreateproject(type) {

		    //var proj_name = document.getElementById("g11n_server_project").value;
		    //console.log(document.getElementById("g11n_server_project").value);
		    //
		    var r = confirm("This will create the " + type + ": " + document.getElementById("g11n_server_"+type).value + " in translation server");
		    if (r == true) {
			jQuery(document).ready(function($) {
				var data = {
					'action': 'tmy_create_server_project',
					'proj_name': document.getElementById("g11n_server_project").value,
					'proj_ver': document.getElementById("g11n_server_version").value,
					'action_type': type
				};
	//		        jQuery.post(ajaxurl, data, function(response) {
	//			    alert('Server Reply: ' + response);
	//	                    console.log("aaa");
	//			    //var div = document.getElementById("g11n_server_project_status");
	//			    //var text = document.createTextNode(response);
	//			    //div.appendChild(text);
	//			});
                        
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


		<tr valign="top">
        	<th scope="row">Enable Translation On</th>
        	<td>
            	<br>
            	<input type="checkbox" name="g11n_l10n_props_blogname" value="Yes" <?php checked( esc_attr(get_option('g11n_l10n_props_blogname')), "Yes" ); ?> /> 
                                                                                                            Site Title/Blog Name <br>
            	<input type="checkbox" name="g11n_l10n_props_desc" value="Yes" <?php checked( esc_attr(get_option('g11n_l10n_props_desc')), "Yes" ); ?> /> Tagline<br>
            	<input type="checkbox" name="g11n_l10n_props_posts" value="Yes" <?php checked( esc_attr(get_option('g11n_l10n_props_posts')), "Yes" ); ?> /> Posts<br>
            	<input type="checkbox" name="g11n_l10n_props_pages" value="Yes" <?php checked( esc_attr(get_option('g11n_l10n_props_pages')), "Yes" ); ?> /> Pages 
        	</td>
        	</tr>             

        	<tr valign="top">
        	<th scope="row">Language Switcher Location</th>
        	<td>
            	<input type="checkbox" name="g11n_switcher_title" value="Yes" <?php checked( esc_attr(get_option('g11n_switcher_title')), "Yes" ); ?> /> In Title <br>
            	<input type="checkbox" name="g11n_switcher_tagline" value="Yes" <?php checked( esc_attr(get_option('g11n_switcher_tagline')), "Yes" ); ?> /> In Tagline <br>
            	<input type="checkbox" name="g11n_switcher_post" value="Yes" <?php checked( esc_attr(get_option('g11n_switcher_post')), "Yes" ); ?> /> In Each Post <br>
            	<input type="checkbox" name="g11n_switcher_sidebar" value="Yes" <?php checked( esc_attr(get_option('g11n_switcher_sidebar')), "Yes" ); ?> /> Top of Sidebar <br>
            	<input type="checkbox" name="g11n_switcher_floating" value="Yes" <?php checked( esc_attr(get_option('g11n_switcher_floating')), "Yes" ); ?> /> Flating Menu <br> <br>
                Language Switcher is also available in "G11n Language Widget" from "Appearance-> Widgets".
 	    	</td>
                </tr>
            	<?php

         
		echo '<tr valign="top"><th scope="row">Language Switcher Type</th><td>';

		  
		$g11n_switch_type = array(
		          'Text' => __('Text'),
		          'Flag' => __('Flag')
		);
		
		foreach ($g11n_switch_type as $key => $desc) :
		          $selected = (get_option('g11n_switcher_type','Text') == $key) ? 'checked="checked"' : '';
		          echo "\n\t<label><input type='radio' name='g11n_switcher_type' value='" . esc_attr($key) . "' $selected/> $desc</label><br />";
	  	endforeach;
		 
          	?>
        	</td>
        	</tr>

        	<tr valign="top">
        	<th scope="row">Use Classic Editor</th>
        	<td><select name="g11n_editor_choice">
               		<option value='Yes'  <?php selected( esc_attr(get_option('g11n_editor_choice')), 'Yes' ); ?>>Yes</option>
               		<option value='No'  <?php selected( esc_attr(get_option('g11n_editor_choice')), 'No' ); ?>>No</option>
            	</select>
        	</td>
        	</tr>
        	<tr valign="top">
        	<th scope="row">Auto Push/Pull Translation</th>
        	<td><select name="g11n_auto_pullpush_translation">
               		<option value='No'  <?php selected( esc_attr(get_option('g11n_auto_pullpush_translation')), 'No' ); ?>>No</option>
            	</select>
        	</td>
        	</tr>
        	<tr valign="top">
        	<th scope="row">Translation Resource File Directory</th>
        	<td><input type="text" name="g11n_resource_file_location" value="<?php echo esc_attr( get_option('g11n_resource_file_location') ); ?>" /></td>
        	</tr>
    		</table>
    			<?php submit_button(); ?>
		</form>
		</div>
		<?php 

        }

        public function tmy_l10n_manager_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
                    <br>
                    <script>
                    function G11nGetLocalTranslationStatus() {
                        var div = document.getElementById('tmy_local_translation_status');

                        jQuery(document).ready(function($) {
                                var data = {
                                    'action': 'tmy_get_local_translation_status',
                                    'secret': 8763
                                };
                                jQuery.post(ajaxurl, data, function(response) {
                                    div.innerHTML = response.slice(0, -1) ;
                                });
                                return;
                        });
                    }

                    function G11nmyGetServerStatus() {
                        var div = document.getElementById('tmy_server_status');
                        div.innerHTML = "Connecting to server ....";

                        jQuery(document).ready(function($) {
                                var data = {
                                    'action': 'tmy_get_project_status',
                                    'secret': 8763
                                };
                                jQuery.post(ajaxurl, data, function(response) {
                                    div.innerHTML = response;
                                });
                                return;
                        });
                    }
                    </script>

		<h2>Translation Status:</h2>
                <button type="button" onclick="G11nGetLocalTranslationStatus()">Refresh Translation Status</button>
		<div class="wrap">
                <div id="tmy_local_translation_status">

		<?php

                global $wpdb;
                $rows = $wpdb->get_results( 'SELECT ID, post_title, meta_value, post_modified  FROM '.
                                                  $wpdb->prefix.'posts,'.
                                                  $wpdb->prefix.'postmeta where post_type="g11n_translation" and '.
                                                  $wpdb->prefix.'posts.post_status != "trash" and '.
                                                  $wpdb->prefix.'posts.ID='.
                                                  $wpdb->prefix.'postmeta.post_id and '.
                                                  $wpdb->prefix.'postmeta.meta_key="g11n_tmy_lang" ORDER BY ID');

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In tmy_l10n_manager_page sql:" . json_encode($rows));
                }
                echo "<table>";
                echo    '<tr><td><b>Orig. ID</b></td>' .
                                        '<td><b>Trans. ID</b></td>' .
                                        '<td><b>Title</b></td>' .
                                        '<td><b>Lang</b></td>' .
                                        '<td><b>Last Modified</b></td></tr>';

                $row_arr = array();
                foreach ( $rows as $row ) {
                    $post_info = $this->translator->get_translation_info($row->ID);
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("In tmy_l10n_manager_page trans post:" . json_encode($post_info));
                    }
                    if (isset($post_info[0])) {
                        $row_arr[] = array("TID" => $post_info[0]->ID,
                                               "ID" => $row->ID,
                                               "post_title" => $row->post_title,
                                               "meta_value" => $row->meta_value,
                                           "post_modified" => $row->post_modified);
                    }
                }
                sort($row_arr);
                echo "<br>";

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("In tmy_l10n_manager_page trans post:" . json_encode($row_arr));
                }
                foreach ( $row_arr as $row ) {
                    $id_link = 'post.php?post=' . $row["ID"] . '&action=edit';
                    $id_hyper = '<a href="' . admin_url($id_link) . '" target="_blank">' . $row["ID"] . '</a>';
        
                    $tid_link = 'post.php?post=' . $row["TID"] . '&action=edit';
                    $tid_hyper = '<a href="' . admin_url($tid_link) . '" target="_blank">' . $row["TID"] . '</a>';
                    //if (isset($post_info[0])) {
                        echo             '<tr><td>' . $tid_hyper . '</td><td>' .
                                                         $id_hyper . '</td><td>' .
                                                         $row["post_title"] . '</td><td>' .
                                                         $row["meta_value"] . '</td><td>' .
                                                         $row["post_modified"] . '</td></tr>';
                        if ( WP_TMY_G11N_DEBUG ) {
                            error_log("In tmy_l10n_manager_page trans post:" . $row["post_title"]);
                        }
                    //}
                }
                echo "</table>";

                echo "</div>";
           
		echo "<h2>Translation Server Status:</h2>";

                if ((strcmp('', get_option('g11n_server_user','')) === 0) || (strcmp('', get_option('g11n_server_token','')) === 0)) {
                     echo "Translation server is not in use, configure the Translation Server(Optional) section in the setup page to start<br>";
                } else {
                    echo '<button type="button" onclick="G11nmyGetServerStatus()">Get Server Status</button> Click Button To Get Latest Status';
        	    echo '<div id="tmy_server_status"></div>';


               }

        }

        public function tmy_support_manager_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

    			/*Collecting System Running Information*/

		}


		?>
		<div class="wrap">
		<h1>G11n Plugin Diagnosis</h1>
		<h3>
		This Diagnosis tool is providing advanced system information of how your site is running, it is 			  
		colloecking following information: Base system(phpinfo), G11n Plugin, Default theme and some system 			  
		options, if you need further assitance to trouble shoot your site, please review there is no sensive 			  
		information included, click copy and send to <a href="mailto:yu.shao.gm@gmail.com">yu.shao.gm@gmail.com</a> in email.
		  <br>
		  <br>
		    <button onclick="g11ncopysysteminfotext()">Copy Text</button>
		  <br></h3>
		<?php
error_log ("Mei debug");
		date_default_timezone_set('America/New_York');
		$g11n_sys_info = "\n***** G11n Plugin Diagnosis *****\n" . date("F d, Y l H:s e") . "\n";
    		//$g11n_sys_info .= phpversion() . "\n";
    		//$g11n_sys_info .= var_export(get_loaded_extensions(),true) . "\n";
    		//$g11n_sys_info .= var_export(apache_getenv(),true) . "\n";
    		//global $_ENV;
    		//$g11n_sys_info .= var_export($_ENV,true) . "\n";
    		//$g11n_sys_info .= var_export(ini_get_all(),true) . "\n";
    		//$g11n_sys_info .= json_encode(ini_get_all(),true) . "\n";
    		$g11n_sys_info .= "*** G11n Plugin Info: \n" . var_export(get_plugin_data( __FILE__ ),true) . "\n";


    		global $wpdb;
    		$no_g11n_config = $wpdb->get_results( 'select count(*) as count from '.
                                                      $wpdb->prefix.
                                                      'options where option_name like "g11n%"');
    		$no_g11n_trans = $wpdb->get_results( 'select count(*) as count from '.
                                                      $wpdb->prefix.
                                                      'posts where post_type="g11n_translation"');
    		$no_g11n_metas = $wpdb->get_results( 'select count(*) as count from '.
                                                     $wpdb->prefix.
                                                     'postmeta where meta_key="g11n_tmy_lang" or meta_key="orig_post_id" or meta_key="translation_push_status"');

    		$g11n_sys_info .= "*** There are ".
                                  $no_g11n_config[0]->count.
                                  " config entries, ".
                                  $no_g11n_trans[0]->count.
                                  " translations, ".
                                  $no_g11n_metas[0]->count.
                                  " meta data entries in system.\n";


    		$g11n_sys_info .= "*** Current Theme: \n" . var_export(wp_get_theme(),true) . "\n";
    		$g11n_sys_info .= "*** home_path: " . var_export(get_home_path(),true) . "\n";

        	//$home_trans_list = get_available_languages(get_home_path() . "wp-content/languages");
        	$home_trans_list = scandir(get_home_path() . "wp-content/languages");
        	$g11n_sys_info .= "Translation in wp-content/languages: \n";
        	$g11n_sys_info .= var_export($home_trans_list,true) . "\n";

    		$g11n_sys_info .= "*** theme_roots: " . var_export(get_theme_roots(),true) . "\n";
    		$g11n_sys_info .= "*** theme_root: " . var_export(get_theme_root(),true) . "\n";
    		$g11n_sys_info .= "*** template dir: " . var_export(get_template_directory(),true) . "\n";
    		$g11n_sys_info .= "*** stylesheet dir: " . var_export(get_stylesheet_directory(),true) . "\n";
    		//$g11n_sys_info .= "WP plugin_dir_path: " . var_export(plugin_dir_path(__FILE__),true) . "\n";
    		//$g11n_sys_info .= "WP plugin_basename: " . var_export(plugin_basename(__FILE__),true) . "\n";

    		$theme_list = search_theme_directories();
    		$g11n_sys_info .= "*** Themes List: " . var_export($theme_list,true) . "\n";

    		foreach ($theme_list as $name => $locs) {
        		$trans_list = get_available_languages($locs['theme_root'] . "/" . $name);
        		$g11n_sys_info .= "*** Themes Translation in: " .$locs['theme_root']."/". $name . "\n";
        		$g11n_sys_info .= var_export($trans_list,true) . "\n";
    		}
    
		//$g11n_sys_info .= "Wordpress Root: " . var_export(get_theme_roots(),true) . "\n";
    		//$g11n_sys_info .= "Wordpress Options: " . var_export(get_alloptions(),true) . "\n";
    
		$options_array = array ('g11n_default_lang',
                             'g11n_additional_lang',
                             'g11n_server_url',
                             'g11n_server_user',
                             //'g11n_server_token',
                             'g11n_server_project',
                             'g11n_server_version',
                             'g11n_l10n_props_blogname',
                             'g11n_l10n_props_desc',
                             'g11n_l10n_props_posts',
                             'g11n_l10n_props_pages',
                             'g11n_site_lang_cookie',
                             'g11n_site_lang_session',
                             'g11n_site_lang_query_string',
                             'g11n_site_lang_browser',
                             'g11n_switcher_tagline',
                             'WP_LANG_DIR',
                             'g11n_switcher_post');

    		foreach ( $options_array as $op_row ) {
        		$g11n_sys_info .= "*** Wordpress Options: ". 
                                          $op_row . ":" . 
                                          var_export(get_option($op_row),true) . "\n";

    		}


    		$info_rest_url = rtrim(get_option('g11n_server_url'),"/") .  "/rest/stats/proj/" .
                get_option('g11n_server_project') .  "/iter/" .
                get_option('g11n_server_version') .  "?detail=true&word=false";

    		$info_server_reply = $this->translator->rest_get_translation_server($info_rest_url);

    		$test_payload = $info_server_reply["payload"];
    		$test_debug_log = $info_server_reply["server_msg"];

    		$g11n_sys_info .= "*** Translation Server Status: ". $info_rest_url. "\n";
    		//$g11n_sys_info .= "Testing Translation Server, Return Payload: ". var_export($test_payload,true) . "\n";
    		$g11n_sys_info .= "Extra Message : ". var_export($test_debug_log,true) . "\n";


    		global $wpdb;
    		$testing_local_rows = $wpdb->get_results( 'SELECT ID, post_title,post_id,meta_id,meta_value FROM '.
                                                          $wpdb->prefix.'posts,'.
                                                          $wpdb->prefix.'postmeta where post_type="g11n_translation" and '.
                                                          $wpdb->prefix.'posts.ID='.
                                                          $wpdb->prefix.'postmeta.post_id and '.
                                                          $wpdb->prefix.'postmeta.meta_key="g11n_tmy_lang"');

    		$g11n_sys_info .= "*** Local Translations: ". var_export($testing_local_rows,true) . "\n";

    		$entitiesToUtf8 = function($input) {
        	// http://php.net/manual/en/function.html-entity-decode.php#104617
        	return preg_replace_callback("/(&#[0-9]+;)/", function($m) { 
                       return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input);
    		};
    		$plainText = function($input) use ($entitiesToUtf8) {
        	return trim(html_entity_decode($entitiesToUtf8(strip_tags($input))));
    		};
    		$titlePlainText = function($input) use ($plainText) {
        	return '# '.$plainText($input);
    		};
    
    		ob_start();
    		phpinfo(-1);
    
    		$phpinfo = array('phpinfo' => array());

    		// Strip everything after the <h1>Configuration</h1> tag (other h1's)
    		if (!preg_match('#(.*<h1[^>]*>\s*Configuration.*)<h1#s', ob_get_clean(), $matches)) {
        		return array();
    		}
    
    		$input = $matches[1];
    		$matches = array();

		    if(preg_match_all(
			'#(?:<h2.*?>(?:<a.*?>)?(.*?)(?:<\/a>)?<\/h2>)|'.
			'(?:<tr.*?><t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>)?)?</tr>)#s',
			$input, 
			$matches, 
			PREG_SET_ORDER
		    )) {
			foreach ($matches as $match) {
			    $fn = strpos($match[0], '<th') === false ? $plainText : $titlePlainText;
			    if (strlen($match[1])) {
				$phpinfo[$match[1]] = array();
			    } elseif (isset($match[3])) {
				$keys1 = array_keys($phpinfo);
				$phpinfo[end($keys1)][$fn($match[2])] = isset($match[4]) ? array($fn($match[3]), $fn($match[4])) : $fn($match[3]);
			    } else {
				$keys1 = array_keys($phpinfo);
				$phpinfo[end($keys1)][] = $fn($match[2]);
			    }

			}
		    }

    		$g11n_sys_info .= "*** PHPINFO \n ";
    		$g11n_sys_info .= var_export($phpinfo,true) . "\n";



    		//echo "<pre>".$g11n_sys_info."</pre>";
		?>
		<textarea rows="30" cols="100" class="scrollabletextbox" spellcheck="false" 
                                               name="g11_sys_info_box" 	id="g11n_sys_info_box_id">';
		<?php

  		echo $g11n_sys_info;
                ?>

		</textarea>
		<script>
			function g11ncopysysteminfotext() {
  				var copyText = document.getElementById("g11n_sys_info_box_id");
  				copyText.select();
  				document.execCommand("Copy");
  				//alert("Copied The Text");
			} 
			function g11ncleardata() {
    				var r = confirm("This will clear all configuration and translation, however you could rebuild easily.");
    				if (r == true) {
        			jQuery(document).ready(function($) {
        				var data = {
        				'action': 'tmy_create_clear_plugin_data',
        				'whatever': 8888
        				};
        				jQuery.post(ajaxurl, data, function(response) {
        					alert('Server Reply: ' + response);
        				});
                			return;
        				});
    				}
			} 
		</script>
		</h3>

		<br><br><br>
		<?php

    		echo "There are ".$no_g11n_config[0]->count." config entries, ".
                      $no_g11n_trans[0]->count." translations, ".
                      $no_g11n_metas[0]->count." meta data entries in system.".
                      "click clear button to clear configuration and translations.";

		?>
		<button onclick="g11ncleardata()">Clear Data</button>
		<br></div>
		<?php
	
        }

	public function tmy_create_sync_translation() {

            if ( WP_TMY_G11N_DEBUG ) {
                error_log("In tmy_create_sync_translation,id:".$_POST['id'].",type:".$_POST['post_type']);
            }

            $message = "Number of translation entries created: ";

            $all_langs = get_option('g11n_additional_lang');
            $default_lang = get_option('g11n_default_lang');
            unset($all_langs[$default_lang]);

            if ( WP_TMY_G11N_DEBUG ) {
                error_log("In tmy_create_sync_translation,title:".get_post_field('post_title', $_POST['id']));
                error_log("In tmy_create_sync_translation,type:".get_post_field('post_type', $_POST['id']));
            }

             if (strcmp($_POST['post_type'], "g11n_translation") !== 0) {

                 if (strcmp($_POST['post_type'], "product") !== 0) {
                    
                     if ( WP_TMY_G11N_DEBUG ) { error_log("In tmy_create_sync_translation langs,".json_encode($all_langs));};

                     // creating language translations for each language
                     $num_success_entries = 0;
                     $num_langs = 0;
                     if (is_array($all_langs)) {
                         $num_langs = count($all_langs);
                         foreach( $all_langs as $value => $code) {
                             $translation_id = $this->translator->get_translation_id($_POST['id'],$code,$_POST['post_type']);
                             if ( WP_TMY_G11N_DEBUG ) { 
                                 error_log("In tmy_create_sync_translation, translation_id = " . $translation_id);
                             }
                             if (! isset($translation_id)) {
                                 $num_success_entries += 1;
                                 //$message .= " $value($code)";
                                 //error_log("in create_sync_translation, no translation_id");
                                 $translation_title = get_post_field('post_title', $_POST['id']);
                                 $translation_contents = get_post_field('post_content', $_POST['id']);
                                 $g11n_translation_post = array(
                                       'post_title'    => $translation_title,
                                       'post_content'  => $translation_contents,
                                       'post_type'  => "g11n_translation"
                                 );
                                 $new_translation_id = wp_insert_post( $g11n_translation_post );
                                 add_post_meta( $new_translation_id, 'orig_post_id', $_POST['id'], true );
                                 add_post_meta( $new_translation_id, 'g11n_tmy_lang', $code, true );
                             }
                             if ( WP_TMY_G11N_DEBUG ) { 
                                 error_log("In tmy_create_sync_translation, new_translation_id = " . $new_translation_id);
                             }
                         }
                     }
                     $message .= $num_success_entries." (no. of languages configured: ". $num_langs.").";

                     // creating language translations for each language

                     // push to translation if all setup
                     if ((strcmp('', get_option('g11n_server_user','')) !== 0) && 
                         (strcmp('', get_option('g11n_server_token','')) !== 0) &&
                         (strcmp('', get_option('g11n_server_url','')) !== 0)) {

                         $content_title = get_post_field('post_title', $_POST['id']);
                         $tmp_array = preg_split('/(\n)/', get_post_field('post_content', $_POST['id']),-1, PREG_SPLIT_DELIM_CAPTURE);
                         $contents_array = array();

                         if (strcmp(get_post_field('post_title', $_POST['id']),'blogname') === 0){
                             $json_file_name = "WordpressG11nAret-" . "blogname" . "-" . $_POST['id'];
                         } elseif (strcmp(get_post_field('post_title', $_POST['id']),'blogdescription') === 0){
                             $json_file_name = "WordpressG11nAret-" . "blogdescription" . "-" . $_POST['id'];
                         } else {
                             $json_file_name = "WordpressG11nAret-" . $_POST['post_type'] . "-" . $_POST['id'];
                             array_push($contents_array, $content_title);
                         }

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
                         $push_return_msg = $this->translator->push_contents_to_translation_server($json_file_name, $contents_array);
                         $message .= " " . $json_file_name . " is pushed to Translation Server: ".$push_return_msg;
                         if ( WP_TMY_G11N_DEBUG ) {
                              error_log("In tmy_create_sync_translation,filename:".$json_file_name);
                         }
                     } else {
                        $message .= " No translation server setup.";
                     }
                     // push to translation if all setup
                 }
             }

             echo $message;

	     wp_die();
        }

	public function tmy_g11n_create_server_project() {

		error_log("create project " . $_POST['proj_name']);
		error_log("create project " . $_POST['proj_ver']);
		error_log("create project " . $_POST['action_type']);


		$ch = curl_init();

		if (strcmp($_POST['action_type'],"project")==0) {
		    $rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest/projects/p/" . $_POST['proj_name'];
		    $payload = json_encode(array(
			     "id" => $_POST['proj_name'],
			     "defaultType" => "Gettext",
			     "name" => $_POST['proj_name'],
			     "description" => "Introduction"
			    ));
		}

		if (strcmp($_POST['action_type'],"version")==0) {
		    $rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest/project/" . $_POST['proj_name'] . "/version/" . $_POST['proj_ver'];
		    $payload = json_encode(array(
			     "id" => $ver_name,
			     "defaultType" => "Gettext",
			     "status" => "ACTIVE",
			     "privateProject" => 0
			    ));
		}
		curl_reset($ch);

		error_log("REST URL" . $rest_url);
		error_log("REST PALOAD" . $payload);

		curl_setopt($ch, CURLOPT_URL, $rest_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-Auth-User: ' . get_option('g11n_server_user'),
		    'X-Auth-Token: ' . get_option('g11n_server_token'),
		    'Content-Type: application/json'
		    ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$http_code_msg = array(
		    200 => 'Already exists, read for use',
		    201 => 'Created',
		    401 => 'Authentication error',
		    403 => 'Operation forbidden',
		    500 => 'Internal server error');

		error_log("return msg: " . $http_code_msg[$http_code]);
		echo $http_code_msg[$http_code] . " (" . $http_code . ")";

		if (strcmp($_POST['action_type'],"version")==0) {
		    curl_reset($ch);
                    $default_language = get_option('g11n_default_lang');
                    $language_options = get_option('g11n_additional_lang', array());
                    unset($language_options[$default_language]);
                    $selected_langs = array_values($language_options);
                    foreach ($selected_langs as &$value) {
                        $value = str_replace("_", "-", $value);
                    }
		    error_log("create project optional lang list: " . json_encode($selected_langs));

		    $rest_url = "https://tmysoft.com/api/project/" . $_POST['proj_name'] . "/version/" . $_POST['proj_ver'] . "/locales";
                    curl_setopt($ch, CURLOPT_URL, $rest_url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'X-Auth-User: ' . get_option('g11n_server_user'),
                        'X-Auth-Token: ' . get_option('g11n_server_token'),
                        'Content-Type: application/json'
                        ));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("data" => $selected_langs)));

                    curl_exec($ch);

		}
		curl_close($ch);


		wp_die();

	}





	public function tmy_g11n_pull_translation($id, $locale) {

        
            // will probable need to remove this function
            /* Pulling translation in */
	    $rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest" . "/projects/p/" . 
	                    get_option('g11n_server_project') . "/iterations/i/" . 
	                    get_option('g11n_server_version') . "/r/" . 
	                    $id . "/translations/" . $locale . "?ext=gettext&ext=comment";
        
        
	    $server_reply = $this->translator->rest_get_translation_server($rest_url);
	    $translation_payload = $server_reply["payload"];
        
	                //$debug_log .= $server_reply["server_msg"];
        
	    if (isset($translation_payload->textFlowTargets[0]->content)) {
	        $translation_title = $translation_payload->textFlowTargets[0]->content;
	        //error_log("SYNC TRANSLATION TITLE = " . $translation_title);
	    } 
       
            $payload_size = count($translation_payload->textFlowTargets);
            $translation_contents = '';
            for ($i = 1; $i < $payload_size; $i++) {
                $translation_contents .= $translation_payload->textFlowTargets[$i]->content ;
            }
        
		               //if (isset($translation_payload->textFlowTargets[1]->content)) {
		               //     $translation_contents = $translation_payload->textFlowTargets[1]->content;
		               //     error_log("SYNC TRANSLATION CONTENTS = " . $translation_contents);
		               //} 
    
		               //$row->id is in the format of "Wordpress-post-23"
            $g11n_res_filename = preg_split("/-/", $id);
    
	    /* change the locale - to _ */
	    $locale = str_replace("-", "_", $locale);
   
            $default_lang_post_id = $g11n_res_filename[2];
            $payload_post_type = $g11n_res_filename[1];

                 //$debug_log .= " Finished translation,id=".$default_lang_post_id."post type=" . $payload_post_type;


            $translation_id = $this->translator->get_translation_id($default_lang_post_id,$locale,$payload_post_type);

            if ((strcmp($payload_post_type,"post") === 0)||(strcmp($payload_post_type,"page") === 0)) {
                     if (isset($translation_id)) {
                        //$debug_log .= " Found local item,id=".$translation_id." ".$payload_post_type;
                         $g11n_translation_post = array(
                           'ID'    => $translation_id,
                           'post_title'    => $translation_title,
                           'post_content'  => $translation_contents,
                           'post_type'  => "g11n_translation"
                         );
                         $update_post_id = wp_update_post( $g11n_translation_post );
                         //$debug_log .= " updated id=".$update_post_id;
                     } else {
                         $g11n_translation_post = array(
                           'post_title'    => $translation_title,
                           'post_content'  => $translation_contents,
                           'post_type'  => "g11n_translation"
                         );
                         $new_translation_id = wp_insert_post( $g11n_translation_post );
                         //$debug_log .= " created trans post id=".$new_translation_id;
                         add_post_meta( $new_translation_id, 'orig_post_id', $default_lang_post_id, true );
                         add_post_meta( $new_translation_id, 'g11n_tmy_lang', $locale, true );
                     }
            }

            if ((strcmp($payload_post_type,"blogname") === 0)) {
                     if (isset($translation_id)) {
                      //error_log("TRANSLATION LANG ID = " .  $translation_id);
                      $g11n_translation_post = array(
                        'ID'    => $translation_id,
                        'post_title'    => "Blog Name Translation",
                        'post_content'  => $translation_title,
                        'post_type'  => "g11n_translation"
                      );
                      $update_post_id = wp_update_post( $g11n_translation_post );
                      //$debug_log .= " found trans post id=".$update_post_id." ".$payload_post_type;
                     } else {
                      //error_log("TRANSLATION LANG ID = empty ");
                      $g11n_translation_post = array(
                        'post_title'    => "Blog Name Translation",
                        'post_content'  => $translation_title,
                        'post_type'  => "g11n_translation"
                      );
                      $new_translation_id = wp_insert_post( $g11n_translation_post );
                      //$debug_log .= " new trans post id=".$new_translation_id." ".$payload_post_type;
                      add_post_meta( $new_translation_id, 'option_name', "blogname", true );
                      add_post_meta( $new_translation_id, 'g11n_tmy_lang', $locale, true );
                     }
            }

            if ((strcmp($payload_post_type,"blogdescription") === 0)) {
                     if (isset($translation_id)) {
                         //error_log("TRANSLATION LANG ID = " .  $translation_id);
                         $g11n_translation_post = array(
                           'ID'    => $translation_id,
                           'post_title'    => "Blog Tag Translation",
                           'post_content'  => $translation_title,
                           'post_type'  => "g11n_translation"
                         );
                         $update_post_id = wp_update_post( $g11n_translation_post );
                         //$debug_log .= " found trans post id=".$update_post_id." ".$payload_post_type;
                     } else {
                         //error_log("TRANSLATION LANG ID = empty ");
                         $g11n_translation_post = array(
                           'post_title'    => "Blog Tag Translation",
                           'post_content'  => $translation_title,
                           'post_type'  => "g11n_translation"
                         );
                         $new_translation_id = wp_insert_post( $g11n_translation_post );
                         //$debug_log .= " new trans post id=".$new_translation_id." ".$payload_post_type;
                         add_post_meta( $new_translation_id, 'option_name', "blogdescription", true );
                         add_post_meta( $new_translation_id, 'g11n_tmy_lang', $locale, true );
                     }
            }

	}

	public function tmy_g11n_get_local_translation_status() {

            global $wpdb;
            $rows = $wpdb->get_results( 'SELECT ID, post_title, meta_value, post_modified  FROM '.
                                                  $wpdb->prefix.'posts,'.
                                                  $wpdb->prefix.'postmeta where post_type="g11n_translation" and '.
                                                  $wpdb->prefix.'posts.post_status != "trash" and '.
                                                  $wpdb->prefix.'posts.ID='.
                                                  $wpdb->prefix.'postmeta.post_id and '.
                                                  $wpdb->prefix.'postmeta.meta_key="g11n_tmy_lang" ORDER BY ID');

            $ret_msg = "<table>";
            $ret_msg .=  '<tr><td><b>Orig. ID</b></td>' .
                                        '<td><b>Trans. ID</b></td>' .
                                        '<td><b>Title</b></td>' .
                                        '<td><b>Lang</b></td>' .
                                        '<td><b>Last Modified</b></td></tr>';

            $row_arr = array();
            foreach ( $rows as $row ) {
                        $post_info = $this->translator->get_translation_info($row->ID);
                        if (isset($post_info[0])) {
                            $row_arr[] = array("TID" => $post_info[0]->ID,
                                               "ID" => $row->ID,
                                               "post_title" => $row->post_title,
                                               "meta_value" => $row->meta_value,
                                               "post_modified" => $row->post_modified);
                        }
            }
            sort($row_arr);
            //$return_msg .= json_encode($row_arr);
            $ret_msg .= "<br>";
            if ( WP_TMY_G11N_DEBUG ) {
                error_log("In tmy_g11n_get_local_translation_status, rows:".json_encode($row_arr));
            }
            foreach ( $row_arr as $row ) {
                $id_link = 'post.php?post=' . $row["ID"] . '&action=edit';
                $id_hyper = '<a href="' . admin_url($id_link) . '" target="_blank">' . $row["ID"] . '</a>';

                $tid_link = 'post.php?post=' . $row["TID"] . '&action=edit';
                $tid_hyper = '<a href="' . admin_url($tid_link) . '" target="_blank">' . $row["TID"] . '</a>';
                //if (isset($post_info[0])) {
                    $ret_msg .=  '<tr><td>' . $tid_hyper . '</td><td>' .
                                                         $id_hyper . '</td><td>' .
                                                         $row["post_title"] . '</td><td>' .
                                                         $row["meta_value"] . '</td><td>' .
                                                         $row["post_modified"] . '</td></tr>';
                //}
            }
            $ret_msg .= "<tr><td>". time() ."</td></tr>";
            $ret_msg .= "</table>  ";
            echo $ret_msg;
            if ( WP_TMY_G11N_DEBUG ) {
                error_log("In tmy_g11n_get_local_translation_status, ret:".json_encode($ret_msg));
            }

        }

	public function tmy_g11n_get_project_status() {

                $http_erro_code_msg = array(
                        200 => 'OK',
                        401 => 'Authentication error, check user and token',
                        404 => 'No project or version on translation server',
                        500 => 'Internal server error'
                        );

                $return_msg = '';
		$return_msg .= "<br>Translation Server URL: ". get_option('g11n_server_url') . ' ';

      	        $rest_url = rtrim(get_option('g11n_server_url'),"/") .  "/rest/version";
    		$server_reply = $this->translator->rest_get_translation_server($rest_url);

    	        if ($server_reply["http_code"] == 200) {
                    $return_msg .= "Sever Version: " . $server_reply["payload"]->versionNo . "<br>";
                    $return_msg .= "Project Name: <b>" . get_option('g11n_server_project'). "</b> ";
                    $return_msg .= "Project Version: <b>" . get_option('g11n_server_version') . "</b><br>";
                    $return_msg .= "<br>Translations Hosted on the Server: <br>";

                    $translation_server_status = True;
                    $rest_url = rtrim(get_option('g11n_server_url'),"/") .  "/rest/stats/proj/" .
                                get_option('g11n_server_project') .  "/iter/" .
                                get_option('g11n_server_version') .  "?detail=true&word=false";
                    $server_reply = $this->translator->rest_get_translation_server($rest_url);
                    $payload = $server_reply["payload"];

                    $return_msg .= "Progress Overview (Translated/Total): <br>";

                    if (! is_null($payload)){
                        if (is_array($payload->stats)){
                            foreach ( $payload->stats as $row ) {
                                $return_msg .= $row->locale . ": ". $row->translated . "/" . $row->total . " ";
                            }
                            $return_msg .=  "<br>";
                        }
		        if (is_array($payload->detailedStats)) {
                            $return_msg .= "<br>Document List(s):";
                            $return_msg .= "<br>Fully translated will be in <b>bold</b> and and pulling down to local database.";
                            $return_msg .= "<table>";
                            //$return_msg .= var_export($payload,true);
                            //$return_msg .= json_encode($payload);
                            foreach ( $payload->detailedStats as $row ) {
                                if (is_array($row->stats)) {
                                    $doc_lang_str = "";
		                    //$row->id is in the format of "Wordpress-post-23"
		                    $g11n_res_filename = preg_split("/-/", $row->id);
    
		                    $default_lang_post_id = $g11n_res_filename[2];
		                    $payload_post_type = $g11n_res_filename[1];
    
		                                   //$debug_log .= " Finished translation,id=".$default_lang_post_id."post type=" . $payload_post_type;

                                             // checking the post id/default_lang_post_id is a valid post in the system now, if yes, pulling the translation
                                             // otherwise ignore it

                                    if( is_null(get_post($default_lang_post_id))){
                                        $return_msg .= "<tr><td><b>" . $row->id ."</b></td><td colspan=\"".count($row->stats)."\"> No local post/page found for id ". $default_lang_post_id . "</td></tr>";

                                    } else {

                                        foreach ( $row->stats as $stat_row ) {
                                             if  ($stat_row->translated == $stat_row->total) {

                                                 //begin fully translated, need to pull the translation down to local WP database

		                                 /* Pulling translation in */
		                                 $rest_url = rtrim(get_option('g11n_server_url'),"/") . "/rest" . "/projects/p/" . 
		                                       get_option('g11n_server_project') . "/iterations/i/" . 
		                                       get_option('g11n_server_version') . "/r/" . 
		                                       $row->id . "/translations/" . $stat_row->locale . "?ext=gettext&ext=comment";
        
        
		                                 $server_reply = $this->translator->rest_get_translation_server($rest_url);
		                                 $translation_payload = $server_reply["payload"];
        
		                                   //$debug_log .= $server_reply["server_msg"];
            
		                                 if (isset($translation_payload->textFlowTargets[0]->content)) {
		                                      $translation_title = $translation_payload->textFlowTargets[0]->content;
		                                        //error_log("SYNC TRANSLATION TITLE = " . $translation_title);
		                                 } 
        
		                                 $payload_size = count($translation_payload->textFlowTargets);
		                                 $translation_contents = '';
		                                 for ($i = 1; $i < $payload_size; $i++) {
		                                     $translation_contents .= $translation_payload->textFlowTargets[$i]->content ;
		                                 }
        
		                                   //if (isset($translation_payload->textFlowTargets[1]->content)) {
		                                   //     $translation_contents = $translation_payload->textFlowTargets[1]->content;
		                                   //     error_log("SYNC TRANSLATION CONTENTS = " . $translation_contents);
		                                   //} 
    
    
		                                 /* change the locale - to _ */
		                                 $stat_row->locale = str_replace("-", "_", $stat_row->locale);

		                                 $translation_id = $this->translator->get_translation_id($default_lang_post_id,$stat_row->locale,$payload_post_type);
                                                 if ( WP_TMY_G11N_DEBUG ) {
                                                     error_log("In tmy_g11n_get_project_status, id:".$default_lang_post_id." locale:".
                                                                                         $stat_row->locale." type: ".$payload_post_type);
                                                     error_log("In tmy_g11n_get_project_status, translation id:".$translation_id);
                                                 }
                                                 if (strcmp($payload_post_type,'blogname') === 0){
		                                     $translation_contents = $translation_title . $translation_contents;
		                                     $translation_title = "blogname";
                                                 } elseif (strcmp($payload_post_type,'blogdescription') === 0){
		                                     $translation_contents = $translation_title . $translation_contents;
		                                     $translation_title = "blogdescription";
                                                 } 

		                                 if (isset($translation_id)) {
		                                     $update_post_id = wp_update_post(array(
		                                                        'ID'    => $translation_id,
		                                                        'post_title'    => $translation_title,
		                                                        'post_content'  => $translation_contents,
		                                                        'post_type'  => "g11n_translation"));
		                                 } else {
		                                     $translation_id = wp_insert_post(array(
		                                                        'post_title'    => $translation_title,
		                                                        'post_content'  => $translation_contents,
		                                                        'post_type'  => "g11n_translation"));
		                                     add_post_meta( $translation_id, 'orig_post_id', $default_lang_post_id, true );
		                                     add_post_meta( $translation_id, 'g11n_tmy_lang', $stat_row->locale, true );
		                                 }

	                                         //tmy_g11n_pull_translation($stat_row->id, $stat_row->locale);
                                                 //finish fully translated, need to pull the translation down to local WP database

                                                 $id_link = 'post.php?post=' . $translation_id . '&action=edit';
                                                 $id_hyper = '<a href="' . admin_url($id_link) . '" target="_blank">' . $translation_id . '</a>';
                                                 $doc_lang_str .= "<td><b>" . $stat_row->locale . ": ". 
                                                   $stat_row->translated . "/" . $stat_row->total . "(ID:".$id_hyper.")</b></td> ";

                                             } else {
                                                 $doc_lang_str .= "<td>" . $stat_row->locale . ": ". 
                                                       $stat_row->translated . "/" . $stat_row->total . "</td> ";
                                             }
                                        }//foreach language
                                        $return_msg .= "<tr><td><b>" . $row->id ."</b></td>". $doc_lang_str . "</tr>";
                                    }

                                }// if (is_array($row->stats))
                            }//for each document row
                            echo "</table>";
                        }// if (is_array($payload->detailedStats))
                    } // if (! is_null($payload))
                } else {
                    $return_msg .= "Sever is not reachable: <br>";
                    $return_msg .= var_export($server_reply,true);
                }

                echo $return_msg;
		wp_die();

        }
	public function tmy_g11n_clear_plugin_data() {

		global $wpdb; 
		$whatever = intval( $_POST['whatever'] );
		if ($whatever == 8888) {

		    $no_g11n_config = $wpdb->get_results( 'delete from '.$wpdb->prefix.'options where option_name like "g11n%"');
		    $no_g11n_trans = $wpdb->get_results( 'delete from '.$wpdb->prefix.'posts where post_type="g11n_translation" or post_title="blogname" or post_title="blogdescription"');
		    $no_g11n_metas = $wpdb->get_results( 'delete from '.$wpdb->prefix.'postmeta where meta_key="g11n_tmy_lang" or meta_key="orig_post_id" or meta_key="translation_push_status"');
		    //error_log(var_export($no_g11n_config,true));
		    error_log(var_export($no_g11n_trans,true));
		    //error_log(var_export($no_g11n_metas,true));
		    echo "Cleared Data";
		    wp_die();

		}

		echo "Wrong Action";
		wp_die();

        }

	public function tmy_plugin_option_update_description($old, $new) {

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("tmy_plugin_option_update_description:" . $old. "->" . $new );
                    error_log("tmy_plugin_option_update_description blog description:" . get_bloginfo('description') );
                } 
                if (strcmp($new,"")==0){
                    if ( WP_TMY_G11N_DEBUG ) {
                        $title_post  = get_page_by_title('blogdescription',OBJECT,'post');
                        if (! is_null($title_post)) {
                            error_log("tmy_plugin_option_update_description, curren blogdescription post ID: :" . $title_post->ID );
                        }
                    } 
                }
                if (strcmp($new,"Yes")==0){
                    // creating placeholder entry as private post type
                    $title_post  = get_page_by_title('blogdescription',OBJECT,'post');

                    if (is_null($title_post)) {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogdescription',
                                                            'post_content'  => get_bloginfo('description'),
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID,
                                                            'post_title'    => 'blogdescription',
                                                            'post_content'  => get_bloginfo('description'),
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("tmy_plugin_option_update_description, blog description post ID: :" . $new_post_id );
                    }
                }


		//if (strcmp($new,"Yes")==0){
                //    $language_options = get_option('g11n_additional_lang', array());
                //    $default_lang = get_option('g11n_default_lang');
                          
                //    foreach( $language_options as $value => $code) {

                 //       if (strcmp($value, $default_lang) === 0) {
                 //           continue;
                 //       }
                 //       error_log("langs: " . $value. " - " . $code);
                 //       $translation_post_id = $this->translator->get_translation_id(0,$code,"blogdescription");
                 //       if (! isset($translation_post_id)) {
                 //           error_log("No translation, creating translation langs: " . $value. " - " . $code);
                 //           $translation_title = get_bloginfo( 'description' );
                 //           $g11n_translation_post = array(
                 //                'post_title'    => "Blog Tag Translation - " . $value,
                 //                'post_content'  => $translation_title,
                 //                'post_type'  => "g11n_translation"
                 //           );
                 //           $new_translation_id = wp_insert_post( $g11n_translation_post );
                 //           add_post_meta( $new_translation_id, 'option_name', "blogdescription", true );
                 //           add_post_meta( $new_translation_id, 'g11n_tmy_lang', $code, true );
                 //       }
                 //   }
                //}

                //error_log($option_name . "=" . get_option($option_name) . ";");
                //error_log($option_name . "=" . get_option('g11n_l10n_props_pages') . ";");
		//wp_die();

        }


        public function tmy_plugin_option_update_blogname($old, $new) {

                if ( WP_TMY_G11N_DEBUG ) {
                    error_log("tmy_plugin_option_update_blogname:" . $old. "->" . $new );
                    error_log("tmy_plugin_option_update_blogname blogname:" . get_bloginfo('name') );
                } 
                if (strcmp($new,"")==0){
                    if ( WP_TMY_G11N_DEBUG ) {
                        $title_post  = get_page_by_title('blogname',OBJECT,'post');
                        if (! is_null($title_post)) {
                            error_log("tmy_plugin_option_update_blogname, curren blogname post ID: :" . $title_post->ID );
                        }
                    } 
                }
                if (strcmp($new,"Yes")==0){
                    // creating placeholder of the blogname entry as private post type
                    $title_post  = get_page_by_title('blogname',OBJECT,'post');

                    if (is_null($title_post)) {
                        $new_post_id = wp_insert_post(array('post_title'    => 'blogname',
                                                            'post_content'  => get_bloginfo('name'),
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    } else {
                        $new_post_id = wp_insert_post(array('ID' => $title_post->ID, 
                                                            'post_title'    => 'blogname',
                                                            'post_content'  => get_bloginfo('name'),
                                                            'post_status'  => 'private',
                                                            'post_type'  => "post"));
                    }
                    if ( WP_TMY_G11N_DEBUG ) {
                        error_log("tmy_plugin_option_update_blogname, blogname post ID: :" . $new_post_id );
                    } 

                    //$language_options = get_option('g11n_additional_lang', array());
                    //$default_lang = get_option('g11n_default_lang');
                    //foreach( $language_options as $value => $code) {

                    //    if (strcmp($value, $default_lang) === 0) {
                    //        continue;
                    //    }
                        //error_log("langs: " . $value. " - " . $code);
                    //    $translation_post_id = $this->translator->get_translation_id(0,$code,"blogname");
                    //    if (! isset($translation_post_id)) {
                    //        //error_log("No translation, creating translation langs: " . $value. " - " . $code);
                    //        $translation_title = get_bloginfo( 'blogname' );
                    //        $g11n_translation_post = array(
		    //             'post_title'    => "Blog Name Translation - " . $value,
                    //             'post_content'  => $translation_title,
                    //             'post_type'  => "g11n_translation"
                    //        );
                    //        $new_translation_id = wp_insert_post( $g11n_translation_post );
                    //        add_post_meta( $new_translation_id, 'option_name', "blogname", true );
                    //        add_post_meta( $new_translation_id, 'g11n_tmy_lang', $code, true );
                    //    }
                    //}
                }

                //error_log($option_name . "=" . get_option($option_name) . ";");
                //error_log($option_name . "=" . get_option('g11n_l10n_props_pages') . ";");
                //wp_die();

        }


	function g11n_edit_posts_views( $views ) {
    		foreach ( $views as $index => $view ) {
        		//$views[ $index ] = preg_replace( '/ <span class="count">\([0-9]+\)<\/span>/', '', $view );
        		//$views[ $index ] = "AAAAA";
		        error_log("edit_posts_views = " . $views[ $index ]);
    		}
        	array_push($views, "Sent for Translation (XX)");
        	array_push($views, "Translation Completed (XX)");

    	return $views;
}

}
