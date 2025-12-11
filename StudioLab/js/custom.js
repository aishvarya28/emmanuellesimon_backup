jQuery(document).ready(function ($) {
  $(".request-price-btn-wishlist").on("click", function (e) {
    e.preventDefault();
    let productName = $(this).data("product-name");
    let productRef = $(this).data("product-ref");
    let productCategory = $(this).data("category-name");

    // Wait for popup to be fully load
    setTimeout(function () {
      $("#form-field-product").val(productName).trigger("input");
      $("#form-field-collection")
        .val(productCategory + " -")
        .attr("value", productCategory + " -")
        .trigger("input");
      $("#form-field-sku").val(productRef).trigger("input");

      //   $(".elementor-field-group-collection").css("width", "auto");

      $("#form-field-collection, #form-field-product").removeAttr("size");

      //   Adjusts the width of a field group dynamically based on the input's text width.
      function zlAdjustWidth(inputSelector, groupSelector) {
        var input = $(inputSelector);
        var fieldGroup = $(groupSelector);

        if (input.length && fieldGroup.length) {
          // Create a temporary span to measure text width
          var span = $("<span>")
            .text(input.val() || input.attr("placeholder"))
            .css({
              visibility: "hidden",
              position: "absolute",
              "white-space": "nowrap",
              font: input.css("font"), // Ensure it matches the input font
              "font-size": input.css("font-size"),
              "font-family": input.css("font-family"),
            });

          $("body").append(span);

          // Apply width based on measured text width
          var newWidth = span.width() + 20; // Add padding
          fieldGroup.attr("style", "width: " + newWidth + "px !important;");

          span.remove();
        }
      }

      // Adjust width for both fields
      zlAdjustWidth(
        "#form-field-collection",
        ".elementor-field-group-collection"
      );

      //zlAdjustWidth('.contact-fr #form-field-product', '.contact-fr .elementor-field-group-product');
    }, 100);
  });
});

jQuery(document).ready(function ($) {
  $('.woocommerce-Price-amount bdi').each(function () {
    var fullText = $(this).text();
    var currencySymbol = $(this).find('.woocommerce-Price-currencySymbol').text() || '€'; // fallback

    // Remove everything except digits to get the raw number
    var numberOnly = fullText.replace(/[^\d]/g, '');

    if (numberOnly) {
      // Format the number with a space as thousands separator
      var formattedNumber = numberOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

      // Update the HTML content with formatted number and currency symbol
      $(this).html(formattedNumber + '\u00A0<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span>');
    }
  });

  var priceText = $('.woocommerce-Price-amount bdi').first().text().replace(/[^\d]/g, '');
  priceText = parseInt(priceText);
  console.log("Cleaned price:", priceText);
    if (!priceText) {
    console.log("Price is blank, showing Price Request button.");
    $('.price').css("display", "none").css("opacity", "0");
    $('.eael-single-product-price .suffix-price-text').removeClass('show-suffix-text');
    $('.eael-single-product-price .prefix-price-text').removeClass('show-suffix-text');
    $('#form-field-price_type').val('price_request');
  }else{

  
  if (priceText >= 40000) {
    // Price Request Button case (above 40000)
    $('.price').css("display", "none").css("opacity", "0");
    $('#form-field-price_type').val('price_request');
  } else {
    if ($('html').attr('lang') === 'en-GB') {
      $('.request-price-btn .elementor-button-text').text('ORDER');
      $('.m-price_request .elementor-button-text').text('ORDER');
      $('.eael-single-product-price .prefix-price-text').text('PRICE :');
      $('.eael-single-product-price .suffix-price-text').text('(EXCL. VAT)');

    } else {
      $('.request-price-btn .elementor-button-text').text('COMMANDER');
      $('.m-price_request .elementor-button-text').text('COMMANDER');
      $('.eael-single-product-price .suffix-price-text').text('(HT)');
      
    }
    $('.eael-single-product-price .suffix-price-text').addClass('show-suffix-text');
    $('.eael-single-product-price .prefix-price-text').addClass('show-suffix-text');
    $('.price').css("opacity", "1").css("display", "block");
    $('.elementor-element-f725fe5').css("margin-top", "0px");
    $('#form-field-price_type').val('order');
  }
  
}
});


jQuery(document).ready(function ($) {
  // Function to get the latest price from the DOM
  function getCurrentPrice() {
    var priceText = $('.woocommerce-Price-amount bdi').first().text().replace(/[^\d]/g, '');
    return parseInt(priceText) || 0;
  }

  // Set a timeout when a swatchly swatch is clicked
  $('.swatchly-swatch').on('click', function () {
    setTimeout(function() {
      var price = getCurrentPrice();
      if (price && price <= 40000) {
        $('.woocommerce-variation-price').css('display', 'block');
        $('.zl-variable-product-price .elementor-button-text').text('COMMANDER');
      } else {
        $('.zl-variable-product-price .elementor-button-text').text('prix sur demande');
      }
    }, 500);
  });

  $('.zl-variable-product-price').on('click', function () {
    setTimeout(function() {
      var price = getCurrentPrice();
      if (price && price <= 40000) {
        $('#form-field-price_type').val('order');
      } else {
        $('#form-field-price_type').val('price_request');
      }
    }, 500);
  });
});