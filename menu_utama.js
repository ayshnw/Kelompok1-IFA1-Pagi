const header = document.querySelector("header");

window.addEventListener ("scroll",function(){
    header.classList.toggl("sticky",this.window.scrollY > 0);
})