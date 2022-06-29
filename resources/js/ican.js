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
        let cd = 5;
        // let cd = rs.onsite;
        // catch error here if $mission is null and not countdown, return "Trang web này chưa được duyệt để chạy traffic. Vui lòng đợi một thời gian hoặc liên hệ với admin"
        const x = setInterval(() => {
            countdown.textContent = cd;
            cd--;
            if (cd < 0) {
                clearInterval(x);
                getCode();
            }
        }, 1000);
    } catch (error) {
        console.log(error);
    }
}

async function getCode() {
    try {
        const code = document.createElement("p");
        const rs = await fetch(
            "http://localhost:8000/generate-code",
            options
        ).then((response) => response.json());
        code.textContent = rs;
        mainEle.appendChild(code);
    } catch (error) {
        console.log(error);
    }
}

const mainEle = document.getElementById("canihelpu");
const countdown = document.getElementById("countdown");
getTimeOnsite();
