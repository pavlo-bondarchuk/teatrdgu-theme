(() => {
  const root = document.querySelector("[data-repertoire]");

  if (!root) {
    return;
  }

  const buttons = Array.from(root.querySelectorAll("[data-repertoire-filter]"));
  const cards = Array.from(root.querySelectorAll("[data-repertoire-card]"));
  const empty = root.querySelector("[data-repertoire-empty]");

  const setActiveFilter = (key) => {
    const normalizedKey = String(key || "all");
    let visibleCount = 0;

    buttons.forEach((button) => {
      const isActive = button.dataset.repertoireFilter === normalizedKey;

      button.classList.toggle("is-active", isActive);
      button.setAttribute("aria-pressed", isActive ? "true" : "false");
    });

    cards.forEach((card) => {
      const genres = (card.dataset.repertoireGenres || "")
        .split(" ")
        .filter(Boolean);

      const isVisible =
        normalizedKey === "all" || genres.includes(normalizedKey);

      card.hidden = !isVisible;

      if (isVisible) {
        visibleCount += 1;
      }
    });

    if (empty) {
      empty.hidden = visibleCount > 0;
    }
  };

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      setActiveFilter(button.dataset.repertoireFilter || "all");
    });
  });
})();
