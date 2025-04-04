<?php
$page_css = "list_product_by_category.css";
$page = "list_product_by_category";
include 'app/views/shares/header.php'; 
?>

<?php
$background_image = "/riderhub/uploads/categories/" . $category->name . ".jpg";  
?>
<main class="main-content" style="background: url('<?php echo $background_image; ?>'); 
                            background-repeat: no-repeat;
                            background-position: center center;
                            background-attachment: fixed;
                            margin-top : 0px;
                            background-size: cover;"> 
<div class="container mt-5 flex-grow-1 main-content" >
    <h1 class="text-center text-warning mb-4">
        Danh sách xe - <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
    </h1>   

    <?php if (empty($products)): ?>
        <p class="no-products">🚨 Chưa có xe nào trong danh mục này.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card shadow-lg rounded text-light border border-warning">
                    <img src="/riderhub/<?php echo $product->image ? $product->image : 'images/no-image.png'; ?>" 
                         class="card-img-top border-bottom border-warning" 
                         alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/riderhub/Product/show/<?php echo $product->id; ?>" 
                               class="text-decoration-none text-warning">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>
                        <p class="text-danger font-weight-bold h5">💰 <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>

                        <!-- Xử lý quyền hiển thị nút -->
                        <div class="d-flex justify-content-between">
                            <!-- Nút cho admin: Sửa và Xóa -->
                            <?php if (SessionHelper::isAdmin()): ?>
                                <a href="/riderhub/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm shadow-sm">Sửa</a>
                                <a href="/riderhub/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?');">Xóa</a>
                            <?php endif; ?>

                            <!-- Nút thêm vào giỏ hàng cho tất cả người dùng đã đăng nhập -->
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <a href="/riderhub/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success btn-cart">
                                🛒 Thêm vào giỏ
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    </div>
</main>
<?php include 'app/views/shares/footer.php'; ?>
