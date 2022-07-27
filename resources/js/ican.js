const options = {
  method: "POST",
  headers: { "content-type": "application/json" },
  body: JSON.stringify({
    pageId: value,
    host: window.location.hostname,
    path: window.location.pathname,
  }),
};
async function getCode() {
  try {
    const rs = await fetch("https://nhiemvu.app/generate-code", options).then(
      (response) => response.json()
    );
    if ("code" in rs || "onsite" in rs) {
      return rs;
    } else if ("error" in rs) {
      // throw rs.error;
    }
  } catch (error) {
    // getCodeBtn.textContent = error;
  }
}

const getCodeBtn = document.getElementById("getCode");
window.addEventListener("DOMContentLoaded", async () => {
  try {
    const result = await getCode();
    if ("code" in result) {
      getCodeBtn.textContent = result.code;
      getCodeBtn.title = "Click để sao chép code";
      getCodeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        navigator.clipboard.writeText(result.code);
        getCodeBtn.title = "Đã sao chép";
      });
    } else {
      //nếu chưa có code sẽ check là google
      if (
        "https://www.google.com/".includes(document.referrer) &&
        document.referrer != ""
      ) {
        getCodeBtn.textContent = "";
        getCodeBtn.disabled = true;
        throw "Lỗi";
      }
      getCodeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        getCodeBtn.disabled = true;
        run(result.onsite);
      });
    }
  } catch (error) {}
});

const countdown = document.getElementById("countdown");

function run(onsite) {
  let cd = onsite;
  let timer = null;
  function name() {
    timer = setInterval(() => {
      getCodeBtn.textContent = `Vui lòng đợi giây lát ${cd > -1 ? cd : 0}`;
      if (cd === 0) {
        clearInterval(timer);
        getCodeBtn.textContent = "Click link bất kỳ trong trang để nhận code";
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
