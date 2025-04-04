<?php
$page = "category";
$page_css = "category.css";
include 'app/views/shares/header.php'; 
?>

<main class="full-width-container">
    <h1 class="text-center text-warning mb-4">Danh Mục Các Dòng Xe (API)</h1>

    <div class="category-container" id="category-list">
        <!-- Danh mục sẽ được load từ API bằng jQuery -->
    </div>

    <!-- Nút thêm danh mục mới (Chỉ admin) -->
    <?php if (SessionHelper::isAdmin()): ?>
        <div class="category-card shadow-lg rounded text-light d-flex align-items-center justify-content-center add-category">
            <a href="/riderhub/Category/add_category_api" class="add-category-btn text-center">
                <i class="bi bi-plus-circle-fill display-4"></i>
                <p class="mt-2">Thêm Loại Xe</p>
            </a>
        </div>
    <?php endif; ?>
</main>

<?php include 'app/views/shares/footer.php'; ?>

<script>
$(document).ready(function () {
    $.get('/riderhub/api/category', function (categories) {
        const $container = $('#category-list');
        $container.empty();

        $.each(categories, function (index, category) {
            const image = category.image ? `/riderhub/${category.image}` : '#222';
            const card = `
                <div class="category-card shadow-lg rounded text-light position-relative overflow-hidden">
                    <div class="category-bg" style="background: url('${image}') no-repeat center center / cover;"></div>
                    <div class="overlay p-3 d-flex flex-column align-items-center justify-content-center">
                        <h3 class="category-title text-warning text-center mb-2">${category.name}</h3>
                        <p class="category-description text-center mb-3">${category.description}</p>
                        <a href="/riderhub/Product/category/${category.id}" class="btn btn-warning">Xem xe</a>
                        <?php if (SessionHelper::isAdmin()): ?>
                        <div class="d-flex justify-content-between w-100 mt-2">
                            <a href="/riderhub/Category/edit_category_api/${category.id}" class="btn btn-warning btn-sm shadow-sm mx-1">Sửa</a>
                            <button class="btn btn-danger btn-sm shadow-sm mx-1" onclick="deleteCategory(${category.id})">Xóa</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            `;
            $container.append(card);
        });
    });
});

function deleteCategory(id) {
    if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
        $.ajax({
            url: `/riderhub/api/category/${id}`,
            type: 'DELETE',
            success: function (data) {
    console.log(data); // ✅ debug
    if (data.message === 'Xóa danh mục thành công') {
        location.reload();
    } else {
        alert("Xóa danh mục thất bại!");
    }
},
error: function (xhr, status, error) {
    console.error("Error deleting category:", error);
    alert("Xóa danh mục thất bại!");
}
        });
    }
}
</script>
