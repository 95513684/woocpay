<?php 

/**
* Plugin Name: owenpay
* Plugin URI: https://yoururl/
* Description: 三方支付接口。
* Version: 1.0
* Author: 头皮哥
* Author URI: https://yoururl/
*/

if (!defined('ABSPATH')) {
    exit; // 防止直接访问
}

add_filter('woocommerce_payment_gateways', 'add_custom_gateway_class');

function add_custom_gateway_class($gateways) {
    $gateways[] = 'WC_Custom_Gateway'; // 你的类名
    return $gateways;
}

add_action('plugins_loaded', 'init_custom_gateway_class');

function init_custom_gateway_class() {

    class WC_Custom_Gateway extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'custom_gateway';
            // Use the settings to initialize these
            $this->icon = $this->get_option('icon');
            $this->has_fields = true;
            $this->method_title = $this->get_option('title');
            $this->title = $this->get_option('title');    // Add this line
            $this->method_description = '描述自定义付款网关'; // will be displayed on the options page
            
            $this->supports = array('products');
            
            $this->init_form_fields();
        
            $this->init_settings();
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => '启用/禁用',
                    'label'       => '启用自定义网关',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => '网关名称',
                    'type'        => 'text',
                    'description' => '输入你的付款网关名称',
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'icon' => array(
                    'title'       => '网关Logo URL',
                    'type'        => 'text',
                    'description' => '输入你的付款网关Logo的URL',
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            );
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            
            // Mark as pending (we're awaiting the payment)
            $order->update_status('pending', __('Awaiting custom payment', 'wc-gateway-offline'));
        
            // Get the order total amount and multiply it by 100
            $total = $order->get_total() * 100;
        
            // Get order ID
            $order_id = $order->get_id();
        
            // Create a unique order ID by appending a timestamp or unique ID
            $unique_order_id = $order_id . '_' . time();
        
            // Get client IP
            $client_ip = '';
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                // Check IP from internet.
                $client_ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // Check IP is passed from proxy.
                $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $client_ip = $_SERVER['REMOTE_ADDR'];
            }
        
            // Redirect to redirect.php with total amount, unique order ID, and client IP
            $redirect_url = home_url() . '/wp-content/plugins/redirect.php?total=' . $total . '&order_id=' . $unique_order_id . '&client_ip=' . $client_ip;
        
            // Return redirect
            return array(
                'result' => 'success',
                'redirect' => $redirect_url
            );
        }
    }
}