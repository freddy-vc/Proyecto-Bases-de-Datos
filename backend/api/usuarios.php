<?php
error_reporting(0);
ini_set('display_errors', 0);
// Encabezados requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir archivos de configuración y controladores
include_once '../config/database.php';
include_once '../controllers/usuario_controller.php';

// Instanciar la base de datos y el controlador
$database = new Database();
$usuarioController = new UsuarioController($database);

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Procesar la solicitud según el método HTTP y la acción
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Respuesta por defecto
$response = [
    'status' => 'error',
    'message' => 'Acción no válida'
];

switch ($method) {
    case 'GET':
        if ($action === 'getAll') {
            // Obtener todos los usuarios
            $usuarios = $usuarioController->getAll();
            $response = [
                'status' => 'success',
                'message' => 'Usuarios obtenidos exitosamente',
                'data' => $usuarios
            ];
        } elseif ($action === 'getById' && isset($_GET['id'])) {
            // Obtener un usuario por su ID
            $usuario = $usuarioController->getById($_GET['id']);
            
            if ($usuario) {
                $response = [
                    'status' => 'success',
                    'message' => 'Usuario obtenido exitosamente',
                    'data' => $usuario
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Usuario no encontrado'
                ];
            }
        }
        break;
        
    case 'POST':
        // Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            // Si no hay datos JSON, intentar obtener de POST
            $data = $_POST;
        }
        
        if ($action === 'login') {
            // Proceso de inicio de sesión
            if (isset($data['username']) && isset($data['password'])) {
                $response = $usuarioController->login($data['username'], $data['password']);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Datos de inicio de sesión incompletos'
                ];
            }
        } elseif ($action === 'register' || $action === 'create') {
            // Proceso de registro/creación de usuario
            if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
                $response = $usuarioController->create($data);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Datos de registro incompletos'
                ];
            }
        }
        break;
        
    case 'PUT':
        // Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"), true);
        
        if ($action === 'update' && isset($_GET['id'])) {
            // Actualizar usuario
            $response = $usuarioController->update($_GET['id'], $data);
        } elseif ($action === 'updatePassword' && isset($_GET['id']) && isset($data['password'])) {
            // Actualizar contraseña
            $response = $usuarioController->updatePassword($_GET['id'], $data['password']);
        }
        break;
        
    case 'DELETE':
        if ($action === 'delete' && isset($_GET['id'])) {
            // Eliminar usuario
            $response = $usuarioController->delete($_GET['id']);
        }
        break;
        
}

// Enviar respuesta
echo json_encode($response);
?>