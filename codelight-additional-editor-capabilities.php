<?php
/*
 Plugin Name: Codelight Additional Editor Capabilities
 Plugin URI: http://codelight.eu
 Description: Allow Editor roles to add, edit or remove non-admin users; grant access to Appearance menu. Based on <a href="http://wordpress.stackexchange.com/a/4500">this stackoverflow answer</a> by John P Bloch.
 Author: Codelight.eu
 Version: 1.0
 Author URI: http://codelight.eu
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

class Codelight_Additional_Editor_Capabilities {

    // These capabilities will be given to the Editor role
    public $caps = array(
        'list_users',
        'add_users',
        'create_users',
        'edit_users',
        'promote_users',
        'remove_users',
        'delete_users', // --> this breaks is_super_admin() calls, making the Editor role a super admin
        'edit_theme_options'
    );

    function __construct() {

        add_filter( 'editable_roles', array($this, 'editable_roles') );
        add_filter( 'map_meta_cap', array($this, 'map_meta_cap'), 10, 4 );

        register_activation_hook( __FILE__, array($this, 'add_editor_capabilities') );
        register_deactivation_hook( __FILE__, array($this, 'remove_editor_capabilities') );
        
    }

    /*
     * On plugin activation, add user-related capabilities to Editor role.
     */
    function add_editor_capabilities() {
        $editor = get_role('editor');
        foreach ($this->caps as $cap) {
            $editor->add_cap($cap);
        }
    }

    /*
     * On plugin deactivation, remove all previously added capabilities.
     */
    function remove_editor_capabilities() {
        $editor = get_role('editor');
        foreach ($this->caps as $cap) {
            $editor->remove_cap($cap);
        }
    }

    /*
     * If the current user is not an admin, remove 'Administrator' from the list of editable roles.
     */
    function editable_roles( $roles ){
        if ( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
            unset( $roles['administrator']);
        }
        return $roles;
    }

    /*
    * If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it.
    */ 
    function map_meta_cap( $caps, $cap, $user_id, $args ) {

        switch ($cap) {

            case 'edit_user':
            case 'remove_user':
            case 'promote_user':

                if ( isset($args[0]) && $args[0] == $user_id ) {
                    break;
                } elseif( !isset($args[0]) ) {
                    $caps[] = 'do_not_allow';
                }
                
                $other = new WP_User( absint($args[0]) );
                if ( $other->has_cap( 'administrator' ) ) {
                    if ( !current_user_can('administrator') ) {
                        $caps[] = 'do_not_allow';
                    }
                }

                break;

            case 'delete_user':
            case 'delete_users':

                if ( !isset($args[0]) ) {
                    break;
                }

                $other = new WP_User( absint($args[0]) );
                if ( $other->has_cap( 'administrator' ) ) {
                    if ( !current_user_can('administrator') ) {
                        $caps[] = 'do_not_allow';
                    }
                }
                
                break;

            default:
                break;
        }

        return $caps;
    }

}

$cl_additional_editor_capabilities = new Codelight_Additional_Editor_Capabilities();

