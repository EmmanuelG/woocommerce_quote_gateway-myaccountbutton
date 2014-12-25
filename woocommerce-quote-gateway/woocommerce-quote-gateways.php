<?php
/*
Plugin Name: WooCommerce Quote Gateway
Plugin URI: 
Description: Add Quote Gateways for WooCommerce.
Version: 1.0.0
Author: Manu et Ben
Author URI: 
License: GPLv2
*/


/* WooCommerce fallback notice. */
function woocommerce_cpg_fallback_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Quote Gateways depends on the last version of %s to work!', 'wcCpg' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
}

/* Load functions. */
function openedge_quote_gateway_load() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
        add_action( 'admin_notices', 'woocommerce_cpg_fallback_notice' );
        return;
    }
   
    function wc_add_Quote_gateway( $methods ) {
        $methods[] = 'WC_Gateway_Quote';
        return $methods;
    }
	add_filter( 'woocommerce_payment_gateways', 'wc_add_Quote_gateway' );
	
	
    // Include the WooCommerce Custom Payment Gateways classes.
    require_once plugin_dir_path( __FILE__ ) . 'class-wc-gateway-quote.php';

}

add_action( 'plugins_loaded', 'openedge_quote_gateway_load', 0 );

/**
* Add a PDF link to the My Account orders table
*/
function openedge_my_account_quote( $actions = NULL, $order = NULL ) {
    global $woocommerce;

    $gateway = wc_get_payment_gateway_by_order($order->id);

    if ($gateway && $gateway->id == 'quote') :
     
        $actions['quote'] = array(
            'url'  => add_query_arg( 'pdfid', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ),
            'name' => __( apply_filters('woocommerce_pdf_my_account_button_label', __( 'PDF Quote', 'woocommerce-pdf-invoice' ) ) )
        );
     
    endif;

    return $actions;
     
}

// Add invoice action to My-order page
add_filter( 'woocommerce_my_account_my_orders_actions', 'openedge_my_account_quote', 10, 2 );

?>