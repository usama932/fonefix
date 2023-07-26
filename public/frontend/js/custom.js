/*----- Search Box -----*/
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
      txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        a[i].style.display = "";
      } else {
        a[i].style.display = "none";
      }
    }
  }
  
/* Testimonial Slider */
var swiper = new Swiper(".slide-content", {
  slidesPerView: 3,
  spaceBetween: 25,
  loop: true,
  centerSlide: 'true',
  fade: 'true',
  grabCursor: 'true',
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  breakpoints:{
      0: {
          slidesPerView: 1,
      },
      575: {
          slidesPerView: 2,
      },
      768: {
          slidesPerView: 3,
      },
  },
});
/* Video Player */
var playButton = document.getElementById("play");
// Event listener for the play/pause button
playButton.addEventListener("click", function() {
  if (video.paused == true) {
    // Play the video
    video.play();
  } else {
    // Pause the video
    video.pause();
  }
});
var playBtn = document.getElementById("playbtn");
// Event listener for the play/pause button
playBtn.addEventListener("click", function() {
  if (videoclip.paused == true) {
    // Play the video
    videoclip.play();
  } else {
    // Pause the video
    videoclip.pause();
  }
});
/* Col Hover Effect */
var ele = document.getElementsByClassName("onhover");
$(ele).on('mouseover', function() {
  $(ele).removeClass('shadow');
  $(this).addClass('shadow');
});

// OTP
document.addEventListener("DOMContentLoaded", function(event) {

  function OTPInput() {
  const inputs = document.querySelectorAll('#otp > *[id]');
  for (let i = 0; i < inputs.length; i++) { inputs[i].addEventListener('keydown', function(event) { if (event.key==="Backspace" ) { inputs[i].value='' ; if (i !==0) inputs[i - 1].focus(); } else { if (i===inputs.length - 1 && inputs[i].value !=='' ) { return true; } else if (event.keyCode> 47 && event.keyCode < 58) { inputs[i].value=event.key; if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } else if (event.keyCode> 64 && event.keyCode < 91) { inputs[i].value=String.fromCharCode(event.keyCode); if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } } }); } } OTPInput(); });
  