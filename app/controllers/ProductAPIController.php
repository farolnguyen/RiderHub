<?php
require_once('app/config/database.php');
require_once('app/models/ProductAPIModel.php');
require_once('app/utils/JWTHandler.php');
class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductAPIModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }
    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }
    // GET /riderhub/ProductApi/index
    public function index()
    {
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }

    // GET /riderhub/ProductApi/show/1
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Không tìm thấy sản phẩm']);
        }
    }

    // POST /riderhub/ProductApi/store
    public function store()
    {
        header('Content-Type: application/json');
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, null);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Tạo sản phẩm thành công']);
        }
    }

    // PUT /riderhub/ProductApi/update/1
    public function update($id)
    {
        header('Content-Type: application/json');
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, null);
        if ($result) {
            echo json_encode(['message' => 'Cập nhật sản phẩm thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Cập nhật sản phẩm thất bại']);
        }
    }

    // DELETE /riderhub/ProductApi/destroy/1
    public function destroy($id)
    {
        header('Content-Type: application/json');
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }
        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Xóa sản phẩm thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Xóa sản phẩm thất bại']);
        }
    }
}
