<?php 
$page_css = "category.css"; 
$page = "category"; // ✅ Xác định trang danh mục xe
include 'app/views/shares/header.php'; 
?>

<main class="full-width-container">
    <h1 class="text-center text-warning mb-4">Danh Mục Các Dòng Xe</h1>

    <div class="category-container">
        <?php foreach ($categories as $category): ?>
        <div class="category-card shadow-lg rounded text-light position-relative overflow-hidden">
            <!-- Hình ảnh nền -->
            <div class="category-bg" 
                 style="background: <?php echo $category->image ? "url('/riderhub/{$category->image}') no-repeat center center / cover" : '#222'; ?>;">
            </div>

            <!-- Lớp overlay -->
            <div class="overlay p-3 d-flex flex-column align-items-center justify-content-center">
                <h3 class="category-title text-warning text-center mb-2">
                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                </h3>
                <p class="category-description text-center mb-3">
                    <?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <a href="/riderhub/Product/category/<?php echo $category->id; ?>" class="btn btn-warning">Xem xe</a>

                <!-- 🛠 Thêm nút "Sửa" và "Xóa" chỉ hiển thị với admin -->
                <?php if (SessionHelper::isAdmin()): ?>
                    <div class="d-flex justify-content-between w-100">
                        <a href="/riderhub/Category/edit/<?php echo $category->id; ?>" class="btn btn-warning btn-sm shadow-sm mx-1">Sửa</a>
                        <a href="/riderhub/Category/delete/<?php echo $category->id; ?>" class="btn btn-danger btn-sm shadow-sm mx-1" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">Xóa</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Ô thêm danh mục mới chỉ hiển thị với admin -->
        <?php if (SessionHelper::isAdmin()): ?>
            <div class="category-card shadow-lg rounded text-light d-flex align-items-center justify-content-center add-category">
                <a href="/riderhub/Category/add_category" class="add-category-btn text-center">
                    <i class="bi bi-plus-circle-fill display-4"></i>
                    <p class="mt-2">Thêm Loại Xe</p>
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>
