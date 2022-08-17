const options = (k) => ({
  method: "POST",
  headers: { "content-type": "application/json" },
  body: JSON.stringify({
    pageId: value,
    publicKey: k,
    host: window.location.hostname,
    path: window.location.pathname,
  }),
});
const options2 = (k, d) => ({
  method: "POST",
  headers: { "content-type": "application/json" },
  body: JSON.stringify({
    pageId: value,
    publicKey: k,
    data: d,
  }),
});

function handleError(error) {
  let errorCode = 0;
  if (error === "Traffic của site chưa sẵn sàng") {
    errorCode = 1;
  }
  if (error === "Lỗi, nhúng không đúng site") {
    errorCode = 2;
  }
  if (error === "Lỗi") {
    errorCode = 3;
  }
  if (error === "Lỗi key") {
    errorCode = 5;
  }
  alert(`vui lòng kiểm tra lại thao tác của bạn, Lỗi code #${errorCode}`);
}

async function getCode() {
  const rs = await fetch(
    // "http://localhost:8001/generate-code",
    "https://nhiemvu.app/generate-code",
    options(localStorage.getItem("publicKey"))
  ).then((response) => response.json());
  if (rs?.code || rs?.onsite) {
    return rs;
  } else if (rs?.error) {
    throw rs.error;
  }
}
async function check(data) {
  try {
    const rs = await fetch(
      // "http://localhost:8001/check",
      "https://nhiemvu.app/check",
      options2(localStorage.getItem("publicKey"), data)
    ).then((response) => response.json());
    if (rs?.success) {
      return rs;
    } else if (rs?.error) {
      throw rs.error;
    }
  } catch (error) {}
}

const publicKey = document.getElementById("publicKey");
const getCodeBtn = document.getElementById("getCode");
window.addEventListener("DOMContentLoaded", async () => {
  const result = await getCode().catch((e) => {});
  if (result?.code) {
    publicKey.hidden = true;
    getCodeBtn.textContent = result.code;
    getCodeBtn.title = "Click để sao chép code";
    getCodeBtn.addEventListener("click", async (e) => {
      e.preventDefault();
      await navigator.clipboard.writeText(result.code);
      alert("Đã sao chép");
    });
  } else {
    publicKey.hidden = false;
    // localStorage.removeItem("publicKey");
    //nếu chưa có code sẽ check là google
    if (document.referrer.includes("https://www.google.com")) {
      getCodeBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        localStorage.setItem("publicKey", publicKey.value);
        const rs = await getCode().catch((error) => {
          getCodeBtn.textContent = "Lấy mã";
          handleError(error);
          console.log(error);
        });
        // getCodeBtn.disabled = true;
        if (rs?.onsite) {
          publicKey.hidden = true;
          run(rs.onsite);
        }
      });
    } else {
      getCodeBtn.textContent = "Lấy mã";
      getCodeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        alert(`vui lòng kiểm tra lại thao tác của bạn, Lỗi code #4`);
      });
      // getCodeBtn.disabled = true;
    }
  }
});

const countdown = document.getElementById("countdown");

function decodeGetTimes(onsite) {
  const main = publicKey.value;
  return [
    0,
    parseInt(main[5] + main[10], 16),
    parseInt(main[25] + main[28], 16),
    onsite,
  ];
}

function run(onsite) {
  let cd = onsite;
  let timer = null;
  function name() {
    timer = setInterval(async () => {
      getCodeBtn.textContent = `Vui lòng đợi giây lát ${cd > -1 ? cd : 0}`;
      if (cd === 0) {
        clearInterval(timer);
        getCodeBtn.textContent = "Click link bất kỳ trong trang để nhận code";
      }
      if (decodeGetTimes(onsite).includes(cd)) {
        await check(cd);
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
  }
}
