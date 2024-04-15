<?php
/**
 * Plugin Name: Simple Maintenance Mode
 * Description: Put your site under maintenance mode easily.
 * Version: 1.0
 * Author: John Diginee
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Function to display maintenance mode message
function simple_maintenance_mode() {
    $message = '<h1>Under Maintenance</h1>';
    $message .= '<p>Sorry, we are currently performing maintenance. Please check back soon.</p>';
    echo $message;
    exit;
}

// Function to check if maintenance mode is enabled
function is_maintenance_mode_enabled() {
    return get_option('simple_maintenance_mode_enabled', false);
}

// Hook into WordPress initialization to check maintenance mode
function check_maintenance_mode() {
    if (is_maintenance_mode_enabled() && !current_user_can('manage_options')) {
        simple_maintenance_mode();
    }
}
add_action('init', 'check_maintenance_mode');

// Function to add settings page for maintenance mode
function simple_maintenance_mode_settings_page() {
    add_options_page('Maintenance Mode Settings', 'Maintenance Mode', 'manage_options', 'simple-maintenance-mode', 'simple_maintenance_mode_settings_page_content');
}
add_action('admin_menu', 'simple_maintenance_mode_settings_page');

// Function to render settings page content
function simple_maintenance_mode_settings_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['maintenance_mode_enabled'])) {
        update_option('simple_maintenance_mode_enabled', true);
    } else {
        update_option('simple_maintenance_mode_enabled', false);
    }
    
    $maintenance_mode_enabled = is_maintenance_mode_enabled();
    ?>
    <div class="wrap">
        <h1>Maintenance Mode Settings</h1>
        <form method="post" action="">
            <label for="maintenance_mode_enabled">
                <input type="checkbox" id="maintenance_mode_enabled" name="maintenance_mode_enabled" <?php checked($maintenance_mode_enabled, true); ?>>
                Enable Maintenance Mode
            </label>
            <p>Check this box to enable maintenance mode. Only administrators will be able to access the site.</p>
            <input type="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>
    <?php
}
