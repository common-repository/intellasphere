<?php

if (!defined('ABSPATH'))
    exit;

/**
 * Class Itsp_Leadaction
 */
if (!class_exists("Itsp_Leadaction")) {

    final class Itsp_Leadaction extends NF_Abstracts_Action {

        /**
         * @var string
         */
        protected $_name = 'is_lead';

        /**
         * @var string
         */
        protected $_timing = 'late';

        /**
         * @var int
         */
        protected $_priority = 0;

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct();
            $this->_nicename = __('Intellasphere', 'intellasphere');
            $fields_settings = Itsp_Utility::ninja_form_mapping();
            if (isset($fields_settings['option']) && $fields_settings['option'] != '') {
                $this->_settings['is_actions'] = array(
                    'name' => 'is_actions',
                    'type' => 'fieldset',
                    'width' => 'full',
                    'label' => __('Intellashpere Actions', 'intellasphere'),
                    'group' => 'primary',
                    'settings' => array(
                        array(
                            'name' => 'is_select_form',
                            'type' => 'select',
                            'label' => __('Send Lead This Form', 'intellasphere'),
                            'width' => 'full',
                            'group' => 'primary',
                            'options' => $fields_settings['option'],
                            'use_merge_tags' => false,
                            'help' => __('This feature requires the user set up automated Lead in the Intellashpere', 'intellasphere')
                        )
                    )
                );
            }

            if (isset($fields_settings['fields']) && $fields_settings['fields'] != '') {
                $this->_settings['field_mapping'] = array(
                    'name' => 'field_mapping',
                    'type' => 'fieldset',
                    'width' => 'full',
                    'label' => __('Field Mapping', 'intellasphere'),
                    'group' => 'primary',
                    'settings' => $fields_settings['fields']
                );
            }
        }

        /**
         * @param array $action_settings
         * @param int|string $form_id
         * @param array $data
         * @return array $data
         */
        public function process($action_settings, $form_id, $data) {
            $lead_id = $action_settings['is_select_form'];
            $lead_list = array();
            if (isset($lead_id) && !empty($lead_id)) {
                foreach ($action_settings as $key => $value) {
                    if (strstr($key, 'is_lead_list_')) {
                        $lead_list[$lead_id][str_replace('is_lead_list_', '', $key)] = $value;
                    }
                }
                $lead_id = Itsp_Utility::is_store_lead($lead_list);
            }

            return $data;
        }

    }

}