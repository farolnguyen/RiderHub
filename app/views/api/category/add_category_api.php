<?php
$page = "add_category";
$page_css = "add_category.css";
include 'app/views/shares/header.php';
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Thêm Danh Mục Xe (API)</h1>

    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form id="add-category-form" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" id="name" name="name"
                    class="form-control border border-warning bg-transparent text-white" maxlength="150" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description"
                    class="form-control border border-warning bg-transparent text-white" required></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="image" class="form-label">Hình ảnh</label>
                <input type="file" id="image" name="image"
                    class="form-control border border-warning bg-black text-warning">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Thêm danh mục</button>
                <a href="/riderhub/Category/list_category_api" class="btn btn-secondary px-4 shadow-lg">Quay lại danh mục</a>
            </div>
        </form>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>

<script>
$(document).ready(function () {
    $('#add-category-form').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/riderhub/api/category',
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                if (response.message === 'Tạo danh mục thành công') {
                    window.location.href = '/riderhub/Category/list_category_api';
                } else {
                    alert('Thêm danh mục thất bại!');
                }
            },
            error: function () {
                alert('Đã xảy ra lỗi khi gửi yêu cầu đến API.');
            }
        });
    });
});
</script>
