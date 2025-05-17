<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db/conexion.php'; // Corrige la ruta del archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'listar') {
        $query = "SELECT e.cod_encuentro, e.fecha, e.hora, 
                         el.nombre AS equipo_local, ev.nombre AS equipo_visitante, 
                         c.nombre AS cancha, e.estado
                  FROM Encuentros e
                  JOIN Equipos el ON e.equipo_local = el.cod_equ
                  JOIN Equipos ev ON e.equipo_visitante = ev.cod_equ
                  JOIN Canchas c ON e.cod_cancha = c.cod_cancha";

        $result = pg_query($dbconn, $query);

        if ($result) {
            $encuentros = pg_fetch_all($result);
            echo json_encode($encuentros);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener los encuentros']);
        }

        pg_free_result($result); // Libera el resultado de la consulta
    }
}

pg_close($dbconn); // Cierra la conexión correctamente