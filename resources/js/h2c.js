import domtoimage from "dom-to-image";

let input = undefined;
let output = undefined;

const imgEle = document.getElementById("mission-img");
const parentImgEle = document.getElementById("parent-mission-img");
function init() {
  const imgW = imgEle.width;
  const imgH = imgEle.height;
  const imgSrc = imgEle.src;
  const offsetImgTop = parentImgEle.offsetTop;
  const offsetImgLeft = parentImgEle.offsetLeft;
  imgEle.remove();
  const temp = document.createElement("canvas");
  temp.width = imgW;
  temp.height = imgH;
  parentImgEle.appendChild(temp);
  input = document.getElementById("i");
  output = document.getElementById("o");
  domtoimage
    .toPng(input, { cacheBust: true })
    .then(function (dataUrl) {
      input.innerHTML = "";
      var img = new Image();
      img.src = dataUrl;
      const imgMission = document.createElement("img");
      imgMission.src = imgSrc;
      imgMission.width = imgW;
      imgMission.height = imgH;
      imgMission.style.objectFit = "contain";
      imgMission.style.position = "absolute";
      imgMission.style.top = offsetImgTop;
      imgMission.style.left = offsetImgLeft;
      output.appendChild(img);
      output.appendChild(imgMission);
    })
    .catch(function (error) {
      console.error("oops, something went wrong!", error);
    });
}
document.addEventListener("DOMContentLoaded", init);
window.addEventListener("resize", () => {
  window.location.reload();
});
