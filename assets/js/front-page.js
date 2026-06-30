(() => {
  document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
    const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
    const prev = slider.querySelector('[data-hero-prev]');
    const next = slider.querySelector('[data-hero-next]');
    const controls = slider.querySelector('.dgut-hero__controls');

    if (slides.length < 2) {
      controls?.setAttribute('hidden', 'hidden');
      return;
    }

    let current = 0;
    const show = (index) => {
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('is-active', i === current));
      dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
    };

    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));
    prev?.addEventListener('click', () => show(current - 1));
    next?.addEventListener('click', () => show(current + 1));

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      window.setInterval(() => show(current + 1), 6500);
    }
  });
})();
