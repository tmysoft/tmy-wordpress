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
            $this->items = $qualified_rows;

            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = array();
            $primary  = 'name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
            //$this->display();


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
