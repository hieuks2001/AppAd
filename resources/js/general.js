window.onMissionDone = undefined;
window.onMissionDone = function (param) {
  alert(param);
};
async function missionPost(callback) {
  try {
    const ms = await fetch(`${window.VITE_URL_API}/ms`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((data) => data.json());
    callback(ms);
  } catch (error) {
    console.log(error);
  }
}
async function missionGet(callback) {
  try {
    const ms = await fetch(`${window.VITE_URL_API}/ms`, {
      method: "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((data) => data.json());
    callback(ms);
  } catch (error) {
    console.log(error);
  }
}
async function pasteKey(code, callback) {
  try {
    const ms = await fetch(`${window.VITE_URL_API}/key`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ key: code }),
    }).then((data) => data.json());
    callback(ms);
  } catch (error) {
    console.log(error);
  }
}

const modalMissionDefailt = document.getElementById("modal-mission-detail");
const checkboxModalMissionDetail = document.getElementById(
  "checkbox-modal-mission-detail"
);
const btnSubmitCode = document.getElementById("btn-submit-code");

/* Button Get Mission */
window.addEventListener("click", (e) => {
  const element = e.target;
  if (element.id === "btn-get-mission") {
    element.classList.add("loading");
    missionPost((result) => {
      /* success get mission */
      if ("error" in result) {
        /* Error Here */
        console.log("====================================");
        console.log(result);
        console.log("====================================");
        element.classList.remove("loading");
      }
      const { keyword, onsite, image } = result?.mission;
      element.classList.remove("loading");
      document.querySelector("#modal-mission-detail .ms-keyword").textContent =
        keyword;
      document.querySelector(
        "#modal-mission-detail .ms-image"
      ).src = `${window.VITE_URL_API}/images/${image}`;
      document.querySelector(
        "#modal-mission-detail .ms-onsite"
      ).textContent = `${onsite}s`;
      btnSubmitCode.dataset.id = element.dataset.id;
      checkboxModalMissionDetail.checked = true; //show modal
    });
  }
});

/* Submit Code */
const inputCode = document.querySelector("#modal-mission-detail .ms-code");
btnSubmitCode.addEventListener("click", (e) => {
  btnSubmitCode.classList.add("loading");
  pasteKey(inputCode.value, (result) => {
    if ("error" in result) {
      /* Error Here */
    } else {
      /* Success Here */
      console.log("====================================");
      console.log(e.target.dataset.id); //this is data-id of btn-get-mission
      console.log("====================================");
      checkboxModalMissionDetail.checked = false; //hide modal if success
      if (window.onMissionDone !== undefined) {
        window.onMissionDone(e.target.dataset.id);
      }
    }
    btnSubmitCode.classList.remove("loading");
  });
});
