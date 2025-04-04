<?php
session_start();

require_once 'app/config/Database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/ProductAPIModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/models/CompanyModel.php';
require_once 'app/helper/SessionHelper.php';

// RESTful API Controllers
require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

// Xử lý URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Trường hợp gọi API RESTful: api/product, api/category
if ($url[0] === 'api' && isset($url[1])) {
    $apiControllerName = ucfirst($url[1]) . 'ApiController';

    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();

        $method = $_SERVER['REQUEST_METHOD'];
        $id = $url[2] ?? null;

        switch ($method) {
            case 'GET':
                $action = $id ? 'show' : 'index';
                break;
            case 'POST':
                $action = 'store';
                break;
            case 'PUT':
                $action = $id ? 'update' : null;
                break;
            case 'DELETE':
                $action = $id ? 'destroy' : null;
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }

        if ($action && method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $id ? [$id] : []);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'API Controller not found']);
        exit;
    }
}

// --- Web controllers (non-API) --- //

// Kết nối database
$database = new Database();
$db = $database->getConnection();

$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found');
}
require_once 'app/controllers/' . $controllerName . '.php';

// Truyền đối tượng kết nối DB nếu controller cần
if ($controllerName === 'ProductController' || $controllerName === 'CategoryController' || $controllerName === 'CompanyController') {
    $controller = new $controllerName($db);
} else {
    $controller = new $controllerName();
}

$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

if (!method_exists($controller, $action)) {
    die('Action not found');
}

call_user_func_array([$controller, $action], array_slice($url, 2));
