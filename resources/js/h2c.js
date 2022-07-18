import html2canvas from "html2canvas";

let input = undefined;
let output = undefined;

function init() {
  input = document.getElementById("i");
  output = document.getElementById("o");
  const options = {
    allowTaint: false,
    imageTimeout: 25000,
    useCORS: true,
  };
  html2canvas(input, options).then((canvas) => {
    const dataUrl = canvas.toDataURL();
    const imageEle = document.createElement("img");
    imageEle.src = dataUrl;
    output.appendChild(imageEle);
  });
  input.innerHTML = "";
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", init);
} else {
  init();
}
