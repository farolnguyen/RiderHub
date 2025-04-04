<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');
class AccountController {
    private $accountModel;
    private $db;
    private $jwtHandler;
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    // Trang đăng ký
    public function register() {
        include_once 'app/views/account/register.php';
    }

    // Trang đăng nhập
    public function login() {
        include_once 'app/views/account/login.php';
    }

    // Lưu tài khoản đăng ký mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $errors = [];

            // Kiểm tra các thông tin nhập vào
            if (empty($username)) $errors['username'] = "Vui lòng nhập username!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập fullname!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập password!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            if (!in_array($role, ['admin', 'user'])) $role = 'user';

            // Kiểm tra username đã tồn tại chưa
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            // Nếu có lỗi, hiển thị lại form đăng ký với thông báo lỗi
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                // Lưu tài khoản mới
                $result = $this->accountModel->save($username, $fullName, $password, $role);
                if ($result) {
                    header('Location: /riderhub/account/login');
                    exit;
                } else {
                    $errors['save'] = "Đã xảy ra lỗi khi lưu tài khoản. Vui lòng thử lại!";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    // Đăng xuất
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: /riderhub/product');
        exit();
    }

    public function checkLogin() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $isApi = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

        // Lấy dữ liệu từ request JSON hoặc form
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? ($_POST['username'] ?? '');
        $password = $data['password'] ?? ($_POST['password'] ?? '');

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account['password'])) {
            $_SESSION['username'] = $account['username'];
            $_SESSION['role'] = $account['role'];

            if ($isApi) {
                $token = $this->jwtHandler->encode([
                    'id' => $account['id'],
                    'username' => $account['username']
                ]);
                $_SESSION['jwt'] = $token;
                echo json_encode(['token' => $token]);
                return;
            } else {
                header('Location: /riderhub/product');
                exit();
            }
        } else {
            if ($isApi) {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid credentials']);
                return;
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
                exit();
            }
        }
    }
    
}
?>
