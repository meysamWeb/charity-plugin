jQuery(document).ready(function ($) {
    $(".parent-checkbox").change(function () {
        if ($(this).is(":checked")) {
            $(this).closest('.parent').find('.child-list').slideDown();
        } else {
            $(this).closest('.parent').find('.child-list').slideUp();
        }
    });

    $(".child-checkbox").change(function () {
        let $grandchildList = $(this).closest('.child').find('.grandchild-list');
        if ($(this).is(":checked")) {
            $grandchildList.slideDown();
            $(this).closest('.child').find('.child-input').slideDown();
        } else {
            $grandchildList.slideUp();
            $(this).closest('.child').find('.child-input').slideUp();
            $grandchildList.find('.grandchild-checkbox').prop('checked', false);
            $grandchildList.find('.grandchild-value').val(0);
        }
    });

    // Select all the child-value elements
    let childValue = $('.child-value');

    // Save the previous child values
    let prevChildVal = [0, 0, 0];

    // Listen for changes to the child-value elements
    childValue.on('input', function () {
        // Get the current index of the child-value element
        let index = childValue.index($(this));

        // Get the current value of the child-value element
        let childVal = parseFloat($(this).val().replace(',', '.'));

        // Check if childVal is a valid number
        if (isNaN(childVal) || childVal < 0) {
            // Reset the child-value element to 0
            $(this).val(0);
            childVal = 0;
        }

        // Get the current value of the cart, including shipping cost
        let cartTotal = parseFloat($('.woocommerce-Price-amount.amount:last').text().replace(/[^\d.,]/g, '').replace(',', '.'));

        // Subtract the previous child value from the cart total
        cartTotal -= prevChildVal[index];

        // Add the new child value to the cart total
        cartTotal += childVal;

        // Save the current child value as the previous child value
        prevChildVal[index] = childVal;

        // Check if cartTotal is a valid number
        if (isNaN(cartTotal)) {
            // Set cartTotal to 0
            cartTotal = 0;
        }

        // Get the current currency symbol
        let currencySymbol = $('.woocommerce-Price-currencySymbol:first').text();
        // Update the cart total with the new total
        $(".woocommerce-Price-amount.amount:last").html('<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span>' + cartTotal.toFixed(2).replace('.', ','));
    });


    // This part is for the charity amount addition
    $('.child-value').on('change', function() {
        let charityAmount = parseFloat($(this).val());

        // If the charityAmount is cleared or invalid, set it to 0
        if (isNaN(charityAmount) || charityAmount < 0) {
            charityAmount = 0;
            $(this).val(0); // Reset the input value to 0 if it's invalid
        }

        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: {
                'action': 'update_charity_in_cart',
                'charity_amount': charityAmount,
                'security': wc_checkout_params.update_order_review_nonce
            },
            success: function(response) {
                // Force WooCommerce to update cart totals after charity is added/removed
                $(document.body).trigger('update_checkout');
            }
        });
    });

});