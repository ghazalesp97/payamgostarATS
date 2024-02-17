//front end serch
function filterjob() {
    var input, filter, container, title, a, txtValue;
    input = document.getElementById("ats-filter");
    filter = input.value.toUpperCase();
    container =  document.querySelectorAll('.ats-job-container');
    title = document.querySelectorAll('.ats-job-container .ats-title');
    for (var i = 0; i < title.length; i++) {
        a = title[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            container[i].style.display = "";
        } else {
            container[i].style.display = "none";
        }
    }
}
function alertresult(){
}


jQuery(document).ready(function($) {

//job accordion
var allPanels = $('.ats-panel') 
  $('.ats-accordion').click(function() {
   $(this).toggleClass("active");
    $(this).next().slideToggle();
    return false;
  });


//apply poppup
const modal = document.querySelector(".apply-modal");
const atsTrigger = document.querySelectorAll(".ats-trigger");
let atsCloseButton = document.querySelector(".close-apply-modal");
let crmIdClicked = document.querySelector("#clickedjob");
let jobTitle = document.querySelector("#jobtitle");
function toggleModal() {
    modal.classList.toggle("show-apply-modal");
}
function windowOnClick(event) {
    if (event.target === modal) {
        toggleModal();
    }
}
if(atsTrigger){
    for (i = 0; i < atsTrigger.length; ++i) {        
        atsTrigger[i].addEventListener("click", function(event){
            toggleModal();
            crmIdClicked.value = event.target.dataset['crmid'];
            jobTitle.value = event.target.dataset['jobtitle'];
        });
      }
}
if(atsCloseButton){
    atsCloseButton.addEventListener("click", toggleModal);
}
window.addEventListener("click", windowOnClick);

//close alert


});

        
        
