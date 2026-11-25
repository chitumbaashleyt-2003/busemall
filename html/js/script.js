// Apply the shop selected from the Home or Shops page.
const selectedShop = new URLSearchParams(
    window.location.search
).get("shop");

if (
    shopFilter &&
    [...shopFilter.options].some(option => option.value === selectedShop)
) {
    shopFilter.value = selectedShop;
}

filterProducts();