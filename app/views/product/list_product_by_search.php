<?php 
$page_css = "list_product_by_category.css"; // CSS riêng cho trang tìm kiếm
$page = "list_product_by_search"; 
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 main-content">
    <h1 class="text-center text-warning mb-4">Tìm Kiếm Xe</h1>

    <!-- Form tìm kiếm và lọc -->
    <form method="GET" action="/riderhub/Product/search" class="d-flex flex-column align-items-center mb-4">
        <!-- Thanh tìm kiếm -->
        <input type="text" name="search" class="form-control search-input mb-3" placeholder="Tìm kiếm sản phẩm..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>" />

        <div class="d-flex justify-content-center mb-4">
            <!-- Bộ lọc theo hãng xe -->
            <select name="company_id" class="form-control filter-select mr-2">
                <option value="">Chọn Hãng Xe</option>
                <?php foreach ($companies as $company): ?>
                    <option value="<?php echo $company->id; ?>" 
                        <?php echo (isset($_GET['company_id']) && $_GET['company_id'] == $company->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($company->name, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Bộ lọc theo danh mục xe -->
            <select name="category_id" class="form-control filter-select mr-2">
                <option value="">Chọn Danh Mục Xe</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>" 
                        <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Bộ lọc theo giá -->
            <input type="number" name="min_price" class="form-control filter-select" placeholder="Giá từ" 
                value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>" />

            <input type="number" name="max_price" class="form-control filter-select" placeholder="Giá đến" 
                value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>" />
        </div>

        <!-- Nút tìm kiếm -->
        <button type="submit" class="btn btn-warning search-btn">Tìm kiếm</button>
    </form>

    <!-- Hiển thị kết quả tìm kiếm -->
    <?php if (empty($products)): ?>
        <p class="no-products">🚨 Không tìm thấy xe nào!</p>
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
                            <?php if (SessionHelper::isAdmin()): ?>
                                <a href="/riderhub/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm shadow-sm">Sửa</a>
                                <a href="/riderhub/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?');">Xóa</a>
                            <?php endif; ?>

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
</main>

<?php include 'app/views/shares/footer.php'; ?>
