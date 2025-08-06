/**
 * Events Calendar Plugin
 * Frontend Filter & View Toggle JS
 * Author: Satori Graphics Pty Ltd
 * License: GPLv2 or later
 */

document.addEventListener("DOMContentLoaded", function () {
  // ----------------------------------------
  // Cache DOM elements
  // ----------------------------------------
  const gridBtn = document.querySelector("#satori-view-grid");
  const listBtn = document.querySelector("#satori-view-list");
  const archiveContainer = document.querySelector(".satori-events-archive-container");
  const filterForm = document.querySelector("#satori-filter-form");

  // ----------------------------------------
  // View toggle: Grid/List buttons
  // ----------------------------------------
  if (gridBtn && listBtn && archiveContainer) {
    gridBtn.addEventListener("click", () => {
      // Add grid view class, remove list view class
      archiveContainer.classList.add("satori-events-view-grid");
      archiveContainer.classList.remove("satori-events-view-list");

      // Update ARIA pressed state
      gridBtn.setAttribute("aria-pressed", "true");
      listBtn.setAttribute("aria-pressed", "false");
    });

    listBtn.addEventListener("click", () => {
      // Add list view class, remove grid view class
      archiveContainer.classList.add("satori-events-view-list");
      archiveContainer.classList.remove("satori-events-view-grid");

      // Update ARIA pressed state
      listBtn.setAttribute("aria-pressed", "true");
      gridBtn.setAttribute("aria-pressed", "false");
    });
  }

  // ----------------------------------------
  // Filter form submission handler (stub)
  // ----------------------------------------
  if (filterForm) {
    filterForm.addEventListener("submit", function (e) {
      e.preventDefault();
      // TODO: AJAX filter implementation goes here

      // Fallback: submit form normally if AJAX not ready
      filterForm.submit();
    });
  }
});
