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
                '<input type="checkbox" name="term_id[]" value="%s" />', esc_attr($item["term_id"])
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
            $rows = $wpdb->get_results( $sql, "ARRAY_A" );

error_log("SORT BY" . json_encode($_GET));
            $qualified_rows = array();
            foreach ($rows as $row) {
                if (array_key_exists($row["taxonomy"], $qualified_taxonomies)) {
                   $sql = "select id_meta.post_id,
                                  lang_meta.meta_value
                             from {$wpdb->prefix}postmeta as id_meta,
                                  {$wpdb->prefix}postmeta as lang_meta,
                                  {$wpdb->prefix}postmeta as type_meta
                            where id_meta.meta_key=\"orig_post_id\" and
                                  id_meta.meta_value={$row["term_id"]} and
                                  type_meta.meta_key=\"g11n_tmy_orig_type\" and
                                  type_meta.meta_value=\"{$row["taxonomy"]}\" and
                                  lang_meta.meta_key=\"g11n_tmy_lang\" and
                                  lang_meta.post_id=type_meta.post_id and
                                  lang_meta.post_id=id_meta.post_id";
                   $lang_rows = $wpdb->get_results( $sql, "ARRAY_A" );
                   $lang_info = "";

                   foreach ($lang_rows as $lang_row) {
                       $lang_info .= esc_attr($lang_row["meta_value"]) . "(<a href=\"" .
                             esc_url( get_edit_post_link($lang_row["post_id"]) ) . "\">" .
                             esc_attr($lang_row["post_id"]) . "</a>) ";
                   }
                   //$row[] = $lang_info;
                   $id_link =  "<a href=\"" . esc_url(get_edit_term_link($row["term_id"])) . "\">" .  esc_attr($row["term_id"]) . "</a>";
                   //$qualified_rows[] = $row;
                   $qualified_rows[] = array( "term_id"=>$row["term_id"],
                                              "name"=>$row["name"],
                                              "id_link"=>$id_link,
                                              "slug"=>$row["slug"],
                                              "taxonomy"=>$row["taxonomy"],
                                              "translations"=>$lang_info
                                            );

                   //echo "<br>" . json_encode($lang_rows) . "<br>";
                   //echo "<br>" . $lang_info . "<br>";
                }
            }
            $sortable = array('taxonomy' => array('taxonomy', false),
                              'term_id' => array('term_id', true));
            usort( $qualified_rows, array( &$this, 'usort_reorder' ) );
            $this->items = $qualified_rows;

            $per_page = 20;
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
            $primary  = 'name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
            //$this->display();


        }


      // Sorting function
      function usort_reorder($a, $b)
      {
            // If no sort, set default
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
                    return  $item["id_link"];
                case 'name':
                    return esc_html( $item["name"] );
                case 'slug':
                    return esc_html( $item["slug"] );
                case 'taxonomy':
                    return esc_html( $item["taxonomy"] );
                case 'translations':
                    return $item["translations"] ;
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
