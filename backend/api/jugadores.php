<?php
require_once '../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query('SELECT jugadores.id, jugadores.nombre, jugadores.posicion, equipos.nombre AS equipo, jugadores.foto FROM jugadores JOIN equipos ON jugadores.equipo_id = equipos.id');
    $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($jugadores);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los jugadores']);
}