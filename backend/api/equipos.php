<?php
/**
 * API para gestionar equipos
 */

// Encabezados para CORS y JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir configuración y modelo
require_once '../models/Equipo.php';

// Crear instancia del modelo
$equipoModel = new Equipo();

// Obtener el método HTTP
$requestMethod = $_SERVER["REQUEST_METHOD"];

try {
    // Manejar diferentes métodos HTTP
    switch ($requestMethod) {
        case 'GET':
            // Si se proporciona un ID, obtener equipo específico
            if (isset($_GET['id'])) {
                $equipo = $equipoModel->getById($_GET['id'], 'cod_equipo');
                
                if ($equipo) {
                    echo json_encode([
                        "status" => "success",
                        "data" => $equipo
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        "status" => "error",
                        "message" => "Equipo no encontrado"
                    ]);
                }
            } 
            // Si se filtra por ciudad
            elseif (isset($_GET['ciudad'])) {
                $equipos = $equipoModel->getByCity($_GET['ciudad']);
                
                echo json_encode([
                    "status" => "success",
                    "data" => $equipos
                ]);
            }
            // Si se solicitan los equipos destacados
            elseif (isset($_GET['destacados'])) {
                $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
                $equipos = $equipoModel->getTopTeams($limit);
                
                echo json_encode([
                    "status" => "success",
                    "data" => $equipos
                ]);
            }
            // Si no hay parámetros, obtener todos los equipos con info de ciudad
            else {
                $equipos = $equipoModel->getAllWithCity();
                
                echo json_encode([
                    "status" => "success",
                    "data" => $equipos
                ]);
            }
            break;
            
        case 'POST':
            // Verificar si es una solicitud de administrador
            session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                exit;
            }
            
            // Obtener datos enviados
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validar datos
            if (empty($data['nombre']) || empty($data['cod_ciu'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Faltan datos obligatorios (nombre, ciudad)"
                ]);
                break;
            }
            
            // Insertar equipo
            $equipo = $equipoModel->insert($data);
            
            if ($equipo) {
                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Equipo creado correctamente",
                    "data" => $equipo
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al crear el equipo"
                ]);
            }
            break;
            
        case 'PUT':
            // Verificar si es una solicitud de administrador
            session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                exit;
            }
            
            // Asegurarse de que se proporciona un ID
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Se requiere ID para actualizar"
                ]);
                break;
            }
            
            $id = $_GET['id'];
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validar datos
            if (empty($data)) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "No hay datos para actualizar"
                ]);
                break;
            }
            
            // Actualizar equipo
            $equipo = $equipoModel->update($id, $data, 'cod_equipo');
            
            if ($equipo) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Equipo actualizado correctamente",
                    "data" => $equipo
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al actualizar el equipo"
                ]);
            }
            break;
            
        case 'DELETE':
            // Verificar si es una solicitud de administrador
            session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                exit;
            }
            
            // Asegurarse de que se proporciona un ID
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Se requiere ID para eliminar"
                ]);
                break;
            }
            
            $id = $_GET['id'];
            
            // Eliminar equipo
            if ($equipoModel->delete($id, 'cod_equipo')) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Equipo eliminado correctamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al eliminar el equipo"
                ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                "status" => "error",
                "message" => "Método no permitido"
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>