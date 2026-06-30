(() => {
  const header = document.querySelector('[data-site-header]');
  const nav = document.querySelector('[data-primary-nav]');
  const toggle = document.querySelector('[data-nav-toggle]');

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });
  }

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href^="#"],button[data-scroll-target]');
    if (!link) return;

    const targetSelector = link.dataset.scrollTarget || link.getAttribute('href');
    if (!targetSelector || targetSelector === '#') return;

    const target = document.querySelector(targetSelector);
    if (!target) return;

    event.preventDefault();
    const offset = header ? header.offsetHeight : 0;
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });

    if (nav?.classList.contains('is-open')) {
      nav.classList.remove('is-open');
      toggle?.setAttribute('aria-expanded', 'false');
    }
  });

  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const prev = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');
    if (!track || !prev || !next) return;

    const step = () => {
      if (carousel.dataset.carouselStep === 'page') {
        return track.clientWidth;
      }

      return track.querySelector(':scope > *')?.getBoundingClientRect().width || track.clientWidth;
    };
    prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
    next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
  });
})();
