

<?php
/**
 * Clase que envuelve una instancia de la clase PDO
 * para el manejo de la base de datos
 */
/* Conectar a una base de datos ODBC invocando al controlador */
class Database {
    /**
     * Única instancia de la clase
     */
    private $db;
    #Creo el objeto bd y la tabla si no existe
    public function __construct() {
        try {
            //Creo conexion a bd y las tablas
            $this->connect();
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    }
    #Genera la conexion a la bd
    private function connect() {
        try {
            $dsn = 'mysql:host=127.0.0.1;dbname=bd_android;mysql:port=3306;mysql:charset=utf8mb4';
            $usuario = 'root';
            $contraseña = '';
            $this->db = new PDO($dsn, $usuario, $contraseña);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
    public function getOfertas() {
        $consulta = "select * from oferta";
        try {
            return $this->db->query($consulta)->fetchAll();
        } catch (PDOException $e) {
            return false;
        }
    }
    
     public function getOfertaCodigo($codigo) {
        $consulta = 'select * from oferta where codigo='.$codigo;
        try {
            return $this->db->query($consulta)->fetch();
        } catch (PDOException $e) {
            return false;
        }
	}
	
	public function getDeportes() {
		$consulta= "SELECT nombre FROM deporte";
		try {			
			$comando= $this->db->prepare($consulta);
			$comando->execute();			
			return $comando->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return -1;
		}
	}
	
	public function getDB() {
		return $this->db;
	}
}