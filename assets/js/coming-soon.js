(() => {
  const video = document.querySelector('.coming-soon__video');
  if (!video) return;

  const showVideo = () => video.classList.add('coming-soon__video--ready');

  if (video.readyState >= HTMLMediaElement.HAVE_FUTURE_DATA) {
    showVideo();
  } else {
    video.addEventListener('canplay', showVideo, { once: true });
  }
})();

