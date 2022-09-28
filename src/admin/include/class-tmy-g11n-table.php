<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'TMY_G11N_Table' ) ) :
    class TMY_G11N_Table extends WP_List_Table {


public function __construct() {
 
    global $status, $page;
 
        parent::__construct(
            array(
                'singular'  => 'movie',
                'plural'    => 'movies',
                'ajax'      => true
                ));
        }

        public function get_columns() {
            return array(
                'cb'  => '<input type="checkbox" />',
                'term_id'      => 'Term ID',
                'name'   => 'Name',
                'slug'   => 'Slug',
                'taxonomy'   => 'Taxonomy',
                'translations'   => 'Translations',
            );
        }

        function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="term_id[]" value="%s" />', esc_attr($item[0])
                           );    
        }

        public function prepare_items() {

            $this->process_bulk_action();

            $qualified_taxonomies = get_taxonomies(array("public" => true, "show_ui"=> true), "names", "or");
            unset($qualified_taxonomies['translation_priority']);
            global $wpdb;
            $sql = "select {$wpdb->prefix}terms.term_id, name, slug, taxonomy
                from {$wpdb->prefix}terms,{$wpdb->prefix}term_taxonomy
                where {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id";
            $rows = $wpdb->get_results( $sql, "ARRAY_N" );

            $qualified_rows = array();
            foreach ($rows as $row) {
                if (array_key_exists($row[3], $qualified_taxonomies)) {
                   $sql = "select id_meta.post_id,
                                  lang_meta.meta_value
                             from {$wpdb->prefix}postmeta as id_meta,
                                  {$wpdb->prefix}postmeta as lang_meta,
                                  {$wpdb->prefix}postmeta as type_meta
                            where id_meta.meta_key=\"orig_post_id\" and
                                  id_meta.meta_value={$row[0]} and
                                  type_meta.meta_key=\"g11n_tmy_orig_type\" and
                                  type_meta.meta_value=\"{$row[3]}\" and
                                  lang_meta.meta_key=\"g11n_tmy_lang\" and
                                  lang_meta.post_id=type_meta.post_id and
                                  lang_meta.post_id=id_meta.post_id";
                   $lang_rows = $wpdb->get_results( $sql, "ARRAY_N" );
                   $lang_info = "";

                   foreach ($lang_rows as $lang_row) {
                       //$lang_info .= "{esc_attr($lang_row[1])}({esc_attr($lang_row[0]})) ";
                       $lang_info .= esc_attr($lang_row[1]) . "(<a href=\"" .
                             esc_url( get_edit_post_link($lang_row[0]) ) . "\">" .
                             esc_attr($lang_row[0]) . "</a>) ";
                   }
                   $row[] = $lang_info;
                   $row[] =  "<a href=\"" . esc_url(get_edit_term_link($row[0])) . "\">" .  esc_attr($row[0]) . "</a>";
                   $qualified_rows[] = $row;

                   //echo "<br>" . json_encode($lang_rows) . "<br>";
                   //echo "<br>" . $lang_info . "<br>";
                }
            }
            usort( $qualified_rows, array( &$this, 'usort_reorder' ) );
            $this->items = $qualified_rows;

            $per_page = 10;
            $current_page = $this->get_pagenum();
            $total_items = count($this->items);

            $found_data = array_slice($this->items,(($current_page-1)*$per_page),$per_page);

            $this->set_pagination_args( array(
              'total_items' => $total_items,                  //WE have to calculate the total number of items
              'per_page'    => $per_page                     //WE have to determine how many items to show on a page
            ) );
            $this->items = $found_data;



            $columns  = $this->get_columns();
            $hidden   = array();
            //$sortable = array();
            $sortable = array('taxonomy' => array('taxonomy', true));
            $primary  = 'name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
            //$this->display();


        }


      // Sorting function
      function usort_reorder($a, $b)
      {
            // If no sort, default to user_login
            $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'taxonomy';
            // If no order, default to asc
            $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
            // Determine sort order
            $result = strcmp($a[$orderby], $b[$orderby]);
            // Send final sort direction to usort
            return ($order === 'asc') ? $result : -$result;
      }

        function get_bulk_actions() {
          $actions = array(
            'start_translation_from_taxonomies_form'    => 'Start or Sync Translation',
            'remove_translation_from_taxonomies_form'    => 'Remove Translation'
          );
          return $actions;
        }

        protected function column_default( $item, $column_name ) {
            switch ( $column_name ) {
                case 'term_id':
                    return  $item[5];
                case 'name':
                    return esc_html( $item[1] );
                case 'slug':
                    return esc_html( $item[2] );
                case 'taxonomy':
                    return esc_html( $item[3] );
                case 'translations':
                    return $item[4] ;
                return 'Unknown';
            }
        }

        /**
         * Generates custom table navigation to prevent conflicting nonces.
         * 
         * @param string $which The location of the bulk actions: 'top' or 'bottom'.
         */
        protected function display_tablenav( $which ) {
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>">

                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                
                ?>

                <br class="clear" />
            </div>
            <?php
        }
        public function single_row( $item ) {
            echo '<tr>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }

    }
endif;

if ( ! class_exists( 'TMY_G11N_Server_Table' ) ) :
    class TMY_G11N_Server_Table extends WP_List_Table {

        //protected $translator;
        //protected $_update_g11n_translation_status;

        protected $data_items;

        public function __construct() {
 
            global $status, $page;
 
            parent::__construct(
                array(
                    'singular'  => 'movie',
                    'plural'    => 'movies',
                    'ajax'      => true
                    ));

            //$this->translator = $translator_func;
            //$this->_update_g11n_translation_status = $trans_update_func;
        }

        public function get_columns() {

            $column_names = array();
            $column_names['doc_name'] = 'Server Document Name';
            $column_names['doc_name_local'] = 'Local Document';
            $default_language = get_option('g11n_default_lang');
            $language_options = get_option('g11n_additional_lang', array());
            unset($language_options[$default_language]);
            foreach ($language_options as $value => $code) {
               $column_names[$code] = $value;
            }
       
            return $column_names;
        }

        function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="term_id[]" value="%s" />', esc_attr($item[0])
                           );    
        }

        public function take_tmy_data( $in ) {

            $this->data_items = $in;
        }
        public function prepare_items( ) {

            $this->process_bulk_action();

             
            $per_page = 10;
            $current_page = $this->get_pagenum();
            $total_items = count($this->data_items);

            $found_data = array_slice($this->data_items,(($current_page-1)*$per_page),$per_page);

            $this->set_pagination_args( array(
              'total_items' => $total_items,                  //WE have to calculate the total number of items
              'per_page'    => $per_page                     //WE have to determine how many items to show on a page
            ) );
            $this->items = $found_data;

            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = array();
            $primary  = 'name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
            //$this->display();


        }
        /*****
        function get_bulk_actions() {
          $actions = array(
            'refresh_translation_server'    => 'Refresh Translation Server'
          );
          return $actions;
        }
        *****/
        function ajax_response() {

            error_log("In AJAX RESPONSE");
            $this->prepare_items();
            $this->display();


        }

        protected function column_default( $item, $column_name ) {

            if (isset($item[$column_name])) {
                return $item[$column_name];
            } else {
                return '';
            }

        }

        /**
         * Generates custom table navigation to prevent conflicting nonces.
         * 
         * @param string $which The location of the bulk actions: 'top' or 'bottom'.
         */
        protected function display_tablenav( $which ) {
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>">

                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                
                ?>

                <br class="clear" />
            </div>
            <?php
        }
        public function single_row( $item ) {
            echo '<tr>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }

    }
endif;
