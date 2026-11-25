document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", () => {
            const isExpanded = menuToggle.getAttribute("aria-expanded") === "true";
            menuToggle.setAttribute("aria-expanded", String(!isExpanded));
            mainNav.classList.toggle("open", !isExpanded);
        });
    }

    const searchInput = document.getElementById("searchInput");
    const shopFilter = document.getElementById("shopFilter");
    const productCards = [...document.querySelectorAll(".product-card")];
    const emptyMessage = document.getElementById("emptyMessage");

    if (!searchInput || !shopFilter) {
        return;
    }

    const selectedShop = new URLSearchParams(window.location.search).get("shop");
    if ([...shopFilter.options].some(option => option.value === selectedShop)) {
        shopFilter.value = selectedShop;
    }

    const filterProducts = () => {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const selectedShop = shopFilter.value;
        let visibleProducts = 0;

        productCards.forEach(card => {
            const matchesSearch =
                (card.dataset.name || "").includes(searchTerm) ||
                (card.dataset.description || "").includes(searchTerm);
            const matchesShop =
                !selectedShop || card.dataset.shop === selectedShop;
            const isVisible = matchesSearch && matchesShop;

            card.hidden = !isVisible;
            if (isVisible) {
                visibleProducts++;
            }
        });

        if (emptyMessage) {
            emptyMessage.hidden = visibleProducts > 0;
        }
    };

    searchInput.addEventListener("input", filterProducts);
    shopFilter.addEventListener("change", filterProducts);
    filterProducts();
});