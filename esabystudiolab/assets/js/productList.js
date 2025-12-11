const viewThree = document.querySelector("#view-three");
const viewFour = document.querySelector("#view-four");
const viewFive = document.querySelector("#view-five");
const filtersType = document.getElementsByClassName("filter-type");

const filters = document.querySelector("#filtres");
const listProduct = document.querySelector("#list-produit");

viewThree.addEventListener("click", (event) => {
  listProduct.className = "three-column";
  viewThree.className = "view-active";
  viewFour.className = "";
  viewFive.className = "";
});

viewFive.addEventListener("click", (event) => {
  listProduct.className = "five-column";
  viewThree.className = "";
  viewFour.className = "";
  viewFive.className = "view-active";
});

viewFour.addEventListener("click", (event) => {
  listProduct.className = "four-column";
  viewThree.className = "";
  viewFour.className = "view-active";
  viewFive.className = "";
});

const filterAjax = function (params) {
  fetch(params.ajaxUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "Cache-Control": "no-cache",
    },
    body: new URLSearchParams(params.data),
  })
    .then((response) => response.json())
    .then((body) => {
      filters.innerHTML = body.data.filter;
      listProduct.innerHTML = body.data.listProduct;
    });
};

function clickfiltre(element) {
  const params = {
    ajaxUrl: element.dataset.ajaxurl,
    data: {
      action: element.dataset.action,
      nonce: element.dataset.nonce,
      id: element.dataset.id,
      slug: element.dataset.slug,
      categorie: element.dataset.categorie,
    },
  };
  filterAjax(params);
}

function hoverImg(element) {
  fadeInEffect(element, 50);
  var hoverimg = element.src;
  element.src = element.dataset.hoverimg;
  element.dataset.hoverimg = hoverimg;
}

function fadeOutEffect(element) {
  var fadeoutEffect = setInterval(function () {
    if (!element.style.opacity) {
      element.style.opacity = 1;
    }
    if (element.style.opacity > 0) {
      console.log(element.style.opacity);
      element.style.opacity -= 0.1;
    } else {
      clearInterval(fadeoutEffect);
    }
  }, 25);
}

function fadeInEffect(element, time = 25) {
  var opacity = 0;
  element.style.opacity = 0;
  var fadeinEffect = setInterval(function () {
    if (element.style.opacity < 1) {
      opacity += 0.1;
      element.style.opacity = opacity;
    } else {
      clearInterval(fadeinEffect);
    }
  }, time);
}

function clickwishList(element){
  console.log(yith_wcwl_l10n.ajax_url)
  const params = {
    ajaxUrl: yith_wcwl_l10n.ajax_url,
    data: {
      action: yith_wcwl_l10n.actions.add_to_wishlist_action,
      nonce: yith_wcwl_l10n.nonce.add_to_wishlist_nonce,
      context: 'frontend',
      add_to_wishlist: element.dataset.id,
      product_type: 'simple',
      fragments: '',
      wishlist_id: '4' 
    },
  }

  wishListAjax(params);
 
}

const wishListAjax = function (params) {
  fetch(params.ajaxUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "Cache-Control": "no-cache",
    },
    body: new URLSearchParams(params.data),
  })
    .then((response) => response.json())
    .then((body) => {
      console.log(body)
    });
};


var input = document.getElementById("search");

input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    window.location.replace('?search="'+input.value+'"');

  }
})
// jQuery(document).ready(function ($) {
//   var priceText = $('.woocommerce-Price-amount bdi').text().trim();
//   var originalText = priceText; // For debugging
  
//   if (!priceText) {
//     // If price is blank
//     console.log("Price is blank, showing Price Request button.");
//     $('.price').css("display", "none").css("opacity", "0");
//     $('.eael-single-product-price .suffix-price-text').removeClass('show-suffix-text');
//     $('.eael-single-product-price .prefix-price-text').removeClass('show-suffix-text');
//     $('#form-field-price_type').val('price_request');
//   }else{
//   // Format cases
//   if (priceText.includes(',') && priceText.includes('.')) {
//     // Format like 4.500,00 → 4500.00
//     priceText = priceText.replace(/\./g, '').replace(',', '.');
//   } else if (priceText.includes(',') && !priceText.includes('.')) {
//     priceText = priceText.replace(',', '.');
//   }
//   // else, already correctly formatted

//   priceText = priceText.replace(/[^0-9.]/g, '');
//   var price = parseFloat(priceText);

//   console.log("Original:", originalText, "Parsed:", price);

//   if (price >= 40000) {
//     // Price Request Button case (above 40000)
//     $('.price').css("display", "none").css("opacity", "0");
//     $('#form-field-price_type').val('price_request');
//   } else {
//     if ($('html').attr('lang') === 'en-GB') {
//       $('.request-price-btn .elementor-button-text').text('ORDER');
//       $('.eael-single-product-price .prefix-price-text').text('PRICE :');
//       $('.eael-single-product-price .suffix-price-text').text('(EXCL. VAT)');

//     } else {
//       $('.request-price-btn .elementor-button-text').text('COMMANDER');
//       $('.m-price_request .elementor-button-text').text('COMMANDER');
//       $('.eael-single-product-price .suffix-price-text').text('(HT)');
      
//     }
//     $('.eael-single-product-price .suffix-price-text').addClass('show-suffix-text');
//     $('.eael-single-product-price .prefix-price-text').addClass('show-suffix-text');
//     $('.price').css("opacity", "1").css("display", "block");
//     $('.elementor-element-f725fe5').css("margin-top", "0px");
//     $('#form-field-price_type').val('order');
//   }
// }
// });