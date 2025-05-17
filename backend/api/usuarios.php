<?php
/**
 * API para gestionar usuarios
 */

// Encabezados para CORS y JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Configuración de la aplicación
define('BASE_URL', '/Proyecto-Bases-de-Datos/');

// Configuración de sesión
session_start();

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Función para verificar si el usuario es administrador
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin';
}

// Incluir modelo
require_once '../models/usuario.php';

// Crear instancia del modelo
$usuarioModel = new Usuario();

// Obtener el método HTTP
$requestMethod = $_SERVER["REQUEST_METHOD"];

try {
    // Manejar diferentes métodos HTTP
    switch ($requestMethod) {
        case 'GET':
            // Si se proporciona un ID, obtener usuario específico
            if (isset($_GET['id'])) {
                $usuario = $usuarioModel->getById($_GET['id']);
                
                if ($usuario) {
                    // No devolver la contraseña por seguridad
                    if (isset($usuario['password'])) {
                        unset($usuario['password']);
                    }
                    
                    echo json_encode([
                        "status" => "success",
                        "data" => $usuario
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        "status" => "error",
                        "message" => "Usuario no encontrado"
                    ]);
                }
            } 
            // Si se especifica la acción "login" (verificar credenciales sin iniciar sesión)
            elseif (isset($_GET['action']) && $_GET['action'] === 'check') {
                if (isset($_GET['username']) && isset($_GET['password'])) {
                    $usuario = $usuarioModel->checkCredentials($_GET['username'], $_GET['password']);
                    
                    if ($usuario) {
                        // No devolver la contraseña por seguridad
                        unset($usuario['password']);
                        
                        echo json_encode([
                            "status" => "success",
                            "data" => $usuario
                        ]);
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "message" => "Credenciales incorrectas"
                        ]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error",
                        "message" => "Faltan credenciales"
                    ]);
                }
            }
            // Si no hay parámetros, obtener todos los usuarios
            else {
                $usuarios = $usuarioModel->getAll();
                
                // Eliminar contraseñas por seguridad
                foreach ($usuarios as &$usuario) {
                    if (isset($usuario['password'])) {
                        unset($usuario['password']);
                    }
                }
                
                echo json_encode([
                    "status" => "success",
                    "data" => $usuarios
                ]);
            }
            break;
            
        case 'POST':
            // Verificar acción específica
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            
            // Obtener datos enviados
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Si es una solicitud de login
            if ($action === 'login') {
                if (isset($data['username']) && isset($data['password'])) {
                    $result = $usuarioModel->login($data['username'], $data['password']);
                    
                    if ($result['status'] === 'success') {
                        echo json_encode($result);
                    } else {
                        http_response_code(401);
                        echo json_encode($result);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error",
                        "message" => "Faltan credenciales"
                    ]);
                }
                break;
            }
            
            // Para otras acciones, verificar si es admin (excepto registro)
            if ($action !== 'register' && !isAdmin()) {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                break;
            }
            
            // Validar datos
            if (empty($data['username']) || ($action !== 'register' && empty($data['email']))) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Faltan datos obligatorios (username, email)"
                ]);
                break;
            }
            
            // Si es un registro nuevo, verificar contraseña
            if (($action === 'register' || empty($data['id'])) && empty($data['password'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "La contraseña es obligatoria para nuevos usuarios"
                ]);
                break;
            }
            
            // Insertar o actualizar usuario
            if (!empty($data['id'])) {
                $usuario = $usuarioModel->update($data['id'], $data);
                $message = "Usuario actualizado correctamente";
            } else {
                $usuario = $usuarioModel->insert($data);
                $message = "Usuario creado correctamente";
                http_response_code(201);
            }
            
            if ($usuario) {
                // No devolver la contraseña
                if (isset($usuario['password'])) {
                    unset($usuario['password']);
                }
                
                echo json_encode([
                    "status" => "success",
                    "message" => $message,
                    "data" => $usuario
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al procesar el usuario"
                ]);
            }
            break;
            
        case 'PUT':
            // Verificar si es administrador
            if (!isAdmin()) {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                break;
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
            
            // Actualizar usuario
            $usuario = $usuarioModel->update($id, $data);
            
            if ($usuario) {
                // No devolver la contraseña
                if (isset($usuario['password'])) {
                    unset($usuario['password']);
                }
                
                echo json_encode([
                    "status" => "success",
                    "message" => "Usuario actualizado correctamente",
                    "data" => $usuario
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al actualizar el usuario"
                ]);
            }
            break;
            
        case 'DELETE':
            // Verificar si es administrador
            if (!isAdmin()) {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "No tiene permisos para realizar esta acción"
                ]);
                break;
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
            
            // Eliminar usuario
            if ($usuarioModel->delete($id)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Usuario eliminado correctamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al eliminar el usuario"
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