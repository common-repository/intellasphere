<?php

GFForms::include_feed_addon_framework();

if (!class_exists("Itsp_Gfisaddon")) {
    
  /**
     * Class Itsp_Gfisaddon
     *
     * Creates Addon Gravity form Integratin.
     *
     * 
     * @category Class
     */
    
class Itsp_Gfisaddon extends GFFeedAddOn {

    protected $_version = 1;
    protected $_min_gravityforms_version = '1.9.16';
    protected $_slug = 'intellasphere';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms Intellasphere Add-On';
    protected $_short_title = 'Intellasphere';
    private static $_instance = null;

    /**
     * Get an instance of this class.
     *
     * @return GFSimpleFeedAddOn
     */
    public static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new Itsp_Gfisaddon();
        }

        return self::$_instance;
    }

    // # FEED PROCESSING -----------------------------------------------------------------------------------------------

    /**
     * Process the feed e.g. subscribe the user to a list.
     *
     * @param array $feed The feed object to be processed.
     * @param array $entry The entry object currently being processed.
     * @param array $form The form object currently being processed.
     *
     * @return bool|void
     */
    public function process_feed($feed, $entry, $form) {
        $feedName = $feed['meta']['feedName'];
        $createTask = $feed['meta']['createTask'];
        $field_map = $this->get_field_map_fields($feed, $createTask);
        $merge_vars = array();
        foreach ($field_map as $name => $field_id) {
            $merge_vars[$createTask][$name] = $this->get_field_value($form, $entry, $field_id);
        }
        $lead_id = Itsp_Utility::is_store_lead($merge_vars);
    }

    /**
     * Configures the settings which should be rendered on the feed edit page in the Form Settings > Simple Feed Add-On area.
     *
     * @return array
     */
    public function feed_settings_fields() {
        $fields_settings = Itsp_Utility::gravity_form_mapping();
        $fields_value = array();
        foreach ($fields_settings as $key => $get_fields) {
            if ($key == 'fields') {
                foreach ($get_fields as $key => $fields) {
                    $fields_value[] = array(
                                'name' => $key,
                                'label' => esc_html__('Map Fields', 'intellasphere'),
                                'type' => 'field_map',
                                'field_map' => $fields,
                                'dependency' => array('field' => 'createTask', 'values' => $key),
                    );
                }
            }
        }

        return array(
            array(
                'title' => esc_html__('Intellasphere Settings', 'intellasphere'),
                'fields' => array(
                    array(
                        'label' => esc_html__('Feed name', 'intellasphere'),
                        'type' => 'text',
                        'name' => 'feedName',
                        'class' => 'small',
                    ),
                    array(
                        'name' => 'createTask',
                        'label' => esc_html__('Create Task', 'intellasphere'),
                        'type' => 'select',
                        'onchange' => "jQuery(this).parents('form').submit();",
                        'choices' => $fields_settings['option']
                    )
                ),
            ),
            array(
                'fields' => $fields_value,
            )
        );
    }

    /**
     * Configures which columns should be displayed on the feed list page.
     *
     * @return array
     */
    public function feed_list_columns() {
        return array(
            'feedName' => esc_html__('Name', 'intellasphere'),
        );
    }

}
}