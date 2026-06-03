document.addEventListener('DOMContentLoaded', () => {
    const flashAlerts = document.querySelectorAll('.alert');
    flashAlerts.forEach((alert) => {
        setTimeout(() => {
            alert.classList.add('fade');
        }, 4000);
    });

    const saleProductName = document.getElementById('saleProductName');
    const saleProductStock = document.getElementById('saleProductStock');
    const saleProductId = document.getElementById('saleProductId');
    const saleQuantity = document.getElementById('saleQuantity');
    const saleSubmit = document.getElementById('saleSubmit');
    const saleAlert = document.getElementById('saleAlert');
    const saleMinus = document.getElementById('saleMinus');
    const salePlus = document.getElementById('salePlus');

    function updateQuantity(amount) {
        const max = parseInt(saleQuantity.max, 10) || 0;
        let value = parseInt(saleQuantity.value, 10) || 1;
        value += amount;
        if (value < 1) {
            value = 1;
        }
        if (max > 0 && value > max) {
            value = max;
        }
        saleQuantity.value = value;
    }

    document.querySelectorAll('.product-sale-link').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const name = link.dataset.productName;
            const stock = parseInt(link.dataset.productStock, 10) || 0;
            const id = link.dataset.productId;

            saleProductName.textContent = name;
            saleProductStock.textContent = stock;
            saleProductId.value = id;
            saleQuantity.value = stock > 0 ? 1 : 0;
            saleQuantity.max = stock;
            saleSubmit.disabled = stock <= 0;
            saleAlert.classList.toggle('d-none', stock > 0);
            saleAlert.textContent = stock > 0 ? '' : 'Stock indisponible pour la vente.';
        });
    });

    if (saleMinus) {
        saleMinus.addEventListener('click', () => updateQuantity(-1));
    }
    if (salePlus) {
        salePlus.addEventListener('click', () => updateQuantity(1));
    }
});
