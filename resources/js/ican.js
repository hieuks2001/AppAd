import AES from "crypto-js/aes";
const pwd = new Date().valueOf().toString();
const options2 = (k, d) => {
  const encrypted = AES.encrypt(
    JSON.stringify({
      id: value,
      key: k,
      data: d,
      path: window.location.pathname,
    }),
    pwd
  );
  return {
    method: "POST",
    headers: { "content-type": "application/json" },
    body: JSON.stringify({
      key1: encrypted.ciphertext.toString(),
      key2: encrypted.key.toString(),
      key3: encrypted.iv.toString(),
    }),
  };
};
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

function decodeGetTimes(onsite, key) {
  return [
    0,
    parseInt(key[5] + key[10], 16),
    parseInt(key[25] + key[28], 16),
    onsite,
  ];
}

const upIcon = `
<svg
  width="36"
  height="36"
  viewBox="0 0 24 24"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
>
  <path
    d="M17.6568 8.96219L16.2393 10.3731L12.9843 7.10285L12.9706 20.7079L10.9706 20.7059L10.9843 7.13806L7.75404 10.3532L6.34314 8.93572L12.0132 3.29211L17.6568 8.96219Z"
    fill="#fff"
  />
</svg>
`;
const downIcon = `
<svg
  width="36"
  height="36"
  viewBox="0 0 24 24"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
>
  <path
    d="M11.0001 3.67157L13.0001 3.67157L13.0001 16.4999L16.2426 13.2574L17.6568 14.6716L12 20.3284L6.34314 14.6716L7.75735 13.2574L11.0001 16.5001L11.0001 3.67157Z"
    fill="#fff"
  />
</svg>
`;
const buttonDiv = document.createElement("div");
buttonDiv.style.cssText = `
  position:fixed;
  bottom: 20px;
  tranform: translateX(-50%);
  right: calc(50% - 64px * 3);
  width: 64px;
  height: 64px;
  background-color: #0F69E6;
  border-radius: 100%;
  display: none;
  place-items: center;
  font-size: 20px;
  cursor: pointer;
  z-index: 999999999;
`;
buttonDiv.setAttribute("id", "btn-scroll");
const titleButton = document.createElement("div");
titleButton.style.cssText = `
  background-color: #0F69E6;
  border-radius: 10px 10px 0 10px;
  position: fixed;
  bottom: 84px;
  tranform: translateX(-50%);
  right: calc(50% - 120px);
  padding: 10px;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  display: none;
  z-index: 999999999;
  `;
document.body.appendChild(titleButton);
document.body.appendChild(buttonDiv);

// buttonDiv.style.display = "grid";
// titleButton.style.display = "block";
// titleButton.textContent = "Bấm để tiếp tục đếm ngược";

let disabledFocusedSite = true;
function run(onsite, key) {
  let cd = onsite;
  let timer = null;

  function countdown() {
    timer = setInterval(() => {
      if (decodeGetTimes(onsite, key).includes(cd)) {
        let isBottom = true;
        disabledFocusedSite = true;
        clearInterval(timer);
        buttonDiv.innerHTML = upIcon;
        buttonDiv.style.display = "grid";
        titleButton.style.display = "block";
        titleButton.textContent = "Bấm để tiếp tục đếm ngược";
        buttonDiv.onclick = async (e) => {
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });
          buttonDiv.innerHTML = downIcon;
          buttonDiv.onclick = () => {};
          titleButton.textContent = "Sau 2 giây sẽ tự vuốt xuống";
          isBottom = !isBottom;
          disabledFocusedSite = false;
          setTimeout(() => {
            if (!isBottom) {
              window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth",
              });
              getCode(key, cd + 1).then((x) => x);
              countdown();
              buttonDiv.style.display = "none";
              titleButton.style.display = "none";
              if (cd === -1) {
                clearInterval(timer);
                getCodeBtn.textContent =
                  "Click link bất kỳ trong trang để nhận code";
              }
            }
          }, 2000);
        };
      }
      if (cd > -1) {
        getCodeBtn.textContent = `Vui lòng đợi giây lát ${cd > -1 ? cd : 0}`;
        cd--;
      }
    }, 1000);
  }
  countdown();
  if (cd) {
    window.onfocus = function () {
      if (!disabledFocusedSite) {
        countdown();
      }
    };
    window.onblur = function () {
      if (timer) clearInterval(timer);
    };
  }
}
