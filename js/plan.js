const floorbtns = document.querySelectorAll(".floorbtns");
const modall = document.querySelector(".mymodal");
const imageinner = document.querySelector(".myinnerimg");
const closebtn = document.querySelector(".close");

floorbtns.forEach((floor) => {
  floor.addEventListener("click", (e) => {
    /* console.log(e.target.parentElement.children[0].src); */
    imageinner.src =
      e.target.parentElement.children[0].src ||
      e.target.parentElement.parentElement.children[0].src;
    modall.classList.remove("d-none");
  });
});

closebtn.addEventListener("click", (e) => {
  e.preventDefault();
  modall.classList.add("d-none");
});
