<?php
class CompanyModel
{
    private $conn;
    private $table_name = "company";  // Đặt tên bảng là "company"

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 🔹 Lấy tất cả hãng xe
    public function getAllCompanies()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 🔹 Lấy hãng xe theo ID
    public function getCompanyById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 🔹 Thêm hãng xe mới
    public function addCompany($name, $description, $image)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description, image) VALUES (:name, :description, :image)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":image", $image);

        return $stmt->execute();
    }

    // 🔹 Cập nhật hãng xe
    public function updateCompany($id, $name, $description, $image)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, image=:image WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":image", $image);

        return $stmt->execute();
    }

    // 🔹 Xóa hãng xe
    public function deleteCompany($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
