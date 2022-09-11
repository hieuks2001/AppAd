import html2canvas from "html2canvas";
window.onMissionDone = undefined;
window.onMissionDone = function (param) {
  alert(param);
};
window.onMissionDoneError = undefined;
window.onMissionDoneError = function (param) {
  alert(`${param}, Lỗi`);
};
window.onMissionCancel = undefined;
window.onMissionCancel = function (param) {
  alert(param);
};
window.onMissionCancelError = undefined;
window.onMissionCancelError = function (param) {
  alert(`${param}, Lỗi hủy nhiệm vụ`);
};
window.onMissionGetError = undefined;
window.onMissionGetError = function (param) {
  alert("Lỗi nhận nhiệm vụ");
};

async function missionPost(callback) {
  try {
    console.log(localStorage.getItem("currentMission"));
    const ms = await fetch(`${window.VITE_URL_API}/api/ms`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ms: localStorage.getItem("currentMission"),
      }),
    }).then((data) => data.json());
    callback(ms);
  } catch (error) {
    console.log(error);
  } finally {
    init()
  }
}
async function missionGet(callback) {
  try {
    const ms = await fetch(`${window.VITE_URL_API}/api/ms`, {
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
    const ms = await fetch(`${window.VITE_URL_API}/api/key`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        key: code,
        ms: localStorage.getItem("currentMission"),
      }),
    }).then((data) => data.json());
    callback(ms);
  } catch (error) {
    console.log(error);
  }
}
async function cancelMission(code, callback) {
  try {
    const ms = await fetch(`${window.VITE_URL_API}/api/cancel`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ms: localStorage.getItem("currentMission"),
      }),
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
const btnDeleteMs = document.getElementById("btn-delete-mission");

function init() {
  input = document.getElementById("i");
  output = document.getElementById("o");
  const options = {
    allowTaint: false,
    imageTimeout: 25000,
    allowTaint : false,
    useCORS: true,
  };
  html2canvas(input, options).then((canvas) => {
    const dataUrl = canvas.toDataURL();
    const imageEle = document.createElement("img");
    imageEle.src = dataUrl;
    if (dataUrl.includes("image")) {
      output.appendChild(imageEle);
    }
  });
  input.innerHTML = "";
}

/* Button Get Mission */
window.addEventListener("click", (e) => {
  const element = e.target;
  if (element.id === "btn-get-mission") {
    element.classList.add("loading");
    let input = undefined;
    let output = undefined;

    missionPost((result) => {
      /* success get mission */
      if (result?.error) {
        /* Error Here */
        if (window.onMissionGetError !== undefined) {
          window.onMissionGetError();
        }
        console.log("====================================");
        console.log(result);
        console.log("====================================");
        element.classList.remove("loading");
      }
      console.log("====================================");
      console.log(result);
      console.log("====================================");
      const { keyword, onsite, image } = result?.page;
      localStorage.setItem("currentMission", result?.mission);
      element.classList.remove("loading");
      document.querySelector("#modal-mission-detail .ms-keyword").textContent =
        keyword;
      document.querySelector(
        "#modal-mission-detail .ms-image"
      ).src = `https://memtraffic.com/images/${image}`;
      document.querySelector(
        "#modal-mission-detail .ms-onsite"
      ).textContent = `${onsite}s`;
      btnSubmitCode.dataset.id = element.dataset.id;
      btnDeleteMs.dataset.id = element.dataset.id;

      document.getElementById("btn-copy-kw").addEventListener("change", (e) => {
        if (e.currentTarget.checked) {
          navigator.clipboard.writeText(keyword);
        } else {
          document.getElementById("btn-copy-kw").checked = true;
        }
      });

      checkboxModalMissionDetail.checked = true; //show modal
    })
  }
});

/* Submit Code */
const inputCode = document.querySelector("#modal-mission-detail .ms-code");
btnSubmitCode.addEventListener("click", (e) => {
  btnSubmitCode.classList.add("loading");
  pasteKey(inputCode.value, (result) => {
    if ("error" in result) {
      /* Error Here */
      if (window.onMissionDoneError !== undefined) {
        window.onMissionDoneError(e.target.dataset.id);
      }
    } else {
      /* Success Here */
      console.log("====================================");
      console.log(e.target.dataset.id); //this is data-id of btn-get-mission
      console.log("====================================");
      localStorage.removeItem("currentMission");
      checkboxModalMissionDetail.checked = false; //hide modal if success
      if (window.onMissionDone !== undefined) {
        window.onMissionDone(e.target.dataset.id);
      }
    }
    btnSubmitCode.classList.remove("loading");
  });
});

btnDeleteMs.addEventListener("click", (e) => {
  btnDeleteMs.classList.add("loading");
  cancelMission(inputCode.value, (result) => {
    if ("error" in result) {
      /* Error Here */
      if (window.onMissionCancelError !== undefined) {
        window.onMissionCancelError(e.target.dataset.id);
      }
    } else {
      /* Success Here */
      console.log("====================================");
      console.log(e.target.dataset.id); //this is data-id of btn-get-mission
      console.log("====================================");
      localStorage.removeItem("currentMission");
      checkboxModalMissionDetail.checked = false; //hide modal if success
      if (window.onMissionCancel !== undefined) {
        window.onMissionCancel(e.target.dataset.id);
      }
    }
    btnDeleteMs.classList.remove("loading");
  });
});
