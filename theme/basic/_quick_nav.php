<?php
if (!defined('_GNUBOARD_'))
  exit; // 개별 페이지 접근 불가
?>

<style>
  /* .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  } */

  /* .scrollbar-hide::-webkit-scrollbar {
    display: none;
  } */
</style>

<section aria-label="카테고리 메뉴" class="px-4 pt-[10px]">
  <div class="relative">
    <div
      class="quick-nav-hint pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 z-10 w-20 h-full bg-linear-to-l from-white/80 to-white/10 blur">
    </div>

    <div class="quick-nav-scroll overflow-x-auto scrollbar-hide">
      <div class="flex w-max items-start gap-7">
        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-black text-white">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M30.841 9.98216C29.8222 10.652 28.5966 11.04 27.2825 11.04C23.7093 11.04 20.8153 8.17021 20.8153 4.63052C20.8153 3.32738 21.2066 2.11942 21.8784 1.10547C20.0549 0.391672 18.0653 0 15.9871 0C7.15747 0 0 7.09403 0 15.8499C0 24.6058 7.15747 31.7035 15.9871 31.7035C24.8167 31.7035 31.9742 24.6058 31.9742 15.8499C31.9742 13.7744 31.5718 11.7941 30.841 9.98216ZM15.9797 20.3889C13.4512 20.3889 11.3988 18.3537 11.3988 15.8463C11.3988 13.3388 13.4512 11.3036 15.9797 11.3036C18.5083 11.3036 20.5606 13.3388 20.5606 15.8463C20.5606 18.3537 18.512 20.3889 15.9797 20.3889Z"
                fill="white" />
            </svg>
          </span>
          <span class="text-[12px]">전체</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M58.6728 79.8218C58.6728 83.6307 59.8154 87.1972 61.7891 90.1404C57.2358 91.7852 52.3362 92.6681 47.2288 92.6681C31.3354 92.6681 17.4676 84.0981 10.0576 71.3384C17.4676 58.596 31.3354 50.026 47.2288 50.026C58.3784 50.026 68.5412 54.2504 76.159 61.1756C66.3944 61.7989 58.6728 69.9014 58.6728 79.8218Z"
                fill="currentColor" />
              <path
                d="M29.9508 24.5931C29.9508 15.0535 37.6724 7.33191 47.1946 7.33191C56.7168 7.33191 64.4558 15.0535 64.4558 24.5931C64.4558 34.1326 56.7168 41.8369 47.1946 41.8369C37.6724 41.8369 29.9508 34.1152 29.9508 24.5931Z"
                fill="currentColor" />
              <path
                d="M82.5482 68.4471C80.9901 67.7373 79.2414 67.3391 77.4063 67.3391C70.481 67.3391 64.8716 72.9486 64.8716 79.8738C64.8716 82.7824 65.8584 85.4659 67.5378 87.5954C69.8231 90.5213 73.3896 92.4085 77.4063 92.4085C84.3315 92.4085 89.9409 86.799 89.9409 79.8738C89.9409 74.7837 86.9111 70.4035 82.5482 68.4471ZM82.15 86.4354L77.5621 84.3752L72.8875 86.2104L73.0953 84.0462L73.3723 81.155L70.1694 77.2249L75.0863 76.1688L77.7698 71.9098L80.3149 76.3073L80.8862 76.4631L85.1798 77.6058L81.8384 81.3627L82.15 86.4354Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">인플루언서</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path d="M83.2228 64.6848L65.1655 82.7421L71.2254 88.802L89.2827 70.7447L83.2228 64.6848Z" fill="black" />
              <path
                d="M70.3215 52.25V32.4265L57.4926 11.1833H53.1816V70.6884L60.9725 79.016L79.0301 60.9585L70.3215 52.25Z"
                fill="currentColor" />
              <path d="M16.7769 64.6995L10.717 70.7594L28.7743 88.8166L34.8342 82.7567L16.7769 64.6995Z" fill="black" />
              <path
                d="M46.8262 11.1833V70.6884L39.0353 79.016L20.9951 60.9585L29.6863 52.25V32.4265L42.5326 11.1833H46.8262Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">종교</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M76.783 50.9695C74.9478 52.1814 72.7317 52.9086 70.3598 52.9086C67.0357 52.9086 64.0405 51.5235 61.911 49.2902C59.92 47.1953 58.6908 44.3559 58.6908 41.2396C58.6908 38.8677 59.4006 36.6689 60.6125 34.8337C57.3231 33.5353 53.7393 32.8081 49.9996 32.8081C34.0716 32.8081 21.1733 45.7063 21.1733 61.6344C21.1733 68.802 23.7876 75.3637 28.1159 80.3845C33.4137 86.5479 41.2565 90.4607 49.9996 90.4607C57.2884 90.4607 63.954 87.7598 69.0267 83.2757C70.0482 82.3755 71.0177 81.3886 71.9007 80.3671C76.2116 75.329 78.8259 68.7847 78.8259 61.6344C78.8259 57.8601 78.0988 54.259 76.783 50.9522V50.9695ZM49.9996 69.91C45.429 69.91 41.7413 66.205 41.7413 61.6517C41.7413 57.0984 45.4463 53.3934 49.9996 53.3934C54.553 53.3934 58.258 57.0984 58.258 61.6517C58.258 66.205 54.553 69.91 49.9996 69.91Z"
                fill="currentColor" />
              <path
                d="M49.9655 26.5408C57.0812 26.5408 63.6948 28.6703 69.235 32.3233L75.7447 9.53931H24.2556L30.8519 32.2194C36.3575 28.6356 42.9018 26.5408 49.9655 26.5408Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">챌린지</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M11.4346 20.2474V79.7525H24.3155L32.8162 53.9906H67.0269L75.7354 79.7525H88.5644V20.2474H11.4346ZM28.5746 24.4199C30.9638 24.4199 32.9028 26.359 32.9028 28.7482C32.9028 31.1374 30.9638 33.0764 28.5746 33.0764C26.1854 33.0764 24.2463 31.1374 24.2463 28.7482C24.2463 26.359 26.1854 24.4199 28.5746 24.4199ZM20.0565 41.5945C17.6673 41.5945 15.7282 39.6554 15.7282 37.2662C15.7282 34.877 17.6673 32.9379 20.0565 32.9379C22.4457 32.9379 24.3848 34.877 24.3848 37.2662C24.3848 39.6554 22.4457 41.5945 20.0565 41.5945ZM28.5746 50.0952C26.1854 50.0952 24.2463 48.1561 24.2463 45.7669C24.2463 43.3777 26.1854 41.4387 28.5746 41.4387C30.9638 41.4387 32.9028 43.3777 32.9028 45.7669C32.9028 48.1561 30.9638 50.0952 28.5746 50.0952ZM37.0752 41.5945C34.686 41.5945 32.747 39.6554 32.747 37.2662C32.747 34.877 34.686 32.9379 37.0752 32.9379C39.4644 32.9379 41.4035 34.877 41.4035 37.2662C41.4035 39.6554 39.4644 41.5945 37.0752 41.5945ZM84.1842 40.9366H77.3282V47.7926H70.1952V40.9366H63.3392V33.8036H70.1952V26.9476H77.3282V33.8036H84.1842V40.9366Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">게임</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M89.885 34.347C83.6177 19.527 68.0532 6.2998 48.0566 7.70216C37.5476 8.42931 28.1293 12.7056 21.1002 19.4751C12.4956 27.768 7.44014 39.6102 8.16729 52.4565C8.91175 65.3201 15.3176 76.5217 24.8398 83.7585C32.5961 89.6623 42.4299 92.9345 52.9389 92.3285C58.4791 92.0169 63.7076 90.6318 68.4341 88.4157C66.1488 85.3513 64.833 81.4559 65.0581 77.2315C65.4909 68.8346 70.304 64.0736 78.4065 61.8229C87.4093 59.3125 95.8754 48.4745 89.9024 34.3297L89.885 34.347ZM26.8308 22.4875C31.0206 22.2451 34.6044 25.4481 34.8468 29.6205C35.0891 33.8103 31.8862 37.3941 27.7137 37.6365C23.524 37.8789 19.9402 34.6759 19.6978 30.5035C19.4554 26.3137 22.6583 22.7299 26.8308 22.4875ZM14.677 57.4253C11.5433 54.6379 11.2663 49.8422 14.0537 46.7085C16.8411 43.5749 21.6369 43.2979 24.7705 46.0853C27.9042 48.8727 28.1812 53.6684 25.3938 56.8021C22.6064 59.9357 17.8107 60.2127 14.677 57.4253ZM30.1549 80.0016C25.9651 80.244 22.3813 77.041 22.139 72.8686C21.8966 68.6788 25.0995 65.095 29.2719 64.8526C33.4617 64.6103 37.0455 67.8132 37.2879 71.9856C37.5303 76.1754 34.3274 79.7592 30.1549 80.0016ZM53.6141 25.1191C50.8267 28.2528 46.031 28.5298 42.8973 25.7424C39.7637 22.955 39.4867 18.1593 42.2741 15.0256C45.0615 11.8919 49.8572 11.6149 52.9909 14.4023C56.1245 17.1897 56.4015 21.9855 53.6141 25.1191ZM70.0616 35.2126C65.8718 35.455 62.288 32.2521 62.0456 28.0797C61.8032 23.8899 65.0061 20.3061 69.1786 20.0637C73.3684 19.8213 76.9522 23.0242 77.1945 27.1967C77.4369 31.3865 74.234 34.9703 70.0616 35.2126Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">취미</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M71.5201 9.80774H28.6702L11.5303 26.8092V48.0177L49.9999 90.1924L88.4696 48.0177V26.8092L71.5374 9.80774H71.5201ZM50.0692 53.8003C43.4382 53.8003 38.0539 48.4159 38.0539 41.785C38.0539 35.1541 43.4382 29.7697 50.0692 29.7697C56.7001 29.7697 62.0844 35.1541 62.0844 41.785C62.0844 48.4159 56.7001 53.8003 50.0692 53.8003Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">이념・가치</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path
                d="M75.7015 47.8705V35.128H54.3024V26.6273H67.1315V13.8849H45.7325V35.128H24.3335V47.8705H11.4353V86.1151H88.5651V47.8705H75.6668H75.7015ZM50.1127 70.9834C43.4125 70.9834 37.9935 65.5471 37.9935 58.8643C37.9935 52.1814 43.4298 46.7451 50.1127 46.7451C56.7955 46.7451 62.2318 52.1814 62.2318 58.8643C62.2318 65.5471 56.7955 70.9834 50.1127 70.9834Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">학교・동문</span>
        </a>

        <a href="#" class="shrink-0 flex flex-col items-center gap-1 text-center">
          <span class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white text-black">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8.5 h-full">
              <path d="M87.7161 88.2532H70.0049V24.5065L87.7161 33.0072V88.2532Z" fill="currentColor" />
              <path
                d="M63.7033 11.7467L12.2834 24.4891V88.2359H29.4234V67.062C29.4234 62.3355 33.2669 58.492 37.9934 58.492C42.7199 58.492 46.5633 62.3355 46.5633 67.062V88.2359H63.7033V11.7467Z"
                fill="currentColor" />
            </svg>
          </span>
          <span class="text-[12px]">회사・협회</span>
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  $(function () {
    const $scroll = $(".quick-nav-scroll");
    const $hint = $(".quick-nav-hint");

    if (!$scroll.length || !$hint.length) {
      return;
    }

    function toggleQuickNavHint() {
      const scrollEl = $scroll.get(0);

      if (!scrollEl) {
        return;
      }

      const hasHorizontalScroll = scrollEl.scrollWidth > scrollEl.clientWidth;

      $hint.toggleClass("hidden", !hasHorizontalScroll);
    }

    toggleQuickNavHint();
    $(window).on("resize", toggleQuickNavHint);
  });
</script>