<?php
$page = "register"; // ✅ Xác định trang để load CSS phù hợp
$page_css = "register.css"; // ✅ Gán file CSS riêng cho trang đăng ký
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">

    <h1 class="text-center text-warning mb-4">Đăng Ký Tài Khoản Mới</h1>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form đăng ký tài khoản -->
    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form method="POST" action="/riderhub/account/save" class="user">
            <div class="form-group mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" id="username" name="username" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Nhập tên đăng nhập" required>
            </div>

            <div class="form-group mb-3">
                <label for="fullname" class="form-label">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Nhập họ và tên" required>
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-group mb-3">
                <label for="confirmpassword" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" id="confirmpassword" name="confirmpassword" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Xác nhận mật khẩu" required>
            </div>           

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Đăng ký</button>
                <a href="/riderhub/account/login" class="btn btn-secondary px-4 shadow-lg">Đăng nhập</a>
            </div>
        </form>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>
