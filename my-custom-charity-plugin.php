<?php
/**
 * Plugin Name: My Custom Charity
 * Plugin URI: https://github.com/meysamWeb/charity-plugin
 * Description: Allows users to add a charity amount to their cart and donate to a cause of their choice.
 * Version: 2.0.0
 * Author: meysamWeb
 * Author URI: https://github.com/meysamWeb
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')) {
    die('Invalid request.');
}

define('MCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MCP_PLUGIN_ASSETS', MCP_PLUGIN_URL . 'assets/');
define('MCP_PLUGIN_INC', MCP_PLUGIN_DIR . 'includes/');


# add scripts
function add_charity_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('charity-script', MCP_PLUGIN_ASSETS . 'js/charity.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'add_charity_scripts');

# add css
wp_enqueue_style('my-charity-plugin-style', MCP_PLUGIN_ASSETS . 'css/style.css');


# include admin menu plugin
require_once MCP_PLUGIN_INC . 'admin/add-admin-menu.php';


# Handle the AJAX request
function handle_charity_ajax_request()
{
    check_ajax_referer('update-order-review', 'security');

    if (isset($_POST['charity_amount'])) {
        $charity_amount = floatval($_POST['charity_amount']);

        WC()->session->set('charity_amount', $charity_amount);
        WC()->cart->calculate_totals();

        wp_send_json_success();
    } else {
        wp_send_json_error('Charity amount missing');
    }
}

add_action('wp_ajax_update_charity_in_cart', 'handle_charity_ajax_request');
add_action('wp_ajax_nopriv_update_charity_in_cart', 'handle_charity_ajax_request');

# Adjust cart totals based on charity amount
function add_charity_to_cart($cart_object)
{
    if (!WC()->session) return;

    $charity_amount = WC()->session->get('charity_amount');
    if ($charity_amount > 0) {
        WC()->cart->add_fee(__('Charity Amount', 'woocommerce'), $charity_amount, true, '');
    }
}

add_action('woocommerce_before_calculate_totals', 'add_charity_to_cart');


# include Display charity amount input field
require_once MCP_PLUGIN_INC . 'front/display-charity-amount-field.php';

# include show charity amount in order details
require_once MCP_PLUGIN_INC . 'admin/show-charity-amount-in-order-details.php';
