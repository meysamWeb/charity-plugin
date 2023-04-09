
jQuery(document).ready(function($) {
    $(".parent-checkbox").change(function() {
      if ($(this).is(":checked")) {
        $(this).closest('.parent').find('.child-list').slideDown();
      } else {
        $(this).closest('.parent').find('.child-list').slideUp();
      }
    });
  
    $(".child-checkbox").change(function() {
        // $(".child-checkbox").not(this).prop("checked", false);
        jQuery('.child-input').not(jQuery(this).siblings('.child-input').slideToggle()).slideUp();
      var $grandchildList = $(this).closest('.child').find('.grandchild-list');
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



//     // Select the child-value element
//   var $childValue = $('.child-value');

//   // Listen for changes to the child-value element
//   $childValue.on('change', function() {
//     // Get the current value of the child-value element
//     var childVal = parseFloat($(this).val());

//     // Get the current value of the cart
//     var cartTotal = parseFloat($('.woocommerce-Price-amount.amount').text().replace(/[^\d\.]/g, ''));

//     // Add the childVal to the cartTotal
//     var newTotal = (cartTotal + childVal).toFixed(2);

//     // Update the cart total with the new total
//     $('.woocommerce-Price-amount.amount').text('$' + newTotal);
//   });







// // Select the child-value element
// let $childValue = $('.child-value');

// // Save the previous child value
// let prevChildVal = 0;

// // Listen for changes to the child-value element
// $childValue.on('input', function() {
//   // Get the current value of the child-value element
//   let childVal = parseFloat($(this).val().replace(',', '.'));

//   // Check if childVal is a valid number
//   if (isNaN(childVal) || childVal < 0) {
//     // Reset the child-value element to 0
//     $(this).val(0);
//     childVal = 0;
//   }

//   // Get the current value of the cart
//   let cartTotal = parseFloat($('.woocommerce-Price-amount.amount').text().replace(/[^\d.,]/g, '').replace(',', '.'));

//   // Subtract the previous child value from the cart total
//   cartTotal -= prevChildVal;

//   // Add the new child value to the cart total
//   cartTotal += childVal;

//   // Save the current child value as the previous child value
//   prevChildVal = childVal;

//   // Check if cartTotal is a valid number
//   if (isNaN(cartTotal)) {
//     // Set cartTotal to 0
//     cartTotal = 0;
//   }

//   // Update the cart total with the new total
//   $('.woocommerce-Price-amount.amount').text('$' + cartTotal.toFixed(2).replace('.', ','));
// });


// Select all the child-value elements
let childValue = $('.child-value');

// Save the previous child values
let prevChildVal = [0, 0, 0];

// Listen for changes to the child-value elements
childValue.on('input', function() {
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

  // Get the current value of the cart
  let cartTotal = parseFloat($('.woocommerce-Price-amount.amount').text().replace(/[^\d.,]/g, '').replace(',', '.'));

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

  // Update the cart total with the new total
  $('.woocommerce-Price-amount.amount').text('$' + cartTotal.toFixed(2).replace('.', ','));
});


});
  