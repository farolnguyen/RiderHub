<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, 
                        c.name as category_name, co.name as company_name
                FROM " . $this->table_name . " p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN company co ON p.company_id = co.id";  // Kết hợp với bảng company
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function searchProducts($search, $company_id, $category_id, $min_price, $max_price)
    {
        // Câu truy vấn SQL
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name, cm.name as company_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              LEFT JOIN company cm ON p.company_id = cm.id
              WHERE 1=1";

        // Thêm điều kiện vào câu truy vấn nếu có
        if (!empty($search)) {
            $query .= " AND p.name LIKE :search";
        }
        if (!empty($company_id)) {
            $query .= " AND p.company_id = :company_id";
        }
        if (!empty($category_id)) {
            $query .= " AND p.category_id = :category_id";
        }
        if (!empty($min_price)) {
            $query .= " AND p.price >= :min_price";
        }
        if (!empty($max_price)) {
            $query .= " AND p.price <= :max_price";
        }

        $stmt = $this->conn->prepare($query);

        // Gán giá trị vào tham số truy vấn
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR); // Dùng bindValue
        }
        if (!empty($company_id)) {
            $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT); // Dùng bindValue
        }
        if (!empty($category_id)) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT); // Dùng bindValue
        }
        if (!empty($min_price)) {
            $stmt->bindValue(':min_price', $min_price, PDO::PARAM_INT); // Dùng bindValue
        }
        if (!empty($max_price)) {
            $stmt->bindValue(':max_price', $max_price, PDO::PARAM_INT); // Dùng bindValue
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getProductsByFilters($search = '', $companyId = '', $categoryId = '', $minPrice = '', $maxPrice = '')
    {
        // Câu truy vấn cơ bản
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name, co.name as company_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  LEFT JOIN company co ON p.company_id = co.id
                  WHERE 1";

        // Thêm điều kiện tìm kiếm
        if (!empty($search)) {
            $query .= " AND p.name LIKE :search";
        }
        if (!empty($companyId)) {
            $query .= " AND p.company_id = :company_id";
        }
        if (!empty($categoryId)) {
            $query .= " AND p.category_id = :category_id";
        }
        if (!empty($minPrice)) {
            $query .= " AND p.price >= :min_price";
        }
        if (!empty($maxPrice)) {
            $query .= " AND p.price <= :max_price";
        }

        $stmt = $this->conn->prepare($query);

        // Bind các tham số nếu có
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        if (!empty($companyId)) {
            $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
        }
        if (!empty($categoryId)) {
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }
        if (!empty($minPrice)) {
            $stmt->bindParam(':min_price', $minPrice, PDO::PARAM_INT);
        }
        if (!empty($maxPrice)) {
            $stmt->bindParam(':max_price', $maxPrice, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name as category_name
FROM " . $this->table_name . " p
LEFT JOIN category c ON p.category_id = c.id
WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function addProduct($name, $description, $price, $category_id, $image)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }
        $query = "INSERT INTO " . $this->table_name . " (name, description, price,
category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function updateProduct($id, $name, $description, $price, $category_id, $image)
{
    $query = "UPDATE " . $this->table_name . " 
              SET name=:name, description=:description, price=:price, 
                  category_id=:category_id, image=:image 
              WHERE id=:id";

    $stmt = $this->conn->prepare($query);
    
    // Lọc dữ liệu đầu vào
    $name = htmlspecialchars(strip_tags($name));
    $description = htmlspecialchars(strip_tags($description));
    $price = htmlspecialchars(strip_tags($price));
    $category_id = htmlspecialchars(strip_tags($category_id));
    $image = htmlspecialchars(strip_tags($image));

    // Gán giá trị vào câu lệnh SQL
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':image', $image);

    return $stmt->execute();
}

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getProductsByCategory($category_id)
    {
        $query = "SELECT * FROM product WHERE category_id = :category_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getProductsByCompany($companyId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE company_id = :company_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":company_id", $companyId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Trả về tất cả sản phẩm của công ty
    }
}