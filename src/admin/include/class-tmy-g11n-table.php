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
                '<input type="checkbox" name="term_id[]" value="%s" />', $item[0]
                           );    
        }

        public function prepare_items() {
            $this->process_bulk_action();
            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = array();
            $primary  = 'name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );

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
