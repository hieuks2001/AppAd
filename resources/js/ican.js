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
    id: value,
    key: k,
    data: d,
    path: window.location.pathname,
  }),
});
const options3 = {
  method: "POST",
  headers: { "content-type": "application/json" },
  body: JSON.stringify({
    id: value,
  }),
};

function handleError(error) {
  let errorCode = 0;
  if (error === "Traffic của site chưa sẵn sàng") {
    errorCode = 1;
  }
  if (error === "Site url not correct!") {
    errorCode = 2;
  }
  if (error === "Error") {
    errorCode = 3;
  }
  alert(`vui lòng kiểm tra lại thao tác của bạn, Lỗi code #${errorCode}`);
}

async function initPage() {
  const rs = await fetch(`${URL_API}/page-init`, options3).then((response) =>
    response.json()
  );
  if (rs?.onsite && rs?.key) {
    return rs;
  } else if (rs?.error) {
    throw rs.error;
  }
}
async function getCode(k, d) {
  try {
    const rs = await fetch(`${URL_API}/generate-code`, options2(k, d)).then(
      (response) => response.json()
    );
    if (rs?.code) {
      return rs;
    } else if (rs?.error) {
      throw rs.error;
    }
  } catch (error) {}
}

const publicKey = document.getElementById("publicKey");
const getCodeBtn = document.getElementById("getCode");
window.addEventListener("DOMContentLoaded", async () => {
  const result = await getCode(localStorage.getItem("publicKey"), null).catch(
    (e) => {
      handleError(e);
    }
  );
  if (result?.code) {
    getCodeBtn.textContent = result.code;
    getCodeBtn.title = "Click để sao chép code";
    getCodeBtn.addEventListener("click", async (e) => {
      e.preventDefault();
      await navigator.clipboard.writeText(result.code);
      alert("Đã sao chép");
    });
  } else {
    // localStorage.removeItem("publicKey");
    //nếu chưa có code sẽ check là google
    if (document.referrer.includes("https://www.google.com")) {
      getCodeBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        const rs = await initPage().catch((error) => {
          getCodeBtn.textContent = "Lấy mã";
          handleError(error);
        });
        getCodeBtn.disabled = true;
        if (rs?.onsite) {
          localStorage.setItem("publicKey", rs?.key);
          run(rs.onsite, rs.key);
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

function decodeGetTimes(onsite, key) {
  return [
    0,
    parseInt(key[5] + key[10], 16),
    parseInt(key[25] + key[28], 16),
    onsite,
  ];
}

function run(onsite, key) {
  let cd = onsite;
  let timer = null;

  function name() {
    timer = setInterval(async () => {
      getCodeBtn.textContent = `Vui lòng đợi giây lát ${cd > -1 ? cd : 0}`;
      if (cd === 0) {
        clearInterval(timer);
        getCodeBtn.textContent = "Click link bất kỳ trong trang để nhận code";
      }
      if (decodeGetTimes(onsite, key).includes(cd)) {
        await getCode(key, cd);
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

function getRandomArbitrary(min, max) {
  return Math.floor(Math.random() * (max - min) + min);
}

