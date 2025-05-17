<?php
// Encabezados requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir archivos de configuración y modelo
include_once '../config/database.php';
include_once '../models/ciudad.php';

// Instanciar la base de datos y el modelo
$database = new Database();
$db = $database->getConnection();
$ciudad = new Ciudad($db);

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Procesar la solicitud según el método HTTP
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener una ciudad específica
            $id = intval($_GET['id']);
            
            if ($ciudad->readOne($id)) {
                $ciudad_arr = [
                    'cod_ciu' => $ciudad->cod_ciu,
                    'nombre' => $ciudad->nombre
                ];
                
                $response = [
                    'status' => 'success',
                    'data' => $ciudad_arr
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Ciudad no encontrada'
                ];
            }
        } elseif (isset($_GET['con_equipos']) && $_GET['con_equipos'] == 1) {
            // Obtener ciudades que tienen equipos
            $stmt = $ciudad->getCiudadesConEquipos();
            $ciudades = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciudades[] = $row;
            }
            
            $response = [
                'status' => 'success',
                'data' => $ciudades
            ];
        } else {
            // Obtener todas las ciudades
            $stmt = $ciudad->read();
            $ciudades = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciudades[] = $row;
            }
            
            $response = [
                'status' => 'success',
                'data' => $ciudades
            ];
        }
        break;
        
    case 'POST':
        // Verificar si es un usuario administrador (en un caso real se verificaría con sesiones)
        $is_admin = false;
        
        // Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            // Si no hay datos JSON, intentar obtener de POST
            $data = $_POST;
        }
        
        if ($is_admin) {
            if (isset($data['nombre']) && !empty($data['nombre'])) {
                // Asignar valores al objeto ciudad
                $ciudad->nombre = $data['nombre'];
                
                // Crear la ciudad
                if ($ciudad->create()) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Ciudad creada correctamente'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'No se pudo crear la ciudad'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'El nombre de la ciudad es obligatorio'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No tiene permisos para realizar esta acción'
            ];
        }
        break;
        
    case 'PUT':
        // Verificar si es un usuario administrador (en un caso real se verificaría con sesiones)
        $is_admin = false;
        
        if ($is_admin) {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                
                // Obtener los datos enviados
                $data = json_decode(file_get_contents("php://input"), true);
                
                if (!$data) {
                    // Si no hay datos JSON, intentar obtener de POST
                    $data = $_POST;
                }
                
                if (isset($data['nombre']) && !empty($data['nombre'])) {
                    // Verificar que la ciudad existe
                    if ($ciudad->readOne($id)) {
                        // Asignar valores al objeto ciudad
                        $ciudad->nombre = $data['nombre'];
                        
                        // Actualizar la ciudad
                        if ($ciudad->update()) {
                            $response = [
                                'status' => 'success',
                                'message' => 'Ciudad actualizada correctamente'
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'No se pudo actualizar la ciudad'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Ciudad no encontrada'
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'El nombre de la ciudad es obligatorio'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'ID de ciudad no especificado'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No tiene permisos para realizar esta acción'
            ];
        }
        break;
        
    case 'DELETE':
        // Verificar si es un usuario administrador (en un caso real se verificaría con sesiones)
        $is_admin = false;
        
        if ($is_admin) {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                
                // Verificar que la ciudad existe
                if ($ciudad->readOne($id)) {
                    // Eliminar la ciudad
                    if ($ciudad->delete()) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Ciudad eliminada correctamente'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'No se pudo eliminar la ciudad'
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Ciudad no encontrada'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'ID de ciudad no especificado'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No tiene permisos para realizar esta acción'
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