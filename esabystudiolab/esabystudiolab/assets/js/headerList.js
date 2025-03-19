const levelOne = document.querySelector("#levelOne");
const levelTwo = document.querySelector("#levelTwo");
const levelThree = document.querySelector("#levelThree");


function headerAjax(params) {
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
      if (params.data.level == "one") {
        levelOne.innerHTML = body.data.levelOne;
        levelTwo.innerHTML = body.data.levelTwo;
        levelThree.innerHTML = "";
      }
      if (params.data.level == "two") {
        levelTwo.innerHTML = body.data.levelTwo;
        levelThree.innerHTML = body.data.levelThree;
      }
    });
}

function clickHeader(element) {
  const params = {
    ajaxUrl: element.dataset.ajaxurl,
    data: {
      action: element.dataset.action,
      nonce: element.dataset.nonce,
      level: element.dataset.level,
      why: element.dataset.why,
      id: element.dataset.id,
      lang: element.dataset.lang,
    },
  };

  headerAjax(params);
}
