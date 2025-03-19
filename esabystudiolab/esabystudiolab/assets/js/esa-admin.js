jQuery(document).ready(function ($) {
    $('#products-list').sortable();

    $('#save-order').on('click', function () {
        const order = [];
        $('#products-list .product-item').each(function () {
            order.push($(this).data('id'));
        });

        const visibility = [];
        $('#products-list .product-item input[type="checkbox"]').each(function () {
            visibilityStatus = 0
            if ($(this).is(':checked')) {
                visibilityStatus = 1;
            } else {
                visibilityStatus = 0;
            }
            visibility.push(visibilityStatus);
        });

        const category = $('#product-category-filter').val();

        $.post(ajaxurl, {
            action: 'save_product_order_esa',
            order: order,
            visibility: visibility,
            category: category,
        }, function (response) {
            alert('Order Saved!');
        });
    });

    $('#filter-products').on('click', function () {
        const category = $('#product-category-filter').val();
        window.location.href = `?page=esa-products-screen&category=${category}`;
    });
});
