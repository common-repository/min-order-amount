<?php
/**
 * Plugin Name: Min Order Amount For WooCommerce
 * Plugin URI: https://wordpress.org/plugins/min-order-amount
 * Text Domain: min-order-amount
 * Description: Set a minimum order amount for checkout.
 * Domain Path: /languages/
 * Version: 1.1
 * Author: Rajdip Sinha Roy
 * Author URI: https://rajdip.tech
 * Developer: Rajdip Sinha Roy
 * Developer URI: https://rajdip.tech
 * WC requires at least: 3.0.0
 * WC tested up to: 4.2.2
*/



if (! defined('ABSPATH')) {
    exit;
}

  /* Checking WooCommerce is active or not */

   if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

  /* Settings Options */
  add_filter( 'woocommerce_order_button_html', 'rj_custom_button_for_checkout_page' );
  add_filter( 'woocommerce_general_settings','rj_woo_minimum_order_settings', 10, 2 );
  function rj_woo_minimum_order_settings( $settings ) {

      $settings[] = array(
        'title' => __( 'Minimum order settings', 'wc_minimum_order_amount' ),
        'type' => 'title',
        'desc' => 'Set the minimum order amount and adjust notifications',
        'id' => 'wc_minimum_order_settings',
      );

        // Minimum order amount
        $settings[] = array(
          'title'             => __( 'Minimum order amount', 'woocommerce' ),
          'desc'              => __( 'Leave this empty if all orders are accepted, otherwise set the minimum order amount', 'wc_minimum_order_amount' ),
          'id'                => 'wc_minimum_order_amount_value',
          'default'           => '',
          'type'              => 'number',
          'desc_tip'          => true,
          'css'      => 'width:70px;',
      );

      // Cart message
        $settings[] = array(
          'title'    => __( 'Cart message', 'woocommerce' ),
          'desc'     => __( 'Show this message if the current order total is less than the defined minimum - for example "50".', 'wc_minimum_order_amount' ),
          'id'       => 'wc_minimum_order_cart_notification',
          'default'  => 'Your current order total is %s — your order must be at least %s then you can checkout.',
          'type'     => 'text',
          'desc_tip' => true,
          'css'      => 'width:500px;',
      );

      // Checkout message
        $settings[] = array(
          'title'    => __( 'Checkout message', 'woocommerce' ),
          'desc'     => __( 'Show this message if the current order total is less than the defined minimum', 'wc_minimum_order_amount' ),
          'id'       => 'wc_minimum_order_checkout_notification',
          'default'  => 'Your current order total is %s — your order must be at least %s then you can checkout.',
          'type'     => 'text',
          'desc_tip' => true,
          'css'      => 'width:500px;',
        );

      $settings[] = array( 'type' => 'sectionend', 'id' => 'wc_minimum_order_settings' );
      return $settings;
  }

/* Notices */

add_action( 'woocommerce_before_checkout_form', 'rj_wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'rj_wc_minimum_order_amount' );


function rj_wc_minimum_order_amount() {

      // Get the minimum value from settings
      $minimum = get_option( 'wc_minimum_order_amount_value' );
      
      // check if the minimum value has even been set
      if ($minimum) {
      if ( WC()->cart->subtotal <= $minimum ) {
        if( is_cart() || is_checkout() ) {
            wc_print_notice(
                sprintf( get_option( 'wc_minimum_order_cart_notification' ),
                    wc_price( WC()->cart->subtotal ),
                    wc_price( $minimum )
                ), 'error'
            );
        } else {
            wc_add_notice(
                sprintf( get_option( 'wc_minimum_order_checkout_notification' ) ,
                    wc_price( WC()->cart->subtotal ),
                    wc_price( $minimum )
                ), 'error'
            );
                }
            }
        }
    }
    function rj_custom_button_for_checkout_page( $order_button ) {
 
	$minimum = get_option( 'wc_minimum_order_amount_value' );
    if( WC()->cart->subtotal <= $minimum ) {

    $order_button_text = __( "Check Minimum Order Amount", "woocommerce" );

    $style = ' style="text-align: center; color: #ff0101; background: #000;"';
    return '<p '.$style.' >' . esc_html( $order_button_text ) . '</p>';
    }
    
    else { $order_button22_text = __( "Place order", "woocommerce" );
        return '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="Place order" data-value="Place order" >' . esc_html( $order_button22_text ) . '</button>' ; }
    
}  
}