<?php
/**
 * Clase para la conexión a la base de datos PostgreSQL
 * Implementación simple usando PDO
 */
class Conexion {
    // Propiedades
    private $host = 'localhost';
    private $usuario = 'postgres';
    private $password = 'tu_contraseña';
    private $baseDatos = 'futsala';
    private $puerto = '5432';
    private $conexion;
    
    /**
     * Constructor - establece la conexión a la base de datos
     */
    public function __construct() {
        try {
            // Crear la conexión a la base de datos con PDO
            $this->conexion = new PDO(
                "pgsql:host={$this->host};port={$this->puerto};dbname={$this->baseDatos}",
                $this->usuario,
                $this->password
            );
            
            // Configurar PDO para que lance excepciones en caso de error
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configurar PDO para que devuelva arrays asociativos
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene la conexión a la base de datos
     * @return PDO
     */
    public function getConexion() {
        return $this->conexion;
    }
    
    /**
     * Cierra la conexión a la base de datos
     */
    public function cerrarConexion() {
        $this->conexion = null;
    }
}
?> 