<?php
/**
 * Modelo para la tabla de equipos
 */

require_once __DIR__ . '/BaseModel.php';

class Equipo extends BaseModel {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('equipos');
    }
    
    /**
     * Obtiene todos los equipos con su información de ciudad
     * 
     * @return array Equipos con información de ciudad
     */
    public function getAllWithCity() {
        $query = "SELECT e.*, c.nombre as nombre_ciudad 
                 FROM equipos e
                 JOIN ciudades c ON e.cod_ciu = c.cod_ciu
                 ORDER BY e.nombre";
        
        $stmt = $this->executeQuery($query);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene equipos filtrados por ciudad
     * 
     * @param int $codCiudad Código de la ciudad a filtrar
     * @return array Equipos filtrados
     */
    public function getByCity($codCiudad) {
        $query = "SELECT e.*, c.nombre as nombre_ciudad 
                 FROM equipos e
                 JOIN ciudades c ON e.cod_ciu = c.cod_ciu
                 WHERE e.cod_ciu = :cod_ciu
                 ORDER BY e.nombre";
        
        $stmt = $this->executeQuery($query, [':cod_ciu' => $codCiudad]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene los equipos mejor clasificados
     * 
     * @param int $limit Límite de equipos a obtener
     * @return array Equipos destacados
     */
    public function getTopTeams($limit = 5) {
        $query = "SELECT e.*, c.nombre as nombre_ciudad, 
                        (SELECT SUM(puntos) FROM clasificacion WHERE cod_equipo = e.cod_equipo) as puntos
                 FROM equipos e
                 JOIN ciudades c ON e.cod_ciu = c.cod_ciu
                 WHERE (SELECT SUM(puntos) FROM clasificacion WHERE cod_equipo = e.cod_equipo) > 0
                 ORDER BY puntos DESC
                 LIMIT :limit";
        
        $stmt = $this->executeQuery($query, [':limit' => $limit]);
        return $stmt->fetchAll();
    }
}
?>