<?php
/*
Plugin Name: Modal Login
Description: Modal Login
*/
session_start();
ob_start();

load_plugin_textdomain('gym', false, dirname(plugin_basename(__FILE__)) .'/lang/'); // TODO: add language files

function gym_modal_options()
{
    add_option('option_bs3patch', '0', '', 'yes');
    add_option('option_checkbox', '0', '', 'yes');
    add_option('option_usermodal', '0', '', 'yes');
    add_option('can_register_option', '0', '', 'yes');
    add_option('button_text', __('Login', 'gym'), '', 'yes');
    add_option('button_text2', __('Profile', 'gym'), '', 'yes');
    add_option('default_buttons', '0', '', 'yes');
    add_option('default_sizes', '0', '', 'yes');
}
register_activation_hook(__FILE__, 'gym_modal_options');

function gym_modal_unset_options()
{
    delete_option('option_bs3patch');
    delete_option('option_checkbox');
    delete_option('option_usermodal');
    delete_option('can_register_option');
    delete_option('button_text');
    delete_option('button_text2');
    delete_option('default_buttons');
    delete_option('default_sizes');
}
register_uninstall_hook(__FILE__, 'gym_modal_unset_options');

//admin setting
if (is_admin()):
include(plugin_dir_path(__FILE__) . 'inc/settings.php');
endif;

//functions
include(plugin_dir_path(__FILE__) . 'inc/functions.php');

//scripts
include(plugin_dir_path(__FILE__) . 'inc/scripts.php');

//shortcode
include(plugin_dir_path(__FILE__) . 'inc/shortcode.php');

//ajax authenticate
include(plugin_dir_path(__FILE__) . 'inc/authenticate.php');

//Modal box
include(plugin_dir_path(__FILE__) . 'inc/modal-box.php');

//widget
include(plugin_dir_path(__FILE__) . 'inc/widget.php');
