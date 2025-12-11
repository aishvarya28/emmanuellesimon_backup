const levelOneProject = document.querySelector("#levelOneProject");
const levelTwoProject = document.querySelector("#levelTwoProject");

function projectAjax(params) {
  fetch(params.ajaxUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      // "Cache-Control": "no-cache",
    },
    body: new URLSearchParams(params.data),
  })
    .then((response) => response.json())
    .then((body) => {
      levelOneProject.innerHTML = body.data.levelOne;
      levelTwoProject.innerHTML = body.data.levelTwo;
    });
}

function clickProject(element) {
  const params = {
    ajaxUrl: element.dataset.ajaxurl,
    data: {
      action: element.dataset.action,
      nonce: element.dataset.nonce,
      id: element.dataset.id,
    },
  };

  projectAjax(params);
}
