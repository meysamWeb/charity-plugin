<?php
/*
Plugin Name: My Charity Plugin
Plugin URI: https://github.com/meysamWeb/charity-plugin
Description: Allows users to add a charity amount to their cart and donate to a cause of their choice.
Version: 1.0.0
Author: meysamWeb
Author URI: https://github.com/meysamWeb
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: my-charity-plugin
*/


if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

// Add scripts
function add_charity_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'charity-script', plugin_dir_url( __FILE__ ) . '/assets/js/charity.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'add_charity_scripts' );

// Add css
wp_enqueue_style('my-charity-plugin-style', plugins_url('/assets/css/style.css', __FILE__));


// Add charity amount to cart
function add_charity_to_cart() {
    // Check if the charity checkbox is checked
    if (isset($_POST['charity-checkbox']) && $_POST['charity-checkbox'] === 'yes') {
        // Get the charity amount from the POST data
        $charity_amount = floatval($_POST['charity-amount']);

        // Add the charity amount to the cart
        WC()->cart->add_fee( __('Charity Amount', 'woocommerce'), $charity_amount, true, '' );
    }
}
add_action( 'woocommerce_checkout_update_order_review', 'add_charity_to_cart' );

// Display charity amount input field
function display_charity_amount_field() {
    ?>
    <div class="parent">
        <label>
          <input type="checkbox" class="parent-checkbox">
          Want to donate a charity?
        </label>
        <div class="child-list">
          <div class="child">
            <label>
              <input type="checkbox" class="child-checkbox">
              Helping children who are homeless
            </label>
            <div class="child-input">
              <label>
              <span class="child-input-amount">Amount</span>
                <input type="number" class="child-value" value="0">
              </label>
            </div>
          </div>
          <div class="child">
            <label>
              <input type="checkbox" class="child-checkbox">
              Contribute to the cost of treating children with
            </label>
            <div class="child-input">
              <label>
              <span class="child-input-amount">Amount</span>
                <input type="number" class="child-value" value="0">
              </label>
            </div>
          </div>
          <div class="child">
            <label>
              <input type="checkbox" class="child-checkbox">
              Help build schools in disadvantaged areas
            </label>
            <div class="child-input">
              <label>
              <span class="child-input-amount">Amount</span>
                <input type="number" class="child-value" value="0">
              </label>
            </div>
          </div>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_review_order_before_payment', 'display_charity_amount_field',50, 0 );



// Add charity information to the WooCommerce order details page
add_action( 'woocommerce_checkout_update_order_meta', 'add_charity_donation_to_order_meta', 10, 1 );

function add_charity_donation_to_order_meta( $order_id ) {
    $charity_total = 0;
    $donation_details = array();
    $donation_options = array(
        'helping_children_homeless' => 'Helping children who are homeless',
        'treating_children_cost' => 'Contribute to the cost of treating children',
        'building_schools_disadvantaged_areas' => 'Help build schools in disadvantaged areas'
    );

    foreach( $donation_options as $key => $value ) {
        if( isset( $_POST[$key] ) && $_POST[$key] == 'yes' && isset( $_POST[$key . '_amount'] ) ) {
            $amount = (float) sanitize_text_field( str_replace( ',', '.', $_POST[$key . '_amount'] ) );
            if( $amount > 0 ) {
                $donation_details[$value] = wc_price( $amount );
                $charity_total += $amount;
            }
        }
    }

    if( $charity_total > 0 ) {
        add_post_meta( $order_id, '_charity_donation', $charity_total, true );
        add_post_meta( $order_id, '_charity_donation_details', $donation_details, true );
    }
}

add_action( 'woocommerce_email_after_order_table', 'display_charity_donation_in_email', 10, 4 );

function display_charity_donation_in_email( $order, $sent_to_admin, $plain_text, $email ) {
    $charity_total = get_post_meta( $order->get_id(), '_charity_donation', true );
    $donation_details = get_post_meta( $order->get_id(), '_charity_donation_details', true );

    if( $charity_total > 0 && $sent_to_admin ) {
        echo '<h2>Charity Donation</h2>';
        foreach( $donation_details as $key => $value ) {
            echo '<p>' . $key . ': ' . $value . '</p>';
        }
        echo '<p><strong>Total: ' . wc_price( $charity_total ) . '</strong></p>';
    }
}

add_action( 'woocommerce_order_details_after_order_table', 'display_charity_donation_in_order_details', 10, 1 );

function display_charity_donation_in_order_details( $order ) {
    $charity_total = get_post_meta( $order->get_id(), '_charity_donation', true );
    $donation_details = get_post_meta( $order->get_id(), '_charity_donation_details', true );

    if( $charity_total > 0 ) {
        echo '<h2>Charity Donation</h2>';
        foreach( $donation_details as $key => $value ) {
            echo '<p>' . $key . ': ' . $value . '</p>';
        }
        echo '<p><strong>Total: ' . wc_price( $charity_total ) . '</strong></p>';
    }
}
