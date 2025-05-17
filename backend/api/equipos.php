<?php
// Encabezados requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir archivos de configuración y controlador
include_once '../config/database.php';
include_once '../controllers/equipo_controller.php';

// Instanciar la base de datos y el controlador
$database = new Database();
$controller = new EquipoController($database);

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
        if ($action === 'destacados') {
            // Obtener equipos destacados
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 3;
            $equipos = $controller->getDestacados($limit);
            $response = [
                'status' => 'success',
                'data' => $equipos
            ];
        } elseif (isset($_GET['id'])) {
            // Obtener un equipo específico
            $id = intval($_GET['id']);
            $equipo = $controller->getById($id);
            
            if ($equipo) {
                $response = [
                    'status' => 'success',
                    'data' => $equipo
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Equipo no encontrado'
                ];
            }
        } else {
            // Obtener todos los equipos
            $equipos = $controller->getAll();
            $response = [
                'status' => 'success',
                'data' => $equipos
            ];
        }
        break;
        
    case 'POST':
        // Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            // Si no hay datos JSON, intentar obtener de POST
            $data = $_POST;
        }
        
        // Crear un nuevo equipo
        $result = $controller->create($data);
        
        if ($result === true) {
            $response = [
                'status' => 'success',
                'message' => 'Equipo creado correctamente'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => $result
            ];
        }
        break;
        
    case 'PUT':
        // Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            // Actualizar el equipo
            $result = $controller->update($id, $data);
            
            if ($result === true) {
                $response = [
                    'status' => 'success',
                    'message' => 'Equipo actualizado correctamente'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => $result
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'ID de equipo no especificado'
            ];
        }
        break;
        
    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            // Eliminar el equipo
            $result = $controller->delete($id);
            
            if ($result === true) {
                $response = [
                    'status' => 'success',
                    'message' => 'Equipo eliminado correctamente'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => $result
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'ID de equipo no especificado'
            ];
        }
        break;
        
    default:
        $response = [
            'status' => 'error',
            'message' => 'Método no permitido'
        ];
        break;
}

// Enviar respuesta
echo json_encode($response);
?>