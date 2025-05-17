<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db/conexion.php';

$sql = "SELECT e.id, el.nombre AS equipo_local, ev.nombre AS equipo_visitante, e.fecha, e.hora, c.nombre AS lugar FROM encuentros e JOIN equipos el ON e.equipo_local_id = el.id JOIN equipos ev ON e.equipo_visitante_id = ev.id JOIN canchas c ON e.campo_id = c.id ORDER BY e.fecha, e.hora";

$result = $conn->query($sql);
$partidos = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $partidos[] = $row;
    }
}
echo json_encode($partidos);
$conn->close();