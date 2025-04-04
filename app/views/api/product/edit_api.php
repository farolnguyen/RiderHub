<?php
$page = "edit";
$page_css = "add.css"; // Dùng lại CSS của add
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Sửa Sản Phẩm</h1>

    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form id="edit-product-form">
            <input type="hidden" id="id" name="id">

            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control border border-warning bg-transparent text-white" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description" class="form-control border border-warning bg-transparent text-white" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="price" class="form-label">Giá (VNĐ)</label>
                <input type="number" id="price" name="price" class="form-control border border-warning bg-transparent text-white" step="1000" required min="1000">
            </div>

            <div class="form-group mb-4">
                <label for="category_id" class="form-label">Danh mục</label>
                <select id="category_id" name="category_id" class="form-select border border-warning bg-black text-warning" required></select>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Lưu thay đổi</button>
                <a href="/riderhub/Product/list_api" class="btn btn-secondary px-4 shadow-lg">Quay lại danh sách</a>
            </div>
        </form>
    </div>
</main>

<script>
$(document).ready(function () {
    const productId = <?= json_encode($editId) ?>;

    // Lấy thông tin sản phẩm
    $.get(`/riderhub/api/product/${productId}`, function (data) {
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        $('#price').val(data.price);
        $('#category_id').val(data.category_id);
    });

    // Lấy danh mục sản phẩm
    $.get('/riderhub/api/category', function (categories) {
        const $categorySelect = $('#category_id');
        categories.forEach(category => {
            const option = $('<option>').val(category.id).text(category.name);
            $categorySelect.append(option);
        });
    });

    // Gửi cập nhật
    $('#edit-product-form').on('submit', function (e) {
        e.preventDefault();

        const jsonData = {
            id: $('#id').val(),
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val()
        };

        $.ajax({
            url: `/riderhub/api/product/${jsonData.id}`,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(jsonData),
            success: function (response) {
                if (response.message === 'Cập nhật sản phẩm thành công') {
                    window.location.href = '/riderhub/Product/list_api';
                } else {
                    alert('Cập nhật sản phẩm thất bại');
                }
            },
            error: function (xhr) {               
                window.location.href = '/riderhub/Product/list_api';
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
