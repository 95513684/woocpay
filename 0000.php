<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['outTradeNo'];
    
    if (!empty($order_id)) {
        // Include WooCommerce order functions
        if (!function_exists('wc_get_order')) {
            require_once '/wp-content/plugins/woocommerce/includes/wc-order-functions.php';
        }
        
        $order = wc_get_order($order_id);
        if ($order) {
            $order->payment_complete();
            $order->update_status('completed');
            $order->add_order_note('接口报送0000已付款');
            
        } else {
            echo '无效的订单 ID。';
        }
    } else {
        echo '请填写订单 ID。';
    }
}
?>