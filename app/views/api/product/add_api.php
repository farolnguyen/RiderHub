<?php
$page = "add";
$page_css = "add.css";
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Thêm Sản Phẩm API</h1>

    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form id="add-product-form">
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
                <input type="number" id="price" name="price" class="form-control border border-warning bg-transparent text-white" step="1000" required min="1000" max="9999999999">
            </div>

            <div class="form-group mb-4">
                <label for="category_id" class="form-label">Danh mục</label>
                <select id="category_id" name="category_id" class="form-select border border-warning bg-black text-warning" required>
                    <!-- Danh mục từ API -->
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Thêm sản phẩm</button>
                <a href="/riderhub/Product/list_api" class="btn btn-secondary px-4 shadow-lg">Quay lại danh sách</a>
            </div>
        </form>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>

<script>
$(document).ready(function () {
    // Load danh mục từ API
    $.get('/riderhub/api/category', function (data) {
        const $categorySelect = $('#category_id');
        data.forEach(category => {
            const option = $('<option>').val(category.id).text(category.name);
            $categorySelect.append(option);
        });
    });

    // Gửi dữ liệu thêm sản phẩm
    $('#add-product-form').on('submit', function (e) {
        e.preventDefault();
        
        const jsonData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val()
        };

        $.ajax({
            url: '/riderhub/api/product',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(jsonData),
            success: function (response) {
                if (response.message === 'Tạo sản phẩm thành công') {
                    window.location.href = '/riderhub/Product/listAPI';
                } else {
                    alert('Thêm sản phẩm thất bại');
                }
            },
            error: function () {
                alert('Lỗi khi gửi yêu cầu API.');
            }
        });
    });
});
</script>

