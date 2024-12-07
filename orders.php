//dummy data test

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .order { margin-bottom: 20px; }
        .order-items { display: flex; gap: 10px; }
        .order-item { width: 100px; }
    </style>
</head>
<body>

<h1>Orders</h1>
<div id="orders"></div>

<script>
    $.ajax({
        url: 'fetch_orders.php',
        method: 'GET',
        success: function(response) {
            const orders = JSON.parse(response);
            const ordersContainer = $('#orders');

            orders.forEach(order => {
                const orderElem = $('<div class="order"></div>');
                const subtotal = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let itemsHtml = '<div class="order-items">';
                
                order.items.forEach(item => {
                    itemsHtml += `
                        <div class="order-item">
                            <img src="${item.image}" alt="Item" width="100">
                            <p>Price: ${item.price}</p>
                            <p>Quantity: ${item.quantity}</p>
                        </div>
                    `;
                });

                itemsHtml += '</div>';

                orderElem.append(`
                    <h3>Order #${order.id}</h3>
                    ${itemsHtml}
                    <p>Subtotal: ${subtotal}</p>
                    <textarea class="review-text" placeholder="Write a review..."></textarea>
                    <button class="submit-review" data-order-id="${order.id}">Submit Review</button>
                    <div class="reviews" data-order-id="${order.id}"></div>
                `);

                ordersContainer.append(orderElem);

                loadReviews(order.id);
            });

            $('.submit-review').on('click', function() {
                const orderId = $(this).data('order-id');
                const reviewText = $(this).siblings('.review-text').val();
                submitReview(orderId, reviewText);
            });
        }
    });

    function submitReview(orderId, reviewText) {
        $.ajax({
            url: 'submit_review.php',
            method: 'POST',
            data: {
                order_id: orderId,
                review: reviewText
            },
            success: function() {
                loadReviews(orderId);
            }
        });
    }

    function loadReviews(orderId) {
        $.ajax({
            url: 'fetch_reviews.php',
            method: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                const reviews = JSON.parse(response);
                const reviewsContainer = $(`.reviews[data-order-id="${orderId}"]`);
                reviewsContainer.empty();

                reviews.forEach(review => {
                    reviewsContainer.append(`<p>${review.review}</p>`);
                });
            }
        });
    }
</script>

</body>
</html>