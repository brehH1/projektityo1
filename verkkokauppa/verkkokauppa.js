document.addEventListener('DOMContentLoaded', function () {
  
  const allCheckbox = document.getElementById('filter-all');
  const categoryCheckboxes = document.querySelectorAll('.category-filter');
  const productCards = document.querySelectorAll('.product-card');

  function applyFilters() {

    const selectedCategories = Array.from(categoryCheckboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.dataset.category);


    if (allCheckbox.checked || selectedCategories.length === 0) {
      productCards.forEach(card => {
        card.style.display = '';
      });
      return;
    }

    productCards.forEach(card => {
      const cat = card.dataset.category;
      if (selectedCategories.includes(cat)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  }

  allCheckbox.addEventListener('change', () => {
    if (allCheckbox.checked) {
      categoryCheckboxes.forEach(cb => cb.checked = false);
    }
    applyFilters();
  });


  categoryCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {

  
      if (cb.checked) {
        allCheckbox.checked = false;
      }

      
      const anyChecked = Array.from(categoryCheckboxes).some(c => c.checked);
      if (!anyChecked) {
        allCheckbox.checked = true;
      }

      applyFilters();
    });
  });

  applyFilters();
});

const searchInput = document.getElementById("search");
const productCards = document.querySelectorAll(".product-card");

searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase();

    productCards.forEach(card => {
        const title = card.querySelector("h2").textContent.toLowerCase();
        const desc = card.querySelector(".product-description").textContent.toLowerCase();
        const category = card.querySelector(".product-category").textContent.toLowerCase();

        if (title.includes(query) || desc.includes(query) || category.includes(query)) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
});

