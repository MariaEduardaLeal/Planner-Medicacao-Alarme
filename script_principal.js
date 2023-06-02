menu = document.querySelector(".menu").querySelectorAll("a");
console.log(menu);

menu.forEach(element => {
    element.addEventListener("click", function(){
    menu.forEach(nav=>nav.classList.remove("active"))

    this.classList.add("active");
    })
});