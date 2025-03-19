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