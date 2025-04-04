<?php
if (!isset($page)) {
    $page = "home";
}
if (!isset($_SESSION['interface'])) {
    $_SESSION['interface'] = 'mvc';
}
if (isset($_POST['interface'])) {
    $_SESSION['interface'] = $_POST['interface'];
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
$interface = $_SESSION['interface'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiderHub - Quản lý sản phẩm</title>

    <link rel="stylesheet" href="/riderhub/public/css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="/riderhub/public/css/<?php echo $page_css; ?>">
    <?php endif; ?>

    <?php $background_image = "/riderhub/public/images/" . $page . "-bg.jpg"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url("<?php echo $background_image; ?>") !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            background-size: cover !important;
            background-attachment: fixed !important;
        }
    </style>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid pl-0 pr-0">
    <a class="navbar-brand pr-3 mr-3" href="/riderhub/" style="margin-left: 0;">Rider<span>Hub</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarContent">

            <div class="d-flex align-items-center">
                <ul class="navbar-nav ml-0 pl-0">
                    <?php if ($interface === 'mvc'): ?>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Company">Hãng Xe</a></li>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Category">Danh mục xe</a></li>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Product">Danh sách xe</a></li>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Product/search">Tìm kiếm</a></li>
                    <?php elseif ($interface === 'api'): ?>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Category/list_category_api">Danh mục xe (API)</a></li>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Product/list_api">Danh sách xe (API)</a></li>   
                    <?php endif; ?>
                </ul>
            </div>        
            <?php if ($interface === 'mvc' && SessionHelper::isLoggedIn()): ?>
                <span class="navbar-text text-warning font-weight-bold mx-auto text-center">
                    <?php 
                        $user = $_SESSION['username'];
                        echo SessionHelper::isAdmin() ? "Welcome Admin $user" : "Welcome $user";
                    ?>
                </span>
            <?php endif; ?>

            <div class="d-flex align-items-center">
                <ul class="navbar-nav">
                    <?php if ($interface === 'mvc' && SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/riderhub/Product/cart">
                                🛒 Giỏ hàng
                                <span class="badge bg-warning" id="cart-count">
                                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/riderhub/Account/logout">Đăng xuất</a>
                        </li>
                        <?php elseif ($interface === 'mvc'): ?>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Account/login">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="/riderhub/Account/register">Đăng ký</a></li>
                        <?php else: ?>
                            <li class="nav-item d-none" id="nav-cart">
                                <a class="nav-link" href="/riderhub/Product/cart">
                                    🛒 Giỏ hàng
                                    <span class="badge bg-warning" id="cart-count">0</span>
                                </a>
                            </li>
                            <li class="nav-item d-none" id="nav-register">
                                <a class="nav-link" href="/riderhub/account/register">Đăng ký</a>
                            </li>
                            <li class="nav-item" id="nav-login">
                                <a class="nav-link" href="/riderhub/account/login">Đăng nhập</a>
                            </li>
                            <li class="nav-item d-none" id="nav-logout">
                                <a class="nav-link" href="#" onclick="logout()">Đăng xuất</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                <form method="POST" class="form-inline ml-3">
                    <select name="interface" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="mvc" <?php if ($interface === 'mvc') echo 'selected'; ?>>MVC</option>
                        <option value="api" <?php if ($interface === 'api') echo 'selected'; ?>>RESTful API</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = sessionStorage.getItem('jwtToken');
    const navLogin = document.getElementById('nav-login');
    const navLogout = document.getElementById('nav-logout');
    const navRegister = document.getElementById('nav-register');
    const navCart = document.getElementById('nav-cart');

    const show = (el) => el && el.classList.remove('d-none');
    const hide = (el) => el && el.classList.add('d-none');

    if (token) {
        hide(navLogin);
        hide(navRegister);
        show(navLogout);
        show(navCart);
    } else {
        show(navLogin);
        show(navRegister);
        hide(navLogout);
        hide(navCart);
    }
});

function logout() {
    sessionStorage.removeItem('jwtToken');
    location.href = "/riderhub/account/login";
}
</script>



<div class="main-content">
