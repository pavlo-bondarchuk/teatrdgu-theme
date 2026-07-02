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

    let swipeStartX = 0;
    let swipeStartY = 0;
    let swiping = false;
    let pointerId = null;

    slider.addEventListener('pointerdown', (event) => {
      if (event.button !== undefined && event.button !== 0) {
        return;
      }

      swipeStartX = event.clientX;
      swipeStartY = event.clientY;
      swiping = true;
      pointerId = event.pointerId;
    });

    slider.addEventListener('pointerup', (event) => {
      if (!swiping || (pointerId !== null && event.pointerId !== pointerId)) {
        return;
      }

      const deltaX = event.clientX - swipeStartX;
      const deltaY = event.clientY - swipeStartY;
      swiping = false;
      pointerId = null;

      if (Math.abs(deltaX) < 48 || Math.abs(deltaX) < Math.abs(deltaY) * 1.4) {
        return;
      }

      show(deltaX > 0 ? current - 1 : current + 1);
    });

    slider.addEventListener('pointercancel', () => {
      swiping = false;
      pointerId = null;
    });

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      window.setInterval(() => show(current + 1), 6500);
    }
  });
})();
