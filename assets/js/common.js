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

    const items = Array.from(track.children);
    if (!items.length) return;

    let currentPage = 0;

    const perView = () => {
      const mobile = Number(carousel.dataset.carouselMobile || 1);
      const tablet = Number(carousel.dataset.carouselTablet || mobile);
      const desktop = Number(carousel.dataset.carouselDesktop || tablet);

      if (window.innerWidth < 640) {
        return mobile;
      }

      if (window.innerWidth < 1024) {
        return tablet;
      }

      return desktop;
    };

    const render = () => {
      const count = perView();
      const pageCount = Math.max(1, Math.ceil(items.length / count));
      currentPage = Math.min(currentPage, pageCount - 1);
      track.style.setProperty('--dgut-carousel-cols', String(count));

      items.forEach((item, index) => {
        const isVisible = index >= currentPage * count && index < (currentPage + 1) * count;
        item.hidden = !isVisible;
      });

      const controls = prev.closest('.slider-controls');
      if (controls) {
        controls.hidden = pageCount < 2;
      }
    };

    prev.addEventListener('click', () => {
      const pageCount = Math.max(1, Math.ceil(items.length / perView()));
      currentPage = (currentPage - 1 + pageCount) % pageCount;
      render();
    });
    next.addEventListener('click', () => {
      const pageCount = Math.max(1, Math.ceil(items.length / perView()));
      currentPage = (currentPage + 1) % pageCount;
      render();
    });
    window.addEventListener('resize', render);
    render();
  });
})();
