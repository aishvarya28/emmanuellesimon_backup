const levelOneMobile = document.querySelector("#levelOneMobile");
const levelTwoMobile = document.querySelector("#levelTwoMobile");
const levelThreeMobile = document.querySelector("#levelThreeMobile");
const titleMobilier = document.querySelector("#title-mobilier");
const mobilierMobile = document.querySelector("#mobilier-mobile");
const architectureMobile = document.querySelector("#architecture-mobile");
const archtectureOneMobile = document.querySelector("#archtectureOneMobile");
const archtectureTwoMobile = document.querySelector("#archtectureTwoMobile");
const architectureButton = document.querySelector("#architectureButton");
const mobilierButton = document.querySelector("#mobilierButton");
const popupMobile = document.querySelector("#popup-mobile");
const menuButton = document.querySelector("#menuButton");
const body = document.querySelector("body");

function eshopAjaxMobile(params) {
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
        console.log(body);
        levelOneMobile.innerHTML = body.data.levelOne;
        levelTwoMobile.innerHTML = body.data.levelTwo;
        levelThreeMobile.innerHTML = "";
      }
      if (params.data.level == "two") {
        levelTwoMobile.innerHTML = body.data.levelTwo;
        levelThreeMobile.innerHTML = body.data.levelThree;
      }
    });
}

function archictectureAjaxMobile(params) {
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
        console.log(body);
        archtectureOneMobile.innerHTML = body.data.levelOne;
        archtectureTwoMobile.innerHTML = body.data.levelTwo;
      }
      if (params.data.level == "two") {
        archtectureTwoMobile.innerHTML = body.data.levelTwo;
        archtectureTwoMobile.innerHTML = body.data.levelThree;
      }
    });
}

function clickEshopMobile(element) {
  const params = {
    ajaxUrl: element.dataset.ajaxurl,
    data: {
      action: element.dataset.action,
      nonce: element.dataset.nonce,
      level: element.dataset.level,
      why: element.dataset.why,
      id: element.dataset.id,
    },
  };

  eshopAjaxMobile(params);
}

function clickArchitectureMobile(element) {
  const params = {
    ajaxUrl: element.dataset.ajaxurl,
    data: {
      action: element.dataset.action,
      nonce: element.dataset.nonce,
      level: element.dataset.level,
      why: element.dataset.why,
      id: element.dataset.id,
    },
  };

  archictectureAjaxMobile(params);
}

function showMobiler() {
  if (
    mobilierMobile.style.display == "" ||
    mobilierMobile.style.display == "none"
  ) {
    mobilierMobile.style.display = "flex";
    mobilierButton.classList.add("active");
  } else {
    mobilierMobile.style.display = "none";
    mobilierButton.classList.remove("active");
  }

  if (architectureMobile.style.display == "flex") {
    architectureMobile.style.display = "none";
    architectureButton.classList.remove("active");
  }
}

function showArchitecture() {
  if (
    architectureMobile.style.display == "" ||
    architectureMobile.style.display == "none"
  ) {
    architectureMobile.style.display = "flex";
    architectureButton.classList.add("active");
  } else {
    architectureMobile.style.display = "none";
    architectureButton.classList.remove("active");
  }

  if (mobilierMobile.style.display == "flex") {
    mobilierMobile.style.display = "none";
    mobilierButton.classList.remove("active");
  }
}

function showMenu() {
  if (popupMobile.style.display == "" || popupMobile.style.display == "none") {
    popupMobile.style.display = "flex";
    menuButton.innerHTML =
      '<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg"><line y1="-0.375" x2="21.9333" y2="-0.375" transform="matrix(0.731055 0.682318 -0.342529 0.939507 1 1.53516)" stroke="black" stroke-width="0.75"/><path d="M1 16.0991L16.7004 1" stroke="black" stroke-width="0.75"/></svg>';
    body.style.height = "100vh";
    body.style.overflowY = "hidden";
  } else {
    popupMobile.style.display = "none";
    menuButton.innerHTML =
      '<svg width="24" height="15" viewBox="0 0 24 15" fill="none" xmlns="http://www.w3.org/2000/svg"> <line y1="0.625" x2="23.8" y2="0.625" stroke="black" stroke-width="0.75"/><line y1="7.625" x2="23.8" y2="7.625" stroke="black" stroke-width="0.75"/> <line y1="14.625" x2="23.8" y2="14.625" stroke="black" stroke-width="0.75"/></svg>';
    body.style.height = "";
    body.style.overflowY = "";
  }
}
