<?php

function custom_order_item_totals_label($total_rows, $order): array {
    foreach ($total_rows as $key => $total) {
        if ($key == 'fee_total') {
            $fees = $order->get_fees();
            foreach ($fees as $fee) {
                if ($fee->get_name() == 'Charity Amount') {
                    $total_rows[$key]['label'] = __('Charity Amount:', 'woocommerce');
                }
            }
        }
    }
    return $total_rows;
}

add_filter('woocommerce_get_order_item_totals', 'custom_order_item_totals_label', 10, 2);