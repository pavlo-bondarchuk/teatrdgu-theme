(() => {
  const root = document.querySelector("[data-repertoire]");
  if (!root) {
    return;
  }

  const buttons = Array.from(root.querySelectorAll("[data-repertoire-filter]"));
  const cards = Array.from(root.querySelectorAll("[data-repertoire-card]"));
  const empty = root.querySelector("[data-repertoire-empty]");

  const setActiveFilter = (key) => {
    const normalizedKey = key.toLowerCase();
    let visibleCount = 0;

    buttons.forEach((button) => {
      const isActive = button.dataset.repertoireFilter === key;
      button.classList.toggle("is-active", isActive);
      button.setAttribute("aria-pressed", isActive ? "true" : "false");
    });

    cards.forEach((card) => {
      const text = (card.dataset.filterText || "").toLowerCase();
      const isVisible = normalizedKey === "all" || text.includes(normalizedKey);
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
