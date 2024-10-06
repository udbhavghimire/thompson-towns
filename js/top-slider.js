const backcontainer = document.querySelector(".hero-container");
const item1s = document.querySelectorAll(".item1");
const car1 = "./image/topcar/img1.png";
const car2 = "./image/topcar/img2.png";
const car3 = "./image/topcar/img3.png";
const car4 = "./image/topcar/img4.png";

let carslider = {
  prev: car1,
  now: car2,
  next: car3,
  nextt: car4,
};

item1s.forEach((item) => {
  item.addEventListener("click", itemClickHandle);
});

function itemClickHandle(e) {
  console.log(e.target.src);
  backcontainer.style.backgroundImage = `url(${e.target.src})`;
  clearInterval(myvar);
}
var myvar = setInterval(function () {
  /* console.log(carslider); */
  var infosave = carslider.prev;
  carslider.prev = carslider.now;
  carslider.now = carslider.next;
  carslider.next = carslider.nextt;
  carslider.nextt = infosave;
  backcontainer.style.backgroundImage = `url(${carslider.now})`;
}, 10000);
