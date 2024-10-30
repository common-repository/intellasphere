<?php

/**
 * IntellaSphere This files is to Include the Table of banner
 *
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */


/**
 * Including List table if Wp List table is Not Exist to Display The banner Table
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


if (!class_exists('Itsp_Banners_List')) {

    class Itsp_Banners_List extends WP_List_Table {

        /** Class constructor */
        public function __construct() {

            parent::__construct([
                'singular' => __('Customer', 'intellasphere'), //singular name of the listed records
                'plural' => __('Customers', 'intellasphere'), //plural name of the listed records
                'ajax' => false //does this table support ajax?
            ]);
        }

        /**
         * Retrieve customers data from the database
         *
         * @param int $per_page
         * @param int $page_number
         *
         * @return mixed
         */
        public static function get_customers($per_page = 20, $page_number = 1) {
              global $wpdb;
              $offset = ( $page_number - 1 ) * $per_page;
              $result =  $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}banner LIMIT %d OFFSET %d",
				$per_page,
				$offset
			),'ARRAY_A'
		);
            return $result;
        }

        /**
         * Delete a customer record.
         *
         * @param int $id customer ID
         */
        public static function delete_customer($id) {
            global $wpdb;
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}banner WHERE ID = %d", $id ) );     
        }

        /**
         * Returns the count of records in the database.
         *
         * @return null|string
         */
        public static function record_count() {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}banner";
            return $wpdb->get_var($sql);
        }

        /** Text displayed when no customer data is available */
        public function no_items() {
            _e('No Report  avaliable.', 'intellasphere');
        }

        /**
         * Render a column when no column specific method exist.
         *
         * @param array $item
         * @param string $column_name
         *
         * @return mixed
         */
        public function column_default($item, $column_name) {
            switch ($column_name) {
                case 'id':
                    return $item[$column_name];
                case 'pages':
                    return '<a href="' . site_url("/wp-admin/post.php?post=$item[$column_name]&action=edit") . '">' . $item[$column_name] . '</a>';
                case 'banner':
                    return $item[$column_name];
            }
        }

        /**
         * Render the bulk edit checkbox
         *
         * @param array $item
         *
         * @return string
         */
        function column_cb($item) {
            return sprintf(
                    '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
            );
        }

        /**
         * Method for name column
         *
         * @param array $item an array of DB data
         *
         * @return string
         */
        function column_name($item) {
            $delete_nonce = wp_create_nonce('itsp_delete_customer');

            $title = '<strong>' . $item['user'] . '</strong>';

            $actions = [
                'delete' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['id']), $delete_nonce)
            ];

            return $title . $this->row_actions($actions);
        }

        /**
         *  Associative array of columns
         *
         * @return array
         */
        function get_columns() {
            $columns = [
                'cb' => '<input type="checkbox" />',
                'id' => __('id', 'intellasphere'),
                'pages' => __('pages', 'intellasphere'),
                'banner' => __('banner', 'intellasphere')
            ];

            return $columns;
        }

        /**
         * Columns to make sortable.
         *
         * @return array
         */
        public function get_sortable_columns() {
            $sortable_columns = array(
                'Postid' => array('Postid', true),
                'user' => array('user', false)
            );

            return $sortable_columns;
        }

        /**
         * Returns an associative array containing the bulk action
         *
         * @return array
         */
        public function get_bulk_actions() {
            $actions = [
                'bulk-delete' => 'Delete'
            ];

            return $actions;
        }

        /**
         * Handles data query and filter, sorting, and pagination.
         */
        public function prepare_items() {
            $this->_column_headers = [
                $this->get_columns(),
                [], // hidden columns
                $this->get_sortable_columns(),
                $this->get_primary_column_name(),
            ];

            /** Process bulk action */
            $this->process_bulk_action();

            $per_page = $this->get_items_per_page('customers_per_page', 20);
            $current_page = $this->get_pagenum();
            $total_items = self::record_count();

            $this->set_pagination_args([
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page //WE have to determine how many items to show on a page
            ]);

            $this->items = self::get_customers($per_page, $current_page);
        }

        public function process_bulk_action() {
            //Detect when a bulk action is being triggered...
            if ('delete' === $this->current_action()) {

                // In our file that handles the request, verify the nonce.
                $nonce = esc_attr($_REQUEST['_wpnonce']);
                if (!wp_verify_nonce($nonce, 'itsp_delete_customer')) {
                    die('Go get a life script kiddies');
                } else {
                    ?>
                    <script>window.location = "<?php echo esc_url_raw(add_query_arg([])); ?>";</script>

                    <?Php
                }
            }

            // If the delete bulk action is triggered
            if (( isset($_POST['action']) && $_POST['action'] == 'bulk-delete' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete' )
            ) {

                $delete_ids = itsp_recursive_sanitize_text_field($_POST['bulk-delete']);

                // loop over the array of record IDs and delete them
                foreach ($delete_ids as $id) {
                    self::delete_customer($id);
                }
                ?>

                <script>window.location = "<?php echo esc_url_raw(add_query_arg([])); ?>";</script>

                <?Php
            }
        }

    }

}