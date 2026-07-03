<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>도너츠 - 소개 페이지</title>
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style>

.swiper {
  width: 100%;
  /* padding:64px; */
  background-color: #000;

}
.swiper-wrapper {
height: 100vh;
}
.swiper-slide{
    /* padding: 64px; */
    display: flex;
    align-items: center;
    justify-content: center;
}
.swiper-slide img{
     aspect-ratio: 16 / 9;
    height: fit-content;
    max-height: 945px;
}
</style>
</head>
<body class=" w-full ">
    
<div class="swiper opacity-0 translate-y-8 transition-all duration-1000 ease-out !hidden md:!flex" id="hero">
  <!-- Additional required wrapper -->
  <div class="swiper-wrapper ">
    <!-- Slides -->
    <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_001.png">
    </div>
    <div class="swiper-slide"> 
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_002.png">
    </div>
    <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_003.png">
    </div>
    <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_004.png">
    </div>
        <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_005.png">
    </div>
        <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_006.png">
    </div>
        <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_007.png">
    </div>
        <div class="swiper-slide">
        <img class="w-full " src="/theme/basic/ready/imgs/Slide_008.png">
    </div>
  </div>
  <!-- If we need pagination -->
  <!-- <div class="swiper-pagination"></div> -->

  <!-- If we need navigation buttons -->
  <!-- <div class="swiper-button-prev"></div> -->
  <!-- <div class="swiper-button-next"></div> -->

  <!-- If we need scrollbar -->
  <div class="swiper-scrollbar"></div>
</div>
<div class="w-full block md:hidden overflow-y-auto">
  <img  src="/theme/basic/ready/imgs/mobile.png">

</div>
</body>
<script>
    window.addEventListener('DOMContentLoaded', () => {
     const hero = document.getElementById('hero');

    hero.classList.remove('opacity-0', 'translate-y-8');
    hero.classList.add('opacity-100', 'translate-y-0');
});
    const swiper = new Swiper('.swiper', {
  // Optional parameters
  speed: 600,
  direction: 'vertical',
  loop: true,
  mousewheel: true,
     slidesPerView: 1,
        // spaceBetween: 64,

  // If we need pagination
//   pagination: {
//     el: '.swiper-pagination',
//   },

  // Navigation arrows
//   navigation: {
//     nextEl: '.swiper-button-next',
//     prevEl: '.swiper-button-prev',
//   },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  },
});
</script>
</html>


