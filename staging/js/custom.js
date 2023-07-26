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

// Select elements here
var playButton = document.getElementById("play");
// Event listener for the play/pause button
playButton.addEventListener("click", function() {
  if (video.paused == true) {
    // Play the video
    video.play();

    // Update the button text to 'Pause'
    // playButton.innerHTML = "Pause";
  } else {
    // Pause the video
    video.pause();

    // Update the button text to 'Play'
    // playButton.innerHTML = "Play";
  }
});
var playButton = document.getElementById("playbtn");
// Event listener for the play/pause button
playButton.addEventListener("click", function() {
  if (videoclip.paused == true) {
    // Play the video
    videoclip.play();

    // Update the button text to 'Pause'
    // playButton.innerHTML = "Pause";
  } else {
    // Pause the video
    videoclip.pause();

    // Update the button text to 'Play'
    // playButton.innerHTML = "Play";
  }
});