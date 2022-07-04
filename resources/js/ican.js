const options = {
  method: "POST",
  headers: { "content-type": "application/json" },
  body: JSON.stringify({
    pageId: value,
  }),
};
async function getTimeOnsite() {
  try {
    const rs = await fetch("http://localhost:8000/info-site", options).then(
      (response) => response.json()
    );
    return rs.onsite;
  } catch (error) {
    console.log(error);
  }
}

async function getCode() {
  try {
    const code = document.createElement("p");
    const rs = await fetch("http://localhost:8000/generate-code", options).then(
      (response) => response.json()
    );
    code.textContent = rs;
    code.title = "Click để copy code";
    mainEle.appendChild(code);
    code.addEventListener("click", () => {
      navigator.clipboard.writeText(rs);
    });
  } catch (error) {
    console.log(error);
  }
}

const getCodeBtn = document.getElementById("getCode");
getCodeBtn.addEventListener("click", (e) => {
  e.preventDefault();
  getCodeBtn.hidden = true;
  run();
});
const mainEle = document.getElementById("canihelpu");
const notificationEle = document.getElementById("notification");
const countdown = document.getElementById("countdown");

async function run() {
  const onsite = await getTimeOnsite();
  let cd = onsite;
  let timer = null;
  function name() {
    notificationEle.textContent = "Vui lòng đợi giây lát";
    timer = setInterval(() => {
      countdown.textContent = cd > -1 ? cd : 0;
      if (cd === 0) {
        clearInterval(timer);
        getCode();
      }
      if (cd > -1) {
        cd--;
      }
    }, 1000);
  }
  if (cd) {
    window.onfocus = function () {
      name();
    };
    window.onblur = function () {
      if (timer) clearInterval(timer);
    };
    name();
  } else {
    notificationEle.textContent = "Traffic của website hiện tại chưa sẵn sàng.";
  }
}
