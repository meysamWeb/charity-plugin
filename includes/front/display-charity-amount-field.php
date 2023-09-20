<?php
function display_charity_amount_field() {
    $titles = get_option('charity_plugin_titles', array());
    ?>
    <div class="parent">
        <label>
            <input type="checkbox" class="parent-checkbox">
            Want to donate to a charity? (Optional)
        </label>
        <div class="child-list">
            <?php foreach($titles as $key => $title): ?>
                <div class="child">
                    <label>
                        <input type="checkbox" class="child-checkbox">
                        <?= esc_html($title); ?>
                    </label>
                    <div class="child-input">
                        <label>
                            <span class="child-input-amount">Amount</span>
                            <input type="number" class="child-value">
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}


add_action('woocommerce_review_order_before_payment', 'display_charity_amount_field', 50, 0);