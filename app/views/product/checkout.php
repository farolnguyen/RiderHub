<?php
$page = "checkout"; // ✅ Xác định trang để load CSS phù hợp
$page_css = "checkout.css"; // ✅ Gán file CSS riêng cho trang checkout
include 'app/views/shares/header.php';
?>

<main class="container mt-5 flex-grow-1 main-content">
    <h1 class="text-center text-warning mb-4">💳 Thanh Toán</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p class="empty-cart text-center">🚨 Giỏ hàng trống! Hãy thêm sản phẩm trước khi thanh toán.</p>
        <div class="text-center mt-4">
            <a href="/riderhub/Product/" class="btn btn-warning btn-lg">🛍 Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="checkout-container">
            <!-- 📝 Thông tin đơn hàng -->
            <div class="order-summary">
                <h2 class="text-warning">🛍 Đơn Hàng Của Bạn</h2>
                <ul class="order-items">
                    <?php 
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        $subtotal = $item['quantity'] * $item['price'];
                        $total_price += $subtotal;
                    ?>
                    <li>
                        <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> (x<?php echo $item['quantity']; ?>)</span>
                        <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <h3 class="text-warning">Tổng cộng: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</h3>
            </div>

            <!-- 📝 Form thanh toán -->
            <div class="payment-form">
                <h2 class="text-warning">📌 Thông Tin Thanh Toán</h2>
                <form method="POST" action="/riderhub/Product/processCheckout">
                    <div class="form-group">
                        <label for="name">Họ và Tên</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ nhận hàng</label>
                        <textarea id="address" name="address" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">✅ Đặt Hàng</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'app/views/shares/footer.php'; ?>
