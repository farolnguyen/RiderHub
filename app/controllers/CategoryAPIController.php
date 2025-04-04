<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // 🔹 Lấy tất cả danh mục (GET /api/category)
    public function index()
    {
        header('Content-Type: application/json');
        $categories = $this->categoryModel->getAllCategories();
        echo json_encode($categories);
    }

    // 🔹 Lấy danh mục theo ID (GET /api/category/show/:id)
    public function show($id)
    {
        header('Content-Type: application/json');
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Danh mục không tồn tại']);
        }
    }

    // 🔹 Thêm danh mục mới (POST /api/category/store)
    public function store()
    {
        header('Content-Type: application/json');

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $imagePath = "";

        // ✅ Kiểm tra dữ liệu
        if (empty($name) || empty($description)) {
            http_response_code(400);
            echo json_encode(['message' => 'Tên và mô tả không được để trống']);
            return;
        }

        // ✅ Xử lý ảnh nếu có
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/categories/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
            $imagePath = $targetFile;
        }

        // ✅ Gọi model
        $result = $this->categoryModel->addCategory($name, $description, $imagePath);
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Tạo danh mục thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Tạo danh mục thất bại']);
        }
    }
    public function update($id)
    {
        header('Content-Type: application/json');
    
        // Lấy dữ liệu từ FormData hoặc từ body (được gửi dưới dạng JSON)
        $data = json_decode(file_get_contents("php://input"), true);
    
        // Kiểm tra nếu không nhận được dữ liệu
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
            return;
        }
    
        // Lấy các trường từ dữ liệu JSON
        $name = $data['name'] ?? ''; 
        $description = $data['description'] ?? '';  
        $existing_image = $data['existing_image'] ?? null; 
    
        // Kiểm tra tên và mô tả có trống không
        if (empty($name) || empty($description)) {
            http_response_code(400);
            echo json_encode(['message' => 'Tên và mô tả không được để trống']);
            return;
        }
    
        // Kiểm tra xóa ảnh nếu người dùng yêu cầu
        if (isset($data['delete_image']) && $data['delete_image'] == "1") {
            if (!empty($existing_image) && file_exists($existing_image)) {
                unlink($existing_image);  // Xóa ảnh cũ
            }
            $imagePath = null;  // Đặt ảnh là null khi xóa
        }
    
        // Nếu có ảnh mới, xử lý lưu ảnh mới
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/categories/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);  // Tạo thư mục nếu chưa tồn tại
            }
    
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Không thể tải lên ảnh mới']);
                return;
            }
        } else {
            // Nếu không có ảnh mới, giữ ảnh cũ
            $imagePath = $existing_image;
        }
    
        // Gọi model để cập nhật danh mục
        $result = $this->categoryModel->updateCategory($id, $name, $description, $imagePath);
    
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Danh mục đã được cập nhật thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cập nhật danh mục thất bại']);
        }
    }
    
    


    // 🔹 Xoá danh mục (DELETE /api/category/delete/:id)
    public function destroy($id)
    {
        header('Content-Type: application/json');

        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            http_response_code(200); // 👈 Đảm bảo là status 200
            echo json_encode(['message' => 'Xóa danh mục thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Không thể xóa danh mục']);
        }
    }


}
