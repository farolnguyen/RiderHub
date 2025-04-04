<?php
$page = "edit_category";
$page_css = "edit_category.css";
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Sửa Danh Mục Xe (API)</h1>

    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form id="edit-category-form" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="existing_image" name="existing_image">

            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" id="name" name="name" class="form-control border border-warning bg-transparent text-white" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description" class="form-control border border-warning bg-transparent text-white" maxlength="150" required></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="image" class="form-label">Hình ảnh (không bắt buộc)</label>
                <input type="file" id="image" name="image" class="form-control border border-warning bg-black text-warning">
                <div id="current-image-preview" class="text-center mt-3"></div>
            </div>

            <div class="form-group mb-3" id="delete-image-container" style="display: none;">
                <label>
                    <input type="checkbox" name="delete_image" value="1"> Xoá hình ảnh hiện tại
                </label>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Lưu thay đổi</button>
                <a href="/riderhub/Category/list_category_api" class="btn btn-secondary px-4 shadow-lg">Quay lại danh mục</a>
            </div>
        </form>
    </div>
</main>

<script>
$(document).ready(function () {
    const categoryId = <?= json_encode($editId) ?>; // Lấy ID danh mục từ PHP

    // Lấy thông tin danh mục
    $.get(`/riderhub/api/category/${categoryId}`, function (data) {
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        $('#existing_image').val(data.image);

        if (data.image) {
            $('#current-image-preview').html(`
                <p class="text-warning">Hình ảnh hiện tại:</p>
                <img src="/riderhub/${data.image}" class="img-thumbnail border border-warning" style="max-width: 150px;">
            `);
        }
    });

    // Gửi yêu cầu PUT
    $('#edit-category-form').on('submit', function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        const jsonData = {
            id: $('#id').val(),
            name: $('#name').val(),
            description: $('#description').val(),
            existing_image: $('#existing_image').val(),  // Đảm bảo lấy ảnh cũ
            delete_image: $('#delete_image').is(":checked") ? "1" : "0"  // Xóa ảnh nếu cần
        };

        // Kiểm tra các giá trị trong form trước khi gửi
        const name = $('#name').val();
        const description = $('#description').val();

        if (!name || !description) {
            alert("Tên và mô tả không được để trống!");
            return; // Nếu thiếu tên hoặc mô tả, ngừng gửi yêu cầu
        }

        // Gửi yêu cầu PUT với dữ liệu JSON
        $.ajax({
            url: `/riderhub/api/category/${categoryId}`,
            method: 'PUT',  // Sử dụng PUT
            contentType: 'application/json',  // Chỉ định gửi dữ liệu dưới dạng JSON
            data: JSON.stringify(jsonData),  // Chuyển dữ liệu thành JSON
            success: function (res) {
                if (res.message === 'Danh mục đã được cập nhật thành công') {
                    window.location.href = '/riderhub/Category/list_category_api';
                } else {
                    alert('Cập nhật danh mục thất bại');
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText); // Log lỗi để kiểm tra chi tiết
                alert('Lỗi khi gửi yêu cầu cập nhật danh mục.');
            }
        });
    });
});


</script>


<?php include 'app/views/shares/footer.php'; ?>
