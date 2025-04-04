<?php
$page = "list";
$page_css = "list.css"; 
include 'app/views/shares/header.php'; 
?>

<main class="container mt-5 flex-grow-1 d-flex flex-column main-content">
    <h1 class="text-center text-warning mb-4">Danh sách Xe Phân Khối Lớn</h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="product-list">
       
    </div>

    <?php if (SessionHelper::isAdmin()): ?>
        <div class="col mt-4">
            <div class="category-card shadow-lg rounded text-light d-flex align-items-center justify-content-center add-product">
                <a href="/riderhub/Product/add_api" class="add-product-btn text-center">
                    <i class="bi bi-plus-circle-fill display-4"></i>
                    <p class="mt-2">Thêm xe mới</p>
                </a>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'app/views/shares/footer.php'; ?>

<script>
$(document).ready(function () {
    const token = sessionStorage.getItem('jwtToken');
    if (!token) {
        alert('Bạn chưa đăng nhập. Vui lòng đăng nhập để xem danh sách sản phẩm.');
        window.location.href = "/riderhub/account/login";
        return;
    }

    fetch('/riderhub/api/product', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(products => {
        const $container = $('#product-list');
        $container.empty();

        products.forEach(product => {
            const card = `
                <div class="col">
                    <div class="card shadow-lg rounded text-light border border-warning">
                        <img src="/riderhub/${product.image ?? 'images/no-image.png'}" 
                             class="card-img-top border-bottom border-warning" 
                             alt="Product Image">

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/riderhub/Product/show/${product.id}" 
                                   class="text-decoration-none text-warning">
                                    ${product.name}
                                </a>
                            </h5>
                            <p class="card-text">${product.description}</p>
                            <p class="text-danger font-weight-bold h5">💰 ${parseInt(product.price).toLocaleString('vi-VN')} VND</p>
                            <p><strong>Danh mục:</strong> ${product.category_name ?? 'Không xác định'}</p>

                            <div class="d-flex justify-content-center mt-2">
                                <?php if (SessionHelper::isLoggedIn()): ?>
                                    <a href="/riderhub/Product/addToCart/${product.id}" class="btn btn-success btn-cart">🛒 Thêm vào giỏ</a>
                                <?php endif; ?>
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <a href="/riderhub/Product/edit_api/${product.id}" class="btn btn-warning btn-sm mx-1">Sửa</a>
                                    <button class="btn btn-danger btn-sm shadow-sm mx-1" onclick="deleteProduct(${product.id})">Xóa</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(card);
        });
    })
    .catch(error => {
        console.error('Lỗi khi gọi API:', error);
        alert('Không thể tải danh sách sản phẩm. Vui lòng thử lại.');
    });
});

function deleteProduct(id) {
    const token = sessionStorage.getItem('jwtToken');
    if (!token) {
        alert("Bạn chưa đăng nhập.");
        return;
    }

    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
        fetch(`/riderhub/api/product/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.message === 'Xóa sản phẩm thành công') {
                location.reload();
            } else {
                alert("Xóa sản phẩm thất bại!");
            }
        })
        .catch(error => {
            console.error('Lỗi xóa:', error);
        });
    }
}
</script>

