<?php
/**
 * Clase para la conexión a la base de datos PostgreSQL
 */
class Database {
    private $host = "localhost";
    private $db_name = "futsala";
    private $username = "postgres";
    private $password = "postgres";
    private $conn;

    /**
     * Método para obtener la conexión a la base de datos
     * @return PDO Objeto de conexión PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>