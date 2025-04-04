<?php 
$page = "add_company"; // ✅ Xác định trang để load CSS phù hợp
$page_css = "add_category.css"; // ✅ Gán file CSS riêng cho trang add_company
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">

    <h1 class="text-center text-warning mb-4">Thêm Hãng Xe</h1>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form method="POST" action="/riderhub/Company/save_company" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên hãng xe</label>
                <input type="text" id="name" name="name"
                    class="form-control border border-warning bg-transparent text-white" maxlength="150" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description"
                    class="form-control border border-warning bg-transparent text-white" required></textarea>
            </div>

            <!-- Thêm phần chọn ảnh -->
            <div class="form-group mb-4">
                <label for="image" class="form-label">Hình ảnh</label>
                <input type="file" id="image" name="image"
                    class="form-control border border-warning bg-black text-warning">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Thêm hãng xe</button>
                <a href="/riderhub/Company" class="btn btn-secondary px-4 shadow-lg">Quay lại danh sách hãng xe</a>
            </div>
        </form>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>
