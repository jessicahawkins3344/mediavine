<?php
/**
 * WordPress settings API demo class
 *
 */
if ( !class_exists('Metabox_Settings_API_ACP' ) ):
    class Metabox_Settings_API_ACP {
        private $settings_api;
        function __construct() {
            $this->settings_api = new Metabox_Settings_API;
            add_action( 'admin_init', array($this, 'admin_init') );
            add_action( 'admin_menu', array($this, 'admin_menu') );
        }
        function admin_init() {
            //set the settings
            $this->settings_api->set_sections( $this->get_settings_sections() );
            $this->settings_api->set_fields( $this->get_settings_fields() );
            //initialize settings
            $this->settings_api->admin_init();
        }


        function admin_menu() {

            //create new top-level menu
            add_menu_page('Metabox Settings', 'Metabox Settings', 'administrator', 'metabox_options' , array($this, 'mv_plugin_page'), 'dashicons-admin-links' );

        }

        function get_settings_sections() {
            $sections = array(
                array(
                    'id'    => 'metabox_defaults',
                    'title' => __( 'Metabox Defaults', 'mv' )
                )
            );
            return $sections;
    }
    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'metabox_defaults' => array(
                array(
                    'name'        => 'html',
                    'desc'        => __( 'Styles for DIY Metabox Section', 'mv' ),
                    'type'        => 'html'
                ),
                array(
                    'name'              => 'item-size',
                    'label'             => __( 'DIY Font Size', 'mv' ),
                    'desc'              => __( 'DIY Font Size', 'mv' ),
                    'placeholder'       => __( 'DIY Size in px', 'mv' ),
                    'type'              => 'text',
                    'default'           => '16'
                ),
                array(
                    'name'    => 'color-title',
                    'class'   => 'diy-item',
                    'label'   => __( 'DIY Background Color', 'mv' ),
                    'desc'    => __( 'Pick the color of the DIY Metabox', 'mv' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'value-color',
                    'label'   => __( 'DIY Text Color', 'mv' ),
                    'desc'    => __( 'Pick the color of the DIY text', 'mv' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'              => 'custom-class',
                    'label'             => __( 'DIY Custom Class', 'mv' ),
                    'desc'              => __( 'Add your own class to the .diy container', 'mv' ),
                    'placeholder'       => __( 'example-class', 'mv' ),
                    'type'              => 'text',
                    'default'           => ''
                ),
                array(
                    'name'              => 'custom-css',
                    'label'             => __( 'DIY Custom CSS', 'mv' ),
                    'desc'              => __( 'Add Custom CSS here', 'mv' ),
                    'placeholder'       => __( 'custom css here', 'mv' ),
                    'type'              => 'textarea',
                    'default'           => ''
                ),
            ),
        );
        return $settings_fields;
    }
    function mv_plugin_page() {
        echo '<div class="wrap-mv">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }
}
endif;
 