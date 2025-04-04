<?php
require_once 'app/models/CompanyModel.php';

class CompanyController {
    private $db;
    private $companyModel;

    public function __construct($db) {
        $this->db = $db;
        $this->companyModel = new CompanyModel($db);
    }

    // 🔹 Hiển thị danh sách hãng xe (cho tất cả người dùng)
    public function index()
    {
        $companies = $this->companyModel->getAllCompanies();
        include 'app/views/company/company.php';
    }

    // 🔹 Hiển thị form thêm hãng xe (chỉ admin)
    public function add_company()
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }
        include 'app/views/company/add_company.php';
    }

    // 🔹 Xử lý lưu hãng xe mới (chỉ admin)
    public function save_company()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && SessionHelper::isAdmin()) {  // Kiểm tra quyền admin
            $name = $_POST['name'];
            $description = $_POST['description'];
            $imagePath = "";

            // 🖼 Xử lý ảnh tải lên
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/companies/";  // Thư mục lưu ảnh hãng xe
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                $imagePath = $targetFile;
            }

            // 🛠 Thêm hãng xe vào database
            $this->companyModel->addCompany($name, $description, $imagePath);
            header("Location: /riderhub/Company");
        } else {
            echo "Bạn không có quyền truy cập vào trang này.";
        }
    }

    // 🔹 Hiển thị form sửa hãng xe (chỉ admin)
    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }

        $company = $this->companyModel->getCompanyById($id);
        if ($company) {
            include 'app/views/company/edit_company.php';
        } else {
            echo "Không tìm thấy hãng xe.";
        }
    }

    // 🔹 Xử lý cập nhật hãng xe (chỉ admin)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && SessionHelper::isAdmin()) {  // Kiểm tra quyền admin
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $imagePath = $_POST['existing_image'];

            // 🗑 Xóa ảnh nếu người dùng chọn "Xóa hình ảnh hiện tại"
            if (isset($_POST['delete_image']) && $_POST['delete_image'] == "1") {
                if (!empty($imagePath) && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $imagePath = NULL;
            }

            // 🖼 Nếu người dùng chọn ảnh mới, cập nhật ảnh mới
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/companies/"; // Thư mục lưu ảnh hãng xe
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                $imagePath = $targetFile;
            }

            // 🛠 Cập nhật hãng xe trong database
            $this->companyModel->updateCompany($id, $name, $description, $imagePath);
            header("Location: /riderhub/Company");
            exit();
        } else {
            echo "Bạn không có quyền truy cập vào trang này.";
        }
    }

    // 🔹 Xóa hãng xe (chỉ admin)
    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập vào trang này.";
            return;
        }

        $this->companyModel->deleteCompany($id);
        header("Location: /riderhub/Company");
    }

    public function list_company_api()
    {
        include 'app/views/api/company/list_company_api.php';
    }

    public function add_company_api() {
        include 'app/views/api/company/add_company_api.php';
    }

    public function edit_company_api($id) {
        $editId = $id;
        include 'app/views/api/company/edit_company_api.php';
    }
}
?>
