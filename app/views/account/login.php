<?php 
$page = "login"; // ✅ Xác định trang để load CSS phù hợp
$page_css = "login.css"; // ✅ Gán file CSS riêng cho trang login
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Đăng Nhập</h1>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Form đăng nhập -->
    <div class="card shadow-lg p-4 rounded text-light border border-warning">
        <form method="POST" action="/riderhub/account/checkLogin" class="user">
            <div class="form-group mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" id="username" name="username" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Nhập tên đăng nhập" required>
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password" 
                    class="form-control border border-warning bg-transparent text-white" 
                    placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-group text-center mb-3">
                <button type="submit" class="btn btn-warning px-4 shadow-lg">Đăng nhập</button>
            </div>
        </form>

        <div class="text-center">
            <p>Bạn chưa có tài khoản? <a href="/riderhub/account/register" class="text-warning">Đăng ký</a></p>
        </div>
    </div>
</main>

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.getElementById('login-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!username || !password) {
        alert("Vui lòng nhập đầy đủ thông tin.");
        return;
    }

    const loginData = { username, password };

    try {
        const response = await fetch('/riderhub/account/checkLogin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'  // để checkLogin hiểu đây là API
            },
            body: JSON.stringify(loginData)
        });

        const data = await response.json();

        if (response.ok && data.token) {
            sessionStorage.setItem('jwtToken', data.token);
            window.location.href = '/riderhub/product';
        } else {
            alert(data.message || 'Đăng nhập thất bại. Vui lòng kiểm tra lại!');
        }
    } catch (error) {
        console.error('Lỗi khi gửi yêu cầu đăng nhập:', error);
        alert("Lỗi khi gửi yêu cầu đăng nhập. Vui lòng thử lại.");
    }
});
</script>