(() => {
  const selectors = {
    component: "[data-opportunities-tabs]",
    tab: '[role="tab"]',
    panel: '[role="tabpanel"]',
  };

  const scrollTabIntoView = (tab) => {
    tab.scrollIntoView({
      behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches
        ? "auto"
        : "smooth",
      block: "nearest",
      inline: "nearest",
    });
  };

  const activateTab = (
    tabs,
    panels,
    activeTab,
    { focusPanel = false, scroll = true } = {},
  ) => {
    const panelId = activeTab.getAttribute("aria-controls");

    tabs.forEach((tab) => {
      const isActive = tab === activeTab;
      tab.classList.toggle("is-active", isActive);
      tab.setAttribute("aria-selected", String(isActive));
      tab.setAttribute("tabindex", isActive ? "0" : "-1");
    });

    panels.forEach((panel) => {
      panel.hidden = panel.id !== panelId;
    });

    if (scroll) {
      scrollTabIntoView(activeTab);
    }

    if (focusPanel) {
      const activePanel = panels.find((panel) => panel.id === panelId);
      activePanel?.focus();
    }
  };

  document.querySelectorAll(selectors.component).forEach((component) => {
    const tabs = Array.from(component.querySelectorAll(selectors.tab));
    const panels = Array.from(component.querySelectorAll(selectors.panel));
    if (!tabs.length || !panels.length) return;

    const initialTab =
      tabs.find((tab) => tab.getAttribute("aria-selected") === "true") ||
      tabs[0];
    activateTab(tabs, panels, initialTab, { scroll: false });

    tabs.forEach((tab, index) => {
      tab.addEventListener("click", () => {
        activateTab(tabs, panels, tab);
      });

      tab.addEventListener("keydown", (event) => {
        let nextIndex = null;

        if (event.key === "ArrowRight") {
          nextIndex = (index + 1) % tabs.length;
        } else if (event.key === "ArrowLeft") {
          nextIndex = (index - 1 + tabs.length) % tabs.length;
        } else if (event.key === "Home") {
          nextIndex = 0;
        } else if (event.key === "End") {
          nextIndex = tabs.length - 1;
        } else if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          activateTab(tabs, panels, tab);
          return;
        }

        if (nextIndex === null) return;

        event.preventDefault();
        tabs.forEach((item, itemIndex) => {
          item.setAttribute("tabindex", itemIndex === nextIndex ? "0" : "-1");
        });
        tabs[nextIndex].focus({ preventScroll: true });
        scrollTabIntoView(tabs[nextIndex]);
      });
    });
  });
})();
