<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/models/CompanyModel.php';

class ProductController
{
    private $companyModel;
    private $categoryModel;
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->companyModel = new CompanyModel($this->db);
    }

    // 🔹 Hiển thị danh sách sản phẩm (cho tất cả người dùng)
    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }
    public function search()
    {
        $search = $_GET['search'] ?? '';
        $company_id = $_GET['company_id'] ?? '';
        $category_id = $_GET['category_id'] ?? '';
        $min_price = $_GET['min_price'] ?? '';
        $max_price = $_GET['max_price'] ?? '';

        // Kiểm tra xem giá trị của min_price và max_price có hợp lệ không
        $min_price = is_numeric($min_price) ? (int)$min_price : 0;
        $max_price = is_numeric($max_price) ? (int)$max_price : 0;

        // Lấy danh sách các hãng và danh mục
        $companies = $this->companyModel->getAllCompanies();
        $categories = $this->categoryModel->getAllCategories();

        // Lọc sản phẩm dựa trên các tiêu chí tìm kiếm
        $products = $this->productModel->searchProducts($search, $company_id, $category_id, $min_price, $max_price);

        // Chuyển các dữ liệu vào view
        include 'app/views/product/list_product_by_search.php';
    }


    // 🔹 Hiển thị chi tiết sản phẩm (cho tất cả người dùng)
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    // 🔹 Hiển thị form thêm sản phẩm (chỉ admin)
    public function add()
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }
        
        $categories = (new CategoryModel($this->db))->getAllCategories();
        include_once 'app/views/product/add.php';
    }

    // 🔹 Xử lý lưu sản phẩm (chỉ admin)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && SessionHelper::isAdmin()) {  // Kiểm tra quyền admin
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getAllCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /riderhub/Product');
            }
        } else {
            echo "Bạn không có quyền truy cập vào trang này.";
        }
    }

    // 🔹 Hiển thị form sửa sản phẩm (chỉ admin)
    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getAllCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    // 🔹 Xử lý cập nhật sản phẩm (chỉ admin)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && SessionHelper::isAdmin()) {  // Kiểm tra quyền admin
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $deleteImage = isset($_POST['delete_image']) ? true : false;

            // 🖼 Lấy thông tin sản phẩm hiện tại để kiểm tra ảnh cũ
            $product = $this->productModel->getProductById($id);
            $currentImage = $product->image;

            // 🖼 Xử lý hình ảnh
            $imagePath = $currentImage; // Giữ ảnh cũ nếu không có ảnh mới

            // Nếu người dùng chọn xóa ảnh, thực hiện xóa ảnh khỏi thư mục và database
            if ($deleteImage && !empty($currentImage)) {
                if (file_exists($currentImage)) {
                    unlink($currentImage); // Xóa file ảnh trong thư mục
                }
                $imagePath = ""; // Xóa đường dẫn ảnh trong database
            }

            // Nếu có ảnh mới, lưu ảnh mới và xóa ảnh cũ (nếu có)
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

                // Xóa ảnh cũ nếu có ảnh mới
                if (!empty($currentImage) && file_exists($currentImage)) {
                    unlink($currentImage);
                }

                $imagePath = $targetFile; // Cập nhật ảnh mới
            }

            // 🛠 Gọi model để cập nhật sản phẩm
            $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $imagePath
            );

            header('Location: /riderhub/Product');
            exit();
        } else {
            echo "Bạn không có quyền truy cập vào trang này.";
        }
    }

    // 🔹 Xóa sản phẩm (chỉ admin)
    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }

        $this->productModel->deleteProduct($id);
        header("Location: /riderhub/Product");
    }

    // 🛒 Hiển thị giỏ hàng
    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    // 🛒 Thêm sản phẩm vào giỏ hàng (có kiểm tra số lượng)
    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;  // ✅ Tăng số lượng nếu đã có trong giỏ
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,  // ✅ Mặc định 1 sản phẩm
                'image' => $product->image
            ];
        }

        header('Location: /riderhub/Product/cart');
    }

    // 🛒 Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /riderhub/Product/cart');
        exit();
    }

    // 🏪 Hiển thị trang thanh toán
    public function checkout()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            echo "Giỏ hàng trống.";
            return;
        }
        include 'app/views/product/checkout.php';
    }

    // 💳 Xử lý thanh toán
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Kiểm tra giỏ hàng có sản phẩm không
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            // 🛒 Tính tổng giá trị đơn hàng
            $cart = $_SESSION['cart'];
            $total_price = 0;
            foreach ($cart as $item) {
                $total_price += $item['quantity'] * $item['price'];
            }

            // 🔄 Bắt đầu transaction
            $this->db->beginTransaction();
            try {
                // 📝 Lưu thông tin đơn hàng
                $query = "INSERT INTO orders (name, phone, address, total_price, status) 
                        VALUES (:name, :phone, :address, :total_price, 'pending')";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':total_price', $total_price);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // 🛍 Lưu thông tin sản phẩm trong đơn hàng
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, unit_price) 
                            VALUES (:order_id, :product_id, :quantity, :unit_price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':unit_price', $item['price']);
                    $stmt->execute();
                }

                // 🧹 Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                $_SESSION['last_order_id'] = $order_id;

                // ✅ Commit giao dịch
                $this->db->commit();

                // 🔄 Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /riderhub/Product/orderConfirmation');
                exit();
            } catch (Exception $e) {
                // ❌ Rollback nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    // 📦 Trang xác nhận đơn hàng
    public function orderConfirmation()
    {
        if (!isset($_SESSION['last_order_id'])) {
            echo "Không tìm thấy đơn hàng.";
            return;
        }

        $order_id = $_SESSION['last_order_id'];
        $query = "SELECT * FROM orders WHERE id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "Lỗi: Không tìm thấy đơn hàng.";
            return;
        }

        include 'app/views/product/orderConfirmation.php';
    }
    public function category($category_id)
    {
        // Lấy thông tin danh mục
        $category = $this->categoryModel->getCategoryById($category_id);
        if (!$category) {
            die("Danh mục không tồn tại!");
        }

        // Lấy danh sách sản phẩm thuộc danh mục
        $products = $this->productModel->getProductsByCategory($category_id);

        // Gửi dữ liệu sang view
        include 'app/views/product/list_product_by_category.php';
    }
    public function company($company_id)
    {
        // Lấy thông tin công ty
        $company = $this->companyModel->getCompanyById($company_id);
        if (!$company) {
            die("Công ty không tồn tại!");
        }

        // Lấy danh sách sản phẩm thuộc công ty
        $products = $this->productModel->getProductsByCompany($company_id);

        // Gửi dữ liệu sang view
        include 'app/views/product/list_product_by_company.php';
    }
    public function list_api()
    {
        include 'app/views/api/product/list_api.php';
    }
    public function add_api() {
        include 'app/views/api/product/add_api.php';
    }
    
    public function edit_api($id) {
        $editId = $id;
        include 'app/views/api/product/edit_api.php';
    }
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);

        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh quá lớn. Giới hạn 10MB.");
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            throw new Exception("Chỉ chấp nhận JPG, JPEG, PNG, GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Tải ảnh lên thất bại.");
        }

        return $target_file;
    }

}

?>
