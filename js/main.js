const select = (el, all = false) => {
  el = el.trim();
  if (all) {
    return [...document.querySelectorAll(el)];
  } else {
    return document.querySelector(el);
  }
};

/**
 * Easy event listener function
 */
const on = (type, el, listener, all = false) => {
  let selectEl = select(el, all);
  if (selectEl) {
    if (all) {
      selectEl.forEach((e) => e.addEventListener(type, listener));
    } else {
      selectEl.addEventListener(type, listener);
    }
  }
};

/**
 * Easy on scroll event listener
 */
const onscroll = (el, listener) => {
  el.addEventListener("scroll", listener);
};

/**
 * Scroll with ofset on page load with hash links in the url
 */
window.addEventListener("load", () => {
  if (window.location.hash) {
    if (select(window.location.hash)) {
      scrollto(window.location.hash);
    }
  }
});

/**
 * Menu isotope and filter
 */
window.addEventListener("load", () => {
  let menuContainer = select(".menu-container");
  if (menuContainer) {
    let menuIsotope = new Isotope(menuContainer, {
      itemSelector: ".menu-item",
      layoutMode: "fitRows",
    });

    let menuFilters = select("#menu-flters li", true);

    on(
      "click",
      "#menu-flters li",
      function (e) {
        e.preventDefault();
        menuFilters.forEach(function (el) {
          el.classList.remove("filter-active");
        });
        this.classList.add("filter-active");

        menuIsotope.arrange({
          filter: this.getAttribute("data-filter"),
        });
        menuIsotope.on("arrangeComplete", function () {
          AOS.refresh();
        });
      },
      true
    );
  }
});
